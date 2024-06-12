<div>
    <div class="row">
        <div class="col-md-5 col-sm-12">
            <div class="row">
                <div class="col-md-2">
                    <label for="selectorDetalleVenta" class="col-form-label">{{ $titleLabel }}: </label>
                </div>
                <div class="col-md-9">
                    <div>
                        <div class="input-group">
                            <select class="form-select" aria-describedby="" id="selectorDetalleVenta" wire:model='idSelector'>
                                <option value="seleccionado" disabled>Seleccione una opcion...</option>
                                    @foreach ($eventosOSucursales as $item)
                                       @if ($item->estado == 1)
                                          <option value="{{ $item->id }}">
                                            {{ $item->nombre}} 
                                            {{ $item->ciudad != "" ?  "-".$item->ciudad : "" }}
                                            {{ $item->direccion != "" ? "-".substr($item->direccion,0,30)."..." : "" }}
                                            {{ $item->fecha != "" ? "-".$item->fecha : "" }}
                                          </option>
                                        @else
                                          <option value="{{ $item->id_sucursal }}" disabled>
                                            {{ $item->nombre}} 
                                            {{ $item->ciudad != "" ?  "-".$item->ciudad : "" }}
                                            {{ $item->direccion != "" ? "-".substr($item->direccion,0,30)."..." : "" }}
                                            {{ $item->fecha != "" ? "-".$item->fecha : "" }}
                                          </option>
                                       @endif
                                    @endforeach
                             </select>
                             <button class="input-group-text" id="btnFormDataInventario" wire:click='buscarRegistrosDeVentas'>
                                <i class="fas fa-search"></i>
                             </button>
                        </div>
                        <div style="color: red ; font-size: 10px;">{{ $mensajeError }}</div>
                    </div>
                </div>
            </div>  
        </div>
        
        <div class="col-md-4">
            <div class="row">
                <div id="buscarformulario">
                    <div class="input-group flex-nowrap">
                        <input type="text" name="buscar" id="buscar" class="form-control" placeholder="Buscar..." aria-label="" aria-describedby="addon-wrapping" wire:model='buscarDetalleVenta'> 
                        <button class="input-group-text" id="inputBuscar" wire:click='buscarRegSucEnv'>
                            <i class="fas fa-search"></i>
                        </button><br>
                    </div>
                </div>
            </div>
            <div class="row">
                <span style="font-size: 10px; color:red" id="btnBuscarItem">{{ $mensajeErrorBuscar }}</span>
            </div>
        </div>
    </div>
    <br>
    <div class="row">
        <table class="table table-striped"> 
            <thead>
                <tr>
                  <th scope="col">Opciones</th>
                  {{-- <th scope="col">Num. Nota Venta</th> --}}
                  <th scope="col">Fecha de Venta</th>
                  <th scope="col">Total Venta</th>
                  <th scope="col">Descuento</th>
                  <th scope="col">Tipo de Pago</th>
                  <th scope="col">Usuario</th>
                  <th scope="col">Estado</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($ventas as $item)
                  <tr>
                    <th scope="row">
                        {{-- <a href="{{ route('editar_venta',[
                            "id"=>$item->id_venta,
                            "id_sucursal"=>$item->id_sucursal,
                            "id_tipo_pago"=>$item->id_tipo_pago,
                            "id_usuario"=>$item->id_usuario,
                            "descuento"=>$item->descuento_venta,
                            "total_venta,"=>$item->total_venta,
                            "efectivo_recivido"=>$item->efectivo_recibido_venta,
                            "cambio"=>$item->cambio_venta,
                            "estado"=>$item->estado_venta,
                            "fecha_venta"=>$item->updated_at_venta,
                            ]) }}">
                            <i class="fas fa-edit fa-xl i" style="color:#6BA9FA"></i></a> --}}
                        @php
                        $auxdata = json_encode([
                                "id"=>$item->id_venta,
                                "id_sucursal"=>$item->id_sucursal,
                                "id_tipo_pago"=>$item->id_tipo_pago,
                                "id_usuario"=>$item->id_usuario,
                                "descuento"=>$item->descuento_venta,
                                "total_venta,"=>$item->total_venta,
                                "efectivo_recivido"=>$item->efectivo_recibido_venta,
                                "cambio"=>$item->cambio_venta,
                                "estado"=>$item->estado_venta,
                                "fecha_venta"=>$item->updated_at_venta,
                            ]);
                        if ($item->estado_venta == 1) 
                        {
                            echo  '<i class="fas fa-trash-alt fa-xl" style="color:#FA746B" onclick=\'habilitarDesabilitar('.$auxdata.')\'></i>'; 
                        }else{
                            echo '<i class="fas fa-check-circle fa-xl" style="color:#FAAE43" onclick=\'habilitarDesabilitar('.$auxdata.')\'></i>';
                        }
                            echo '<i class="fas fa-file-pdf" style="color:#FC2631; font-size: 22px; padding-left: 8px;" onclick=\'exportPdf('.$auxdata.')\'></i>';
                       @endphp
                       
                    </th>
                    {{-- <th>{{"00001"}}</th> --}}
                    <th>{{"$item->updated_at_venta"}}</th>
                    <th>{{"$item->total_venta"}} Bs</th>
                    <th>{{"$item->descuento_venta"}}%</th>
                    <th>{{$item->tipo_pagos}}</th>
                    <th>{{"$item->nombre_users"}}</th>
                    <td> 
                        @if ( $item->estado_venta == 1 )
                            <span class="badge bg-success">Activo</span>    
                        @else
                            <span class="badge bg-warning">Inactivo</span>    
                        @endif
                    </td>
                  </tr>    
                @endforeach
              </tbody>
        </table>
        {{ $ventas->links() }}
    </div>
</div>

