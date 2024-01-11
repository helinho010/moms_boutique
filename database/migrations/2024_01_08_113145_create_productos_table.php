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
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->uuid('codigo_producto');
            $table->string('nombre');
            $table->double('precio',8,2);
            $table->string('talla');
            $table->unsignedBigInteger('id_categoria');
            $table->boolean('estado')->default(1)->comment('0 = inactivo, 1 = activo');
            $table->timestamps();

            
            $table->foreign('id_categoria')
                  ->references('id')
                  ->on('categorias')
                  ->onUpdate('cascade');   
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
