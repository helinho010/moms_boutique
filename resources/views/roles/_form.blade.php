<div class="row">
    <div class="col-md-5">
        <div class="mb-3">
            <label for="nombre_rol" class="form-label">Rol:</label>
            <input type="text" class="form-control" 
                   name="nombre_rol" id="nombre_rol" 
                   placeholder="Introduzca el nombre del Rol" 
                   value="{{ strtolower($rol->name) }}" readonly> 
          </div>
    </div>
</div>

<div class="row">
    <div class="row">
        <div class="col-md text-center">
            <h5>Seleccione Permisos para el Rol</h5>
            <hr>  
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            @foreach ($permisos as $permiso)
                @if ($loop->iteration % 15 == 0)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" 
                                value="{{ $permiso->name }}" name="permisos_rol[]" 
                                id="permisoRol{{$permiso->id}}" 
                                @if ( $rol->hasPermissionTo($permiso->name) )
                                    checked
                                @endif
                            >
                            <label class="form-check-label" for="permisoRol{{$permiso->id}}">
                                {{ $permiso->name }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-3">
                @else
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" 
                            value="{{ $permiso->name }}" name="permisos_rol[]" 
                            id="permisoRol" 
                            @if ( $rol->hasPermissionTo($permiso->name) )
                                checked
                            @endif
                        >
                        <label class="form-check-label" for="permisoRol">
                            {{ $permiso->name }}
                        </label>
                    </div> 
                @endif
            @endforeach
            </div>
    </div>                              
</div>