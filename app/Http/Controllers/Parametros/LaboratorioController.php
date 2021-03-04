<?php

namespace App\Http\Controllers\Parametros;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LaboratorioController extends Controller
{
    //
    public function index()
    {
        return view('parametros.parametros');
    }
} 
 