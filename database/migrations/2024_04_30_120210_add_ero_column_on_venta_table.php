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
        Schema::table('venta', function (Blueprint $table) {
            $table->string('envio')->nullable()->after('cambio');
            $table->string('referencia')->nullable()->after('envio');
            $table->string('observacion')->nullable()->after('referencia');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('venta', function (Blueprint $table) {
            $table->dropColumn('observacion');
            $table->dropColumn('referencia');
            $table->dropColumn('envio');

        });
    }
};
