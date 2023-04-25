<?php

namespace App\Http\Controllers\AnalisisQ;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ConstantesController extends Controller
{
    public function index()
    {
        return view('analisisQ.constantes');
    }
}
