<?php

namespace App\Http\Controllers\Beto;

use App\Http\Controllers\Controller;
use App\Models\Signo;
use Illuminate\Http\Request;

class BetoController extends Controller
{
    //
    public function formula()
    { 
        
        //Leer cadena y convertirlo a función
            // $a = 6;
            // $formula2 = "/2";
            // $formula = "".$a."+4*6".$formula2;
            // $var = eval("return ".$formula.";");
            // echo $var;

        //Descomponer formula
        $formula = "(mx+b)";
        echo "<br>Formula:".$formula;
        //convierte cadena a array de datos
        $str = str_split($formula);
        echo "<br>Tamaño:".sizeof($str);

        echo "<br><br>Datos 1 x 1 <br>________________________________________<br>";
        //Recorre el array para descomponer datos
        for ($i=0; $i < sizeof($str); $i++) { 
            # code...
            echo "<br>Dato ".$i." = ".$str[$i];
        }
        //signos a comparar
        $signos = Signo::all();
        echo "<br>Signos a comparar <br>";
        foreach($signos as $item)
        {
            echo "<br>Signo: ".$item->Signo;
        }
        // Contador de variables o constantes
        $numSignos = 0;
        $numVar = 0;
        $arrVar = array();
        $tempVar = '';
        $swVar = false;
        for ($i=0; $i < sizeof($str); $i++) { 
            # code...
            // Recorreo los signos y los compara
            foreach($signos as $item)
            {
                if($item->Signo == $str[$i])
                {
                    $numSignos++;
                    $swVar = false;
                    $tempVar = '';
                    if(sizeof(str_split($tempVar)) > 0)
                    {
                        $arrVar[$numVar] = $tempVar;
                        $numVar++;
                        $tempVar = '';
                    }
                }else{
                    $swVar = true;
                    if($swVar == true)
                    {
                        $tempVar .= $str[$i];
                    }
                }
            }   
        }
        echo "<br>Signos: ".$numSignos;
        echo "<br>Variables: ".$numVar;
    }
}
