<?php

namespace App\Http\Controllers\Cotizacion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CotizacionController extends Controller
{
    //
    public function index()
    {
        return view('cotizacion.cotizacion');
    }
}
