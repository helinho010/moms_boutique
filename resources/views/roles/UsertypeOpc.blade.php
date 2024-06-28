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
   
@endsection

@section('card-title')
    <div class="row">
        <div class="col">
            <h4>Roles</h4>
        </div>
        <div class="col text-end">
            <button type="button" class="btn btn-success" id="modalRol" data-bs-toggle="modal" data-bs-target="#exampleModal">
                <i class="fas fa-plus"></i> Agregar Rol 
            </button>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <form action="{{ route('buscar_roles') }}" method="POST" id="buscarformulario">
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
                <tr class="text-center">
                  <th scope="col">Opciones</th>
                  <th scope="col">Rol</th>
                  <th scope="col">Opciones Habilitadas</th>
                  <th scope="col">Fecha de Creacion/Modificacion</th>
                  <th scope="col">Estado</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($roles as $rol)
                  <tr class="text-center">
                    <th scope="row">
                        @if ($rol->id != 1)
                            <a href="{{ route('editar_rol',['id'=>$rol->id]) }}"><i class="fas fa-edit fa-xl i" style="color:#6BA9FA"></i></a>
                            @php
                                $dataRol = json_encode(["id" => $rol->id, "nombre_rol" => $rol->type, "estado" => $rol->estado, ]);

                                if ($rol->estado == 1) 
                                {
                                    echo  '<i class="fas fa-trash-alt fa-xl" style="color:#FA746B" onclick=\'habilitarDesabilitar('.$dataRol.')\'></i>'; 
                                }else{
                                    echo '<i class="fas fa-check-circle fa-xl" style="color:#FAAE43" onclick=\'habilitarDesabilitar('.$dataRol.')\'></i>';
                                }
                            @endphp
                        @endif
                    </th>
                    <td>{{ $rol->type }}</td>
                    <td>
                        @foreach ($opciones_habilitadas as $opcion)
                            @if ($opcion->id_usertypes == $rol->id)
                             <div class="row">
                                <div class="col-md-3"></div>
                                <div class="col-md-2 text-end">  <i class="{{ $opcion->icono_opciones_sistemas}}" style="color:#6BA9FA"></i> </div>
                                <div class="col-md-7" style="text-align: left">{{ $opcion->opcion_opciones_sistemas }}</div>
                             </div>
                            @endif
                        @endforeach
                    </td>
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
                                    <label for="nuevo_rol" class="form-label">Nuevo Rol:</label>
                                    <input type="text" class="form-control" name="nuevo_rol" id="nuevo_rol" placeholder="Introduzca el nuevo Rol">
                                    <span id="mensaje_rol_span" style="display: none; color:red">*Error de Rol </span> 
                                  </div>
                            </div>
                        </div>

                        <div class="row">
                            <hr><br>
                            <div class="row">
                                <div class="col-md text-center">
                                    <h5>Seleccione las Opciones a Habilitar</h5>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-1"></div>
                                <div class="col-md-10">
                                    <div id="sucursalesHabilitadas0"></div>
                                    <div id="sucursalesHabilitadas">
                                        @foreach ($opciones as $opcion)
                                            <div class="form-check">
                                                <input class="form-check-input soloLectura" type="checkbox" value="{{ $opcion->id}}" id="flexCheckChecked{{$opcion->id}}" name=opciones_seleccionadas[]>
                                                <label class="form-check-label" for="flexCheckChecked{{$opcion->id}}">
                                                    {{ $opcion->opcion}} <i class="{{ $opcion->icono }}" style="color:#6BA9FA"></i>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div id="sucursalesHabilitadas1"></div>
                                </div>
                                <div class="col-md-1"></div>
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

    function habilitarDesabilitar(rol)
    {
        let mensaje = '';
        if(rol.estado == 1){
            mensaje = 'Esta seguro de deshabilitar el Rol?';
        }else{
            mensaje = 'Esta seguro de habilitar el Rol?';
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
                        url: '/actualizar_estado_rol',
                        data: {"id":rol.id, "estado":rol.estado},
                        success: function (response) {
                          if (response.respuesta) {
                            Swal.fire("Cambio Guardado!", "", "success");    
                            location.reload();
                          } else {
                            Swal.fire({
                            icon: "error",
                            title: "hubo un error" ,
                            text: response.mensaje,
                            });  
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