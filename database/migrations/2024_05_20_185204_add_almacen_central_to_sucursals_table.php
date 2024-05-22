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
        Schema::table('sucursals', function (Blueprint $table) {
            $table->boolean('almacen_central')
                  ->default(false)
                  ->after('ciudad')
                  ->comment('Este campo indica que sucursal tiene el almacen central');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sucursals', function (Blueprint $table) {
            $table->dropColumn('almacen_central');
        });
    }
};
