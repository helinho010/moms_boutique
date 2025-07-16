<div>
    <div class="container-fuid mb-4">
        <div class="row mb-4">
            <div class="col-md-12 titulo">
                <h3 class="text-center">Formulario de Compra <div class="h5">(Planificacion)</div>
                </h3>
            </div>
        </div>
        <div class="row cabecera">
            <div class="col-md-4">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12 h5 text-center">
                            Datos de la Compra
                        </div>
                        <div class="col-md-12">
                            <div class="input-group input-group-sm mb-2">
                                <label for="id_usuario">Usuario</label>
                                <select class="form-select-sm select2" name="id_usuario" id="id_usuario">
                                    <option value="">Seleccione un usuario</option>
                                    @foreach ($usuarios as $usuario)
                                        <option value="{{ $usuario->id }}" 
                                            @if ($usuario->id == auth()->user()->id) selected @endif
                                        > {{ $usuario->name}} </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="input-group input-group-sm mb-2">
                                <label for="">Sucursal Destino: </label>
                                <select class="form-select-sm select2" name="id_sucursal" id="id_sucursal">
                                    <option value="">Seleccione una Sucursal...</option>
                                    @foreach ($sucursales as $sucursal)
                                        <option value="{{ $sucursal->id }}">{{ $sucursal->direccion }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-2">
                                <label for="" class="form-labe">Codigo: </label>
                                <input type="text" name="codigo_compra" id="codigo_compra" class="form-control form-control-sm" value="{{ $codigo_compra }}" readonly>
                            </div>    
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12 h5 text-center">
                            Estado de la Compra
                        </div>
                        <div class="col-md-12">
                            <div class="input-group input-group-sm mb-2">
                                <label for="estado_compra">Estado de la Compra</label>
                                <select class="form-select-sm select2" name="estado_compra" id="estado_compra">
                                    <option value="">Seleccione un estado</option>
                                    @foreach ($estadoCompra as $estado)
                                        <option value="{{ $estado }}">{{ $estado }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-2">
                                <label for="" class="form-label">Fecha de Compra: </label>
                                <input type="date" name="fecha_compra" id="fecha_compra" class="form-control form-control-sm" value="{{ date('Y-m-d') }}" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12 h5 text-center">
                            Datos Monetarios
                        </div>
                        <div class="col-md-12">
                            <div class="input-group input-group-sm mb-2">
                                <label for="total_compra">Total Compra</label>
                                <input type="text" name="total_compra" id="total_compra" class="form-control form-control-sm" value="1250" readonly>
                            </div>
                            <div class="input-group input-group-sm mb-2">
                                <label for="observacion">Observacion</label>
                                <textarea name="observacion" id="observacion" class="form-control form-control-sm" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid pruebadecss cuerpo">
        <div class="row">
            Lorem ipsum dolor sit amet consectetur, adipisicing elit. Aut facilis debitis ratione, maiores cum incidunt reprehenderit iste fugit quod sequi veniam perspiciatis, voluptatum neque nobis aliquam, nihil ea? Placeat, unde.
        </div>
    </div>
</div>

@section('css')
    <style>
        .cabecera > div{
            border: 1px solid black;
        }
    </style>
@endsection


@push('scripts')
<script src="{{ asset('jquery/jquery-3.7.1.min.js') }}"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        $('.select2').select2({
            width: '100%',
        });
    });
</script>
@endpush