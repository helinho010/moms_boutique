@extends('layouts.plantillabase')

@section('title','Inventario Externo')

@section('css')
    <style>
        table > tbody > tr > th > i{
            font-size: 20px;
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
                echo '<div class="alert alert-danger" role="alert">Error al registrar el nuevo Item de Inventario <br>' ;
                if (isset($_GET['mensaje'])) 
                {
                    echo $_GET['mensaje'];
                }
                echo '</div>';
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

        if ($errors->first('id_evento') != '' ||
            $errors->first('id_producto') != '' ||
            $errors->first('id_tipo_ingreso_salida') != '' ||
            $errors->first('cantidad_ingreso') != '') 
            {
                echo '<div class="alert alert-danger" role="alert">'.
                $errors->first('id_evento')."<br>".
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
            <h4>Inventario Externo</h4>
        </div>
        <div class="col text-end">
            <button type="button" class="btn btn-success" id="btnModalRegistroActualizacion" data-bs-toggle="modal" data-bs-target="#exampleModal" disabled>
                <i class="fas fa-plus"></i> Agregar Item al Inventario
            </button><br>
            <span style="font-size: 10px; color:red" id="reqBtnAgregarItem">(*) Seleccionar una Evento</span>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-2">
                    <label for="inputPassword6" class="col-form-label">Evento: </label>
                </div>
                <div class="col-md-10">
                    <form action="{{ route('data_inventario_externo') }}" method="POST" id="dataformInventario">
                        @method('POST')
                        @csrf
                        <div class="input-group">
                            <select class="form-select" aria-describedby="" name="id_evento" id="select_evento">
                                <option value="seleccionado" @if (!isset($id_evento) || !isset($_GET['id_evento'])) selected  @endif disabled>Seleccione una opcion...</option>
                                    @foreach ($eventos as $item)
                                       @if ($item->estado == 1)
                                          <option value="{{ $item->id }}" 
                                            @if ( isset($id_evento) && $item->id == $id_evento ) 
                                                selected  
                                            @endif
                                            >{{ "$item->nombre - $item->fecha_evento" }}</option>
                                        @else
                                          <option value="{{ $item->id_evento_user_sucursal }}" disabled>{{ "$item->nombre - $item->fecha_evento ... (deshabilitado)" }}</option>
                                       @endif
                                    @endforeach
                             </select>
                             <button class="input-group-text" id="btnFormDataInventario"><i class="fas fa-search"></i></button>
                             <button class="input-group-text" id="btnFormDataExportPdf" disabled title="Exportar a PDF"><i id="ibtnFormDataExportPdf" class="fas fa-file-pdf" style="font-size: 19px; color:#292622"></i></button>
                             <button class="input-group-text" id="btnFormDataRetornarProductos" disabled title="Devolver los productos al Almacen Central"><i id="ibtnFormDataRetornarProductos" class="fas fa-exchange-alt" style="font-size: 19px; color:#292622"></i></button>
                        </div>
                    </form>
                </div>
            </div>  
        </div>
        
        <div class="col-md-4">
            <div class="row">
                <form action="{{ route('buscar_inventario_externo') }}" method="POST" id="buscarformulario">
                    @method('POST')
                    @csrf
                    <div class="input-group flex-nowrap">
                        <input type="text" name="buscar" id="buscar" class="form-control" placeholder="Buscar..." aria-label="" aria-describedby="addon-wrapping">
                        <input type="text" name="id_evento" id="id_evento" hidden>
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
                @foreach ($inventariosExternos as $aux)
                  <tr>
                    <th scope="row">
                        @if ($aux->estado_inventario_externos != 3 )
                                {{-- <i class="fas fa-edit fa-xl i" style="color:#6BA9FA" onclick='editar(@php echo json_encode([
                                "id"=>$aux->id,
                                "codigo"=>$aux->codigo_producto,
                                "nombre"=>$aux->nombre,
                                "precio"=>$aux->precio,
                                "talla"=>$aux->talla,
                                "id_categoria"=>$aux->id_categoria,

                                ]); @endphp)'>
                                </i> --}}
                                @php
                                $auxdata = json_encode([
                                    "id"=>$aux->id_inventario_externos,
                                    "id_producto"=>$aux->id_productos,
                                    "id_sucursal"=>$aux->id_sucursals,
                                    "id_evento"=>$aux->id_eventos,
                                    "id_tipo_ingreso_salida"=>$aux->id_tipo_ingreso_salidas,
                                    "cantidad_salida,"=>$aux->cantidad_inventario_externos,
                                    "estado"=>$aux->estado_inventario_externos,
                                    ]);
                                if ($aux->estado_inventario_externos == 1) 
                                {
                                    echo  '<i class="fas fa-trash-alt fa-xl" style="color:#FA746B" onclick=\'habilitarDesabilitar('.$auxdata.')\'></i>'; 
                                }else{
                                    echo '<i class="fas fa-check-circle fa-xl" style="color:#FAAE43" onclick=\'habilitarDesabilitar('.$auxdata.')\'></i>';
                                }
                            @endphp
                        @else

                        @endif
                    </th>
                    <th>{{"$aux->razon_social_sucursals - $aux->ciudad_sucursals -".substr($aux->direccion_sucursals,0,30)."..." }}</th>
                    <th>
                        {{ $aux->nombre_productos }} <br>
                        Talla: <span class="badge bg-primary">{{ $aux->talla_productos!=""?$aux->talla_productos:"ST(Sin Talla)"}}</span>
                        Precio: <span class="badge bg-info text-dark">{{ $aux->precio_productos!=""?$aux->precio_productos:0 }} Bs</span>   
                    </th>
                    <th>{{$aux->cantidad_inventario_externos}}</th>
                    <th>{{"$aux->tipo_tipo_ingreso_salidas"}}</th>
                    <th>{{"$aux->nombre_users"}}</th>
                    <td> 
                        @if ( $aux->estado_inventario_externos == 1 )
                            <span class="badge bg-success">Activo</span>    
                        @else
                            @if ($aux->estado_inventario_externos == 2)
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
        {{ $inventariosExternos->links() }}
    </div>

        <!-- Modal -->
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
                                            @foreach ($eventos as $item)
                                               @if ($item->estado == 1)
                                                  <option value="{{ $item->id }}" 
                                                    @if (isset($id_evento) && $item->id == $id_evento ) 
                                                        selected  
                                                    @endif
                                                    >{{ "$item->nombre - $item->fecha_evento" }}</option>
                                                @else
                                                  <option value="{{ $item->id_evento_user_sucursal }}" disabled>{{ "$item->nombre - $item->fecha_evento (deshabilitado)" }}</option>
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
    
    $('button').on('click',function() 
    {   
        event.preventDefault();

        if ($(this).attr('id') == 'inputBuscar'){
            $("#buscarformulario").submit();

        } else if ($(this).attr('id') == 'modalBtnGuardarActualizar'){
            $("#modalFormularioRegistroActualizacion").submit();

        } else if ($(this).attr('id') == 'btnFormDataInventario'){
            $("#dataformInventario").attr('action',"{{ route('data_inventario_externo') }}");
            $("#dataformInventario").submit();

        }else if($(this).attr('id') == 'btnFormDataExportPdf'){
            $("#dataformInventario").attr('action',"{{ route('inventario_externo_pdf_lista') }}");
            $("#dataformInventario").submit();
        }else if ( $(this).attr('id') == 'btnFormDataRetornarProductos') {
            devolverProductos($('#select_evento').val());
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

    function editar(item)
    {
        $("#exampleModal").modal("show");
        $("#exampleModalLabel").html("<h3>Editar Producto</h3>");
        $("#formularioRegistroActualizacion").attr("action","{{ route('actualizar_producto') }}");
        $("#formularioRegistroActualizacion").append('<input type="text" name="id" '+ 'value="'+ item.id +'"' +'hidden>');
        $("#id_categoria_producto").val(item.id_categoria);
        $("#nombre_producto").val(item.nombre);
        $("#precio_producto").val(item.precio);
        $("#talla_producto").val(item.talla);
        $("#btnGuardarActualizar").val("Actualizar");
        $("#btnGuardarActualizar").on('click',function(){
            $("#formularioRegistroActualizacion").submit();
            resestablecerValoresModal();
        });
    }

    function habilitarDesabilitar(item)
    {
        let mensaje = '';
        console.log(item);
        if(item.estado == 1){
            mensaje = 'Esta seguro de deshabilitar el Item del Inventario?';
        }else{
            mensaje = 'Esta seguro de habilitar el Item del Inventario?';
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
                        url: "{{ route('actualizar_estado_inventario_externo') }}",
                        data: {"id":item.id, "estado":item.estado},
                        success: function (response) {
                          Swal.fire("Cambio Guardado!", "", "success");        
                          location.reload();
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
        $("#inventario\\ externo").addClass('active');

        $('.select2Modal').select2({
            theme: "bootstrap-5",
            dropdownParent: $('#exampleModal'),
            width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
            placeholder: $( this ).data( 'placeholder' ),
        });
    });
    
</script>
@endpush
