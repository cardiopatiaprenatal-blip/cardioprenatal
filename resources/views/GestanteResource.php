<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GestanteResource extends JsonResource
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
            'data_nascimento' => $this->data_nascimento,
            // Inclui a contagem de consultas se ela tiver sido carregada (como no método index)
            'consultas_count' => $this->whenCounted('consultas'),
            // Inclui a lista de consultas se a relação tiver sido carregada (como no método show)
            'consultas' => ConsultaResource::collection($this->whenLoaded('consultas')),
        ];
    }
}
