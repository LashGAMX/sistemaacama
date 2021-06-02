<?php

namespace App\Http\Controllers\Campo;

use App\Http\Controllers\Controller;
use Facade\FlareClient\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CampoController extends Controller
{
    //
    public function asignar()
    {
        $model = DB::table('ViewSolicitud')->where('Id_servicio',1)->orWhere('Id_servicio',3)->get();
        $intermediarios = DB::table('ViewIntermediarios')->where('deleted_at',NULL)->get();
        return view('campo.asignarMuestreo',compact('model','intermediarios'));
    }
    public function
}
