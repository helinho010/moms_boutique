<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class UsertypeOpc extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_tipo_usuario',
        'id_opcion_sistema',
        'estado',
    ];

    public static function permisosRol($rol)
    {
        $permisos = Role::findByName($rol)
                        ->permissions
                        ->pluck('name')
                        ->toArray();
        return $permisos;
    }
}
