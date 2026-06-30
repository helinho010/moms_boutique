<div>
    <div class="container-fuid mb-4">
        <div class="row mb-4">
            <div class="col-md-12 titulo">
                <h3 class="text-center">Formulario de Compra <div class="h5">(Planificacion)</div>
                </h3>
            </div>
        </div>
        <div class="row cabecera">
            <div class="col-md-6">
                <div class="container-fluid">
                    <div class="row mb-4 mt-4">
                        <div class="mb-3 row">
                            <label for="staticEmail" class="col-sm-3 col-form-label">Usuario: </label>
                            <div class="col-sm-6">
                                <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="{{ auth()->user()->name }}">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="sucursal_destino" class="col-sm-3 col-form-label">Sucursal Destino: </label>
                            <div class="col-sm-6">
                                <select name="sucursal_destino" id="sucursal_destino">
                                    <option value="-1">Seleccione una opcion...</option>
                                    @foreach ($sucursales as $sucursal)
                                        <option value="{{ $sucursal->id }}">{{ $sucursal->direccion }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="staticEmail" class="col-sm-3 col-form-label">Codigo Compra: </label>
                            <div class="col-sm-6">
                                <input type="text" readonly name="codigo_compra" class="form-control-plaintext" value="{{ $codigo_compra }}">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="staticEmail" class="col-sm-3 col-form-label">Fecha Compra: </label>
                            <div class="col-sm-6">
                                <input type="date" class="form-control-plaintext" name="fecha_compra" value="{{ $fecha_compra }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- <div class="col-md-4">
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
            </div> --}}
            {{-- <div class="col-md-4">
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
            </div> --}}
        </div>
        @livewire('compra.detalle-productos', ['productos' => $productos])
    </div>
</div>

@section('css')
    <style>
        .cabecera > div, .cuerpo > div {
            border: 0px solid black;
        }

        hr {
            border: 1px dashed rgb(100, 16, 69);
            margin-bottom: 60px;
        }
        
        label {
            font-weight: bold;
            size: 14px; 
        }
    </style>
@endsection


@push('scripts')
<script src="{{ asset('jquery/jquery-3.7.1.min.js') }}"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    // $(document).ready(function() {
    //     $('.select2').select2({
    //         width: '60%',
    //     });
    // });

    document.addEventListener('livewire:load', function () {
        $('#id_producto_seleccionado').select2();

        $('#id_producto_seleccionado').on('change', function () {
            let value = $(this).val();
            Livewire.find(componentId).set('idProductoSeleccionado', value);
        });
    });
</script>
@endpush