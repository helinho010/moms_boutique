@extends('layouts.plantillabase')

@section('title', 'Agregar Compra')

@section('card-title')
    <div class="h5">Agregar Nueva Compra</div>
@endsection

@section('content')
    <form action="{{ route('guardar_compra') }}" method="post">
        @csrf
        @method('POST')
        @include('compras.__formulario')
        <div class="container mt-5">
            <div class="row">
                <div class="col-md-12 text-center">
                    <button type="submit" class="btn btn-success">Guardar Compra</button>
                    <a href="{{ route('home_compras') }}" class="btn btn-danger">Cancelar</a>
                </div>
            </div>
        </div>
    </form>
@endsection
