@extends('layouts.plantillabase')

@section("title", "Edicion Compra")

@section("card-title","Edicion de Compra")

@section("content")
    @livewire("compra.detalle-productos", ['productos' => $productos, 'compra' => $compra, 'detalleCompra' => $detalleCompra])
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