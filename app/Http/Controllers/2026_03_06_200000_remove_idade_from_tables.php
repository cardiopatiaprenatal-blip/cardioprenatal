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
        Schema::table('consultas', function (Blueprint $table) {
            if (Schema::hasColumn('consultas', 'idade')) {
                $table->dropColumn('idade');
            }
        });

        Schema::table('gestantes', function (Blueprint $table) {
            if (Schema::hasColumn('gestantes', 'idade')) {
                $table->dropColumn('idade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('consultas', function (Blueprint $table) {
            $table->integer('idade')->after('data_consulta');
        });
        Schema::table('gestantes', function (Blueprint $table) {
            $table->integer('idade')->nullable()->after('data_nascimento');
        });
    }
};