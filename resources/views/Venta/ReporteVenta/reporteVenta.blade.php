@extends('layouts.plantillabase')

@section('title','Reporte de Ventas')

@section('css')
    <style>
        table > tbody > tr > th > i{
            font-size: 20px;
        }
        .estado{
            font-size: 12px;
        }
        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 34px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked + .slider {
            background-color: #2196F3;
        }

        input:checked + .slider:before {
            transform: translateX(26px);
        }
    </style>
@endsection

@section('h-title')
    
@endsection

@section('card-title')
    <div class="row">
        <div class="col">
            <h4>Reporte de Ventas</h4>
        </div>
        <div class="col text-end">
            @livewire('boton-on-of')
        </div>
    </div>
@endsection

@section('content')
  <div class="row">
    <div class="alert alert-warning" role="alert">
        Por favo seleccione un rango de fechas, indicando la fecha de inicio y la fecha de finalización.
    </div>
  </div>
  <div class="row">
    @livewire('reporte-ventas-component')  
  </div>
@endsection


@push('scripts')
<script src="{{ asset('jquery/jquery-3.7.1.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  $(document).ready(function(){
    $("#home").removeClass('active');
    $("#venta").addClass('active');
    if ($("#id_sucursal").val() > 0) 
    {
       $("#obternerReporteVentasExcel").prop('disabled', false);
    }
  });

  $("#id_sucursal").change(function (e){ 
    if ($("#id_sucursal").val() > 0) 
    {
       $("#obternerReporteVentasExcel").prop('disabled', false);
    }
  });
  
  $("button").on('click',function(e){
    if($(this).attr('id') == 'obternerReporteVentasExcel')
    {
        $('#reporteVentasExcel').submit();
    }
  });
</script>
@endpush

