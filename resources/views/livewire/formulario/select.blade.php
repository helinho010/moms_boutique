<div>
    <x-formulario.select id="{{ $id_select }}" name="{{ $name_select }}"
                         wire:click="actualizarRoles">
        <option value="0">Seleccione una opcion...</option>
        @foreach ($roles as $rol)
            <option value="{{ $rol }}">{{ $rol }}</option>
        @endforeach
    </x-formulario.select>
</div>
