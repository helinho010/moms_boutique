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
        Schema::create('sucursals', function (Blueprint $table) {
            $table->id();
            $table->string("nit",30);
            $table->string("razon_social",200);
            $table->string("direccion");
            $table->string("telefonos",200);
            $table->string("ciudad",200);
            $table->boolean("activo")->default(1)->comment("0 = inactivo, 1 = activo");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sucursals');
    }
};
