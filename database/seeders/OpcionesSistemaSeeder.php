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

            ['opcion' => "Proveedor",
             'icono' => "far fa-building",
             'ruta' => 'home_proveedor',
             'created_at' => date("Y/m/d H:i"), 
             'updated_at' => date("Y/m/d H:i")],

            ['opcion' => "Categoria",
             'icono' => "far fa-copyright",
             'ruta' => 'home_categoria',
             'created_at' => date("Y/m/d H:i"), 
             'updated_at' => date("Y/m/d H:i")],

             ['opcion' => "Tipo Ingreso Salida",
             'icono' => "fas fa-people-carry",  
             'ruta' => 'home_tipo_ingreso_salida',
             'created_at' => date("Y/m/d H:i"), 
             'updated_at' => date("Y/m/d H:i")],

             ['opcion' => "Evento",
             'icono' => "far fa-calendar-alt",  
             'ruta' => 'home_evento',
             'created_at' => date("Y/m/d H:i"), 
             'updated_at' => date("Y/m/d H:i")],

             ['opcion' => "Producto",
             'icono' => "fab fa-product-hunt",  
             'ruta' => 'home_producto',
             'created_at' => date("Y/m/d H:i"), 
             'updated_at' => date("Y/m/d H:i")],

             ['opcion' => "Sucursal",
             'icono' => "fas fa-building",  
             'ruta' => 'home_sucursal',
             'created_at' => date("Y/m/d H:i"), 
             'updated_at' => date("Y/m/d H:i")],

             ['opcion' => "Inventario Interno",
             'icono' => "fas fa-boxes",
             'ruta' => 'home_inventario_interno',  
             'created_at' => date("Y/m/d H:i"), 
             'updated_at' => date("Y/m/d H:i")],

             ['opcion' => "Inventario Externo",
             'icono' => "fas fa-dolly-flatbed", 
             'ruta' => 'home_inventario_externo', 
             'created_at' => date("Y/m/d H:i"), 
             'updated_at' => date("Y/m/d H:i")],

             ['opcion' => "Traspaso Productos",
             'icono' => "fas fa-shipping-fast",
             'ruta' => 'home_traspaso_productos',  
             'created_at' => date("Y/m/d H:i"), 
             'updated_at' => date("Y/m/d H:i")],

             ['opcion' => "Venta",
             'icono' => "fas fa-cart-arrow-down", 
             'ruta' => 'home_venta',
             'created_at' => date("Y/m/d H:i"), 
             'updated_at' => date("Y/m/d H:i")],

             ['opcion' => "Tipo Pago",
             'icono' => "far fa-money-bill-alt",
             'ruta' => 'home_tipo_pago',  
             'created_at' => date("Y/m/d H:i"), 
             'updated_at' => date("Y/m/d H:i")],
             
             ['opcion' => "Usuarios",
             'icono' => "fas fa-user",  
             'created_at' => date("Y/m/d H:i"),
             'ruta' => 'home_usuarios', 
             'updated_at' => date("Y/m/d H:i")],

             ['opcion' => "Rol Usuarios",
             'icono' => "fas fa-briefcase",
             'ruta' => 'home_rol_usuarios',  
             'created_at' => date("Y/m/d H:i"), 
             'updated_at' => date("Y/m/d H:i")],
		];

        for ($i=0; $i<count($opciones); $i++)
        {
        	OpcionesSistema::create($opciones[$i]);
        }
    }
}
