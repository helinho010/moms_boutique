<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Caja extends Model
{
    use HasFactory;
    protected $fillable = [
        'fecha_cierre',
        'efectivo',
        'transferencia',
        'qr',
        'observacion',
        'id_usuario',
    ];
}
