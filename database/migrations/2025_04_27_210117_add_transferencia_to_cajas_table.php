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
        Schema::table('cajas', function (Blueprint $table) {
            $table->renameColumn('transferencia', 'tarjeta')
                  ->after('efectivo');
        });

        Schema::table('cajas', function (Blueprint $table) {
            $table->float('transferencia', 10, 2)
                  ->after('tarjeta')
                  ->default(0)
                  ->comment('Transferencia bancaria');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cajas', function (Blueprint $table) {
            $table->dropColumn('transferencia');
            $table->renameColumn('tarjeta', 'transferencia')->after('efectivo');
        });
    }
};
