<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSucursal extends Model
{
    use HasFactory;

    protected $fillable = ['id_usuario', 'id_sucursal'];

    /**
     * Obtiene las sucursales habilitadas para un usuario
     * @param int $id_usuario
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function sucursalesHabilitadasUsuario($id_usuario) 
    {
        $sucursales = self::selectRaw('
                                    sucursals.*,
                                    user_sucursals.id as id_user_sucursal,
                                    user_sucursals.id_usuario as id_usuario_user_sucursal, 
                                    user_sucursals.estado as estado_user_sucursal
                                    ')
                          ->join('sucursals', 'sucursals.id', 'user_sucursals.id_sucursal')
                          ->where('user_sucursals.id_usuario', $id_usuario)
                          ->where('user_sucursals.estado', 1)
                          ->where('sucursals.activo', 1)
                          ->get();
                          
        return $sucursales;
    }
}
