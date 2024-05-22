<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evento;

class EventoController extends Controller
{
    public function index(Request $request)
    {
        $eventos = Evento::orderBy('updated_at','desc')->paginate(10);
        return view('evento',compact('eventos'));
    }

    public function buscar(Request $request)
    {
        if ($request->buscar != '') 
        {
            $eventos = Evento::where("nombre", "like", '%'.$request->buscar.'%')
                                   ->orderBy('updated_at','desc')
                                   ->paginate(10);    
        }else {
            $eventos = Evento::orderBy('updated_at','desc')->paginate(10);
        }
        return view('evento',compact('eventos'));
    }

    public function store(Request $request)
    {
        $request->validate([
           'nombre' => 'required', 
           'fecha_evento' => 'required', 
        ]);

        $nuevaEvento = new Evento();
        $nuevaEvento->nombre = $request->nombre;
        $nuevaEvento->fecha_evento = $request->fecha_evento;

        $estado = 0;
        if ($nuevaEvento->save()) {
            $estado = 1;
        }

        return redirect()->route('home_evento',['exito'=>$estado]);
    }

    public function update(Request $request)
    {
        $request->validate([
           'nombre' => 'required', 
           'fecha_evento' => 'required', 
        ]);

        $actualizarEvento = Evento::where("id",$request->id)->first();
        $actualizarEvento->nombre = $request->nombre;
        $actualizarEvento->fecha_evento = $request->fecha_evento;

        $estado = 0;
        if ($actualizarEvento->save()) {
            $estado = 1;
        }

        return redirect()->route('home_evento',['actualizado'=>$estado]);
    }

    public function update_estado(Request $request)
    {
        switch ($request->estado) 
        {
            case 0:
                $evento = Evento::where("id",$request->id)->first();
                $evento->estado = 1;
            break;

            case 1:
                $evento = Evento::where("id",$request->id)->first();
                $evento->estado = 0;
            break;
            
            default:
                
            break;
        }
        $evento->save();
    }


}
