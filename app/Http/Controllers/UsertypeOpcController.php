<?php

namespace App\Http\Controllers;

use App\Models\OpcionesSistema;
use App\Models\Usertype;
use App\Models\UsertypeOpc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Svg\Tag\Rect;

class UsertypeOpcController extends Controller
{
    public function index(Request $request)
    {
        $validar = $request->validate([
            'buscar' => 'string|nullable',
        ]);

        if (isset($request->buscar)) {
            $roles = Role::where('name', 'like', '%' . $request->buscar . '%')
                         ->orderBy("updated_at", "asc")
                         ->paginate(5)->withQueryString();
        }else{
            $roles = Role::select(['id', 'name', 'updated_at'])
                         ->orderBy("updated_at", "asc")                
                         ->paginate(5);

        }
        
        $permisos = Permission::select(['id', 'name'])
                        ->orderBy("updated_at", "asc")
                        ->get();
                                  
        return view('roles.UsertypeOpc',[
            "roles" => $roles,
            "permisos" => $permisos,
        ]);
    }

    public function buscar(Request $request)
    {
        $roles = collect();
        $opcionesHabilitadas = collect();

        if($request->buscar != '')
        {
            $roles = Usertype::orderBy("updated_at","desc")
                             ->where('estado',1)
                             ->where(DB::raw('usertypes.`type`'), 'like', '%' . $request->buscar . '%')
                             ->paginate(10);
            
            $opcionesHabilitadas = UsertypeOpc::selectRaw('
                                                        usertype_opcs.id as id_usertype_opcs,
                                                        usertype_opcs.estado as estado_usertype_opcs,
                                                        usertypes.id as id_usertypes,
                                                        usertypes.`type` as tipo_usertypes,
                                                        usertypes.estado as estado_usertypes,
                                                        opciones_sistemas.id as id_opciones_sistemas,
                                                        opciones_sistemas.opcion as opcion_opciones_sistemas,
                                                        opciones_sistemas.icono as icono_opciones_sistemas,
                                                        opciones_sistemas.estado as estado_opciones_sistemas
                                                    ')
                                           ->join('usertypes', 'usertypes.id', 'usertype_opcs.id_tipo_usuario')
                                           ->join('opciones_sistemas', 'opciones_sistemas.id', 'usertype_opcs.id_opcion_sistema')
                                           ->where('usertypes.id',$roles[0]->id)
                                           ->get();
      
        }else{
            return redirect()->route('home_rol_usuarios');
        }
        
        $opciones = OpcionesSistema::where('estado',1)
                                    ->get();
                            
        return view('roles.UsertypeOpc',[
            "roles" => $roles,
            "opciones" => $opciones,
            "opciones_habilitadas" => $opcionesHabilitadas,
        ]);

    }

    /**
     * Retorna los siguientes valores:
     * 0 = No existe ese Rol
     * 1 = Si existe ese Rol
     * 3 = No se envio parametro Rol (nombre de rol)
     */

    public function consultaRol(Request $request)
    {
        if(isset($request->rol))
        {
            $existeRol = Usertype::whereRaw('LOWER(`type`) LIKE ? ',[trim(strtolower($request->rol)).'%'])
                             ->get();
            if($existeRol->count() > 0)
            {
                return 1;
            }else{
                return 0;
            }

        }else{
            return 3;
        }
    }

    public function store(Request $request)
    {
        $validar = $request->validate([
            'nuevo_rol' => 'required|string|max:50',
            'permisos_rol' => 'required|array',
        ]);

        try {
             $nuevoRol = Role::create(['name' => strtolower($request->nuevo_rol)]);
             $nuevoRol->syncPermissions($request->permisos_rol);
             return redirect()->route('home_rol_usuarios')->with('nuevoRolCreado', 'Rol creado correctamente');
        } catch (\Throwable $th) {
            return redirect()->route('home_rol_usuarios')->with('errorNuevoRolCreado', 'Error al crear el rol');
        }        
    }

    public function editar($id_rol)
    {
        if (gettype(intval($id_rol)) == 'integer' && $id_rol == 1){
            
            return redirect()->route('home_rol_usuarios')->with('errorEditarRolSuperAdministrador', 'No se puede editar el rol de Super Administrador');
        }

        if ( gettype(intval($id_rol)) == 'integer'){
            $rol = Role::where('id',$id_rol)->first();
            $permisos = Permission::select(['id', 'name'])
                            ->orderBy("updated_at", "asc")
                            ->get();
        }else{
            return redirect()->route('home_rol_usuarios')->with('errorParametroEnviadoEditarRol', 'Error al enviar los parametros para editar el rol');
        }

        return view('roles.editar',[
            'rol' => $rol,
            'permisos' => $permisos, 
        ]);
    }

    public function update(Request $request)
    {
        $validar = $request->validate([
            'nombre_rol' => 'required|string|max:50|exists:roles,name',
            'permisos_rol' => 'required|array',
            'permisos_rol.*' => 'exists:permissions,name'
        ]);

        try 
        {
            $rol = Role::where('name',$request->nombre_rol)->first();
            $rol->syncPermissions($request->permisos_rol);
            return redirect()->route('home_rol_usuarios')->with('rolEditado', 'Rol editado correctamente');
            
        } catch (\Throwable $th) {
            return redirect()->route('home_rol_usuarios')->with('errorRolEditado', 'Error al editar el rol');
        } 
    }


    public function actualizarEstadoRol(Request $request)
    {
        $mensaje = '';
        $respuesta = false;
        switch ($request->estado) {
            case 0:
                $rol = Usertype::where('id',$request->id)->first();
                $rol->estado = 1;
                $rol->save();
                $mensaje = 'Rol deshabilitado';
                $respuesta = true;
            break;
            
            case 1:
                $rol = Usertype::where('id',$request->id)->first();
                $rol->estado = 0;
                $rol->save();
                $mensaje = 'Rol Habilitado';
                $respuesta = true;
            break;
            
            default:
                $mensaje = 'Error en el estado del rol (al momento del envio)';
            break;
        }
        return ['mensaje'=>$mensaje, 'respuesta'=>$respuesta];
    }

    public function eliminarRol(Request $request){
        
        $validar = $request->validate([
            'nombre_rol' => 'required|string|exists:roles,name',
        ]);

        try {
                if(strtolower($request->nombre_rol) == 'super administrador')
                {
                    return redirect()->route('home_rol_usuarios')->with('errorEliminarRolSuperAdministrador', 'No se puede eliminar el rol de Super Administrador');
                }
                $rol = Role::where('name',$request->nombre_rol)->first();
                $rol->delete();
                return redirect()->route('home_rol_usuarios')->with('rolEliminado', 'Rol eliminado correctamente');
        } catch (\Throwable $th) {
            return redirect()->route('home_rol_usuarios')->with('errorRolEliminado', 'Error al eliminar el rol');
        }

    }

    public function CrearRol(Request $request)
    {
        $validar = $request->validate([
            'rol' => 'required|string|max:50',
        ]);

        try {
              
              $nuevoRol = Role::create(['name' => strtolower($request->rol)]);
              return ['mensaje'=> 'Rol creado correctamente', 'estado'=> 0];
              
        } catch (\Throwable $th) {
            return ['mensaje'=> "El rol: $request->rol ya existe en la base de datos", 'estado'=> 1];       
        }
    }

    public function crearPermiso(Request $request)
    {
        $validar = $request->validate([
            'nombre_permiso' => 'required|string|max:50',
        ]);

        try {
              $nuevoPermiso = Permission::create(['name' => strtolower($request->nombre_permiso)]);
              return redirect()->route('home_rol_usuarios')->with('nuevoPermisoCreado', 'Permiso creado correctamente');
              
        } catch (\Throwable $th) {
            return redirect()->route('home_rol_usuarios')->with('errorNuevoPermisoCreado', "El permiso: $request->nombre_permiso ya existe en nuestos registros");
        }
    }
}
