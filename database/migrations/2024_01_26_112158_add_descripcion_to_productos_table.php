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
        Schema::table('productos', function (Blueprint $table) {
            $table->double('costo',8,2)
                  ->after('nombre')
                  ->default(0)
                  ->comment("Costo del producto");
            $table->text('descripcion')
                  ->after('id_categoria')
                  ->nullable()
                  ->commet('Columna que maneja la descripcion de cada producto');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropColumn('costo');
            $table->dropColumn('descripcion');
        });
    }
};
