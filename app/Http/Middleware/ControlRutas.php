<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\UsertypeOpc;
use Illuminate\Support\Facades\Auth;

class ControlRutas
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // dd($request);

        if ( Auth::user()->usertype_id == 1)
        {
            return $next($request);    
        }else{
            $opcionesHabilitadas = UsertypeOpc::selectRaw('
                                                        usertypes.id as id_tipo_usuario,
                                                        usertypes.`type` as tipo_usuario,
                                                        opciones_sistemas.opcion as opcion_opciones_sistemas,
                                                        opciones_sistemas.ruta as ruta_opciones_sistemas
                                                        ')
                                              ->join('usertypes', 'usertypes.id', 'usertype_opcs.id_tipo_usuario')
                                              ->join('opciones_sistemas', 'opciones_sistemas.id', 'usertype_opcs.id_opcion_sistema')
                                              ->where('usertypes.id', Auth::user()->usertype_id)
                                              ->get();
            
            foreach ($opcionesHabilitadas as $key => $opcion) 
            {
              if ( strtolower(str_replace(" ","_",$opcion->opcion_opciones_sistemas)) == strtolower(basename($request->url()))) 
              {
                return $next($request);
              }
            }
            
            return redirect("/");
        }
    }
}
