<div>
    <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-5">
            <form action="{{ route('buscar_categoria') }}" method="POST" id="buscarformulario">
                @method('POST')
                @csrf
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Seleccione un Producto: </label>
                    <div class="input-group">
                        <select class="form-select form-control" aria-describedby="" name="id_producto" id="select_producto">
                            <option value="seleccionado" @if (!isset($id_evento) || !isset($_GET['id_evento'])) selected  @endif disabled>Seleccione una opcion...</option>
                                @foreach ($productosEvento as $item)
                                   @if ($item->estado_inventario_interno == 1)
                                      @if ($item->stock > 0)
                                        <option value="{{ $item->id_producto }}" @if ( isset($id_evento) && $item->id == $id_evento )  selected @endif>
                                            {{-- {{ "$item->nombre_producto - $item->talla -  $item->precio Bs. (Stock: $item->stock)" }} --}}
                                            {{ $item->nombre_producto }} - Talla: {{ $item->talla!=''?$item->talla:"ST(Sin Talla)"}} - {{ "$item->precio Bs. (Stock: $item->stock)"}}
                                        </option> 
                                      @else
                                        <option value="{{ $item->id_producto }}" disabled> {{ "$item->nombre_producto - $item->talla - $item->precio Bs. (Stock: $item->stock)" }}</option> 
                                      @endif
                                    @else
                                      <option value="{{ $item->id_evento_user_sucursal }}" disabled>{{ "$item->nombre_producto - $item->talla - $item->precio ... (deshabilitado)" }}</option>
                                   @endif
                                @endforeach
                         </select>
                         {{-- <button class="input-group-text" id="btnFormDataInventario"><i class="fas fa-cart-arrow-down" style="color:green; font-size: 20px;"></i></button> --}}
                    </div>
                </div>
            </form>
        </div>
        
        <div class="col-md-3">
            <div class="mb-3">
                <label for="cantidad" class="form-label">Introduzca la Cantidad: </label>
                <div class="input-group">
                     <input type="number" name="cantidad" id="cantidad" class="form-control" placeholder="0" aria-label="cantidad" aria-describedby="basic-addon1">
                     <button class="input-group-text" id="btnAddItemVenta"><i class="fas fa-cart-arrow-down" style="color:green; font-size: 20px;"></i></button>
                </div>
            </div>
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
                          <th style="width: 20%;">Cantidad</th>
                          <th style="width: 40%; border-left: solid 2px black;">Descripcion</th>
                          <th style="width: 20%; border-left: solid 2px black;">Precio Unitario [Bs.]</th>
                          <!--th style="width: 10%; border-left: solid 2px black;">Descuento [%]</th-->
                          <th style="width: 20%; border-left: solid 2px black;">Subtotal [Bs.]</th>
                        </tr>
                      </thead>
                      <tbody id="itemsEliminar">
                        {{-- <tr class="text-center" style="border-bottom: solid 1px black;" ></tr> --}}
                        
                        <tr style="border: 0;" id="contenidoItemsProductos">
                            <th colspan="5"></th>
                        </tr>
                        {{-- Suma de Items --}}
                        <tr class="sinMargen">
                            <th colspan="2" ></th>
                            <td style="font-weight: bold;">Descuento [Bs]: </td> 
                            <td><input type="text" placeholder="0" id="descuentoVenta"></td>
                          </tr>
                        <tr class="sinMargen">
                          <th colspan="2" ></th>
                          <td style="font-weight: bold;">Total [Bs.]: </td> 
                          <td id="total">0</td>
                        </tr>
                        <tr class="sinMargen">
                            <th colspan="2"></th>
                            <td style="font-weight: bold">Efectivo Recibido [Bs.]: </td> 
                            <td><input type="text" placeholder="0" id="efectivoRecebido"></td>
                        </tr>
                        <tr class="sinMargen">
                            <th colspan="2"></th>
                            <td style="font-weight: bold">Cambio [Bs.]: </td> 
                            <td id="cambio">0</td>
                        </tr>
                        <tr style="border: 0;">
                            <th colspan="5"></th>
                        </tr>
                        <tr style="border: 0; border-top: solid 1px black; border-top-style: dotted; border-bottom: solid 1px black; border-bottom-style: dotted;">
                            <th class="text-begin" colspan="5" style="font-weight: bold"> Son: <abbr id="efectivoLiteral">CERO BOLIVIANOS CON CERO CENTAVOS.</abbr> </th>
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
            {{-- id="realizarVenta" --}}
        </div>
    </div>
    
    
    
    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="staticBackdropLabel">Datos del Cliente: </h5>
              <button type="button" class="btn-close modalBtnCerrar" data-bs-dismiss="modal" aria-label="Close" id=""></button>
            </div>
            <div class="modal-body">
                <div class="input-group mb-3">
                    <label class="input-group-text" for="nit_cliente">Nit del Cliente:</label>
                    <input type="text" class="form-control" id="nit_cliente" placeholder="0">
                </div>
                <div class="input-group mb-3">
                    <label class="input-group-text" for="nombre_cliente">Nombre Cliente:</label>
                    <input type="text" class="form-control" id="nombre_cliente" placeholder="S/N">
                </div>
                <div class="input-group mb-3">
                    <label class="input-group-text" for="selectTipoPago">Tipo de Pago:</label>
                    <select class="form-select" id="selectTipoPago">
                      <option value="seleccionarTipoPago" selected disabled>Seleccione una opcion ....</option>
                      @foreach ($tipoPagos as $tipopago)
                        <option value="{{ $tipopago->id }}">{{ $tipopago->tipo }}</option>    
                      @endforeach
                    </select>
                </div>
                <div class="input-group mb-3">
                    <label class="input-group-text" for="envio">Envios:</label>
                    <input type="text" class="form-control" id="envio" placeholder="Intraduzca el envio">
                </div>
                <div class="input-group mb-3">
                    <label class="input-group-text" for="referencia">Referencia:</label>
                    <input type="text" class="form-control" id="referencia" placeholder="Introduzca la referencia">
                </div>
                <div class="input-group mb-3">
                    <label class="input-group-text" for="observacion">Observaciones:</label>
                    <textarea class="form-control" id="observacion" placeholder="Introduzca la observacion"></textarea>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary modalBtnCerrar" data-bs-dismiss="modal" id="">Cerrar</button>
              <button type="button" class="btn btn-primary" id="realizarVenta">Guardar</button>
            </div>
          </div>
        </div>
    </div>
</div>
