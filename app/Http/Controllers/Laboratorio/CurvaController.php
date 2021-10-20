<?php

namespace App\Http\Controllers\Laboratorio;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\estandares;
use App\Models\Formulas;

class CurvaController extends Controller
{
    public function index(){

        $model = Estandares::all();
        $formula = Formulas::all();
        return view('laboratorio/curva',compact('model','formula'));
    }

    public function promedio(){
        
    }
}
