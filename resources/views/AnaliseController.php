<?php

namespace App\Http\Controllers;

use App\Jobs\AnalisarDadosIA;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AnaliseController extends Controller
{
    /**
     * Inicia o job de análise de dados em segundo plano.
     */
    public function iniciarAnalise()
    {
        // Limpa o cache antigo antes de iniciar uma nova análise
        Cache::forget('analise_ia_status');
        Cache::forget('resultado_analise_ia');

        AnalisarDadosIA::dispatch();

        return response()->json(['message' => 'A análise foi iniciada com sucesso.'], 202);
    }

    /**
     * Verifica o status atual da análise.
     */
    public function verificarStatus()
    {
        $status = Cache::get('analise_ia_status', 'pendente');

        return response()->json(['status' => $status]);
    }

    /**
     * Retorna o resultado da análise quando concluída.
     */
    public function obterResultado()
    {
        $status = Cache::get('analise_ia_status');

        if ($status !== 'concluido') {
            return response()->json(['message' => 'A análise ainda não foi concluída ou falhou.'], 404);
        }

        $resultado = Cache::get('resultado_analise_ia');

        return response()->json($resultado);
    }
}
