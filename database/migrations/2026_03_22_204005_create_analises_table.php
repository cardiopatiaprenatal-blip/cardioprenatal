<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('analises', function (Blueprint $table) {
            $table->id();

            // tudo que vem da API Python
            $table->json('estatistica_geral')->nullable();
            $table->json('analise_risco')->nullable();
            $table->json('comorbidades')->nullable();

            // opcional
            $table->json('graficos')->nullable();

            // controle
            $table->timestamp('ultima_atualizacao')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analises');
    }
};
