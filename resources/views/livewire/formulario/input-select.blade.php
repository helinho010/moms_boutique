<div>
    
    <div class="col-md-12">
        <input type="text" name="{{ $nombreInput }}" 
               id="{{ $identificadorInput }}" class="input-select" 
               wire:model.live="textoValue"
               wire:click = 'focoinput'
               placeholder="{{ $placeholder }}"
        >
        <div class="div-select-items item-posicion-absoluta" {{ $oculto }}>
            <ul class="list-group">
                @foreach ($items as $item)
                <li class="list-group-item item-seleccionado" wire:click="itemSeleccionado">
                    {{ $loop->iteration }} | 
                    Producto: {{ $item->nombre_productos }} - 
                    Talla: {{ $item->talla_productos != "" ? $item->talla_productos:"ST(Sin Tall)"}} - 
                    Precio Venta: {{ $item->precio_productos }}
                    @can('costo producto')
                         - Costo Producto: {{ $item->costo_productos }}
                    @endcan
                </li>
                @endforeach
            </ul>
        </div>
    </div>
    
    <style>
        .input-select, .div-select-items{
            border: solid 1px silver ;
            height: 2.5em;
            border-radius: 5px;
        }
        
        .input-select{
            width: 100%;
            position: relative;
        }

        .div-select-items{
            height: 15em;
            font-size: 0.9em;
            z-index: 999;
            overflow-y: scroll;
            background: white;
            width: 63%
        }

        .item-posicion-absoluta{
            position: absolute;
            
        }

        .item-seleccionado:hover{
            background: silver;
            color: white;
            font-weight: bold;
            font-size: 1em;
        }

        .input-select:focus{
            border: solid 1px red;
        }
    </style>
</div>


@script
<script>
    $wire.on('selectText', (data) => {
            console.log(data.identificadorInput);
            var input = document.getElementById("{{ $identificadorInput }}");
            input.select();  // Seleccionar el contenido del input
    });
</script>
@endscript