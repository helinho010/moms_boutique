@extends('layouts.plantillabase')

@section('title', 'Compras')

@section('mensaje-errores')
    @if (session('mensaje-errores'))
        <div class="alert alert-danger">
            {{ session('mensaje-errores') }}
        </div>
    @endif
@endsection

@section('card-title')
    <div class="container">
        <div class="row">
            <div class="col">
                <h4><strong>Compra de Items</strong> <span class="h6">(Planificacion de Compras)</span></h4>
            </div>
            @can('crear compras')
                <div class="col text-end">
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#nuevaCompraModal">
                        <i class="fas fa-plus"></i> Agregar Compra
                    </button>
                </div>
            @endcan
        </div>
    </div>
@endsection

@section('content')
    <div class="container-fluid mb-5">
        <div class="row">
            <div class="col-md-7">
                <div class="row">
                    <div class="col-md-2">
                        <label for="idSucursalSelectPrincipal" class="col-form-label">Sucursal:</label>
                    </div>
                    <div class="col-md-10">
                        <form action="{{ route('home_caja') }}" id="formularioCaja">
                            <div class="input-group">
                                <select class="form-select" aria-describedby="" name="id_sucursal" id="idSucursalSelectPrincipal">
                                    <option value="seleccionado" 
                                        @if ( !isset($id_sucursal) ) selected @endif 
                                        disabled>Seleccione una opcion...
                                    </option>

                                    @can('todas las sucursales')
                                        <option value="999" 
                                            @if (isset($id_sucursal) && $id_sucursal==999 ) 
                                                selected 
                                            @endif>
                                            Todas las Sucursales
                                        </option>
                                    @endcan

                                    @foreach ($sucursales as $sucursal)
                                        <option value="{{ $sucursal->id }}" 
                                            @if (isset($id_sucursal) && $sucursal->id == $id_sucursal )
                                                selected
                                            @endif>
                                            {{ "$sucursal->ciudad - ".substr($sucursal->direccion,0,40)."..." }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="submit" class="input-group-text" id="btnFormDataInventario">
                                    <i class="fas fa-search"></i>
                                </button>
                        </form>
            
                        @can('exportar pdf')
                            <button type="button" class="btn btn-danger" id="id-export-pdf-cierre-caja" data-bs-toggle="modal"
                                data-bs-target="#modalExportarPdfCierreCaja">
                                <i class="far fa-file-pdf" style="font-size: 20px;"></i>
                            </button>
                        @endcan
            
                        @can('exportar excel')
                            <button type="button" class="btn btn-success" id="id-export-excel-cierre-caja" data-bs-toggle="modal"
                                data-bs-target="#modalExportarExcelCierreCaja">
                                <i class="far fa-file-excel" style="font-size: 22px;"></i>
                            </button>
                        @endif
                        </div> <!--Hay que tener cuidado con este cierre de div-->
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <form action="{{ route('home_caja') }}" method="GET" id="buscarCierresCajaFormulario">
                    <div class="input-group flex-nowrap">
                        <input type="text" name="buscar" id="buscar" class="form-control" placeholder="Buscar..." aria-label="buscar" aria-describedby="addon-wrapping">
                        <input type="number" value="{{ $id_sucursal ? $id_sucursal: 0  }}" name="id_sucursal" id="id_sucursal_buscar" hidden>
                        <button type="submit" class="input-group-text" id="btnBuscarFormularioCierreCaja">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="container-fluid mb-5">
        <div class="row">
            <div class="col">
                <table class="table table-bordered" style="font-size:12px;">
                    <thead>
                        <tr class="align-middle" style="font-size:1.2em; font-weight: bold;"> 
                            <th scope="col" style="width: 10%">Opciones</th>
                            <th scope="col">Codigo</th>
                            <th scope="col">Destino Compra</th>
                            <th scope="col">Total Compra Bs.</th>
                            <th scope="col">Presupuesto Bs.</th>
                            <th scope="col">Sobrante Bs.</th>
                            <th scope="col">Observacion</th>
                            <th scope="col">Usuario</th>
                            <th scope="col">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">
                                @can('editar compras')
                                    <a href="#" class="btn btn-info btn-sm" title="Editar Compra">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                @endcan
                                @can('eliminar compras')
                                    <a href="#" class="btn btn-danger btn-sm" title="Eliminar Compra">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                @endcan
                            </th>
                            <td>cmp-20250001</td>
                            <td>La Paz-Calle Diaz Romero, esquina montes y pando</td>
                            <td>1507.53</td>
                            <td>1500</td>
                            <td>7.53</td>
                            <td>Compra generada aleratoriamente, en el sistema de compras con criterio</td>
                            <td>hmejia</td>
                            <td>
                                <span class="badge rounded-pill text-bg-success">Aprobado 05/07/2025</span>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                @can('editar compras')
                                    <a href="#" class="btn btn-info btn-sm" title="Editar Compra">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                @endcan
                                @can('eliminar compras')
                                    <a href="#" class="btn btn-danger btn-sm" title="Eliminar Compra">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                @endcan
                            </th>
                            <td>cmp-2025070002</td> 
                            <td>La Paz-Calle Diaz Romero, esquina montes y pando</td>
                            <td>1507.53</td>
                            <td>1500</td>
                            <td>7.53</td>
                            <td>Compra generada aleratoriamente, en el sistema de compras con criterio</td>
                            <td>emejia</td>
                            <td >
                                <span class="badge rounded-pill text-bg-primary">Revision 10/07/2025</span>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                @can('editar compras')
                                    <a href="#" class="btn btn-info btn-sm mb-2" title="Editar Compra">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                @endcan
                                @can('revisar compras')
                                    <a href="#" class="btn btn-warning btn-sm mb-2" title="Revisar Compra">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                @endcan
                                @can('aprobar compras')
                                    <a href="#" class="btn btn-success btn-sm mb-2" title="Aprobar Compra">
                                        <i class="fas fa-check"></i>
                                    </a>
                                @endcan
                                @can('eliminar compras')
                                    <a href="#" class="btn btn-danger btn-sm mb-2" title="Eliminar Compra">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                @endcan
                            </th>
                            <td>cmp-20250001</td>
                            <td>La Paz-Calle Diaz Romero, esquina montes y pando</td>
                            <td>1507.53</td>
                            <td>1500</td>
                            <td>7.53</td>
                            <td>Compra generada aleratoriamente, en el sistema de compras con criterio</td>
                            <td>allanos</td>
                            <td >
                                <span class="badge rounded-pill text-bg-info">creado 05/07/2025</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('jquery/jquery-3.7.1.min.js') }}"></script>
    
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Otra Opcion",
            allowClear: true,
            width: '50%', // Ajusta el ancho al 100% del contenedor
            // theme: 'bootstrap-4' // Cambia el tema a Bootstrap 5
        });
    });
    </script>
@endpush
