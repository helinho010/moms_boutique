@extends('layouts.plantillabase')

@section('title','Editar Rol')

@section('css')
    <link rel="stylesheet" href="{{ asset('DataTables/datatables.min.css') }}">
    <style>
        table > tbody > tr > th > i{
            font-size: 20px;
        }
    </style>
@endsection

@section('mensaje-errores')
    @if ($errors->any())
        <x-formulario.mensaje-error-validacion-inputs color="danger">
            <h5>
                <div class="container">
                    <div class="row">
                        <div class="col-md-1">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="col-md-4">
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
            <h4>Editar Rol</h4>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <form method="POST" action="{{ route('update_rol') }}" id="update_rol">
                @csrf
                @method('POST')
                @include('roles._form') 
                <br>   
                <button type="button" class="btn btn-success" onclick="guardarActualizar()">Enviar</button>
                <a class="btn btn-warning" href="{{ route('home_rol_usuarios'); }}" style="color: black">Volver</a>
            </form>
        </div>
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

    // function validarNombreRol(nombreRol)
    // {
    //     let nombreValido = false;
    //     $.ajax({
    //         async: false,
    //         type: "POST",
    //         url: "/consultar_rol",
    //         data: {'rol':nombreRol},
    //         success: function (response) {
    //             console.log(response);
    //             if (response == 0) {
    //                 $("#nombre_rol").attr('style', 'border: 2px green solid');
    //                 nombreValido = true;
    //             }
    //         }
    //     });

    //     return nombreValido;
    // }

    function guardarActualizar()
    {
       SweetAlert.fire({
            title: '¿Está seguro de actualizar el Rol?',
            text: "¡No podrá revertir esta acción!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, guardar cambios'
        }).then((result) => {
            if (result.isConfirmed) {
                // let nombreRol = $("#nombre_rol").val();
                // if (validarNombreRol(nombreRol)) {
                    $('#update_rol').submit();
                // }else{
                //     alert('El nombre del rol ya existe');
                // }
            }
        }); 
        
    }

    $(document).ready(function(){        
        $("#rol\\ usuarios").addClass('active');
    });

</script>
@endpush
