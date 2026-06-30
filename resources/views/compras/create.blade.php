@extends('layouts.plantillabase')

@section('title', 'Nueva Compra')

@section('card-title')
    <div class="h5">Nueva Compra</div>
@endsection

@section('content')
    {{-- <form action="{{ route('guardar_compra') }}" method="post">
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
    </form> --}}

    @livewire('compra.detalle-productos', ['productos' => $productos])
    
@endsection

@push('scripts')
    <script src="{{ asset('jquery/jquery-3.7.1.min.js') }}"></script>
    
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
        // $('.select2').select2({
        //     placeholder: "Otra Opcion",
        //     allowClear: true,
        //     width: '50%', // Ajusta el ancho al 100% del contenedor
        //     // theme: 'bootstrap-4' // Cambia el tema a Bootstrap 5
        // });
    });
    </script>
@endpush
