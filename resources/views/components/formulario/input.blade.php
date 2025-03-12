@props([
    'value' => " ",
    'disabled' => false,
])

<div>
    <div class="mb-3">
        <input type="{{ $attributes->get('tipo') }}"
               name="{{ $attributes->get('name') }}" 
               class="form-control" 
               id="{{ $attributes->get('id') }}" 
               value="{{ $value }}"
               placeholder="{{ $attributes->get('placeholder') }}"
               {{ $disabled == 'true' ? 'readonly' : '' }}
        >
    </div>
</div>