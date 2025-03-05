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
             'orden_opcion' => 1,
             'icono' => "far fa-building",
             'ruta' => 'home_proveedor',
             'created_at' => date("Y/m/d H:i"), 
             'updated_at' => date("Y/m/d H:i")],

            ['opcion' => "Categoria",
             'orden_opcion' => 2,
             'icono' => "far fa-copyright",
             'ruta' => 'home_categoria',
             'created_at' => date("Y/m/d H:i"), 
             'updated_at' => date("Y/m/d H:i")],

             ['opcion' => "Tipo Ingreso Salida",
             'orden_opcion' => 3,
             'icono' => "fas fa-people-carry",  
             'ruta' => 'home_tipo_ingreso_salida',
             'created_at' => date("Y/m/d H:i"), 
             'updated_at' => date("Y/m/d H:i")],

             ['opcion' => "Evento",
             'orden_opcion' => 4,
             'icono' => "far fa-calendar-alt",  
             'ruta' => 'home_evento',
             'created_at' => date("Y/m/d H:i"), 
             'updated_at' => date("Y/m/d H:i")],

             ['opcion' => "Producto",
             'orden_opcion' => 5,
             'icono' => "fab fa-product-hunt",  
             'ruta' => 'home_producto',
             'created_at' => date("Y/m/d H:i"), 
             'updated_at' => date("Y/m/d H:i")],

             ['opcion' => "Sucursal",
             'orden_opcion' => 6,
             'icono' => "fas fa-building",  
             'ruta' => 'home_sucursal',
             'created_at' => date("Y/m/d H:i"), 
             'updated_at' => date("Y/m/d H:i")],

             ['opcion' => "Inventario Interno",
             'orden_opcion' => 7,
             'icono' => "fas fa-boxes",
             'ruta' => 'home_inventario_interno',  
             'created_at' => date("Y/m/d H:i"), 
             'updated_at' => date("Y/m/d H:i")],

             ['opcion' => "Inventario Externo",
             'orden_opcion' => 8,
             'icono' => "fas fa-dolly-flatbed", 
             'ruta' => 'home_inventario_externo', 
             'created_at' => date("Y/m/d H:i"), 
             'updated_at' => date("Y/m/d H:i")],

             ['opcion' => "Traspaso Productos",
             'orden_opcion' => 9,
             'icono' => "fas fa-shipping-fast",
             'ruta' => 'home_traspaso_productos',  
             'created_at' => date("Y/m/d H:i"), 
             'updated_at' => date("Y/m/d H:i")],

             ['opcion' => "Venta",
             'icono' => "fas fa-cart-arrow-down",
             'orden_opcion' => 10, 
             'ruta' => 'home_venta',
             'created_at' => date("Y/m/d H:i"), 
             'updated_at' => date("Y/m/d H:i")],

             ['opcion' => "Tipo Pago",
             'orden_opcion' => 12,
             'icono' => "far fa-money-bill-alt",
             'ruta' => 'home_tipo_pago',  
             'created_at' => date("Y/m/d H:i"), 
             'updated_at' => date("Y/m/d H:i")],
             
             ['opcion' => "Usuarios",
             'orden_opcion' => 13,
             'icono' => "fas fa-user",  
             'created_at' => date("Y/m/d H:i"),
             'ruta' => 'home_usuarios', 
             'updated_at' => date("Y/m/d H:i")],

             ['opcion' => "Rol Usuarios",
             'orden_opcion' => 14,
             'icono' => "fas fa-briefcase",
             'ruta' => 'home_rol_usuarios',  
             'created_at' => date("Y/m/d H:i"), 
             'updated_at' => date("Y/m/d H:i")],

             ['opcion' => "Caja",
             'orden_opcion' => 11,
             'icono' => "fab fa-contao",
             'ruta' => 'home_caja',  
             'created_at' => date("Y/m/d H:i"), 
             'updated_at' => date("Y/m/d H:i")],
		];

        for ($i=0; $i<count($opciones); $i++)
        {
        	OpcionesSistema::create($opciones[$i]);
        }
    }
}
