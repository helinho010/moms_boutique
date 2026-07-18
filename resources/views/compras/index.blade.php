@extends('layouts.plantillabase')

@section('title', 'Compras')

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
            <div class="col">
                <h4><strong>Compra de Items</strong> <span class="h6">(Planificacion de Compras)</span></h4>
            </div>
            @can('crear compras')
                <div class="col text-end">
                    <a href="{{ route('agregar_compra') }}" class="btn btn-success">
                        <i class="fas fa-plus"></i> Agregar Compra
                    </a>
                </div>
            @endcan
        </div>
    </div>
@endsection

@section('content')
    <div class="container-fluid mb-5">
        <div class="row">
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-2">
                        <label for="idSucursalSelectPrincipal" class="col-form-label">Sucursal:</label>
                    </div>
                    <div class="col-md-10">
                        <form action="{{ route('home_compras') }}" id="formularioCaja">
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

                        @can('exportar excel')
                            <button type="button" class="btn btn-success" id="exportarCompra" data-bs-toggle="modal"
                                        data-bs-target="#modalExportarCompras" class="d-inline">
                                    <i class="far fa-file-excel" style="font-size: 22px;"></i>
                            </button>
                        @endif
                        </div> <!--Hay que tener cuidado con este cierre de div-->
                    </div>
                </div>
            </div>

            <div class="col-md-5" style="margin: auto;">
                <div class="d-inline">
                    <form action="{{ route('home_compras') }}" method="GET" class="d-inline" id="formularioBuscarCompras">
                        <div class="input-group flex-nowrap">
                            <input type="hidden" id="id_sucursal_buscar" name="id_sucursal" value="{{ $id_sucursal ? $id_sucursal : '' }}">
                            <input type="text" name="buscar" 
                                   id="buscar" class="form-control" 
                                   placeholder="Buscar..." 
                                   aria-label="buscar" aria-describedby="addon-wrapping"
                                   value="{{ $buscar ? $buscar : '' }}"
                            >
                            <button type="button" class="input-group-text" id="btnBuscarCompras">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid mb-5">
        <div class="row">
            <div class="col">
                <table class="table table-bordered" style="font-size:14px;">
                    <thead>
                        <tr class="align-middle" style="font-size:1.2em; font-weight: bold;"> 
                            <th scope="col" style="width: 10%">Opciones</th>
                            <th scope="col">Codigo</th>
                            <th scope="col">Destino Compra</th>
                            <th scope="col">Total IVA Bs.</th>
                            <th scope="col">Total Compra Bs.</th>
                            {{-- <th scope="col">Sobrante Bs.</th> --}}
                            <th scope="col">Fecha Compra</th>
                            <th scope="col">Observacion</th>
                            <th scope="col">Usuario</th>
                            <th scope="col">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($compras as $compra)
                            <tr>
                                <th scope="row">
                                    @if ($compra->estado_aprobacion == 0)
                                        @can('editar compras')
                                            <a href="{{ route("editar_compra", ["id" => $compra->id ]) }}" class="btn btn-info btn-sm" title="Editar Compra">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endcan
                                        @can('aprobar compras')
                                            <a href="{{ route('aprobar_compra', ['id' => $compra->id ]) }}" class="btn btn-success btn-sm" title="Aprobar Compra">
                                                <i class="fas fa-check"></i>
                                            </a>
                                        @endcan
                                        @can('eliminar compras')
                                            <form action="{{ route('eliminar_compra', ['id' => $compra->id]) }}" method="post" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-danger btn-sm" title="Eliminar Compra" id="btn-eliminar-compra">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                            </form>
                                        @endcan
                                    @else
                                        @if ($compra->id_usuario_envio_bd == null && $compra->fecha_envio_productos_bd == null)
                                            <form action="{{ route('enviar_compra_bodega') }}" method="post" class="d-inline">
                                                @method("PATCH")
                                                @csrf
                                                <input type="number" name="id_compra" value="{{ $compra->id }}" hidden readonly>
                                                <button type="button" class="btn btn-primary btn-sm" title="Enviar a la bodega" id="btn-enviar-compra-bodega">
                                                    <i class="fas fa-truck"></i>
                                                </button>
                                            </form>
                                            <span class="d-inline">
                                                <button type="button" class="btn btn-warning btn-sm" title="Ver Compra" onclick="verCompra({{ $compra->id }})">
                                                    <!--data-bs-toggle="modal" data-bs-target="#modalVerCompra{{ $compra->id }}"-->
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </span>
                                            @can('exportar pdf')
                                                <form action="{{ route('compra_exportar_pdf', ["id_compra" => $compra->id]) }}" method="post" class="d-inline">
                                                    @csrf
                                                    @method('POST')
                                                    <button type="submit" class="btn btn-danger btn-sm" title="Exportar PDF">
                                                        <i class="far fa-file-pdf" style="font-size: 15px;"></i>
                                                    </button>
                                                </form>
                                            @endcan
                                        @else
                                            <button type="button" class="btn btn-secondary btn-sm d-inline" title="Compra Enviada a Bodega">
                                                <i class="fa fa-lock" aria-hidden="true"></i> 
                                            </button>
                                            @can('exportar pdf')
                                                <form action="{{ route('compra_exportar_pdf', ["id_compra" => $compra->id]) }}" method="post" class="d-inline">
                                                    @csrf
                                                    @method('POST')
                                                    <button type="submit" class="btn btn-danger btn-sm" title="Exportar PDF">
                                                        <i class="far fa-file-pdf" style="font-size: 15px;"></i>
                                                    </button>
                                                </form>
                                            @endcan
                                        @endif 
                                    @endif
                                </th>
                                <td>{{ $compra->codigo_compra }}</td>
                                <td>{{ $compra->direccion }}</td>
                                <td>{{ $compra->total_iva }}</td>
                                <td>{{ $compra->total_compra }}</td>
                                {{-- <td>{{ $compra->total_compra }}</td> --}}
                                <td> {{ $compra->fecha_compra->format('d/m/Y') }} </td>
                                <td>{{ $compra->observaciones }}</td>
                                <td>
                                    <span data-toggle="tooltip" data-placement="top" title="Creado">C:</span> {{ $compra->usrname_creador }} <br>
                                    <span data-toggle="tooltip" data-placement="top" title="Aprobado">A:</span> {{ $compra->usrname_aprobador ?? '---' }} <br>
                                    <span data-toggle="tooltip" data-placement="top" title="Enviado a Almacen">E:</span> {{ $compra->usrname_envio_bd ?? '---' }} <br>
                                </td>
                                <td>
                                        @if ($compra->estado_aprobacion == 0)
                                            <span class="badge rounded-pill text-bg-warning">
                                                En Revision <br> {{ $compra->updated_at->format('d/m/Y') }}
                                            </span>
                                        @elseif ($compra->estado_aprobacion == 1)
                                            <span class="badge rounded-pill text-bg-info">
                                                Aprobado <br> {{ $compra->updated_at->format('d/m/Y') }}
                                            </span>
                                        @elseif ($compra->estado_aprobacion == 2)
                                            <span class="badge rounded-pill text-bg-danger">
                                                Rechazado <br>  {{ $compra->updated_at->format('d/m/Y') }}
                                            </span>
                                        @elseif ($compra->estado_aprobacion == 3) 
                                            <span class="badge rounded-pill text-bg-success">
                                                Enviado BD <br> {{ $compra->updated_at->format('d/m/Y') }}
                                            </span>
                                        @endif
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                        
                        {{-- <tr>
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
                        </tr> --}}
                    </tbody>
                </table>
            </div>
        </div>
        {{ $compras->links() }}
    </div>

    <x-modal id="modalVerCompra" nombreBtn="Aceptar" tamanioModal="modal-lg">
        <x-slot:title>
            Detalles de la Compra
        </x-slot:title>
        <div id="datosCompra"></div>
    </x-modal>

    @can('exportar excel')
        <x-modal id="modalExportarCompras" nombreBtn="Exportar" tamanioModal="modal-md" onclick="confirmarEnviar('frm-exportar-compras')">
            <x-slot:title>
                Exportar Compras a Excel
            </x-slot:title>
            <form action="{{ route('compra_exportar_excel') }}" method="POST" id="frm-exportar-compras">
                @csrf
                <div class="mb-3">
                    <label for="id_sucursal" class="form-label">Sucursal:</label>
                    <select class="form-control" id="id_sucursal" name="id_sucursal" required>
                        <option value="">Seleccione una sucursal</option>
                        @foreach($sucursales as $sucursal)
                            <option value="{{ $sucursal->id }}">{{ $sucursal->ciudad }} - {{ $sucursal->direccion }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="fecha_inicio" class="form-label">Fecha Inicio:</label>
                    <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
                </div>
                <div class="mb-3">
                    <label for="fecha_fin" class="form-label">Fecha Fin:</label>
                    <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" required>
                </div>
            </form>
        </x-modal>
    @endcan
    

@endsection

@push('scripts')
    <script src="{{ asset('jquery/jquery-3.7.1.min.js') }}"></script>
    
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function eliminarFormularioID(id, codigoCompra) {
            Swal.fire({
                title: '¿Estás seguro de elimiar la compra: ' + codigoCompra + ' ?',
                text: "¡No podrás revertir esto!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminarlo!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('formularioEliminacion' + id).submit();
                }
            })
        }

        function verCompra(id_compra){
            $.ajax({
                url: '/compras/detalle_compra/' + id_compra,
                method: 'POST',
                success: function(response) {
                    let compra = JSON.parse(response).compra;
                    let detalleCompra = JSON.parse(response).detalleCompra;
                    let totalCompra = JSON.parse(response).totalCompra;
                    console.log(compra);
                    console.log(detalleCompra);
                    console.log(totalCompra);    
                    $('#datosCompra').html(`
                        <div class="container">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <h5><strong>Usuario Creador:</strong> ${compra[0].nombre_usuario_creador}</h5>
                                    <h5><strong>Usuario Aprobador:</strong> ${compra[0].nombre_usuario_aprobador}</h5>
                                    <h5><strong>Código de Compra:</strong> ${compra[0].codigo_compra}</h5>
                                </div>
                                <div class="col-md-6">
                                    <h5><strong>Destino de Compra:</strong> ${compra[0].direccion}</h5>
                                    <h5><strong>Fecha de Compra:</strong> ${new Date(compra[0].fecha_compra).toLocaleDateString()}</h5>
                                    <h5><strong>Observaciones:</strong> ${compra[0].observaciones.slice(0, 25)}...</h5>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th scope="col">Producto</th>
                                                <th scope="col">Cantidad</th>
                                                <th scope="col">Precio Unitario (Bs.)</th>
                                                <th scope="col">Iva (Bs.)</th>
                                                <th scope="col">Subtotal (Bs.)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            ${detalleCompra.map(item => `
                                                <tr>
                                                    <td>${item.descripcion}</td>
                                                    <td>${item.cantidad}</td>
                                                    <td>${item.precio_unitario.toFixed(2)}</td>
                                                    <td>${(item.iva).toFixed(2)}</td>
                                                    <td>${(item.cantidad * item.precio_unitario).toFixed(2)}</td>
                                                </tr>
                                            `).join('')}
                                                <tr>
                                                    <td colspan="4" class="text-end"><strong>Total Compra:</strong></td>
                                                    <td><strong>Bs. ${totalCompra.toFixed(2)}</strong></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="4" class="text-end"><strong>Total Iva:</strong></td>
                                                    <td><strong>Bs. ${compra[0].total_iva.toFixed(2)}</strong></td>
                                                </tr>
                                        </tbody>
                                    </table>
                                    
                                </div>
                            </div>
                        </div>
                    `);
                    $('#modalVerCompra').modal('show');
                },
                error: function() {
                    console.log('Error al cargar los detalles de la compra.');
                }
            });
        }

        function confirmarEnviar(frmId) {
            Swal.fire({
                title: '¿Estás seguro de exportar las compras a Excel?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, exportar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(frmId).submit();
                }
            });

        }


        $(document).ready(function(){
            $("button").on('click', function(){
                if($(this).attr('id') === 'btn-enviar-compra-bodega'){
                    Swal.fire({
                        title: '¿Estás seguro de enviar esta compra a la sucursal?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sí, enviar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $(this).closest('form').submit();
                        }
                    });
                }

                if($(this).attr('id') === "btn-eliminar-compra")
                {
                    Swal.fire({
                        title: '¿Estás seguro de eliminar esta compra?',
                        text: "¡No podrás revertir esto!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sí, eliminarlo!',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $(this).closest('form').submit();
                        }
                    });
                }

                if($(this).attr('id') === "btnBuscarCompras")
                {
                    let buscarInput = $('#buscar').val();
                    let idSucursalInput = $('#idSucursalSelectPrincipal').val();
                    
                    if (idSucursalInput == "seleccionado" || idSucursalInput == null || idSucursalInput == undefined) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Por favor, seleccione una sucursal antes de buscar.',
                        });
                    }else{
                        $('#formularioBuscarCompras').submit();
                    }
                }
            });

            $("#idSucursalSelectPrincipal").change(function() {
                let selectedSucursal = $(this).val();
                $('#id_sucursal_buscar').val(selectedSucursal);
            });

        });
    </script>
@endpush
