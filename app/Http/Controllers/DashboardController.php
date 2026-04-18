<?php

namespace App\Http\Controllers;

use App\Models\Analise;
use App\Models\Consulta;
use App\Models\Gestante;

class DashboardController extends Controller
{
    public function index()
    {

        $analiseGeral = Analise::first();

        return view('dashboard', [
            'totalGestantes' => Gestante::count(),
            'totalConsultas' => Consulta::count(),
            'chdConfirmadas' => Consulta::where('chd_confirmada', true)->count(),
            'analise' => $analiseGeral,
        ]);
    }
}