<?php

namespace App\Http\Controllers;

use App\Models\Consulta;
use App\Models\Gestante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConsultaController extends Controller
{
    public function index()
    {

        return view('consultas.import');
    }
    private function storeFromForm(Request $request, $id)
    {
        // 1. Validar todos os campos do formulário
        $validatedData = $request->validate([
            'data_consulta' => 'required|date',
            'idade' => 'required|integer',
            'idade_gestacional' => 'required|integer',
            'altura' => 'nullable|numeric',
            'peso' => 'nullable|numeric',
            'pressao_sistolica' => 'nullable|integer',
            'diabetes_gestacional' => 'required|boolean',
            'obesidade_pre_gestacional' => 'required|boolean',
            'bpm_materno' => 'nullable|integer',
            'saturacao' => 'nullable|integer',
            'temperatura_corporal' => 'nullable|numeric',
            'glicemia_jejum' => 'nullable|numeric',
            'glicemia_pos_prandial' => 'nullable|numeric',
            'hba1c' => 'nullable|numeric',
            'frequencia_cardiaca_fetal' => 'nullable|integer',
            'circunferencia_cefalica_fetal_mm' => 'nullable|numeric',
            'circunferencia_abdominal_mm' => 'nullable|numeric',
            'comprimento_femur_mm' => 'nullable|numeric',
            'translucencia_nucal_mm' => 'nullable|numeric',
            'doppler_ducto_venoso' => 'nullable|string|max:255',
            'eixo_cardiaco' => 'nullable|string|max:255',
            'quatro_camaras' => 'nullable|string|max:255',
            'chd_confirmada' => 'required|boolean',
            'tipo_chd' => 'nullable|string|max:255',
            
        ]);

        // 2. Adicionar o ID da gestante para o salvamento
        $validatedData['gestante_id'] = $id;

        // Adicionar o número da consulta
        $ultimoNumero = Consulta::where('gestante_id', $id)->max('consulta_numero');
        $validatedData['consulta_numero'] = ($ultimoNumero ?? 0) + 1;

        // 3. Criar a consulta usando atribuição em massa
        Consulta::create($validatedData);

        // 4. Redirecionar para a página de detalhes da gestante
        return redirect()->route('gestantes.show', $id)->with('success', 'Consulta salva com sucesso!');
    }
    
    public function store(Request $request, $id)
    {
        // Lógica para salvar uma nova consulta a partir do formulário
        return $this->storeFromForm($request, $id);
    }

    public function import(Request $request)
    {
        if (!$request->hasFile('csv')) {
            return response()->json(['message' => 'Nenhum arquivo CSV enviado.'], 400);
        }

        return $this->importCsv($request);
    }

    private function importCsv(Request $request)
    {
        $request->validate(['csv' => 'required|file|mimes:csv,txt']);
        set_time_limit(0);

        $file = $request->file('csv');
        $handle = fopen($file->getRealPath(), 'r');

        if (!$handle) {
            return response()->json(['message' => 'Erro ao ler o arquivo'], 400);
        }

        DB::beginTransaction();

        try {
            $header = fgetcsv($handle, 0, ';');

            $batch = [];

            while (($row = fgetcsv($handle, 0, ';')) !== false) {

                $data = array_combine($header, $row);

                // Normalizações
                $data['data_consulta'] = date('Y-m-d', strtotime($data['data_consulta']));

                $booleans = [
                    'diabetes_gestacional',
                    'hipertensao',
                    'hipertensao_pre_eclampsia',
                    'obesidade_pre_gestacional',
                    'historico_familiar_chd',
                    'uso_medicamentos',
                    'tabagismo',
                    'alcoolismo',
                    'chd_confirmada',
                ];

                foreach ($booleans as $field) {
                    $data[$field] = filter_var($data[$field], FILTER_VALIDATE_BOOLEAN);
                }

                /*
     |--------------------------------------------------------------------------
     | 1. Criar ou localizar a gestante
     |--------------------------------------------------------------------------
     */
                $gestante = Gestante::firstOrCreate(
                    [
                        'gestante_id' => $data['gestante_id'],
                    ]
                );

                /*
     |--------------------------------------------------------------------------
     | 2. Criar ou atualizar a consulta
     |--------------------------------------------------------------------------
     */
                Consulta::updateOrCreate(
                    [
                        'gestante_id'     => $gestante->id,
                        'consulta_numero' => $data['consulta_numero'],
                    ],
                    [
                        'gestante_id' => $gestante->id,

                        'data_consulta' => $data['data_consulta'],
                        'idade' => $data['idade'],
                        'idade_gestacional' => $data['idade_gestacional'],
                        'pressao_sistolica' => $data['pressao_sistolica'],
                        'bpm_materno' => $data['bpm_materno'],
                        'saturacao' => $data['saturacao'],
                        'temperatura_corporal' => $data['temperatura_corporal'],
                        'altura' => $data['altura'],
                        'peso' => $data['peso'],
                        'glicemia_jejum' => $data['glicemia_jejum'] ?? null,
                        'glicemia_pos_prandial' => $data['glicemia_pos_prandial'] ?? null,
                        'hba1c' => $data['hba1c'] ?? null,

                        'diabetes_gestacional' => $data['diabetes_gestacional'],
                        'hipertensao' => $data['hipertensao'],
                        'hipertensao_pre_eclampsia' => $data['hipertensao_pre_eclampsia'],
                        'obesidade_pre_gestacional' => $data['obesidade_pre_gestacional'],
                        'historico_familiar_chd' => $data['historico_familiar_chd'],
                        'uso_medicamentos' => $data['uso_medicamentos'],
                        'tabagismo' => $data['tabagismo'],
                        'alcoolismo' => $data['alcoolismo'],

                        'frequencia_cardiaca_fetal' => $data['frequencia_cardiaca_fetal'],
                        'circunferencia_cefalica_fetal_mm' => $data['circunferencia_cefalica_fetal_mm'],
                        'circunferencia_abdominal_mm' => $data['circunferencia_abdominal_mm'],
                        'comprimento_femur_mm' => $data['comprimento_femur_mm'],
                        'translucencia_nucal_mm' => $data['translucencia_nucal_mm'],

                        'doppler_ducto_venoso' => $data['doppler_ducto_venoso'],
                        'eixo_cardiaco' => $data['eixo_cardiaco'],
                        'quatro_camaras' => $data['quatro_camaras'],

                        'chd_confirmada' => $data['chd_confirmada'],
                        'tipo_chd' => $data['tipo_chd'] ?? null,
                    ]
                );
            }

            DB::commit();

            return response()->json([
                'message' => 'CSV importado com sucesso'
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Erro ao importar CSV',
                'error' => $e->getMessage()
            ], 500);
        }
    }




    public function create($id)
    {
        // 1. Encontrar a gestante pelo ID recebido na rota.
        $gestante = Gestante::findOrFail($id);

        // 2. Passar o objeto 'gestante' para a view.
        return view('consultas.create', compact('gestante'));
    }

    public function show($id)
    {
        // Lógica para mostrar uma consulta específica
    }

    public function edit($id)
    {
        // Lógica para mostrar o formulário de edição de consulta
    }

    public function update(Request $request, $id)
    {
        // Lógica para atualizar uma consulta existente
    }

    public function destroy($id)
    {
        // Lógica para excluir uma consulta
    }
}
