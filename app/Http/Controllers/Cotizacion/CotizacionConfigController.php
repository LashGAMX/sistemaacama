<?php

namespace App\Http\Controllers\Cotizacion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CotizacionConfigController extends Controller
{

    /**
     * Retorna la Pagina Principal del Modulo de Cotización
     */
    public function index()
    {
        //Vista Cotización
        return view('cotizacion.cotizacionCatalogo');
    }
}
