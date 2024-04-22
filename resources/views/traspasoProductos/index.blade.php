@extends('layouts.plantillabase')

@section('title','Categoria')

@section('css')
    <link rel="stylesheet" href="{{ asset('DataTables/datatables.min.css') }}">
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
                echo '<div class="alert alert-success" role="alert">La Categoria se registro correctamente</div>';
            }else{
                echo '<div class="alert alert-danger" role="alert">Error al registrar la Categoria</div>';
            }
        }
        if ($errors->first('nombre') != '') {
            echo '<div class="alert alert-danger" role="alert">'.$errors->first('nombre').'</div>';
        }   
    @endphp
@endsection

@section('card-title')
    <div class="row">
        <div class="col">
            <h4>Lista de Categorias</h4>
        </div>
        <div class="col text-end">
            <button type="button" class="btn btn-success" id="modalCategoria" data-bs-toggle="modal" data-bs-target="#exampleModal">
                <i class="fas fa-plus"></i> Agregar Categoria 
            </button>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <form action="{{ route('buscar_categoria') }}" method="POST" id="buscarformulario">
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
                  <th scope="col">Nombre Categoria</th>
                  <th scope="col">Estado</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($categorias as $categoria)
                  <tr>
                    <th scope="row">
                      <i class="fas fa-edit fa-xl i" style="color:#6BA9FA" onclick='editarCategoria(@php echo json_encode(["id"=>$categoria->id,"nombre"=>$categoria->nombre]); @endphp)'></i>
                      @php
                        $dataCategoria = json_encode(['id'=>$categoria->id,'estado'=>$categoria->estado]);
                        if ($categoria->estado == 1) 
                        {
                            echo  '<i class="fas fa-trash-alt fa-xl" style="color:#FA746B" onclick=\'habilitarDesabilitar('.$dataCategoria.')\'></i>'; 
                        }else{
                            echo '<i class="fas fa-check-circle fa-xl" style="color:#FAAE43" onclick=\'habilitarDesabilitar('.$dataCategoria.')\'></i>';
                        }
                      @endphp

                    </th>
                    <td>{{ $categoria->nombre }}</td>
                    <td> 
                        @if ( $categoria->estado == 1 )
                            <span class="badge bg-success">Activo</span>    
                        @else
                            <span class="badge bg-warning">Inactivo</span>    
                        @endif
                    </td>
                  </tr>    
                @endforeach
              </tbody>
        </table>
        {{ $categorias->links() }}
    </div>

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Nueva Categoria</h5>
                <button type="button" class="btn-close cerrarModal" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('nueva_categoria') }}" id="nueva_categoria">
                        @csrf
                        @method('POST')
                        <div class="mb-3">
                          <label for="exampleInputEmail1" class="form-label">Nombre de la Categoria:</label>
                          <input type="text" class="form-control" name="nombre" id="nombre_categoria" aria-describedby="emailHelp" placeholder="Introduzca el nombre de la Categoria"> 
                        </div>
                      </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger cerrarModal" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-success" id="inputNombreModal">Guardar</button>
                </div>
            </div>
            </div>
        </div>
@endsection


@push('scripts')
<script src="{{ asset('jquery/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('DataTables/datatables.min.js') }}"></script>
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
        } else if ($(this).attr('id') == 'inputNombreModal') 
        {
            $("#nueva_categoria").submit();
        }
    });

    function editarCategoria(categoria){
        console.log(categoria.nombre);
        $("#exampleModal").modal("show");
        $("#exampleModalLabel").html("<h3>Editar Categoria</h3>");
        $("#nueva_categoria").attr("action","{{ route('actualizar_categoria') }}");
        $("#nueva_categoria").append('<input type="text" name="id" '+ 'value="'+ categoria.id +'"' +'hidden>');
        $("#nombre_categoria").val(categoria.nombre);
        $("#inputNombreModal").val("Actualizar");
        $("#inputNombreModal").on('click',function(){
            $("#nueva_categoria").submit();
        });
    }

    function habilitarDesabilitar(categoria)
    {
        let mensaje = '';
        if(categoria.estado == 1){
            mensaje = 'Esta seguro de deshabilitar la categoria?';
        }else{
            mensaje = 'Esta seguro de habilitar la categoria?';
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
                        url: '/actualizar_estado',
                        data: {"id":categoria.id, "estado":categoria.estado},
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
        $("#categoria").addClass('active');
    });

</script>
@endpush