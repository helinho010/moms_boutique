@extends('layouts.plantillabase')

@section('title','Inventario Externo')

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
            <h4>Seleccione una Sucursal para realizar la venta</h4>
        </div>
    </div>
@endsection

@section('content')

  @php
   $contador = 0;   
  @endphp
  <div class="row">
  @foreach ($sucursales as $item)
      <div class="col-md-4">
        <div class="card border-primary mb-3" style="max-width: 18rem;">
          <div class="card-header">Sucursal</div>
          <div class="card-body text-primary">
            <h5 class="card-title">{{ $item->razon_social_sucursal }}</h5>
            <p class="card-text"><span style="color:black; font-weight: bold">Direccion:</span> <span style="color:black">{{ $item->direccion_sucursal }}</span></p>
            <p class="card-text"><span style="color:black; font-weight: bold">Ciudad:</span> {{ $item->ciudad_sucursal }}</p>
            <form action="{{ route('seleccion_sucursal_venta') }}" method="POST">
              @csrf
              @method('POST')
              <div class="mb-3">
                <input type="text" class="form-control" name="id_sucursal" id="id_sucursal" value="{{ $item->id_sucursal }}" hidden>
              </div>
              <div class="mb-3">
                <input type="text" class="form-control" id="exampleInputPassword1" hidden>
              </div>
              <button type="submit" class="btn btn-primary">Realizar Venta aqui</button>
            </form>
          </div>
        </div>
      </div>
  @endforeach
</div>
@endsection


@push('scripts')
<script src="{{ asset('jquery/jquery-3.7.1.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

