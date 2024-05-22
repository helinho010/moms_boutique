<div>
    <div class="mb-3 form-check" style="margin: 5px;">
        <input type="checkbox" class="form-check-input" id="almacenCentralCheck" wire:model='almcen_central' {{$estado}}>
        <label class="form-check-label" for="almacenCentralCheck">Sucursal Central (Almacen Central)</label>
    </div>
    <button wire:click='actulizarCheckAlmacenCentral'> +++</button>
</div>
