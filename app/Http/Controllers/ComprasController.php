<?php

namespace App\Http\Controllers;

use App\Models\Sucursal;
use App\Models\User;
use Illuminate\Http\Request;

class ComprasController extends Controller
{
    public function index()
    {
        return view('compras.index', [
            'sucursales' => [],
            'id_sucursal' => null,
        ]);
    }

    public function create()
    {
        $sucursales = Sucursal::all();
        $usuarios = User::all();
        $estadoCompra = ["creado", "revisado", "aprobado"];

        return view('compras.create', [
            'sucursales' => $sucursales,
            'usuarios' => $usuarios,
            'codigo_compra' => 'COMP-2025070001',
            'estadoCompra' => $estadoCompra,
            'id_sucursal' => null,
        ]);
    }

    public function store(Request $request)
    {
        dd($request);
        // Validar y guardar la compra
        $data = $request->validate([
            'codigo_compra' => 'required|string|max:20',
            'id_sucursal' => 'required|exists:sucursals,id',
            'total_compra' => 'required|numeric',
            'presupuesto' => 'nullable|numeric',
            'sobrante' => 'nullable|numeric',
            'observacion' => 'nullable|string',
            'id_usuario_creador' => 'required|exists:users,id',
        ]);

        // Aquí se guardaría la compra en la base de datos

        return redirect()->route('home_compras')->with('success', 'Compra guardada exitosamente.');
    }
}
