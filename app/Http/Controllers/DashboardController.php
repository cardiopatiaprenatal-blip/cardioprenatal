<?php

namespace App\Http\Controllers;

use App\Jobs\AnalisarDadosIA;
use App\Models\Consulta;
use App\Models\Gestante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\Process\Process;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard', [
            'totalGestantes' => Gestante::count(),
            'totalConsultas' => Consulta::count(),
            'chdConfirmadas' => Consulta::where('chd_confirmada', true)->count(),
        ]);
    }

    public function analisar()
    {
        $this->iniciarAnalise();

        // Redireciona de volta imediatamente com uma mensagem de sucesso
        return redirect()
            ->route('dashboard')
            ->with('analise_iniciada', 'A análise foi iniciada em segundo plano. Os resultados aparecerão em breve.');
    }

    public function verificarAnalise()
    {
        $status = Cache::get('analise_ia_status', 'ocioso');
        $resultado = ($status === 'concluido') ? Cache::get('resultado_analise_ia') : null;

        return response()->json(['status' => $status, 'resultado' => $resultado]);
    }

    /**
     * Método privado para encapsular a lógica de início da análise.
     */
    private function iniciarAnalise(): void
    {
        Cache::forget('resultado_analise_ia');
        Cache::put('analise_ia_status', 'iniciando', now()->addMinutes(30));
        AnalisarDadosIA::dispatch();
    }
}
