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

    /* Cabeçalho da página */
    .page-header { margin-bottom: 32px; }

    .page-title {
        font-family: 'DM Serif Display', serif;
        font-size: 28px;
        color: var(--primary);
        letter-spacing: -.3px;
    }

    .page-subtitle { font-size: 14px; color: var(--muted); margin-top: 4px; }

    /* Grid de cards */
    .cards-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin-bottom: 28px;
    }

    /* Card estatística */
    .stat-card {
        background: var(--surface);
        border-radius: 18px;
        padding: 24px;
        box-shadow: 0 2px 4px rgba(127,12,26,.04), 0 8px 24px rgba(127,12,26,.08);
        border: 1px solid var(--border);
        display: flex;
        flex-direction: column;
        gap: 12px;
        transition: transform .2s, box-shadow .2s;
        animation: cardIn .5s cubic-bezier(.22,.97,.58,1) both;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(127,12,26,.06), 0 12px 32px rgba(127,12,26,.12);
    }

    .stat-card:nth-child(1) { animation-delay: .05s; }
    .stat-card:nth-child(2) { animation-delay: .10s; }
    .stat-card:nth-child(3) { animation-delay: .15s; }

    @keyframes cardIn {
        from { opacity: 0; transform: translateY(16px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .stat-card-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .stat-card-label { font-size: 13px; font-weight: 500; color: var(--muted); }

    .stat-card-icon {
        width: 40px; height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .stat-card-icon svg { width: 20px; height: 20px; }

    .icon-red   { background: var(--accent-light); color: var(--accent); }
    .icon-rose  { background: #fff0f3; color: #e05; }
    .icon-green { background: #edfbf3; color: var(--success); }

    .stat-card-value {
        font-family: 'DM Serif Display', serif;
        font-size: 36px;
        color: var(--text);
        line-height: 1;
        letter-spacing: -.5px;
    }

    .stat-card-value.danger { color: var(--accent); }

    /* Card IA */
    .ia-card {
        background: var(--surface);
        border-radius: 18px;
        padding: 32px;
        box-shadow: 0 2px 4px rgba(127,12,26,.04), 0 8px 24px rgba(127,12,26,.08);
        border: 1px solid var(--border);
        animation: cardIn .5s .20s cubic-bezier(.22,.97,.58,1) both;
    }

    .ia-card-top {
        display: flex;
        flex-direction: row;
        align-items: flex-start;
        justify-content: space-between;
        gap: 24px;
        flex-wrap: wrap;
    }

    .ia-card-info { flex: 1; }

    .ia-card-title {
        font-family: 'DM Serif Display', serif;
        font-size: 20px;
        color: var(--primary);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .ia-card-title svg { width: 20px; height: 20px; color: var(--accent); }

    .ia-card-desc { font-size: 14px; color: var(--muted); margin-top: 6px; line-height: 1.5; }

    /* Botão análise */
    .btn-analisar {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 11px 22px;
        background: linear-gradient(135deg, var(--primary), var(--accent-mid));
        color: #fff;
        border: none;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        font-family: 'DM Sans', sans-serif;
        cursor: pointer;
        box-shadow: 0 4px 14px rgba(192,57,43,.30);
        transition: opacity .2s, transform .15s;
        white-space: nowrap;
    }

    .btn-analisar:hover { opacity: .9; transform: translateY(-1px); }
    .btn-analisar:active { transform: translateY(0); }
    .btn-analisar svg { width: 16px; height: 16px; }

    /* Divisor */
    .ia-divider {
        height: 1px;
        background: var(--border);
        margin: 28px 0;
    }

    /* Área resultados */
    .resultados-title {
        font-family: 'DM Serif Display', serif;
        font-size: 18px;
        color: var(--primary);
        margin-bottom: 20px;
    }

    /* Loading */
    .loading-wrap {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 12px;
        padding: 32px 0;
    }

    .loading-heart {
        width: 40px; height: 40px;
        color: var(--accent);
        animation: heartbeat .8s ease-in-out infinite;
    }

    @keyframes heartbeat {
        0%, 100% { transform: scale(1); }
        50%       { transform: scale(1.2); }
    }

    .loading-text { font-size: 14px; color: var(--muted); }

    /* Gráficos */
    .graficos-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }

    .grafico-item {
        background: var(--accent-light);
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 16px;
    }

    .grafico-item h4 {
        font-size: 13px;
        font-weight: 600;
        color: var(--primary);
        text-align: center;
        margin-bottom: 10px;
        text-transform: capitalize;
    }

    .grafico-item img { width: 100%; height: auto; border-radius: 8px; }

    /* Erro */
    .error-wrap {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 20px;
        color: var(--accent);
        font-size: 14px;
        font-weight: 500;
    }

    .error-wrap svg { width: 18px; height: 18px; }

    @media (max-width: 768px) {
        .cards-grid { grid-template-columns: 1fr; }
        .graficos-grid { grid-template-columns: 1fr; }
        .ia-card-top { flex-direction: column; }
        .btn-analisar { width: 100%; justify-content: center; }
    }
</style>

<!-- Cabeçalho -->
<div class="page-header">
    <h2 class="page-title">Visão Geral</h2>
    <p class="page-subtitle">Acompanhe os dados do Cardioprenatal em tempo real</p>
</div>

<!-- Cards de estatísticas -->
<div class="cards-grid">

    <div class="stat-card">
        <div class="stat-card-top">
            <span class="stat-card-label">Total de Gestantes</span>
            <div class="stat-card-icon icon-red">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                </svg>
            </div>
        </div>
        <div class="stat-card-value">{{ $totalGestantes }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-card-top">
            <span class="stat-card-label">Total de Consultas</span>
            <div class="stat-card-icon icon-rose">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>
                </svg>
            </div>
        </div>
        <div class="stat-card-value">{{ $totalConsultas }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-card-top">
            <span class="stat-card-label">Casos de CHD Confirmados</span>
            <div class="stat-card-icon icon-green">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 12h-4l-3 9L9 3l-3 9H2"/>
                </svg>
            </div>
        </div>
        <div class="stat-card-value danger">{{ $chdConfirmadas }}</div>
    </div>

</div>

<!-- Card IA -->
<div class="ia-card">
    <div class="ia-card-top">
        <div class="ia-card-info">
            <h3 class="ia-card-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/>
                </svg>
                Análise Preditiva com IA
            </h3>
            <p class="ia-card-desc">
                A análise do histórico de dados é iniciada automaticamente para gerar insights e visualizações.
            </p>
        </div>

        <form action="{{ route('dashboard.analisar') }}" method="POST">
            @csrf
            <button type="submit" class="btn-analisar">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polygon points="5 3 19 12 5 21 5 3"/>
                </svg>
                Iniciar Nova Análise
            </button>
        </form>
    </div>

    <!-- Área de Resultados -->
    <div id="area-resultados-ia" style="display: none;">
        <div class="ia-divider"></div>
        <h3 class="resultados-title">Resultados da Análise</h3>

        <div id="loading-indicator" class="loading-wrap">
            <svg class="loading-heart" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
            </svg>
            <p class="loading-text">Análise em andamento, por favor aguarde...</p>
        </div>

        <div id="graficos-container" class="graficos-grid" style="display: none;"></div>

        <div id="error-message" class="error-wrap" style="display: none;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
            Ocorreu um erro ao processar a análise. Tente novamente mais tarde.
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const areaResultados    = document.getElementById('area-resultados-ia');
    const loadingIndicator  = document.getElementById('loading-indicator');
    const graficosContainer = document.getElementById('graficos-container');
    const errorMessage      = document.getElementById('error-message');

    @if(session('analise_iniciada'))
        areaResultados.style.display = 'block';
        verificarStatusAnalise();
    @endif

    function verificarStatusAnalise() {
        const intervalId = setInterval(() => {
            fetch('{{ route("dashboard.verificarAnalise") }}')
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'concluido') {
                        clearInterval(intervalId);
                        loadingIndicator.style.display = 'none';
                        renderizarGraficos(data.resultado);
                    } else if (data.status === 'erro') {
                        clearInterval(intervalId);
                        loadingIndicator.style.display = 'none';
                        errorMessage.style.display = 'flex';
                    }
                })
                .catch(() => {
                    clearInterval(intervalId);
                    loadingIndicator.style.display = 'none';
                    errorMessage.style.display = 'flex';
                });
        }, 5000);
    }

    function renderizarGraficos(resultado) {
        if (!resultado || !resultado.imagens) {
            errorMessage.innerText = 'A análise foi concluída, mas nenhum gráfico foi gerado.';
            errorMessage.style.display = 'flex';
            return;
        }

        graficosContainer.innerHTML = '';
        graficosContainer.style.display = 'grid';

        for (const [titulo, imagem_base64] of Object.entries(resultado.imagens)) {
            const div = document.createElement('div');
            div.className = 'grafico-item';
            div.innerHTML = `
                <h4>${titulo.replace(/_/g, ' ')}</h4>
                <img src="${imagem_base64}" alt="Gráfico de ${titulo.replace(/_/g, ' ')}">
            `;
            graficosContainer.appendChild(div);
        }
    }
});
</script>
@endpush

@endsection