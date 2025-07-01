<div>
    <input type="number" name="id_inventario_interno" value="{{ $item->id }}" hidden>
    <div class="form-group mb-3">
        <label for="id_sucursal">Sucursal: </label>
        <select name="id_sucursal" id="id_sucursal" class="form-control">
            @foreach ($sucursales as $sucursal)
                <option value="{{ $sucursal->id }}" {{ old('id_sucursal', $item->id_sucursal) == $sucursal->id ? 'selected' : '' }}>
                    {{ substr($sucursal->direccion, 0, 40) . "..." }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group mb-3">
        <label for="id_producto">Producto: </label>
        <select name="id_producto" id="id_producto" class="form-control">
            @foreach ($productos as $producto)
                <option value="{{ $producto->id }}" {{ old('id_producto', $item->id_producto) == $producto->id ? 'selected' : '' }}>
                    {{ $producto->nombre }} - 
                    Talla: {{ $producto->talla ? $producto->talla : "ST(Sin Talla)" }} -
                    Precio Venta: {{ $producto->precio ? $producto->precio : '0.00' }} 
                    @can('costo producto')
                        - Costo Producto: {{ $producto->costo ? $producto->costo : '0.00' }}
                    @endcan
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group mb-3">
        <label for="id_tipo_ingreso_salida">Tipo Ingreso Salida: </label>
        <select name="id_tipo_ingreso_salida" id="id_tipo_ingreso_salida" class="form-control">
            @foreach ($tipoIngresoSalidas as $tipo)
                <option value="{{ $tipo->id }}" {{ old('tipo_ingreso_salida', $item->id_tipo_ingreso_salida) == $tipo->id ? 'selected' : '' }}>
                    {{ $tipo->tipo }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group mb-3">
        <label for="cantidad_ingreso">Cantidad: </label>
        <input type="number" name="cantidad_ingreso" id="cantidad_ingreso" class="form-control" value="{{ old('cantidad_ingreso', $item->cantidad_ingreso) }}" required placeholder="0">
    </div>

    <button type="submit" class="btn btn-success">Guardar</button>
    <a href="{{ route('home_inventario_interno', ['id_sucursal' => $id_sucursal]) }}" class="btn btn-warning text-dark">Volver</a>
</div>
