<div>
    <select class="form-select" 
            aria-label="Default select example"
            id="{{ $id }}"
            name="{{ $name }}"
            {{ $attributes }}
    >
        {{$slot}}
    </select>
</div>