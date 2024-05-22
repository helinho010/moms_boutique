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
        Schema::table('usertypes', function (Blueprint $table) {
            $table->boolean("estado")->default(1)->after("type")->comment("0 = Inactivo, 1 = Activo");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('usertypes', function (Blueprint $table) {
            $table->dropColumn("estado");
        });
    }
};
