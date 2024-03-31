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
            <h4>Editar Rol</h4>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <form method="POST" action="{{ route('update_rol') }}" id="update_rol">
                @csrf
                @method('POST')
                @include('roles._form') 
                <br>   
                <input type="submit" class="btn btn-success"></input>
                <a class="btn btn-warning" href="{{ route('home_rol_usuarios'); }}" style="color: black">Volver</a>
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

    function validarNombreRol(nombreRol)
    {
        let nombreValido = false;
        $.ajax({
            type: "POST",
            url: "/consultar_rol",
            data: {'rol':nombreRol},
            success: function (response) {
                if (response == 0) {
                    $("#nombre_rol").attr('style', 'border: 2px green solid');
                }
            }
        });
    }

    $("#nombre_rol").change(function(){
        validarNombreRol($("#nombre_rol").val());
    });

    $(document).ready(function(){        
        $("#rol\\ usuarios").addClass('active');
    });

</script>
@endpush
