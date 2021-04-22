<?php

namespace App\Http\Controllers\Historial;

use App\Http\Controllers\Controller;
use App\Models\Clientes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HistorialController extends Controller
{
    public function index()
    {
        $idUser = Auth::user()->id;
        return view('historial/historial',compact('idUser'));
      //var_dump(Clientes::all());
    }
}
