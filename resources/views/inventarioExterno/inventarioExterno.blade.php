@extends('layouts.plantillabase')

@section('title','Inventario Externo')

@section('css')
    <style>
        table > tbody > tr > th > i{
            font-size: 20px;
        }
    </style>
@endsection

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

    @if (session("itemEliminadoInventarioExternoError"))
        <x-formulario.mensaje-error-validacion-inputs color="danger">
            <h5>{{ session('itemEliminadoInventarioExternoError') }}</h5>
        </x-formulario.mensaje-error-validacion-inputs>      
    @endif

    @if (session("itemEliminadoInventarioExternoCorrectamente"))
        <x-formulario.mensaje-error-validacion-inputs color="success">
            <h5>{{ session('itemEliminadoInventarioExternoCorrectamente') }}</h5>
        </x-formulario.mensaje-error-validacion-inputs>      
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

@endsection

@section('card-title')
    <div class="row">
        <div class="col">
            <h4>Inventario Externo</h4>
        </div>
        @can('crear inventario externo')
            <div class="col text-end">
                <button type="button" class="btn btn-success" id="btnModalRegistroActualizacion" data-bs-toggle="modal" data-bs-target="#exampleModal" disabled>
                    <i class="fas fa-plus"></i> Agregar Item al Inventario
                </button><br>
                <span style="font-size: 10px; color:red" id="reqBtnAgregarItem">(*) Seleccionar una Evento</span>
            </div>
        @endcan
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-2">
                    <label for="select_evento" class="col-form-label">Evento: </label>
                </div>
                <div class="col-md-10">
                    <form action="{{ route('home_inventario_externo') }}" method="GET" id="dataformInventario">
                        <div class="input-group">
                            <select class="form-select" aria-describedby="" name="id_evento" id="select_evento">
                                <option value="seleccionado" @if (!isset($id_evento)) selected  @endif disabled>Seleccione una opcion...</option>
                                    @foreach ($eventos as $evento)
                                        @if ($evento->estado_evento == 1)
                                            <option value="{{ $evento->id_evento }}" 
                                                @if ( isset($id_evento) && $evento->id_evento == $id_evento ) 
                                                    selected  
                                                @endif
                                                >
                                                {{ "$evento->nombre_evento - $evento->fecha_evento" }}
                                            </option>
                                        @endif
                                    @endforeach
                             </select>
                             <button class="input-group-text" type="submit"><i class="fas fa-search"></i></button>
                    </form>
                             @can('exportar pdf')                               
                                <form action="{{route('inventario_externo_pdf')}}" method="get" id="formExportInventarioExternoPdf">
                                    <input type="text" name="id_evento" id="id_evento" value="{{ $id_evento }}" hidden>
                                    <button type="button" class="btn btn-danger" id="btnExportInventarioExternoPdf" title="Exportar a PDF">
                                        <i class="far fa-file-pdf" style="font-size: 20px;"></i>
                                    </button>
                                </form>
                             @endcan
                             
                             @can('devolver productos inventario externo')                                
                                <form action="{{route('inventario_externo_retornar_productos')}}" method="get" id="formInventarioExternoRetornarProductos">
                                    <input type="text" name="id_evento" id="id_evento" value="{{ $id_evento }}" hidden>
                                    <button type="button" class="btn btn-success" id="btnDevolverProductosInventarioExterno" title="Devolver los productos">
                                        <i class="fas fa-exchange-alt"></i>
                                    </button>
                                </form>
                             @endif
                        </div>
                    
                </div>
            </div>  
        </div>
        
        <div class="col-md-4">
            <div class="row">
                <form action="{{ route('home_inventario_externo') }}" method="GET" id="buscarformulario">
                    <div class="input-group flex-nowrap">
                        <input type="text" name="buscar" id="buscar" class="form-control" placeholder="Buscar..." aria-label="" aria-describedby="addon-wrapping">
                        <input type="text" name="id_evento" id="id_evento" hidden>
                        <button type="submit" class="input-group-text">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
            <div class="row">
                <span style="font-size: 10px; color:red" id="btnBuscarItem">(*) Seleccionar una Sucursal</span>
            </div>
        </div>
    </div>
    <br>
    <div class="row">
        <table class="table table-striped align-middle table-bordered"> 
            <thead>
                <tr class="align-middle">
                  <th style="width: 8%">Opciones</th>
                  <th style="width: 20%">Sucursal</th>
                  <th style="width: 30%">Producto</th>
                  <th style="width: 10%">Cantidad</th>
                  <th style="width: 15%">Tipo Ingreso Salida</th>
                  <th style="width: 13%">Usuario</th>
                  <th style="width: 13%">Estado</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($inventario as $item)
                  <tr>
                    <th scope="row">
                        @if ($item->estado_inventario_externo != 3 )
                            @if ($item->estado_inventario_externo == 1)
                                @can('eliminar inventario externo')
                                    <form action="{{ route('eliminar_item_inventario_externo') }}" method="post" id="formEliminarInventarioExterno{{ $item->id_inventario_externo }}">
                                        @csrf
                                        @method('DELETE')
                                        <input type="text" name="id_inventario_externo" value="{{ $item->id_inventario_externo }}" hidden>
                                        @if (isset($id_evento))
                                            <input type="text" name="id_evento" value="{{ $id_evento }}" hidden>
                                        @endif
                                        <button type="button" onclick="eliminarItemInventarioExterno({{ $item->id_inventario_externo }})" class="btn btn-danger" title="Eliminar item del inventario"
                                        style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;"
                                        >
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>    
                                @endcan
                            @endif
                        @else
                        @endif
                    </th>
                    <th>{{"$item->ciudad_sucursal -".substr($item->direccion_sucursal,0,30)."..." }}</th>
                    <th>
                        {{ $item->nombre_producto }} <br>
                        Talla: <span class="badge bg-primary">{{ $item->talla_productos!=""?$item->talla_productos:"ST(Sin Talla)"}}</span>
                        Precio: <span class="badge bg-info text-dark">{{ $item->precio_producto != "" ? $item->precio_producto : 0 }} Bs</span> 
                        @can('costo producto')
                            costo: <span class="badge bg-info text-dark">{{ $item->costo_producto != "" ? $item->costo_producto : 0 }} Bs</span> 
                        @endcan  
                    </th>
                    <th>{{$item->cantidad_inventario_externo}}</th>
                    <th>{{"$item->tipo_ingreso_salida"}}</th>
                    <th>{{"$item->nombre_del_usuario"}}</th>
                    <td> 
                        @if ( $item->estado_inventario_externo == 1 )
                            <span class="badge bg-success">Activo</span>    
                        @else
                            @if ($item->estado_inventario_externos == 2)
                                <span class="badge bg-warning">Inactivo</span>        
                            @else
                                <span class="badge bg-secondary">Devuelto</span>    
                            @endif
                        @endif
                    </td>
                  </tr>    
                @endforeach
              </tbody>
        </table>
        {{ $inventario->links() }}
    </div>

        <!-- Modal -->
        @can('crear inventario externo')
            <div class="modal fade" id="exampleModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Nuevo Item para el Inventario Externo: </h5>
                    <button type="button" class="btn-close cerrarModal" data-bs-dismiss="modal" aria-label="Close" onclick="resestablecerValoresModal()"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="{{ route('nuevo_inventario_externo') }}" id="modalFormularioRegistroActualizacion"> 
                        @csrf
                        @method('POST')
                        <div class="row">
                            <div class="col-md-2">
                                <label for="modalSelectEvento" class="col-form-label">Evento: </label>
                            </div>
                            <div class="col-md-8">
                                    <div class="input-group">
                                        <select class="form-select" aria-describedby="" name="id_evento" id="modalSelectEvento">
                                            <option value="seleccionado" @if (!isset($id_evento)) selected  @endif disabled>Seleccione una opcion...</option>
                                                @foreach ($eventos as $evento)
                                                @if ($evento->estado_evento == 1)
                                                    <option value="{{ $evento->id_evento }}" 
                                                        @if (isset($id_evento) && $evento->id_evento == $id_evento ) 
                                                            selected  
                                                        @endif
                                                        >{{ "$evento->nombre_evento - $evento->fecha_evento" }}</option>
                                                @endif
                                                @endforeach
                                        </select>
                                    </div>
                            </div>
                        </div>
                        <br>
                        {{-- Prueba de coponente livewire --}}
                            <livewire:selecto-filter-productos />
                        {{-- Fin componenete --}}
                        <br>
                        @isset($tiposIngresosSalidas)
                            <div class="row">
                                <div class="col-md-2">
                                    <label for="modalSelectTipoEntrada" class="col-form-label">Tipo de Salida: </label>
                                </div>
                                <div class="col-md-8">
                                        <div class="input-group">
                                            <select class="form-select" aria-describedby="" name="id_tipo_ingreso_salida" id="modalSelectTipoEntrada">
                                                <option value="seleccionado" selected disabled>Seleccione una opcion...</option>
                                                    @foreach ($tiposIngresosSalidas as $item)
                                                        @if ($item->estado == 1)
                                                            <option value="{{ $item->id }}" 
                                                                @if ($item->id == 6)
                                                                    selected
                                                                @endif
                                                                >{{ "$item->tipo"}}</option>
                                                            @else
                                                            <option value="{{ $item->id }}" disabled>{{ "$item->tipo (deshabilitado)"}}</option>
                                                        @endif
                                                    @endforeach
                                            </select>
                                        </div>
                                </div>
                            </div> 
                        @endisset
                        <br>     
                        <div class="row">
                            <div class="col-md-2">
                                <label for="modalInputCantidadIngreso" class="form-label">Cantidad Salida: </label>
                            </div>
                            <div class="col-md-8">
                                <input type="number" name="cantidad_salida" placeholder="0" class="form-control" id="modalInputCantidadSalida">
                            </div>
                        </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger cerrarModal" data-bs-dismiss="modal" onclick="resestablecerValoresModal()">Cerrar</button>
                        <button type="button" class="btn btn-success" id="modalBtnGuardarActualizar">Guardar</button>
                    </div>
                </div>
                </div>
            </div> 
        @endcan
@endsection


@push('scripts')
<script src="{{ asset('jquery/jquery-3.7.1.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    //btnFormDataRetornarProductos ibtnFormDataRetornarProductos font-size: 19px;
    $("#select_evento").on('change',function(){
        console.log("Se esta ejecuntando el css");
        $("#btnFormDataExportPdf").prop('disabled', false);
        $('#ibtnFormDataExportPdf').attr('style','');
        $('#ibtnFormDataExportPdf').attr('style','font-size:19px; color:#F52220');
        $("#btnFormDataRetornarProductos").prop('disabled', false);
        $('#ibtnFormDataRetornarProductos').attr('style','');
        $('#ibtnFormDataRetornarProductos').attr('style','font-size:19px; color:#F52220');
    });

    $(document).ready(function(){
        // alert($("#select_sucursal option:selected").attr('value'));
        if ($("#select_evento option:selected").attr('value') > 0) 
        {
            $('#btnModalRegistroActualizacion').prop( "disabled", false );
            $('#reqBtnAgregarItem').remove();     
            $('#inputBuscar').prop( "disabled", false );
            $('#btnBuscarItem').remove();
            $('#id_evento').val($("#select_evento option:selected").attr('value'));
            $('#title_nombre_sucursal').text($("#select_sucursal option:selected").text());
            // formularioRegistroActualizacion-selectSucursalRegAct
            $('#selectSucursalRegAct').val($("#select_sucursal option:selected").attr('value'))
            
            // Estilos para los botones de exportar a PDF y Devolver los productos
            $("#btnFormDataExportPdf").prop('disabled', false);
            $('#ibtnFormDataExportPdf').attr('style','');
            $('#ibtnFormDataExportPdf').attr('style','font-size:19px; color:#F52220');
            $("#btnFormDataRetornarProductos").prop('disabled', false);
            $('#ibtnFormDataRetornarProductos').attr('style','');
            $('#ibtnFormDataRetornarProductos').attr('style','font-size:19px; color:#F52220');
        }
        
    });

    $("#select_evento").on('change',function(){  
        $('#btnModalRegistroActualizacion').prop( "disabled", false );
        $('#reqBtnAgregarItem').remove();
        $('#inputBuscar').prop( "disabled", false );
        $('#btnBuscarItem').remove();
        $('#id_evento').val($("#select_evento option:selected").attr('value'));
        $('#modalSelectEvento').val($("#select_evento option:selected").attr('value'));
        $('#title_nombre_sucursal').text($("#select_sucursal option:selected").text());
    });

    function devolverProductos(valorSelectEvento)
    {
        Swal.fire({
                title: "Esta seguro de retornar los productos?",
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: "Si",
                denyButtonText: `No`
                }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) 
                {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('inventario_externo_retornar_productos') }}",
                        data: {"id_evento":valorSelectEvento},
                        success: function (response) {
                            if (response.respuesta) {
                                Swal.fire(response.mensaje, "", "success");        
                                // location.reload();
                            } else {
                                Swal.fire(response.mensaje, "", "error");        
                                // location.reload();
                            }
                        }
                    });
                } else if (result.isDenied) {
                    // Swal.fire("Changes are not saved", "", "info");
                }
            });
    }
    
    $('button').on('click',function(){
        if ($(this).attr('id') == 'inputBuscar'){
            $("#buscarformulario").submit();

        } else if ($(this).attr('id') == 'modalBtnGuardarActualizar'){
            $("#modalFormularioRegistroActualizacion").submit();

        } else if ($(this).attr('id') == 'btnFormDataInventario'){
            $("#dataformInventario").attr('action',"{{ route('data_inventario_externo') }}");
            $("#dataformInventario").submit();

        }else if($(this).attr('id') == 'btnExportInventarioExternoPdf'){
            Swal.fire({
                title: "Obtener el pdf del inventario?",
                showDenyButton: true,
                confirmButtonText: "Si",
                denyButtonText: `No`
                }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) 
                {
                    $("#formExportInventarioExternoPdf").submit();
                } else if (result.isDenied) {
                    // Swal.fire("Changes are not saved", "", "info");
                }
            });
            
        }else if ( $(this).attr('id') == 'btnDevolverProductosInventarioExterno') {
            Swal.fire({
                title: "Esta seguro de retornar los productos?",
                showDenyButton: true,
                confirmButtonText: "Si",
                denyButtonText: `No`
                }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) 
                {
                    $("#formInventarioExternoRetornarProductos").submit();
                } else if (result.isDenied) {
                    // Swal.fire("Changes are not saved", "", "info");
                }
            });
            // devolverProductos($('#select_evento').val());
        }
    });

    function resestablecerValoresModal()
    {
        $("#exampleModalLabel").html("<h3>Nuevo Item para el Inventario Externo</h3>");
        $("#formularioTipoIngresoSalida").attr("action","{{ route('nuevo_inventario_externo') }}");
        $("#modalSelectProducto").val('seleccionado');
        $("#modalSelectSucursal").val('seleccionado');
        $("#modalSelectTipoEntrada").val(6);
        $("#modalInputCantidadSalida").val('');
        $("#btnGuardarActualizar").val("Guardar");     
        // $("#id_categoria_producto").val('seleccionado');
    }

    function eliminarItemInventarioExterno(id_inventario_externo)
    {
        Swal.fire({
            title: "Esta seguro de eliminar el item del inventario externo?",
            showDenyButton: true,
            confirmButtonText: "Si",
            denyButtonText: `No`
            }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) 
            {
                $("#formEliminarInventarioExterno"+id_inventario_externo).submit();
            } else if (result.isDenied) {
                // Swal.fire("Changes are not saved", "", "info");
            }
        });
    }

    $(document).ready(function(){

        $("#home").removeClass('active');
        $("#inventario\\ externo").addClass('active');
    });
    
</script>
@endpush
