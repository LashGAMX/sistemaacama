<?php

namespace App\Http\Controllers\Precios;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CatalogoController extends Controller
{
    //
    public function index()
    {
        // $idUser = Auth::user()->id;
        return view('precios.catalogo');
    }
    public function details($idSucursal, $idNorma)
    {
        $idUser = Auth::user()->id;
        return view('precios.catalogo',compact('idSucursal', 'idNorma')); 
    }
}
