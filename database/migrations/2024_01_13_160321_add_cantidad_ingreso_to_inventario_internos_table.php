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
        Schema::table('inventario_internos', function (Blueprint $table) {
            $table->Integer('cantidad_ingreso')->after('id_tipo_ingreso_salida');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventario_internos', function (Blueprint $table) {
            $table->dropColumn('cantidad_ingreso');
        });
    }
};
