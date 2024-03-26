<?php

namespace App\Http\Controllers;

use App\Models\Sucursal;
use App\Models\User;
use App\Models\UserSucursal;
use App\Models\Usertype;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuariosController extends Controller
{
    public function index()
    {
        // dd(auth()->user()->usertype_id);
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
                        ->orderBy('users.updated_at','desc')
                        ->paginate(10);
               
        $roles = Usertype::where('estado',1)
                         ->get();

        $sucursales = Sucursal::where('activo',1)->get();
        
        $sucursalesHabilitadasUsuario = UserSucursal::selectRaw('
                                                                    user_sucursals.id as id_user_sucursals,
                                                                    user_sucursals.estado as estado_user_sucursals,
                                                                    user_sucursals.updated_at as updated_at_user_sucursals,
                                                                    sucursals.id as id_sucursal,
                                                                    sucursals.razon_social as razon_social_sucursal,
                                                                    sucursals.ciudad as ciudad_sucursal,
                                                                    sucursals.activo as estado_sucursal,
                                                                    sucursals.direccion as direccion_sucursal,
                                                                    users.id as id_usuario,
                                                                    users.name as nombre_usuario,
                                                                    users.username as usuario,
                                                                    users.estado as estado_usuario
                                                                    ')
                                                        ->join('sucursals', 'sucursals.id', 'user_sucursals.id_sucursal')
                                                        ->join('users', 'users.id', 'user_sucursals.id_usuario')
                                                        ->where('sucursals.activo',1)
                                                        ->get();
        
        return view('UsertypeOpc', 
            [
                'usuarios' => $usuarios,
                'roles' => $roles,
                'sucursales' => $sucursales,
                'sucursales_habilitadas' => $sucursalesHabilitadasUsuario,
            ]);
    }

    public function create(Request $request)
    {
        //  dd($request);   
        $validatedData = $request->validate([
            'nombre_usuario' => 'required|string|max:255',
            'usuario' => 'required|string|max:100',
            'contrasenia' => 'required|string',
            'confirmar_contrasenia' => 'required|string',
            'correo' => 'required|email|max:255',
            'tipo_usuario' => 'required|numeric|gt:0',
            // 'sucursales_seleccionadas' => 'required|array|min:1',
        ]);
        
        $newUsuario = User::create([
            'name' => ucwords($request['nombre_usuario']),
            'username' => strtolower($request['usuario']),
            'email' => strtolower($request['correo']),
            'usertype_id' => $request['tipo_usuario'],
            'password' => Hash::make($request['contrasenia']),
        ]);

        if ($request->tipo_usuario == 1) 
        {
            $allSucursales = Sucursal::where('activo',1)->get();
            foreach ($allSucursales as $key => $sucursal) 
            {
                UserSucursal::create([
                    'id_usuario' => $newUsuario->id,
                    'id_sucursal' => $sucursal->id,
                ]);
            }    
        }else{
            foreach ($request->sucursales_seleccionadas as $key => $sucursal) 
            {
                UserSucursal::create([
                    'id_usuario' => $newUsuario->id,
                    'id_sucursal' => $sucursal,
                ]);
            }
        }
        return redirect()->route('home_usuarios');
    }


    /**
     * Return 0 = no existe usuario
     * Return 1 = Si existe usuario
     * Return 3 = No se envio el usuario
     */
    public function consulta(Request $request)
    {
        if(isset($request->usuario))
        {
            $existeUsuario = User::whereRaw('LOWER(`username`) LIKE ? ',['%'.strtolower($request->usuario).'%'])
                                 ->get();
            if($existeUsuario->count() > 0)
            {
                return 1;
            }else{
                return 0;
            }

        }else{
            return 3;
        }
    }

    public function editar(Request $request)
    {
       return $request;     
    }

    public function update_estado(Request $request)
    {
        switch ($request->estado) 
        {
            case 0:
                $User = User::where("id",$request->id)->first();
                $User->estado = 1;
            break;

            case 1:
                $User = User::where("id",$request->id)->first();
                $User->estado = 0;
            break;
            
            default:
                
            break;
        }
        $User->save();
    }
}
