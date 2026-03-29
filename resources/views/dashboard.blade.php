@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500;600&display=swap');

    :root {
        --primary:      #7f0c1a;
        --accent:       #c0392b;
        --accent-mid:   #e74c3c;
        --accent-light: #fdf0f0;
        --success:      #27ae60;
        --text:         #1c1a1a;
        --muted:        #8a6f6f;
        --border:       #f0d5d5;
        --surface:      #ffffff;
        --bg:           #faf4f4;
    }

    .dashboard-wrapper { animation: fadeIn 0.6s ease-out; font-family: 'DM Sans', sans-serif; }
    
    .page-header { margin-bottom: 30px; }
    .page-title { font-family: 'DM Serif Display', serif; font-size: 32px; color: var(--primary); letter-spacing: -0.5px; }
    .page-subtitle { font-size: 14px; color: var(--muted); }

    /* Grid de Estatísticas Rápidas */
    .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px; }
    .card-stat { 
        background: var(--surface); padding: 25px; border-radius: 20px; border: 1px solid var(--border);
        box-shadow: 0 4px 15px rgba(127,12,26,0.05); transition: transform 0.3s;
    }
    .card-stat:hover { transform: translateY(-5px); }
    .stat-label { font-size: 12px; font-weight: 700; color: var(--muted); text-transform: uppercase; }
    .stat-value { font-family: 'DM Serif Display', serif; font-size: 36px; color: var(--text); margin-top: 8px; }
    .stat-value.danger { color: var(--accent); }

    /* Seções de Dados */
    .data-section { background: var(--surface); border-radius: 24px; padding: 30px; border: 1px solid var(--border); margin-bottom: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.03); }
    .section-title { font-family: 'DM Serif Display', serif; font-size: 22px; color: var(--primary); margin-bottom: 20px; border-bottom: 2px solid var(--accent-light); padding-bottom: 10px; }

    /* Tabela Estilizada */
    .table-responsive { overflow-x: auto; border-radius: 12px; border: 1px solid var(--border); }
    table { width: 100%; border-collapse: collapse; font-size: 14px; }
    th { background: var(--accent-light); color: var(--primary); padding: 12px; text-align: center; font-weight: 600; }
    td { padding: 12px; border-top: 1px solid var(--border); text-align: center; color: var(--text); }
    .text-left { text-align: left; padding-left: 20px; font-weight: 600; }

    /* Cards de Risco e Comorbidades */
    .grid-info { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; }
    .info-card { background: #fdfafa; padding: 20px; border-radius: 18px; border: 1px solid var(--border); }
    .info-card h4 { color: var(--primary); font-weight: 600; margin-bottom: 10px; text-transform: capitalize; }
    .info-item { font-size: 13px; display: flex; justify-content: space-between; margin-bottom: 5px; padding: 4px 0; border-bottom: 1px dashed #eee; }

    /* Gráficos */
    .plots-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 25px; margin-top: 20px; }
    .plot-item { background: white; padding: 15px; border-radius: 20px; border: 1px solid var(--border); text-align: center; box-shadow: 0 4px 10px rgba(0,0,0,0.02); }
    .plot-item img { width: 100%; border-radius: 12px; margin-top: 10px; }

    /* Botões de Ação */
    .action-bar { display: flex; gap: 15px; margin-top: 20px; }
    .btn-action { flex: 1; padding: 15px; border-radius: 12px; text-align: center; font-weight: 600; text-decoration: none; transition: 0.3s; }
    .btn-blue { background: #3498db; color: white; }
    .btn-gray { background: #95a5a6; color: white; }
    .btn-action:hover { opacity: 0.9; transform: translateY(-2px); }

    @keyframes fadeIn { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
</style>

<div class="dashboard-wrapper">
    <header class="page-header">
        <h1 class="page-title">Dashboard</h1>
        <p class="page-subtitle">Análise Inteligente Cardioprenatal</p>
    </header>

    <div class="stats-grid">
        <div class="card-stat">
            <span class="stat-label">Total Gestantes</span>
            <div class="stat-value">{{ $totalGestantes }}</div>
        </div>
        <div class="card-stat">
            <span class="stat-label">Consultas</span>
            <div class="stat-value">{{ $totalConsultas }}</div>
        </div>
        <div class="card-stat">
            <span class="stat-label">Casos de CHD</span>
            <div class="stat-value danger">{{ $chdConfirmadas }}</div>
        </div>
    </div>

    <div class="data-section">
        <h3 class="section-title">Estatística Geral da Base</h3>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th class="text-left">Variável</th>
                        <th>Média</th><th>Std</th><th>Mín</th><th>P50</th><th>Máx</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($analise->estatistica_geral as $item)
                    <tr>
                        <td class="text-left">{{ str_replace('_', ' ', $item['index']) }}</td>
                        <td>{{ number_format((float)$item['mean'], 2) }}</td>
                        <td>{{ number_format((float)$item['std'], 2) }}</td>
                        <td>{{ $item['min'] }}</td>
                        <td>{{ $item['50%'] }}</td>
                        <td>{{ $item['max'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="grid-info">
        <div class="data-section">
            <h3 class="section-title">Análise de Risco</h3>
            <div style="display: flex; flex-direction: column; gap: 15px;">
                @foreach ($analise->analise_risco as $grupo)
                <div class="info-card">
                    <h4>{{ $grupo['grupo_de_risco'] }}</h4>
                    <div class="info-item"><span>Pressão Sistólica:</span> <strong>{{ $grupo['pressao_sistolica_mean'] }}</strong></div>
                    <div class="info-item"><span>FC Fetal:</span> <strong>{{ $grupo['frequencia_cardiaca_fetal_mean'] }}</strong></div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="data-section">
            <h3 class="section-title">Impacto de Comorbidades</h3>
            <div style="display: flex; flex-direction: column; gap: 15px;">
                @foreach ($analise->comorbidades as $nome => $dados)
                <div class="info-card">
                    <h4>{{ str_replace('_', ' ', $nome) }}</h4>
                    @foreach ($dados as $item)
                        <div class="info-item"><span>Idade Gest.:</span> <strong>{{ $item['idade_gestacional_mean'] }} sem</strong></div>
                    @endforeach
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="data-section">
        <h3 class="section-title">Gráficos Analíticos</h3>
        <div class="plots-grid">
            @foreach ($analise->graficos as $nome => $img)
                @if ($img)
                <div class="plot-item">
                    <h5 style="color: var(--primary); font-family: 'DM Serif Display';">{{ str_replace('_', ' ', $nome) }}</h5>
                    <img src="data:image/png;base64,{{ $img }}" />
                </div>
                @endif
            @endforeach
        </div>
    </div>

    <div class="data-section">
        <h3 class="section-title">Ações do Sistema</h3>
        <div class="action-bar">
            <a href="{{ route('consultas.import') }}" class="btn-action btn-blue">Importar Dados CSV</a>
            <a href="{{ route('gestantes.index') }}" class="btn-action btn-gray">Gerenciar Gestantes</a>
        </div>
    </div>
</div>

@endsection