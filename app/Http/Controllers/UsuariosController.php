<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Usertype;
use Illuminate\Http\Request;

class UsuariosController extends Controller
{
    public function index()
    {
        $roles = Usertype::where('estado',1)->paginate(10);
        $usuarios = User::where('estado',1)->paginate(10);
        return view('UsertypeOpc', 
            [
                'roles'=>$roles,
                'usuarios'=>$usuarios,
                'opciones_habilitadas'=>'a,b,c,d',
            ]);
    }
}
