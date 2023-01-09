<?php

namespace App\Http\Controllers\Laboratorio;

use App\Http\Controllers\Controller;
use App\Models\BitacoraDirectos;
use App\Models\CodigoParametros;
use App\Models\LoteAnalisis;
use App\Models\LoteDetalleDirectos;
use App\Models\Parametro;
use App\Models\PlantillaDirectos;
use App\Models\Promedio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DirectosController extends Controller
{
    public function lote()
    {
        $parametro = DB::table('ViewParametroUsuarios')->where('Id_user', Auth::user()->id)->get();

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
    public function getDetalleLote(Request $res)
    {
        $model = BitacoraDirectos::where('Id_lote', $res->idLote)->get();
        if ($model->count()) {
            $model = BitacoraDirectos::where('Id_lote', $res->idLote)->first();
        } else {
            $model = PlantillaDirectos::where('Id_parametro', $res->idParametro)->first();
        }
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function setPlantilla(Request $res)
    {
        $model = BitacoraDirectos::where('Id_lote', $res->idLote)->get();
        if ($model->count()) {
            $model = BitacoraDirectos::where('Id_lote', $res->idLote)->first();
            $model->Texto = $res->texto;
            $model->save();
        } else {
            $model = BitacoraDirectos::create([
                'Id_lote' => $res->idLote,
                'Texto' => $res->texto,
            ]);
        }
        $data = array(
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
        $sw = true;
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
        $parametro = DB::table('ViewParametroUsuarios')->where('Id_user', Auth::user()->id)->get();
        return view('laboratorio.directos.captura', compact('parametro'));
    }

    public function getLoteCapturaDirecto(Request $res)
    {
        $detalle = DB::table('ViewLoteDetalleDirectos')->where('Id_lote', $res->idLote)->get();

        $data = array(
            'detalle' => $detalle,
        );

        return response()->json($data);
    }
    public function operacion(Request $res)
    {
        $resultado = "";

        $promedio = ($res->l1 + $res->l2 + $res->l3) / 3;

        switch ($res->id) {
            case 110:
            case 14:
                $resultado = round($promedio, 1);
                break;
            case 67:
            case 68:
                $resultado = round($promedio, 0);
                break;
            default:
                $resultado = round($promedio, 3);
                break;
        }

        $model = LoteDetalleDirectos::find($res->idDetalle);
        $model->Resultado = $resultado;
        $model->Lectura1 = $res->l1;
        $model->Lectura2 = $res->l2;
        $model->Lectura3 = $res->l3;
        $model->Temperatura = $res->temp;
        $model->Promedio = $res->promedio;
        $model->save();


        $data = array(
            'resultado' => $resultado,
            'model' =>  $model,
        );
        return response()->json($data);
    }
    public function operacionTemperatura(Request $request)
    {
        $resultado = "";

        $promedio = ($request->l1 + $request->l2 + $request->l3) / 3;
        $resultado = round($promedio, 3);

        $model = LoteDetalleDirectos::find($request->idDetalle);
        $model->Resultado = $resultado;
        $model->Lectura1 = $request->l1;
        $model->Lectura2 = $request->l2;
        $model->Lectura3 = $request->l3;
        $model->Promedio = $request->promedio;
        $model->save();


        $data = array(
            'res' => $resultado,
            'model' =>  $model,
        );
        return response()->json($data);
    }
    public function operacionColor(Request $res)
    {
        $resultado = 0;
        $factor = 0;
        $dilusion = 50 / $res->volumen;
        $promedio = ($res->aparente + $res->verdadero) * $res->dilusion;
        if ($res->verdadero != 0) {

            if ($promedio >= 1 && $promedio <= 50) {
                $factor = $factor + 1;
            } else if ($promedio >= 51 && $promedio <= 100) {
                $factor = $factor + 5;
            } else if ($promedio >= 101 && $promedio <= 250) {
                $factor = $factor + 10;
            } else if ($promedio >= 251 && $promedio <= 500) {
                $factor = $factor + 20;
            }
        }
        $resultado = $promedio + $factor;

        $model = LoteDetalleDirectos::find($res->idDetalle);
        $model->Resultado = $resultado;
        $model->Color_a = $res->aparente;
        $model->Color_v = $res->verdadero;
        $model->Factor_dilucion = $dilusion;
        $model->Vol_muestra = $res->volumen;
        $model->Ph = $res->ph;
        $model->Factor_correcion = $factor;
        $model->save();

        $data = array(
            'resultado' => $resultado,
            'dilusion' => $dilusion,
            'factor' => $factor,
            'promedio' => $promedio
        );

        return response()->json($data);
    }
    public function enviarObsGeneral(Request $request)
    {


        $model = LoteDetalleDirectos::where('Id_lote', $request->idLote)->get();
        foreach ($model as $item) {
            $update = LoteDetalleDirectos::find($item->Id_detalle);
            $update->Observacion = $request->observacion;
            $update->save();
        }

        $data = array(
            'model' => $update
        );
        return response()->json($data);
    }
    public function updateObsMuestra(Request $request)
    {

        $update = LoteDetalleDirectos::find($request->idDetalle);
        $update->Observacion = $request->observacion;
        $update->save();

        $data = array(
            'model' => $update
        );
        return response()->json($data);
    }
    public function getDetalleDirecto(Request $res)
    {
        $model = LoteDetalleDirectos::where("Id_detalle", $res->idDetalle)->first();
        $data = array('model' => $model);
        return response()->json($data);
    }

    public function liberarMuestra(Request $request)
    {
        $sw = false;
        $mensaje = "";
        $model = LoteDetalleDirectos::find($request->idMuestra);
        $model->Liberado = 1;
        if ($model->Resultado != null) {
            $sw = true;
            $model->save();
        }
        $modelCod = CodigoParametros::find($model->Id_codigo);
        $modelCod->Resultado = $model->Resultado;
        $modelCod->Analizo = Auth::user()->id;
        $modelCod->save();

        $model = LoteDetalleDirectos::where('Id_lote', $request->idLote)->where('Liberado', 1)->get();
        $loteModel = LoteAnalisis::find($request->idLote);
        $loteModel->Liberado = $model->count();
        $loteModel->save();


        $data = array(
            'sw' => $sw,
            'mensaje' => $mensaje,
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
        $mpdf->SetWatermarkImage(
            asset('/public/storage/MembreteVertical.png'),
            1,
            array(215, 280),
            array(0, 0),
        );
        $mpdf->showWatermarkImage = true;

        $lote = DB::table('ViewLoteAnalisis')->where('Id_lote', $idLote)->first();
        $plantilla = PlantillaDirectos::where('Id_parametro', $lote->Id_tecnica)->first();
        switch ($lote->Id_tecnica) {
            case 110:
            case 14: // PH
                $model = DB::table('ViewLoteDetalleDirectos')->where('Id_lote', $idLote)->get();
                $plantilla = PlantillaDirectos::where('Id_parametro', 14)->first();
                $data = array(
                    'lote' => $lote,
                    'model' => $model,
                    'plantilla' => $plantilla
                );

                $htmlHeader = view('exports.laboratorio.directos.ph.bitacoraHeader', $data);
                $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                $htmlCaptura = view('exports.laboratorio.directos.ph.bitacoraBody', $data);
                $htmlFooter = view('exports.laboratorio.directos.ph.bitacoraFooter', $data);
                $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                $mpdf->CSSselectMedia = 'mpdf';
                $mpdf->WriteHTML($htmlCaptura);
                break;
            case 67: // Conductividad

                $model = DB::table('ViewLoteDetalleDirectos')->where('Id_lote', $idLote)->get();
                $data = array(
                    'lote' => $lote,
                    'model' => $model,
                    'plantilla' => $plantilla
                );

                $htmlHeader = view('exports.laboratorio.directos.conductividad.bitacoraHeader', $data);
                $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                $htmlCaptura = view('exports.laboratorio.directos.conductividad.bitacoraBody', $data);
                $htmlFooter = view('exports.laboratorio.directos.ph.bitacoraFooter', $data);
                $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                $mpdf->CSSselectMedia = 'mpdf';
                $mpdf->WriteHTML($htmlCaptura);
                break;

            case 97: // PH

                $model = DB::table('ViewLoteDetalleDirectos')->where('Id_lote', $idLote)->get();
                $data = array(
                    'lote' => $lote,
                    'model' => $model,
                    'plantilla' => $plantilla
                );
                $htmlHeader = view('exports.laboratorio.directos.ph.bitacoraHeader', $data);
                $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                $htmlCaptura = view('exports.laboratorio.directos.ph.bitacoraBody', $data);
                $htmlFooter = view('exports.laboratorio.directos.ph.bitacoraFooter', $data);
                $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                $mpdf->CSSselectMedia = 'mpdf';
                $mpdf->WriteHTML($htmlCaptura);
                break;
            case 66:
            case 102: // COLOR VERDADERO
                $model = DB::table('ViewLoteDetalleDirectos')->where('Id_lote', $idLote)->get();
                $data = array(
                    'lote' => $lote, 
                    'model' => $model,
                    'plantilla' => $plantilla
                );
                $htmlHeader = view('exports.laboratorio.directos.color.bitacoraHeader', $data);
                $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                $htmlCaptura = view('exports.laboratorio.directos.color.bitacoraBody', $data);
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
