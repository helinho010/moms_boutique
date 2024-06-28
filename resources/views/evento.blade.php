@extends('layouts.plantillabase')

@section('title','Evento')

@section('css')
    <style>
        table > tbody > tr > th > i{
            font-size: 20px;
        }
    </style>
@endsection

@section('h-title')
    @php
        if (isset($_GET['exito'])) 
        {
            if ($_GET['exito'] == 1) {
                echo '<div class="alert alert-success" role="alert">El Evento se registro correctamente</div>';
            }else{
                echo '<div class="alert alert-danger" role="alert">Error al registrar el Evento</div>';
            }
            unset($_GET['exito']);
        }
        if ($errors->first('nombre') != '') {
            echo '<div class="alert alert-danger" role="alert">'.$errors->first('nombre').'</div>';
        }   
    @endphp
@endsection

@section('card-title')
    <div class="row">
        <div class="col">
            <h4>Lista de Eventos</h4>
        </div>
        <div class="col text-end">
            <button type="button" class="btn btn-success" id="modalRegistroActualizacion" data-bs-toggle="modal" data-bs-target="#exampleModal">
                <i class="fas fa-plus"></i> Agregar Evento
            </button>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <form action="{{ route('buscar_evento') }}" method="POST" id="buscarformulario">
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
        <table class="table table-striped"> 
            <thead>
                <tr>
                  <th scope="col">Opciones</th>
                  <th scope="col">Nombre Evento</th>
                  <th scope="col">Fecha del Evento</th>
                  <th scope="col">Estado</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($eventos as $aux)
                  <tr>
                    <th scope="row">
                      <i class="fas fa-edit fa-xl i" style="color:#6BA9FA" onclick='editar(@php echo json_encode(["id"=>$aux->id,"nombre"=>$aux->nombre,"fecha_evento"=>$aux->fecha_evento]); @endphp)'></i>
                      @php
                        $auxdata = json_encode(['id'=>$aux->id,'estado'=>$aux->estado]);
                        if ($aux->estado == 1) 
                        {
                            echo  '<i class="fas fa-trash-alt fa-xl" style="color:#FA746B" onclick=\'habilitarDesabilitar('.$auxdata.')\'></i>'; 
                        }else{
                            echo '<i class="fas fa-check-circle fa-xl" style="color:#FAAE43" onclick=\'habilitarDesabilitar('.$auxdata.')\'></i>';
                        }
                      @endphp

                    </th>
                    <td>{{ $aux->nombre }}</td>
                    <td>{{ $aux->fecha_evento }}</td>
                    <td> 
                        @if ( $aux->estado == 1 )
                            <span class="badge bg-success">Activo</span>    
                        @else
                            <span class="badge bg-warning">Inactivo</span>    
                        @endif
                    </td>
                  </tr>    
                @endforeach
              </tbody>
        </table>
        {{ $eventos->links() }}
    </div>

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Nuevo Evento</h5>
                <button type="button" class="btn-close cerrarModal" data-bs-dismiss="modal" aria-label="Close" onclick="resestablecerValoresModal()"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('nuevo_evento') }}" id="formularioRegistroActualizacion">
                        @csrf
                        @method('POST')
                        <div class="mb-3">
                          <label for="exampleInputEmail1" class="form-label">Nombre del Evento:</label>
                          <input type="text" class="form-control" name="nombre" id="nombre_evento" placeholder="Introduzca el nombre del Evento"> 
                        </div>
                        <div class="mb-3">
                            <label for="fecha_evento" class="form-label">Fecha del Evento:</label>
                            <input type="date" value="{{ date('Y-m-d') }}" class="form-control" name="fecha_evento" id="fecha_evento"> 
                          </div>
                      </form>
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
        $("#exampleModalLabel").html("<h3>Nuevo Evento</h3>");
        $("#formularioTipoIngresoSalida").attr("action","{{ route('nuevo_evento') }}");
        $("#nombre_evento").val('');
        // $("#fecha_evento").val('');
        $("#inputTipoModal").val("Guardar");     
    }

    function editar(item)
    {
        $("#exampleModal").modal("show");
        $("#exampleModalLabel").html("<h3>Editar Evento</h3>");
        $("#formularioRegistroActualizacion").attr("action","{{ route('actualizar_evento') }}");
        $("#formularioRegistroActualizacion").append('<input type="text" name="id" '+ 'value="'+ item.id +'"' +'hidden>');
        $("#nombre_evento").val(item.nombre);
        $("#fecha_evento").val(item.fecha_evento);
        $("#btnGuardarActualizar").val("Actualizar");
        $("#btnGuardarActualizar").on('click',function(){
            $("#formularioRegistroActualizacion").submit();
            resestablecerValoresModal();
        });
    }

    function habilitarDesabilitar(item)
    {
        let mensaje = '';
        if(item.estado == 1){
            mensaje = 'Esta seguro de deshabilitar la Evento?';
        }else{
            mensaje = 'Esta seguro de habilitar la Evento?';
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
                        url: {{ route('actualizar_estado_evento') }},
                        data: {"id":item.id, "estado":item.estado},
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
        var pathname = window.location.pathname;
        
        if (parametroGetExito == 1) 
        {
            setTimeout(() => {
                $(location).attr('href',pathname);
            }, 10000);
        }
        
        $("#home").removeClass('active');
        $("#evento").addClass('active');
    });
    
</script>
@endpush
