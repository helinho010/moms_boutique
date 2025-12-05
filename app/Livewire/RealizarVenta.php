<?php

namespace App\Livewire;

use App\Http\Controllers\VentaController;
use App\Models\Cliente;
use App\Models\DetalleVenta;
use App\Models\Evento;
use App\Models\InventarioExterno;
use App\Models\InventarioInterno;
use App\Models\Producto;
use App\Models\Sucursal;
use App\Models\TipoPago;
use App\Models\Venta;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\Attributes\Validate;
use Luecano\NumeroALetras\NumeroALetras;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Type\Integer;


class RealizarVenta extends Component
{
    public string $tipoVenta;
    public int $id_tipo_venta; // Variable donde se almacena el id de Sucursal o Eventa
    public $sucursalDB;
    public $eventoDB;
    // public $productosEvento;
    public $productos;
    public $tipoPagos;

    // Varialbes de los modelos 
    #[Validate('required|numeric')]
    public $idProductoSeleccionado;

    #[Validate('required|min:1|numeric')]
    public $cantidadDelProductoSeleccionado;

    // Valores monetarios
    public $subtotal;
    public $descuentoItem = [];
    public $descuentoTotal = 0;
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
        $this->tipoPagos = TipoPago::where('estado',1)->get();
        
        if ($this->tipoVenta === 'sucursal') {
            $this->sucursalDB = Sucursal::obtenerSucursal($this->id_tipo_venta);
            $this->productos = InventarioInterno::inventarioXSucurusal($this->id_tipo_venta)->get();

        }else{
            
            $this->eventoDB = Evento::where('id',$this->id_tipo_venta)->get();    
            $this->productos = InventarioExterno::inventarioXEvento($this->id_tipo_venta)->get();
        }
        
        $this->idProductoSeleccionado = 'seleccionado';
        $this->cantidadDelProductoSeleccionado = 0;
    }

    public function literalTotal ()
    {
        // Literal de monto total
        $litNum = new NumeroALetras();

        $litNum->apocope = true;
        
        if ($this->total >= 0){
            $this->literalMonto = $litNum->toMoney($this->total, 2, 'bolivianos', 'centavos');    
        }else{
            $this->js("alert('El total de la factura no puede ser negativo')");
        }
    }

    public function mount()
    {

        $this->valoresIniciales();
        // $this->descuento = "0.0";
        $this->total = "0.0";
        $this->efectivoRecivido = "0.0";
        $this->cambio = "0.0";
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

        // Buscar producto según tipo de venta
        if ($this->tipoVenta === 'sucursal') {
            $productoBuscado = InventarioInterno::buscarItemInvetario(
                $this->idProductoSeleccionado,
                $this->id_tipo_venta
            )->first();
        } else {
            // Código para el evento inventario interno
            return; 
        }

        if (!$productoBuscado) {
            // Podrías lanzar un error o un return temprano si no encuentra el producto
            return;
        }

        // Normalizar valores
        $precioUnitario = floatval($productoBuscado->precio_productos ?? 0);
        $cantidadNueva = intval($this->cantidadDelProductoSeleccionado);
        $descripcion = $productoBuscado->nombre_productos 
            . " - Talla: " . ($productoBuscado->talla_productos ?: "ST (Sin Talla)");

        $coleccion = collect($this->productosAVender);

        // Buscar producto en la colección
        $posicion = $coleccion->search(fn($item) => $item['id_producto'] == $this->idProductoSeleccionado);

        if ($posicion !== false) {
            // Ya existe → actualizar
            $this->productosAVender[$posicion]['cantidad'] += $cantidadNueva;
            $this->productosAVender[$posicion]['subtotal'] =
                $precioUnitario * $this->productosAVender[$posicion]['cantidad'];
        } else {
            // No existe → agregar
            $this->productosAVender[] = [
                "id_producto"     => $productoBuscado->id_productos,
                "cantidad"        => $cantidadNueva,
                "descripcion"     => $descripcion,
                "precio_unitario" => $precioUnitario,
                "subtotal"        => $precioUnitario * $cantidadNueva,
                "descuento"       => 0,
            ];
        }

        // Resetear selección
        $this->idProductoSeleccionado = 'seleccionado';
        $this->cantidadDelProductoSeleccionado = 0;        

        // Recalcular totales
        $this->calcularValoresMonetarios();
    }


    public function calcularValoresMonetarios()
    {
        $this->total = 0;
        $this->descuentoTotal = 0;

        foreach ($this->productosAVender as $key => $producto) 
        {

            $cantidad = floatval($producto["cantidad"]);
            $precio = floatval($producto["precio_unitario"]);
            $descuento = floatval($producto["descuento"] ?? 0);

            $subtotal = ($precio * $cantidad) - $descuento;

            // Actualiza el subtotal en el array
            $this->productosAVender[$key]["subtotal"] = $subtotal;

            // Suma total y descuento total
            $this->total += $subtotal;
            $this->descuentoTotal += $descuento;
        }

        $this->descuentoTotal = number_format($this->descuentoTotal != "" ? $this->descuentoTotal : 0 , 2);
        //$this->total = number_format($this->total - ($this->descuentoTotal!="" ? $this->descuentoTotal:0), 2);    
        $this->efectivoRecivido = number_format($this->efectivoRecivido != "" ? floatval($this->efectivoRecivido):0, 2);
        $this->cambio = number_format($this->efectivoRecivido - $this->total,2);
    }

    public function exportarPdf($idVenta)
    {
        
    }


    public function almacenarDatos()
    {
        
        $cliente = new Cliente();
        $cliente->nit_ci = $this->nitCliente != "" ? $this->nitCliente : 0 ;
        $cliente->razon_social = $this->nombreCliente != "" ? $this->nombreCliente : "S/N" ;
        $cliente->save();

        $venta = new Venta();
        if ($this->tipoVenta === 'sucursal') {
            $venta->id_sucursal = $this->id_tipo_venta;
            $venta->id_evento = 0;
        }else{
            $venta->id_sucursal = 0;
            $venta->id_evento = $this->id_tipo_venta;
        }

        $venta->id_usuario = auth()->user()->id;
        $venta->id_cliente = $cliente->id;
        $venta->descuento = $this->descuentoTotal;
        $venta->total_venta = $this->total;
        $venta->efectivo_recibido = $this->efectivoRecivido;
        $venta->cambio = $this->cambio;
        $venta->id_tipo_pago = $this->idTipoPagoSeleccionado;
        $venta->envio = $this->envio;
        $venta->referencia = $this->referencia;
        $venta->observacion = $this->observacion;
        $venta->nombre_pdf = "";
        $venta->save();        

        foreach ($this->productosAVender as $key => $producto) 
        {
            $detalleVenta = new DetalleVenta();
            $detalleVenta->id_venta = $venta->id;
            $detalleVenta->id_producto = $producto["id_producto"];
            $detalleVenta->cantidad = $producto["cantidad"];
            $detalleVenta->descuento_item = $producto["descuento"];
            $detalleVenta->descripcion = $producto["descripcion"];
            $detalleVenta->precio_unitario = $producto["precio_unitario"];
            $detalleVenta->subtotal = $producto["subtotal"];
            $detalleVenta->save();

            
            if ($this->tipoVenta === "sucursal"){
                InventarioInterno::disminuirStock($producto["id_producto"], $producto["cantidad"]);
            }else{
                $productoInventarioExterno = InventarioExterno::where('id_evento',session('eventoSeleccionadoParaVenta'))
                                                      ->where('id_producto',$producto["id_producto"])          
                                                      ->update(['cantidad'=> DB::raw('cantidad-'.$producto["cantidad"])]);
            }
            
        }

        // $this->exportarPdf($venta->id);
        $this->js("$('#staticBackdrop').modal('hide');");
        $this->mount();
        // dd($venta->id,$this->sucursalDB->id, $this->tipoVenta);
        $this->dispatch('ventaAlmacenada',
            $this->tipoVenta, $this->sucursalDB->id, $venta->id 
        );
        // return Storage::disk("eventos")->download($nombreArchivo);
    }

    public function eliminarProducto($id_producto)
    {
        foreach ($this->productosAVender as $key => $producto) 
        {
          if($producto["id_producto"] == $id_producto )
          {
            unset($this->productosAVender[$key]);
            $this->productosAVender = array_values($this->productosAVender);
            break;
          }
        }

        $this->calcularValoresMonetarios();
    }


    public function render()
    {
        $this->valoresIniciales();
        $this->literalTotal();
        return view('livewire.realizar-venta');
    }
}
