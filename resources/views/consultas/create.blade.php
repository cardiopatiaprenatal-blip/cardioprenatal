@extends('layouts.app')

@section('title', 'Nova Consulta')

@section('content')

<div class="max-w-7xl mx-auto bg-white shadow-lg rounded-xl p-10">

    <h2 class="text-3xl font-bold mb-10 text-gray-800">
        Cadastrar Nova Consulta
    </h2>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl relative mb-8" role="alert">
            <strong class="font-bold">Opa! Encontramos alguns erros:</strong>
            <ul class="mt-3 list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('consultas.store', ['id' => $gestante->id]) }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">

            <!-- COLUNA 1 -->
            <div class="bg-gray-50 p-6 rounded-xl shadow-sm space-y-6">

                <h3 class="text-lg font-semibold border-b pb-2 text-gray-700">Dados da Consulta</h3>

                <div>
                    <label class="block text-sm font-medium mb-1">Data da Consulta</label>
                    <input type="date" name="data_consulta"
                        value="{{ old('data_consulta', date('Y-m-d')) }}"
                        class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-400" required>
                </div>

                <h3 class="text-lg font-semibold border-b pt-4 pb-2 text-gray-700">Dados Maternos</h3>

                <div>
                    <label class="block text-sm font-medium mb-1">Idade</label>
                    <input type="number" name="idade"
                        min="10" max="49"
                        value="{{ old('idade', $gestante->idade) }}"
                        class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-400" required>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Idade Gestacional (semanas)</label>
                    <input type="number" name="idade_gestacional"
                        min="4" max="42"
                        value="{{ old('idade_gestacional') }}"
                        class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-400" required>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Altura (cm)</label>
                    <input type="number" name="altura"
                        min="140" max="190"
                        value="{{ old('altura') }}"
                        class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-400">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Peso (kg)</label>
                    <input type="number" step="0.1" name="peso"
                        min="30" max="300"
                        value="{{ old('peso') }}"
                        class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-400">
                </div>

            </div>

            <!-- COLUNA 2 -->
            <div class="bg-gray-50 p-6 rounded-xl shadow-sm space-y-6">

                <h3 class="text-lg font-semibold border-b pb-2 text-gray-700">Sinais Vitais</h3>

                <div>
                    <label class="block text-sm font-medium mb-1">Pressão Sistólica</label>
                    <input type="number" name="pressao_sistolica"
                        min="50" max="300"
                        value="{{ old('pressao_sistolica') }}"
                        class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-400">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">BPM Materno</label>
                    <input type="number" name="bpm_materno"
                        min="20" max="300"
                        value="{{ old('bpm_materno') }}"
                        class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-400">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Saturação O₂ (%)</label>
                    <input type="number" name="saturacao"
                        min="50" max="100"
                        value="{{ old('saturacao') }}"
                        class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-400">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Temperatura (°C)</label>
                    <input type="number" step="0.1" name="temperatura_corporal"
                        min="30" max="42"
                        value="{{ old('temperatura_corporal') }}"
                        class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-400">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Obesidade Pré-Gestacional</label>
                    <select name="obesidade_pre_gestacional" class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-400">
                        <option value="0" {{ old('obesidade_pre_gestacional') == 0 ? 'selected' : '' }}>Não</option>
                        <option value="1" {{ old('obesidade_pre_gestacional') == 1 ? 'selected' : '' }}>Sim</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Diabetes Gestacional</label>
                    <select name="diabetes_gestacional" class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-400">
                        <option value="0" {{ old('diabetes_gestacional') == 0 ? 'selected' : '' }}>Não</option>
                        <option value="1" {{ old('diabetes_gestacional') == 1 ? 'selected' : '' }}>Sim</option>
                    </select>
                </div>

            </div>

            <!-- COLUNA 3 -->
            <div class="bg-gray-50 p-6 rounded-xl shadow-sm space-y-6">

                <h3 class="text-lg font-semibold border-b pb-2 text-gray-700">Dados Laboratoriais</h3>

                <div>
                    <label class="block text-sm font-medium mb-1">Glicemia Jejum</label>
                    <input type="number" step="0.1" name="glicemia_jejum"
                        min="20" max="1000"
                        value="{{ old('glicemia_jejum') }}"
                        class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-400">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Glicemia Pós-Prandial</label>
                    <input type="number" step="0.1" name="glicemia_pos_prandial"
                        min="20" max="1000"
                        value="{{ old('glicemia_pos_prandial') }}"
                        class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-400">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">HbA1c (%)</label>
                    <input type="number" step="0.1" name="hba1c"
                        min="3" max="20"
                        value="{{ old('hba1c') }}"
                        class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-400">
                </div>

            </div>

            <!-- COLUNA 4 -->
            <div class="bg-gray-50 p-6 rounded-xl shadow-sm space-y-6">

                <h3 class="text-lg font-semibold border-b pb-2 text-gray-700">Dados Fetais</h3>

                <div>
                    <label class="block text-sm font-medium mb-1">FC Fetal (bpm)</label>
                    <input type="number" name="frequencia_cardiaca_fetal"
                        min="0" max="300"
                        value="{{ old('frequencia_cardiaca_fetal') }}"
                        class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-400">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Circ. Cefálica (mm)</label>
                    <input type="number" step="0.1" name="circunferencia_cefalica_fetal_mm"
                        min="20" max="380"
                        value="{{ old('circunferencia_cefalica_fetal_mm') }}"
                        class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-400">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Circ. Abdominal (mm)</label>
                    <input type="number" step="0.1" name="circunferencia_abdominal_mm"
                        min="20" max="380"
                        value="{{ old('circunferencia_abdominal_mm') }}"
                        class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-400">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Comp. do Fêmur (mm)</label>
                    <input type="number" step="0.1" name="comprimento_femur_mm"
                        min="2" max="80"
                        value="{{ old('comprimento_femur_mm') }}"
                        class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-400">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Translucência Nucal (mm)</label>
                    <input type="number" step="0.1" name="translucencia_nucal_mm"
                        min="0.5" max="6"
                        value="{{ old('translucencia_nucal_mm') }}"
                        class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-400">
                </div>

                <h3 class="text-lg font-semibold border-b pt-4 pb-2 text-gray-700">
                    Avaliação Cardíaca Fetal
                </h3>
<div>
    <label class="block text-sm font-medium mb-1">Doppler Ducto Venoso</label>
    <select name="doppler_ducto_venoso"
        class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-400
        @error('doppler_ducto_venoso') border-red-500 ring-red-400 @enderror"
        required>

        <option value="">Selecione...</option>

            <option value="Ausente"
                {{ old('doppler_ducto_venoso') == 'Ausente' ? 'selected' : '' }}>
                Ausente
            </option>

            <option value="Fluxo normal"
                {{ old('doppler_ducto_venoso') == 'Fluxo normal' ? 'selected' : '' }}>
                Fluxo normal
            </option>

            <option value="Fluxo aumentado"
                {{ old('doppler_ducto_venoso') == 'Fluxo aumentado' ? 'selected' : '' }}>
                Fluxo aumentado
            </option>

            <option value="Fluxo reverso"
                {{ old('doppler_ducto_venoso') == 'Fluxo reverso' ? 'selected' : '' }}>
                Fluxo reverso
            </option>

        </select>
    </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Eixo Cardíaco (°)</label>
                    <input type="number" name="eixo_cardiaco"
                        min="0" max="180"
                        value="{{ old('eixo_cardiaco') }}"
                        class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-400">
                </div>

               <div>
                <label class="block text-sm font-medium mb-1">Quatro Câmaras</label>
                <select name="quatro_camaras"
                    class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-400
                    @error('quatro_camaras') border-red-500 ring-red-400 @enderror"
                    required>

                    <option value="">Selecione...</option>

                    <option value="Não visível"
                        {{ old('quatro_camaras') == 'Não visível' ? 'selected' : '' }}>
                        Não visível
                    </option>

                    <option value="Normal"
                        {{ old('quatro_camaras') == 'Normal' ? 'selected' : '' }}>
                        Normal
                    </option>

                </select>
            </div>

                <div>
                    <label class="block text-sm font-medium mb-1">CHD Confirmada?</label>
                    <select name="chd_confirmada"
                        class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-400" required>
                        <option value="0" {{ old('chd_confirmada', '0') == '0' ? 'selected' : '' }}>Não</option>
                        <option value="1" {{ old('chd_confirmada') == '1' ? 'selected' : '' }}>Sim</option>
                    </select>
                </div>

              <div>
    <label class="block text-sm font-medium mb-1">Tipo de CHD</label>
    <select name="tipo_chd"
        id="tipo_chd"
        class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-400
        @error('tipo_chd') border-red-500 ring-red-400 @enderror">

        <option value="">Selecione...</option>

            <option value="Cardiomiopatia Hipertrófica"
                {{ old('tipo_chd') == 'Cardiomiopatia Hipertrófica' ? 'selected' : '' }}>
                Cardiomiopatia Hipertrófica
            </option>

            <option value="PCA — Persistência do Canal Arterial"
                {{ old('tipo_chd') == 'PCA — Persistência do Canal Arterial' ? 'selected' : '' }}>
                PCA — Persistência do Canal Arterial
            </option>

            <option value="DSV — Defeito do Septo Ventricular"
                {{ old('tipo_chd') == 'DSV — Defeito do Septo Ventricular' ? 'selected' : '' }}>
                DSV — Defeito do Septo Ventricular
            </option>

            <option value="DSA — Defeito do Septo Atrial"
                {{ old('tipo_chd') == 'DSA — Defeito do Septo Atrial' ? 'selected' : '' }}>
                DSA — Defeito do Septo Atrial
            </option>

            <option value="DSAV — Defeito do Septo Atrioventricular"
                {{ old('tipo_chd') == 'DSAV — Defeito do Septo Atrioventricular' ? 'selected' : '' }}>
                DSAV — Defeito do Septo Atrioventricular
            </option>

            <option value="Estenose ou Hipoplasia Pulmonar"
                {{ old('tipo_chd') == 'Estenose ou Hipoplasia Pulmonar' ? 'selected' : '' }}>
                Estenose ou Hipoplasia Pulmonar
            </option>

            <option value="Alteração da Veia Cava"
                {{ old('tipo_chd') == 'Alteração da Veia Cava' ? 'selected' : '' }}>
                Alteração da Veia Cava
            </option>

            <option value="TGA — Transposição das Grandes Artérias"
                {{ old('tipo_chd') == 'TGA — Transposição das Grandes Artérias' ? 'selected' : '' }}>
                TGA — Transposição das Grandes Artérias
            </option>

            <option value="Tetralogia de Fallot"
                {{ old('tipo_chd') == 'Tetralogia de Fallot' ? 'selected' : '' }}>
                Tetralogia de Fallot
            </option>

            <option value="Estenose ou Hipoplasia Aórtica"
                {{ old('tipo_chd') == 'Estenose ou Hipoplasia Aórtica' ? 'selected' : '' }}>
                Estenose ou Hipoplasia Aórtica
            </option>

            <option value="Insuficiência ou Hipoplasia Tricúspide"
                {{ old('tipo_chd') == 'Insuficiência ou Hipoplasia Tricúspide' ? 'selected' : '' }}>
                Insuficiência ou Hipoplasia Tricúspide
            </option>

            <option value="Doença Valvar Múltipla"
                {{ old('tipo_chd') == 'Doença Valvar Múltipla' ? 'selected' : '' }}>
                Doença Valvar Múltipla
            </option>

            <option value="Hipoplasia do Coração Esquerdo"
                {{ old('tipo_chd') == 'Hipoplasia do Coração Esquerdo' ? 'selected' : '' }}>
                Hipoplasia do Coração Esquerdo
            </option>

        </select>
    </div>
            </div>

        </div>

        <div class="mt-12 text-right">
            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-8 rounded-xl shadow-md transition">
                Salvar Consulta
            </button>
        </div>

    </form>

</div>

@endsection