<?php

namespace App\Http\Controllers\Beto;

use App\Http\Controllers\Controller; 
use App\Models\Signo;
use Illuminate\Http\Request;


use FormulaParser\FormulaParser;

class BetoController extends Controller
{
    //
    public function readFormula() 
    {
        //Formula a resolver
         // $formula = "(a+cb)/2";
        // $formula ="(10/D1)*NMP";
        // $formula = "(((ABS-CA)-CB)/CM)*D";
        $formula = "((A)/(D*H)*I+(B)/(E*H)*I+(C)/(G*H)*I)/3";
        echo "<br>".$formula."<br>"; 
        //Obtener variables de la formula
        $exploded = $this->multiexplode(array("(",")","+","/","*","-"),$formula);
        echo "<br>Variables obtenidas: ";
        var_dump($exploded);
        echo '<br>';
        $arr = array();
        //Limpiar varibles optenidos de vacio y alamcenar en arr
        $cont = 0;
        for ($i=0; $i < sizeof($exploded) -1; $i++) { 
            # code...
            if($exploded[$i] != '')
            {
                $arr[$cont] = $exploded[$i];
                $cont++;
            }
        }
        echo "<br>Varibles sin cadena vacia:";
        var_dump($arr);
        $exploded2 = $this->multiexplode($arr,$formula);
        var_dump($exploded2);
        echo '<br> ';
        for ($i=1; $i < sizeof($exploded); $i++) { 
            # code...
            // $arr[$i-1] = $exploded[$i];
            
            if($exploded[$i] != '')
            {
                $aux = $formula; // asigno formula
                $sus = str_replace($exploded[$i],$i,$aux); // Reemplazo formula
                $formula = $sus; // Re asigno formula
            }
            // echo "<br>".$sus."<br>";
        }
        echo $sus;
        echo "<br>";
        $var = eval("return (".$sus.");");
        echo "Resultado:".$var;
    }
    public function formula()
    {

        // $formula = "(a+cb)/2";
        // $formula ="(10/D1)*NMP";
        $formula = "(((ABS-CA)-CB)/CM)*D";
        echo "<br>".$formula."<br>";
        // $arr = str_split();

        // $chunks = preg_split('/(:|-|\*|=)/', $string,-1, PREG_SPLIT_NO_EMPTY);
        // var_dump($chunks);}
        // $data = preg_split('/(:|-|\+*|=|()|)/', $formula,-1, PREG_SPLIT_NO_EMPTY);
        // var_dump($data);
        // $text = "here is a sample: this text, and this will be exploded. this also | this one too :)";
        // $exploded = $this->multiexplode(array(",",".","|",":"),$text);

    
        $exploded = $this->multiexplode(array("(",")","+","/","*","-"),$formula);
        var_dump($exploded);
        echo '<br> ';
        $arr = array();
        for ($i=0; $i < sizeof($exploded) -1; $i++) { 
            # code...
            if($exploded[$i] != '')
            {
                $arr[$i-1] = $exploded[$i];
            }
        }
        var_dump($arr);
        $exploded2 = $this->multiexplode($arr,$formula);
        var_dump($exploded2);
        echo '<br> ';
        for ($i=1; $i < sizeof($exploded); $i++) { 
            # code...
            // $arr[$i-1] = $exploded[$i];
            
            if($exploded[$i] != '')
            {
                $aux = $formula; // asigno formula
                $sus = str_replace($exploded[$i],$i,$aux); // Reemplazo formula
                $formula = $sus; // Re asigno formula
            }
            // echo "<br>".$sus."<br>";
        }
        echo $sus;
        echo "<br>";
        $var = eval("return ".$sus.";");
        echo "Resultado:".$var;
               //Leer cadena y convertirlo a función
        // $a = 6;
        // $formula2 = "/2";
        // $formula = "".$a."+4*6".$formula2;
        // $var = eval("return ".$formula.";");
        // echo $var;

// This is just a simple way to debug stuff ;-)
// echo '<pre>';
// print_r($chunks);
// echo '</pre>';
echo "<br>";
//        $formula = '3*x^2 - 4*p + 3/y';
// $precision = 2; // Number of digits after the decimal point 
//         $parser = new FormulaParser($formula, $precision);
//         $parser->setVariables(['x' => -4, 'y' => 8,'p' => 2]);
//         $result = $parser->getResult(); // [0 => 'done', 1 => 16.38]

//         // echo $result[1];  
//         var_dump($result);
//         // $formula = "(mx+b)"; 
//         $formula = '3*x^2 - 4*y + 3/y';
// $precision = 2; // Number of digits after the decimal point

// $parser = new Math($formula, $precision);
// $parser->setVariables(['x' => -4, 'y' => 8]);
// $result = $parser->getResult(); // [0 => 'done', 1 => 16.38]
   
    }
    function multiexplode ($delimiters,$string) {

        $ready = str_replace($delimiters, $delimiters[0], $string);
        $launch = explode($delimiters[0], $ready);
        return  $launch;
    }
    

    public function descomponer($formula)
    {

        // echo "<br>Formula:" . $formula;
        //convierte cadena a array de datos
        $str = str_split($formula);
        // echo "<br>Tamaño:" . sizeof($str);

        $signos = Signo::all();
        $numSignos = 0;
        $arr = array();
        for ($i = 0; $i < sizeof($str); $i++) {
            # code...
            // Recorreo los signos y los compara
            foreach ($signos as $item) {
                if ($item->Signo == $str[$i]) {
                    $numSignos++;
                    $arr[$i] = $str[$i];
                }else{

                }
            }
        }

        $data = array(
            'arr' => $arr,
        );
        return $data;
    }
    public function test()
    {


        //Leer cadena y convertirlo a función
        // $a = 6;
        // $formula2 = "/2";
        // $formula = "".$a."+4*6".$formula2;
        // $var = eval("return ".$formula.";");
        // echo $var;

        //Descomponer formula
        // $formula = "(mx+b)";
        // echo "<br>Formula:".$formula;
        // //convierte cadena a array de datos
        // $str = str_split($formula);
        // echo "<br>Tamaño:".sizeof($str);

        // echo "<br><br>Datos 1 x 1 <br>________________________________________<br>";
        // //Recorre el array para descomponer datos
        // for ($i=0; $i < sizeof($str); $i++) { 
        //     # code...
        //     echo "<br>Dato ".$i." = ".$str[$i];
        // }
        // //signos a comparar
        // $signos = Signo::all();
        // echo "<br>Signos a comparar <br>";
        // foreach($signos as $item)
        // {
        //     echo "<br>Signo: ".$item->Signo;
        // }
        // // Contador de variables o constantes
        // $numSignos = 0;
        // $numVar = 0;
        // $arrVar = array();
        // $tempVar = ''; 
        // $swVar = false;
        // for ($i=0; $i < sizeof($str); $i++) { 
        //     # code...
        //     // Recorreo los signos y los compara
        //     foreach($signos as $item)
        //     {
        //         if($item->Signo == $str[$i])
        //         {
        //             $numSignos++;
        //             $swVar = false;
        //             $tempVar = '';
        //             if(sizeof(str_split($tempVar)) > 0)
        //             {
        //                 $arrVar[$numVar] = $tempVar;
        //                 $numVar++;
        //                 $tempVar = '';
        //             }
        //         }else{
        //             $swVar = true;
        //             if($swVar == true)
        //             {
        //                 $tempVar .= $str[$i];
        //                 // echo "<br>tempVar:".$tempVar;
        //             }
        //         }
        //     }   
        // }
        // echo "<br>Signos: ".$numSignos;
        // echo "<br>Variables: ".$numVar;
        // // var_dump($arrVar);
    }
}
