@props([
    'cols' => "30",
    'rows' => "3"
])

<div>
    <textarea class="form-control" name="{{ $attributes->get('name') }}" id="{{ $attributes->get('id') }}" 
              cols="{{ $cols }}" rows="{{ $rows }}" 
              placeholder="{{ $attributes->get('placeholder') }}"
    >{{ $slot }}</textarea>
</div>