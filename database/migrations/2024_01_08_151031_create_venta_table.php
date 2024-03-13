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
        Schema::create('venta', function (Blueprint $table) {

            $table->id();
            $table->unsignedBigInteger('id_sucursal');
            $table->unsignedBigInteger('id_tipo_pago');
            $table->unsignedBigInteger('id_usuario');
            
            $table->integer('descuento')->comment('Descuento en porcentajes');
            $table->double('total_venta',8,2);
            $table->double('efectivo_recibido',8,2);
            $table->double('cambio',8,2);
            $table->timestamps();

            $table->foreign('id_sucursal')->references('id')->on('users')->onUpdate('cascade');
            $table->foreign('id_tipo_pago')->references('id')->on('tipo_pagos')->onUpdate('cascade');
            $table->foreign('id_usuario')->references('id')->on('sucursals')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('venta');
    }
};
