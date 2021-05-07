<?php

namespace App\Http\Controllers\AnalisisQ;

use App\Http\Controllers\Controller;
use App\Models\AreaAnalisis;
use App\Models\Tecnica;
use Illuminate\Http\Request;

class FormulasController extends Controller
{
    public function index()
    {
        
        return view('analisisQ.formulas');
    }
 
    public function crearFormula()
    {
        $area = AreaAnalisis::all();
        $tecnica = Tecnica::all();
        return view('analisisQ.crear_formula',compact('area','tecnica'));
    }
    public function getVariables(Request $request)
    {
        /* Variables formula */
        //Obtener variables de la formula
        $formula = $this->multiexplode(array("(",")","+","/","*","-"),$request->formula);
        $arrFormula = array();
        //Limpiar varibles optenidos de vacio y alamcenar en arr
        $cont = 0;
        for ($i=0; $i < sizeof($formula); $i++) { 
            # code...
            if($formula[$i] != '')
            {
                $arrFormula[$cont] = $formula[$i];
                $cont++;
            }
        }
        /* Variables formula sistema*/
        $formulaSis = $this->multiexplode(array("(",")","+","/","*","-"),$request->formulaSis);
        $arrSis = array();
        //Limpiar varibles optenidos de vacio y alamcenar en arr
        $cont = 0;
        for ($i=0; $i < sizeof($formulaSis); $i++) { 
            # code...
            if($formulaSis[$i] != '')
            {
                $arrSis[$cont] = $formulaSis[$i];
                $cont++;
            }
        }
        $data = array(
            'formula' => $request->formula,
            'variables' => $arrFormula,
            'formulaSis'  => $request->formulaSis,
            'variableSis' => $arrSis,
        );
        
        return response()->json($data);
    }
    function multiexplode ($delimiters,$string) {

        $ready = str_replace($delimiters, $delimiters[0], $string);
        $launch = explode($delimiters[0], $ready);
        return  $launch;
    }
    
}
