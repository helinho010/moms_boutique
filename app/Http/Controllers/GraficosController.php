<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DetalleVenta;

class GraficosController extends Controller
{
    public function productosMasVendidos(Request $request)
    {
        $rankingProductos = DetalleVenta::selectRaw('*')
                                        ->get();
                                        
        return $rankingProductos;
    }
}
