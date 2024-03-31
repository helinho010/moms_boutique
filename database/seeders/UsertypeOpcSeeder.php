<?php

namespace Database\Seeders;

use App\Models\UsertypeOpc;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsertypeOpcSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $RolOpc = [

            ['id_tipo_usuario' => 1,
             'id_opcion_sistema' => 1,  
             'created_at' => date("Y/m/d H:i"), 
             'updated_at' => date("Y/m/d H:i")],

             ['id_tipo_usuario' => 1,
             'id_opcion_sistema' => 2,  
             'created_at' => date("Y/m/d H:i"), 
             'updated_at' => date("Y/m/d H:i")],

             ['id_tipo_usuario' => 1,
             'id_opcion_sistema' => 3,  
             'created_at' => date("Y/m/d H:i"), 
             'updated_at' => date("Y/m/d H:i")],

             ['id_tipo_usuario' => 1,
             'id_opcion_sistema' => 4,  
             'created_at' => date("Y/m/d H:i"), 
             'updated_at' => date("Y/m/d H:i")],

             ['id_tipo_usuario' => 1,
             'id_opcion_sistema' => 5,  
             'created_at' => date("Y/m/d H:i"), 
             'updated_at' => date("Y/m/d H:i")],

             ['id_tipo_usuario' => 1,
             'id_opcion_sistema' => 6,  
             'created_at' => date("Y/m/d H:i"), 
             'updated_at' => date("Y/m/d H:i")],

             ['id_tipo_usuario' => 1,
             'id_opcion_sistema' => 7,  
             'created_at' => date("Y/m/d H:i"), 
             'updated_at' => date("Y/m/d H:i")],

             ['id_tipo_usuario' => 1,
             'id_opcion_sistema' => 8,  
             'created_at' => date("Y/m/d H:i"), 
             'updated_at' => date("Y/m/d H:i")],

             ['id_tipo_usuario' => 1,
             'id_opcion_sistema' => 9,  
             'created_at' => date("Y/m/d H:i"), 
             'updated_at' => date("Y/m/d H:i")],

             ['id_tipo_usuario' => 1,
             'id_opcion_sistema' => 10,  
             'created_at' => date("Y/m/d H:i"), 
             'updated_at' => date("Y/m/d H:i")],

             ['id_tipo_usuario' => 1,
             'id_opcion_sistema' => 11,  
             'created_at' => date("Y/m/d H:i"), 
             'updated_at' => date("Y/m/d H:i")],

             ['id_tipo_usuario' => 1,
             'id_opcion_sistema' => 12,  
             'created_at' => date("Y/m/d H:i"), 
             'updated_at' => date("Y/m/d H:i")],

             ['id_tipo_usuario' => 1,
             'id_opcion_sistema' => 13,  
             'created_at' => date("Y/m/d H:i"), 
             'updated_at' => date("Y/m/d H:i")],
		];

        for ($i=0; $i<count($RolOpc); $i++)
        {
        	UsertypeOpc::create($RolOpc[$i]);
        }
    }
}
