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
        Schema::table('opciones_sistemas', function (Blueprint $table) {
            $table->integer("orden_opcion")
                  ->after("opcion")
                  ->default(0)
                  ->comment("Orden de la opcion con la cual aparecera en la vista del usuario");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('opciones_sistemas', function (Blueprint $table) {
            $table->dropColumn("orden_opcion");
        });
    }
};
