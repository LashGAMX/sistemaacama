<?php

namespace App\Http\Controllers\seguimiento;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class IncidenciasController extends Controller
{

    public function lista(){
        $user = Auth::user()->id;
        $model = db::table('incidencias')->where('Id_user', $user)->get();

        return view('seguimiento.listaIncidencias', compact('model')); 
    }
    public function incidencias(){
        return view('seguimiento.incidencias'); 
    }
    public function create(Request $res){
        $user = Auth::user()->id;
        $model = db::table('incidencias')->where('Id_user', $user)
        ->update(['Id_prioridad' => $res]);

    }
}
