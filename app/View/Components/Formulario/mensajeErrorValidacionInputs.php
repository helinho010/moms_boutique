<?php

namespace App\View\Components\Formulario;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class mensajeErrorValidacionInputs extends Component
{
    public string $color;
    /**
     * Create a new component instance.
     */
    public function __construct(
        $color
    )
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.formulario.mensaje-error-validacion-inputs');
    }
}
