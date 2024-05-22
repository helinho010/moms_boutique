<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categoria = [

            ['nombre' => "Bebe",  
             'created_at' => date("Y/m/d H:i"), 
             'updated_at' => date("Y/m/d H:i")],

             ['nombre' => "Embarazo",  
             'created_at' => date("Y/m/d H:i"), 
             'updated_at' => date("Y/m/d H:i")],

             ['nombre' => "Fajas",  
             'created_at' => date("Y/m/d H:i"), 
             'updated_at' => date("Y/m/d H:i")],

             ['nombre' => "Familia",  
             'created_at' => date("Y/m/d H:i"), 
             'updated_at' => date("Y/m/d H:i")],

             ['nombre' => "Lactancia",  
             'created_at' => date("Y/m/d H:i"), 
             'updated_at' => date("Y/m/d H:i")]
		];

        for ($i=0; $i<count($categoria); $i++)
        {
        	Categoria::create($categoria[$i]);
        }
    }
}
