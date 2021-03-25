<?php

namespace App\Http\Controllers\Cotizacion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cotizaciones;
use App\Models\IntermediariosView;
use App\Models\Clientes;
use App\Models\Norma;
class CotizacionController extends Controller
{

    /**
     * Retorna la Pagina Principal del Modulo de Cotización
     */
    public function index()
    {
        //Vista Cotización
        $model = Cotizaciones::All();
        $intermediarios = IntermediariosView::All();
        $cliente = Clientes::All();
        $norma = Norma::All();
        return view('cotizacion.cotizacion',compact('model','intermediarios','cliente','norma'));
    }

    public function create()
    {
        //Vista Crear
        return view('cotizacion.cotizacionCrear');
    }
}
