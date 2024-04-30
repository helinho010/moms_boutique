@extends('layouts.plantillabase')

@section('title','Reporte de Ventas')

@section('css')
    <style>
        table > tbody > tr > th > i{
            font-size: 20px;
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
    </div>
@endsection

@section('content')
  <div class="row">
    <div class="alert alert-warning" role="alert">
        Por favo seleccione un rango de fechas, indicando la fecha de inicio y la fecha de finalización.
    </div>
  </div>
  <div class="row">
    <form action="{{ route('reporte_venta_excel') }}" method="post" id="reporteVentasExcel">
        @csrf
        @method('post')
        <div class="row">
            <div class="col-md-5">
                <div class="mb-3">
                    <label for="fecha_inicial" class="form-label">Seleccione Sucursal</label>
                    <select class="form-select" name="id_sucursal" aria-label="Default select example">
                        <option value="seleccionado" selected disabled>Seleccione una sucursal ...</option>
                        @foreach ($sucursales as $sucursal)
                            <option value="{{ $sucursal->id_sucursal }}">{{$sucursal->ciudad_sucursal}}-{{ substr($sucursal->direccion_sucursal,0,40)."..." }}</option>
                        @endforeach
                      </select>
                  </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label for="fecha_inicial" class="form-label">Fecha Inicio</label>
                    <input type="date" class="form-control" name="fecha_inicial" id="fecha_inicial" value="{{ date("Y-m-d") }}">
                  </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label for="fecha_final" class="form-label">Fecha Final</label>
                    <input type="date" class="form-control" name="fecha_final" id="fecha_final" value="{{ date("Y-m-d") }}">
                  </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4"></div>
            <div class="col-md-4">
                <button type="button" class="btn btn-primary" id="obternerReporteVentasExcel">Obtener el Reporte</button>    
            </div>
            <div class="col-md-4"></div>
        </div>
    </form>
</div>
@endsection


@push('scripts')
<script src="{{ asset('jquery/jquery-3.7.1.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  $(document).ready(function(){
    $("#home").removeClass('active');
    $("#venta").addClass('active');
  });

  $("button").on('click',function(e){
    if($(this).attr('id') == 'obternerReporteVentasExcel')
    {
        $('#reporteVentasExcel').submit();
    }
  });
</script>
@endpush

