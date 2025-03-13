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
                  <th scope="col">Num. Factura</th>
                  <th scope="col">Usuario</th>
                  <th scope="col">Estado</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($ventas as $item)
                  <tr>
                    <th scope="row">
                        @if ($item->estado_venta == 1)
                            <i class="fas fa-trash-alt fa-xl" style="color:#FA746B; font-size: 18px; margin-right: 7px;" 
                            wire:click='habilitarEliminar({{ $item->id_venta }}, {{$item->estado_venta}})'
                            wire:confirm='Esta seguro de deshabilitar la venta?'
                            ></i>
                        @else
                            <i class="fas fa-check-circle fa-xl" style="color:#FAAE43; font-size: 18px; margin-right: 7px;" 
                            wire:click='habilitarEliminar({{ $item->id_venta }}, {{$item->estado_venta}})'
                            wire:confirm='Esta seguro de habilitar la venta?'
                            ></i>
                        @endif
                            <i class="fas fa-file-pdf" style="color:rgb(190, 43, 43); font-size: 18px; margin-right: 7px;" 
                            wire:click='exportarPdf({{ $item->id_venta }})'
                            ></i>
                    </th>
                    {{-- <th>{{"00001"}}</th> --}}
                    <th>{{"$item->updated_at_venta"}}</th>
                    <th>{{"$item->total_venta"}} Bs.</th>
                    <th>{{ number_format($item->descuento_venta,2) }} Bs.</th>
                    <th>{{$item->tipo_pagos}}</th>
                    <th class="text-center">{{$item->numero_factura_venta === null ? '-':$item->numero_factura_venta}}</th>
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

