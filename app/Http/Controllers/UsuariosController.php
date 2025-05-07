<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\Sucursal;
use App\Models\User;
use App\Models\UserSucursal;
use App\Models\Usertype;
use App\Models\UsuarioEvento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Spatie\Permission\Models\Role;

class UsuariosController extends Controller
{
    public function index(Request $request)
    {
        $validacion = $request->validate([
            "buscar" => "string|nullable",
        ]);

        if (!isset($request->buscar)) {
            $usuarios = User::selectRaw('
                                        users.id as id_usuario,
                                        users.name as nombre_usuario,
                                        users.username as usuario,
                                        users.email as email_usuario,
                                        users.estado as estado_usuario,
                                        users.updated_at as updated_at_usuario
                                    ')
                            ->where('users.estado',1)
                            ->orderBy('users.updated_at','desc')
                            ->paginate(5); 
        }else{
           $usuarios = User::buscar($request->buscar)->withQueryString();
        }
        
        $roles = Role::all();

        $sucursales = Sucursal::where('activo',1)->get();

        $eventos = Evento::where('estado',1)
                         ->get();
        
        return view('usuario.UserOpcSuc', 
            [
                'usuarios' => $usuarios,
                'roles' => $roles,
                'sucursales' => $sucursales,
                'eventos' => $eventos,
            ]);
    }

    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'nombre_usuario' => 'required|string|max:255',
            'usuario' => 'required|string|max:100',
            'contrasenia' => 'required|string|min:8',
            'confirmar_contrasenia' => 'required|string|min:8|same:contrasenia',
            'correo' => 'required|email|max:255|unique:users,email',
            'tipo_usuario' => 'required|string|exists:roles,name',
            'sucursales_seleccionadas' => 'required|array|min:1',
        ]);
        
        try {
                $nuevoUsuario = User::create([
                    'name' => ucwords($request['nombre_usuario']),
                    'username' => strtolower($request['usuario']),
                    'email' => strtolower($request['correo']),
                    'password' => Hash::make($request['contrasenia']),
                ]);
        
                // Asignar rol al nuevo usuario
                $nuevoUsuario->assignRole($request->tipo_usuario);
        
                // Asignar sucursales al nuevo usuario
                if( count($request->sucursales_seleccionadas) > 0 ){
                    foreach ($request->sucursales_seleccionadas as $sucursal) {
                        UserSucursal::create([
                            'id_usuario' => $nuevoUsuario->id,
                            'id_sucursal' => $sucursal,
                            'estado' => 1
                        ]);
                    }
                }
                // Asignar eventos al nuevo usuario 
                if( isset($request->eventos_seleccionados) && count($request->eventos_seleccionados) > 0 ){
                    foreach ($request->eventos_seleccionados as $evento) {
                        UsuarioEvento::create([
                            'id_usuario' => $nuevoUsuario->id,
                            'id_evento' => $evento,
                            'estado' => 1
                        ]);
                    }
                }
        } catch (\Throwable $th) {
            return redirect()->route('home_usuarios')->with('usuarioNoCreado', $th->getMessage() . $th->getLine());
        }

        return redirect()->route('home_usuarios')->with('usuarioCreado', 'Usuario creado correctamente');
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
       if ($request->id_usuario != 1) 
       {
            $usuario = User::selectRaw('
                                    users.id as id_usuario,
                                    users.name as name_usuario,
                                    users.username as username_usuario,
                                    users.email as email_usario,
                                    users.estado as estado_usuario,
                                    users.created_at as created_at_usuario,
                                    users.updated_at as updated_at_usuario
                                ')
                            //->join('usertypes', 'usertypes.id', 'users.usertype_id')
                            ->where('users.id',$request->id_usuario)
                            ->get();

            $roles = Role::all();
            
            $sucursales = Sucursal::where('activo',1)->get();

            $sucursalesXUsuario = UserSucursal::where('id_usuario',$request->id_usuario)->get();

            $eventos = Evento::where('estado',1)
                             ->get();
                             
            $eventosXUsuario = UsuarioEvento::where('id_usuario',$request->id_usuario)
                                            ->where('estado',1)
                                            ->get();

            return view('usuario.edit',[
                'usuario' => $usuario,
                'roles' => $roles,
                'rolUsuario' => count(User::getUsersRoles($request->id_usuario)) > 0 ? User::getUsersRoles($request->id_usuario)[0] : '',
                'sucursales' => $sucursales,
                'sucursalXUsuario' => $sucursalesXUsuario,
                'eventos' => $eventos, 
                'eventosXUsuario' => $eventosXUsuario,
            ]);  
       }
       else{
          return redirect()->route('home_usuarios');
       }  
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'nombre_usuario' => 'required|string',
            'correo' => 'required',
            'tipo_usuario' => 'required|string|exists:roles,name',
            'contrasenia' => 'nullable|string|min:8',
            'confirmar_contrasenia' => 'nullable|string|min:8|same:contrasenia',
            'sucursales_seleccionadas' => 'required|array|min:1',
        ]);
        
        try {
                $usuarioBD = User::findOrFail($request->id_usuario);
                $usuarioBD->name = $request->nombre_usuario;
                $usuarioBD->email = $request->correo;
                $usuarioBD->syncRoles([$request->tipo_usuario]);
                
                if (!is_null($request->contrasenia) && !is_null($request->confirmar_contrasenia)) 
                {
                    $usuarioBD->password = Hash::make($request->contrasenia);   
                }

                $usuarioBD->save();

                //Sucursales
                UserSucursal::where('id_usuario',$request->id_usuario)
                            ->delete();
                if( count($request->sucursales_seleccionadas) > 0 ){
                    foreach ($request->sucursales_seleccionadas as $sucursal) {
                        UserSucursal::updateOrInsert(
                            ['id_usuario' => $request->id_usuario, 'id_sucursal' => $sucursal],
                            ['estado' => 1]
                        );
                    }
                }

                //Eventos
                UsuarioEvento::where('id_usuario',$request->id_usuario)
                            ->delete();
                if( isset($request->eventos_seleccionados) && count($request->eventos_seleccionados) > 0 ){
                    foreach ($request->eventos_seleccionados as $evento) {
                        UsuarioEvento::updateOrInsert(
                            ['id_usuario' => $request->id_usuario, 'id_evento' => $evento],
                            ['estado' => 1]
                        );
                    }
                }

        } catch (\Throwable $th) {
            
            return redirect()->route('home_usuarios')->with('usuarioNoEncontrado', $th->getMessage() . $th->getLine());
        }
        
        return redirect()->route('home_usuarios')->with('usuarioEditado', 'Usuario editado correctamente'); ;
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
