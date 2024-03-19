<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Usertype;
use Illuminate\Http\Request;

class UsuariosController extends Controller
{
    public function index()
    {
        
        $usuarios = User::selectRaw('
                                    users.id as id_usuario,
                                    users.name as nombre_usuario,
                                    users.username as usuario,
                                    users.email as email_usuario,
                                    users.estado as estado_usuario,
                                    users.updated_at as updated_at_usuario,
                                    usertypes.id as id_tipo_usuario,
                                    usertypes.`type` as tipo_usuario
                                    ')
                        ->join('usertypes', 'usertypes.id', 'users.usertype_id')
                        // ->where('estado',1)
                        ->paginate(10);
               
        $roles = Usertype::where('estado',1)
                         ->get();
        
        return view('UsertypeOpc', 
            [
                'usuarios'=>$usuarios,
                'roles'=>$roles,
                'opciones_habilitadas'=>'a,b,c,d',
            ]);
    }
}
