<?php

namespace App\Http\Controllers\Recursos;

use App\Http\Controllers\Controller;
use App\Models\CodigoParametros;
use App\Models\LoteDetalleCloro;
use App\Models\LoteDetalleColiformes;
use App\Models\LoteDetalleDbo;
use App\Models\LoteDetalleDirectos;
use App\Models\LoteDetalleDureza;
use App\Models\LoteDetalleEnterococos;
use App\Models\LoteDetalleGA;
use App\Models\LoteDetalleNitrogeno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
class RecursoController extends Controller
{
    //
    public function index(){
        $idUser = Auth::user()->id;
        return view('recurso.recurso',compact('idUser'));
    }
    public function basura(){
       
        return view ('beto.basura');
    }

    public function buscarBasura(Request $req){
        $model = CodigoParametros::where('Codigo', $req->folio)->where('Id_parametro', $req->parametro)->first(); 
        $model2 = LoteDetalleEnterococos::where('Id_codigo', $model->Id_codigo)->get(); 
        $data = array(
            'model' => $model,
            'model2' => $model2,
        );

         return response()->json($data);
       // $id = $model->Id_codigo; 
    }
   
    public function tirarlabasura(Request $req) {
        $model = DB::table("lote_detalle_enterococos")->where('Id_codigo', $req->id)->get();
        foreach ($model as $item) {
            $model2 = DB::table("lote_detalle_enterococos")->where('Id_codigo', $item->Id_codigo)->delete();
        }
        $data = array(
            'id' => $req->id,
        );
        return response()->json($data);
    }
    public function reasignar(Request $req){
        $model = CodigoParametros::where('Id_codigo', $req->id)->first();
        $model->Asignado = 0;
        $model->save();

        $data = array(
            "model" => $model,
        );

        return response()->json($data);
    }

}