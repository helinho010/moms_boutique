@extends('layouts.plantillabase')

@section('title', "Editar Cierre Caja")

@section('mensaje-errores')
    @if (session('exito'))
        <x-formulario.mensaje-error-validacion-inputs color="success">
            <h5>{{ session('exito') }}</h5>
        </x-formulario.mensaje-error-validacion-inputs>
    @endif

    @if (session('error'))
        <x-formulario.mensaje-error-validacion-inputs color="danger">
            <h5>{{ session('error') }}</h5>
        </x-formulario.mensaje-error-validacion-inputs>
    @endif

    @if ($errors->any())
    <x-formulario.mensaje-error-validacion-inputs color="warning">
        <h5>Error al enviar datos al Sistema</h5>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </x-formulario.mensaje-error-validacion-inputs>
    @endif
@endsection

@section('card-title')
    <div class="col">
        <h4><strong>Editar Cierre de Caja</strong></h4>
    </div>
@endsection

@section('content')

<div class="row" style="font-size: 1rem; font-weight: bold">
    <div class="col">
        <span class="h5">Ventas del Sistema:</span>
    </div>
    <div class="col text-center">
        <span class="h5" id="ventaSistema"> {{$cierre->venta_sistema}} </span>
        <span>Bs</span>
    </div>
    <div class="col-1" id="load" hidden>
        <div class="spinner-grow text-primary spinner-grow-sm" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <hr>
</div>

<form action="{{ route('actualizar_cierre',['id'=> $cierre->id]) }}" method="post" class="row" id="frm-edit-cierre-caja">
    @csrf
    @method("PATCH")

    @php
    $fechahoy = date('Y-m-d');
    @endphp

    <div class="row">
        <div class="col">
            <x-formulario.label for="fecha">Sucursal:</x-formulario.label>
            <x-formulario.select id="sucursal" name="id_sucursal">
                <option value="0" disabled>Seleccione una opcion</option>
                @foreach ($sucursales as $sucursal)
                  <option value="{{ $sucursal->id }}" @if ( $sucursal->id == $cierre->id_sucursal) selected @endif>
                    {{ $sucursal->direccion }}
                  </option>
                @endforeach
            </x-formulario.select>
        </div>

        <div class="col">
            <x-formulario.label for="fecha">Fecha de Cierre:</x-formulario.label>
            <x-formulario.input tipo="date" :value="$cierre->fecha_cierre" name="fecha_cierre" id="fecha" placeholder="" />
        </div>

    </div>

    <div class="row">

        <div class="col">
            <x-formulario.label for="efectivo">Efectivo Bs.:</x-formulario.label>
            <x-formulario.input tipo="text" name="efectivo"
                                id="efectivo" placeholder="Introduzca el efectivo" 
                                value="{{ $cierre->efectivo }}"
            />
        </div>

        <div class="col">
            <x-formulario.label for="tarjeta">Tarjeta Bs.:</x-formulario.label>
            <x-formulario.input tipo="text" name="tarjeta" 
                                id="tarjeta" placeholder="Introduzca el efectivo"
                                value="{{ $cierre->tarjeta }}" 
            />
        </div>
    </div>

    <div class="row">
        <div class="col">
            <x-formulario.label for="qr">QR Bs.:</x-formulario.label>
            <x-formulario.input tipo="text" name="qr" 
                                id="qr" placeholder="Introduzca el efectivo" 
                                value="{{ $cierre->qr }}"
            />
        </div>

        <div class="col">
            <x-formulario.label for="transferencia">Transferencia Bs.:</x-formulario.label>
            <x-formulario.input tipo="text" name="transferencia" 
                                id="transferencia" placeholder="Introduzca el efectivo"
                                value="{{ $cierre->transferencia }}" 
            />
        </div>
    </div>

    <hr style="margin-top: 18px;">
    <div class="row">
        <div class="col">
            <h5>Total declarado:</h5>
        </div>
        <div class="col text-center">
            <span class="h5" id="totalDeclarado">{{ $cierre->total_declarado }}</span>
            <span> Bs</span>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <h5>Diferencia:</h5>
        </div>
        <div class="col text-center">
            <span class="h5" id="diferencia">
                {{ $cierre->venta_sistema - $cierre->total_declarado }}
            </span>
            <span> Bs</span>
            <span id="sobranteFaltante"></span>
        </div>
    </div>
    <hr>

    <input type="text" value="{{ $cierre->venta_sistema }}" name="venta_sistema" id="venta_sistema" hidden>
    <input type="text" value="{{ $cierre->total_declarado }}" name="total_declarado" id="total_declarado" hidden>

    <x-formulario.label for="observacion">Observacion: </x-formulario.label>
    <x-formulario.textarea name="observacion" id="observacion" placeholder="Tiene alguna observacion?">
        {{ $cierre->observacion }}
    </x-formulario.textarea>

    <div class="row" style="padding-top: 10px">
        <div class="col"></div>
        <div class="col">
            <a href="{{ route('home_caja', ["id_sucursal" => $id_sucursal]) }}" class="btn btn-danger">Volver</a>
            <button type="button" class="btn btn-success" onclick="document.getElementById('frm-edit-cierre-caja').submit();">Guardar</button>
        </div>
        <div class="col"></div>
    </div>
</form>
@endsection


@push('scripts')
    <script src="{{ asset('jquery/jquery-3.7.1.min.js') }}"></script>
    
    <script>
        $.ajaxSetup({
            headers: {
             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function sumarValores(){
            // Captura de valores de los inputs
            let efectivo = $("#efectivo").val() != "" ?  parseFloat($("#efectivo").val()) : 0.0 ;
            let tarjeta = $("#tarjeta").val() != "" ?  parseFloat($("#tarjeta").val()) : 0.0 ;
            let transferencia = $("#transferencia").val() != "" ?  parseFloat($("#transferencia").val()) : 0.0 ;
            let qr = $("#qr").val() != "" ?  parseFloat($("#qr").val()) : 0.0 ;
            let sum = (efectivo + tarjeta + transferencia +  qr).toFixed(2);
            $("#totalDeclarado").text( sum );

            //Captura de datos para ser recalculados
            let ventaSistema = parseFloat($("#ventaSistema").text());
            let totalDeclarado = parseFloat($("#totalDeclarado").text());
            let diferencia = parseFloat($("#diferencia").text());
            let dif = (ventaSistema - totalDeclarado).toFixed(2);
            console.log("----->" + ventaSistema);
            // Nuevos valores
            $("#totalDeclarado").text( sum );
            $("#total_declarado").val( sum );
            $("#diferencia").text(dif);

            if ( dif == 0 ) {
                $("#sobranteFaltante").text("");
            } else if (dif > 0) {
                $("#sobranteFaltante").text("(Faltante)");
                $("#sobranteFaltante").css("color", "red");
            } else {
                $("#sobranteFaltante").text("(Sobrante)");
                $("#sobranteFaltante").css("color", "green")
            }
        }

        function obtenerVentaSucursal(id_sucursal, fecha_venta){
            
            $.ajax({
                    url: "{{ route('calcular_total_venta_dia') }}",
                    data: {
                        id_sucursal: id_sucursal,
                        fecha : fecha_venta
                    },
                    type: "POST",
                    dataType : "json",
                    async: false,
                    beforeSend:function(){
                        $("#load").attr('hidden',false);
                        
                    }
                }) 

                .done(function( resp ) {
                    setTimeout(() => {
                        $("#load").attr('hidden',true);
                    }, 2000);
                    $("#ventaSistema").text((resp.venta).toFixed(2))
                    $("#venta_sistema").val((resp.venta).toFixed(2));
                })
                // Code to run if the request fails; the raw request and
                // status codes are passed to the function
                .fail(function( xhr, status, errorThrown ) {
                    setTimeout(() => {
                        $("#load").attr('hidden',true);
                    }, 2000);
                    $("#venta_sistema").val("0");
                    alert( "Sorry, there was a problem!" );
                    console.log( "Error: " + errorThrown );
                    console.log( "Status: " + status );
                    console.dir( xhr );
                })
                // Code to run regardless of success or failure;
                .always(function( xhr, status ) {
                    setTimeout(() => {
                        $("#load").attr('hidden',true);
                    }, 2000);
            });
        }

        function borrarDatosInputs(){
            $("#ventaSistema").text("0");
            $("#fecha").val("{{date('Y-m-d')}}");
            $("#sucursal").val(0);
            $("#efectivo").val("");
            $("#tarjeta").val("");
            $("#transferencia").val("");
            $("#qr").val("");
            $("#totalDeclarado").text("0");
            $("#diferencia").text("0");
            $("#sobranteFaltante").text("");
            $("#observacion").val("");
        }

        $(document).on("change", "#sucursal", function(){
            obtenerVentaSucursal($(this).val(), $("#fecha").val())
            sumarValores();
        });
        
        $(document).ready(function(){
            $("#caja").addClass('active');
        });

        $("input").change(function(){
            if ($(this).attr('id') == "fecha") {
                obtenerVentaSucursal($("#sucursal").val(), $(this).val());
                console.log($("#sucursal").val());
            }             
            sumarValores();
        });

        $(document).ready(function(){
            $("button").click(function(){
                if ($(this).attr("data-bs-dismiss") == "modal") {
                    borrarDatosInputs();    
                }

            });
        });

    </script>
@endpush