<div>
    <button class="input-group-text" 
            id="boton-buscar" 
            type="button" 
            data-bs-toggle="modal" 
            data-bs-target="#modalComponentstaticBackdrop">
        {{ $slot }}
    </button>
</div>

<style>
    #boton-buscar{
        height: 100%;
        color: red;
    }
</style>