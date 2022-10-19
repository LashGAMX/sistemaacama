<?php

namespace App\Http\Controllers\seguimiento;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IncidenciasController extends Controller
{
    public function lista(){
        return view('seguimiento.listaIncidencias'); 
    }
    public function incidencias(){
        return view('seguimiento.incidencias'); 
    }
}
