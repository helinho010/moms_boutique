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
        Schema::create('detalle_ventas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_producto');
            $table->unsignedBigInteger('id_sucursal');
            $table->unsignedBigInteger('id_usuario');
            // $table->unsignedBigInteger('id_factura');
            $table->unsignedBigInteger('id_tipo_pago');
            $table->integer('cantidad');
            $table->boolean('estado')->default(1)->comment('0 = inactivo, 1 = activo');
            $table->foreign('id_producto')->references('id')->on('productos')->onUpdate('cascade');
            $table->foreign('id_sucursal')->references('id')->on('sucursals')->onUpdate('cascade');
            $table->foreign('id_usuario')->references('id')->on('users')->onUpdate('cascade');
            // $table->foreign('id_factura')->references('id')->on('productos')->onUpdate('cascade');
            $table->foreign('id_tipo_pago')->references('id')->on('tipo_pagos')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_ventas');
    }
};
