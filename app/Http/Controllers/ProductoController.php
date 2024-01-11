<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Categoria;

class ProductoController extends Controller
{
    public function index()
    {
        $productos = Producto::selectRaw('productos.id,
                                        productos.codigo_producto,
                                        productos.nombre,
                                        productos.precio,
                                        productos.talla,
                                        productos.estado,
                                        productos.created_at,
                                        productos.updated_at, 
                                        productos.id_categoria,
                                        categorias.nombre as nombre_categoria')
                               ->join('categorias','categorias.id','productos.id_categoria')
                               ->orderBy('updated_at','desc')
                               ->paginate(10);
        $categorias = Categoria::all();

        return view('producto',compact('productos','categorias'));
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
            'nombre' => 'required',
            'precio' => 'required',
            'talla' => 'required',
        ]);

        $actualizarProducto = Producto::where("id",$request->id)->first();
        $actualizarProducto->nombre = $request->nombre;
        $actualizarProducto->precio = $request->precio;
        $actualizarProducto->talla = $request->talla;
        if (isset($request->id_categoria)) 
        {
            $actualizarProducto->id_categoria = $request->id_categoria;
        }
        
        $estado = 0;
        if ($actualizarProducto->save()) {
            $estado = 1;
        }

        return redirect()->route('home_producto',['actualizado'=>$estado]);
    }

    public function update_estado(Request $request)
    {
        switch ($request->estado) 
        {
            case 0:
                $producto = Producto::where("id",$request->id)->first();
                $producto->estado = 1;
            break;

            case 1:
                $producto = Producto::where("id",$request->id)->first();
                $producto->estado = 0;
            break;
            
            default:
                
            break;
        }
        $producto->save();
    }
}
