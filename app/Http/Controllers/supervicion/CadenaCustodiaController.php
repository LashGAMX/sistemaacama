<?php

namespace App\Http\Controllers\Supervicion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CadenaCustodiaController extends Controller
{
    //
    public function cadenaCustodia()
    {  
        $model = DB::table('ViewSolicitud')->orderby('Id_solicitud','desc')->get();         
        return view('supervicion.cadena.cadena',compact('model'));
    } 
    public function detalleCadena($id)
    {
        $model = DB::table('ViewSolicitud')->where('Id_solicitud',$id)->first();         
        return view('supervicion.cadena.detalleCadena',compact('model'));
    }
}
 