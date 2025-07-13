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
        Schema::create('detalle_compras', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_compra');
            $table->unsignedBigInteger('id_producto');
            $table->unsignedBigInteger('id_usuario')->nullable();//aqui nos quedamos
            $table->integer('cantidad');
            $table->float('precio_unitario', 10, 2);
            $table->float('precio_venta', 10, 2);
            $table->float('sub_total', 10, 2)->default(0.00);
            $table->text('observacion', 10, 2)->default(0.00);
            $table->timestamps();
            $table->softDeletes('deleted_at', precision: 0);

            $table->foreign('id_compra')->references('id')->on('compras')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('id_producto')->references('id')->on('productos')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_compras');
    }
};
