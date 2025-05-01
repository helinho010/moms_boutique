@extends('layouts.plantillabase')

@section('title','Sucursales')

@section('css')
    <style>
        table > tbody > tr > th > i{
            font-size: 20px;
        }
    </style>
@endsection

@section('h-title')

    @error('errorAddAlmacenCentral')
     <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    @php

        if (isset($_GET['exito'])) 
        {
            if ($_GET['exito'] == 1) {
                echo '<div class="alert alert-success" role="alert">La Sucursal se registro correctamente</div>';
            }else{
                echo '<div class="alert alert-danger" role="alert">Error al registrar la Sucursal</div>';
            }
        }

        if (isset($_GET['actualizado'])) 
        {
            if ($_GET['actualizado'] == 1) {
                echo '<div class="alert alert-success" role="alert">La Sucursal fue actualizada correctamente</div>';
            }else if ( $_GET['actualizado'] == 2 ) {
                echo '<div class="alert alert-warning" role="alert">Ya existe una Sucursal Central</div>';    
            }else{
                echo '<div class="alert alert-danger" role="alert">Error al actualizar la Sucursal</div>';
            }
        }

        if ($errors->first('nit') != '' ||
            $errors->first('razon_social') != '' ||
            $errors->first('direccion') != '' ||
            $errors->first('telefonos') != '' ||
            $errors->first('ciudad') != '') 
            {
                echo '<div class="alert alert-danger" role="alert">'.
                $errors->first('nit')."<br>".
                $errors->first('razon_social')."<br>".
                $errors->first('direccion')."<br>".
                $errors->first('telefonos')."<br>".
                $errors->first('ciudad')."<br>".
                $errors->first('activo')."<br>"
                .'</div>';
        }   
    @endphp
@endsection

@section('card-title')
    <div class="row">
        <div class="col">
            <h4>Lista de Sucursales</h4>
        </div>
        <div class="col text-end">
            <button type="button" class="btn btn-success" id="modalRegistroActualizacion" data-bs-toggle="modal" data-bs-target="#exampleModal">
                <i class="fas fa-plus"></i> Agregar Sucursal
            </button>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <form action="{{ route('buscar_sucursal') }}" method="POST" id="buscarformulario">
                @method('POST')
                @csrf
                <div class="input-group flex-nowrap">
                    <input type="text" name="buscar" id="buscar" class="form-control" placeholder="Buscar..." aria-label="Username" aria-describedby="addon-wrapping">
                    <button class="input-group-text" id="inputBuscar"><i class="fas fa-search"></i></button>
                </div>
            </form>
        </div>
        <div class="col-md-3"></div>
    </div>
    <br>
    <div class="row">
        <table class="table table-striped table-bordered"> 
            <thead>
                <tr class="align-middle">
                  <th scope="col">Opciones</th>
                  <th scope="col">Nit</th>
                  <th scope="col">Razon Social</th>
                  <th scope="col">Direccion</th>
                  <th scope="col">Telefonos</th>
                  <th scope="col">Ciudad</th>
                  <th scope="col">Almacen Central</th>
                  <th scope="col">Estado</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($sucursales as $aux)
                  <tr>
                    <th scope="row" >
                        <i class="fas fa-edit fa-xl i" style="color:#6BA9FA" onclick='editar(@php echo json_encode([
                            "id"=>$aux->id,
                            "nit"=>$aux->nit,
                            "razon_social"=>$aux->razon_social,
                            "direccion"=>$aux->direccion,
                            "telefonos"=>$aux->telefonos,
                            "ciudad"=>$aux->ciudad,
                            "activo"=>$aux->activo,
                            ]); @endphp)'>
                        </i>
                        @php
                        $auxdata = json_encode([
                            "id"=>$aux->id,
                            "nit"=>$aux->nit,
                            "razon_social"=>$aux->razon_social,
                            "direccion"=>$aux->direccion,
                            "telefonos"=>$aux->telefonos,
                            "ciudad"=>$aux->ciudad,
                            "activo"=>$aux->activo,
                            ]);
                        if ($aux->activo == 1) 
                        {
                            echo  '<i class="fas fa-trash-alt fa-xl" style="color:#FA746B" onclick=\'habilitarDesabilitar('.$auxdata.')\'></i>'; 
                        }else{
                            echo '<i class="fas fa-check-circle fa-xl" style="color:#FAAE43" onclick=\'habilitarDesabilitar('.$auxdata.')\'></i>';
                        }
                      @endphp

                    </th>
                    <th>{{$aux->nit}}</th>
                    <th>{{$aux->razon_social}}</th>
                    <th>{{$aux->direccion}}</th>
                    <th>{{$aux->telefonos}}</th>
                    <th>{{$aux->ciudad}}</th>
                    <th>
                        @if ($aux->almacen_central)
                            <span class="badge bg-success">&nbsp;&nbsp;Si&nbsp;&nbsp;</span>
                        @else
                            <span class="badge bg-warning"> &nbsp;No&nbsp; </span>
                        @endif
                    </th>
                    <td> 
                        @if ( $aux->activo == 1 )
                            <span class="badge bg-success">Activo</span>    
                        @else
                            <span class="badge bg-warning">Inactivo</span>    
                        @endif
                    </td>
                  </tr>    
                @endforeach
              </tbody>
        </table>
        {{ $sucursales->links() }}
    </div>

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Nueva Sucursal</h5>
                <button type="button" class="btn-close cerrarModal" data-bs-dismiss="modal" aria-label="Close" onclick="resestablecerValoresModal()"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('nueva_sucursal') }}" id="formularioRegistroActualizacion">
                        @csrf
                        @method('POST')
                        {{-- <div class="mb-3">
                          <label for="nit_sucursal" class="form-label">Nit:</label>
                          <input type="text" class="form-control" name="nit" id="nit_sucursal" aria-describedby="emailHelp" placeholder="Introduzca el Nit"> 
                        </div>
                        <div class="mb-3">
                            <label for="razon_social_sucursal" class="form-label">Razon Social:</label>
                            <input type="text" class="form-control" name="razon_social" id="razon_social_sucursal" aria-describedby="emailHelp" placeholder="Introduzca la Razon Social"> 
                          </div>
                          <div class="mb-3">
                            <label for="direccion_sucursal" class="form-label">Direccion:</label>
                            <input type="text" class="form-control" name="direccion" id="direccion_sucursal" aria-describedby="emailHelp" placeholder="Introduzca la Direccion"> 
                          </div>
                          <div class="mb-3">
                            <label for="telefonos_sucursal" class="form-label">Telefonos:</label>
                            <input type="text" class="form-control" name="telefonos" id="telefonos_sucursal" aria-describedby="emailHelp" placeholder="Introduzca los Telefonos"> 
                          </div>
                          <div class="mb-3">
                            <label for="ciudad_sucursal" class="form-label">Ciudad:</label>
                            <input type="text" class="form-control" name="ciudad" id="ciudad_sucursal" aria-describedby="emailHelp" placeholder="Introduzca la Ciudad"> 
                          </div> --}}
                          <br>
                          @livewire('check-almacen-central')
                      </form>

                      @php  
                        if (isset($errorAddAlmacenCentral)) 
                        {
                            echo '<div class="alert alert-danger" role="alert">'.$errorAddAlmacenCentral[0]->errorAddAlmacenCentral.'</div>';
                        }
                      @endphp
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger cerrarModal" data-bs-dismiss="modal" onclick="resestablecerValoresModal()">Cerrar</button>
                    <button type="button" class="btn btn-success" id="btnGuardarActualizar">Guardar</button>
                </div>
            </div>
            </div>
        </div>
@endsection


@push('scripts')
<script src="{{ asset('jquery/jquery-3.7.1.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    $('button').on('click',function() 
    {   
        event.preventDefault();
        if ($(this).attr('id') == 'inputBuscar') 
        {
            $("#buscarformulario").submit();
        } else if ($(this).attr('id') == 'btnGuardarActualizar') 
        {
            $("#formularioRegistroActualizacion").submit();
        }
    });

    function resestablecerValoresModal()
    {
        $("#exampleModalLabel").html("<h3>Nueva Sucursal</h3>");
        $("#formularioTipoIngresoSalida").attr("action","{{ route('nueva_sucursal') }}");
        $("#nit_sucursal").val('');
        $("#razon_social_sucursal").val('');
        $("#direccion_sucursal").val('');
        $("#telefonos_sucursal").val('');
        $("#ciudad_sucursal").val('');
        $("#btnGuardarActualizar").val("Guardar");     
    }

    function editar(item)
    {
        $("#exampleModal").modal("show");
        $("#exampleModalLabel").html("<h3>Editar Sucursal</h3>");
        $("#formularioRegistroActualizacion").attr("action","{{ route('actualizar_sucursal') }}");
        $("#formularioRegistroActualizacion").append('<input type="text" name="id" '+ 'value="'+ item.id +'"' +'hidden>');
        $("#nit_sucursal").val(item.nit);
        $("#razon_social_sucursal").val(item.razon_social);
        $("#direccion_sucursal").val(item.direccion);
        $("#telefonos_sucursal").val(item.telefonos);
        $("#ciudad_sucursal").val(item.ciudad);
        $("#btnGuardarActualizar").val("Actualizar");
        $("#btnGuardarActualizar").on('click',function(){
            $("#formularioRegistroActualizacion").submit();
            resestablecerValoresModal();
        });

        Livewire.dispatch('cambiar_id_sucursal', { id_sucursal_pased: item.id });
    }

    function habilitarDesabilitar(item)
    {
        let mensaje = '';
        if(item.activo == 1){
            mensaje = 'Esta seguro de deshabilitar la Sucursal?';
        }else{
            mensaje = 'Esta seguro de habilitar la Sucursal?';
        }

        Swal.fire({
                title: mensaje,
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: "Si",
                denyButtonText: `No`
                }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) 
                {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('actualizar_estado_sucursal') }}",
                        data: {"id":item.id, "activo":item.activo},
                        success: function (response) {
                          Swal.fire("Cambio Guardado!", "", "success");        
                          location.reload();
                        }
                    });
                } else if (result.isDenied) {
                    // Swal.fire("Changes are not saved", "", "info");
                }
            });

    }

    function getParameterByName(name) {
        name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
        var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
        return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
    }

    $(document).ready(function(){
        var parametroGetExito = getParameterByName('exito');
        var parametroGetActualizado = getParameterByName('actualizado');
        var pathname = window.location.pathname;
        
        if (parametroGetExito == 1 || parametroGetActualizado == 1) 
        {
            setTimeout(() => {
                $(location).attr('href',pathname);
            }, 10000);
        } 
    });

    $(document).ready(function(){
        $("#home").removeClass('active');
        $("#sucursal").addClass('active');
    });
    
</script>
@endpush
