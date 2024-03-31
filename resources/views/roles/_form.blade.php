<div class="row">
    <input type="text" name="id_rol" value="{{$rol[0]->id}}" hidden>
</div>
<div class="row">
    <div class="col-md">
        <div class="mb-3">
            <label for="nombre_rol" class="form-label">Rol:</label>
            <input type="text" class="form-control" name="nombre_rol" id="nombre_rol" placeholder="Introduzca el nombre del Rol" value="{{ $rol[0]->type }}"> 
          </div>
    </div>
</div>

<div class="row">
    <div class="row">
        <div class="col-md text-center">
            <h5>Seleccione Sucursales a Habilitar</h5>
        </div>
    </div>
    <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-10">
            @foreach ($opciones as $opcion)
                @php
                    $controlImpresion = false;
                @endphp
                    @foreach ($opciones_habilitadas as $opc_hab)
                        @if ($opc_hab->id_opciones_sistemas == $opcion->id) 
                            <div class="form-check">
                                <input class="form-check-input soloLectura" type="checkbox" value="{{ $opcion->id }}" id="flexCheckChecked{{ $opcion->id }}" name=opciones_seleccionadas[] checked >
                                <label class="form-check-label" for="flexCheckChecked{{$opcion->id}}">
                                    <i class="{{ $opcion->icono }}" style="color:#6BA9FA"></i> {{ $opcion->opcion }} 
                                </label>
                            </div>
                            @php
                                $controlImpresion =  true;  
                            @endphp
                            @break
                        @endif
                    @endforeach
                    @if (!$controlImpresion)
                        <div class="form-check">
                            <input class="form-check-input soloLectura" type="checkbox" value="{{ $opcion->id }}" id="flexCheckChecked{{ $opcion->id }}" name=opciones_seleccionadas[]>
                            <label class="form-check-label" for="flexCheckChecked{{$opcion->id}}">
                                <i class="{{ $opcion->icono }}" style="color:#6BA9FA"></i> {{ $opcion->opcion }} 
                            </label>
                        </div> 
                    @endif
            @endforeach
        </div>
        <div class="col-md-1"></div>
    </div>                              
</div>