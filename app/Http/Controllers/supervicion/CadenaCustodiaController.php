<?php

namespace App\Http\Controllers\Supervicion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CadenaCustodiaController extends Controller
{
    //
    public function index()
    { 
        return view('supervicion.cadena.cadena')    ;
    }
}
