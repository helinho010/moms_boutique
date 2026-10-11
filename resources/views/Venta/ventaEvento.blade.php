@extends('layouts.plantillabase')

@section('title','Venta en un Evento')

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet"/>
    <style>
        div.row > div.container-fluid > div{
            margin: auto;
        }
        div.row > div.container-fluid > div > table, div.row > div.container-fluid > div > table > thead, div.row > div.container-fluid > div > table > thead > tr {
            width: 90%;
            border: solid 2px black;
        }
        .sinMargen{
            border: 0;
        }
        .sinMargen > td {
            border-top: solid 1px black;
            border-bottom: solid 1px black;
            border-left: solid 2px black;
            border-right: solid 1px black;
        }
        #efectivoRecebido, #descuentoVenta, #totalinput, #cambioinput {
           border: 0;
           width: 100%;
           height: auto;
        }
        #efectivoRecebido:focus , #descuentoVenta:focus , #totalinput:focus, #cambioinput:focus{
           border: 0;
           outline: none;
           width: 100%;
           height: auto;
           font-weight: bold;
        }
        
    </style>
@endsection

@section('h-title')
@endsection

@section('card-title')
    <div class="row">
        <div class="col">
            <h4>Realizando Venta en el Evento: 
                <span class="h5" style="color: #512BFA">
                    @isset($evento)
                        {{ $evento->nombre }}
                    @endisset
                </span>
                <span class="h5" style="color: #512BFA">
                    @isset($evento)
                        {{ $evento->fecha_evento->format('d-m-Y') }}
                    @endisset
                </span>
            </h4>
        </div>
    </div>
@endsection

@section('content')
    @livewire('realizar-venta', ['tipoVenta' => 'evento', 'id_tipo_venta'=> $evento->id])
@endsection


@push('scripts')
    <script src="{{ asset('jquery/jquery-3.7.1.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function(){
            $("#home").removeClass('active');
            $("#venta").addClass('active');
        });
    </script>
@endpush