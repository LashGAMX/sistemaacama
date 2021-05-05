<?php

namespace App\Http\Controllers\Beto;

use App\Http\Controllers\Controller; 
use App\Models\Signo;
use Illuminate\Http\Request;


use FormulaParser\FormulaParser;

class BetoController extends Controller
{
    //
    public function formula()
    {

        $formula = "(a+cb)";
        // $arr = str_split();

        $string = '
        <ul>
            <li>Name: John</li>
            <li>Surname- Doe</li>
            <li>Phone* 555 0456789</li>
            <li>Zip code= ZP5689</li>
        </ul>
        ';
        
        // $chunks = preg_split('/(:|-|\*|=)/', $string,-1, PREG_SPLIT_NO_EMPTY);
        // var_dump($chunks);}
        // $data = preg_split('/(:|-|\+*|=|()|)/', $formula,-1, PREG_SPLIT_NO_EMPTY);
        // var_dump($data);
        // $text = "here is a sample: this text, and this will be exploded. this also | this one too :)";
        // $exploded = $this->multiexplode(array(",",".","|",":"),$text);

        $exploded = $this->multiexplode(array("(",")","+"),$formula);
        var_dump($exploded);

// This is just a simple way to debug stuff ;-)
// echo '<pre>';
// print_r($chunks);
// echo '</pre>';

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
