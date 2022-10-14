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
        $model = DB::table('ViewLoteAnalisis')->where('Id_tecnica', $res->id)->where('Fecha', $res->fecha)->get();
        if ($model->count()) {
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
        return view('laboratorio.directos.asignarMuestraLote', compact('id'));
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
    //* Asignar parametro a lote
    public function asignarMuestraLote(Request $request)
    {
        $sw = false;
        $loteModel = LoteAnalisis::where('Id_lote', $request->idLote)->first();
        $paraModel = Parametro::find($loteModel->Id_tecnica);

        $model = LoteDetalleDirectos::create([
            'Id_lote' => $request->idLote,
            'Id_analisis' => $request->idAnalisis,
            'Id_codigo' => $request->idSol,
            'Id_parametro' => $loteModel->Id_tecnica,
            'Id_control' => 1,
            'Analizo' => 1,
        ]);
        $detModel = LoteDetalleDirectos::where('Id_lote', $request->idLote)->get();


        $solModel = CodigoParametros::find($request->idSol);
        $solModel->Asignado = 1;
        $solModel->save();


        $loteModel = LoteAnalisis::find($request->idLote);
        $loteModel->Asignado = $detModel->count();
        $loteModel->Liberado = 0;
        $loteModel->save();

        $data = array(
            'idArea' => $paraModel->Id_area,
            'sw' => $sw,
            'model' => $paraModel,
        );
        return response()->json($data);
    }
    public function sendMuestrasLote(Request $res)
    {
        $sw = false;
        $loteModel = LoteAnalisis::where('Id_lote', $res->idLote)->first();
        $paraModel = Parametro::find($loteModel->Id_tecnica);

        for ($i = 0; $i < sizeof($res->idCodigos); $i++) {
            $sol = CodigoParametros::where('Id_codigo', $res->idCodigos[$i])->first();
            $model = LoteDetalleDirectos::create([
                'Id_lote' => $res->idLote,
                'Id_analisis' => $sol->Id_solicitud,
                'Id_codigo' => $res->idCodigos[$i],
                'Id_parametro' => $loteModel->Id_tecnica,
                'Id_control' => 1,
                'Analizo' => 1,
            ]);
            $solModel = CodigoParametros::find($sol->Id_codigo);
            $solModel->Asignado = 1;
            $solModel->save();
        }
        $detModel = LoteDetalleDirectos::where('Id_lote', $res->idLote)->get();



        $loteModel = LoteAnalisis::find($res->idLote);
        $loteModel->Asignado = $detModel->count();
        $loteModel->Liberado = 0;
        $loteModel->save();

        $data = array(
            'idArea' => $paraModel->Id_area,
            'sw' => $sw,
            'model' => $paraModel,
        );
        return response()->json($data);
    }
    //! Captura
    public function captura()
    {
        $parametro = Parametro::where('id_area', 7)->get();
        return view('laboratorio.directos.captura', compact('parametro'));
    }

    public function getLoteCapturaDirecto(Request $request)
    {
        $loteModel = LoteAnalisis::where('Id_lote', $request->idLote)->first();
        $detalle = DB::table('ViewLoteDetalleDirectos')->get();

        $data = array(
            'detalle' => $detalle,
        );

        return response()->json($data);
    }
    public function operacion(Request $request){
        $res = "";

        $promedio = $request->l1 + $request->l2 + $request->l3 / 3;
        $res = round($promedio, 3);

        $model = LoteDetalleDirectos::find($request->idDetalle);
        $model->Resultado = $res;
        $model->Lectura1 = $request->l1;
        $model->Lectura2 = $request->l2;
        $model->Lectura3 = $request->l3;
        $model->temperatura = $request->temperatura1;
        $model->save();


        $data = array(
            'res' => $res,
            'model' =>  $model,
        );
        return response()->json($data);
    }
    public function exportPdfDirecto($idLote)
    {
        //Opciones del documento PDF
        $mpdf = new \Mpdf\Mpdf([
            'orientation' => 'P',
            'format' => 'letter',
            'margin_left' => 10,
            'margin_right' => 10, 
            'margin_top' => 31,
            'margin_bottom' => 45,
            'defaultheaderfontstyle' => ['normal'],
            'defaultheaderline' => '0'
        ]);

        $lote = DB::table('ViewLoteAnalisis')->where('Id_lote', $idLote)->first();
        switch ($lote->Id_tecnica) {
            case 152: // COT
    
                $model = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $idLote)->get();
                // $textoProcedimiento = ReportesMb::where('Id_reporte', 3)->first();
                $curva = CurvaConstantes::where('Id_parametro', $lote->Id_tecnica)->where('Fecha_inicio', '<=', $lote->Fecha)->where('Fecha_fin', '>=', $lote->Fecha)->first();
                $data = array(  
                    'lote' => $lote,
                    'model' => $model,
                    'curva' => $curva,
                    // 'textoProcedimiento' => $textoProcedimiento,
                );
                
                $htmlHeader = view('exports.laboratorio.fq.espectro.cot.capturaHeader', $data);
                $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                $htmlCaptura = view('exports.laboratorio.fq.espectro.cot.capturaBody', $data);
                $mpdf->CSSselectMedia = 'mpdf';
                $mpdf->WriteHTML($htmlCaptura);
                break; 
            default:
                # code...
                break;
        }

        // var_dump($model);
        
        $mpdf->Output();
    }
}
