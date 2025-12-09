@extends('layouts.plantillabase')

@section('title','Inventario Interno')

@section('css')
    <style>
        table > tbody > tr > th > i{
            font-size: 20px;
        }

        #btnExportDataInventarioPdf{
            height: 40px;
        }

        .titulo_sucursal{
            margin-top: 50px;
            margin-bottom: 10px;
        }
        .titulo_sucursal > div > h5{
            font-size: 20px;
            color: #0d6efd;
            font-weight: bold;
        }

    </style>
@endsection

@section('mensaje-errores')
    @php
        if (isset($_GET['exito']))
        {
            if ($_GET['exito'] == 1) {
                echo '<div class="alert alert-success" role="alert">El item del Inventario se registro correctamente</div>';
            }else{
                echo '<div class="alert alert-danger" role="alert">Error al registrar el nuevo Item de Inventario</div>';
            }
        }

        if (isset($_GET['actualizado']))
        {
            if ($_GET['actualizado'] == 1) {
                echo '<div class="alert alert-success" role="alert">El Item del Inventario fue actualizado correctamente</div>';
            }else{
                echo '<div class="alert alert-danger" role="alert">Error al actualizar el Item del Inventario</div>';
            }
        }

        if ($errors->first('id_sucursal') != '' ||
            $errors->first('id_producto') != '' ||
            $errors->first('id_tipo_ingreso_salida') != '' ||
            $errors->first('cantidad_ingreso') != '')
            {
                echo '<div class="alert alert-danger" role="alert">'.
                $errors->first('id_sucursal')."<br>".
                $errors->first('id_producto')."<br>".
                $errors->first('id_tipo_ingreso_salida')."<br>".
                $errors->first('cantidad_ingreso')."<br>"
                .'</div>';
        }
    @endphp
    
    @if (session("errorItemCreado"))
        <x-formulario.mensaje-error-validacion-inputs color="danger">
            <h5>{{ session('errorItemCreado') }}</h5>
        </x-formulario.mensaje-error-validacion-inputs>      
    @endif

    @if (session("itemCreado"))
        <x-formulario.mensaje-error-validacion-inputs color="success">
            <h5>{{ session('itemCreado') }}</h5>
        </x-formulario.mensaje-error-validacion-inputs>      
    @endif
    
    @if (session('errorItemDatosProporcionados'))
        <x-formulario.mensaje-error-validacion-inputs color="danger">
            <h5>{{ session('errorItemDatosProporcionados') }}</h5>
        </x-formulario.mensaje-error-validacion-inputs>
    @endif
    
    @if (session("errorItemActualizado"))
        <x-formulario.mensaje-error-validacion-inputs color="danger">
            <h5>{{ session('errorItemActualizado') }}</h5>
        </x-formulario.mensaje-error-validacion-inputs>      
    @endif
    
    @if (session("itemActualizado"))
        <x-formulario.mensaje-error-validacion-inputs color="success">
            <h5>{{ session('itemActualizado') }}</h5>
        </x-formulario.mensaje-error-validacion-inputs>      
    @endif

    @if (session("correcto"))
        <x-formulario.mensaje-error-validacion-inputs color="success">
            <h5>{{ session('correcto') }}</h5>
        </x-formulario.mensaje-error-validacion-inputs>      
    @endif

    @if (session("error"))
        <x-formulario.mensaje-error-validacion-inputs color="danger">
            <h5>{{ session('error') }}</h5>
        </x-formulario.mensaje-error-validacion-inputs>      
    @endif
@endsection

@section('card-title')
    <div class="row">
        <div class="col">
            <h4>Inventario Interno</h4>
        </div>
        <div class="col text-end">
            <button type="button" class="btn btn-success" id="btnModalRegistroActualizacion" data-bs-toggle="modal" data-bs-target="#exampleModal" disabled>
                <i class="fas fa-plus"></i> Agregar Iten al Inventario
            </button><br>
            <span style="font-size: 10px; color:red" id="reqBtnAgregarItem">(*) Seleccionar una Sucursal</span>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-2">
                    <label for="inputPassword6" class="col-form-label">Sucursal:</label>
                </div>
                <div class="col-md-10">
                    <form action="{{ route('home_inventario_interno') }}" id="dataformInventario">
                        <div class="input-group">
                             <select class="form-select" aria-describedby="" name="id_sucursal" id="select_sucursal">
                                <option value="seleccionado" @if ( !isset($id_sucursal) ) selected  @endif disabled>Seleccione una opcion...</option>
                                @can('todas sucursales')
                                  <option value="999" @if (isset($id_sucursal) && $id_sucursal == 999 ) selected  @endif>
                                    Todas las Sucursales
                                  </option>
                                @endcan
                                    @foreach ($sucursales as $sucursal)
                                        <option value="{{ $sucursal->id }}" 
                                            @if (isset($id_sucursal) && $sucursal->id == $id_sucursal ) 
                                                selected  
                                            @endif>
                                            {{ "$sucursal->ciudad - ".substr($sucursal->direccion,0,40)."..." }}
                                        </option>
                                    @endforeach
                             </select>
                             <button type="submit" class="input-group-text" id="btnFormDataInventario"><i class="fas fa-search"></i></button>
                    </form>

                             @can('exportar pdf')
                                {{-- <button type="button" class="input-group-text" id="btnExportDataInventarioPdf"><i class="far fa-file-pdf" style="color: red;font-size: 20px;"></i></button> --}}
                                
                                <form action="{{route('inventario_interno_pdf')}}" method="get">
                                    <input type="text" name="id_sucursal" id="id_sucursal_pdf" value="{{ $id_sucursal }}" hidden>
                                    <button type="submit" class="btn btn-danger" id="btnExportDataInventarioPdf">
                                        <i class="far fa-file-pdf" style="font-size: 20px;"></i>
                                    </button>
                                </form>
                             @endcan
                             
                             @can('exportar excel')
                                {{-- <button type="button" class="input-group-text" id="btnExportDataInventarioExcel"><i class="far fa-file-excel" style="color: green;font-size: 20px;"></i></button>  --}}
                                
                                <form action="{{route('inventario_interno_excel')}}" method="get">
                                    <input type="text" name="id_sucursal" id="id_sucursal_excel" value="{{ $id_sucursal }}" hidden>
                                    <button type="submit" class="btn btn-success" id="btnExportDataInventarioExcel">
                                        <i class="far fa-file-excel" style="font-size: 22px;"></i>
                                    </button>
                                </form>
                             @endif
                             {{-- @livewire('boton-invint-pdf') --}}
                        </div>
                    
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="row">
                <form action="{{ route('home_inventario_interno') }}" method="GET" id="buscarformulario">
                    <div class="input-group flex-nowrap">
                        <input type="text" name="buscar" id="buscar" class="form-control" placeholder="Buscar..." aria-label="" aria-describedby="addon-wrapping">
                        <input type="text" name="id_sucursal" id="id_sucursal" hidden>
                        <button type="submit" class="input-group-text" id="inputBuscar" disabled><i class="fas fa-search"></i></button><br>
                    </div>
                </form>
            </div>
            <div class="row">
                <span style="font-size: 10px; color:red" id="btnBuscarItem">(*) Seleccionar una Sucursal</span>
            </div>
        </div>
    </div>

    <div class="row titulo_sucursal">
        <div class="col-md-12">
            <h5 style="text-align: center;">
                @if (isset($id_sucursal))
                    @if ($id_sucursal != 999)
                       @foreach ($sucursales as $sucursal)
                           @if ($sucursal->id == $id_sucursal)
                               {{  $sucursal->ciudad ." - ".substr($sucursal->direccion,0,40)."..." }}
                           @endif
                       @endforeach
                    @else
                        Todas las Sucursales
                    @endif
                @endif
            </h5>
        </div>
    </div>

    <div class="row">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                  <th scope="col">Opciones</th>
                  {{-- <th scope="col">Sucursal</th> --}}
                  <th scope="col">Producto</th>
                  <th scope="col">Tipo Ingreso Salida</th>
                  <th scope="col">Ult. Cant. Ing.</th>
                  <th scope="col">Stock</th>
                  <th scope="col">Usuario</th>
                  <th scope="col">Estado</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($inventario as $item)
                  <tr>
                    <th scope="row">
                        <div class="row">
                            <div class="col-md-4">
                                @can('editar inventario interno')
                                    <a href="{{ route('editar_inventario_interno', ['id_sucursal'=>$item->id_sucursals, 'id_producto'=>$item->id_productos]) }}" 
                                    class="btn btn-outline-primary" title="Editar"
                                    >
                                        <i class="fas fa-edit"></i>
                                    </a>                            
                                @endcan
                            </div>
                            <div class="col-md-4">
                                @can('eliminar inventario interno')
                                    @if ($item->estado_inventario_internos == 1)
                                        <form action="{{ route('actualizar_estado_inventario_interno') }}" method="post" id="formularioEstadoActivo{{ $item->id_inventario_internos }}">
                                            @csrf
                                            @method('PATCH')
                                            <input type="text" name="id_inventario_interno" value="{{ $item->id_inventario_internos }}" hidden>
                                            <input type="text" name="estado_inventario_interno" value="{{ $item->estado_inventario_internos }}" hidden>
                                            <input type="text" name="id_sucursal" value="{{ $item->id_sucursals }}" hidden>
                                            <button type="button" class="btn btn-outline-danger" onclick='habilitarDesabilitar("formularioEstadoActivo{{ $item->id_inventario_internos }}")'>
                                                <i class="fas fa-trash-alt fa-xl" style="color:#FA746B"></i>
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('actualizar_estado_inventario_interno') }}" method="post" id="formularioEstadoNoActivo{{ $item->id_inventario_internos }}">
                                            @csrf
                                            @method('PATCH')
                                            <input type="text" name="id_inventario_interno" value="{{ $item->id_inventario_internos }}" hidden>
                                            <input type="text" name="estado_inventario_interno" value="{{ $item->estado_inventario_internos }}" hidden>
                                            <input type="text" name="id_sucursal" value="{{ $item->id_sucursals }}" hidden>
                                            <button type="button" class="btn btn-outline-warning" onclick='habilitarDesabilitar("formularioEstadoNoActivo{{ $item->id_inventario_internos }}")'>
                                                <i class="fas fa-check-circle fa-xl" style="color:#FAAE43"></i>
                                            </button>
                                        </form>
                                    @endif
                                @endcan
                            </div>
                        </div>
                    </th>
                    {{-- <th>{{"$item->direccion_sucursals"}}</th> --}}
                    <th>
                        {{$item->nombre_productos}} <br>
                        Talla: <span class="badge bg-primary">{{ $item->talla_productos!=""?$item->talla_productos:"ST(Sin Talla)" }}</span><br>
                        Precio Venta: <span class="badge bg-info text-dark">{{ $item->precio_productos}} Bs.</span> <br>
                        @can('costo producto')
                          Costo:  <span class="badge bg-secondary">{{ $item->costo_productos }} Bs.</span>
                        @endcan
                    </th>
                    <th>{{ $item->tipo_tipo_ingreso_salidas }}</th>
                    <th>{{ $item->cantidad_ingreso_inventario_internos }}</th>
                    <th>{{ $item->stock }}</th>
                    <th>{{ $item->name_users }}</th>
                    <td>
                        @if ( $item->estado_inventario_internos == 1 )
                            <span class="badge bg-success">Activo</span>
                        @else
                            <span class="badge bg-warning">Inactivo</span>
                        @endif
                    </td>
                  </tr>
                @endforeach
              </tbody>
        </table>
        {{ $inventario->links() }}
    </div>

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Nuevo Item para el Inventario: </h5>
                <button type="button" class="btn-close cerrarModal" data-bs-dismiss="modal" aria-label="Close" onclick="resestablecerValoresModal()"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('nuevo_inventario_interno') }}" id="modalFormularioRegistroActualizacion">
                      @csrf
                      @method('POST')
                      <div class="row">
                        <div class="col-md-2">
                            <label for="inputPassword6" class="col-form-label">Sucursal: </label>
                        </div>
                        <div class="col-md-8">
                                <div class="input-group">
                                    <select class="form-select" aria-describedby="" name="id_sucursal" id="modalSelectSucursal">
                                        <option value="seleccionado" @if (!isset($id_sucursal)) selected  @endif disabled>Seleccione una opcion...</option>
                                            @foreach ($sucursales as $sucursal)
                                               @if ($sucursal->activo == 1)
                                                  <option value="{{ $sucursal->id }}" 
                                                    @if (isset($id_sucursal) && $sucursal->id == $id_sucursal ) 
                                                        selected  
                                                    @endif>
                                                    {{ "$sucursal->ciudad - ".substr($sucursal->direccion,0,40)."..." }}
                                                  </option>
                                               @endif
                                            @endforeach
                                     </select>
                                </div>
                        </div>
                      </div>
                      <br>
                      @isset($productos)
                        <div class="row">
                            <div class="col-md-2">
                                <label for="inputPassword6" class="col-form-label">Producto: </label>
                            </div>
                            <div class="col-md-8">
                                    <div class="input-group">
                                        <select class="form-select" aria-describedby="" name="id_producto" id="modalSelectProducto">
                                            <option value="seleccionado" selected disabled>Seleccione una opcion...</option>
                                                @foreach ($productos as $producto)
                                                    @if ($producto->estado == 1)
                                                        <option value="{{ $producto->id }}">
                                                            {{ $producto->nombre }} -
                                                            {{ $producto->talla != "" ? "Talla: ".$producto->talla : "ST(Sin Talla)" }} -
                                                            {{ $producto->precio != "" ? "Precio: ".$producto->precio : 0 }} Bs.
                                                        </option>
                                                    @endif
                                                @endforeach
                                        </select>
                                    </div>
                            </div>
                        </div>
                      @endisset
                      <br>
                      @isset($tipoIngresoSalidas)
                        <div class="row">
                            <div class="col-md-2">
                                <label for="inputPassword6" class="col-form-label">Tipo de Ingreo: </label>
                            </div>
                            <div class="col-md-8">
                                    <div class="input-group">
                                        <select class="form-select" aria-describedby="" name="id_tipo_ingreso_salida" id="modalSelectTipoEntrada">
                                            <option value="seleccionado" selected disabled>Seleccione una opcion...</option>
                                                @foreach ($tipoIngresoSalidas as $tipo)
                                                    @if ($tipo->estado == 1)
                                                        <option value="{{ $tipo->id }}">{{ "$tipo->tipo"}}</option>
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
                            <label for="modalInputCantidadIngreso" class="form-label">Cantidad Ingreso: </label>
                        </div>
                        <div class="col-md-8">
                            <input type="number" name="cantidad_ingreso" placeholder="0" class="form-control" id="modalInputCantidadIngreso">
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
@endsection

@push('scripts')
<script src="{{ asset('jquery/jquery-3.7.1.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).ready(function(){
        // alert($("#select_sucursal option:selected").attr('value'));
        if ($("#select_sucursal option:selected").attr('value') > 0)
        {
            $('#btnModalRegistroActualizacion').prop( "disabled", false );
            $('#reqBtnAgregarItem').remove();
            $('#inputBuscar').prop( "disabled", false );
            $('#btnBuscarItem').remove();
            $('#id_sucursal').val($("#select_sucursal option:selected").attr('value'));
            $('#title_nombre_sucursal').text($("#select_sucursal option:selected").text());
            // formularioRegistroActualizacion-selectSucursalRegAct
            $('#selectSucursalRegAct').val($("#select_sucursal option:selected").attr('value'));
            
        }

    });

    $("#select_sucursal").on('change',function(){
        let idSucursal = $("#select_sucursal option:selected").attr('value');
        
        $('#btnModalRegistroActualizacion').prop( "disabled", false );
        $('#reqBtnAgregarItem').remove();
        $('#inputBuscar').prop( "disabled", false );
        $('#btnBuscarItem').remove();
        $('#id_sucursal').val(idSucursal);
        $('#modalSelectSucursal').val(idSucursal);
        $('#title_nombre_sucursal').text(idSucursal);
        $('#id_sucursal_pdf').val(idSucursal);
        $('#id_sucursal_excel').val(idSucursal);
    });

    $('button').on('click',function()
    {
        // event.preventDefault();
        if ($(this).attr('id') == 'inputBuscar'){
            $("#buscarformulario").submit();

        } else if ($(this).attr('id') == 'modalBtnGuardarActualizar'){
            $('#modalSelectSucursal').removeAttr('disabled');
            $("#modalFormularioRegistroActualizacion").submit();

        }else if ($(this).attr('id') == 'btnExportDataInventarioPdf') {
            $('#dataformInventario').attr('action', "{{ route('inventario_interno_pdf') }}" );
            $('#dataformInventario').submit();
        }

        else if ($(this).attr('id') == 'btnExportDataInventarioExcel') {
            $('#dataformInventario').attr('action', "{{ route('inventario_interno_excel') }}" );
            $('#dataformInventario').submit();
        }

    });

    function resestablecerValoresModal()
    {
        $("#exampleModalLabel").html("<h3>Nuevo Item para el Inventario Interno</h3>");
        $("#modalFormularioRegistroActualizacion").attr("action","{{ route('nuevo_inventario_interno') }}");
        $('#modalSelectSucursal').removeAttr('disabled');
        $("#modalSelectProducto").val('seleccionado');
        $("#modalSelectTipoEntrada").val('seleccionado');
        $("#modalInputCantidadIngreso").val('');
        $("#modalBtnGuardarActualizar").text("Guardar");
        // $("#id_categoria_producto").val('seleccionado');
    }

    function editar(item)
    {
        console.log(item);
        $("#exampleModal").modal("show");
        $("#exampleModalLabel").html("<h3>Editar Item del Inventario Interno</h3>");
        $("#modalFormularioRegistroActualizacion").attr("action","{{ route('actualizar_inventario_interno') }}");
        $("#modalFormularioRegistroActualizacion").append('<input type="text" name="id" '+ 'value="'+ item.id +'"' +'hidden>');
        $("#modalSelectSucursal").attr("disabled", "disabled");
        $("#modalSelectSucursal").val(item.id_sucursal);
        $("#modalSelectProducto").val(item.id_producto);
        $("#modalSelectTipoEntrada").val(item.id_tipo_ingreso_salida);
        $("#modalInputCantidadIngreso").val(item.cantidad_ingreso);
        $("#modalBtnGuardarActualizar").text("Actualizar");
        $("#modalBtnGuardarActualizar").on('click',function(){
            $("#modalFormularioRegistroActualizacion").submit();
            resestablecerValoresModal();
        });
    }

    function habilitarDesabilitar(idformulario)
    {        
        Swal.fire({
                title: "Esta seguro de deshabilitar el item?",
                showDenyButton: true,
                confirmButtonText: "Si",
                denyButtonText: `No`
                }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed)
                {
                    // Swal.fire("Saved!", "", "success");
                    $("#" + idformulario).submit();
                } else if (result.isDenied) {
                    // Swal.fire("Changes are not saved", "", "info");
                }
            });

    }

    function getParameterByName(name) {
        name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
        var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
        return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
    }

    $(document).ready(function(){
        // $('.select2').select2();
        var parametroGetExito = getParameterByName('exito');
        var parametroGetActualizado = getParameterByName('actualizado');
        var pathname = window.location.pathname;

        if (parametroGetExito == 1 || parametroGetActualizado == 1)
        {
            setTimeout(() => {
                $(location).attr('href',pathname);
            }, 10000);
        }

        $("#home").removeClass('active');
        $("#inventario\\ interno").addClass('active');

        $('.select2').select2({
            theme: "bootstrap-5",
        });

        $('.select2Modal').select2({
            theme: "bootstrap-5",
            dropdownParent: $('#exampleModal'),
        });
    });

</script>
@endpush
