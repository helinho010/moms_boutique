@extends('layouts.plantillabase')

@section('title', 'Editar Usuario')

@section('css')
    <link rel="stylesheet" href="{{ asset('DataTables/datatables.min.css') }}">
    <style>
        table>tbody>tr>th>i {
            font-size: 20px;
        }
    </style>
@endsection

@section('h-title')
    @error('mensaje_confirm_pwd')
        <div class="alert alert-danger" role="alert">
            {{ $message }}
        </div>
    @enderror
    @error('sucursales_seleccionadas')
        <div class="alert alert-danger" role="alert">
            {{ $message }}
        </div>
    @enderror
@endsection

@section('card-title')
    <div class="row">
        <div class="col">
            <h4>Editar Usuario</h4>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <form method="POST" action="{{ route('update_usuario') }}" id="update_usuario">
                @csrf
                @method('POST')
                @include('usuario._form')
                <br>
                <button type="button" class="btn btn-success" onclick="guardarActualizar()">Enviar</button>
                <a class="btn btn-warning" href="{{ route('home_usuarios') }}" style="color: black">Volver</a>
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
            if ($("#contrasenia").val() !== '' || $("#confirmar_contrasenia").val() !== '') 
            {
                if ($("#contrasenia").val() === $("#confirmar_contrasenia").val()) 
                {
                    $('#update_usuario').submit();
                } else {
                    alert("Las contrasenias no son iguales");
                }
            }else{
                $('#update_usuario').submit();
            }

            
        }

        $(document).ready(function() {
            $("#usuarios").addClass('active');
        });
    </script>
@endpush
