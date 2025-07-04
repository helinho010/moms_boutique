@extends('layouts.plantillabase')

@section('title','Usuarios')

@section('css')
    <link rel="stylesheet" href="{{ asset('DataTables/datatables.min.css') }}">
    <style>
        table > tbody > tr > th > i{
            font-size: 20px;
        }
    </style>
@endsection

@section('mensaje-errores')
    @php
        if (isset($_GET['exito'])) 
        {
            if ($_GET['exito'] == 1) {
                echo '<div class="alert alert-success" role="alert">El usuario se registro correctamente</div>';
            }else{
                echo '<div class="alert alert-danger" role="alert">Error al registrar al usuario</div>';
            }
        }

        if (isset($_GET['actualizado'])) 
        {
            if ($_GET['actualizado'] == 1) {
                echo '<div class="alert alert-success" role="alert">El usuario fue actualizado correctamente</div>';
            }else{
                echo '<div class="alert alert-danger" role="alert">Error al actualizar el usuario</div>';
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

    @if ($errors->any())
        <x-formulario.mensaje-error-validacion-inputs color="danger">
            @foreach ($errors->all() as $error)
                * {{ $error }} <br>
            @endforeach
        </x-formulario.mensaje-error-validacion-inputs>
    @endif

    @if (session('usuarioNoEncontrado'))
        <x-formulario.mensaje-error-validacion-inputs color="danger">
            <h5><i class="fas fa-exclamation-triangle"></i> {{session('usuarioNoEncontrado')}} </h5>
        </x-formulario.mensaje-error-validacion-inputs>
    @endif

    @if (session('usuarioEditado'))
        <x-formulario.mensaje-error-validacion-inputs color="success">
            <h5><i class="fas fa-thumbs-up"></i> Usuario editado Correctamente</h5>
        </x-formulario.mensaje-error-validacion-inputs>
    @endif

    @if (session('usuarioCreado'))
        <x-formulario.mensaje-error-validacion-inputs color="success">
            <h5><i class="fas fa-check-circle"></i> {{ session('usuarioCreado') }} </h5>
        </x-formulario.mensaje-error-validacion-inputs>
    @endif
    
    @if (session('usuarioNoCreado'))
        <x-formulario.mensaje-error-validacion-inputs color="danger">
            <h5><i class="fas fa-exclamation-circle"></i> {{ session('usuarioNoCreado') }} </h5>
        </x-formulario.mensaje-error-validacion-inputs>
    @endif

@endsection

@section('card-title')
    <div class="row">
        <div class="col">
            <h4>Usuarios</h4>
        </div>
        <div class="col text-end">
            @can('crear usuario')  
                <button type="button" class="btn btn-success" 
                        id="modalUsuario" data-bs-toggle="modal" 
                        data-bs-target="#crearUsuarioModal">
                    <i class="fas fa-plus"></i> Agregar Usuario 
                </button>
            @endcan
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <form action="{{ route('home_usuarios') }}" method="GET" id="buscarformulario">
                <div class="input-group flex-nowrap">
                    <input type="text" name="buscar" class="form-control" placeholder="Buscar...">
                    <button type="submit" class="input-group-text"><i class="fas fa-search"></i></button>
                </div>
            </form>
        </div>
        <div class="col-md-3"></div>
    </div>
    <br>
    <div class="row">
        <table class="table table-striped table-bordered"> 
            <thead>
                <tr class="text-center align-middle">
                  <th scope="col">Opciones</th>
                  <th scope="col">Nombre</th>
                  <th scope="col">Usuario</th>
                  <th scope="col">Sucursales Habilitadas</th>
                  <th scope="col">Fecha de Creacion/Modificacion</th>
                  <th scope="col">Rol</th>
                  <th scope="col">Estado</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($usuarios as $usuario)
                  <tr class="text-center">
                    <th scope="row">
                      @if ($usuario->id_usuario != 1)
                            <a href="{{ route('editar_usuario',['id_usuario'=>$usuario->id_usuario]) }}"><i class="fas fa-edit fa-xl i" style="color:#6BA9FA"></i></a>
                            @php
                                $dataProveedor = json_encode([
                                    "id" => $usuario->id_usuario,
                                    "nombre_usuario" => $usuario->nombre_usuario,
                                    "usuario" => $usuario->usuario,
                                    "correo" => $usuario->email_usuario,
                                    "tipo_usuario" => $usuario->id_tipo_usuario,
                                    "estado" => $usuario->estado_usuario,
                                    "sucursales" => $sucursales,
                                ]);
                                if ($usuario->estado_usuario == 1) 
                                {
                                    echo  '<i class="fas fa-trash-alt fa-xl" style="color:#FA746B" onclick=\'habilitarDesabilitar('.$dataProveedor.')\'></i>'; 
                                }else{
                                    echo '<i class="fas fa-check-circle fa-xl" style="color:#FAAE43" onclick=\'habilitarDesabilitar('.$dataProveedor.')\'></i>';
                                }
                            @endphp
                      @endif
                    </th>
                    <td>{{ $usuario->nombre_usuario }}</td>
                    <td>{{ $usuario->usuario }}</td>
                    <td>
                        @foreach (App\Models\UserSucursal::sucursalesHabilitadasUsuario($usuario->id_usuario) as $sucursal)
                           {{ $sucursal->ciudad }} - {{ $sucursal->direccion }} <br>
                        @endforeach
                    </td>
                    <td>{{ $usuario->updated_at_usuario }}</td>
                    <td>        
                        @foreach (App\Models\User::getUsersRoles($usuario->id_usuario) as $rol)

                            @if ( $loop->count > 1)
                                @if ($loop->first)
                                    {{ $rol }} |
                                @else
                                    @if ( $loop->last)
                                        {{ $rol }}
                                    @else
                                        {{ $rol }} |
                                    @endif
                                @endif
                            @else
                                {{ $rol }}
                            @endif
                        @endforeach
                    </td>    
                    <td> 
                        @if ( $usuario->estado_usuario == 1 )
                            <span class="badge bg-success">Activo</span>    
                        @else
                            <span class="badge bg-warning">Inactivo</span>    
                        @endif
                    </td>
                  </tr>    
                @endforeach
              </tbody>
        </table>
        {{ $usuarios->links() }}
    </div>

        <!-- Modal -->
        @can('crear usuario')
            <div class="modal fade" id="crearUsuarioModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="crearUsuarioModalLabel">Nuevo Usuario</h5>
                    <button type="button" class="btn-close cerrarModal btnCerrarModalNuevoUsuario" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="{{ route('nuevo_usuario') }}" id="nuevo_usuario">
                            @csrf
                            @method('POST')
                            <div class="row">
                                <div class="col-md">
                                    <div class="mb-3">
                                        <label for="nombre_usuario" class="form-label">Nombre Completo del Usuario:</label>
                                        <input type="text" class="form-control" name="nombre_usuario" id="nombre_usuario" placeholder="Introduzca el nombre del usuario"> 
                                    </div>
                                </div>
                                <div class="col-md">
                                    <div class="mb-3">
                                        <label for="usuario" class="form-label">Usuario:</label>
                                        <input type="text" class="form-control" name="usuario" id="usuario" placeholder="Introduzca el usuario" autocomplete="off"> 
                                        <span id="existeUsuarioBdComentario" style="display: none;">Usuario ya existe</span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md">
                                    <div class="mb-3">
                                        <label for="contrasenia" class="form-label">Contraseña:</label>
                                        <input type="password" class="form-control" name="contrasenia" id="contrasenia" placeholder="Introduzca la Contraseña" autocomplete="off"> 
                                    </div>
                                </div>
                                <div class="col-md">
                                    <div class="mb-3">
                                        <label for="confirmar_contrasenia" class="form-label">Repita la Contraseña:</label>
                                        <input type="password" class="form-control" name="confirmar_contrasenia" id="confirmar_contrasenia" placeholder="Confirmar Contraseña"> 
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md">
                                    <div class="mb-3">
                                        <label for="correo" class="form-label">Correo Electronico:</label>
                                        <input type="email" class="form-control" name="correo" id="correo" placeholder="Introduzca el Correo Electronico"> 
                                    </div>
                                </div>
                                <div class="col-md">
                                    <div class="mb-3">
                                        <label for="ciudad_proveedor" class="form-label">Tipo de Usuario:</label>
                                        <div class="row">
                                            <div class="col-10">
                                                {{-- <select class="form-select" aria-label="Default select example" name="tipo_usuario" id="tipo_usuario">
                                                    <option value="0" selected disabled>Seleccione una opcion...</option>
                                                    @foreach ($roles as $rol)
                                                        <option value="{{ $rol->name }}">{{ $rol->name}}</option>    
                                                    @endforeach
                                                </select> --}}
                                                @livewire('formulario.select', ['id_select' => 'tipo_usuario', 'name_select'=>'tipo_usuario'])
                                            </div>
                                            <div class="col-2">
                                                <button type="button" class="btn btn-outline-dark" data-bs-toggle="modal" data-bs-target="#crearRol">
                                                    <i class="fa fa-square-plus" style="font-size: 2vw;"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr><br>
                                <div class="row">
                                    <div class="col-md text-center">
                                        <h5>Seleccione Sucursales a Habilitar</h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-1"></div>
                                    <div class="col-md-10">
                                        <div id="sucursalesHabilitadas0"></div>
                                        <div id="sucursalesHabilitadas">
                                            @foreach ($sucursales as $sucursal)
                                                <div class="form-check">
                                                    <input class="form-check-input soloLectura" type="checkbox" value="{{ $sucursal->id}}" id="flexCheckChecked" name=sucursales_seleccionadas[]>
                                                    <label class="form-check-label" for="flexCheckChecked">
                                                        {{ $sucursal->ciudad}} - {{substr($sucursal->direccion,0,30)}}... 
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="col-md-1"></div>
                                </div>
                                <br><hr><br>
                                <div class="row">
                                    <div class="col-md text-center">
                                        <h5>Seleccione Eventos a Habilitar</h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-1"></div>
                                    <div class="col-md-10">
                                        <div id="eventosHabilitados">
                                            @foreach ($eventos as $evento)
                                                <div class="form-check">
                                                    <input class="form-check-input soloLectura" type="checkbox" value="{{ $evento->id}}" name=eventos_seleccionados[]>
                                                    <label class="form-check-label" for="flexCheckChecked">
                                                        {{ $evento->nombre}} - Fecha: {{ $evento->fecha_evento }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="col-md-1"></div>
                                </div>                               
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger cerrarModal btnCerrarModalNuevoUsuario" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-success" id="btnGuardarActualizar" onclick="guardarActualizarUsuario()">Guardar</button>
                    </div>
                </div>
                </div>
            </div>
        @endcan
        <!-- Fin Modal -->

        @can('crear rol')
              <div class="modal fade" id="crearRol" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Nuevo Rol</h5>
                      <button type="button" class="btn-close btnCerrarModalNuevoRol" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <x-formulario.label for="rol">Nombre Rol:</x-formulario.label>
                        <x-formulario.input id="rol" name="rol" 
                                            tipo="text" placeholder="Introduzca el nombre del rol" 
                                            required>
                        </x-formulario.input>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-danger btnCerrarModalNuevoRol" data-bs-dismiss="modal">Cerrar</button>
                      <button type="button" class="btn btn-success" id="btnModalNuevoRol">Crear Rol</button>
                    </div>
                  </div>
                </div>
              </div>
        @endcan
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

    var myModalEl = document.getElementById('crearRol')
    myModalEl.addEventListener('hidden.bs.modal', function () {
        $('#crearUsuarioModal').modal('show');
    });

    function limpiarInputsCrearNuevoUsuario(){
        $("#nombre_usuario").val('');
        $("#usuario").val('');
        $("#contrasenia").val('');
        $("#confirmar_contrasenia").val('');
        $("#correo").val('');
        $("#tipo_usuario").val(0);
        $('#sucursalesHabilitadas1').remove();
        $('#sucursalesHabilitadas0').append('<div id="sucursalesHabilitadas1"></div>');
    }

    function limpiarInputsCrearNuevoRol(){
        $("#rol").val('');
    }
    
    $('button').on('click',function() 
    {   
        // event.preventDefault();
        if ($(this).attr('id') == 'inputBuscar') 
        {
            $("#buscarformulario").submit();
        }
        else if ($(this).attr('id') == 'modalUsuario') {
            $("#contrasenia").val('');
            $("#confirmar_contrasenia").val('');
            $("#exampleModal").show();
        }else if ($(this).hasClass('btnCerrarModalNuevoUsuario') ) {
           limpiarInputsCrearNuevoUsuario();
        }else if ($(this).hasClass('btnCerrarModalNuevoRol')) {
            limpiarInputsCrearNuevoRol();
        }else if ($(this).attr('id') == 'btnModalNuevoRol') {
            Swal.fire({
                title: "Esta seguro de crear el rol?",
                showDenyButton: true,
                confirmButtonText: "Si",
                denyButtonText: `No`
                }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) 
                {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('crear_rol') }}",
                        data: {"rol":$("#rol").val()},
                        success: function (response) {
                            if (response.estado == 0) {
                                Swal.fire("Rol Creado exitosamente!", "", "success");
                                $("#crearRol").modal('hide');
                                $("#crearUsuarioModal").modal('show');
                                limpiarInputsCrearNuevoRol();
                            } else{
                                Swal.fire(response.mensaje, "", "warning");
                            }
                        }
                    });
                } else if (result.isDenied) {
                    // Swal.fire("Changes are not saved", "", "info");
                }
            });
        }
    });

    function editar(usuario){
        $("#exampleModalLabel").html("<h3>Editar Usuario</h3>");
        $("#nuevo_usuario").attr("action","{{ route('editar_usuario') }}");
        $("#nuevo_usuario").append('<input type="text" name="id" '+ 'value="'+ usuario.id +'"' +'hidden>');
        $("#nombre_usuario").val(usuario.nombre_usuario);
        $("#usuario").val(usuario.usuario);
        $("#contrasenia").val('');
        $("confirmar_contrasenia").val('');
        $("#correo").val(usuario.correo);
        $("#tipo_usuario").val(usuario.tipo_usuario);
        $('#sucursalesHabilitadas').remove();
        let sucursalUsuarioHabilitado = usuario.sucursales_habilitadas.filter(element => {
            return element.id_usuario == usuario.id 
        });
        console.log(sucursalUsuarioHabilitado);
        usuario.sucursales.forEach(sucursal => {
            sucursalUsuarioHabilitado.forEach( element => {
                if (sucursal.id == element.id_sucursal) 
                {
                  $('#sucursalesHabilitadas1').append('<div class="form-check">\
                                                        <input class="form-check-input soloLectura" type="checkbox" value="'+element.id_sucursal+'" id="flexCheckChecked'+element.id_sucursal+'" name=sucursales_seleccionadas[] checked>\
                                                        <label class="form-check-label" for="flexCheckChecked'+element.id_sucursal+'">\
                                                         '+element.ciudad_sucursal+' '+ element.direccion_sucursal+'\
                                                        </label>\
                                                      </div>');      
                } else {
                    
                }
            })
        });
        $("#btnGuardarActualizar").val("Actualizar");
        $("#exampleModal").modal("show");
    }

    
    function habilitarDesabilitar(usuario)
    {
        let mensaje = '';
        if(usuario.estado == 1){
            mensaje = 'Esta seguro de deshabilitar al Usuario?';
        }else{
            mensaje = 'Esta seguro de habilitar al Usuario?';
        }

        Swal.fire({
                title: mensaje,
                showDenyButton: true,
                // showCancelButton: true,
                confirmButtonText: "Si",
                denyButtonText: `No`
                }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) 
                {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('actualizar_estado_usuario') }}",
                        data: {"id":usuario.id, "estado":usuario.estado},
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

    $("#tipo_usuario").on('change',function (){ 
        if ($('#tipo_usuario').val()==1) 
        {
            $('.soloLectura').prop("disabled", true);
            $('.soloLectura').prop('checked', true);
        } else {
            $('.soloLectura').prop('checked', false);
            $('.soloLectura').prop("disabled", false);
        }
    });

    function guardarActualizarUsuario()
    {
        let nombre_usuario = $("#nombre_usuario").val();
        let usuario = $("#usuario").val();
        let contrasenia = $("#contrasenia").val();
        let confirmar_contrasenia = $("#confirmar_contrasenia").val();
        let correo = $("#correo").val();
        let tipo_usuario = $("#tipo_usuario").val();
        let contadorControl = 0;
        var existeUsuarioBd = 0;

        $("input:checkbox").each(function(){
            if ($(this).is(':checked') == true) {
                contadorControl++;
            }
        });

        $.ajax({
            async: false,
            type: "POST",
            url: "/consultar_usuario",
            data: {'usuario':usuario},
            success: function (response) {
                existeUsuarioBd = response; 
            }
        });

        if(existeUsuarioBd == 0)
        {
            $('#usuario').attr('style','border:2px green solid');
            $('#existeUsuarioBdComentario').attr('style','display:block; color:green;');
            $('#existeUsuarioBdComentario').text('Usuario Valido');

            if(contrasenia === confirmar_contrasenia)
            {
                if (contadorControl > 0 && nombre_usuario != '' &&
                    usuario != '' && contrasenia != '' &&  confirmar_contrasenia != '' && 
                    correo != '' && tipo_usuario != 0) 
                {
                    $('#nuevo_usuario').submit();   
                } else {
                    alert("Por favor Rellene los campos y seleccione las Sucursales para asignar al usuario ");
                }
            }else{
                alert("Las contraseñas no son iguales, por favor vuelva a intentar");
            }
        }else{
            $('#usuario').attr('style','border:2px red solid');
            $('#existeUsuarioBdComentario').attr('style','display:block; color:red;');
            $('#existeUsuarioBdComentario').text("El susuario ya existe");
        }   

    }

    $('.cerrarModal').on('click',function(){
        $('#sucursalesHabilitadas1').remove();
        $('#sucursalesHabilitadas0').append('<div id="sucursalesHabilitadas1"></div>');
        
    });

    $(document).ready(function(){
        $("#home").removeClass('active');
        $("#usuarios").addClass('active');
    });    

</script>
@endpush