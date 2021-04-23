<?php

namespace App\Http\Controllers\Historial;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConfigController extends Controller
{
    
    public function index()
    {
        $idUser = Auth::user()->id;
        return view('historial/config',compact('idUser'));
      //var_dump(Clientes::all());
    }
}