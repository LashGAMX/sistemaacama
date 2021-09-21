<?php

namespace App\Http\Controllers\Laboratorio;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LaboratorioController extends Controller
{
    function index(){ 
        return view('laboratorio.laboratorio');
    }

    public function analisis(){
        return view('laboratorio.analisis');
    }

    public function observacion(){
        return view('laboratorio.observacion');
    }
}
