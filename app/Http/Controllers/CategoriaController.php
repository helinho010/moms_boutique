<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categoria;

class CategoriaController extends Controller
{
    public function index(Request $request)
    {
        $categorias = Categoria::orderBy('updated_at','desc')->paginate(10);
        return view('/categoria',compact('categorias'));
    }

    public function buscar(Request $request)
    {
        // dd($request);
        if ($request->buscar != '') 
        {
            $categorias = Categoria::where("nombre", "like", '%'.$request->buscar.'%')
                                   ->orderBy('updated_at','desc')
                                   ->paginate(10);    
        }else {
            $categorias = Categoria::orderBy('updated_at','desc')->paginate(10);
        }
        return view('/categoria',compact('categorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
           'nombre' => 'required|unique:categorias', 
        ]);

        $nuevaCategoria = new Categoria();
        $nuevaCategoria->nombre = $request->nombre;

        $estado = 0;
        if ($nuevaCategoria->save()) {
            $estado = 1;
        }

        return redirect()->route('home_categoria',['exito'=>$estado]);
    }

    public function update(Request $request)
    {
        // dd($request);

        $request->validate([
           'nombre' => 'required|unique:categorias', 
        ]);

        $actualizarCategoria = Categoria::where("id",$request->id)->first();
        $actualizarCategoria->nombre = $request->nombre;

        $estado = 0;
        if ($actualizarCategoria->save()) {
            $estado = 1;
        }

        return redirect()->route('home_categoria',['actualizado'=>$estado]);
    }

    public function update_estado(Request $request)
    {
        switch ($request->estado) 
        {
            case 0:
                $categoria = Categoria::where("id",$request->id)->first();
                $categoria->estado = 1;
            break;

            case 1:
                $categoria = Categoria::where("id",$request->id)->first();
                $categoria->estado = 0;
            break;
            
            default:
                
            break;
        }
        $categoria->save();
    }
}
