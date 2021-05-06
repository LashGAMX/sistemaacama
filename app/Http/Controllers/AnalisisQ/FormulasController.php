<?php

namespace App\Http\Controllers\AnalisisQ;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FormulasController extends Controller
{
    public function index()
    {
        return view('analisisQ.formulas');
    }

    public function crearFormula()
    {
        return view('analisisQ.crear_formula');
    }
}
