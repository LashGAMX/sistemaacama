<?php

namespace App\Http\Controllers\Beto;

use App\Http\Controllers\Controller; 
use App\Models\Signo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


use FormulaParser\FormulaParser;

class BetoController extends Controller
{

    public function animacion()
    {
        return view('beto.animacion');
    }

}