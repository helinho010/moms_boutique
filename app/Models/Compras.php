<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Compras extends Model
{
    use HasFactory;

    protected $casts = [
        'fecha_compra' => 'date',
        'fecha_creacion_aprobacion' => 'date',
        'fecha_aprobacion' => 'date',
        'fecha_envio_productos_bd' => 'date',
        'updated_at' => 'date',
        'created_at' => 'date',
    ];

    
    public static function compras($idCompra = null, $buscar = null, $idSucursal = null)
    {
        $query = self::leftJoin('users as usuario_creador', 'usuario_creador.id', '=', 'compras.id_usuario')
                       ->leftJoin('users as usuario_aprobador', 'usuario_aprobador.id', '=', 'compras.id_usuario_aprobador')
                       ->leftJoin('users as usuario_envio_bd', 'usuario_envio_bd.id', '=', 'compras.id_usuario_envio_bd')
                       ->leftJoin('sucursals', 'sucursals.id', '=', 'compras.id_sucursal_destino')
                       ->select(
                        'compras.*',
                        'usuario_creador.name as nombre_usuario_creador',
                        'usuario_creador.username as usrname_creador',
                        'usuario_aprobador.name as nombre_usuario_aprobador',
                        'usuario_aprobador.username as usrname_aprobador',
                        'usuario_envio_bd.name as nombre_usuario_envio_bd',
                        'usuario_envio_bd.username as usrname_envio_bd',
                        'sucursals.razon_social',
                        'sucursals.direccion',
                        'sucursals.nit',
                        'sucursals.telefonos',
                        'sucursals.ciudad'
                        );

        if ( !is_null($idCompra) && is_null($buscar) && is_null($idSucursal) ) {

            $query->where('compras.id', $idCompra);

        } else if ( is_null($idCompra) && !is_null($buscar) && is_null($idSucursal) ) {
            $query->where('compras.codigo_compra', 'like', "%$buscar%")
                  ->orWhere('compras.observaciones', 'like', "%$buscar%")
                  ->orWhere('sucursals.razon_social', 'like', "%$buscar%")
                  ->orWhere('sucursals.direccion', 'like', "%$buscar%")
                  ->orWhere('sucursals.nit', 'like', "%$buscar%")
                  ->orWhere('sucursals.ciudad', 'like', "%$buscar%")
                  ->orWhere('usuario_creador.name', 'like', "%$buscar%")
                  ->orWhere('usuario_creador.username', 'like', "%$buscar%")
                  ->orWhere('usuario_aprobador.name', 'like', "%$buscar%")
                  ->orWhere('usuario_aprobador.username', 'like', "%$buscar%")
                  ->orWhere('usuario_envio_bd.name', 'like', "%$buscar%")
                  ->orWhere('usuario_envio_bd.username', 'like', "%$buscar%");
                  
        } else if ( is_null($idCompra) && is_null($buscar) && !is_null($idSucursal) ) {

            $query->where('compras.id_sucursal_destino', $idSucursal);

        } 

        $query->orderBy('compras.updated_at', 'desc');
        
        return $query;
    }

    public function todasCompras()
    {
        return self::leftJoin('users as usuario_creador', 'usuario_creador.id', '=', 'compras.id_usuario')
                   ->leftJoin('users as usuario_aprobador', 'usuario_aprobador.id', '=', 'compras.id_usuario_aprobador')
                   ->leftJoin('sucursals', 'sucursals.id', '=', 'compras.id_sucursal_destino')
                   ->select(
                    'compras.*',
                    'usuario_creador.name as nombre_usuario_creador',
                    'usuario_aprobador.name as nombre_usuario_aprobador',
                    'sucursals.razon_social',
                    'sucursals.direccion'
                    )
                  ->orderBy('compras.updated_at', 'desc')
                  ->get();
    }

    public function detalleproductos(): HasMany
    {
        return $this->hasMany(DetalleCompra::class, 'id_compra', 'id');
    }


}
