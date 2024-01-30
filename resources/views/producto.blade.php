@extends('layouts.plantillabase')

@section('title','Productos')

@section('css')
    <style>
        table > tbody > tr > th > i{
            font-size: 20px;
        }
        #descripcion{
            width: 20%;
        }
    </style>
@endsection

@section('h-title')
    @php
        if (isset($_GET['exito'])) 
        {
            if ($_GET['exito'] == 1) {
                echo '<div class="alert alert-success" role="alert">El Producto se registro correctamente</div>';
            }else{
                echo '<div class="alert alert-danger" role="alert">Error al registrar el Producto</div>';
            }
        }

        if (isset($_GET['actualizado'])) 
        {
            if ($_GET['actualizado'] == 1) {
                echo '<div class="alert alert-success" role="alert">El Producto fue actualizado correctamente</div>';
            }else{
                echo '<div class="alert alert-danger" role="alert">Error al actualizar el Producto</div>';
            }
        }

        if ($errors->first('id_categoria') != '' ||
            $errors->first('nombre') != '' ||
            $errors->first('costo') != '' ||
            $errors->first('precio') != '' ||
            $errors->first('talla') != '') 
            {
                echo '<div class="alert alert-danger" role="alert">'.
                $errors->first('id_categoria')."<br>".
                $errors->first('nombre')."<br>".
                $errors->first('costo')."<br>".
                $errors->first('precio')."<br>".
                $errors->first('talla')."<br>"
                .'</div>';
        }   
    @endphp
@endsection

@section('card-title')
    <div class="row">
        <div class="col">
            <h4>Lista de Productos</h4>
        </div>
        <div class="col text-end">
            <button type="button" class="btn btn-success" id="modalRegistroActualizacion" data-bs-toggle="modal" data-bs-target="#exampleModal">
                <i class="fas fa-plus"></i> Agregar Producto
            </button>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <form action="{{ route('buscar_producto') }}" method="POST" id="buscarformulario">
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
                  <th scope="col">Codigo_producto</th>
                  <th scope="col">Nombre del Producto</th>
                  <th scope="col">Costo [Bs]</th>
                  <th scope="col">Precio [Bs]</th>
                  <th scope="col">Talla</th>
                  <th scope="col">Categoria</th>
                  <th scope="col" id="descripcion">Descripcion</th>
                  <th scope="col">Estado</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($productos as $aux)
                  <tr>
                    <th scope="row">
                        <i class="fas fa-edit fa-xl i" style="color:#6BA9FA" onclick='editar(@php echo json_encode([
                            "id"=>$aux->id,
                            "codigo"=>$aux->codigo_producto,
                            "nombre"=>$aux->nombre,
                            "costo"=>$aux->costo,
                            "precio"=>$aux->precio,
                            "talla"=>$aux->talla,
                            "descripcion"=>$aux->descripcion,
                            "id_categoria"=>$aux->id_categoria,
                            "arrayCategorias" => $categorias,
                            ]); @endphp)'>
                        </i>
                        @php
                        $auxdata = json_encode([
                            "id"=>$aux->id,
                            "codigo"=>$aux->codigo_producto,
                            "nombre"=>$aux->nombre,
                            "costo"=>$aux->costo,
                            "precio"=>$aux->precio,
                            "talla"=>$aux->talla,
                            "descripcion"=>$aux->descripcion,
                            "estado"=>$aux->estado,
                            "id_categoria"=>$aux->id_categoria,
                            ]);
                        if ($aux->estado == 1) 
                        {
                            echo  '<i class="fas fa-trash-alt fa-xl" style="color:#FA746B" onclick=\'habilitarDesabilitar('.$auxdata.')\'></i>'; 
                        }else{
                            echo '<i class="fas fa-check-circle fa-xl" style="color:#FAAE43" onclick=\'habilitarDesabilitar('.$auxdata.')\'></i>';
                        }
                      @endphp

                    </th>
                    <th>{{$aux->codigo_producto}}</th>
                    <th>{{$aux->nombre}}</th>
                    <th>{{$aux->costo}}</th>
                    <th>{{$aux->precio}}</th>
                    <th>{{$aux->talla}}</th>
                    <th>{{$aux->nombre_categoria}}</th>
                    <th style="text-align: justify;">{{$aux->descripcion}}</th>
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
        {{ $productos->links() }}
    </div>

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Nuevo Producto</h5>
                <button type="button" class="btn-close cerrarModal" data-bs-dismiss="modal" aria-label="Close" onclick="resestablecerValoresModal()"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('nuevo_producto') }}" id="formularioRegistroActualizacion">
                        @csrf
                        @method('POST')
                        <div class="mb-3">
                            {{-- <select class="select2" name="category">
                                @foreach($categorias as $categoria)
                                <option value="{{ $categoria->id }}">{{ "$categoria->nombre - $categoria->talla - $categoria->precio" }}</option>
                                @endforeach 
                            </select> --}}
                             <label for="id_categoria_producto" class="form-label">Categoria:</label>
                             <select class="form-select" aria-label="Default select example" name="id_categoria" id="id_categoria_producto">
                                <option value="seleccionado" selected disabled>Seleccione una opcion...</option>
                                    @foreach ($categorias as $item)
                                       @if ($item->estado == 1)
                                          <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                                        @else
                                          <option value="{{ $item->id }}" disabled>{{ "$item->nombre (deshabilitado)" }}</option>
                                       @endif
                                    @endforeach
                             </select>
                        </div>
                        <div class="mb-3">
                          <label for="nombre_producto" class="form-label">Nombre:</label>
                          <input type="text" class="form-control" name="nombre" id="nombre_producto" aria-describedby="emailHelp" placeholder="Introduzca el Nombre del producto"> 
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="costo_producto" class="form-label">Costo:</label>
                                    <input type="number" class="form-control" name="costo" id="costo_producto" placeholder="Costo del Producto"> 
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="precio_producto" class="form-label">Precio:</label>
                                    <input type="number" class="form-control" name="precio" id="precio_producto" placeholder="Precio del Producto"> 
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="talla_producto" class="form-label">Talla:</label>
                            <input type="text" class="form-control" name="talla" id="talla_producto" aria-describedby="emailHelp" placeholder="Introduzca la Talla del Producto"> 
                        </div>
                        <div class="mb-3">
                            <label for="talla_producto" class="form-label">Descripcion:</label>
                            <textarea class="form-control" name="descripcion" id="descripcion_producto" rows="3" placeholder="Introduzca una Descripcion del Producto"></textarea>
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
        $("#exampleModalLabel").html("<h3>Nuevo Producto</h3>");
        $("#formularioTipoIngresoSalida").attr("action","{{ route('nuevo_producto') }}");
        $("#id_categoria_producto").val('seleccionado');
        $("#nombre_producto").val('');
        $("#costo_producto").val('');
        $("#precio_producto").val('');
        $("#talla_producto").val('');
        $("#descripcion_producto").val('');
        $("#btnGuardarActualizar").val("Guardar");     
    }

    function editar(item)
    {
        $("#exampleModal").modal("show");
        $("#exampleModalLabel").html("<h3>Editar Producto</h3>");
        $("#formularioRegistroActualizacion").attr("action","{{ route('actualizar_producto') }}");
        $("#formularioRegistroActualizacion").append('<input type="text" name="id" '+ 'value="'+ item.id +'"' +'hidden>');
        $("#id_categoria_producto").val(item.id_categoria);
        $("#nombre_producto").val(item.nombre);
        $("#costo_producto").val(item.costo);
        $("#precio_producto").val(item.precio);
        $("#talla_producto").val(item.talla);
        $("#descripcion_producto").val(item.descripcion);
        $("#btnGuardarActualizar").val("Actualizar");
        $("#btnGuardarActualizar").on('click',function(){
            $("#formularioRegistroActualizacion").submit();
            resestablecerValoresModal();
        });
    }

    function habilitarDesabilitar(item)
    {
        let mensaje = '';
        console.log(item);
        if(item.estado == 1){
            mensaje = 'Esta seguro de deshabilitar el Producto?';
        }else{
            mensaje = 'Esta seguro de habilitar el Producto?';
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
                        url: '/actualizar_estado_producto',
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
        // $('.select2').select2();
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
    
</script>
@endpush
