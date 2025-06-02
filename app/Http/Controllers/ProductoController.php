<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Categoria;

class ProductoController extends Controller
{
    public function index(Request $request)
    {
        if (isset($request->buscar))
        {
            $productos = Producto::buscar($request->buscar)->withQueryString();
        }else {
            $productos = Producto::selectRaw('productos.id,
                                        productos.codigo_producto,
                                        productos.nombre,
                                        productos.costo,
                                        productos.precio,
                                        productos.talla,
                                        productos.descripcion,
                                        productos.estado,
                                        productos.created_at,
                                        productos.updated_at, 
                                        productos.id_categoria,
                                        categorias.nombre as nombre_categoria')
                                ->join('categorias','categorias.id','productos.id_categoria')
                                ->orderBy('updated_at','desc')
                                ->paginate(10);
        }

        $categorias = Categoria::all();

        return view('producto',compact('productos','categorias'));
    }

    public function buscar(Request $request)
    {
    
        if ($request->buscar != '') 
        {
            $request->session()->forget('buscar_producto');
            session(['buscar_producto' => $request->buscar]);

            $productos = Producto::where("codigo_producto", "like", '%'.session('buscar_producto').'%')
                                 ->orwhere('nombre','like','%'.session('buscar_producto').'%')
                                 ->orwhere('costo','like','%'.session('buscar_producto').'%')
                                 ->orwhere('precio','like','%'.session('buscar_producto').'%')
                                 ->orwhere('talla','like','%'.session('buscar_producto').'%')
                                 ->orwhere('descripcion','like','%'.session('buscar_producto').'%')
                                 ->orderBy('updated_at','desc')
                                 ->paginate(10);
        }else {
            $request->session()->forget('buscar_producto');
            return redirect()->route('home_producto');
        }
        $categorias = Categoria::where('estado',1)->get();
        return view('producto',compact('productos','categorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_categoria' => 'required|numeric',
            'nombre' => 'required',
            'costo' => 'required',
            'precio' => 'required',
            'talla' => 'required',
        ]);

        $nuevoProducto = new Producto();
        $nuevoProducto->nombre = $request->nombre;
        $nuevoProducto->costo = $request->costo;
        $nuevoProducto->precio = $request->precio;
        $nuevoProducto->talla = $request->talla;
        $nuevoProducto->descripcion = $request->descripcion;
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
        $actualizarProducto->costo = $request->costo;
        $actualizarProducto->precio = $request->precio;
        $actualizarProducto->talla = $request->talla;
        $actualizarProducto->descripcion = $request->descripcion;

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

    public function buscarProductoId(Request $request)
    {
        $validate = $request->validate([
            'id' => 'required|numeric',
        ]);

        $productoEncontrado = Producto::where('id',$request->id)->get();
        
        return $productoEncontrado;
    }
}
