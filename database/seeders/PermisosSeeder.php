<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermisosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            ['name' => 'exportar pdf'],
            ['name' => 'exportar excel'],
            ['name' => 'exportar csv'],

            ['name' => 'crear proveedor'],
            ['name' => 'editar proveedor'],
            ['name' => 'eliminar proveedor'],

            ['name' => 'crear categoria'],
            ['name' => 'editar categoria'],
            ['name' => 'eliminar categoria'],

            ['name' => 'crear tipo ingreso salida'],
            ['name' => 'editar tipo ingreso salida'],
            ['name' => 'eliminar tipo ingreso salida'],

            ['name' => 'crear evento'],
            ['name' => 'editar evento'],
            ['name' => 'eliminar evento'],

            ['name' => 'crear producto'],
            ['name' => 'editar producto'],
            ['name' => 'eliminar producto'],

            ['name' => 'crear sucursal'],
            ['name' => 'editar sucursal'],
            ['name' => 'eliminar sucursal'],

            ['name' => 'crear inventario interno'],
            ['name' => 'editar inventario interno'],
            ['name' => 'eliminar inventario interno'],

            ['name' => 'crear inventario externo'],
            ['name' => 'editar inventario externo'],
            ['name' => 'eliminar inventario externo'],
            ['name' => 'devolver productos inventario externo'],

            ['name' => 'crear traspaso productos'],
            ['name' => 'editar traspaso productos'],
            ['name' => 'eliminar traspaso productos'],
            ['name' => 'ver todos los traspasos'],

            ['name' => 'realizar venta'],
            ['name' => 'editar venta'],
            ['name' => 'eliminar venta'],
            ['name' => 'reporte venta'],

            ['name' => 'crear cierre caja'],
            ['name' => 'editar cierre caja'],
            ['name' => 'eliminar cierre caja'],
            ['name' => 'revisar cierre caja'],

            ['name' => 'crear tipo pago'],
            ['name' => 'editar tipo pago'],
            ['name' => 'eliminar tipo pago'],

            ['name' => 'crear usuario'],
            ['name' => 'editar usuario'],
            ['name' => 'eliminar usuario'],

            ['name' => 'crear rol'],
            ['name' => 'editar rol'],
            ['name' => 'eliminar rol'],

            ['name' => 'crear permiso'],
            ['name' => 'editar permiso'],
            ['name' => 'eliminar permiso'],

            // Permisos de opciones del sistema
            ['name' => 'opc proveedores'],
            ['name' => 'opc categoria'],
            ['name' => 'opc tipo ingreso salida'],
            ['name' => 'opc tipo pago'],
            ['name' => 'opc eventos'],
            ['name' => 'opc productos'],
            ['name' => 'opc sucursales'],
            ['name' => 'opc inventario interno'],
            ['name' => 'opc inventario externo'],
            ['name' => 'opc traspaso productos'],
            ['name' => 'opc ventas'],
            ['name' => 'opc cierre caja'],
            ['name' => 'opc usuarios'],
            ['name' => 'opc roles'],
        ];

        $permisosAdministrador = [];

        foreach ($permissions as $permission) {
            $permiso = Permission::create($permission);
            array_push($permisosAdministrador, $permiso);
        }

        // Asignar permisos al rol administrador
        $administrador = Role::findByName('administrador');

        $administrador->syncPermissions($permisosAdministrador);        
    }
}
