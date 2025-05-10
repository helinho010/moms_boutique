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

@section('mensaje-errores')
    @if (session('errorNuevoRolCreado'))
        <x-formulario.mensaje-error-validacion-inputs color="danger">
            <h5><i class="fas fa-exclamation-triangle"></i>{{ session('errorNuevoRolCreado') }}</h5>
        </x-formulario.mensaje-error-validacion-inputs>
    @endif
    @if (session('nuevoRolCreado'))
        <x-formulario.mensaje-error-validacion-inputs color="success">
            <h5><i class="fas fa-thumbs-up"></i> {{session('nuevoRolCreado')}} </h5>
        </x-formulario.mensaje-error-validacion-inputs>
    @endif
    @if (session('errorEditarRolSuperAdministrador'))
        <x-formulario.mensaje-error-validacion-inputs color="danger">
            <h5><i class="fas fa-exclamation-triangle"></i>{{ session('errorEditarRolSuperAdministrador') }}</h5>
        </x-formulario.mensaje-error-validacion-inputs>
    @endif
    @if (session('rolEditado'))
        <x-formulario.mensaje-error-validacion-inputs color="success">
            <h5><i class="fas fa-thumbs-up"></i> {{session('rolEditado')}} </h5>
        </x-formulario.mensaje-error-validacion-inputs>
    @endif
    @if (session('nuevoPermisoCreado'))
        <x-formulario.mensaje-error-validacion-inputs color="success">
            <h5><i class="fas fa-thumbs-up"></i> {{session('nuevoPermisoCreado')}} </h5>
        </x-formulario.mensaje-error-validacion-inputs>
    @endif
    @if (session('errorNuevoPermisoCreado'))
        <x-formulario.mensaje-error-validacion-inputs color="danger">
            <h5><i class="fas fa-exclamation-triangle"></i>{{ session('errorNuevoPermisoCreado') }}</h5>
        </x-formulario.mensaje-error-validacion-inputs>
    @endif
    @if (session('errorRolEliminado'))
        <x-formulario.mensaje-error-validacion-inputs color="danger">
            <h5><i class="fas fa-exclamation-triangle"></i>{{ session('errorRolEliminado') }}</h5>
        </x-formulario.mensaje-error-validacion-inputs>
    @endif
    @if (session('rolEliminado'))
        <x-formulario.mensaje-error-validacion-inputs color="success">
            <h5><i class="fas fa-thumbs-up"></i> {{session('rolEliminado')}} </h5>
        </x-formulario.mensaje-error-validacion-inputs>
    @endif
    @if (session('errorEliminarRolSuperAdministrador'))
        <x-formulario.mensaje-error-validacion-inputs color="danger">
            <h5><i class="fas fa-exclamation-triangle"></i>{{ session('errorEliminarRolSuperAdministrador') }}</h5>
        </x-formulario.mensaje-error-validacion-inputs>
    @endif
    @if ($errors->any())
        <x-formulario.mensaje-error-validacion-inputs color="danger">
            <h5>
                <div class="container">
                    <div class="row">
                        <div class="col-md-1">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="col-md-6">
                            @foreach ($errors->all() as $error)
                                {{ $error }} <br>
                            @endforeach
                        </div>
                    </div>
                </div>
            </h5>   
        </x-formulario.mensaje-error-validacion-inputs>    
    @endif
    
@endsection

@section('card-title')
    <div class="row">
        <div class="col">
            <h4>Roles</h4>
        </div>
        @can('crear rol')
            <div class="col text-end">
                <button type="button" class="btn btn-success" id="modalRol" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    <i class="fas fa-plus"></i> Agregar Rol 
                </button>
                @can('crear permiso')
                    <button type="button" class="btn btn-success" id="modalRol" data-bs-toggle="modal"
                        data-bs-target="#crearPermisoModal">
                        <i class="fas fa-plus"></i> Agregar Permiso
                    </button>
                @endcan
            </div>
        @endcan
        
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <form action="{{ route('home_rol_usuarios') }}" method="GET" id="buscarformulario">
                <div class="input-group flex-nowrap">
                    <input type="text" name="buscar" id="buscar" class="form-control" placeholder="Buscar..." aria-label="Username" aria-describedby="addon-wrapping">
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
                  <th scope="col">Rol</th>
                  <th scope="col">Permisos</th>
                  <th scope="col">Fecha</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($roles as $rol)
                  <tr class="text-center">
                    <th scope="row">
                        @if (strtolower($rol->name) != "super administrador")
                            @can('editar rol')
                                <a href="{{ route('editar_rol',['id_rol'=>$rol->id]) }}">
                                    <i class="fas fa-edit fa-xl i" style="color:#6BA9FA"></i>
                                </a>
                            @endcan
                            @can('eliminar rol')
                                <form action="{{ route('eliminar_rol') }}" method="post" id="formEliminarRol{{ $rol->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="nombre_rol" id="nombre_rol" value="{{ $rol->name }}">
                                    <button class="btn btn-outline-light" type="button" 
                                            onclick="eliminarRol('formEliminarRol{{$rol->id}}')">
                                        <i class="fas fa-trash-alt fa-xl" style="color:#FA746B"></i>
                                    </button>
                                </form>
                            @endcan
                        @endif
                    </th>
                    <td>{{ $rol->name }}</td>
                    <td>
                        @if( trim(strtolower($rol->name)) == "super administrador")
                            <span class="badge bg-primary">{{ "Todos los permisos" }}</span>
                        @endif
                        @foreach (App\Models\UsertypeOpc::permisosRol($rol->name) as $permiso)
                            {{-- <span class="badge bg-primary">{{ $permiso }}</span> --}}                            
                            @if ( $loop->count > 1)
                                @if ($loop->first)
                                    {{ $permiso }} |
                                @else
                                    @if ($loop->last)
                                        {{ $permiso }}
                                    @else
                                        {{ $permiso }} |
                                    @endif
                                @endif
                            @else   
                                {{ $permiso }}
                            @endif
                        @endforeach
                    </td>
                    <td>{{ $rol->updated_at }}</td>
                  </tr>    
                @endforeach
              </tbody>
        </table>
        {{ $roles->links() }}
    </div>

    <!-- Modal -->
     @can('crear rol')
        <div class="modal fade" id="exampleModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Nuevo Rol</h5>
                <button type="button" class="btn-close cerrarModal" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('nuevo_rol') }}" id="form_nuevo_rol">
                        @csrf
                        @method('POST')
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="nuevo_rol" class="form-label">Nombre del Rol:</label>
                                    <input type="text" class="form-control" name="nuevo_rol" id="nuevo_rol" placeholder="Introduzca el nuevo Rol">
                                    <span id="mensaje_rol_span" style="display: none; color:red">*Error de Rol </span> 
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <br>
                            <div class="row">
                                <div class="col-md text-center">
                                    <h5>Seleccione los permisos para el rol</h5>
                                    <hr>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                @foreach ($permisos as $permiso)
                                    @if ($loop->iteration % 21 == 0)
                                        </div>
                                        <div class="col-md-4">
                                    @else
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" 
                                                value="{{ $permiso->name }}" name="permisos_rol[]" id="permisoRol{{$permiso->id}}">
                                            <label class="form-check-label" for="permisoRol{{$permiso->id}}">
                                                {{ $permiso->name }}
                                            </label>
                                        </div> 
                                    @endif
                                @endforeach
                                </div>
                            </div>                              
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger cerrarModal" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-success" id="btnGuardarActualizar" onclick="guardarActualizarUsuario()">Guardar</button>
                </div>
            </div>
            </div>
        </div>
     @endcan 
     
     @can('crear permiso')
         <x-modal id="crearPermisoModal" title="Crear Permiso" class="btnCerrarModalCrearPermiso"
                  idformulario="frmCrearPermisoModal" nombreBtn="Guardar">
            <form action="{{ route('crear_permiso') }}" method="post" id="frmCrearPermisoModal">
                @method('post')
                @csrf
                <x-formulario.label for="nombre-permiso">Nombre Permiso:</x-formulario.label>
                <x-formulario.input tipo="text" name="nombre_permiso" 
                                    id="nombre_permiso" placeholder="Nombre del Permiso" 
                                    value="" >
                ></x-formulario.input>
            </form>
         </x-modal>
     @endcan
    <!-- Fin Modal -->

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

    function editar(usuario){
        // console.log(usuario);
        // console.log(usuario.sucursales_habilitadas);
        $("#exampleModalLabel").html("<h3>Editar Usuario</h3>");
        $("#nuevo_rol").attr("action","{{ route('editar_usuario') }}");
        $("#nuevo_rol").append('<input type="text" name="id" '+ 'value="'+ usuario.id +'"' +'hidden>');
        $("#nuevo_rol").val(usuario.nuevo_rol);
        $("#usuario").val(usuario.usuario);
        $("#contrasenia").val();
        $("confirmar_contrasenia").val();
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
        // usuario.sucursales_habilitadas.forEach(element => {
        //     if (element.id_usuario == usuario.id) 
        //     {
        //         $('#sucursalesHabilitadas1').append('<div class="form-check">\
        //                                             <input class="form-check-input soloLectura" type="checkbox" value="'+element.id_sucursal+'" id="flexCheckChecked'+element.id_sucursal+'" name=sucursales_seleccionadas[] checked>\
        //                                             <label class="form-check-label" for="flexCheckChecked'+element.id_sucursal+'">\
        //                                                 '+element.ciudad_sucursal+' '+ element.direccion_sucursal+'\
        //                                             </label>\
        //                                         </div>');
        //     } else {
        //         $('#sucursalesHabilitadas1').append('<div class="form-check">\
        //                                             <input class="form-check-input soloLectura" type="checkbox" value="'+element.id_sucursal+'" id="flexCheckChecked'+element.id_sucursal+'" name=sucursales_seleccionadas[] checked>\
        //                                             <label class="form-check-label" for="flexCheckChecked'+element.id_sucursal+'">\
        //                                                 '+element.ciudad_sucursal+' '+ element.direccion_sucursal+'\
        //                                             </label>\
        //                                         </div>');
        //     }
            
        // });
        $("#btnGuardarActualizar").val("Actualizar");
        $("#exampleModal").modal("show");
    }

    function eliminarRol(nombreformulario)
    {
        console.log(nombreformulario);
        Swal.fire({
                title: "Estas seguro de eliminar el Rol?",
                showDenyButton: true,
                // showCancelButton: true,
                confirmButtonText: "Si",
                denyButtonText: `No`
                }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) 
                {
                    $("#"+nombreformulario).submit();
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
        let nuevo_rol = $("#nuevo_rol").val();
        let contadorControl = 0;
        var existeRolBd = 0;

        $("input:checkbox").each(function(){
            if ($(this).is(':checked') == true) {
                contadorControl++;
            }
        });

        if (nuevo_rol != '') 
        {
            $.ajax({
                async: false,
                type: "POST",
                url: "/consultar_rol",
                data: {'rol':nuevo_rol},
                success: function (response) {
                    existeRolBd = response; 
                }
            });
            if(existeRolBd == 0)
            {
                $('#nuevo_rol').attr('style','border:2px green solid');
                $('#mensaje_rol_span').attr('style','display:block; color:green;');
                $('#mensaje_rol_span').text('Rol Correcto');

                if(contadorControl > 0)
                {
                    console.log("Si esta correcto");
                    $('#form_nuevo_rol').submit();
                }else{
                    alert("Seleccione opciones del sistema");
                }
            }else{
                $('#nuevo_rol').attr('style','border:2px red solid');
                $('#mensaje_rol_span').attr('style','display:block; color:red;');
                $('#mensaje_rol_span').text("El Rol ya existe");
            } 

        } else {
            $('#nuevo_rol').attr('style','border:2px red solid');
            $('#mensaje_rol_span').attr('style','display:block; color:red;');
            $('#mensaje_rol_span').text('Campo de Rol vacio');
        }
    }

    $('.cerrarModal').on('click',function(){
        $('#nuevo_rol').val('');
        $('#nuevo_rol').removeAttr('style');
        $('#mensaje_rol_span').attr('style','display:none;');
        $("input:checkbox").each(function(){
            if ($(this).is(':checked') == true) {
                $(this).prop('checked', false);
            }
        });
    });

    $(document).ready(function(){
        $("#rol\\ usuarios").addClass('active');
    });
</script>
@endpush