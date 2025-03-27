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
        'transferencia',
        'qr',
        'observacion',
        'id_usuario',
        'id_sucursal',
        'venta_sistema',
        'total_declarado',
        'verificado',
    ];

    public static function registrosCajaXUsuario(int $tipo_usuario, int $id_usuario, int $paginate = 10){
        $registros = Caja::selectRaw('
                                        cajas.id as id_cierre_caja,
                                        cajas.fecha_cierre as fecha_cierre_caja,
                                        cajas.efectivo as efectivo_caja,
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
                                        users.id as id_usuario,
                                        users.usertype_id as id_tipo_usuario
                                    ')
                          ->join("users", "users.id", "cajas.id_usuario")
                          ->join("sucursals", "sucursals.id", "cajas.id_sucursal");

        if ( $tipo_usuario != 1 ) {
            $registros = $registros->where('users.id', $id_usuario);
        }

        $registros = $registros->orderBy("cajas.updated_at","desc")
                               ->paginate($paginate);
        
        return $registros;
    }
    
    public static function buscar(string $cadenaBusqueda, int $tipo_usuario, int $id_usuario, int $paginate = 10){ 
        
        $registros = Self::selectRaw('cajas.id as id_cierre_caja,
                                        cajas.fecha_cierre as fecha_cierre_caja,
                                        cajas.efectivo as efectivo_caja,
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
                                        users.id as id_usuario,
                                        users.usertype_id as id_tipo_usuario')
            ->join('users', 'cajas.id_usuario', '=', 'users.id')
            ->join('sucursals', 'cajas.id_sucursal', '=', 'sucursals.id')
            // ->where('fecha_cierre', 'like', '%' . $cadenaBusqueda . '%')
            // ->orWhere('efectivo', 'like', '%' . $cadenaBusqueda . '%')
            // ->orWhere('transferencia', 'like', '%' . $cadenaBusqueda . '%')
            // ->orWhere('qr', 'like', '%' . $cadenaBusqueda . '%')
            // ->orWhere('observacion', 'like', '%' . $cadenaBusqueda . '%')
            // ->orWhere('users.name', 'like', '%' . $cadenaBusqueda . '%')
            // ->orWhere('sucursals.direccion', 'like', '%' . $cadenaBusqueda . '%')
            // ->orWhere('venta_sistema', 'like', '%' . $cadenaBusqueda . '%')
            // ->orWhere('total_declarado', 'like', '%' . $cadenaBusqueda . '%')
            // ->orWhere('verificado', 'like', '%' . $cadenaBusqueda . '%');
            ->where(function($query) use ($cadenaBusqueda) {
                $query->where('fecha_cierre', 'like', '%' . $cadenaBusqueda . '%')
                      ->orWhere('efectivo', 'like', '%' . $cadenaBusqueda . '%')
                      ->orWhere('transferencia', 'like', '%' . $cadenaBusqueda . '%')
                      ->orWhere('qr', 'like', '%' . $cadenaBusqueda . '%')
                      ->orWhere('observacion', 'like', '%' . $cadenaBusqueda . '%')
                      ->orWhere('users.name', 'like', '%' . $cadenaBusqueda . '%')
                      ->orWhere('sucursals.direccion', 'like', '%' . $cadenaBusqueda . '%')
                      ->orWhere('venta_sistema', 'like', '%' . $cadenaBusqueda . '%')
                      ->orWhere('total_declarado', 'like', '%' . $cadenaBusqueda . '%')
                      ->orWhere('verificado', 'like', '%' . $cadenaBusqueda . '%');
            });

            if ( $tipo_usuario != 1 ) {
                $registros = $registros->where('users.id', $id_usuario);
            }

            $registros = $registros->orderBy("cajas.updated_at","desc")
                                   ->paginate($paginate);

        return $registros;
    }
}
