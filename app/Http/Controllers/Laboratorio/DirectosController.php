<?php

namespace App\Http\Controllers\Laboratorio;

use App\Http\Controllers\Controller;
use App\Models\CodigoParametros;
use App\Models\LoteAnalisis;
use App\Models\LoteDetalleDirectos;
use App\Models\Parametro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DirectosController extends Controller
{
    public function lote()
    {
        $parametro = DB::table('ViewParametros')->where('Id_area', 7)->get();
        return view('laboratorio.directos.lote', compact('parametro'));
    }
    public function getLote(request $res)
    {
        $sw = false;
        $model = DB::table('ViewLoteAnalisis')->where('Id_tecnica',$res->id)->where('Fecha', $res->fecha)->get();
        if($model->count())
        {
            $sw = true;
        }
        $data = array(
            'sw' => $sw,
            'model' => $model,
        );
        return response()->json($data);
    }
    public function setLote(Request $res)
    {
        $model = LoteAnalisis::create([
            'Id_area' => 7,
            'Id_tecnica' => $res->id,
            'Asignado' => 0,
            'Liberado' => 0,
            'Fecha' => $res->fecha,
        ]); 
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function loteDetalle($id)
    {
        return view('laboratorio.directos.asignarMuestraLote',compact('id'));
    }
    //* Muestra los parametros sin asignar a lote
    public function muestraSinAsignar(Request $request)
    {
        $lote = LoteAnalisis::find($request->idLote);
        $model = DB::table('ViewCodigoParametro')
            ->where('Id_parametro', $lote->Id_tecnica)
            ->where('Asignado', '!=', 1)
            ->get();
        $data = array(
            'model' => $model,
            'lote' => $lote,
        );
        return response()->json($data);
    }
    public function getMuestraAsignada(Request $res)
    {
        $model = DB::table('ViewLoteDetalleDirectos')->where('Id_lote', $res->idLote)->get();
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
        //! Eliminar parametro muestra1
        public function delMuestraLote(Request $request)
        {
            $loteModel = LoteAnalisis::where('Id_lote', $request->idLote)->first();
            
            $detModel = DB::table('lote_detalle_directos')->where('Id_detalle', $request->idDetalle)->delete();
            $detModel = LoteDetalleDirectos::where('Id_lote', $request->idLote)->get();   
    
            $loteModel = LoteAnalisis::find($request->idLote);
            $loteModel->Asignado = $detModel->count();
            $loteModel->Liberado = 0;
            $loteModel->save();
    
    
            $solModel = CodigoParametros::where('Id_solicitud', $request->idSol)->where('Id_parametro', $request->idParametro)->first();
            $solModel->Asignado = 0;
            $solModel->save();
    
            $data = array(
                'idDetalle' => $request->idDetalle,
            );
    
            return response()->json($data);
        }

        //! Captura
    public function captura(){
        $parametro = Parametro::where('id_area', 7)->get();
        return view('laboratorio.directos.captura', compact('parametro'));
    }

    public function getLoteCapturaDirecto(Request $request){
        $loteModel = LoteAnalisis::where('Id_lote', $request->idLote)->first();
        $detalle = DB::table('ViewLoteDetalleDirectos')->get();

        $data = array(
            'detalle' => $detalle,
        );

        return response()->json($data);
    }
}
