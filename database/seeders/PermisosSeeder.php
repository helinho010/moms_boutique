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

            ['name' => 'crear traspaso productos'],
            ['name' => 'editar traspaso productos'],
            ['name' => 'eliminar traspaso productos'],

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
        ];

        $permisosAdministrador = [];

        foreach ($permissions as $permission) {
            $permiso = Permission::create($permission);
            array_push($permisosAdministrador, $permiso);
        }

        // Asignar permisos a los roles
        $administrador = Role::findByName('administrador');
        $ventas = Role::findByName('ventas');

        $administrador->syncPermissions($permisosAdministrador);
        $ventas->syncPermissions([
            'exportar pdf',
            'crear inventario interno',
            'editar inventario interno',
            'eliminar inventario interno',
            'realizar venta',
            'editar venta', 
            'eliminar venta',
            'crear cierre caja',
            'editar cierre caja',
            'eliminar cierre caja',
        ]);
        
        
    }
}
