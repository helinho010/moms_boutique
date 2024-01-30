<?php

namespace App\Http\Controllers;

use App\Models\Usertype;
use App\Models\UsertypeOpc;
use Illuminate\Http\Request;

class UsertypeOpcController extends Controller
{
    public function index()
    {
        $roles = Usertype::orderBy("updated_at","desc")
                         ->paginate(10);
        $opcionesHabilitadas = UsertypeOpc::orderBy("updated_at","desc")->get();
                                          
        return view('UsertypeOpc',[
            "roles" => $roles,
            "opciones_habilitadas" => $opcionesHabilitadas,
        ]);
    }
}
