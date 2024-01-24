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
        Schema::create('usertype_opcs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_tipo_usuario');
            $table->unsignedBigInteger('id_opcion_sistema');
            $table->boolean('estado')->default(1)->comment('0 = Inactivo, 1 = Activo');
            $table->timestamps();

            $table->foreign('id_tipo_usuario')->references('id')->on('usertypes')->onUpdate('cascade');
            $table->foreign('id_opcion_sistema')->references('id')->on('opciones_sistemas')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usertype_opcs');
    }
};
