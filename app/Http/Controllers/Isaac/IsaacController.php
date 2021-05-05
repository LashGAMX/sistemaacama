<?php

namespace App\Http\Controllers\Isaac;

use App\Http\Controllers\Controller;
use App\Models\Signo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Writer\Ods\Formula;

class IsaacController extends Controller
{
   

    public function index()
    {
        return view('isaac.isaac');
    }
    
    public function agregar(Request $request)
    {
        $request->formula;
        

    }

    // public function descomponer()
    // {
    //     $formula = "mx+b";
    //      $tipo = [];
    //      $model = Signo::all();

    //      $formula_div = str_split($formula);
    //      echo $formula."<br>";
    //      echo "---------------------------------------"."<br>";
        
    //      for ($i = 0; $i < sizeof($formula_div); $i++) {
             
    //         foreach($model as $item)
    //         {
    //             if ($item->Signo == $formula_div[$i]){
    //                 echo "signo"."<br>";
    //             }
    //             else
    //             {
    //                 echo "letra"."<br>";
    //             }
    //         }
            
    //      }
    // }
}