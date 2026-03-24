<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConsultaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GestanteController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {

    if (Auth::check()) {
        return redirect()->route('dashboard');
    }

    return view('auth.login');
})->name('login');


Route::post('login', [AuthController::class, 'login'])->name('auth');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// ROTAS PROTEGIDAS POR LOGIN
Route::group(['middleware' => 'auth'], function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::post('/dashboard/analisar', [DashboardController::class, 'analisar'])
        ->name('dashboard.analisar');

    Route::get('/dashboard/verificar-analise', [DashboardController::class, 'verificarAnalise'])
        ->name('dashboard.verificarAnalise');

    Route::resource('gestantes', GestanteController::class);

    // Rotas de Consulta
    Route::get('/consultas/import', [ConsultaController::class, 'index'])->name('consultas.import');
    Route::post('/consultas/import', [ConsultaController::class, 'import'])->name('consultas.import.store');
    Route::get('/consultas/create/{id}', [ConsultaController::class, 'create'])->name('consultas.create');
    Route::post('/consultas/{id}', [ConsultaController::class, 'store'])->name('consultas.store');
});

Route::get('/register', [AuthController::class, 'registerIndex'])->name('register');
Route::post('/register', [AuthController::class, 'register']);