<?php

namespace App\Http\Controllers\Laboratorio;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Parametro;  

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
    
    public function tipoAnalisis(){ 
        return view('laboratorio.tipoAnalisis');
    }
    public function captura()
    {
        $parametro = Parametro::all();
        return view('laboratorio.captura',compact('parametro'));
    }
    public function lote()
    {
        return view('laboratorio.lote');
    }
    public function asignar()
    {
        return view('laboratorio.asignar');
    }
}
