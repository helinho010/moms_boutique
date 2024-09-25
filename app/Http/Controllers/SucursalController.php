<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sucursal;

use function PHPUnit\Framework\isNull;

class SucursalController extends Controller
{
    public function index(Request $request)
    {
        $sucursales = Sucursal::orderBy('updated_at','desc')->paginate(10);
        return view('sucursal',compact('sucursales'));
    }

    public function buscar(Request $request)
    {
        if ($request->buscar != '') 
        {
            $sucursales = Sucursal::orwhere("nit", "like", '%'.$request->buscar.'%')
                                    ->orwhere('nit','like','%'.$request->buscar.'%')
                                    ->orwhere('razon_social','like','%'.$request->buscar.'%')
                                    ->orwhere('direccion','like','%'.$request->buscar.'%')
                                    ->orwhere('telefonos','like','%'.$request->buscar.'%')
                                    ->orwhere('ciudad','like','%'.$request->buscar.'%')
                                    ->orwhere('activo','like','%'.$request->buscar.'%')
                                   ->orderBy('updated_at','desc')
                                   ->paginate(10);    
        }else {
            $sucursales = Sucursal::orderBy('updated_at','desc')->paginate(10);
        }
        return view('sucursal',compact('sucursales'));
    }

    public function store(Request $request)
    {
        // dd($request);
        $request->validate([
            'nit' => 'required',
            'razon_social' => 'required',
            'direccion' => 'required',
            'telefonos' => 'required',
            'ciudad' => 'required',
        ]);

        $existeAlmacenCentral = Sucursal::where('almacen_central',1)
                                        ->get();

        if ($request->almacen_central=='on' && $existeAlmacenCentral->count() > 0 ) 
        {
            return back()->withInput()->withErrors(['errorAddAlmacenCentral' => '¡Ya existe registrado un almacen central!']);
        }
        else
        {
            $nuevoSucursal = new Sucursal();
            $nuevoSucursal->nit = $request->nit;
            $nuevoSucursal->razon_social = $request->razon_social;
            $nuevoSucursal->direccion = $request->direccion;
            $nuevoSucursal->telefonos = $request->telefonos;
            $nuevoSucursal->ciudad = $request->ciudad;
            $nuevoSucursal->almacen_central = $request->almacen_central=='on'?true:false;

            $estado = 0;
            if ($nuevoSucursal->save()) {
                $estado = 1;
            }
        }

        return redirect()->route('home_sucursal',['exito'=>$estado]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'nit' => 'required',
            'razon_social' => 'required',
            'direccion' => 'required',
            'telefonos' => 'required',
            'ciudad' => 'required',
        ]);

        $sucursalCentralActual = Sucursal::where('almacen_central',1)->first();
        
        if (is_null($sucursalCentralActual) || $sucursalCentralActual->count() < 1) 
        {
            $idSucursalCentralActual = 0;
        }else{
            
            $idSucursalCentralActual = $sucursalCentralActual->id ;
        }

        $actualizarSucursal = Sucursal::where("id",$request->id)->first();
        $actualizarSucursal->nit = $request->nit;
        $actualizarSucursal->razon_social = $request->razon_social;
        $actualizarSucursal->direccion = $request->direccion;
        $actualizarSucursal->telefonos = $request->telefonos;
        $actualizarSucursal->ciudad = $request->ciudad;
        $contarSucursalesCentrales = Sucursal::where('almacen_central', true);
        if ( isset($request->almacen_central) && $contarSucursalesCentrales->count() == 0 ) 
        {
            $actualizarSucursal->almacen_central = true;
            $estado = 0;
        }else if($idSucursalCentralActual == $request->id ){
            /**
             * Estamos en este punto
             */
            $actualizarSucursal->almacen_central = false;
            $estado = 1;
        }
        else{
            $actualizarSucursal->almacen_central = false;
            $estado = 2;
        }

        if ($estado != 2){
            if ($actualizarSucursal->save()) {
                $estado = 1;
            }
        }
        

        return redirect()->route('home_sucursal',['actualizado'=>$estado]);
    }

    public function update_estado(Request $request)
    {
        switch ($request->activo) 
        {
            case 0:
                $sucursal = Sucursal::where("id",$request->id)->first();
                $sucursal->activo = 1;
            break;

            case 1:
                $sucursal = Sucursal::where("id",$request->id)->first();
                $sucursal->activo = 0;
            break;
            
            default:
                
            break;
        }
        $sucursal->save();
    }
}
