<?php

namespace App\Http\Controllers\Seguimiento;

use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;

class SeguimientoController extends Controller
{
    public function index(){
        return view('seguimiento/seguimiento');
    }
    public function muestra(){
        return view('seguimiento.muestra');
    }
}
  