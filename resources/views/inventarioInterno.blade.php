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

    </style>
@endsection

@section('h-title')
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
                <div class="col-md-8">
                    <form action="{{ route('data_inventario_interno_page') }}" method="POST" id="dataformInventario">
                        @method('POST')
                        @csrf
                        <div class="input-group">
                            <select class="form-select" aria-describedby="" name="id_sucursal" id="select_sucursal">
                                <option value="seleccionado" @if ( !isset($id_sucursal) ) selected  @endif disabled>Seleccione una opcion...</option>
                                @if (auth()->user()->usertype_id == 1)
                                  <option value="999" @if (isset($id_sucursal) && $id_sucursal == 999 ) selected  @endif>
                                    Todas las Sucursales
                                  </option>
                                @endif
                                    @foreach ($sucursales as $item)
                                       @if ($item->estado_sucursal == 1)
                                          <option value="{{ $item->id_sucursal_user_sucursal }}" @if (isset($id_sucursal) && $item->id_sucursal_user_sucursal == $id_sucursal ) selected  @endif>{{ "$item->razon_social_sucursal - $item->ciudad_sucursal - ".substr($item->direccion_sucursal,0,40)."..." }}</option>
                                        @else
                                          <option value="{{ $item->id_sucursal_user_sucursal }}" disabled>{{ "$item->razon_social_sucursal - $item->ciudad_sucursal - ".substr($item->direccion_sucursal,0,30)."... (deshabilitado)" }}</option>
                                       @endif
                                    @endforeach
                             </select>
                             <button class="input-group-text" id="btnFormDataInventario"><i class="fas fa-search"></i></button>
                             <button class="input-group-text" id="btnExportDataInventarioPdf"><i class="far fa-file-pdf" style="color: red;font-size: 20px;"></i></button>
                             <button class="input-group-text" id="btnExportDataInventarioExcel"><i class="far fa-file-excel" style="color: green;font-size: 20px;"></i></button>
                             {{-- @livewire('boton-invint-pdf') --}}
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="row">
                <form action="{{ route('buscar_inventario_interno') }}" method="POST" id="buscarformulario">
                    @method('POST')
                    @csrf
                    <div class="input-group flex-nowrap">
                        <input type="text" name="buscar" id="buscar" class="form-control" placeholder="Buscar..." aria-label="" aria-describedby="addon-wrapping">
                        <input type="text" name="id_sucursal" id="id_sucursal" hidden>
                        <button class="input-group-text" id="inputBuscar" disabled><i class="fas fa-search"></i></button><br>
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
        <table class="table table-striped">
            <thead>
                <tr>
                  <th scope="col">Opciones</th>
                  <th scope="col">Producto</th>
                  <th scope="col">Sucursal</th>
                  <th scope="col">Tipo Ingreso Salida</th>
                  <th scope="col">Ult. Cant. Ing.</th>
                  <th scope="col">Stock</th>
                  <th scope="col">Usuario</th>
                  <th scope="col">Estado</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($inventariosInternos as $aux)
                  <tr>
                    <th scope="row">
                        @php
                        $auxdata = json_encode([
                            "id"=>$aux->id_inventario_interno,
                            "id_producto"=>$aux->id_producto,
                            "id_sucursal"=>$aux->id_sucursal,
                            "id_tipo_ingreso_salida"=>$aux->id_tipo_ingreso_salida,
                            "stock"=>$aux->stock,
                            "cantidad_ingreso"=>$aux->cantidad_ingreso,
                            "estado"=>$aux->estado_inventario_interno,
                            ]);
                        // var_dump($aux);
                        echo '<i class="fas fa-edit fa-xl i" style="color:#6BA9FA" onclick=\'editar('.$auxdata.')\'></i>';
                        if ($aux->estado_inventario_interno == 1)
                        {
                            echo  '<i class="fas fa-trash-alt fa-xl" style="color:#FA746B" onclick=\'habilitarDesabilitar('.$auxdata.')\'></i>';
                        }else{
                            echo '<i class="fas fa-check-circle fa-xl" style="color:#FAAE43" onclick=\'habilitarDesabilitar('.$auxdata.')\'></i>';
                        }
                      @endphp

                    </th>
                    <th>
                        {{$aux->nombre_producto}} <br>
                        Talla: <span class="badge bg-primary">{{ $aux->talla!=""?$aux->talla:"ST(Sin Talla)" }}</span>
                        Precio: <span class="badge bg-info text-dark">{{ $aux->precio}} Bs.</span> <br>
                        {{-- {{"$aux->nombre_producto - Talla: $aux->talla - Precio: $aux->precio Bs"}} --}}
                    </th>
                    <th>{{"$aux->razon_social_sucursal - $aux->ciudad_sucursal"}}</th>
                    <th>{{"$aux->nombre_tipo_ingreso_salida"}}</th>
                    <th>{{$aux->cantidad_ingreso}}</th>
                    <th>{{$aux->stock}}</th>
                    <th>{{"$aux->nombre_usuario"}}</th>
                    <td>
                        @if ( $aux->estado_inventario_interno == 1 )
                            <span class="badge bg-success">Activo</span>
                        @else
                            <span class="badge bg-warning">Inactivo</span>
                        @endif
                    </td>
                  </tr>
                @endforeach
              </tbody>
        </table>
        {{ $inventariosInternos->links() }}
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
                                            @foreach ($sucursales as $item)
                                               @if ($item->estado_sucursal == 1)
                                                  <option value="{{ $item->id_sucursal_user_sucursal }}" @if (isset($id_sucursal) && $item->id_sucursal_user_sucursal == $id_sucursal ) selected  @endif>{{ "$item->razon_social_sucursal - $item->ciudad_sucursal - ".substr($item->direccion_sucursal,0,40)."..." }}</option>
                                                @else
                                                  <option value="{{ $item->id_sucursal_user_sucursal }}" disabled>{{ "$item->razon_social_sucursal - $item->ciudad_sucursal - ".substr($item->direccion_sucursal,0,30)."... (deshabilitado)" }}</option>
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
                                                @foreach ($productos as $item)
                                                @if ($item->estado == 1)
                                                    <option value="{{ $item->id }}">
                                                        {{ $item->nombre }} -
                                                        {{ $item->talla != "" ? "Talla: ".$item->talla : "ST(Sin Talla)" }} -
                                                        {{ $item->precio != "" ? "Precio: ".$item->precio : 0 }} Bs.
                                                    </option>
                                                    @else
                                                    <option value="{{ $item->id }}" disabled>
                                                        {{ $item->nombre }} -
                                                        {{ $item->talla != "" ? "Talla: ".$item->talla : "ST(Sin Talla)" }} -
                                                        {{ $item->precio != "" ? "Precio: ".$item->precio : 0 }} Bs. (deshabilitado)
                                                    </option>
                                                @endif
                                                @endforeach
                                        </select>
                                    </div>
                            </div>
                        </div>
                      @endisset
                      <br>
                      @isset($tiposIngresosSalidas)
                        <div class="row">
                            <div class="col-md-2">
                                <label for="inputPassword6" class="col-form-label">Tipo de Ingreo: </label>
                            </div>
                            <div class="col-md-8">
                                    <div class="input-group">
                                        <select class="form-select" aria-describedby="" name="id_tipo_ingreso_salida" id="modalSelectTipoEntrada">
                                            <option value="seleccionado" selected disabled>Seleccione una opcion...</option>
                                                @foreach ($tiposIngresosSalidas as $item)
                                                @if ($item->estado == 1)
                                                    <option value="{{ $item->id }}">{{ "$item->tipo"}}</option>
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
            $('#selectSucursalRegAct').val($("#select_sucursal option:selected").attr('value'))
        }

    });

    $("#select_sucursal").on('change',function(){
        $('#btnModalRegistroActualizacion').prop( "disabled", false );
        $('#reqBtnAgregarItem').remove();
        $('#inputBuscar').prop( "disabled", false );
        $('#btnBuscarItem').remove();
        $('#id_sucursal').val($("#select_sucursal option:selected").attr('value'));
        $('#modalSelectSucursal').val($("#select_sucursal option:selected").attr('value'));
        $('#title_nombre_sucursal').text($("#select_sucursal option:selected").text());
    });

    $('button').on('click',function()
    {
        event.preventDefault();
        if ($(this).attr('id') == 'inputBuscar'){
            $("#buscarformulario").submit();

        } else if ($(this).attr('id') == 'modalBtnGuardarActualizar'){
            $('#modalSelectSucursal').removeAttr('disabled');
            $("#modalFormularioRegistroActualizacion").submit();

        } else if ($(this).attr('id') == 'btnFormDataInventario'){
            $('#dataformInventario').attr('action', "{{ route('data_inventario_interno_page') }}" );
            $("#dataformInventario").submit();

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

    function habilitarDesabilitar(item)
    {
        let mensaje = '';
        console.log(item);
        if(item.estado == 1){
            mensaje = 'Esta seguro de deshabilitar el Producto?';
        }else{
            mensaje = 'Esta seguro de habilitar el Producto?';
        }

        Swal.fire({
                title: mensaje,
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
                        url: "{{ route('actualizar_estado_inventario_interno') }}",
                        data: {"id":item.id, "estado":item.estado},
                        success: function (response) {
                          if (response.estado) {
                            // Swal.fire("Cambio Guardado!", "", "success");
                            $("#mensaje-errores-inputs").html('<div class="alert alert-success" role="alert">Registro eliminado exitosamente! </div>')
                            setTimeout(() => {
                                location.reload();
                            }, 5000);
                          }
                        }
                    });
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
