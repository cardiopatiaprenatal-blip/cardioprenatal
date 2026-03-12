<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConsultaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'gestante_id' => $this->gestante_id,
            'consulta_numero' => $this->consulta_numero,
            'data_consulta' => $this->data_consulta,
            'idade_gestacional' => $this->idade_gestacional,
            'altura' => $this->altura,
            'peso' => $this->peso,
            'pressao_sistolica' => $this->pressao_sistolica,
            'diabetes_gestacional' => $this->diabetes_gestacional,
            'obesidade_pre_gestacional' => $this->obesidade_pre_gestacional,
            'bpm_materno' => $this->bpm_materno,
            'saturacao' => $this->saturacao,
            'temperatura_corporal' => $this->temperatura_corporal,
            'glicemia_jejum' => $this->glicemia_jejum,
            'glicemia_pos_prandial' => $this->glicemia_pos_prandial,
            'hba1c' => $this->hba1c,
            'frequencia_cardiaca_fetal' => $this->frequencia_cardiaca_fetal,
            'circunferencia_cefalica_fetal_mm' => $this->circunferencia_cefalica_fetal_mm,
            'circunferencia_abdominal_mm' => $this->circunferencia_abdominal_mm,
            'comprimento_femur_mm' => $this->comprimento_femur_mm,
            'translucencia_nucal_mm' => $this->translucencia_nucal_mm,
            'doppler_ducto_venoso' => $this->doppler_ducto_venoso,
            'eixo_cardiaco' => $this->eixo_cardiaco,
            'quatro_camaras' => $this->quatro_camaras,
            'chd_confirmada' => $this->chd_confirmada,
            'tipo_chd' => $this->tipo_chd,
        ];
    }
}