<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TrasporteProductosController extends Controller
{
    public function index(Request $request)
    {
        return view('traspasoProductos.index');
    }
}
