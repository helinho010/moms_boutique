<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    public function index()
    {
        $proveedores = Proveedor::orderBy('updated_at','desc')->paginate(10);
        return view('proveedor',[
            "proveedores"=>$proveedores,
        ]);
    }

    public function buscar(Request $request)
    {
        if ($request->buscar != '') 
        {
            $proveedores = Proveedor::where("nombre", "like", '%'.$request->buscar.'%')
                                   ->orwhere("telefono", "like", '%'.$request->buscar.'%')
                                   ->orwhere("ciudad", "like", '%'.$request->buscar.'%')
                                   ->orwhere("observacion", "like", '%'.$request->buscar.'%')
                                   ->orderBy('updated_at','desc')
                                   ->paginate(10);    
        }else {
            $proveedores = Proveedor::orderBy('updated_at','desc')->paginate(10);
        }
        return view('proveedor',[
            "proveedores" => $proveedores,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'telefono' => 'required',
            'ciudad' => 'required',
         ]);
 
         $nuevoProveedor = new Proveedor();
         $nuevoProveedor->nombre = $request->nombre;
         $nuevoProveedor->telefono = $request->telefono;
         $nuevoProveedor->ciudad = $request->ciudad;
         $nuevoProveedor->observacion = $request->observacion;
 
         $estado = 0;
         if ($nuevoProveedor->save()) {
             $estado = 1;
         }
        return redirect()->route('home_proveedor',['exito'=>$estado]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'telefono' => 'required',
            'ciudad' => 'required',
         ]);
 
         $actualizarProveedor = Proveedor::where("id",$request->id)->first();
         $actualizarProveedor->nombre = $request->nombre;
         $actualizarProveedor->telefono = $request->telefono;
         $actualizarProveedor->ciudad = $request->ciudad;
         $actualizarProveedor->observacion = $request->observacion;
 
         $estado = 0;
         if ($actualizarProveedor->save()) {
             $estado = 1;
         }
        return redirect()->route('home_proveedor',['actualizado'=>$estado]);
    }

    public function update_estado(Request $request)
    {
        switch ($request->estado) 
        {
            case 0:
                $proveedor = Proveedor::where("id",$request->id)->first();
                $proveedor->estado = 1;
            break;

            case 1:
                $proveedor = Proveedor::where("id",$request->id)->first();
                $proveedor->estado = 0;
            break;
            
            default:
                
            break;
        }
        $proveedor->save();
    }


}
