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
        $model = db::table('ViewIncidencias')->where('Id_user', $user)->get();
        $modulos = DB::table('menu_items')->where('parent_id', null)->get();
        $prioridad = DB::table('incidencias_prioridad')->get();
        return view('seguimiento.listaIncidencias', compact('prioridad', 'model', 'user', 'modulos')); 
    }
    public function incidencias(){
        $modulos = DB::table('menu_items')->where('parent_id', null)->get();
        $prioridad = DB::table('incidencias_prioridad')->get();
        return view('seguimiento.incidencias', compact('modulos', 'prioridad')); 
    }
    public function getsubmodulos(Request $request){
        $submodulos = DB::table('menu_items')->where('parent_id', $request->modulo)->get();

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
    public function buscar(Request $request){
        $model = DB::table('ViewIncidencias')->where('Id_modulo', $request->modulo)
        ->where('Id_submodulo', $request->submodulo)
        ->where('Id_prioridad', $request->prioridad)
        ->get();

        $data = array(
            'modulo' => $request->modulo,
            'Submodulo' => $request->submodulo,
            'prioridad' => $request->prioridad,
            'model' => $model,
        );

        return response()->json($data);
    }
    
}
