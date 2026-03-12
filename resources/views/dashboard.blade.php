@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">

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
        <div class="bg-white shadow rounded-lg p-6">
            <p class="text-sm text-gray-500">Gestantes</p>
            <p class="text-3xl font-bold text-blue-600">
                {{ $totalGestantes }}
            </p>
        </div>

        <!-- Consultas -->
        <div class="bg-white shadow rounded-lg p-6">
            <p class="text-sm text-gray-500">Consultas</p>
            <p class="text-3xl font-bold text-green-600">
                {{ $totalConsultas }}
            </p>
        </div>

        <!-- CHD -->
        <div class="bg-white shadow rounded-lg p-6">
            <p class="text-sm text-gray-500">CHD confirmadas</p>
            <p class="text-3xl font-bold text-red-600">
                {{ $chdConfirmadas }}
            </p>
        </div>

    </div>

    <!-- Ações rápidas -->
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">
            Ações rápidas
        </h3>

        <div class="flex flex-col md:flex-row gap-4">
            <a href="{{ route('consultas.import') }}"
               class="flex-1 text-center bg-blue-600 text-white py-3 rounded hover:bg-blue-700 transition">
                Importar CSV
            </a>

            <a href="{{ route('gestantes.index') }}"
               class="flex-1 text-center bg-gray-600 text-white py-3 rounded hover:bg-gray-700 transition">
                Ver Gestantes
            </a>
        </div>
    </div>

    <!-- Seção de Análise Estatística -->
    @if($analyticsData)
        <div class="bg-white shadow rounded-lg p-6 mt-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">
                Análise Estatística
            </h3>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Gráfico de Distribuição de Idade -->
                <div>
                    <h4 class="font-semibold text-gray-600">Distribuição de Idade</h4>
                    <canvas id="distIdadeChart" class="mt-2"></canvas>
                </div>

                <!-- Gráfico de Boxplot do IMC -->
                <div>
                    <h4 class="font-semibold text-gray-600">IMC por Confirmação de CHD</h4>
                    <canvas id="imcChart" class="mt-2"></canvas>
                </div>
            </div>
        </div>
    @else
        <div class="bg-white shadow rounded-lg p-6 mt-6 text-center">
            <p class="text-gray-600">
                O relatório de análise ainda está sendo gerado. Por favor, atualize a página em alguns minutos.
            </p>
        </div>
    @endif

</div>
@endsection

@push('scripts')
{{-- 1. Inclui a biblioteca Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
{{-- 2. Inclui o plugin para BoxPlot --}}
<script src="https://unpkg.com/chart.js-chart-box-and-violin-plot/build/Chart.BoxPlot.js"></script>

@if($analyticsData)
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const analyticsData = @json($analyticsData);

        // Gráfico 1: Distribuição de Idade (Histograma)
        if (analyticsData.graficos && analyticsData.graficos.distribuicao_idade) {
            const distIdadeCtx = document.getElementById('distIdadeChart').getContext('2d');
            new Chart(distIdadeCtx, {
                type: 'bar',
                data: {
                    labels: analyticsData.graficos.distribuicao_idade.labels,
                    datasets: [{
                        label: 'Número de Gestantes',
                        data: analyticsData.graficos.distribuicao_idade.values,
                        backgroundColor: 'rgba(54, 162, 235, 0.6)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                }
            });
        }

        // Gráfico 2: IMC por CHD (BoxPlot)
        if (analyticsData.graficos && analyticsData.graficos.imc_por_chd) {
            const imcCtx = document.getElementById('imcChart').getContext('2d');
            new Chart(imcCtx, {
                type: 'boxplot', // Este tipo é fornecido pelo plugin
                data: {
                    labels: ['Sem CHD', 'Com CHD'],
                    datasets: [{
                        label: 'Distribuição de IMC',
                        data: [
                            analyticsData.graficos.imc_por_chd.sem_chd,
                            analyticsData.graficos.imc_por_chd.com_chd
                        ],
                        backgroundColor: 'rgba(255, 99, 132, 0.6)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1,
                        itemRadius: 2, // Raio dos pontos outliers
                    }]
                }
            });
        }
    });
</script>
@endif
@endpush
