@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
<div class="max-w-7xl mx-auto py-8 space-y-8">

    <!-- Título -->
    <div>
        <h2 class="text-2xl font-semibold text-gray-800">
            Dashboard
        </h2>
        <p class="text-sm text-gray-500">
            Visão geral do cardioprenatal
        </p>
    </div>

    <!-- Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <!-- Gestantes -->
        <div class="bg-white shadow-lg rounded-2xl p-6">
            <p class="text-sm text-gray-500">Total de Gestantes</p>
            <p class="text-3xl font-bold text-gray-800">{{ $totalGestantes }}</p>
        </div>

        <!-- Consultas -->
        <div class="bg-white shadow-lg rounded-2xl p-6">
            <p class="text-sm text-gray-500">Total de Consultas</p>
            <p class="text-3xl font-bold text-gray-800">{{ $totalConsultas }}</p>
        </div>

        <!-- CHD -->
        <div class="bg-white shadow-lg rounded-2xl p-6">
            <p class="text-sm text-gray-500">Casos de CHD Confirmados</p>
            <p class="text-3xl font-bold text-red-600">{{ $chdConfirmadas }}</p>
        </div>

    </div>

    <!-- Seção de Análise da IA -->
    <div class="bg-white shadow-lg rounded-2xl p-8">
        <div class="flex flex-col md:flex-row justify-between items-start gap-4">
            <div>
                <h3 class="text-xl font-bold text-gray-800">Análise Preditiva com IA</h3>
                <p class="text-gray-500 mt-1">
                    A análise do histórico de dados é iniciada automaticamente para gerar insights e visualizações.
                </p>
            </div>
            <!-- Formulário para iniciar a análise -->
            <form action="{{ route('dashboard.analisar') }}" method="POST">
                @csrf
                <button type="submit" class="bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Iniciar Nova Análise
                </button>
            </form>
        </div>

        <!-- Área de Resultados Dinâmica -->
        <div id="area-resultados-ia" class="mt-8 border-t pt-8" style="display: none;">
            <h3 class="text-xl font-bold text-gray-800 mb-6">Resultados da Análise</h3>

            <!-- Indicador de Carregamento -->
            <div id="loading-indicator" class="text-center py-4">
                <p class="text-gray-600 animate-pulse">Análise em andamento, por favor aguarde...</p>
            </div>

            <!-- Container dos Gráficos -->
            <div id="graficos-container" class="grid grid-cols-1 md:grid-cols-2 gap-8" style="display: none;">
                <!-- Os gráficos serão inseridos aqui pelo JavaScript -->
            </div>

            <!-- Mensagem de Erro -->
            <div id="error-message" class="text-center py-4 text-red-600" style="display: none;">
                Ocorreu um erro ao processar a análise. Tente novamente mais tarde.
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const areaResultados = document.getElementById('area-resultados-ia');
    const loadingIndicator = document.getElementById('loading-indicator');
    const graficosContainer = document.getElementById('graficos-container');
    const errorMessage = document.getElementById('error-message');

    // Se a página foi carregada após iniciar a análise, começa a verificação
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
                        errorMessage.style.display = 'block';
                    }
                    // Se for 'processando' ou 'iniciando', continua esperando
                })
                .catch(() => {
                    clearInterval(intervalId); // Para em caso de erro de rede
                    loadingIndicator.style.display = 'none';
                    errorMessage.style.display = 'block';
                });
        }, 5000); // Verifica a cada 5 segundos
    }

    function renderizarGraficos(resultado) {
        if (!resultado || !resultado.imagens) {
            errorMessage.innerText = 'A análise foi concluída, mas nenhum gráfico foi gerado.';
            errorMessage.style.display = 'block';
            return;
        }

        graficosContainer.innerHTML = ''; // Limpa gráficos antigos
        graficosContainer.style.display = 'grid';

        for (const [titulo, imagem_base64] of Object.entries(resultado.imagens)) {
            const div = document.createElement('div');
            div.className = 'bg-gray-50 p-4 rounded-xl border';
            div.innerHTML = `
                <h4 class="font-semibold text-center mb-2 capitalize">${titulo.replace(/_/g, ' ')}</h4>
                <img src="${imagem_base64}" alt="Gráfico de ${titulo.replace(/_/g, ' ')}" class="w-full h-auto rounded-md">
            `;
            graficosContainer.appendChild(div);
        }
    }
});
</script>
@endpush
@endsection
