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
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['usertype_id']);
            $table->dropColumn('usertype_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('usertype_id')
                  ->after('id')
                  ->nullable();
                  
            $table->foreign('usertype_id')
                  ->references('id')->on('usertypes')
                  ->onUpdate('cascade');
        });
    }
};
