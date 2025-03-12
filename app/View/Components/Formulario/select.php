<?php

namespace App\View\Components\Formulario;

use App\Models\Sucursal;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class select extends Component
{

    /**
     * Create a new component instance.
     */
    public function __construct( 
        // public $sucursales,
        public string $id,
        public string $name
    )
    {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.formulario.select');
    }
}
