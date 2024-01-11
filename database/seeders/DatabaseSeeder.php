<?php

namespace Database\Seeders;


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
            CategoriaSeeder::class,
            TipoIngresoSalidaSeeder::class,
            EventoSeeder::class,
            SucursalSeeder::class,
            ProductoSeeder::class,
    	]);
    }
}
