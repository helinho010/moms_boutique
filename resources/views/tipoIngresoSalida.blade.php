@extends('layouts.plantillabase')

@section('title','Tipo de Ingreso o Salida')

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
                echo '<div class="alert alert-success" role="alert">El Tipo de Ingreso o Salida se registro correctamente</div>';
            }else{
                echo '<div class="alert alert-danger" role="alert">Error al registrar el Tipo de Ingreso o Salida</div>';
            }
        }
        if ($errors->first('tipo') != '') {
            echo '<div class="alert alert-danger" role="alert">'.$errors->first('tipo').'</div>';
        }   
    @endphp
@endsection

@section('card-title')
    <div class="row">
        <div class="col">
            <h4>Lista de Tipos de Ingresos o Salidas</h4>
        </div>
        <div class="col text-end">
            <button type="button" class="btn btn-success" id="modaltipoIngresoSalida" data-bs-toggle="modal" data-bs-target="#exampleModal">
                <i class="fas fa-plus"></i> Agregar Tipo de Ingreso o Salida 
            </button>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <form action="{{ route('buscar_tipo_ingreso_salida') }}" method="POST" id="buscarformulario">
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
                  <th scope="col">Tipo</th>
                  <th scope="col">Estado</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($tipoIngresoSalidas as $tipoIngresoSalida)
                  <tr>
                    <th scope="row">
                      <i class="fas fa-edit fa-xl i" style="color:#6BA9FA" onclick='editartipoIngresoSalida(@php echo json_encode(["id"=>$tipoIngresoSalida->id,"tipo"=>$tipoIngresoSalida->tipo]); @endphp)'></i>
                      @php
                        $datatipoIngresoSalida = json_encode(['id'=>$tipoIngresoSalida->id,'estado'=>$tipoIngresoSalida->estado]);
                        if ($tipoIngresoSalida->estado == 1) 
                        {
                            echo  '<i class="fas fa-trash-alt fa-xl" style="color:#FA746B" onclick=\'habilitarDesabilitar('.$datatipoIngresoSalida.')\'></i>'; 
                        }else{
                            echo '<i class="fas fa-check-circle fa-xl" style="color:#FAAE43" onclick=\'habilitarDesabilitar('.$datatipoIngresoSalida.')\'></i>';
                        }
                      @endphp

                    </th>
                    <td>{{ $tipoIngresoSalida->tipo }}</td>
                    <td> 
                        @if ( $tipoIngresoSalida->estado == 1 )
                            <span class="badge bg-success">Activo</span>    
                        @else
                            <span class="badge bg-warning">Inactivo</span>    
                        @endif
                    </td>
                  </tr>    
                @endforeach
              </tbody>
        </table>
        {{ $tipoIngresoSalidas->links() }}
    </div>

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Nueva Tipo de Ingreso o Salida</h5>
                <button type="button" class="btn-close cerrarModal" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('nuevo_tipo_ingreso_salida') }}" id="formularioTipoIngresoSalida">
                        @csrf
                        @method('POST')
                        <div class="mb-3">
                          <label for="exampleInputEmail1" class="form-label">Nombre del tipo de Ingreso o Salida:</label>
                          <input type="text" class="form-control" name="tipo" id="tipo_ingreso_salida" aria-describedby="emailHelp" placeholder="Introduzca el tipo de ingreso o salida"> 
                        </div>
                      </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger cerrarModal" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-success" id="inputTipoModal">Guardar</button>
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
        } else if ($(this).attr('id') == 'inputTipoModal') 
        {
            $("#formularioTipoIngresoSalida").submit();
        }
    });

    function resestablecerValoresModal()
    {
        $("#exampleModalLabel").html("<h3>Nueva Tipo de Ingreso o Salida</h3>");
        $("#formularioTipoIngresoSalida").attr("action","{{ route('nuevo_tipo_ingreso_salida') }}");
        $("#tipo_ingreso_salida").val('');
        $("#inputTipoModal").val("Guardar");     
    }

    function editartipoIngresoSalida(tipoIngresoSalida){
        console.log(tipoIngresoSalida.tipo);
        $("#exampleModal").modal("show");
        $("#exampleModalLabel").html("<h3>Editar Tipo de Ingreso o Salida</h3>");
        $("#formularioTipoIngresoSalida").attr("action","{{ route('actualizar_tipo_ingreso_salida') }}");
        $("#formularioTipoIngresoSalida").append('<input type="text" name="id" '+ 'value="'+ tipoIngresoSalida.id +'"' +'hidden>');
        $("#tipo_ingreso_salida").val(tipoIngresoSalida.tipo);
        $("#inputTipoModal").val("Actualizar");
        $("#inputTipoModal").on('click',function(){
            $("#formularioTipoIngresoSalida").submit();
            resestablecerValoresModal();
        });
        
    }

    function habilitarDesabilitar(tipoIngresoSalida)
    {
        let mensaje = '';
        if(tipoIngresoSalida.estado == 1){
            mensaje = 'Esta seguro de deshabilitar la tipoIngresoSalida?';
        }else{
            mensaje = 'Esta seguro de habilitar la tipoIngresoSalida?';
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
                        url: "{{ route('actualizar_estado_tipo_ingreso_salida') }}",
                        data: {"id":tipoIngresoSalida.id, "estado":tipoIngresoSalida.estado},
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

    $(document).ready(function(){
        $("#home").removeClass('active');
        $("#tipo\\ ingreso\\ salida").addClass('active');
    });

</script>
@endpush