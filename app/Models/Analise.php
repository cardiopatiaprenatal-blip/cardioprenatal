<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Analise extends Model
{
    protected $fillable = [
        'estatistica_geral',
        'analise_risco',
        'comorbidades',
        'graficos',
        'ultima_atualizacao'
    ];

    protected $casts = [
        'estatistica_geral' => 'array',
        'analise_risco' => 'array',
        'comorbidades' => 'array',
        'graficos' => 'array',
    ];
    
}
