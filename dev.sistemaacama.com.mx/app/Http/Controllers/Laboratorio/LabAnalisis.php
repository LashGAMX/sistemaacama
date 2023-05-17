<?php

namespace App\Http\Controllers\Laboratorio;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LabAnalisis extends Controller
{
    // 
    public function captura()
    {
        return view('laboratorio.analisis.captura');
    }
}
 