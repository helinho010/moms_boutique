<div class="row">
    <div class="col-md">
        <div class="mb-3">
            <label for="nombre_usuario" class="form-label">Nombre de Usuario:</label>
            <input type="text" class="form-control" value="{{ $usuario[0]->name_usuario }}" name="nombre_usuario" id="nombre_usuario" placeholder="Introduzca el nombre del usuario"> 
          </div>
    </div>
    <div class="col-md">
        <div class="mb-3">
            <label for="usuario" class="form-label">Usuario:</label>
            <input type="text" class="form-control" value="{{ $usuario[0]->username_usuario }}" name="usuario" id="usuario" placeholder="Introduzca el usuario"> 
            <span id="existeUsuarioBdComentario" style="display: none;">Usuario ya existe</span>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md">
        <div class="mb-3">
            <label for="contrasenia" class="form-label">Contraseña:</label>
            <input type="password" class="form-control" name="contrasenia" id="contrasenia" placeholder="Introduzca la Contraseña"> 
        </div>
    </div>
    <div class="col-md">
        <div class="mb-3">
            <label for="confirmar_contrasenia" class="form-label">Repita la Contraseña:</label>
            <input type="password" class="form-control" name="confirmar_contrasenia" id="confirmar_contrasenia" placeholder="Confirmar Contraseña"> 
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md">
        <div class="mb-3">
            <label for="correo" class="form-label">Correo Electronico:</label>
            <input type="email" class="form-control" value="{{ $usuario[0]->email_usario }}" name="correo" id="correo" placeholder="Introduzca el Correo Electronico"> 
        </div>
    </div>
    <div class="col-md">
        <div class="mb-3">
            <label for="ciudad_proveedor" class="form-label">Tipo de Usuario:</label>
            <div class="row">
                <div class="col-10">
                    <select class="form-select" aria-label="Default select example" name="tipo_usuario" id="tipo_usuario">
                        <option value="0" selected disabled>Seleccione una opcion...</option>
                        @foreach ($roles as $rol)
                            <option value="{{ $rol->id }}" @if ($rol->id == $usuario[0]->id_tipo_usuario)
                                selected
                            @endif>{{ $rol->type}}</option>    
                        @endforeach
                    </select>
                </div>
                <div class="col-2"><i class="fa fa-square-plus" style="font-size: 2vw;" onclick="agregarRol()"></i></div>
            </div>
        </div>
    </div>
    <hr><br>
    <div class="row">
        <div class="col-md text-center">
            <h5>Seleccione Sucursales a Habilitar</h5>
        </div>
    </div>
    <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-10">
            <div id="sucursalesHabilitadas0"></div>
            <div id="sucursalesHabilitadas">
                {{-- @foreach ($sucursales as $sucursal)
                    <div class="form-check">
                        <input class="form-check-input soloLectura" type="checkbox" value="{{ $sucursal->id}}" id="flexCheckChecked" name=sucursales_seleccionadas[]>
                        <label class="form-check-label" for="flexCheckChecked">
                            {{ $sucursal->ciudad}} - {{substr($sucursal->direccion,0,30)}}... 
                        </label>
                    </div>
                @endforeach --}}
            </div>
            <div id="sucursalesHabilitadas1"></div>
        </div>
        <div class="col-md-1"></div>
    </div>                              
</div>