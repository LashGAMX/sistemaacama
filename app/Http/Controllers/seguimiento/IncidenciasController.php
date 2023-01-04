<?php

namespace App\Http\Controllers\seguimiento;

use App\Http\Controllers\Controller;
use App\Models\ModulosSistema;
use App\Models\SubmodulosSistema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class IncidenciasController extends Controller
{

    public function lista(){
        $user = Auth::user()->id;
        $model = db::table('incidencias')->where('Id_user', $user)->get();

        return view('seguimiento.listaIncidencias', compact('model', 'user')); 
    }
    public function incidencias(){
        $modulos = ModulosSistema::all();

        return view('seguimiento.incidencias', compact('modulos')); 
    }
    public function getsubmodulos(Request $request){
        $submodulos = SubmodulosSistema::where('Id_modulo', $request->modulo)->get();

        $data = array(
            'submodulos' => $submodulos
        );
        return response()->json($data);
    }
    public function create(Request $res){
        $user = Auth::user()->id;
        $model = db::create([
            'Id_user' => $user,
            'Id_prioridad' => $res->prioridad,
            'Id_modulo' => $res->modulo,
            'Id_submodulo' => $res->submodulo,
            'Descripcion' => $res->descripcion,
            'Id_estado' => 1,

        ]);
        return response()->json($model);
    }
}
