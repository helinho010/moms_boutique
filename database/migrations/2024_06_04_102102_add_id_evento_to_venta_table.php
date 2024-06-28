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
        Schema::table('venta', function (Blueprint $table) {
            $table->unsignedBigInteger('id_evento')
                  ->after('id_sucursal')
                  ->default(0)
                  ->comment("Se creo esta columna para el registro de la venta en los eventos, registrando el id de cada evento");
            // $table->string('observacion')
            //       ->nullable()
            //       ->after('referencia');
            $table->dropForeign(['id_sucursal']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('venta', function (Blueprint $table) {
            $table->dropColumn('id_evento');
            $table->dropColumn('observacion');
            $table->foreign('id_sucursal')->references('id')->on('sucursals')->onUpdate('cascade');
        });
    }
};
