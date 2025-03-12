<?php

namespace App\View\Components\Formulario;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class input extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $tipo,
        public string $name,
        public string $id,
        // public string $value,
        // public $disabled,
        public string $placeholder,
    )
    {
        //
    }

    function fechaHoy()
    {
        return date("d/m/Y");
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.formulario.input');
    }
}
