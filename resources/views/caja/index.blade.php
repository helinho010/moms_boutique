@extends('layouts.plantillabase')

@section('title', "Cierre Caja")

@section('mensaje-errores')
  @if (session("error"))
    <x-formulario.mensaje-error-validacion-inputs color="danger">
        <h5>Ya existe un cierre con esta fecha </h5>
    </x-formulario.mensaje-error-validacion-inputs>      
  @endif

  @if ($errors->any())
    <x-formulario.mensaje-error-validacion-inputs color="warning">
        <h5>Error al enviar datos al Sistema</h5>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </x-formulario.mensaje-error-validacion-inputs>
  @endif
@endsection

@section('card-title')
<div class="row">
    <div class="col">
        <h4><strong>Cierre de Caja</strong></h4>
    </div>
    <div class="col text-end">
        <button type="button" class="btn btn-success" id="btn-nuevoCierreCaja" data-bs-toggle="modal" data-bs-target="#nuevoCierreCaja">
            <i class="fas fa-plus"></i> Cierre de Caja
        </button>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-3"></div>
    <div class="col-md-6">
        <form action="{{ route('buscar_evento') }}" method="POST" id="buscarformulario">
            @method('POST')
            @csrf
            <div class="input-group flex-nowrap">
                <input type="text" name="buscar" id="buscar" class="form-control" placeholder="Buscar..." aria-label="Username" aria-describedby="addon-wrapping">
                <button class="input-group-text" id="inputBuscar"><i class="fas fa-search"></i></button>
            </div>
        </form>
    </div>
    <div class="col-md-3"></div>
</div>
<br>
<div class="row">
    <table class="table table-striped"> 
        <thead>
            <tr>
              <th scope="col">Opciones</th>
              <th scope="col">Fecha Cierre Caja</th>
              <th scope="col">Efectivo</th>
              <th scope="col">Transaccion</th>
              <th scope="col">Qr</th>
              <th scope="col">Total</th>
              <th scope="col">Observacion</th>
              <th scope="col">Usuario</th>
              <th scope="col">Estado</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($cierres_caja as $cierre)
              <tr>
                <th scope="row">
                  <i class="fas fa-edit fa-xl i" style="color:#6BA9FA" onclick='editar(@php echo json_encode(["id"=>$cierre->id,"nombre"=>$cierre->nombre,"fecha_evento"=>$cierre->fecha_evento]); @endphp)'></i>
                </th>
                <td>{{ $cierre->fecha_cierre_caja }}</td>
                <td>{{ $cierre->efectivo_caja }}</td>
                <td>{{ $cierre->transferencia_caja }}</td>
                <td>{{ $cierre->qr_caja }}</td>
                <td>{{ $cierre->efectivo_caja + $cierre->transferencia_caja + $cierre->qr_caja }}</td>
                <td>{{ $cierre->observacion_caja }}</td>
                <td>{{ $cierre->nombre_usuario }}</td>
                <td> 
                    @if ( $cierre->estado == 1 )
                        <span class="badge bg-success">Activo</span>    
                    @else
                        <span class="badge bg-warning">Inactivo</span>    
                    @endif
                </td>
              </tr>    
            @endforeach
          </tbody>
    </table>
    {{ $cierres_caja->links() }}
    @php
        $fechaActual = date('Y-m-d');    
    @endphp

    <x-modal id="nuevoCierreCaja" title="Nuevo Cierre de Caja" idformulario="frm-cierre-caja" nombre-btn="Guardar">
        <form action="{{route('add_cierre_caja')}}" method="post" class="row" id="frm-cierre-caja">
            @csrf
            @method("post")
            <div class="col-auto">
                <x-formulario.label for="fecha">Fecha/Hora:</x-formulario.label>
                <x-formulario.input tipo="text" disabled="true" :value="$fechaActual" name="fecha" id="fecha" placeholder="" />
            </div>
            <br>
            <div class="col-auto">
                <x-formulario.label for="efectivo">Efectivo Bs.:</x-formulario.label>
                <x-formulario.input tipo="text" name="efectivo" id="efectivo" placeholder="Introduzca el efectivo"/>
            </div>
            
            <div class="col-auto">
                <x-formulario.label for="transferencia">Transferencia Bs.:</x-formulario.label>
                <x-formulario.input tipo="text" name="transferencia" id="transferencia" placeholder="Introduzca el efectivo"/>
            </div>
            
            <div class="col-auto">
                <x-formulario.label for="qr">QR Bs.:</x-formulario.label>
                <x-formulario.input tipo="text" name="qr" id="qr" placeholder="Introduzca el efectivo"/>
            </div>
            
            <br>
            <x-formulario.label for="observacion">Observacion: </x-formulario.label>
            <x-formulario.textarea name="observacion" id="observacion" placeholder="Tiene alguna observacion?"></x-formulario.textarea>
        </form>

    </x-modal>
@endsection


@push('scripts')
    <script src="{{ asset('jquery/jquery-3.7.1.min.js') }}"></script>
    <script>
        $(document).ready(function(){
            $("#caja").addClass('active');
        });

        $("#")
    </script>
@endpush