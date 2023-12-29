<?php

namespace App\Http\Controllers\Recursos;

use App\Http\Controllers\Controller;
use App\Models\CodigoParametros;
use App\Models\LoteDetalleDirectos;
use App\Models\LoteDetalleDureza;
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
        $model2 = LoteDetalleDirectos::where('Id_codigo', $model->Id_codigo)->get(); 
        $data = array(
            'model' => $model,
            'model2' => $model2,
        );

         return response()->json($data);
        $id = $model->Id_codigo; 
    }
    public function tirarlabasura(Request $req) {
        $model = DB::table("lote_detalle_directos")->where('Id_codigo', $req->id)->delete();
        $data = array(
            'id' => $req->id,
        );
        return response()->json($data);
    }
}