<?php

namespace App\Http\Controllers\Seguimiento;

use App\Http\Controllers\Controller;
use App\Models\Incidencias;
use App\Models\ModulosSistema;
use App\Models\SubmodulosSistema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class IncidenciasController extends Controller
{
    public function admin(){
        $user = Auth::user()->id;
        $model= db::table('ViewIncidencias')->where('Id_user', $user)->get();
        $modulos = DB::table('menu_items')->where('parent_id', null)->get();
        $prioridad = DB::table('incidencias_prioridad')->get();
        $estado = DB::table('incidencias_estado')->get();
        $usuarios = DB::table('users')->get();
        return view('seguimiento.incidenciasAdmin', compact('prioridad', 'model', 'user', 'modulos', 'user', 'estado', 'usuarios')); 
    }
    public function index(Request $request){
        $model = DB::table('ViewIncidencias')->where('Id_estado', "<", 3)->orderBy('Id_incidencia', 'desc')->get();
        
        $data = array(
            'model' => $model
        );
       
        return response()->json($data);
    }
    public function lista(){
        $user = Auth::user()->id;
        $model = db::table('ViewIncidencias')->where('Id_user', $user)->get();
        $modulos = DB::table('menu_items')->where('parent_id', null)->get();
        $prioridad = DB::table('incidencias_prioridad')->get();
        return view('seguimiento.listaIncidencias', compact('prioridad', 'model', 'user', 'modulos', 'user')); 
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
       
        if ($res->file == NULL) {
            $imagenComoBase64 = "";
        } else {
            $contenidoBinario = file_get_contents($res->file);
            $imagenComoBase64 = base64_encode($contenidoBinario);
        }
        $user = Auth::user()->id;
        $model = Incidencias::create([
            'Id_prioridad' => $res->prioridad,
            'Id_modulo' => $res->modulo,
            'Id_submodulo' => $res->submodulo,
            'Id_user' => $user,
            'Descripcion' => $res->descripcion,
            'Imagen' => $imagenComoBase64,
            'Id_estado' => 1,
        ]);
        
        return redirect('admin/seguimiento/incidencias/lista');
    }
    public function update(Request $request){
        $model = Incidencias::find($request->id);
        $model->Observacion = $request->observacion;
        $model->Id_Estado = $request->estado;
        $model->save();

        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function getIncidencia(Request $request){
        $model = DB::table('ViewIncidencias')->where('Id_incidencia', $request->id)->first();
        $estado = DB::table('incidencias_estado')->get();
        $data = array(
            'model' => $model,
            'estado' => $estado,
        );
        return response()->json($data);
    }
    public function buscar(Request $request){
        if ($request->modulo != 0 && $request->submodulo == 0 && $request->prioridad == 0 && $request->estado == 0) {

            $model = DB::table('ViewIncidencias')->where('Id_modulo', $request->modulo)->get();

        } elseif ($request->modulo == 0 && $request->submodulo == 0 && $request->prioridad == 0 && $request->estado != 0) {
            $model = DB::table('ViewIncidencias')->where('Id_estado', $request->estado)->get();

        } elseif ($request->modulo == 0 && $request->submodulo == 0 && $request->prioridad != 0){
             
            $model = DB::table('ViewIncidencias')->where('Id_prioridad', $request->prioridad)->get();

        }elseif ($request->modulo != 0 && $request->submodulo != 0 && $request->prioridad == 0){
             
            $model = DB::table('ViewIncidencias')->where('Id_Modulo', $request->modulo)->where('Id_submodulo', $request->submodulo)->get();

        } elseif ($request->modulo != 0 && $request->submodulo != 0 && $request->prioridad != 0){
            $model = DB::table('ViewIncidencias')->where('Id_modulo', $request->modulo)
            ->where('Id_submodulo', $request->submodulo)
            ->where('Id_prioridad', $request->prioridad)
            ->get();
    
        } elseif ($request->modulo == 0 && $request->submodulo == 0 && $request->prioridad == 0){
            $model = DB::table('ViewIncidencias')->orderBy('Id_modulo', 'desc')->get();
        }

        $data = array(
            'modulo' => $request->modulo,
            'Submodulo' => $request->submodulo,
            'prioridad' => $request->prioridad,
            'model' => $model,
        );

        return response()->json($data);
    }
    
}
