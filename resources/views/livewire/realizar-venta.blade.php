<div>
    <div class="row">
        <div class="col-md-1"></div>

        <div class="col-md-5" wire:ignore>
            <label for="select_producto" class="form-label">Seleccione un Producto: </label>
            <div class="input-group">
                <select class="producto-select2 form-control" 
                        name="id_producto" id="select_producto" 
                        >
                    <option></option>
                    @foreach ($productos as $producto)
                        @if ($producto->stock >= 1)
                            <option value="{{ $producto->id_productos }}"> 
                                    {{ $producto->nombre_productos }} 
                                    - Talla: {{ $producto->talla_productos == '' ? "ST(Sin talla)" : $producto->talla_productos}}
                                    - Precio {{ $producto->precio_productos == '' ? 0 :  $producto->precio_productos }} Bs (Stock: {{ $producto->stock }})
                            </option>
                        @endif
                    @endforeach    
                </select>
            </div>
            @error('idProductoSeleccionado') <span class="error" style="color: red"> Debe seleccionar un producto *</span> @enderror
        </div>
        
        <div class="col-md-3">
            <label for="cantidad" class="form-label">Introduzca la Cantidad: </label>
            <div class="input-group">
                 <input type="number" name="cantidad" id="cantidad" 
                        class="form-control" placeholder="0" 
                        wire:model='cantidadDelProductoSeleccionado' 
                        wire:keydown.enter='almaceneArrayProdcutosVenta' 
                        wire:keydown.tab='almaceneArrayProdcutosVenta'>
                 <button class="input-group-text" id="btnAddItemVenta" wire:click='almaceneArrayProdcutosVenta()'><i class="fas fa-cart-arrow-down" style="color:green; font-size: 20px;"></i></button>
            </div>
            @error('cantidadDelProductoSeleccionado') <span class="error" style="color: red"> La Cantidad debe ser mayor a cero*</span> @enderror
        </div>
        
        <div class="col-md-2"></div>
    </div>
    <br>
    
    <div class="row">
        <div class="container-fluid ">
            <div class="col-md-10">
                <table class="table table-bordered">
                    <thead>
                        <tr class="text-center">
                          <th style="width: 15%;">Cantidad</th>
                          <th style="width: 40%; border-left: solid 2px black;">Descripcion</th>
                          <th style="width: 15%; border-left: solid 2px black;">Precio Unitario [Bs.]</th>
                          <th style="width: 7%; border-left: solid 2px black;">Descuento [Bs.]</th>
                          <th style="width: 15%; border-left: solid 2px black;">Subtotal [Bs.]</th>
                        </tr>
                      </thead>
                      <tbody id="itemsEliminar">
                        {{-- <tr class="text-center" style="border-bottom: solid 1px black;" ></tr> --}}
                        
                        <tr style="border: 0;" id="contenidoItemsProductos">
                            <th colspan="5">
                                @foreach ($productosAVender as $index => $productoventa)
                                    <tr class="text-center itemProductoVenta" style="border-bottom: solid 1px black;">
                                        <th scope="row"> 
                                            <button class="btn" 
                                                    wire:click="eliminarProducto({{ $productoventa["id_producto"] }})" 
                                                    wire:confirm="Esta seguro de eliminar el registro?">
                                                    <i class="fas fa-trash-alt" style="color: red;"></i>
                                            </button>&nbsp;&nbsp;&nbsp;
                                            {{ $productoventa["cantidad"] }} 
                                        </th>
                                        <td> {{ $productoventa["descripcion"] }} </td> 
                                        <td> {{ $productoventa["precio_unitario"] }} </td>
                                        <td> 
                                              <input class="inputSinEstilos" style="width: 100%" type="text" 
                                                    wire:model="productosAVender.{{ $index }}.descuento"
                                                    placeholder="0 Bs." key($productoventa["id_producto"]) wire:change='calcularValoresMonetarios'
                                                    wire:keydown.enter='calcularValoresMonetarios'
                                                    wire:keydown.tab='calcularValoresMonetarios'>
                                        </td>
                                        <td> {{ $productoventa["subtotal"] }} </td> 
                                    </tr> 
                                @endforeach
                            </th>
                        </tr>
                        <tr style="border: 0;">
                            <th colspan="5"></th>
                        </tr>
                        {{-- Suma de Items --}}
                        <tr class="sinMargen">
                            <th colspan="2"></th>
                            <td colspan="2" style="font-weight: bold;">Total Descuento [Bs]: </td> 
                            <td>{{ number_format($descuentoTotal, 2) }}</td>
                        </tr>
                        <tr class="sinMargen">
                          <th colspan="2" ></th>
                          <td colspan="2" style="font-weight: bold;">Total [Bs.]: </td> 
                          <td id="total"> {{ number_format($total, 2) }} </td>
                        </tr>
                        <tr class="sinMargen">
                            <th colspan="2"></th>
                            <td colspan="2" style="font-weight: bold">Efectivo Recibido [Bs.]: </td> 
                            <td><input type="text" placeholder="0" id="efectivoRecebido" 
                                wire:model='efectivoRecivido' wire:keydown.enter='calcularValoresMonetarios' wire:keydown.tab='calcularValoresMonetarios'></td>
                        </tr>
                        <tr class="sinMargen">
                            <th colspan="2"></th>
                            <td colspan="2" style="font-weight: bold">Cambio [Bs.]: </td> 
                            <td id="cambio"> {{ number_format($cambio, 2) }} </td>
                        </tr>
                        <tr style="border: 0;">
                            <th colspan="5"></th>
                        </tr>
                        <tr style="border: 0; border-top: solid 1px black; border-top-style: dotted; border-bottom: solid 1px black; border-bottom-style: dotted;">
                            <th class="text-begin" colspan="5" style="font-weight: bold"> Son: <abbr id="efectivoLiteral">{{ $literalMonto }}</abbr> </th>
                        </tr>
                        <tr style="border: 0;">
                            <th colspan="5"></th>
                        </tr>
                      </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="row text-center">
        <div class="col-md-12" >
            <button type="button" class="btn btn-outline-primary" style="width: 30%;" data-bs-toggle="modal" data-bs-target="#staticBackdrop">Realizar Venta</button> 
        </div>
    </div>
    
    
    
    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="staticBackdropLabel"> Datos del Cliente </h5>
              <button type="button" class="btn-close modalBtnCerrar" data-bs-dismiss="modal" aria-label="Close" id=""></button>
            </div>
            <div class="modal-body">
                <div class="input-group mb-3">
                    <label class="input-group-text" for="nit_cliente">Nit del Cliente:</label>
                    <input type="text" class="form-control" id="nit_cliente" placeholder="0" wire:model='nitCliente'>
                </div>
                <div class="input-group mb-3">
                    <label class="input-group-text" for="nombre_cliente">Nombre Cliente:</label>
                    <input type="text" class="form-control" id="nombre_cliente" placeholder="S/N" wire:model='nombreCliente'>
                </div>
                <div class="input-group mb-3">
                    <label class="input-group-text" for="selectTipoPago">Tipo de Pago:</label>
                    <select class="form-select" id="selectTipoPago" wire:model='idTipoPagoSeleccionado'>
                      <option value="seleccionarTipoPago" selected disabled>Seleccione una opcion ....</option>
                      @foreach ($tipoPagos as $tipopago)
                        <option value="{{ $tipopago->id }}">{{ $tipopago->tipo }}</option>    
                      @endforeach
                    </select>
                </div>
                <div class="input-group mb-3">
                    <label class="input-group-text" for="factura">Factura:</label>
                    <input type="text" class="form-control" id="factura" placeholder="Introduzca el numero de factura" wire:model='factura'>
                </div>
                <div class="input-group mb-3">
                    <label class="input-group-text" for="envio">Envios:</label>
                    <input type="text" class="form-control" id="envio" placeholder="Introduzca el envio" wire:model='envio'>
                </div>
                <div class="input-group mb-3">
                    <label class="input-group-text" for="referencia">Referencia:</label>
                    <input type="text" class="form-control" id="referencia" placeholder="Introduzca la referencia" wire:model='referencia'>
                </div>
                <div class="input-group mb-3">
                    <label class="input-group-text" for="observacion">Observaciones:</label>
                    <textarea class="form-control" id="observacion" placeholder="Introduzca la observacion" wire:model='observacion'></textarea>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary modalBtnCerrar" data-bs-dismiss="modal" id="">Cerrar</button>
              <button type="button" class="btn btn-primary" id="realizarVenta" wire:click='almacenarDatos'>Guardar</button>
            </div>
          </div>
        </div>
    </div>
</div>

@assets
    <style>
        .select2-container--default .select2-results {
            background-color: #d3d3d3; /* Plomo claro */
            /* o usa: background-color: #808080; para plomo más oscuro */
            /* o usa: background-color: #f0f0f0; para plomo muy claro */
        }
        .select2-container--default .select2-selection--single {
            height: 38px; /* Ajusta la altura según tus necesidades */
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 38px; /* Ajusta la línea según la altura */
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 38px; /* Ajusta la altura según tus necesidades */
        }
        .inputSinEstilos {
            border: none;
            background: transparent;
            text-align: center;
        }
    </style>
@endassets

@script
    <script>
        function seleccionarContenidoInput(id) {
            $('#' + id).select();
        }

        // Inicializar Select2
        $('#select_producto').select2({
            placeholder: 'Seleccione una opción...',
            allowClear: true,
            width: '100%'
        });

        // Sincronizar con Livewire 3 usando $wire
        $('#select_producto').on('change', function (e) {
            $wire.set('idProductoSeleccionado', $(this).val())
            console.log("PRueba de consola: " + Livewire.all().find(c => c.name === 'realizar-venta').$wire.idProductoSeleccionado);
        });

        $wire.on('resetSelectProducto', () => {
            $('#select_producto').val(null).trigger('change.select2');
        });

        // Evento ventaAlmacenada
        $wire.on('ventaAlmacenada', (data) => {
            window.open(`/reporte_venta_pdf/${data[0]}/${data[1]}/venta/${data[2]}`, '_blank');
        });

        $wire.on('refreshMe', () => {
            location.reload();
        });

        $("#descuentoVenta").on('click', function () {
            $(this).select();
        });
    </script>
@endscript



{{-- document.addEventListener('livewire:initialized', () => {
            Livewire.on('ventaAlmacenada', ( data ) => {
                // console.log('Datos: ' + data);
                // Aquí puedes abrir el PDF, mostrar alerta, etc.
                // console.log(`/reporte_venta_pdf/${data[0]}/${data[1]}/venta/${data[2]}`);
                window.open(`/reporte_venta_pdf/${data[0]}/${data[1]}/venta/${data[2]}`, '_blank');
            });
    }); --}}