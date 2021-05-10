<?php

namespace App\Http\Controllers\Cotizacion;

use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;

class SolicitudController extends Controller
{
    // 
    public function index()
    {
        return view('cotizacion.solicitud');
    } 
    public function create()
    {
        return view('cotizacion.createSolicitud.blade.php');
    }
}
  
