<?php

namespace Database\Seeders;

use App\Models\Producto;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $producto = [

            ['nombre' => 'Boby Inesita manga cero',
             'precio'=> 30,
             'talla' => 'RN (recien nacido)',
             'id_categoria' => 1,  
             'created_at' => date("Y/m/d H:i"), 
             'updated_at' => date("Y/m/d H:i")],

             ['nombre' => 'Zapato Bloompy',
             'precio'=> 100,
             'talla' => '24',
             'id_categoria' => 4,  
             'created_at' => date("Y/m/d H:i"), 
             'updated_at' => date("Y/m/d H:i")],
		];

        for ($i=0; $i<count($producto); $i++)
        {
        	Producto::create($producto[$i]);
        }
    }
}
