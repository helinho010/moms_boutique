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
}
