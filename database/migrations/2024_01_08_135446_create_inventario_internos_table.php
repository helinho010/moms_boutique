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
        Schema::create('inventario_internos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_producto');
            $table->unsignedBigInteger('id_sucursal');
            $table->unsignedBigInteger('id_usuario');
            $table->unsignedBigInteger('id_tipo_ingreso_salida');
            $table->Integer('stock')->default(0);
            $table->boolean('estado')->default(1)->comment('0 = inactivo, 1 = activo');

            $table->foreign('id_producto')->references('id')->on('productos')->onUpdate('cascade');
            $table->foreign('id_sucursal')->references('id')->on('sucursals')->onUpdate('cascade');
            $table->foreign('id_usuario')->references('id')->on('users')->onUpdate('cascade');
            $table->foreign('id_tipo_ingreso_salida')->references('id')->on('tipo_ingreso_salidas')->onUpdate('cascade');

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventario_internos');
    }
};
