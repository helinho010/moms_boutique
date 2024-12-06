<div>
    <select class="form-select" 
            aria-label="Default select example"
            id=""{{ $id }}
            name="{{ $name }}"
    >
        <option selected disabled>Seleccione una opcion</option>
        @foreach ($sucursales as $sucursal)
            <option value="{{ $sucursal->id_sucursal }}"> {{ $sucursal->direccion_sucursal }} </option>
        @endforeach
    </select>
</div>