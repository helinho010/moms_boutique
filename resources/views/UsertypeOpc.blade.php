@extends('layouts.plantillabase')

@section('title','Roles')

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
                echo '<div class="alert alert-success" role="alert">El Proveedor se registro correctamente</div>';
            }else{
                echo '<div class="alert alert-danger" role="alert">Error al registrar al Proveedor</div>';
            }
        }

        if (isset($_GET['actualizado'])) 
        {
            if ($_GET['actualizado'] == 1) {
                echo '<div class="alert alert-success" role="alert">El Proveedor fue actualizado correctamente</div>';
            }else{
                echo '<div class="alert alert-danger" role="alert">Error al actualizar el Proveedor</div>';
            }
        }

        if ($errors->first('nombre') != '' ||
            $errors->first('telefono') != '' ||
            $errors->first('ciudad') != '' ) 
        {
            echo '<div class="alert alert-danger" role="alert">'.
                $errors->first('nombre')."<br>".
                $errors->first('telefono')."<br>".
                $errors->first('ciudad')."<br>".
                '</div>';
        }   
    @endphp
@endsection

@section('card-title')
    <div class="row">
        <div class="col">
            <h4>Usuarios</h4>
        </div>
        <div class="col text-end">
            <button type="button" class="btn btn-success" id="modalCategoria" data-bs-toggle="modal" data-bs-target="#exampleModal">
                <i class="fas fa-plus"></i> Agregar Rol 
            </button>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <form action="{{ route('buscar_proveedor') }}" method="POST" id="buscarformulario">
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
                  <th scope="col">Rol</th>
                  <th scope="col">Opciones Habilitadas</th>
                  <th scope="col">Fecha de Creacion/Modificacion</th>
                  <th scope="col">Estado</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($roles as $rol)
                  <tr>
                    <th scope="row">
                      <i class="fas fa-edit fa-xl i" style="color:#6BA9FA" onclick='editar(@php echo json_encode([
                        "id"=>$rol->id,
                        "nombre"=>$rol->nombre,
                        "telefono"=>$rol->telefono,
                        "ciudad" =>$rol->ciudad,
                        "observacion" => $rol->observacion,
                        "estado" => $rol->estado,
                        ]); @endphp)'></i>
                      @php
                        $dataProveedor = json_encode([
                            "id"=>$rol->id,
                            "nombre"=>$rol->nombre,
                            "telefono"=>$rol->telefono,
                            "ciudad" =>$rol->ciudad,
                            "observacion" => $rol->observacion,
                            "estado" => $rol->estado,
                        ]);
                        if ($rol->estado == 1) 
                        {
                            echo  '<i class="fas fa-trash-alt fa-xl" style="color:#FA746B" onclick=\'habilitarDesabilitar('.$dataProveedor.')\'></i>'; 
                        }else{
                            echo '<i class="fas fa-check-circle fa-xl" style="color:#FAAE43" onclick=\'habilitarDesabilitar('.$dataProveedor.')\'></i>';
                        }
                      @endphp

                    </th>
                    <td>{{ $rol->type }}</td>
                    <td>{{ $opciones_habilitadas }}</td>
                    <td>{{ $rol->updated_at }}</td>

                    <td> 
                        @if ( $rol->estado == 1 )
                            <span class="badge bg-success">Activo</span>    
                        @else
                            <span class="badge bg-warning">Inactivo</span>    
                        @endif
                    </td>
                  </tr>    
                @endforeach
              </tbody>
        </table>
        {{ $roles->links() }}
    </div>

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Nuevo Proveedor</h5>
                <button type="button" class="btn-close cerrarModal" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('nuevo_proveedor') }}" id="nuevo_proveedor">
                        @csrf
                        @method('POST')
                        <div class="mb-3">
                          <label for="nombre_proveedor" class="form-label">Nombre del Proveedor:</label>
                          <input type="text" class="form-control" name="nombre" id="nombre_proveedor" placeholder="Introduzca el nombre del Proveedor"> 
                        </div>
                        <div class="mb-3">
                            <label for="telefono_proveedor" class="form-label">Telefono del Proveedor:</label>
                            <input type="text" class="form-control" name="telefono" id="telefono_proveedor" placeholder="Introduzca el nombre del Proveedor"> 
                          </div>
                          <div class="mb-3">
                            <label for="ciudad_proveedor" class="form-label">Ciudad:</label>
                            <input type="text" class="form-control" name="ciudad" id="ciudad_proveedor" placeholder="Introduzca el nombre del Proveedor"> 
                          </div>
                          <div class="mb-3">
                            <label for="observacion_proveedor" class="form-label">Observacion:</label>
                            <textarea class="form-control" name="observacion" id="observacion_proveedor" rows="3" placeholder="Observacion.."></textarea>
                          </div>
                      </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger cerrarModal" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-success" id="btnGuardarActualizar">Guardar</button>
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
        } else if ($(this).attr('id') == 'btnGuardarActualizar') 
        {
            $("#nuevo_proveedor").submit();
        }
    });

    function editar(proveedor){
        $("#exampleModal").modal("show");
        $("#exampleModalLabel").html("<h3>Editar Proveedor</h3>");
        $("#nuevo_proveedor").attr("action","{{ route('actualizar_proveedor') }}");
        $("#nuevo_proveedor").append('<input type="text" name="id" '+ 'value="'+ proveedor.id +'"' +'hidden>');
        $("#nombre_proveedor").val(proveedor.nombre);
        $("#telefono_proveedor").val(proveedor.telefono);
        $("#ciudad_proveedor").val(proveedor.ciudad);
        $("#observacion_proveedor").val(proveedor.observacion);
        $("#btnGuardarActualizar").val("Actualizar");
        $("#btnGuardarActualizar").on('click',function(){
            $("#nuevo_proveedor").submit();
        });
    }

    function habilitarDesabilitar(proveedor)
    {
        let mensaje = '';
        if(proveedor.estado == 1){
            mensaje = 'Esta seguro de deshabilitar al proveedor?';
        }else{
            mensaje = 'Esta seguro de habilitar al proveedor?';
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
                        url: '/actualizar_estado_proveedor',
                        data: {"id":proveedor.id, "estado":proveedor.estado},
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
        // $('.select2').select2();
        var parametroGetExito = getParameterByName('exito');
        var parametroGetActualizado = getParameterByName('actualizado');
        var pathname = window.location.pathname;
        
        if (parametroGetExito == 1 || parametroGetActualizado == 1) 
        {
            setTimeout(() => {
                $(location).attr('href',pathname);
            }, 5000);
        } 
    });
    

</script>
@endpush