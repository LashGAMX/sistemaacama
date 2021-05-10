<?php

namespace App\Http\Controllers\AnalisisQ;

use App\Http\Controllers\Controller;
use App\Http\Livewire\AnalisisQ\Parametros;
use App\Models\AreaAnalisis;
use App\Models\Constante;
use App\Models\Parametro;
use App\Models\Regla;
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
        $parametro = Parametro::all(); 
        $area = AreaAnalisis::all();
        $tecnica = Tecnica::all();
        $reglas = Regla::all();
        return view('analisisQ.crear_formula',compact('parametro','area','tecnica','reglas'));
    }
    public function nivel()
    {
        // $area = AreaAnalisis::all();
        // $tecnica = Tecnica::all();
        // $reglas = Regla::all();
        return view('analisisQ.nivel_formula');
    }
    public function probarFormula(Request $request)
    {
           /* Variables formula */
        //Obtener variables de la formula
        $formula = $request->formula;
        $exploded = $this->multiexplode(array("(",")","+","/","*","-"),$formula);
        $arrFormula = array();
        //Limpiar varibles optenidos de vacio y alamcenar en arr
        $cont = 0;
        for ($i=0; $i < sizeof($exploded); $i++) { 
            # code...
            if($exploded[$i] != '')
            {
                $arrFormula[$cont] = $exploded[$i];
                $cont++;
            }
        }
        // Reemplazar datos formula por valores
        $contVal = 0;
        for ($i=0; $i < sizeof($exploded); $i++) { 
            # code...
            // $arr[$i-1] = $exploded[$i];
            
            if($exploded[$i] != '')
            {
                $aux = $formula; // asigno formula
                $sus = str_replace($exploded[$i],$request->valores[$contVal],$aux); // Reemplazo formula
                $formula = $sus; // Re asigno formula
                $contVal++;
            }
            // echo "<br>".$sus."<br>";
        }
        $resultado = eval("return (".$sus.");");

        $data = array(
            'resultado' => $resultado,
            'valores' => $request->valores,
            'formulaVal' => $formula,
            'formula' => $request->formula,
        ); 
        return response()->json($data);
    }
    public function getVariables(Request $request)
    { 
        $reglas = Regla::all();
        $constantes = Constante::all();

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
            'reglas' => $reglas,
            'constantes' => $constantes,
        );
        
        return response()->json($data);
    }
    function multiexplode ($delimiters,$string) {

        $ready = str_replace($delimiters, $delimiters[0], $string);
        $launch = explode($delimiters[0], $ready);
        return  $launch;
    }
    
}
