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
        #efectivoRecebido, #descuentoVenta{
           border: 0;
           width: 100%;
           height: auto;
        }
        #efectivoRecebido:focus , #descuentoVenta:focus{
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
                   // console.log(response);
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

                        // console.log(arrayProductosVenta);

                        $(".itemProductoVenta").remove();

                        $.each(arrayProductosVenta, function (indexInArray, valueOfElement) { 
                             $("#contenidoItemsProductos").before(' \
                                <tr class="text-center itemProductoVenta" style="border-bottom: solid 1px black;" data-producto="'+valueOfElement.id_producto+'"> \
                                    <th scope="row">'+valueOfElement.cantidad+'</th> \
                                    <td>'+valueOfElement.nombre_producto + ' ' + valueOfElement.talla_producto +'</td> \
                                    <td> ' +valueOfElement.precio_producto+ '</td> \
                                    <!--td>'+ 0 +'</td--> \
                                    <td class="subtotal">'+ (valueOfElement.cantidad * valueOfElement.precio_producto ).toFixed(2) +'</td> \
                                </tr> \
                            ');
                        });

                        // Se esta calculando el total de la venta
                        $('.subtotal').each(function(index) {
                            sumaTotal = sumaTotal + parseFloat($(this).text());
                            $("#total").text(sumaTotal.toFixed(2));
                        });

                        let descuento_venta = $("#descuentoVenta").val() == '' ? 0 : $("#descuentoVenta").val();
                        // Esto es el calculo en porcentajes 
                        // let total_venta = (parseFloat(sumaTotal)-parseFloat(sumaTotal)*parseFloat(descuento_venta)/100).toFixed(2);
                        // Esto es el calculo en Bolivianos
                        let total_venta = (parseFloat(sumaTotal)-parseFloat(descuento_venta)).toFixed(2);

                        $("#total").text(total_venta);

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

        $( "#descuentoVenta" ).on( "keydown", function( event ) {
            let total_venta = 0;

            if( event.which == 13 )
            {
                let descuento_venta = $("#descuentoVenta").val();

                $('.subtotal').each(function(index) {
                  total_venta = total_venta + parseFloat($(this).text());
                });

                // Calculo del descuento en porcentajes
                // total_venta = (parseFloat(total_venta)-parseFloat(total_venta)*parseFloat(descuento_venta)/100).toFixed(2)

                // Calculo del descuento en bolivianos
                total_venta = (parseFloat(total_venta)-parseFloat(descuento_venta)).toFixed(2)

                $("#total").text(total_venta);

                let efectivo_recibido = $("#efectivoRecebido").val() == '' ? 0:$("#efectivoRecebido").val();
                $( "#cambio" ).text((parseFloat(efectivo_recibido)-parseFloat(total_venta)).toFixed(2));
            }

            cambiarNumeroALiterarEfectivo($("#total").text());
        });

        /**
         * Alterna entre rojo y transparente los resgistros a eliminar
        */
        $("#itemsEliminar").on('click',"tr.itemProductoVenta",function(event){
            
            if($(this).css("background-color") == 'rgb(255, 0, 0)')
            {
                $(this).css("background-color","");
            }else{
                $(this).css("background-color","red");
            }
        });


        /**
         * Captura la tecla del o suprimir para eliminar los registros seleccionados y recalcula los datos de Total, cambio
        */
        $('html').keyup(function(e){ 
            if(e.keyCode == 46){
                if( e.target.tagName != 'INPUT')
                {
                    // alert(e.target.tagName);
                    let auxArray = [];
                    $.each($("tr.itemProductoVenta"), function (indexInArray, valueOfElement) { 
                        console.log($(valueOfElement).css("background-color"));
                        if ($(valueOfElement).css('background-color') != 'rgb(255, 0, 0)' ) {
                            let producto = $(valueOfElement).attr("data-producto");
                            let elemtoProductoArray = arrayProductosVenta.find((elemento) => elemento.id_producto == producto);
                            auxArray.push(elemtoProductoArray);
                        } 
                    });

                    arrayProductosVenta = auxArray;
                    let sumaTotal = 0;

                    $(".itemProductoVenta").remove();
                    $.each(arrayProductosVenta, function (indexInArray, valueOfElement) { 
                        $("#contenidoItemsProductos").before(' \
                        <tr class="text-center itemProductoVenta" style="border-bottom: solid 1px black;" data-producto="'+valueOfElement.id_producto+'"> \
                        <th scope="row">'+valueOfElement.cantidad+'</th> \
                        <td>'+valueOfElement.nombre_producto + ' ' + valueOfElement.talla_producto +'</td> \
                        <td> ' +valueOfElement.precio_producto+ '</td> \
                        <!--td>'+ 0 +'</td--> \
                        <td class="subtotal">'+ (valueOfElement.cantidad * valueOfElement.precio_producto).toFixed(2) +'</td> \
                        </tr> \
                        ');
                        sumaTotal = sumaTotal + (valueOfElement.cantidad * valueOfElement.precio_producto).toFixed(2);
                    });
                    //$("#total").text(parseFloat(sumaTotal));
                    
                    let descuento_venta = $("#descuentoVenta").val() == '' ? 0 : $("#descuentoVenta").val();
                    // Calculo del total de la venta en porcentaje
                    // let total_venta = (parseFloat(sumaTotal)-parseFloat(sumaTotal)*parseFloat(descuento_venta)/100).toFixed(2);
                    // Calculo del total de la venta en bolivianos
                    let total_venta = (parseFloat(sumaTotal)-parseFloat(descuento_venta)).toFixed(2);
                    $("#total").text(total_venta);
                    cambiarNumeroALiterarEfectivo(total_venta);
                    let efectivo_recibido = $("#efectivoRecebido").val() == '' ? 0:$("#efectivoRecebido").val();
                    $( "#cambio" ).text((parseFloat(efectivo_recibido)-parseFloat(total_venta)).toFixed(2));

                }
            } 
        });

        /**
         * Realizar la Venta BOTON
        */
        $("#realizarVenta").click(function(){
            $("#staticBackdrop").modal('hide');
            Swal.fire({
                title: "Esta seguro de realizar la venta?",
                // text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Si, estoy seguro"
                }).then((result) => {
                   if (result.isConfirmed) {
                      if (arrayProductosVenta.length > 0) 
                      {
                        if ( parseInt($("#selectTipoPago").val()) > 0) {
                            $.ajax({
                                type: "POST",
                                url: "/realizar_venta",
                                data: {
                                        "productos":arrayProductosVenta, 
                                        "idTipoPago":$("#selectTipoPago").val(), 
                                        "nit_cliente":$("#nit_cliente").val(),
                                        "nombre_cliente":$("#nombre_cliente").val(),
                                        "totalVenta":$("#total").text(),
                                        "efectivo_recibido":$("#efectivoRecebido").val() == '' ? 0:$("#efectivoRecebido").val(),
                                        "descuento_venta":$("#descuentoVenta").val() == '' ? 0:$("#descuentoVenta").val(),
                                        "cambio_venta":$("#cambio").text(),
                                        "envio":$("#envio").val(),
                                        "referencia":$("#referencia").val(),
                                        "observacion":$("#observacion").val(),
                                      },
                                success: function (response) {
                                    var respuesta = JSON.stringify(response);
                                    var respuesta = JSON.parse(respuesta);
                                    console.log(respuesta);
                                    if (respuesta.estado == 1) {
                                        Swal.fire({
                                            title: "Venta Realizada Exitosamente!",
                                            // text: "You clicked the button!",
                                            icon: "success",
                                            timer: 1500
                                        });
                                        setTimeout(() => {
                                            var win = window.open('/'+respuesta.nombreArchivo, '_blank');
                                            $(location).attr('href','/detalle_ventas_rango_fechas');                            
                                        }, 1600);
                                        
                                    } else {
                                        Swal.fire({
                                            title: "Hubo un Error!",
                                            text: "Contactese con el administrador"+ response.estado,
                                            icon: "error"
                                        }); 
                                    }
                                }
                            }); 
                        } else {
                            Swal.fire({
                                title: "No selecciono un tipo de pago",
                                // text: "That thing is still around?",
                                icon: "error",
                            });  
                        }
                        
                      } else {
                        Swal.fire({
                            title: "No Existe items registrados para la venta",
                            // text: "That thing is still around?",
                            icon: "error",
                        });
                      }
                    }
                });
        });


        $(".modalBtnCerrar").on("click", function(){
            $("#selectTipoPago").val("seleccionarTipoPago");
        });
        

        $(document).ready(function(){
            $("#home").removeClass('active');
            $("#venta").addClass('active');
        });

    </script>
@endpush

