@props([
    'cols' => "30",
    'rows' => "3",
    "name",
    "id",
    "placeholder",
])
<div>
    <textarea name="{{ $name }}" id="{{ $id }}" 
              cols="{{ $cols }}" rows="{{ $rows }}" 
              placeholder="{{ $placeholder }}"
              {{ $attributes->merge(['class' => 'form-control']) }}
    >
        {{ $slot }}
    </textarea>
</div>