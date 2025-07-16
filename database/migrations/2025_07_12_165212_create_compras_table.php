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
            $table->string('codigo_compra', length: 20);
            $table->unsignedBigInteger('id_sucursal');
            $table->float('total_compra', 10, 2);
            $table->float('presupuesto', 10, 2)->default(0.00);
            $table->float('sobrante', 10, 2)->default(0.00);
            $table->text('observacion')->nullable();
            $table->unsignedBigInteger('id_usuario_creador');
            $table->unsignedBigInteger('id_usuario_revisor')->nullable();
            $table->unsignedBigInteger('id_usuario_aprobador')->nullable();
            $table->enum('estado', ['creado', 'revisado', 'aprobado'])
                  ->default('creado')
                  ->comment('Estado de la compra: creado, revisado, aprobado');
            $table->date('fecha_creada');
            $table->date('fecha_revisada')->nullable();
            $table->date('fecha_aprobada')->nullable();
            
            $table->timestamps();
            $table->softDeletes('deleted_at', precision: 0);

            $table->foreign('id_sucursal')->references('id')->on('sucursals')->onUpdate('cascade');
            $table->foreign('id_usuario_creador')->references('id')->on('users')->onUpdate('cascade');
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
