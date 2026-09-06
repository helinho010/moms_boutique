<div>
    <form>

        <div class="container-fluid mb-4">

            <div class="row mb-4">
                <div class="col-md-12 titulo">
                    <h3 class="text-center">
                        Formulario de Compra
                        <small class="d-block h5">(Planificación)</small>
                    </h3>
                </div>
            </div>

            <div class="row cabecera">
                <div class="col-md-6">
                    <div class="container-fluid">
                        <div class="row mb-4 mt-4">

                            <div class="mb-3 row">
                                <label class="col-sm-3 col-form-label">Usuario:</label>
                                <div class="col-sm-6">
                                    {{-- <input type="text" readonly class="form-control-plaintext"
                                        value="{{ auth()->user()->name }}"> --}}
                                        {{ auth()->user()->name }}
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label class="col-sm-3 col-form-label">
                                    @error('sucursalDestinoId') 
                                        <i class="fas fa-exclamation-triangle" style="color: red;"></i>
                                    @enderror   
                                    Sucursal:
                                </label>
                                <div class="col-sm-6" wire:ignore>
                                    <select class="form-select" wire:model="sucursalDestinoId">
                                        <option value="">Seleccione...</option>
                                        @foreach ($sucursales as $sucursal)
                                            <option value="{{ $sucursal->id }}">
                                                {{ $sucursal->direccion }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label class="col-sm-3 col-form-label">Código:</label>
                                <div class="col-sm-6">
                                    {{-- <input type="text" readonly class="form-control-plaintext"
                                        value="{{ $codigo_compra }}"> --}}
                                        {{ $codigo_compra }}
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label class="col-sm-3 col-form-label">
                                    @error('fecha_compra') 
                                        <i class="fas fa-exclamation-triangle" style="color: red;"></i>
                                    @enderror 
                                    Fecha Compra:
                                </label>
                                <div class="col-sm-6">
                                    <input type="date" class="form-control"
                                        wire:model="fecha_compra">
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label class="col-sm-3 col-form-label">IVA:</label>
                                <div class="d-inline p-2 col-sm-4">
                                    <input type="checkbox" class="form-check-input"
                                        wire:model="iva"
                                        wire:click="calcularIva"
                                    >
                                    <div class="d-inline p-2 text-muted">{{ $iva ? 'Sí' : 'No' }}</div>
                                </div>
                                
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <hr>

            <!-- Formualario Item Productos -->
            <div class="row mt-4 mb-4 align-items-center">
                <div class="col-md-6">
                    <div class="input-group">
                        <label class="col-sm-2 col-form-label">Producto</label>
                        <div>
                            <livewire:select-2
                                :options="$this->productosSelect"
                                onchange="seleccionarProducto"
                            />
                        </div>
                    </div>
                    @error('idProductoSeleccionado')
                        <div class="text-danger">{{ $message }}***</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <div class="input-group">
                        <input type="number"
                            class="form-control"
                            placeholder="Cantidad"
                            wire:model="cantidadProducto"
                            wire:keydown.tab="agregarProducto"
                            min="1">

                        <button class="btn btn-success btn-sm"
                            type="button"
                            wire:click="agregarProducto">
                            Agregar
                        </button>
                    </div>
                    @error('cantidadProducto')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Fin Formualario Item Productos -->

            <hr>

            <!-- Tabla -->
            <div class="row cabecera">
                <div class="col-md-12">

                    <table class="table mt-3 text-center">
                        <thead class="thead-dark">
                            <tr class="text-uppercase">
                                <th>Opciones</th>
                                <th>#</th>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>P. Unitario</th>
                                <th>IVA</th>
                                <th>Sub Total</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($detalleCompra as $item)
                                <tr>
                                    <td>
                                        <i class="fa-solid fa-trash-can"
                                           wire:click="eliminarProducto({{ $item['id'] }})">
                                        </i>
                                    </td>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item['descripcion'] }}</td>
                                    <td>{{ $item['cantidad'] }}</td>
                                    <td>{{ number_format($item['precio_unitario'], 2) }}</td>
                                    <td>{{ number_format($item['iva'] / 100, 2) }}</td>
                                    <td>{{ number_format($item['sub_total'] / 100, 2) }}</td>
                                </tr>
                            @endforeach

                            <tr>
                                <td colspan="5"></td>
                                <td><b>Total General Bs.</b></td>
                                <td><b>{{ number_format($totalCompra / 100, 2) }}</b></td>
                            </tr>
                            <tr>
                                <td colspan="5"></td>
                                <td><b>Total IVA Bs.</b></td>
                                <td><b>{{ number_format($totalIva / 100, 2) }}</b></td>
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>

            <div class="text-center mt-3">
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#cancelarCompraModal">
                    Cancelar Compra
                </button>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#observacionModal">
                    Guardar Compra
                </button>
            </div>

        </div>
    </form>

    <x-modal
        id="observacionModal"
        nombreBtn="Guardar"
        idformulario="confirmarEnvioForm"
        title="Observación para la compra"
        wire:click="guardarCompra"
    >
        <x-formulario.textarea
            name="observacion"
            id="observacion"
            placeholder="Ingrese una observación (opcional)"
            cols="30"
            rows="4"
            wire:model="observacion">
        </x-formulario.textarea>

    </x-modal>

    <x-modal
        id="cancelarCompraModal"
        nombreBtn="Sí, cancelar"
        idformulario="cancelarCompraForm"
        title="¿Está seguro de cancelar la compra?"
        wire:click="cancelarCompra"
    >
        <p class="text-center">Esta accion redirigirá a la página principal, sin guardar la compra</p>
    </x-modal>

</div>

