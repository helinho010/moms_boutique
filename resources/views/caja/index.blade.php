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
        <form action="{{ route('home_caja') }}" method="GET" id="buscarformulario">
            <div class="input-group flex-nowrap">
                <input type="text" name="buscar" id="buscar" class="form-control" placeholder="Buscar..." aria-label="Username" aria-describedby="addon-wrapping">
                <button type="submit" class="input-group-text" id="input-buscar-cierre-caja">
                    <i class="fas fa-search"></i>
                </button>
                <button type="button" class="input-group-text" 
                        id="id-export-pdf-cierre-caja" style="color:red;"
                        data-bs-toggle="modal" data-bs-target="#modalExportarPdfCierreCaja">
                        <i class="far fa-file-pdf fa-xl"></i>
                </button>
                <button type="button" class="input-group-text" 
                        id="id-export-excel-cierre-caja" style="color:green;"
                        data-bs-toggle="modal" data-bs-target="#modalExportarExcelCierreCaja"
                ><i class="far fa-file-excel fa-xl"></i>
                </button>
            </div>
        </form>
    </div>
    <div class="col-md-3"></div>
</div>
<br>
<div class="row">
    <table class="table table-striped table-bordered"> 
        <thead>
            <tr class="align-middle">
              <th scope="col" style="width: 10%" >Opciones</th>
              <th scope="col" style="width: 15%">Sucursal</th>
              <th scope="col">Fecha</th>
              <th scope="col">Efectivo</th>
              <th scope="col">Tarjeta</th>
              <th scope="col">Transf.</th>
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
                    @if (auth()->user()->usertype_id != 1)
                        <a href="{{ route("editar_cierre",["id_cierre" => $cierre->id_cierre_caja]) }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-edit fa-xl" style="color:#6BA9FA"></i>
                        </a>
                    @endif
                    
                    @if (auth()->user()->usertype_id == 1)
                        
                        @if ( auth()->user()->id == $cierre->id_usuario_caja )
                            <a href="{{ route("editar_cierre",["id_cierre" => $cierre->id_cierre_caja]) }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-edit" style="color:#6BA9FA"></i>
                            </a>
                        @endif

                        <button type="button" class="btn btn-outline-primary btn-sm" id="btn-editarCierreCaja" 
                                data-bs-toggle="modal" data-bs-target="#editarCierreCaja" 
                                onclick="verificarDatos({{ json_encode($cierre) }})"
                        >
                            <i class="fas fa-registered" style="color:#6BA9FA"></i>
                        </button>
                    @endif
                  @endif  
                </th>
                <td>{{ $cierre->direccion_sucursal }}</td>
                <td>{{ $cierre->fecha_cierre_caja }}</td>
                <td>{{ $cierre->efectivo_caja }}</td>
                <td>{{ $cierre->tarjeta_caja }}</td>
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

    {{-- Modal para registrar un nuevo cierre de caja--}}
    <x-modal id="nuevoCierreCaja" title="Cierre de Caja" 
             idformulario="frm-cierre-caja" nombre-btn="Guardar">
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
                    <x-formulario.label for="fecha">Fecha de Cierre:</x-formulario.label>
                    <x-formulario.input tipo="date" :value="$fechaActual" 
                                        name="fecha" id="fecha" placeholder="" 
                    />
                </div>
            </div>
            
            <div class="row">
                
                <div class="col">
                    <x-formulario.label for="efectivo">Efectivo Bs.:</x-formulario.label>
                    <x-formulario.input tipo="text" name="efectivo" id="efectivo" placeholder="Introduzca el efectivo"/>
                </div>

                <div class="col">
                    <x-formulario.label for="tarjeta">Tarjeta Bs.:</x-formulario.label>
                    <x-formulario.input tipo="text" name="tarjeta" id="tarjeta" placeholder="Introduzca el efectivo"/>
                </div>
            </div>
            
            <div class="row">
                <div class="col-6">
                    <x-formulario.label for="qr">QR Bs.:</x-formulario.label>
                    <x-formulario.input tipo="text" name="qr" id="qr" placeholder="Introduzca el efectivo"/>
                </div>
                <div class="col">
                    <x-formulario.label for="transferencia">Transferencia Bs.:</x-formulario.label>
                    <x-formulario.input tipo="text" name="transferencia" id="transferencia" placeholder="Introduzca el efectivo"/>
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

    {{-- Modal para revisar un cierre de caja --}}
    <x-modal id="editarCierreCaja" title="Verificar Cierre de Caja" 
             idformulario="revCierreCaja" nombre-btn="Registrar">
        <div class="row">
            <div class="col-md-12 text-center title h4">
                Datos del Cierre
            </div>
            <hr>
        </div>
        <div class="row">
            <div class="col-md-6">
                <span>Fecha de Cierre: </span> 
            </div>
            <div class="col-md-6">
                <span id="verif_fecha_cierre">Y-m-d</span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <span>Sucursal: </span> 
            </div>
            <div class="col-md-6">
                <span id="verif_sucursal">...</span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <span>Usuario: </span> 
            </div>
            <div class="col-md-6">
                <span id="verif_usuario">...</span>
            </div>
        </div>
        <div class="row" 
             style="border-top: 1px solid black; 
                    border-bottom: 1px solid black; 
                    margin:20px 0px 20px 0px;
                    padding: 10px 0px 10px 0px;
                    background-color: #267026;
                    color: #fff;
        ">
            <div class="col-md-6">
                <span>Vental registrada en el sistema: </span> 
            </div>
            <div class="col-md-6">
                <span id="verif_venta_sistema">0</span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <span>Efectivo Registrado: </span> 
            </div>
            <div class="col-md-6">
                <span id="verif_efectivo">0</span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <span>Tarjeta Registrado: </span> 
            </div>
            <div class="col-md-6">
                <span id="verif_tarjeta">0</span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <span>Transferencia Registrada: </span> 
            </div>
            <div class="col-md-6">
                <span id="verif_transferencia">0</span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <span>QR Registrado: </span> 
            </div>
            <div class="col-md-6">
                <span id="verif_qr">0</span>
            </div>
        </div>
        <div class="row"
            style="border-top: 1px solid black; 
            border-bottom: 1px solid black; 
            margin:20px 0px 20px 0px;
            padding: 10px 0px 10px 0px;
            background-color: #c95622;
            color: #fff;
        ">
            <div class="col-md-6">
                <span>Total Declarado: </span> 
            </div>
            <div class="col-md-6">
                <span id="verif_total_declarado">0</span>
            </div>
            <div class="col-md-6">
                <span>Diferencia Declarada: </span> 
            </div>
            <div class="col-md-6">
                <span id="verif_diferencia_declarada">0</span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <span>Observacion: </span> 
            </div>
            <div class="col-md-6">
                <span id="verif_observacion">...</span>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-12 text-center">
                <form action="{{ route('verificar_cierre') }}" method="post" id="revCierreCaja">
                    @csrf
                    @method("PATCH")
                    <input type="text" name="id_cierre" id="verif_id_cierre" hidden>
                    <input type="checkbox" name="verificado_cierre" id="verificado_cierre"> Esta correcto los datos?
                </form>
            </div>
        </div>
        
    </x-modal>

    {{-- Modal para reporte Pdf --}}
    <x-modal id="modalExportarPdfCierreCaja" title="Exportar Cierre a Pdf" 
             idformulario="formExportCierreierreCaja" nombre-btn="Exportar pdf">
        
        @php $fechahoy = date('Y-m-d')  @endphp

        <form action="{{ route('exportar_cierre_pdf') }}" method="post" id="formExportCierreierreCaja">
            @csrf
            @method('POST')
            <div class="alert alert-warning" role="alert">
                <i class="fa-solid fa-triangle-exclamation"></i> Precaucion, la fecha final debe ser mayor a la fecha inicial.
            </div>
            <div class="row">
                <div class="col-md-6">
                    <x-formulario.label for="fechaInicial">fecha Inicio: </x-formulario.label>
                    <x-formulario.input tipo="date" name="fecha_inicio" 
                                        id="fecha-inicio" placeholder=""
                                        :value="$fechahoy"
                    >
                    </x-formulario.input>
                </div>
                <div class="col-md-6">
                    <x-formulario.label for="fechaFicial">fecha Final: </x-formulario.label>
                    <x-formulario.input tipo="date" name="fecha_final" 
                                        id="fecha-final" placeholder=""
                                        :value="$fechahoy"
                    >
                    </x-formulario.input>
                </div>
            </div>
        </form>
    </x-modal>

    {{-- Modal ExportarExcel --}}
    <x-modal id="modalExportarExcelCierreCaja" title="Exportar Cierre a Excel" 
             idformulario="formExportCierreierreCajaExcel" nombre-btn="Exportar Excel">
        
        @php $fechahoy = date('Y-m-d')  @endphp

        <form action="{{ route('exportar_cierre_excel') }}" method="post" id="formExportCierreierreCajaExcel">
            @csrf
            @method('POST')

            <div class="alert alert-warning" role="alert">
                <i class="fa-solid fa-triangle-exclamation"></i> Precaucion, la fecha final debe ser mayor a la fecha inicial.
            </div>
            <div class="row">
                <div class="col-md-6">
                    <x-formulario.label for="fechaInicial">fecha Inicio: </x-formulario.label>
                    <x-formulario.input tipo="date" name="fecha_inicio" 
                                        id="fecha-inicio" placeholder=""
                                        :value="$fechahoy"
                    >
                    </x-formulario.input>
                </div>
                <div class="col-md-6">
                    <x-formulario.label for="fechaFicial">fecha Final: </x-formulario.label>
                    <x-formulario.input tipo="date" name="fecha_final" 
                                        id="fecha-final" placeholder=""
                                        :value="$fechahoy"
                    >
                    </x-formulario.input>
                </div>
            </div>
        </form>
    </x-modal>

@endsection


@push('scripts')
    <script src="{{ asset('jquery/jquery-3.7.1.min.js') }}"></script>
    
    <script>   
    
        $(document).ready(function(){
            $("#caja").addClass('active');
                $.ajaxSetup({
                    headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
        });

        function sumarValores(){
            // Captura de valores de los inputs
            let efectivo = $("#efectivo").val() != "" ?  parseFloat($("#efectivo").val()) : 0.0 ;
            let tarjeta = $("#tarjeta").val() != "" ?  parseFloat($("#tarjeta").val()) : 0.0 ;
            let transferencia = $("#transferencia").val() != "" ?  parseFloat($("#transferencia").val()) : 0.0 ;
            let qr = $("#qr").val() != "" ?  parseFloat($("#qr").val()) : 0.0 ;
            let sum = (efectivo + tarjeta + transferencia + qr).toFixed(2);
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
            $("#transferencia").val("");
            $("#qr").val("");
            $("#totalDeclarado").text("0");
            $("#diferencia").text("0");
            $("#sobranteFaltante").text("");
            $("#observacion").val("");
        }

        function verificarDatos(data){
            $("#verif_id_cierre").val(data.id_cierre_caja);
            $("#verif_fecha_cierre").text(data.fecha_cierre_caja);
            $("#verif_sucursal").text(data.direccion_sucursal);
            $("#verif_usuario").text(data.name_usuario);
            $("#verif_venta_sistema").text(data.venta_sistema_caja.toFixed(2) + " Bs");
            $("#verif_efectivo").text(data.efectivo_caja.toFixed(2) + " Bs");
            $("#verif_tarjeta").text(data.tarjeta_caja.toFixed(2) + " Bs");
            $("#verif_transferencia").text(data.transferencia_caja.toFixed(2) + " Bs");
            $("#verif_qr").text(data.qr_caja.toFixed(2) + " Bs");
            $("#verif_total_declarado").text(data.total_declarado_caja.toFixed(2) + " Bs");
            $("#verif_diferencia_declarada").text((data.venta_sistema_caja - data.total_declarado_caja).toFixed(2) + " Bs");
            $("#verif_observacion").text(data.observacion_caja);
        }

        $(document).on("change", "#sucursal", function(){
            obtenerVentaSucursal($(this).val(), $("#fecha").val());
            sumarValores();
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