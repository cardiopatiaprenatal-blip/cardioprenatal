<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\File;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Consulta;
use App\Models\Analise;

class AnalisarDadosIA implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        Log::info('Job AnalisarDadosIA criado.');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('Iniciando Job AnalisarDadosIA.');

        // Define status inicial
        Cache::forget('resultado_analise_ia');
        Cache::put('analise_ia_status', 'processando', now()->addMinutes(30));
        Log::info('Status da análise definido como PROCESSANDO.');

        try {

            Log::info('Buscando consultas no banco.');

            $consultas = Consulta::with('gestante')->get();

            Log::info('Consultas carregadas.', [
                'quantidade' => $consultas->count()
            ]);

            // Mapeamento para um formato plano, similar ao outro job.
            // Isso simplifica o processamento no Python com json_normalize.
            $dadosParaJson = $consultas->map(function ($consulta) {
                $dadosConsulta = $consulta->toArray();
                if ($consulta->gestante) {
                    $dadosGestante = $consulta->gestante->toArray();
                    unset($dadosGestante['id']); // Remove o ID da gestante para não sobrescrever o da consulta
                    return array_merge($dadosConsulta, $dadosGestante);
                }
                return $dadosConsulta;
            });

            // Se não houver consultas, o job deve ser concluído com sucesso com um resultado vazio.
            if ($consultas->isEmpty()) {
                Log::info('Nenhuma consulta encontrada. Concluindo o job com resultado vazio.');
                Cache::put('resultado_analise_ia', ['status' => 'concluido', 'histograma_idade' => ['labels' => [], 'values' => []], 'total_diabetes' => 0, 'imagens' => []], now()->addMinutes(30));
                Cache::put('analise_ia_status', 'concluido', now()->addMinutes(30));
                Log::info('Job AnalisarDadosIA concluído com sucesso (sem dados).');
                return;
            }

            // Criar um arquivo CSV temporário
            $tempCsv = tempnam(sys_get_temp_dir(), 'analise_');
            $handle_csv = fopen($tempCsv, 'w');
            
            // Escrever cabeçalho
            fputcsv($handle_csv, array_keys($dadosParaJson->first()));
            
            // Escrever dados
            foreach ($dadosParaJson as $linha) {
                fputcsv($handle_csv, $linha);
            }
            fclose($handle_csv);

            Log::info('Enviando arquivo CSV para FastAPI...');

            // Obtém a URL da API com fallback seguro para evitar avisos de ambiente
            $apiUrl = env('PYTHON_API_URL', 'http://127.0.0.1:5000/analisar');
            
            $response = Http::timeout(120)
                ->attach('file', file_get_contents($tempCsv), 'dados_consultas.csv')
                ->post($apiUrl);

            // Remove o arquivo temporário
            @unlink($tempCsv);

            if ($response->failed()) {
                throw new \Exception('Erro na comunicação com FastAPI: ' . $response->body());
            }

            $resultado_analise = $response->json();

            Log::info('Resposta da API recebida com sucesso.');

            if (!$resultado_analise) {
                Log::warning('Falha ao converter JSON retornado pelo Python.', [
                    'raw_body' => $response->body()
                ]);
                Cache::put('analise_ia_status', 'erro', now()->addMinutes(30));
                return;
            }

            // Salva no banco de dados para que o DashboardController possa ler
            Analise::updateOrCreate(
                ['id' => 1], // Mantém um registro único de análise global
                [
                    'estatistica_geral' => $resultado_analise['tabelas']['estatistica_geral'] ?? [],
                    'analise_risco'     => $resultado_analise['tabelas']['analise_risco'] ?? $resultado_analise['analise_risco'] ?? [],
                    'comorbidades'      => $resultado_analise['tabelas']['comorbidades'] ?? [],
                    'odds_ratio_data'   => $resultado_analise['tabelas']['odds_ratio_data'] ?? $resultado_analise['odds_ratio_data'] ?? [],
                    'fetal_summary'     => $resultado_analise['tabelas']['fetal_summary'] ?? $resultado_analise['fetal_summary'] ?? [],
                    'graficos'          => $resultado_analise['graficos'] ?? $resultado_analise['imagens'] ?? [],
                    'full_response'     => json_encode($resultado_analise),
                    'ultima_atualizacao' => now()
                ]
            );

            Cache::put('resultado_analise_ia', $resultado_analise, now()->addMinutes(30));
            Cache::put('analise_ia_status', 'concluido', now()->addMinutes(30));

            Log::info('Resultado salvo no cache.');
            Log::info('Job AnalisarDadosIA concluído com sucesso.');

        } catch (\Exception $e) {

            Cache::put('analise_ia_status', 'erro', now()->addMinutes(30));

            Log::error('Erro geral no Job AnalisarDadosIA.', [
                'mensagem' => $e->getMessage(),
                'arquivo' => $e->getFile(),
                'linha' => $e->getLine()
            ]);
        }
    }
}
