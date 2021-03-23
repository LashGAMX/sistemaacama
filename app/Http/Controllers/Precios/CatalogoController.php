<?php

namespace App\Http\Controllers\Precios;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CatalogoController extends Controller
{
    //
    public function index()
    {
        return view('precios.catalogo');
    }
    public function details($idSucursal)
    {
        return view('precios.catalogo',compact('idSucursal'));
    }
}
