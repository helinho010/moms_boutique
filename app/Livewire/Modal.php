<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;

class Modal extends Component
{
    public String $tituloModal;
    public String $imagenUsuario = 'sinimagen.png';
    public String $nombreUsuario;
    public String $usuario;
    public String $correoUsuario;
    public $rolUsuario;
    public Array $datos = [];

    public function mount()
    {
        $usuarioLogin = User::selectRaw('
                                        users.name as nombre,
                                        users.username as nombre_usuario,
                                        users.email as correo_usuario
                                        ')
                            // ->join('usertypes', 'usertypes.id', 'users.usertype_id')
                            ->where('users.id', auth()->user()->id)
                            ->get();

        $this->datos = $usuarioLogin->toArray();

        $this->imagenUsuario = 'sinimagen.png'; //$usuarioLogin->imagen != "" ? $usuarioLogin->imagen : 'sinimagen.png';
        $this->nombreUsuario = $usuarioLogin[0]->nombre;
        $this->usuario = $usuarioLogin[0]->nombre_usuario;
        $this->correoUsuario = $usuarioLogin[0]->correo_usuario;

        $rolUsuario = User::find(auth()->user()->id);
        $this->rolUsuario = $rolUsuario->getRoleNames();
    }


    public function render()
    {
        return view('livewire.modal');
    }
}
