<?php

namespace Database\Seeders;

use App\Models\Sucursal;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SucursalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sucursal = [

            ['nit' => "123456789",
             'razon_social'=> 'Mons Boutique',
             'direccion' => 'Miraflores Calle Díaz Romero Esq Lucas Jaime MATERNO INFANTIl',
             'telefonos' => '75275956',
             'ciudad' => 'La Paz',  
             'created_at' => date("Y/m/d H:i"), 
             'updated_at' => date("Y/m/d H:i")],

             ['nit' => "987654321",
             'razon_social'=> 'Moms Boutique',
             'direccion' => 'Ciudad Satelite - Frente a Hospital Holandes Miraflores Lado Materno Infantil',
             'telefonos' => '75288556 - 75275956',
             'ciudad' => 'El Alto',  
             'created_at' => date("Y/m/d H:i"), 
             'updated_at' => date("Y/m/d H:i")],
		];

        for ($i=0; $i<count($sucursal); $i++)
        {
        	Sucursal::create($sucursal[$i]);
        }
    }
}
