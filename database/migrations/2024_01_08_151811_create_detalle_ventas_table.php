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
            $table->unsignedBigInteger('id_venta');
            //$table->unsignedBigInteger('id_producto');
            $table->integer('cantidad');
            $table->string('descripcion')->comment('nombre completo mas la categoia del producto');
            $table->double('precio_unitario',8,2); 
            $table->double('subtotal',8,2);
            $table->foreign('id_venta')->references('id')->on('venta')->onUpdate('cascade');
            //$table->foreign('id_producto')->references('id')->on('productos')->onUpdate('cascade');
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
