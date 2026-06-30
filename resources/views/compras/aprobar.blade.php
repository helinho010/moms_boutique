@extends('layouts.plantillabase')

@section('title', 'Aprobar Compra')

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

@section('card-title')
    <div class="container-fluid">
        <div class="row">
            <div class="col-3">
                <h4><strong>Aprobar Compra </strong></h4>
            </div>
            
            <div class="col text-end">
                <div class="d-inline">
                    @can('aprobar compra')
                        <form action="{{ route('aprobar_compra_post') }}" method="post" class="d-inline">
                            @csrf
                            @method('POST')
                            <input type="number" name="id_compra" hidden value="{{ $compra[0]->id }}" readonly>
                            <button type="button" class="btn btn-success" id="btn-aprobar-compra">
                                <i class="fas fa-check"></i> Aprobar Compra
                            </button>
                        </form>
                    @endcan
                </div>
                <!--div class="d-inline">
                    @can('rechazar compra')
                        <form action="{{ route('rechazar_compra', $compra[0]->id) }}" method="post" class="d-inline">
                            @csrf
                            @method('POST')
                            <button type="button" class="btn btn-danger" id="btn-rechazar-compra">
                                <i class="fas fa-times"></i> Rechazar Compra
                            </button>
                        </form>
                    @endcan
                </div-->
                <div class="d-inline">
                    <a href="{{ route('home_compras') }}" class="btn btn-warning text-dark">
                        <i class="fas fa-arrow-left"></i> Volver a Compras Pendientes
                    </a>
                </div>
            </div>
            
        </div>
    </div>
@endsection

@section('content')
    <div class="row cabecera">
                <div class="col-md-6">
                    <div class="container-fluid">
                        <div class="row mb-4 mt-4">

                            <div class="mb-3 row">
                                <label class="col-sm-3 col-form-label">Usuario:</label>
                                <div class="col-sm-6">
                                    {{ $compra[0]->nombre_usuario_creador }}
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label class="col-sm-3 col-form-label">Sucursal:</label>
                                <div class="col-sm-6">
                                    {{ $compra[0]->razon_social }} - {{ $compra[0]->direccion }}
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label class="col-sm-3 col-form-label">Código:</label>
                                <div class="col-sm-6">
                                    {{ $compra[0]->codigo_compra }}
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label class="col-sm-3 col-form-label">Fecha Compra:</label>
                                <div class="col-sm-6">
                                    {{ $compra[0]->fecha_compra->format('d/m/Y') }}
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            
            <hr>

            <div class="row cabecera">
                <div class="col-md-12">

                    <table class="table mt-3 text-center">
                        <thead class="thead-dark">
                            <tr class="text-uppercase">
                                <th>#</th>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>P. Unitario</th>
                                <th>Sub Total</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($detalleCompra as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item['descripcion'] }}</td>
                                    <td>{{ $item['cantidad'] }}</td>
                                    <td>{{ $item['precio_unitario'] }}</td>
                                    <td>{{ $item['sub_total'] }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="3"></td>
                                <td><b>Total</b></td>
                                <td><b>{{ $totalCompra }}</b></td>
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>
@endsection


@push('scripts')
    <script src="{{ asset('jquery/jquery-3.7.1.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            $("button").on('click', function() {
                if ($(this).attr('id') === 'btn-aprobar-compra') {
                    Swal.fire({
                        title: '¿Estás seguro de que deseas aprobar esta compra?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sí, aprobar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $(this).closest('form').submit();
                        }
                    });
                } else if ($(this).attr('id') === 'btn-rechazar-compra') {
                    Swal.fire({
                        title: '¿Estás seguro de que deseas rechazar esta compra?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sí, rechazar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $(this).closest('form').submit();
                        }
                    });
                }
            });
        });
    </script>

@endpush


