@extends('layouts.plantillabase')

@section('title','Traspaso Productos')

@section('css')
    <link rel="stylesheet" href="{{ asset('DataTables/datatables.min.css') }}">
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
                echo '<div class="alert alert-success" role="alert">La Categoria se registro correctamente</div>';
            }else{
                echo '<div class="alert alert-danger" role="alert">Error al registrar la Categoria</div>';
            }
        }
        if ($errors->first('nombre') != '') {
            echo '<div class="alert alert-danger" role="alert">'.$errors->first('nombre').'</div>';
        }   
    @endphp
@endsection

@section('card-title')
    <div class="row">
        <div class="col">
            <h4>Lista de Traspaso de Productos</h4>
        </div>
        <div class="col text-end">
            <button type="button" class="btn btn-success" id="modalCategoria" data-bs-toggle="modal" data-bs-target="#exampleModal">
                <i class="fas fa-plus"></i> Agregar Traspaso de Productos 
            </button>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <form action="{{ route('buscar_categoria') }}" method="POST" id="buscarformulario">
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
                  <th scope="col">Sucursal Origen</th>
                  <th scope="col">Sucursal Destino</th>
                  <th scope="col">Producto</th>
                  <th scope="col">Cantidad</th>
                  <th scope="col">Tipo Ingreso Salida</th>
                  <th scope="col">Fecha Registro/Actualizacion</th>
                  <th scope="col">Usuario</th>
                  <th scope="col">Estado</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($traspasos as $traspaso)
                  <tr>
                    <th scope="row">
                      {{-- <i class="fas fa-edit fa-xl i" style="color:#6BA9FA" onclick='editarCategoria(@php echo json_encode(["id"=>$traspaso->id,"nombre"=>$traspaso->nombre]); @endphp)'></i> --}}
                      @php
                        // $dataCategoria = json_encode(['id'=>$traspaso->id,'estado'=>$traspaso->estado]);
                        // if ($traspaso->estado_trasporte_productos == 1) 
                        // {
                        //     echo  '<i class="fas fa-trash-alt fa-xl" style="color:#FA746B" onclick=\'habilitarDesabilitar('.$dataCategoria.')\'></i>'; 
                        // }else{
                        //     echo '<i class="fas fa-check-circle fa-xl" style="color:#FAAE43" onclick=\'habilitarDesabilitar('.$dataCategoria.')\'></i>';
                        // }
                      @endphp

                    </th>
                    <td>
                        @foreach ($sucursales as $sucursal)
                            @if ($traspaso->id_sucursal_origen == $sucursal->id_sucursal)
                                {{ $sucursal->razon_social_sucursal }} - {{ substr($sucursal->direccion_sucursal,0,35)."..." }}
                            @endif        
                        @endforeach
                    </td>
                    <td>
                        @foreach ($sucursales as $sucursal)
                            @if ($traspaso->id_sucursal_destino == $sucursal->id_sucursal)
                                {{ $sucursal->razon_social_sucursal }} - {{ substr($sucursal->direccion_sucursal,0,35)."..." }}
                            @endif        
                        @endforeach
                    </td>
                    <td>
                        {{ $traspaso->nombre_productos }} <br>
                        Talla: <span class="badge bg-primary">{{ $traspaso->talla_productos!=""?$traspaso->talla_productos:"ST(Sin Talla)" }}</span> 
                        Precio: <span class="badge bg-info text-dark">{{ $traspaso->descripcion_productos!=""?$traspaso->descripcion_productos:"0" }} Bs.</span>
                    </td>
                    <td>{{ $traspaso->cantidad_trasporte_productos }}</td>
                    <td>{{ $traspaso->nombre_tipo_ingreso_salidas }}</td>
                    <td>{{ $traspaso->updated_at_trasporte_productos }}</td>
                    <td>{{ $traspaso->name_usuario }}</td>
                    <td> 
                        @if ( $traspaso->estado_trasporte_productos == 1 )
                            <span class="badge bg-success">Activo</span>    
                        @else
                            <span class="badge bg-warning">Inactivo</span>    
                        @endif
                    </td>
                  </tr>    
                @endforeach
              </tbody>
        </table>
        {{ $traspasos->links() }}
    </div>

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Nuevo Traspaso</h5>
                <button type="button" class="btn-close cerrarModal" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('nuevo_traspaso_productos') }}" id="nuevo_traspaso_productos">
                        @csrf
                        @method('POST')
                        <div class="row">
                            <div class="mb-3">
                                <div class="col-md-10">
                                    <label for="">Sucursal de Origen:</label>
                                    <div class="input-group">
                                        <select class="form-select" aria-describedby="" name="id_sucursal_origen" id="modalSelectSucursalOrigen">
                                            <option value="seleccionado" selected disabled>Seleccione una opcion...</option>
                                                @foreach ($sucursales as $item)
                                                   @if ($item->estado_sucursal)
                                                      <option value="{{ $item->id_sucursal }}">{{ $item->razon_social_sucursal }} - {{ substr($item->direccion_sucursal,0,35)." ..." }}</option>
                                                    @else
                                                      <option value="{{ $item->id_sucursal }}" disabled>{{ "$item->nombre - $item->fecha_evento (deshabilitado)" }}</option>
                                                   @endif
                                                @endforeach
                                         </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-3">
                                <div class="col-md-10">
                                    <label for="">Sucursal de Destino:</label>
                                    <div class="input-group">
                                        <select class="form-select" aria-describedby="" name="id_sucursal_destino" id="modalSelectSucursalDestino">
                                            <option value="seleccionado" selected disabled>Seleccione una opcion...</option>
                                                @foreach ($sucursales as $item)
                                                   @if ($item->estado_sucursal)
                                                      <option value="{{ $item->id_sucursal }}">{{ $item->razon_social_sucursal }} - {{ substr($item->direccion_sucursal,0,35)." ..." }}</option>
                                                    @else
                                                      <option value="{{ $item->id_sucursal }}" disabled>{{ "$item->nombre - $item->fecha_evento (deshabilitado)" }}</option>
                                                   @endif
                                                @endforeach
                                         </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-3">
                                <div class="col-md-10">
                                    <label for="">Producto:</label>
                                    <div class="input-group">
                                        <select class="form-select" aria-describedby="" name="id_producto" id="modalSelectProducto">
                                            <option value="seleccionado" selected disabled>Seleccione una opcion...</option>
                                                @foreach ($productos as $item)
                                                    @if ($item->estado_producto)
                                                        <option value="{{ $item->id_producto }}">{{ $item->nombre_producto }} - Talla: {{ $item->talla!=""?$item->talla:"ST(Sin talla)" }} - {{ "(Stock:".$item->stock.")" }}</option>
                                                    @else
                                                        <option value="{{ $item->id_producto }}" disabled>{{ $item->nombre_producto }} - Talla: {{ $item->talla!=""?$item->talla:"ST(Sin talla)" }} - {{ "(Stock:".$item->stock.") (deshabilitado)" }}</option>
                                                    @endif
                                                @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-3">
                                <div class="col-md-10">
                                    <label for="">Tipo Salida:</label>
                                    <div class="input-group">
                                        <select class="form-select" aria-describedby="" name="id_tipo_salida" id="modalSelectTipoSalida">
                                            <option value="seleccionado" selected disabled>Seleccione una opcion...</option>
                                                @foreach ($tipoSalida as $item)
                                                    @if ($item->estado)
                                                        <option value="{{ $item->id }}">{{ $item->tipo }}</option>
                                                    @else
                                                        <option value="{{ $item->id }}" disabled>{{ $item->tipo }}</option>
                                                    @endif
                                                @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-10">
                                <div class="mb-3">
                                    <label for="exampleInputEmail1" class="form-label">Cantidad:</label>
                                    <input type="number" class="form-control" name="cantidad" id="cantidad" aria-describedby="emailHelp" placeholder="Introduzca la Cantidad"> 
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-10">
                                <div class="mb-3">
                                    <label for="exampleInputEmail1" class="form-label">Observaciones:</label>
                                    <textarea class="form-control" name="observaciones" id="observaciones" cols="30" rows="3" aria-describedby="emailHelp" placeholder="Introduzca el nombre de la Categoria"></textarea>
                                </div>
                            </div>  
                        </div>
                      </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger cerrarModal" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-success" id="inputNombreModal">Guardar</button>
                </div>
            </div>
            </div>
        </div>
@endsection


@push('scripts')
<script src="{{ asset('jquery/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('DataTables/datatables.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    $('button').on('click',function() 
    {   
        event.preventDefault(); 

        if ($(this).attr('id') == 'inputBuscar') 
        {
            $("#buscarformulario").submit();
        } else if ($(this).attr('id') == 'inputNombreModal') 
        {
            $("#nuevo_traspaso_productos").submit();

        } else if ($(this).attr('class') == 'btn btn-danger cerrarModal' || $(this).attr('class') == 'btn-close cerrarModal') 
        {
            console.log("Que es esto");
            $("#modalSelectSucursalOrigen").val('seleccionado');
            $("#modalSelectSucursalDestino").val('seleccionado');
            $("#modalSelectProducto").val('seleccionado');
            $("#cantidad").val('');
            $("#observaciones").val('');
        }
    });

    function getParameterByName(name) {
        name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
        var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
        return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
    }

    $("#modalSelectSucursalOrigen").on('change',function()
    {
        var parametroGet = getParameterByName('id_sucursal');
        var pathname = window.location.pathname;

        if (parametroGet != '' && parametroGet == $("#modalSelectSucursalOrigen option:selected").val()) 
        {
            console.log(parametroGet);
        } else{
            window.location.href = pathname+"?id_sucursal="+$("#modalSelectSucursalOrigen option:selected").val();
        }
    });
    

    $(document).ready(function(){
        var parametroGet = getParameterByName('id_sucursal');
        var pathname = window.location.pathname;

        if (parametroGet != '') 
        {
            console.log(parametroGet);
            $("#exampleModal").modal('show');
        } 

        $("#home").removeClass('active');
        $("#traspaso\\ productos").addClass('active');
    });



    function habilitarDesabilitar(categoria)
    {
        let mensaje = '';
        if(categoria.estado == 1){
            mensaje = 'Esta seguro de deshabilitar la categoria?';
        }else{
            mensaje = 'Esta seguro de habilitar la categoria?';
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
                        url: '/actualizar_estado',
                        data: {"id":categoria.id, "estado":categoria.estado},
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

</script>
@endpush