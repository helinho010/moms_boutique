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
        Schema::create('trasporte_productos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_sucursal_origen');
            $table->unsignedBigInteger('id_sucursal_destino');
            $table->unsignedBigInteger('id_producto');
            $table->unsignedBigInteger('id_tipo_ingreso_salida');
            $table->unsignedBigInteger('id_usuario');
            $table->integer('cantidad');            
            $table->string('observaciones')->nullable();
            $table->boolean('estado')->default(1)->comment('0 = Inactivo, 1 = Activo');
            $table->timestamps();

            $table->foreign('id_sucursal_origen')->references('id')->on('sucursals')->onUpdate('cascade');
            $table->foreign('id_sucursal_destino')->references('id')->on('sucursals')->onUpdate('cascade');
            $table->foreign('id_producto')->references('id')->on('productos')->onUpdate('cascade');
            $table->foreign('id_tipo_ingreso_salida')->references('id')->on('tipo_ingreso_salidas')->onUpdate('cascade');
            $table->foreign('id_usuario')->references('id')->on('users')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trasporte_productos');
    }
};
