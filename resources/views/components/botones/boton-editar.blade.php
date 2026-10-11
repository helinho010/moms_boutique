<div>
    <button type="button" 
        {{ $attributes->merge(['class' => 'btn btn-' . $color . ' btn-' . $tamanio]) }}
        title="{{ $nombreTitle }}"
    >
        <i class="{{ $nombreIcono }}"></i>
    </button>
</div>