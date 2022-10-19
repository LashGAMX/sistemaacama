<?php

namespace App\Http\Controllers\seguimiento;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IncidenciasController extends Controller
{
    public function index(){
        return view('seguimiento.incidencias'); 
    }
}
