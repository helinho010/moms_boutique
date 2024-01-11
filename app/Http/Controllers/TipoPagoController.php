<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TipoPago;

class TipoPagoController extends Controller
{
    public function index(Request $request)
    {
        $tipoPagos = TipoPago::orderBy('updated_at','desc')->paginate(10);
        return view('tipoPago',compact('tipoPagos'));
    }

    public function buscar(Request $request)
    {
        if ($request->buscar != '') 
        {
            $tipoPagos = TipoPago::where("tipo", "like", '%'.$request->buscar.'%')
                                   ->orderBy('updated_at','desc')
                                   ->paginate(10);    
        }else {
            $tipoPagos = TipoPago::orderBy('updated_at','desc')->paginate(10);
        }
        return view('tipoPago',compact('tipoPagos'));
    }

    public function store(Request $request)
    {
        $request->validate([
           'tipo' => 'required|unique:tipo_pagos', 
        ]);

        $nuevotipoPago = new TipoPago();
        $nuevotipoPago->tipo = $request->tipo;

        $estado = 0;
        if ($nuevotipoPago->save()) {
            $estado = 1;
        }

        return redirect()->route('home_tipo_pago',['exito'=>$estado]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'tipo' => 'required|unique:tipo_pagos', 
         ]);

        $actualizarEvento = TipoPago::where("id",$request->id)->first();
        $actualizarEvento->tipo = $request->tipo;

        $estado = 0;
        if ($actualizarEvento->save()) {
            $estado = 1;
        }

        return redirect()->route('home_tipo_pago',['actualizado'=>$estado]);
    }

    public function update_estado(Request $request)
    {
        switch ($request->estado) 
        {
            case 0:
                $tipoPago = TipoPago::where("id",$request->id)->first();
                $tipoPago->estado = 1;
            break;

            case 1:
                $tipoPago = TipoPago::where("id",$request->id)->first();
                $tipoPago->estado = 0;
            break;
            
            default:
                
            break;
        }
        $tipoPago->save();
    }
}
