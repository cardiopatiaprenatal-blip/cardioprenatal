<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Log;
use App\Models\Consulta;

class AnalisarDadosIA implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
        Log::info('Job AnalisarDadosIA criado.');
    }

    public function handle(): void
    {
        Log::info('Iniciando Job AnalisarDadosIA.');

        // Define status inicial
        Cache::put('analise_ia_status', 'processando', now()->addMinutes(30));
        Log::info('Status da análise definido como PROCESSANDO.');

        $pythonPath = 'python';
        $scriptPath = base_path('scripts/analise_ia.py');

        Log::info('Caminho do Python: ' . $pythonPath);
        Log::info('Caminho do script Python: ' . $scriptPath);

        try {

            Log::info('Buscando consultas no banco.');

            $todasAsConsultas = Consulta::with('gestante')->get();

            Log::info('Consultas carregadas.', [
                'quantidade' => $todasAsConsultas->count()
            ]);

            $data = [
                'historico_consultas' => $todasAsConsultas->toArray()
            ];

            Log::info('Dados preparados para envio ao Python.');

            $jsonData = json_encode($data);

            Log::info('Tamanho do JSON enviado para Python.', [
                'bytes' => strlen($jsonData)
            ]);

            $process = new Process([$pythonPath, $scriptPath, $jsonData]);
            $process->setTimeout(300);

            Log::info('Executando script Python...');

            $process->run();

            Log::info('Script Python executado.');

            if (!$process->isSuccessful()) {

                Cache::put('analise_ia_status', 'erro', now()->addMinutes(30));

                Log::error('Erro na execução do Python.', [
                    'erro' => $process->getErrorOutput(),
                    'saida' => $process->getOutput()
                ]);

                return;
            }

            $output = $process->getOutput();

            Log::info('Saída do Python recebida.', [
                'saida_bruta' => $output
            ]);

            $resultado_analise = json_decode($output, true);

            if (!$resultado_analise) {
                Log::warning('Falha ao converter JSON retornado pelo Python.', [
                    'output' => $output
                ]);
            }

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