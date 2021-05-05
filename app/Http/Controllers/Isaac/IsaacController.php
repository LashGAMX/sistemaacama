<?php

namespace App\Http\Controllers\Isaac;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Writer\Ods\Formula;

class IsaacController extends Controller
{

    public function index()
    {
         $formula = "mx+b";
         $tipo = [];

         $formula_div = str_split($formula);
         echo $formula."<br>";
         echo "---------------------------------------"."<br>";
        
         for ($i = 0; $i <count($formula_div); $i++) {
             if ($formula_div[$i] == "m"){
                 echo "variable"."<br>";
             } else if ($formula_div[$i] == "b"){
                echo "constante"."<br>";
             }
             else if ($formula_div[$i] == "x"){
                echo "variable"."<br>";
             }
             else if ($formula_div[$i] == "+"){
                echo "suma"."<br>";
             }
         }
        
    }
}