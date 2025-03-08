@extends('layouts.plantillabase')

@section('title', "Cierre Caja")

@section('mensaje-errores')
  @if (session("error"))
    <x-formulario.mensaje-error-validacion-inputs color="danger">
        <h5>{{ session('error') }}</h5>
    </x-formulario.mensaje-error-validacion-inputs>      
  @endif

  @if (session("exito"))
    <x-formulario.mensaje-error-validacion-inputs color="success">
        <h5>{{ session('exito') }}</h5>
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
<div class="row">
    <div class="col">
        <h4><strong>Cierre de Caja</strong></h4>
    </div>
    <div class="col text-end">
        <button type="button" class="btn btn-success" id="btn-nuevoCierreCaja" data-bs-toggle="modal" data-bs-target="#nuevoCierreCaja">
            <i class="fas fa-plus"></i> Cierre de Caja
        </button>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-3"></div>
    <div class="col-md-6">
        <form action="{{ route('buscar_evento') }}" method="POST" id="buscarformulario">
            @method('POST')
            @csrf
            <div class="input-group flex-nowrap">
                <input type="text" name="buscar" id="buscar" class="form-control" placeholder="Buscar..." aria-label="Username" aria-describedby="addon-wrapping">
                <button class="input-group-text" id="inputBuscar"><i class="fas fa-search"></i></button>
            </div>
        </form>
    </div>
    <div class="col-md-3"></div>
</div>
<br>
<div class="row">
    <table class="table table-striped"> 
        <thead>
            <tr>
              <th scope="col">Opciones</th>
              <th scope="col" style="width: 15%">Sucursal</th>
              <th scope="col">Fecha</th>
              <th scope="col">Efectivo</th>
              <th scope="col">Tarjeta</th>
              <th scope="col">Qr</th>
              <th scope="col">Total Sistema</th>
              <th scope="col">Total Declarado</th>
              <th scope="col" style="width: 15%">Observacion</th>
              <th scope="col">Usuario</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($cierres_caja as $cierre)
              <tr>
                <th scope="row" class="text-center">
                  @if ( $cierre->verificado_caja == 1)
                    <i class="fas fa-check-double fa-xl" style="color: #22ac1d"></i>
                  @else
                    @if (auth()->user()->id != 1)
                        <a href="{{ route("editar_cierre",["id_cierre" => $cierre->id_cierre_caja]) }}" class="btn btn-outline-primary">
                            <i class="fas fa-edit fa-xl" style="color:#6BA9FA"></i>
                        </a>
                    @endif
                    
                    @if (auth()->user()->id == 1)
                        <button type="button" class="btn btn-outline-primary" id="btn-editarCierreCaja" data-bs-toggle="modal" data-bs-target="#editarCierreCaja" >
                            <i class="fas fa-registered fa-xl" style="color:#6BA9FA"></i>
                        </button>
                    @endif
                  @endif  
                </th>
                <td>{{ $cierre->direccion_sucursal }}</td>
                <td>{{ $cierre->fecha_cierre_caja }}</td>
                <td>{{ $cierre->efectivo_caja }}</td>
                <td>{{ $cierre->transferencia_caja }}</td>
                <td>{{ $cierre->qr_caja }}</td>
                <td>{{ number_format($cierre->venta_sistema_caja, 2) }}</td>
                <td>{{ number_format($cierre->total_declarado_caja, 2) }}</td>
                <td>{{ $cierre->observacion_caja }}</td>
                <td> 
                    {{-- @if ( $cierre->estado == 1 )
                        <span class="badge bg-success">Activo</span>    
                    @else
                        <span class="badge bg-warning">Inactivo</span>    
                    @endif --}}
                    {{$cierre->name_usuario}}
                </td>
              </tr>    
            @endforeach
          </tbody>
    </table>
    {{ $cierres_caja->links() }}
    @php
        $fechaActual = date('Y-m-d');    
    @endphp

    <x-modal id="nuevoCierreCaja" title="Cierre de Caja" idformulario="frm-cierre-caja" nombre-btn="Guardar">
        <div class="row" style="font-size: 1rem; font-weight: bold">
            <div class="col">
                <span class="h5">Ventas del Sistema:</span>
            </div>
            <div class="col text-center">
                <span class="h5" id="ventaSistema">0</span>
                <span>Bs</span>
            </div>
            <div class="col-1" id="load" hidden>
                <div class="spinner-grow text-primary spinner-grow-sm" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
            <hr>
        </div>

        <form action="{{route('add_cierre_caja')}}" method="post" class="row" id="frm-cierre-caja">
            @csrf
            @method("post")
            
            <div class="row">
                <div class="col">
                    <x-formulario.label for="fecha">Fecha de Cierre:</x-formulario.label>
                    <x-formulario.input tipo="date" :value="$fechaActual" name="fecha" id="fecha" placeholder="" />
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <x-formulario.label for="fecha">Sucursal:</x-formulario.label>
                    <x-formulario.select id="sucursal" name="sucursal">
                        <option value="0" selected disabled>Seleccione una opcion</option>
                        @foreach ($sucursales as $sucursal)
                            <option value="{{ $sucursal->id }}"> 
                                {{ $sucursal->direccion }} 
                            </option>
                        @endforeach
                    </x-formulario.select>
                </div>
            </div>
            
            <div class="row">
                
                <div class="col">
                    <x-formulario.label for="efectivo">Efectivo Bs.:</x-formulario.label>
                    <x-formulario.input tipo="text" name="efectivo" id="efectivo" placeholder="Introduzca el efectivo"/>
                </div>

                <div class="col">
                    <x-formulario.label for="transferencia">Tarjeta Bs.:</x-formulario.label>
                    <x-formulario.input tipo="text" name="transferencia" id="tarjeta" placeholder="Introduzca el efectivo"/>
                </div>
            </div>
            
            <div class="row">
                <div class="col-6">
                    <x-formulario.label for="qr">QR Bs.:</x-formulario.label>
                    <x-formulario.input tipo="text" name="qr" id="qr" placeholder="Introduzca el efectivo"/>
                </div>
            </div>
            
            <hr style="margin-top: 18px;">
            <div class="row">
                <div class="col">
                    <h5>Total declarado:</h5>
                </div>
                <div class="col text-center">
                    <span class="h5" id="totalDeclarado">0</span>
                    <span> Bs</span>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <h5>Diferencia:</h5>
                </div>
                <div class="col text-center">
                    <span class="h5" id="diferencia">
                        0
                    </span>
                    <span> Bs</span>
                    <span id="sobranteFaltante"></span>
                </div>
            </div>
            <hr>
            
            <input type="text" name="venta_sistema" id="venta_sistema" hidden>
            <input type="text" name="total_declarado" id="total_declarado" hidden>

            <x-formulario.label for="observacion">Observacion: </x-formulario.label>
            <x-formulario.textarea name="observacion" id="observacion" placeholder="Tiene alguna observacion?"></x-formulario.textarea>
        </form>

    </x-modal>

    <x-modal id="editarCierreCaja" title="Edicion de Cierre de Caja" idformulario="revCierreCaja" nombre-btn="Enviar">
        
    </x-modal>
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
            let qr = $("#qr").val() != "" ?  parseFloat($("#qr").val()) : 0.0 ;
            let sum = (efectivo + tarjeta + qr).toFixed(2);
            $("#totalDeclarado").text( sum );

            //Captura de datos para ser recalculados
            let ventaSistema = parseFloat($("#ventaSistema").text());
            let totalDeclarado = parseFloat($("#totalDeclarado").text());
            let diferencia = parseFloat($("#diferencia").text());
            let dif = (ventaSistema - totalDeclarado).toFixed(2);

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
                    async : false,
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
            $("#qr").val("");
            $("#totalDeclarado").text("0");
            $("#diferencia").text("0");
            $("#sobranteFaltante").text("");
            $("#observacion").val("");
        }

        $(document).on("change", "#sucursal", function(){
            obtenerVentaSucursal($(this).val(), $("#fecha").val());
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