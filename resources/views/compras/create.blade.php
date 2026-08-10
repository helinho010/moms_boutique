@extends('layouts.plantillabase')

@section('title', 'Nueva Compra')

@section('card-title')
    <div class="h5">Nueva Compra</div>
@endsection

@section('mensaje-errores')
    @if (session('mensaje-errores'))
        <div class="alert alert-danger">
            {{ session('mensaje-errores') }}
        </div>
    @endif
    @if (session('mensaje-exito'))
        <div class="alert alert-success">
            {{ session('mensaje-exito') }}
        </div>
    @endif
@endsection

@section('content')

    @livewire('compra.detalle-productos', ['productos' => $productos])
    
@endsection

@push('scripts')
    <script src="{{ asset('jquery/jquery-3.7.1.min.js') }}"></script>
    
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
@endpush
