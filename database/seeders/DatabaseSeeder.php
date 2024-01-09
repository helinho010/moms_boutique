<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Evento;
use App\Models\Producto;
use App\Models\Sucursal;
use App\Models\TipoIngresoSalida;
use App\Models\TipoPago;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UsertypesTableSeeder::class,
            UsersTableSeeder::class,
            TipoPagoSeeder::class,
            Categoria::class,
            TipoIngresoSalida::class,
            Evento::class,
            Sucursal::class,
            Producto::class,
    	]);
    }
}
