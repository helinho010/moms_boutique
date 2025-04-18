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
        Schema::create('opciones_sistemas', function (Blueprint $table) {
            $table->id();
            $table->string('opcion');
            $table->boolean('estado')->default(1)->comment('0 = inactivo, 1 = Activo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opciones_sistemas');
    }
};
