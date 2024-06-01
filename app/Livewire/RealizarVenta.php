<?php

namespace App\Livewire;

use App\Models\Evento;
use App\Models\InventarioExterno;
use App\Models\Producto;
use App\Models\TipoPago;
use Livewire\Component;
use Livewire\Attributes\Validate;
use Luecano\NumeroALetras\NumeroALetras;
use Illuminate\Support\Facades\DB;

class RealizarVenta extends Component
{
    public $evento;
    public $tipoPagos;
    public $productosEvento;

    // Varialbes de los modelos 
    #[Validate('required|numeric')]
    public $idProductoSeleccionado;

    #[Validate('required|min:1|numeric')]
    public $cantidadDelProductoSeleccionado;
    public $descuento;
    public $total;
    public $efectivoRecivido;
    public $cambio;
    public $productosAVender;
    public $literalMonto; 

    // Datos de la factura 
    public $nitCliente;
    public $nombreCliente;
    // #[Validate('required|min:1|numeric')]
    public $idTipoPagoSeleccionado;
    public $envio;
    public $referencia;
    public $observacion;


    public function valoresIniciales()
    {
        $this->evento = Evento::where('id',session('eventoSeleccionadoParaVenta'))->get();

        $this->tipoPagos = TipoPago::where('estado',1)->get();

        $this->productosEvento = InventarioExterno::selectRaw(' inventario_externos.id as id_inventario_externos,
                                                            inventario_externos.cantidad as cantidad_inventario_externos,
                                                            inventario_externos.activo as estado_inventario_externos,
                                                            inventario_externos.created_at as created_at_inventario_externos,
                                                            inventario_externos.updated_at as updated_at_inventario_externos,
                                                            productos.id as id_productos,
                                                            productos.nombre as nombre_productos,
                                                            productos.costo as costo_productos,
                                                            productos.precio as precio_productos,
                                                            productos.talla as talla_productos,
                                                            productos.estado as estado_productos,
                                                            sucursals.id as id_sucursals,
                                                            sucursals.razon_social as razon_social_sucursals,
                                                            sucursals.direccion as direccion_sucursals,
                                                            sucursals.ciudad as ciudad_sucursals,
                                                            sucursals.activo as estado_sucursals,
                                                            users.id as id_users,
                                                            users.name as name_users,
                                                            users.estado as estado_users,
                                                            eventos.id as id_eventos,
                                                            eventos.nombre as nombre_eventos,
                                                            eventos.estado as estado_eventos,
                                                            tipo_ingreso_salidas.id as id_tipo_ingreso_salidas,
                                                            tipo_ingreso_salidas.tipo as tipo_tipo_ingreso_salidas,
                                                            tipo_ingreso_salidas.estado as estado_tipo_ingreso_salidas')
                                                ->join('productos', 'productos.id','inventario_externos.id_producto')
                                                ->join('sucursals', 'sucursals.id', 'inventario_externos.id_sucursal')
                                                ->join('users', 'users.id', 'inventario_externos.id_usuario')
                                                ->join('eventos', 'eventos.id', 'inventario_externos.id_evento')
                                                ->join('tipo_ingreso_salidas', 'tipo_ingreso_salidas.id', 'inventario_externos.id_tipo_ingreso_salida')
                                                ->where('eventos.id', session('eventoSeleccionadoParaVenta'))
                                                ->orderBy('productos.nombre','asc')
                                                ->get();
                                                
        $this->idProductoSeleccionado = 'seleccionado';
        $this->cantidadDelProductoSeleccionado = 0;
    }

    public function mount()
    {
        $this->valoresIniciales();
        $this->descuento = 0;
        $this->total = 0;
        $this->efectivoRecivido = 0;
        $this->cambio = 0;
        $this->productosAVender = array();
        $this->literalMonto = 0.0;
        $this->nitCliente = "";
        $this->nombreCliente = "";
        $this->idTipoPagoSeleccionado = "seleccionarTipoPago";
        $this->envio = "";
        $this->referencia = "";
        $this->observacion = "";

    }

    public function almaceneArrayProdcutosVenta()
    {

        $this->validate();
        $productoBuscadoId = Producto::findOrFail($this->idProductoSeleccionado);

        if ( count($this->productosAVender) == 0 ) 
        {
            array_push(
                $this->productosAVender,
                [ 
                    "id_producto" => $productoBuscadoId->id,
                    "cantidad" => intval($this->cantidadDelProductoSeleccionado) , 
                    "descripcion" => $productoBuscadoId->nombre." - Talla: ".($productoBuscadoId->talla!=""? $productoBuscadoId->talla:"ST(Sin Talla)"),
                    "precio_unitario" => $productoBuscadoId->precio != "" ? $productoBuscadoId->precio : 0,
                    "subtotal" => floatval(($productoBuscadoId->precio !="" ? $productoBuscadoId->precio : 0)*$this->cantidadDelProductoSeleccionado),
                ]
             );
        }else{
            $existeProducto = false;

            foreach ($this->productosAVender as $key => &$producto) 
            {
                if ($producto["id_producto"] == $productoBuscadoId->id) 
                {
                    $producto["cantidad"] = $producto["cantidad"] + intval($this->cantidadDelProductoSeleccionado);
                    $producto["subtotal"] = floatval(($productoBuscadoId->precio !="" ? $productoBuscadoId->precio : 0) * $producto["cantidad"]);
                    $existeProducto = true;
                    break;
                }
            }
            
            unset($producto);

            if (!$existeProducto) 
            {
                array_push(
                    $this->productosAVender,
                    [ 
                        "id_producto" => $productoBuscadoId->id,
                        "cantidad" => intval($this->cantidadDelProductoSeleccionado) , 
                        "descripcion" => $productoBuscadoId->nombre." - Talla: ".($productoBuscadoId->talla!=""? $productoBuscadoId->talla:"ST(Sin Talla)"),
                        "precio_unitario" => $productoBuscadoId->precio!=""? $productoBuscadoId->precio:0,
                        "subtotal" => floatval(($productoBuscadoId->precio!="" ? $productoBuscadoId->precio:0)*$this->cantidadDelProductoSeleccionado),
                    ]
                );
            }
        }

        $this->idProductoSeleccionado = 'seleccionado';
        $this->cantidadDelProductoSeleccionado = 0;
        
    }

    public function calcularValoresMonetarios()
    {
        $this->total = 0;

        foreach ($this->productosAVender as $key => $producto) 
        {
            $this->total = $this->total + $producto["subtotal"];
        }

        $this->descuento = number_format($this->descuento != "" ? $this->descuento : 0 , 2);
        $this->total = number_format($this->total - ($this->descuento!="" ? $this->descuento:0), 2);
        $this->efectivoRecivido = number_format($this->efectivoRecivido != "" ? floatval($this->efectivoRecivido):0, 2);
        $this->cambio = number_format($this->efectivoRecivido - $this->total,2);
        // Literal de monto total
        $litNum = new NumeroALetras();
        $litNum->apocope = true;
        $this->literalMonto = $litNum->toMoney($this->total, 2, 'bolivianos', 'centavos');
    }

    public function almacenarDatos()
    {
        dd($this->productosAVender);
        
        // $productoInventarioExterno = InventarioExterno::where('id_evento',session('eventoSeleccionadoParaVenta'))
        //                                               ->where('id_producto',$this->idProductoSeleccionado)          
        //                                               ->update(['cantidad'=> DB::raw('cantidad-'.$this->cantidadDelProductoSeleccionado)]);
    }


    public function render()
    {
        $this->valoresIniciales();
        $this->calcularValoresMonetarios();
        return view('livewire.realizar-venta');
    }
}
