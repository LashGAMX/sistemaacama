<?php

namespace App\Http\Controllers\Precios;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaqueteController extends Controller
{
    //
    public function index()
    {
        return view('precios.paquete');
    }
}
