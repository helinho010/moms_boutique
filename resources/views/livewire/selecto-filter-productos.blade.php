<div>
    <div class="row">
        <div class="col-md-2">
            <label for="modalSelectSucursal" class="col-form-label">Sucursal:</label>
        </div>
        <div class="col-md-8">
                <div class="input-group">
                    <select class="form-select" aria-describedby="" name="id_sucursal" id="modalSelectSucursal" wire:model.live='id_sucursal_seleccionado'>
                        <option value="seleccionado" disabled>Seleccione una opcion...</option>
                            @foreach ($sucursales as $item)
                               @if ($item->estado_sucursal == 1)
                                  <option value="{{ $item->id_sucursal }}">{{ "$item->razon_social_sucursal - $item->ciudad_sucursal - ".substr($item->direccion_sucursal,0,40)."..." }}</option>
                                @else
                                  <option value="{{ $item->id_sucursal }}" disabled>{{ "$item->razon_social_sucursal - $item->ciudad_sucursal - ".substr($item->direccion_sucursal,0,30)."... (deshabilitado)" }}</option>
                               @endif
                            @endforeach
                     </select>
                </div>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-2">
            <label for="inputPassword6" class="col-form-label">Producto:</label>
        </div>
        <div class="col-md-8">
                <div class="input-group">
                    <select class="form-select" aria-describedby="" name="id_producto" id="modalSelectProducto">
                        <option value="seleccionado" selected disabled>Seleccione una opcion...</option>
                            @foreach ($productos as $item)
                                @if ($item->estado == 1 && $item->stock>0)
                                    <option value="{{ $item->id_producto }}">
                                        {{ $item->nombre }} - Talla: {{ $item->talla!=""?$item->talla:"ST(Sin Talla)" }} - Precio: {{$item->precio}} Bs. - Stock: {{$item->stock}}
                                    </option>
                                    @else
                                    <option value="{{ $item->id_producto }}" disabled>
                                        {{ $item->nombre }} - Talla: {{ $item->talla!=""?$item->talla:"ST(Sin Talla)" }} - Precio: {{$item->precio}} Bs. - Stock: {{$item->stock}}
                                    </option>
                                @endif
                            @endforeach
                    </select>
                </div>
        </div>
    </div>
</div>
