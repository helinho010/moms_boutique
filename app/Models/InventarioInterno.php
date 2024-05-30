<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventarioInterno extends Model
{
    use HasFactory;
    protected $fillable = ['id_sucursal',
                            'id_producto','id_usuario',
                            'id_tipo_ingreso_salida',
                            'cantidad_ingreso',
                            'stock',
                          ];
}
