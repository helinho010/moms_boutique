@props([
    'cols' => "30",
    'rows' => "3",
    "name",
    "id",
    "placeholder",
])

<div>
    <textarea class="form-control" name="{{ $name }}" id="{{ $id }}" 
              cols="{{ $cols }}" rows="{{ $rows }}" 
              placeholder="{{ $placeholder }}"
    >
        {{ $slot }}
    </textarea>
</div>