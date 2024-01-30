<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\OpcionesSistema;

class OpcionesSistemaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $opciones = [

            ['opcion' => "Categoria",
             'icono' => "far fa-copyright",
             'created_at' => date("Y/m/d H:i"), 
             'updated_at' => date("Y/m/d H:i")],

             ['opcion' => "Tipo Ingreso Salida",
             'icono' => "fas fa-people-carry",  
             'created_at' => date("Y/m/d H:i"), 
             'updated_at' => date("Y/m/d H:i")],

             ['opcion' => "Evento",
             'icono' => "far fa-calendar-alt",  
             'created_at' => date("Y/m/d H:i"), 
             'updated_at' => date("Y/m/d H:i")],

             ['opcion' => "Producto",
             'icono' => "fab fa-product-hunt",  
             'created_at' => date("Y/m/d H:i"), 
             'updated_at' => date("Y/m/d H:i")],

             ['opcion' => "Sucursal",
             'icono' => "fas fa-building",  
             'created_at' => date("Y/m/d H:i"), 
             'updated_at' => date("Y/m/d H:i")],

             ['opcion' => "Inventario Interno",
             'icono' => "fas fa-boxes",  
             'created_at' => date("Y/m/d H:i"), 
             'updated_at' => date("Y/m/d H:i")],

             ['opcion' => "Inventario Externo",
             'icono' => "fas fa-dolly-flatbed",  
             'created_at' => date("Y/m/d H:i"), 
             'updated_at' => date("Y/m/d H:i")],

             ['opcion' => "Traspaso Productos",
             'icono' => "fas fa-shipping-fast",  
             'created_at' => date("Y/m/d H:i"), 
             'updated_at' => date("Y/m/d H:i")],

             ['opcion' => "Venta",
             'icono' => "fas fa-cart-arrow-down", 
             'created_at' => date("Y/m/d H:i"), 
             'updated_at' => date("Y/m/d H:i")],

             ['opcion' => "Tipo Pago",
             'icono' => "far fa-money-bill-alt",  
             'created_at' => date("Y/m/d H:i"), 
             'updated_at' => date("Y/m/d H:i")],
             
             ['opcion' => "Usuarios",
             'icono' => "fas fa-user",  
             'created_at' => date("Y/m/d H:i"), 
             'updated_at' => date("Y/m/d H:i")],

             ['opcion' => "Rol Usuarios",
             'icono' => "as fa-briefcase",  
             'created_at' => date("Y/m/d H:i"), 
             'updated_at' => date("Y/m/d H:i")],
		];

        for ($i=0; $i<count($opciones); $i++)
        {
        	OpcionesSistema::create($opciones[$i]);
        }
    }
}
