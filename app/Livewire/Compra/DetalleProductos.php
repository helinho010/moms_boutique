<?php

namespace App\Livewire\Compra;

use App\Models\Compras;
use App\Models\DetalleCompra;
use App\Models\UserSucursal;
use Livewire\Component;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\DB;

class DetalleProductos extends Component
{
    public $productos; //recibe todos los productos para mostrarlos en el detalle
    public $productosSelect; //almacena los productos formateados para mostrarlos en el select
    #[Validate('required|integer|exists:productos,id', onUpdate: false)]
    public $idProductoSeleccionado; //almacena el id del producto seleccionado
    #[Validate('required', message:"La cantidad es requerida")]
    #[Validate('integer', message:"La cantidad tiene que ser un número entero")]
    #[Validate('min:1', message:"La cantidad tiene que ser un número entero mayor a 0")]
    public $cantidadProducto; //almacena la cantidad ingresada por el usuario
    public $detalleCompra = []; //almacena el detalle de compra con los productos agregados
    public $totalCompra; //almacena el total de la compra
    public $sucursales; //obteiene todas las sucursales habilitadas para el usuairo 
    #[Validate('required|integer|exists:sucursals,id', onUpdate: false)]
    public $sucursalDestinoId; //almacena el id de la sucursal seleccionada por el usuario
    public $codigo_compra; //almacena el código de compra generado
    #[Validate('required|date', message:"La fecha de compra es requerida")]
    public $fecha_compra; //almacena la fecha de compra seleccionada por el usuario
    public $observacion; //almacena la observación ingresada por el usuario
    public $iva = true; //almacena si la compra tiene IVA o no
    public $totalIva; //almacena el total de IVA de la compra

    public $producto; //Prueba

    protected $listeners = ['seleccionarProducto'];

    function mount($productos, $compra=null, $detalleCompra=null)
    {

        $this->productosSelect = collect($this->productos)->mapWithKeys(function ($producto) {
            return [
                $producto['id'] => 'ID: ' . $producto['id'] . ' - ' . $producto['nombre'] . ' - Talla: ' . $producto['talla']
            ];
        })->toArray();
        
        $this->idProductoSeleccionado = 1;
        $this->cantidadProducto = '';    
        $this->totalCompra = 0;
        $this->totalIva = 0;
        $this->sucursales = UserSucursal::sucursalesHabilitadasUsuario(auth()->id());

        if($compra)
        { 
            $this->sucursalDestinoId = $compra[0]->id_sucursal_destino;
            $this->codigo_compra = $compra[0]->codigo_compra;
            $this->fecha_compra = $compra[0]->fecha_compra->format('Y-m-d');
            $this->observacion = $compra[0]->observaciones;
            $this->detalleCompra = $detalleCompra->map(function($item) {
                return [
                    'id' => $item->id_producto,
                    'descripcion' => $item->descripcion,
                    'cantidad' => $item->cantidad,
                    'precio_unitario' => $item->precio_unitario,
                    'iva' => $item->iva,
                    'sub_total' => $item->sub_total,
                ];
            })->toArray();
            $this->iva = $compra[0]->con_iva ? true : false;
            $this->totalCompra = $this->calcularTotalCompra();
            $this->totalIva = $this->calcularTotalIva();
        }
        else {
            $this->codigo_compra = $this->generateCodigoCompra();
            $this->fecha_compra = date('Y-m-d'); 
        }
    }

    public function generateCodigoCompra()
    {
        return 'CMP-' . time();
    }

    private function calcularTotalCompra()
    {
        $total = 0;
        foreach ($this->detalleCompra as $producto) {
            $total += $producto['sub_total'];
        }
        return $total;
    }

    private function calcularTotalIva()
    {
        $totalIva = 0;
        foreach ($this->detalleCompra as $producto) {
            $totalIva += $producto['iva']; 
        }
        return $totalIva;
    }

    private function existeProductoEnDetalle($idProducto)
    {
        foreach ($this->detalleCompra as &$producto) {
            if ($producto['id'] == $idProducto) {
                return true;
            }
        }
        return false;
    }

    private function actualizarCantidadProductoEnDetalle($idProducto, $cantidad)
    {
        foreach ($this->detalleCompra as &$producto) {
            if ($producto['id'] == $idProducto) {
                
                $producto['cantidad'] += $cantidad;
                $producto['iva'] =  $this->iva ? $producto['precio_unitario'] * $producto['cantidad'] * 13 : 0;
                $producto['sub_total'] = (($producto['precio_unitario'] * $producto['cantidad']) - $producto['iva'] /100) * 100;
                break;
            }
        }
        $this->totalCompra = $this->calcularTotalCompra();
        $this->totalIva = $this->calcularTotalIva();
    }
    
    public function agregarProducto()
    {
        $this->validate();

        unset($this->producto_compra);

        if ($this->existeProductoEnDetalle($this->idProductoSeleccionado)) 
        {
            $this->actualizarCantidadProductoEnDetalle($this->idProductoSeleccionado, $this->cantidadProducto);
        } else {

            $item = $this->productos->where('id', $this->idProductoSeleccionado)->first();    
            // CORREGIDO: Calcular IVA correctamente
            $ivaCalculado = $this->iva ? $item->costo * 13 * $this->cantidadProducto : 0;
            $subTotalCalculado = (($item->costo * $this->cantidadProducto) - $ivaCalculado / 100) * 100;
            
            $producto_compra = [
                'id' => $this->idProductoSeleccionado,
                'descripcion' => $item->nombre . ' - Talla: ' . $item->talla,
                'cantidad' => $this->cantidadProducto,
                'precio_unitario' => $item->costo,
                'iva' => $ivaCalculado,
                'sub_total' => $subTotalCalculado,
            ];

            array_push($this->detalleCompra, $producto_compra);
        }
        
        $this->totalCompra = $this->calcularTotalCompra();
        $this->totalIva = $this->calcularTotalIva();
        $this->cantidadProducto = '';
    }

    public function guardarCompra()
    {
        if ( !$this->sucursalDestinoId ) {
            $this->addError('sucursalDestinoId', 'Debe seleccionar una sucursal');
            $this->js("$('#observacionModal').modal('hide')");
            return;
        }

        if ( !$this->fecha_compra ) {
            $this->addError('fecha_compra', 'Debe seleccionar una fecha de compra');
            $this->js("$('#observacionModal').modal('hide')");
            return;
        }

        if (count($this->detalleCompra) == 0) {
            $this->addError('detalleCompra', 'Debe agregar al menos un producto a la compra');
            $this->js("$('#observacionModal').modal('hide')");
            return;
        }
        
        DB::table('compras')->updateOrInsert(
            ['codigo_compra' => $this->codigo_compra],
            [
                'id_usuario' => auth()->user()->id,
                'id_sucursal_destino' => $this->sucursalDestinoId,
                'fecha_compra' => $this->fecha_compra,
                'total_compra' => $this->totalCompra,
                'total_iva' => $this->totalIva,
                'con_iva' => $this->iva,
                'observaciones' => $this->observacion,
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

        $compraGuardada = DB::table('compras')->where('codigo_compra', $this->codigo_compra)->first();
        
        DetalleCompra::where('id_compra', $compraGuardada->id)->delete();

        foreach ($this->detalleCompra as $producto) {
            $nuevoDetalleCompra = new DetalleCompra();
            $nuevoDetalleCompra->id_compra = $compraGuardada->id;
            $nuevoDetalleCompra->id_producto = $producto['id'];
            $nuevoDetalleCompra->descripcion = $producto['descripcion'];
            $nuevoDetalleCompra->cantidad = $producto['cantidad'];
            $nuevoDetalleCompra->precio_unitario = $producto['precio_unitario'];
            $nuevoDetalleCompra->iva = $producto['iva'];
            $nuevoDetalleCompra->sub_total = $producto['sub_total'];
            $nuevoDetalleCompra->save();
        }        

        session()->flash('mensaje-exito', 'Compra ' . $compraGuardada->codigo_compra . ' guardada exitosamente.');
        // redirect()->route('home_compras', ['id_sucursal' => $compraGuardada->id_sucursal_destino]);
        $this->redirectRoute('home_compras', ['id_sucursal' => $compraGuardada->id_sucursal_destino]);
    }

    public function eliminarProducto($idProducto)
    {
        $this->detalleCompra = array_filter($this->detalleCompra, function ($producto) use ($idProducto) {
            return $producto['id'] != $idProducto;
        });

        $this->totalCompra = $this->calcularTotalCompra();
    }

    public function seleccionarProducto($idProducto)
    {
        if(in_array($this->productosSelect[$idProducto['data']],$this->productosSelect)){
            $this->idProductoSeleccionado = (int) $idProducto['data'];
        }
    }

    public function cancelarCompra()
    {
        $this->detalleCompra = [];
        $this->totalCompra = 0;
        $this->codigo_compra = $this->generateCodigoCompra();
        $this->fecha_compra = date('Y-m-d');
        $this->observacion = null;
        
        session()->flash('mensaje-exito', 'Compra cancelada exitosamente.');
        redirect()->route('home_compras', ['id_sucursal' => $this->sucursalDestinoId]);
    }

    public function calcularIva()
    {
        foreach ($this->detalleCompra as &$producto) {
            // CORREGIDO: Ya está bien, pero unificamos el redondeo
            $producto['iva'] = $this->iva ? $producto['precio_unitario'] * 13 * $producto['cantidad'] : 0;
            $producto['sub_total'] = (($producto['precio_unitario'] * $producto['cantidad']) - $producto['iva'] / 100) * 100;
        }
        $this->totalCompra = $this->calcularTotalCompra();
        $this->totalIva = $this->calcularTotalIva(); // Asegurar que también se actualice
    }

    public function render()
    {
        return view('livewire.compra.detalle-productos');
    }
}
