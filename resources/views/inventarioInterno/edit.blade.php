@extends('layouts.plantillabase')

@section('title', "Editar Item Inventario Interno")

@section('mensaje-errores')
    
@endsection

@section('card-title')
    <div class="col">
        <h4><strong>Editar Item del inventario interno</strong></h4>
    </div>
@endsection

@section('content')
    <div class="card-body">
        <form action="{{ route('actualizar_inventario_interno') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="container">
                <div class="row">
                    <div class="col-md-8">
                        @include('inventarioInterno._form')
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection