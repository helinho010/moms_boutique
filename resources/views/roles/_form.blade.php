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
            <h5>Seleccione PERMISOS para el Rol</h5>
            <hr>  
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            @foreach ($permisos as $permiso)
                @if (strpos(strtolower($permiso), 'opc ') === false)
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
                        </div> {{-- esto cierra el div.col-md-3 --}}
                        <div class="col-md-3"> {{--Y esto abre  de nuevo el div --}}
                    @else
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
                    @endif
                @endif
            @endforeach
        </div>
    </div>  
</div>
<hr>
<div class="row">
    <div class="row">
        <div class="col-md text-center">
            <h5>Seleccione OPCIONES para el Rol</h5>
            <hr>  
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            @foreach ($permisos as $permiso)
                @if (strpos(strtolower($permiso->name), "opc ") !== false)
                    <div class="form-check form-check-inline me-3">
                        <input class="form-check-input" type="checkbox" value="{{ $permiso->name }}" name="permisos_rol[]"
                            id="permisoRol{{$permiso->id}}">
                        <label class="form-check-label" for="permisoRol{{$permiso->id}}">
                            {{ $permiso->name }}
                        </label>
                    </div>
                @endif
            @endforeach
        </div>
    </div>  
</div>








{{-- Esto es una opcion para mostrar los permisos y las opciones del sistema --}}
    {{-- <div class="row" style="margin-top: 20px;">
        <div class="col-md-12">
            <div class="d-flex align-items-start">
                <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <button class="nav-link active" id="v-pills-home-tab" data-bs-toggle="pill" data-bs-target="#v-pills-home" type="button" role="tab" aria-controls="v-pills-home" aria-selected="true">Home</button>
                    <button class="nav-link" id="v-pills-profile-tab" data-bs-toggle="pill" data-bs-target="#v-pills-profile" type="button" role="tab" aria-controls="v-pills-profile" aria-selected="false">Profile</button>
                    <button class="nav-link" id="v-pills-messages-tab" data-bs-toggle="pill" data-bs-target="#v-pills-messages" type="button" role="tab" aria-controls="v-pills-messages" aria-selected="false">Messages</button>
                    <button class="nav-link" id="v-pills-settings-tab" data-bs-toggle="pill" data-bs-target="#v-pills-settings" type="button" role="tab" aria-controls="v-pills-settings" aria-selected="false">Settings</button>
                </div>
                <div class="tab-content" id="v-pills-tabContent">
                    <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">...1</div>
                    <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">...2</div>
                    <div class="tab-pane fade" id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab">...3</div>
                    <div class="tab-pane fade" id="v-pills-settings" role="tabpanel" aria-labelledby="v-pills-settings-tab">...4</div>
                </div>
            </div>

        </div>
    </div> --}}