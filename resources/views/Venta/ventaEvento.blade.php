@extends('layouts.plantillabase')

@section('title','Venta en un Evento')

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
        #efectivoRecebido, #descuentoVenta, #totalinput, #cambioinput {
           border: 0;
           width: 100%;
           height: auto;
        }
        #efectivoRecebido:focus , #descuentoVenta:focus , #totalinput:focus, #cambioinput:focus{
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
            <h4>Realizando Venta en el Evento: 
                <span class="h4" style="color: #512BFA">
                    @isset($evento)
                        {{ $evento[0]->nombre }}
                    @endisset
                </span>
                <span class="h4" style="color: #512BFA">
                    @isset($evento)
                        {{ $evento[0]->fecha_evento }}
                    @endisset
                </span>
            </h4>
        </div>
    </div>
@endsection

@section('content')
    @livewire('realizar-venta')
@endsection


@push('scripts')
    <script src="{{ asset('jquery/jquery-3.7.1.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- <script>
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

    </script> --}}

    <script>
        /**
         * Realizar la Venta BOTON
        */
        // $("#realizarVenta").click(function(){
        //     $("#staticBackdrop").modal('hide');
        //     Swal.fire({
        //         title: "Esta seguro de realizar la venta?",
        //         // text: "You won't be able to revert this!",
        //         icon: "warning",
        //         showCancelButton: true,
        //         confirmButtonColor: "#3085d6",
        //         cancelButtonColor: "#d33",
        //         confirmButtonText: "Si, estoy seguro"
        //         }).then((result) => {
        //            if (result.isConfirmed) {
        //               if (arrayProductosVenta.length > 0) 
        //               {
        //                 if ( parseInt($("#selectTipoPago").val()) > 0) {
        //                     $.ajax({
        //                         type: "POST",
        //                         url: "/realizar_venta",
        //                         data: {
        //                                 "productos":arrayProductosVenta, 
        //                                 "idTipoPago":$("#selectTipoPago").val(), 
        //                                 "nit_cliente":$("#nit_cliente").val(),
        //                                 "nombre_cliente":$("#nombre_cliente").val(),
        //                                 "totalVenta":$("#total").text(),
        //                                 "efectivo_recibido":$("#efectivoRecebido").val() == '' ? 0:$("#efectivoRecebido").val(),
        //                                 "descuento_venta":$("#descuentoVenta").val() == '' ? 0:$("#descuentoVenta").val(),
        //                                 "cambio_venta":$("#cambio").text(),
        //                                 "envio":$("#envio").val(),
        //                                 "referencia":$("#referencia").val(),
        //                                 "observacion":$("#observacion").val(),
        //                               },
        //                         success: function (response) {
        //                             var respuesta = JSON.stringify(response);
        //                             var respuesta = JSON.parse(respuesta);
        //                             console.log(respuesta);
        //                             if (respuesta.estado == 1) {
        //                                 Swal.fire({
        //                                     title: "Venta Realizada Exitosamente!",
        //                                     // text: "You clicked the button!",
        //                                     icon: "success",
        //                                     timer: 1500
        //                                 });
        //                                 setTimeout(() => {
        //                                     var win = window.open('/'+respuesta.nombreArchivo, '_blank');
        //                                     $(location).attr('href','/detalle_ventas_rango_fechas');                            
        //                                 }, 1600);
                                        
        //                             } else {
        //                                 Swal.fire({
        //                                     title: "Hubo un Error!",
        //                                     text: "Contactese con el administrador"+ response.estado,
        //                                     icon: "error"
        //                                 }); 
        //                             }
        //                         }
        //                     }); 
        //                 } else {
        //                     Swal.fire({
        //                         title: "No selecciono un tipo de pago",
        //                         // text: "That thing is still around?",
        //                         icon: "error",
        //                     });  
        //                 }
                        
        //               } else {
        //                 Swal.fire({
        //                     title: "No Existe items registrados para la venta",
        //                     // text: "That thing is still around?",
        //                     icon: "error",
        //                 });
        //               }
        //             }
        //         });
        // });

        $(document).ready(function(){
            $("#home").removeClass('active');
            $("#venta").addClass('active');
        });
    </script>
@endpush

