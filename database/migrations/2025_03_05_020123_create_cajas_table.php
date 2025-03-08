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
        Schema::create('cajas', function (Blueprint $table) {
            $table->id();
            $table->date("fecha_cierre");
            $table->float("efectivo",10,2);
            $table->float("transferencia",10,2);
            $table->float("qr",10,2);
            $table->float("venta_sistema",10,2);
            $table->float("total_declarado",10,2);
            $table->text("observacion");
            $table->boolean("verificado")
                  ->default(0)
                  ->comment("0 => no verificado, 1 => verificado");
            $table->unsignedBigInteger("id_sucursal");
            $table->unsignedBigInteger("id_usuario");
            $table->timestamps();

            $table->foreign('id_sucursal')->references('id')->on('sucursals');
            $table->foreign('id_usuario')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cajas');
    }
};
