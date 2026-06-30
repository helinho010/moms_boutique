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
        Schema::create('compras', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_usuario');
            $table->unsignedBigInteger('id_usuario_aprobador')->nullable();
            $table->unsignedBigInteger('id_usuario_rechazador')->nullable();
            $table->unsignedBigInteger('id_usuario_envio_bd')->nullable();
            $table->unsignedBigInteger('id_sucursal_destino');
            $table->string('codigo_compra')->unique();
            $table->date('fecha_compra'); //cuando se va a realizar la comprar 
            $table->integer('total_compra');
            $table->unsignedTinyInteger('estado_aprobacion')->default(0); // 0: Pendiente, 1: Aprobada, 2: Rechazada, 3: Enviada a Bodega
            $table->date('fecha_creacion_compra')->default(date("Y-m-d")); // cuando el usuario creo esta compra
            $table->date('fecha_aprobacion')->nullable();
            $table->date('fecha_envio_productos_bd')->nullable();
            $table->boolean('estado')->default(true);
            $table->string('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compras');
    }
};
