<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sucursal extends Model
{
    use HasFactory;
    
    protected $fillable = ['nit','razon_social','direccion', 'telefonos', 'ciudad'];

    public static function almacenCentral()
    {
        return self::where('almacen_central',1)->first();
    }

    public static function obtenerSucursal($idSucursal)
    {
        return self::where('id',$idSucursal)->first();
    }
}
