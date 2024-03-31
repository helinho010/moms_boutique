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
            $table->string('ruta',150)
                  ->default('')
                  ->after('icono')
                  ->comment('Es el nombre ruta "name" del archivo web.php de cada opcion Ej: Route::get(/proveedor, [ProveedorController::class,index])->name(home_proveedor); que en este caso seria el home_proveedor');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('opciones_sistemas', function (Blueprint $table) {
            $table->dropColumn('ruta');
        });
    }
};
