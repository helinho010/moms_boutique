@extends('layouts.plantillabase')

@section('title','Inventario Externo')

@section('css')
    <style>
        div.row > div.container-fluid > div{
            margin: auto;
        }
        div.row > div.container-fluid > div > table, div.row > div.container-fluid > div > table > thead, div.row > div.container-fluid > div > table > thead > tr {
            width: 90%;
            border: solid 2px black;
        }
        .sinMargen{
            border: 0;
        }
        .sinMargen > td {
            border-top: solid 1px black;
            border-bottom: solid 1px black;
            border-left: solid 2px black;
            border-right: solid 1px black;
        }
        #efectivoRecebido{
           border: 0;
           width: 100%;
           height: auto;
        }
        #efectivoRecebido:focus{
           border: 0;
           outline: none;
           width: 100%;
           height: auto;
           font-weight: bold;
        }

    </style>
@endsection

@section('h-title')
    
@endsection

@section('card-title')
    <div class="row">
        <div class="col">
            <h4>Realizando venta en la sucursal: 
                <span class="h4" style="color: #512BFA">
                    @isset($sucursal)
                        {{ $sucursal[0]->razon_social." - ".$sucursal[0]->direccion }}
                    @endisset
                </span>
        </h4>
        </div>
    </div>
@endsection

@section('content')
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
                            @foreach ($productos as $item)
                               @if ($item->estado_inventario_interno == 1)
                                  <option value="{{ $item->id_producto }}" 
                                    @if ( isset($id_evento) && $item->id == $id_evento ) 
                                        selected  
                                    @endif
                                    >{{ "$item->nombre_producto - $item->talla -  $item->precio (Stock: $item->stock)" }}</option>
                                @else
                                  <option value="{{ $item->id_evento_user_sucursal }}" disabled>{{ "$item->nombre_producto - $item->talla -  $item->precio ... (deshabilitado)" }}</option>
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
    <div class="col-md-2"> {{ $request }} </div>
</div>
<br>

<div class="row">
    <div class="container-fluid ">
        <div class="col-md-10">
            <table class="table table-bordered">
                <thead>
                    <tr class="text-center">
                      <th style="width: 10%;">Cantidad</th>
                      <th style="width: 40%; border-left: solid 2px black;">Descripcion</th>
                      <th style="width: 10%; border-left: solid 2px black;">Precio Unitario [Bs.]</th>
                      <th style="width: 10%; border-left: solid 2px black;">Descuento [%]</th>
                      <th style="width: 10%; border-left: solid 2px black;">Subtotal [Bs.]</th>
                    </tr>
                  </thead>
                  <tbody>
                    {{-- <tr class="text-center" style="border-bottom: solid 1px black;" ></tr> --}}
                    
                    <tr style="border: 0;" id="contenidoItemsProductos">
                        <th colspan="5"></th>
                    </tr>
                    {{-- Suma de Items --}}
                    <tr class="sinMargen">
                      <th colspan="3" ></th>
                      <td style="font-weight: bold;">Total [Bs.]: </td> 
                      <td id="total">0</td>
                    </tr>
                    <tr class="sinMargen">
                        <th colspan="3"></th>
                        <td style="font-weight: bold">Efectivo Recibido [Bs.]: </td> 
                        <td><input type="text" placeholder="0" id="efectivoRecebido"></td>
                    </tr>
                    <tr class="sinMargen">
                        <th colspan="3"></th>
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
@endsection


@push('scripts')
    <script src="{{ asset('jquery/jquery-3.7.1.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
         let arrayProductosVenta =[]; 
         $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function cambiarNumeroALiterarEfectivo(efectivo)
        {
            $.ajax({
                   type: "POST",
                   url: "/numeros_a_letras",
                   data: {"efectivo" : parseFloat(efectivo) },
                   success: function (response) {
                   console.log(response);
                   $("#efectivoLiteral").text(response);
                }
            });
        }

        function agregarRegistrsoVenta()
        {
            let id_producto_seleccionado = $("#select_producto").val();
            let cantidad = $("#cantidad").val();
            
            if ($("#cantidad").val() >= 0 && $("#cantidad").val() != '' ) {
                let respuesta = '';
                let sumaTotal = 0;
                
                $.ajax({
                    type: "POST",
                    url: "/buscarProductoId",
                    data: {'id':id_producto_seleccionado},
                    success: function (response) {
                                               
                        if( typeof arrayProductosVenta.find((producto) => producto.id_producto == id_producto_seleccionado) !== 'undefined' )
                        {
                            let aux = arrayProductosVenta.find((producto) => producto.id_producto == id_producto_seleccionado);
                            aux.cantidad = parseInt(aux.cantidad)  + parseInt(cantidad);
                        }else{
                            let objetoProductoVenta = {
                                "id_producto":response[0].id,
                                "nombre_producto":response[0].nombre,
                                "precio_producto":response[0].precio,
                                "talla_producto":response[0].talla,
                                "id_categoria":response[0].id_categoria,
                                "cantidad": cantidad,
                            };
                            arrayProductosVenta.push(objetoProductoVenta);
                        }

                        console.log(arrayProductosVenta);

                        $(".itemProductoVenta").remove();
                        $.each(arrayProductosVenta, function (indexInArray, valueOfElement) { 
                             $("#contenidoItemsProductos").before(' \
                                <tr class="text-center itemProductoVenta" style="border-bottom: solid 1px black;"> \
                                    <th scope="row">'+valueOfElement.cantidad+'</th> \
                                    <td>'+valueOfElement.nombre_producto + ' ' + valueOfElement.talla_producto +'</td> \
                                    <td> ' +valueOfElement.precio_producto+ '</td> \
                                    <td>'+ 0 +'</td> \
                                    <td class="subtotal">'+ (valueOfElement.cantidad * valueOfElement.precio_producto - valueOfElement.cantidad * valueOfElement.precio_producto * 13/100 ).toFixed(2) +'</td> \
                                </tr> \
                            ');
                        });

                        $('.subtotal').each(function(index) {
                            console.log(index + ": " + $(this).text());
                            sumaTotal = sumaTotal + parseFloat($(this).text());
                            $("#total").text(sumaTotal.toFixed(2));
                        });

                        cambiarNumeroALiterarEfectivo($("#total").text());

                        let efectivo_recibido = $("#efectivoRecebido").val() == '' ? 0:$("#efectivoRecebido").val();
                        let total_compra = $("#total").text();
                        $( "#cambio" ).text((parseFloat(efectivo_recibido)-parseFloat(total_compra)).toFixed(2));

                        $("#select_producto").val('seleccionado');
                        $("#cantidad").val('');
                    },
                    error: function(error){
                        console.log(error);
                    }
                });   
            } else {
                alert("Error en la casilla de CANTIDAD");
                $("#cantidad").focus();
            } 
        }

        $("#btnAddItemVenta").click(function(){
            agregarRegistrsoVenta();
        });

        $( "#cantidad" ).on( "keydown", function( event ) {
            if( event.which == 13 )
            {
                agregarRegistrsoVenta();
            }
        });

        $( "#efectivoRecebido" ).on( "keydown", function( event ) {
            if( event.which == 13 )
            {
                //$( "#cambio" ).html( event.type + ": " +  event.which );
                let efectivo_recibido = $("#efectivoRecebido").val() == '' ? 0:$("#efectivoRecebido").val();
                let total_compra = $("#total").text();
                $( "#cambio" ).text((parseFloat(efectivo_recibido)-parseFloat(total_compra)).toFixed(2));
            }
        });

    </script>
@endpush

