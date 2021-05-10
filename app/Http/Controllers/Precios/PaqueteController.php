<?php

namespace App\Http\Controllers\Precios;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaqueteController extends Controller
{
    //
    public function index()
    {
        $idUser = Auth::user()->id;
        return view('precios.paquete', compact($idUser));
    }
}
