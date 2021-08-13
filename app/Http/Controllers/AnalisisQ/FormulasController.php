<?php

namespace App\Http\Controllers\AnalisisQ;

use App\Http\Controllers\Controller;
use App\Http\Livewire\AnalisisQ\Parametros;
use App\Models\AreaAnalisis;
use App\Models\Constante;
use App\Models\Formulas;
use App\Models\NivelFormula;
use App\Models\FormulaNivel;
use App\Models\Parametro;
use App\Models\Regla;
use App\Models\Tecnica;
use App\Models\Unidad;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;



class FormulasController extends Controller
{

    public $sus;

    public function index()
    { 
        $formulas = Formulas::all();
        return view('analisisQ.formulas', compact('formulas'));
    }
 
    public function crearFormula()
    { 
        $parametro = Parametro::all();
        $area = AreaAnalisis::all();
        $tecnica = Tecnica::all();
        $reglas = Regla::all();
        $niveles = NivelFormula::all();
        return view('analisisQ.crear_formula',compact('area','tecnica','reglas','parametro','niveles'));
    }
    public function constantes()
    {
        // $model = DB::table('ViewNormaParametro'); Esto no va aqui
        $constantes = Constante::all();
        return view('analisisQ.constantes', compact('constantes'));
    }
    public function constante_create(Request $request) //creación de constantes
    {
        Constante::create([
            'Constante' => $request->constante,
            'Valor' => $request->valor,
            'Descripcion' => $request->descripcion,
        ]);
        $data = array(
            'Constante' => $request->constante,
            'Valor' => $request->valor,
            'Descripcion' => $request->descripcion,
        );
        return response()->json($data);
    }
    public function createNiveles(Request $request) //  Creacion de nivel formula
    {
        FormulaNivel::create([
            'Nombre' => $request->nombre,
            'Nivel' => $request->nivel,
            // 'Descripcion' => $request->descripcion,
            'Resultado' => $request->resultado,

        ]);
        $data = array(
            'Nombre' => $request->nombre,
            'Nivel' => $request->nivel,
            'Resultado' => $request->resultado,
        ); 
        return response()->json($data);
    }
    public function create(Request $request)  // Creacion de formula
    {
        

       $model = Formulas::create([
            'Id_area' => $request->area,
            'Id_parametro' => $request->parametro,
            'Id_tecnica' => $request->tecnica,
            'Formula' => $request->formula,
            'Formula_sistema' => $request->formulaSis,
            'Resultado' => $request->resultadoCal,
        ]);
    
        // VariablesFormula::create([
        //     'Id_formula' => $model->Id_formula,
        //     'Id_parametro' => $request->parametro,
        //     'Variable',
        //     'Id_tipo',
        //     'Valor',
        //     'Drcimal'
        // ]);
        $data = array(
            'sw' => 1,
            'formula' => $request->formula,
        ); 
        return response()->json($data);
    }
    public function nivel()
    {
        // $area = AreaAnalisis::all();
        // $tecnica = Tecnica::all();
        // $reglas = Regla::all();
        $nivel = FormulaNivel::all();
        return view('analisisQ.nivel_formula', compact('nivel'));
    } 
    public function crear_nivel()
    {
        $nivel = NivelFormula::all();
        $reglas = Regla::all();
        return view('analisisQ.crear_nivel_formula',compact('nivel','reglas')); 
    }
    public function probarFormula(Request $request) //Modal probar formula
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

        $parametro = $request->idParametro;
       // $unidad = DB::table('ViewParametros')->where('Id_parametro',$parametro)->get();
        $unidad = Unidad::where('Id_unidad',$parametro)->get();
        $data = array(
            'resultado' => $resultado,
            'valores' => $request->valores,
            'formulaVal' => $formula,
            'formula' => $request->formula,
            'parametro' => $parametro,
            'unidad' => $unidad,  
        ); 
        return response()->json($data);
    }
    function multiexplode ($delimiters,$string) {

        $ready = str_replace($delimiters, $delimiters[0], $string);
        $launch = explode($delimiters[0], $ready);
        return  $launch;
    }
    public function getVariables(Request $request)
    {
        $reglas = Regla::all();
        $constantes = Constante::all();
        $niveles = FormulaNivel::all();
        $nivel1 = FormulaNivel::where('Nivel',"1")->get();
        $nivel2 = FormulaNivel::where('Nivel',"2")->get();
        $nivel3 = FormulaNivel::where('Nivel',"3")->get();
       
        

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
            'niveles'  => $niveles,
            'nivel2' => $nivel2,
            'nivel1' => $nivel1,
            'nivel3' => $nivel3,
            
        );
        
        return response()->json($data);
    }
}

