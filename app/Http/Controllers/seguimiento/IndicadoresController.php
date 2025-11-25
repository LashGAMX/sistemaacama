<?php

namespace App\Http\Controllers\Seguimiento;

use App\Http\Controllers\Controller; 

class IndicadoresController extends Controller
{
    public function index()
    {
        return view('seguimiento.indicadores.indicadores');
    }
    public function graficos()
    {
        return view('seguimiento.indicadores.graficos');
    }
}
