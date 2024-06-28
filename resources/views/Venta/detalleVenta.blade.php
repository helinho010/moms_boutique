@extends('layouts.plantillabase')

@section('title','Ventas')

@section('css')
    <style>
        table > tbody > tr > th > i{
            font-size: 20px;
        }
        .estado{
            font-size: 12px;
        }
        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 34px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked + .slider {
            background-color: #2196F3;
        }

        input:checked + .slider:before {
            transform: translateX(26px);
        }
    </style>
@endsection

@section('h-title')
@endsection

@section('card-title')
    <div class="row">
        <div class="col">
            <h4>Detalle de Ventas</h4>
        </div>

        @livewire('boton-on-of', ['estado' => false])

        {{-- <div class="col text-end">
            <button type="button" class="btn btn-success" id="btnModalRegistroActualizacion" data-bs-toggle="modal" data-bs-target="#exampleModal" disabled>
                <i class="fas fa-plus"></i> Agregar Iten al Inventario
            </button><br>
            <span style="font-size: 10px; color:red" id="reqBtnAgregarItem">(*) Seleccionar una Sucursal</span>
        </div> --}}
    </div>
@endsection

@section('content')
    
    {{-- <div class="row">
        <div class="col-md-5">
            <div class="row">
                <div class="col-md-2">
                    <label for="inputPassword6" class="col-form-label">Sucursal: </label>
                </div>
                <div class="col-md-8">
                    <form action="{{ route('buscar_detalle_venta') }}" method="POST" id="dataformDetalleVenta">
                        @method('POST')
                        @csrf
                        <div class="input-group">
                            <select class="form-select" aria-describedby="" name="id_sucursal" id="select_sucursal">
                                <option value="seleccionado" @if (!isset($id_sucursal_seleccionado)) selected  @endif disabled>Seleccione una opcion...</option>
                                    @foreach ($sucursales as $item)
                                       @if ($item->estado_sucursal == 1)
                                          <option value="{{ $item->id_sucursal }}" 
                                            @if (isset($id_sucursal_seleccionado) && $item->id_sucursal_user_sucursal == $id_sucursal_seleccionado ) 
                                                selected  
                                            @endif>
                                            {{ "$item->razon_social_sucursal - $item->ciudad_sucursal - ".substr($item->direccion_sucursal,0,40)."..." }}
                                          </option>
                                        @else
                                          <option value="{{ $item->id_sucursal }}" disabled>
                                            {{ "$item->razon_social - $item->ciudad_sucursal - ".substr($item->direccion,0,30)."... (deshabilitado)" }}
                                          </option>
                                       @endif
                                    @endforeach
                             </select>
                             <button class="input-group-text" id="btnFormDataInventario"><i class="fas fa-search"></i></button>
                        </div>
                    </form>
                </div>
            </div>  
        </div>
        
        <div class="col-md-4">
            <div class="row">
                <form action="{{ route('buscar_inventario_interno') }}" method="POST" id="buscarformulario">
                    @method('POST')
                    @csrf
                    <div class="input-group flex-nowrap">
                        <input type="text" name="buscar" id="buscar" class="form-control" placeholder="Buscar..." aria-label="" aria-describedby="addon-wrapping">
                        <input type="text" name="id_sucursal" id="id_sucursal" hidden>
                        <button class="input-group-text" id="inputBuscar" disabled><i class="fas fa-search"></i></button><br>
                    </div>
                </form>
            </div>
            <div class="row">
                <span style="font-size: 10px; color:red" id="btnBuscarItem">(*) Seleccionar una Sucursal</span>
            </div>
        </div>
    </div> --}}

    <br>
    @livewire('detalle-venta')
    
        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Editar Venta: </h5>
                <button type="button" class="btn-close cerrarModal" data-bs-dismiss="modal" aria-label="Close" onclick="resestablecerValoresModal()"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('nuevo_inventario_interno') }}" id="modalFormularioRegistroActualizacion"> 
                      @csrf
                      @method('POST')
                      <div class="row">
                        <div class="col-md-2">
                            <label for="inputPassword6" class="col-form-label">Sucursal: </label>
                        </div>
                        <div class="col-md-8">
                                <div class="input-group">
                                    <select class="form-select" aria-describedby="" name="id_sucursal" id="modalSelectSucursal">
                                        <option value="seleccionado" @if (!isset($id_sucursal)) selected  @endif disabled>Seleccione una opcion...</option>
                                            @foreach ($sucursalesModal as $item)
                                               @if ($item->estado_sucursal == 1)
                                                  <option value="{{ $item->id_sucursal_user_sucursal }}" @if (isset($id_sucursal) && $item->id_sucursal_user_sucursal == $id_sucursal ) selected  @endif>{{ "$item->razon_social_sucursal - $item->ciudad_sucursal - ".substr($item->direccion_sucursal,0,40)."..." }}</option>
                                                @else
                                                  <option value="{{ $item->id_sucursal_user_sucursal }}" disabled>{{ "$item->razon_social_sucursal - $item->ciudad_sucursal - ".substr($item->direccion_sucursal,0,30)."... (deshabilitado)" }}</option>
                                               @endif
                                            @endforeach
                                     </select>
                                </div>
                        </div>
                      </div>
                      <br>
                      @isset($productos)
                        <div class="row">
                            <div class="col-md-2">
                                <label for="inputPassword6" class="col-form-label">Producto: </label>
                            </div>
                            <div class="col-md-8">
                                    <div class="input-group">
                                        <select class="form-select" aria-describedby="" name="id_producto" id="modalSelectProducto">
                                            <option value="seleccionado" selected disabled>Seleccione una opcion...</option>
                                                @foreach ($productos as $item)
                                                @if ($item->estado == 1)
                                                    <option value="{{ $item->id }}">{{ "$item->nombre - $item->talla - $item->precio" }}</option>
                                                    @else
                                                    <option value="{{ $item->id }}" disabled>{{ "$item->nombre - $item->talla - $item->precio (deshabilitado)" }}</option>
                                                @endif
                                                @endforeach
                                        </select>
                                    </div>
                            </div>
                        </div>
                      @endisset
                      <br>
                      @isset($tiposIngresosSalidas)
                        <div class="row">
                            <div class="col-md-2">
                                <label for="inputPassword6" class="col-form-label">Tipo de Ingreo: </label>
                            </div>
                            <div class="col-md-8">
                                    <div class="input-group">
                                        <select class="form-select" aria-describedby="" name="id_tipo_ingreso_salida" id="modalSelectTipoEntrada">
                                            <option value="seleccionado" selected disabled>Seleccione una opcion...</option>
                                                @foreach ($tiposIngresosSalidas as $item)
                                                @if ($item->estado == 1)
                                                    <option value="{{ $item->id }}">{{ "$item->tipo"}}</option>
                                                    @else
                                                    <option value="{{ $item->id }}" disabled>{{ "$item->tipo (deshabilitado)"}}</option>
                                                @endif
                                                @endforeach
                                        </select>
                                    </div>
                            </div>
                        </div> 
                      @endisset
                      <br>     
                      <div class="row">
                        <div class="col-md-2">
                            <label for="exampleInputPassword1" class="form-label">Cantidad Ingreso: </label>
                        </div>
                        <div class="col-md-8">
                            <input type="number" name="cantidad_ingreso" placeholder="0" class="form-control" id="modalInputCantidadIngreso">
                        </div>
                      </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger cerrarModal" data-bs-dismiss="modal" onclick="resestablecerValoresModal()">Cerrar</button>
                    <button type="button" class="btn btn-success" id="modalBtnGuardarActualizar">Guardar</button>
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

    function editar(item)
    {
        $("#exampleModal").modal("show");

        // $("#exampleModalLabel").html("<h3>Editar Evento</h3>");
        // $("#formularioRegistroActualizacion").attr("action","{{ route('actualizar_evento') }}");
        // $("#formularioRegistroActualizacion").append('<input type="text" name="id" '+ 'value="'+ item.id +'"' +'hidden>');
        // $("#nombre_evento").val(item.nombre);
        // $("#fecha_evento").val(item.fecha_evento);
        // $("#btnGuardarActualizar").val("Actualizar");
        // $("#btnGuardarActualizar").on('click',function(){
        //     $("#formularioRegistroActualizacion").submit();
        //     resestablecerValoresModal();
        // });
    }

    function habilitarDesabilitar(venta)
    {
        // alert("Estamos en el lugar correcto");
        let mensaje = '';
        if(venta.estado == 1){
            mensaje = 'Esta seguro de deshabilitar la venta?';
        }else{
            mensaje = 'Esta seguro de habilitar la venta?';
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
                        url: "{{ route('actualizar_estado_detalle_venta') }}",
                        data: {
                               "id":venta.id, 
                               "estado":venta.estado
                              },
                        success: function (response) {
                          if (response.estado == 1) 
                          {
                            Swal.fire("Cambio Guardado!", "", "success");        
                            location.reload();  
                          } else {
                            Swal.fire("Hubo un error, " + response.mensaje, "", "error");        
                          }
                          
                        }
                    });
                } else if (result.isDenied) {
                    // Swal.fire("Changes are not saved", "", "info");
                }
            });
    }

    function exportPdf(venta)
    {
        $.ajax({
            type: "POST",
            url: "{{ route('reimprimir_pdf') }}",
            data: {
                    "id":venta.id, 
                  },
            success: function (response) {
                if (response[0].nombre_pdf == '') 
                {
                    console.log('no tiene nombre');
                } else {
                    console.log(response[0].nombre_pdf);    
                    window.open('/'+response[0].nombre_pdf, '_blank');
                }     
            }
        });
    }

    $(document).ready(function(){
        $("#home").removeClass('active');
        $("#venta").addClass('active');
    });

</script>
@endpush

