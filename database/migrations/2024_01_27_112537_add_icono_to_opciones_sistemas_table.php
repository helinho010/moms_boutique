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
        Schema::table('opciones_sistemas', function (Blueprint $table) {
            $table->string('icono')
                  ->after('opcion')
                  ->default('fas fa-hashtag')
                  ->comment('<i class="fas fa-hashtag"></i> solo se debe almacenar el nombre de la clase ej: fas fa-hashtag ');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('opciones_sistemas', function (Blueprint $table) {
            $table->dropColumn('icono');
        });
    }
};
