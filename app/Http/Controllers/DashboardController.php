<?php

namespace App\Http\Controllers;

use App\Models\Consulta;
use App\Models\Gestante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class DashboardController extends Controller
{
    public function index()
    {
        // Dados para os cards de resumo
        $totalGestantes = Gestante::count();
        $totalConsultas = Consulta::count();
        $chdConfirmadas = Consulta::where('chd_confirmada', true)->count();

        // Lê o arquivo JSON gerado pelo script Python
        $reportPath = base_path('python_api/output/dashboard_data.json');
        $analyticsData = null;

        if (File::exists($reportPath)) {
            $analyticsData = json_decode(File::get($reportPath), true);
        }

        return view('dashboard', [
            'totalGestantes' => $totalGestantes,
            'totalConsultas' => $totalConsultas,
            'chdConfirmadas' => $chdConfirmadas,
            'analyticsData' => $analyticsData
        ]);
    }
}
