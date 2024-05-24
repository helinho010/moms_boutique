<div>
    <div class="mb-3">
        <label for="nit_sucursal" class="form-label">Nit:</label>
        <input type="text" class="form-control" name="nit" id="nit_sucursal" aria-describedby="emailHelp" placeholder="Introduzca el Nit" wire:change.live='comprobarAlmacenCentral'> 
    </div>
    <div class="mb-3">
          <label for="razon_social_sucursal" class="form-label">Razon Social:</label>
          <input type="text" class="form-control" name="razon_social" id="razon_social_sucursal" aria-describedby="emailHelp" placeholder="Introduzca la Razon Social" wire:change.live='comprobarAlmacenCentral'> 
    </div>
    <div class="mb-3">
          <label for="direccion_sucursal" class="form-label">Direccion:</label>
          <input type="text" class="form-control" name="direccion" id="direccion_sucursal" aria-describedby="emailHelp" placeholder="Introduzca la Direccion" wire:change.live='comprobarAlmacenCentral'> 
    </div>
    <div class="mb-3">
          <label for="telefonos_sucursal" class="form-label">Telefonos:</label>
          <input type="text" class="form-control" name="telefonos" id="telefonos_sucursal" aria-describedby="emailHelp" placeholder="Introduzca los Telefonos" wire:change.live='comprobarAlmacenCentral'> 
    </div>
    <div class="mb-3">
        <label for="ciudad_sucursal" class="form-label">Ciudad:</label>
        <input type="text" class="form-control" name="ciudad" id="ciudad_sucursal" aria-describedby="emailHelp" placeholder="Introduzca la Ciudad" wire:change.live='comprobarAlmacenCentral'> 
    </div>
    <div class="mb-3 form-check" style="margin: 5px;">
        <input type="checkbox" class="form-check-input" name="almacen_central" id="almacenCentralCheck" wire:model='almacen_central' {{$estado}}>
        <label class="form-check-label" for="almacenCentralCheck">
            Sucursal Central (Almacen Central) <br>
            {{$mensaje}}
        </label>
    </div>
</div>
