@extends('layouts.plantillabase')

@section('title','Crear Rol')

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
                echo '<div class="alert alert-success" role="alert">El Rol se registro correctamente</div>';
            }else{
                echo '<div class="alert alert-danger" role="alert">Error al registrar al Rol</div>';
            }
        }

        if (isset($_GET['actualizado'])) 
        {
            if ($_GET['actualizado'] == 1) {
                echo '<div class="alert alert-success" role="alert">El Rol fue actualizado correctamente</div>';
            }else{
                echo '<div class="alert alert-danger" role="alert">Error al actualizar el Rol</div>';
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
            <h4>Crear Nuevo Rol</h4>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <form method="POST" action="{{ route('nuevo_rol') }}" id="nuevo_rol">
                @csrf
                @method('POST')
                @include('roles._form')    
            </form>
        </div>
        <div class="col-md-3"></div>
    </div>
    <br>
    <div class="row"></div>
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

    function editar(usuario){
        // console.log(usuario);
        // console.log(usuario.sucursales_habilitadas);
        $("#exampleModalLabel").html("<h3>Editar Usuario</h3>");
        $("#nuevo_usuario").attr("action","{{ route('editar_usuario') }}");
        $("#nuevo_usuario").append('<input type="text" name="id" '+ 'value="'+ usuario.id +'"' +'hidden>');
        $("#nombre_usuario").val(usuario.nombre_usuario);
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

    $("#btnGuardarActualizar").on('click',function(){
       $("#nuevo_usuario").submit();
    });

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
                showCancelButton: true,
                confirmButtonText: "Si",
                denyButtonText: `No`
                }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) 
                {
                    $.ajax({
                        type: "POST",
                        url: '/actualizar_estado_usuario',
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

    function agregarRol()
    {
        alert("Nicola Tesla");
    }



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
            $('#existeUsuarioBdComentario').text('Usuario Correcto');

            if(contrasenia === confirmar_contrasenia)
            {
                if (contadorControl > 0 && nombre_usuario != '' &&
                    usuario != '' && contrasenia != '' &&  confirmar_contrasenia != '' && 
                    correo != '' && tipo_usuario != 0) 
                {
                    console.log(nombre_usuario +
                                usuario +
                                contrasenia +
                                confirmar_contrasenia +
                                correo +
                                tipo_usuario +
                                contadorControl);
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
    

</script>
@endpush
