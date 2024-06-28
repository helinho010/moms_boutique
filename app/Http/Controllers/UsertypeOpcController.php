<?php

namespace App\Http\Controllers;

use App\Models\OpcionesSistema;
use App\Models\Usertype;
use App\Models\UsertypeOpc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UsertypeOpcController extends Controller
{
    public function index()
    {
        $roles = Usertype::orderBy("updated_at","desc")
                         ->paginate(10);
        
        $opciones = OpcionesSistema::where('estado',1)
                                    ->get();

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
                                           ->get();
                                  
        return view('roles.UsertypeOpc',[
            "roles" => $roles,
            "opciones" => $opciones,
            "opciones_habilitadas" => $opcionesHabilitadas,
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
        $nuevoRol = new Usertype();
        $nuevoRol->type = $request->nuevo_rol;
        $nuevoRol->save();

        foreach ($request->opciones_seleccionadas as $key => $value) {
            $nuevaAsigancionRolOpcionesSis = new UsertypeOpc();
            $nuevaAsigancionRolOpcionesSis->id_tipo_usuario = $nuevoRol->id;    
            $nuevaAsigancionRolOpcionesSis->id_opcion_sistema = $value;
            $nuevaAsigancionRolOpcionesSis->save();
        }
        
        return redirect()->route('home_rol_usuarios');
    }

    public function editar(Request $request)
    {
        $rol = Usertype::where('id',$request->id)->get();
        
        $opciones = OpcionesSistema::where('estado',1)
                                    ->get();

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
                                           ->where('usertypes.id',$request->id)
                                           ->get();


        return view('roles.editar',[
            'rol'=>$rol,
            'opciones' => $opciones,
            'opciones_habilitadas' => $opcionesHabilitadas,
        ]);
    }

    public function update(Request $request)
    {
        // dd($request->opciones_seleccionadas);
        try 
        {
            $rol = Usertype::find($request->id_rol);
            $rol->type = $request->nombre_rol;
            $rol->save();

            $opcionesHabilitadas = UsertypeOpc::where('id_tipo_usuario',$request->id_rol);
            $opcionesHabilitadas->delete();

            foreach ($request->opciones_seleccionadas as $key => $value) {
               $newUserTypeOpcion = new UsertypeOpc();
               $newUserTypeOpcion->id_tipo_usuario = $request->id_rol;
               $newUserTypeOpcion->id_opcion_sistema = $value;
               $newUserTypeOpcion->save();
            }

        } catch (\Throwable $th) {
            //throw $th;
        }
        return redirect()->route('home_rol_usuarios');
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


}
