@extends('layouts.app')

@section('title', 'Gestante')

@section('content')
<div class="min-h-screen bg-gray-100 py-8">
    <div class="max-w-6xl mx-auto space-y-8">

        <!-- Cabeçalho -->
        <div class="bg-white shadow-lg rounded-2xl p-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                <div>
                    <h2 class="text-2xl font-bold text-gray-800">
                        Gestante {{ $gestante->gestante_id }}
                    </h2>
                    <p class="text-gray-500 text-sm mt-1">
                        Data de Nascimento:
                        <span class="font-semibold text-gray-700">
                            {{ $gestante->data_nascimento ? \Carbon\Carbon::parse($gestante->data_nascimento)->format('d/m/Y') : 'Não informada' }}
                        </span>
                    </p>
                    <p class="text-gray-500 text-sm mt-1">
                        Total de consultas: 
                        <span class="font-semibold text-gray-700">
                            {{ $gestante->consultas->count() }}
                        </span>
                    </p>
                </div>

                <a href="{{ route('consultas.create', ['id' => $gestante->id]) }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-green-600 text-white rounded-xl shadow hover:bg-green-700 transition">
                    + Nova Consulta
                </a>
            </div>
        </div>

        <!-- Consultas -->
        @forelse ($gestante->consultas as $consulta)
            <div class="bg-white shadow-md hover:shadow-lg transition rounded-2xl p-8 space-y-8">

                <!-- Cabeçalho da consulta -->
                 <div class="flex items-center gap-3">

                            <span class="px-4 py-1.5 rounded-full text-xs font-semibold
                                {{ $consulta->chd_confirmada 
                                    ? 'bg-red-100 text-red-700' 
                                    : 'bg-green-100 text-green-700' }}">
                                CHD {{ $consulta->chd_confirmada ? 'Confirmada' : 'Não confirmada' }}
                            </span>

                            <a href="{{ url('consultas/' . $consulta->id . '/edit') }}"
                            class="px-4 py-1.5 bg-blue-600 text-white text-xs rounded-lg hover:bg-blue-700 transition">
                                Editar
                            </a>

                </div>
                <!-- Grid principal -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                    <!-- Bloco padrão -->
                    @php
                        $card = "bg-gray-50 border p-5 rounded-xl space-y-4";
                        $title = "text-xs font-semibold text-gray-500 uppercase tracking-wider";
                        $idade = null;
                        if ($gestante->data_nascimento && $consulta->data_consulta) {
                            $idade = \Carbon\Carbon::parse($consulta->data_consulta)->diffInYears(\Carbon\Carbon::parse($gestante->data_nascimento));
                        }
                    @endphp

                    <!-- Dados da Gestante -->
                    <div class="{{ $card }}">
                        <h4 class="{{ $title }}">Dados da Gestante</h4>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <p><span class="text-gray-400">Idade na consulta</span><br><strong>{{ $idade !== null ? $idade . ' anos' : 'N/A' }}</strong></p>
                            <p><span class="text-gray-400">Idade Gestacional</span><br><strong>{{ $consulta->idade_gestacional }} sem</strong></p>
                            <p><span class="text-gray-400">Altura</span><br><strong>{{ $consulta->altura }} cm</strong></p>
                            <p><span class="text-gray-400">Peso</span><br><strong>{{ $consulta->peso }} kg</strong></p>
                        </div>
                    </div>

                    <!-- Sinais Vitais -->
                    <div class="{{ $card }}">
                        <h4 class="{{ $title }}">Sinais Vitais</h4>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <p><span class="text-gray-400">Pressão Sistólica</span><br><strong>{{ $consulta->pressao_sistolica }}</strong></p>
                            <p><span class="text-gray-400">BPM Materno</span><br><strong>{{ $consulta->bpm_materno }}</strong></p>
                            <p><span class="text-gray-400">Saturação</span><br><strong>{{ $consulta->saturacao }}%</strong></p>
                            <p><span class="text-gray-400">Temperatura</span><br><strong>{{ $consulta->temperatura_corporal }} °C</strong></p>
                        </div>
                    </div>

                    <!-- Dados Laboratoriais -->
                    <div class="{{ $card }}">
                        <h4 class="{{ $title }}">Dados Laboratoriais</h4>
                        <div class="grid grid-cols-3 gap-4 text-sm">
                            <p><span class="text-gray-400">Glic. Jejum</span><br><strong>{{ $consulta->glicemia_jejum ?? 'N/A' }}</strong></p>
                            <p><span class="text-gray-400">Glic. Pós</span><br><strong>{{ $consulta->glicemia_pos_prandial ?? 'N/A' }}</strong></p>
                            <p><span class="text-gray-400">HbA1c</span><br><strong>{{ $consulta->hba1c ? $consulta->hba1c.'%' : 'N/A' }}</strong></p>
                        </div>
                    </div>

                    <!-- Dados Fetais -->
                    <div class="{{ $card }}">
                        <h4 class="{{ $title }}">Dados Fetais</h4>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <p><span class="text-gray-400">FC Fetal</span><br><strong>{{ $consulta->frequencia_cardiaca_fetal }}</strong></p>
                            <p><span class="text-gray-400">Circ. Cefálica</span><br><strong>{{ $consulta->circunferencia_cefalica_fetal_mm }} mm</strong></p>
                            <p><span class="text-gray-400">Circ. Abdominal</span><br><strong>{{ $consulta->circunferencia_abdominal_mm }} mm</strong></p>
                            <p><span class="text-gray-400">Comp. Fêmur</span><br><strong>{{ $consulta->comprimento_femur_mm }} mm</strong></p>
                            <p><span class="text-gray-400">TN</span><br><strong>{{ $consulta->translucencia_nucal_mm }} mm</strong></p>
                        </div>
                    </div>

                </div>

                <!-- Avaliação Cardíaca -->
                <div class="bg-gray-50 border rounded-xl p-6 space-y-4">
                    <h4 class="{{ $title }}">Avaliação Cardíaca</h4>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-sm">
                        <p><span class="text-gray-400">Doppler Ducto Venoso</span><br><strong>{{ $consulta->doppler_ducto_venoso }}</strong></p>
                        <p><span class="text-gray-400">Eixo Cardíaco</span><br><strong>{{ $consulta->eixo_cardiaco }}</strong></p>
                        <p><span class="text-gray-400">Quatro Câmaras</span><br><strong>{{ $consulta->quatro_camaras }}</strong></p>
                    </div>

                    @if ($consulta->chd_confirmada && $consulta->tipo_chd)
                        <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-lg text-sm font-semibold">
                            Tipo de CHD: {{ $consulta->tipo_chd }}
                        </div>
                    @endif
                </div>

            </div>
        @empty
            <div class="bg-white shadow rounded-2xl p-8 text-center text-gray-500">
                Nenhuma consulta registrada.
            </div>
        @endforelse

    </div>
</div>
@endsection