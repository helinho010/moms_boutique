<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Type\Integer;

class Caja extends Model
{
    use HasFactory;

    protected $fillable = [
        'fecha_cierre',
        'efectivo',
        'tarjeta',
        'transferencia',
        'qr',
        'observacion',
        'id_usuario',
        'id_sucursal',
        'venta_sistema',
        'total_declarado',
        'verificado',
    ];

    static private $columnasVer = 'cajas.id as id_cierre_caja,
                                        cajas.fecha_cierre as fecha_cierre_caja,
                                        cajas.efectivo as efectivo_caja,
                                        cajas.tarjeta as tarjeta_caja,
                                        cajas.transferencia as transferencia_caja, 
                                        cajas.qr as qr_caja, 
                                        cajas.venta_sistema as venta_sistema_caja,
	                                    cajas.total_declarado as total_declarado_caja,
                                        cajas.observacion as observacion_caja,
                                        cajas.verificado as verificado_caja,
                                        cajas.id_usuario as id_usuario_caja,
                                        sucursals.id as id_sucursal,
                                        sucursals.razon_social as razon_social_sucursal,
                                        sucursals.direccion as direccion_sucursal,
                                        users.name as name_usuario,
                                        users.username as nombre_usuario,
                                        users.id as id_usuario';

    public static function cierresCajaXSucursal(int $id_sucursal)
    {  
        $usuario = User::find(auth()->user()->id);

        $cierresCaja = Caja::selectRaw( self::$columnasVer )
                          ->join("users", "users.id", "cajas.id_usuario")
                          ->join("sucursals", "sucursals.id", "cajas.id_sucursal");

        if ( !($id_sucursal == 999 && $usuario->hasPermissionTo('todas las sucursales')) ){
            $cierresCaja = $cierresCaja->where('cajas.id_sucursal', $id_sucursal);
        }

        $cierresCaja = $cierresCaja->orderBy("cajas.updated_at","desc");
        
        return $cierresCaja;
    }
    
    public static function buscar(string $cadenaBusqueda, int $id_sucursal)
    {    
        $usuario = User::find(auth()->user()->id);
         
        $cierresCaja = Self::selectRaw(self::$columnasVer)
            ->join('users', 'cajas.id_usuario', '=', 'users.id')
            ->join('sucursals', 'cajas.id_sucursal', '=', 'sucursals.id')
            ->where(function($query) use ($cadenaBusqueda) {
                $query->where('fecha_cierre', 'like', '%' . $cadenaBusqueda . '%')
                      ->orWhere('efectivo', 'like', '%' . $cadenaBusqueda . '%')
                      ->orWhere('tarjeta', 'like', '%' . $cadenaBusqueda . '%')
                      ->orWhere('transferencia', 'like', '%' . $cadenaBusqueda . '%')
                      ->orWhere('qr', 'like', '%' . $cadenaBusqueda . '%')
                      ->orWhere('observacion', 'like', '%' . $cadenaBusqueda . '%')
                      ->orWhere('users.name', 'like', '%' . $cadenaBusqueda . '%')
                      ->orWhere('sucursals.direccion', 'like', '%' . $cadenaBusqueda . '%')
                      ->orWhere('venta_sistema', 'like', '%' . $cadenaBusqueda . '%')
                      ->orWhere('total_declarado', 'like', '%' . $cadenaBusqueda . '%')
                      ->orWhere('verificado', 'like', '%' . $cadenaBusqueda . '%');
            });

            if ( !($id_sucursal == 999 && $usuario->hasPermissionTo('todas las sucursales')) ) 
            {
                $cierresCaja = $cierresCaja->where('cajas.id_sucursal', $id_sucursal);
            }

            $cierresCaja = $cierresCaja->orderBy("cajas.updated_at","desc");

        return $cierresCaja;
    }
}
