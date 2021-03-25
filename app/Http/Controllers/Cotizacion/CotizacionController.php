<?php

namespace App\Http\Controllers\Cotizacion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cotizaciones;
class CotizacionController extends Controller
{

    /**
     * Retorna la Pagina Principal del Modulo de Cotización
     */
    public function index()
    {
        //Vista Cotización
        $model = Cotizaciones::All();
        return view('cotizacion.cotizacion',compact('model'));
    }

    public function create()
    {
        //Vista Crear
        return view('cotizacion.cotizacionCrear');
    }
}
