@props([
    'value' => "",
    'disabled' => false,
    "tipo",
    "name",
    "placeholder",
    "id"
])

<div>
    <div class="mb-3">
        <input type="{{ $tipo }}"
               name="{{ $name }}" 
               class="form-control" 
               id="{{ $id }}" 
               value="{{ $value }}"
               placeholder="{{ $placeholder }}"
               {{ $disabled == 'true' ? 'readonly' : '' }}
        >
    </div>
</div>