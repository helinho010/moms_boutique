<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Categoria;

class ProductoController extends Controller
{
    public function index()
    {
        $productos = Producto::orderBy('updated_at','desc')->paginate(10);
        $categoriasSelect = Categoria::where('estado',1)->get();
        $categorias = Categoria::all();

        return view('producto',compact('productos','categorias','categoriasSelect'));
    }

    public function buscar(Request $request)
    {
        if ($request->buscar != '') 
        {
            $productos = Producto::orwhere("codigo_producto", "like", '%'.$request->buscar.'%')
                                    ->orwhere('nombre','like','%'.$request->buscar.'%')
                                    ->orwhere('precio','like','%'.$request->buscar.'%')
                                    ->orwhere('talla','like','%'.$request->buscar.'%')
                                   ->orderBy('updated_at','desc')
                                   ->paginate(10);
        }else {
            $productos = Producto::orderBy('updated_at','desc')->paginate(10);
        }
        $categorias = Categoria::where('estado',1)->get();
        return view('producto',compact('productos','categorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_categoria' => 'required|numeric',
            'nombre' => 'required',
            'precio' => 'required',
            'talla' => 'required',
        ]);

        $nuevoProducto = new Producto();
        $nuevoProducto->nombre = $request->nombre;
        $nuevoProducto->precio = $request->precio;
        $nuevoProducto->talla = $request->talla;
        $nuevoProducto->id_categoria = $request->id_categoria;

        $estado = 0;
        if ($nuevoProducto->save()) {
            $estado = 1;
        }

        return redirect()->route('home_producto',['exito'=>$estado]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'id_categoria' => 'required|numeric',
            'nombre' => 'required',
            'precio' => 'required',
            'talla' => 'required',
        ]);

        $actualizarProducto = Producto::where("id",$request->id)->first();
        $actualizarProducto->nombre = $request->nit;
        $actualizarProducto->precio = $request->precio;
        $actualizarProducto->talla = $request->talla;
        $actualizarProducto->id_categoria = $request->id_categoria;
        
        $estado = 0;
        if ($actualizarProducto->save()) {
            $estado = 1;
        }

        return redirect()->route('home_producto',['actualizado'=>$estado]);
    }

    public function update_estado(Request $request)
    {
        switch ($request->activo) 
        {
            case 0:
                $producto = Producto::where("id",$request->id)->first();
                $producto->activo = 1;
            break;

            case 1:
                $producto = Producto::where("id",$request->id)->first();
                $producto->activo = 0;
            break;
            
            default:
                
            break;
        }
        $producto->save();
    }
}
