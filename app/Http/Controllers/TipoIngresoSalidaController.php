<?php

namespace App\Http\Controllers;

use App\Models\TipoIngresoSalida;
use Illuminate\Http\Request;

class TipoIngresoSalidaController extends Controller
{
    public function index(Request $request)
    {
        $tipoIngresoSalidas = TipoIngresoSalida::orderBy('updated_at','desc')->paginate(10);
        return view('tipoIngresoSalida',compact('tipoIngresoSalidas'));
    }

    public function buscar(Request $request)
    {
        if ($request->buscar != '') 
        {
            $tipoIngresoSalidas = TipoIngresoSalida::where("tipo", "like", '%'.$request->buscar.'%')
                                   ->orderBy('updated_at','desc')
                                   ->paginate(10);    
        }else {
            $tipoIngresoSalidas = TipoIngresoSalida::orderBy('updated_at','desc')->paginate(10);
        }
        return view('tipoIngresoSalida',compact('tipoIngresoSalidas'));
    }

    public function store(Request $request)
    {
        $request->validate([
           'tipo' => 'required|unique:tipo_ingreso_salidas', 
        ]);

        $nuevoTipoIngresoSalida = new TipoIngresoSalida();
        $nuevoTipoIngresoSalida->tipo = $request->tipo;
        $estado = 0;
        if ($nuevoTipoIngresoSalida->save()) {
            $estado = 1;
        }

        return redirect()->route('home_tipo_ingreso_salida',['exito'=>$estado]);
    }

    public function update(Request $request)
    {
        // dd($request);

        $request->validate([
           'tipo' => 'required|unique:tipo_ingreso_salidas', 
        ]);

        $actualizarTipoIngresoSalida = TipoIngresoSalida::where("id",$request->id)->first();
        $actualizarTipoIngresoSalida->tipo = $request->tipo;

        $estado = 0;
        if ($actualizarTipoIngresoSalida->save()) {
            $estado = 1;
        }

        return redirect()->route('home_tipo_ingreso_salida',['actualizado'=>$estado]);
    }

    public function update_estado(Request $request)
    {
        switch ($request->estado) 
        {
            case 0:
                $tipoIngresoSalida = TipoIngresoSalida::where("id",$request->id)->first();
                $tipoIngresoSalida->estado = 1;
            break;

            case 1:
                $tipoIngresoSalida = TipoIngresoSalida::where("id",$request->id)->first();
                $tipoIngresoSalida->estado = 0;
            break;
            
            default:
                
            break;
        }
        $tipoIngresoSalida->save();
    }
}
