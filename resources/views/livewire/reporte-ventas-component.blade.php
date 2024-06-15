<div>
    <div class="row">
        <div class="col-md-5">
            <div class="mb-3">
                <label for="id_sucursal" class="form-label">{{ $titleLabel }}</label>
                <select class="form-select" name="id_sucursal" id="id_sucursal" aria-label="Default select example" wire:model='idSelector'>
                    <option value="seleccionado" selected disabled>Seleccione una opcion ...</option>
                    @foreach ($eventosOSucursales as $item)
                        @if ($item->estado == 1)
                        <option value="{{ $item->id }}">
                            {{ $item->nombre}}
                            {{ $item->ciudad != "" ? " - ".$item->ciudad : "" }}
                            {{ $item->direccion != "" ? " - ".substr($item->direccion,0,30)."..." : "" }}
                            {{ $item->fecha != "" ? " - ".$item->fecha : "" }}
                        </option>
                        @else
                        <option value="{{ $item->id_sucursal }}" disabled>
                            {{ $item->nombre}}
                            {{ $item->ciudad != "" ? "-".$item->ciudad : "" }}
                            {{ $item->direccion != "" ? "-".substr($item->direccion,0,30)."..." : "" }}
                            {{ $item->fecha != "" ? "-".$item->fecha : "" }}
                        </option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="mb-3">
                <label for="fecha_inicial" class="form-label">Fecha Inicio</label>
                <input type="date" class="form-control" name="fecha_inicial" id="fecha_inicial" wire:model='fechaInicial'>
            </div>
        </div>
        <div class="col-md-3">
            <div class="mb-3">
                <label for="fecha_final" class="form-label">Fecha Final</label>
                <input type="date" class="form-control" name="fecha_final" id="fecha_final" wire:model='fechaFinal'>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4">
            <button type="button" class="btn btn-primary" id="obternerReporteVentasExcel" disabled wire:click='obtenerReporte'>
                Obtener el Reporte
            </button>
        </div>
        <div class="col-md-4"></div>
    </div>
</div>