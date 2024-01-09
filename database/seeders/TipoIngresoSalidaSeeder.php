<?php

namespace Database\Seeders;

use App\Models\TipoIngresoSalida;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipoIngresoSalidaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tipo_ingreso_salida = [

            ['tipo' => "Permuta",  
             'created_at' => date("Y/m/d H:i"), 
             'updated_at' => date("Y/m/d H:i")],

             ['tipo' => "Cambio",  
             'created_at' => date("Y/m/d H:i"), 
             'updated_at' => date("Y/m/d H:i")],

             ['tipo' => "Defectuoso",  
             'created_at' => date("Y/m/d H:i"), 
             'updated_at' => date("Y/m/d H:i")],

             ['tipo' => "Compra",  
             'created_at' => date("Y/m/d H:i"), 
             'updated_at' => date("Y/m/d H:i")],

             ['tipo' => "Devolucion",  
             'created_at' => date("Y/m/d H:i"), 
             'updated_at' => date("Y/m/d H:i")],

             ['tipo' => "Prestamo",  
             'created_at' => date("Y/m/d H:i"), 
             'updated_at' => date("Y/m/d H:i")],
		];

        for ($i=0; $i<count($tipo_ingreso_salida); $i++)
        {
        	TipoIngresoSalida::create($tipo_ingreso_salida[$i]);
        }
    }
}
