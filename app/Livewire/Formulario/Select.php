<?php

namespace App\Livewire\Formulario;

use Livewire\Component;
use Spatie\Permission\Models\Role as ModelsRole;

class Select extends Component
{
    public string $id_select;
    public string $name_select;
    public $roles;

    public function mount($id_select, $name_select)
    {
        $this->id_select = $id_select;
        $this->name_select = $name_select;
        $this->roles = ModelsRole::all()->pluck('name', 'id')->toArray();
    }

    public function actualizarRoles()
    {
        $this->roles = ModelsRole::all()->pluck('name', 'id')->toArray();
    }
    
    public function render()
    {
        return view('livewire.formulario.select');
    }
}
