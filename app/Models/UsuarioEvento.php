<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsuarioEvento extends Model
{
    use HasFactory;

    protected $table = 'user_evento';

    protected $fillable = [
        'id_usuario',
        'id_evento',
        'estado',
    ];


    public static function eventosHabilitadosUsuario ($id_usuario)
    {
        $eventos = self::selectRaw('
                                    user_evento.id as id_user_evento,
                                    users.id as id_users,
                                    users.name as nombre_users,
                                    users.estado as estado_users,
                                    eventos.id as id_evento,
                                    eventos.nombre as nombre_evento,
                                    eventos.fecha_evento as fecha_evento,
                                    eventos.estado as estado_evento
                                  ')
                        ->join('users', 'users.id', 'user_evento.id_usuario')
                        ->join('eventos', 'eventos.id', 'user_evento.id_evento')
                        ->where('user_evento.id_usuario', $id_usuario)
                        ->where('user_evento.estado', 1)
                        ->orderBy('eventos.fecha_evento', 'desc')
                        ->get();
                        
        return $eventos;
    }
}
