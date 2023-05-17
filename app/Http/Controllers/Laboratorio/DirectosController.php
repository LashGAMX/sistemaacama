<?php

namespace App\Http\Controllers\Laboratorio;

use App\Http\Controllers\Controller;
use App\Models\BitacoraDirectos;
use App\Models\CodigoParametros;
use App\Models\LoteAnalisis;
use App\Models\LoteDetalleDirectos;
use App\Models\Parametro;
use App\Models\PlantillaDirectos;
use App\Models\ControlCalidad;
use App\Models\Promedio;
use App\Models\User;
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
    public function getPendientes(Request $res)
    {
        $model = array();
        $temp = array();
        $codigo = DB::table('ViewCodigoParametro')->where('Asignado', 0)->get();
        $param = DB::table('ViewParametroUsuarios')->where('Id_user', Auth::user()->id)->get();

        foreach ($codigo as $item) {
            $temp = array();
            foreach ($param as $item2) {
                if ($item->Id_parametro == $item2->Id_parametro) {
                    array_push($temp, $item->Codigo);
                    array_push($temp, "(" . $item->Id_parametro . ") " . $item->Parametro);
                    array_push($temp, $item->Hora_recepcion);
                    array_push($model, $temp);
                    break;
                }
            }
        }
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function createControlCalidadDirectos(Request $request)
    {
        $muestra = LoteDetalleDirectos::where('Id_detalle', $request->idMuestra)->first();

        $model = $muestra->replicate();
        $model->Id_control = $request->idControl;
        $model->save();

        $data = array(
            'model' => $model,
        );
        return response()->json($data);
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
        $lote = DB::table('ViewLoteAnalisis')->where('Id_lote', $res->idLote)->first();
        $plantilla = BitacoraDirectos::where('Id_lote', $res->idLote)->get();
        if ($plantilla->count()) {
        } else {
            $plantilla = PlantillaDirectos::where('Id_parametro', $lote->Id_tecnica)->get();
        }
        $data = array(
            'plantilla' => $plantilla,
        );
        return response()->json($data);
    }
    public function setPlantilla(Request $res)
    {
        $lote = DB::table('ViewLoteAnalisis')->where('Id_lote', $res->id)->first();
        $temp = BitacoraDirectos::where('Id_lote', $res->id)->get();
        if ($temp->count()) {
            $model = BitacoraDirectos::where('Id_lote', $res->id)->first();
            $model->Titulo = $res->titulo;
            $model->Texto = $res->texto;
            $model->Rev = $res->rev;
            $model->save();
        } else {
            $model = BitacoraDirectos::create([
                'Id_lote' => $res->id,
                'Id_parametro' => $lote->Id_tecnica,
                'Titulo' => $res->titulo,
                'Texto' => $res->texto,
                'Rev' => $res->rev,
            ]);
        }
        $data = array(
            'lote' => $lote,
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
            'Analizo' => Auth::user()->id,
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
                'Analizo' =>  Auth::user()->id,
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
        $controlModel = ControlCalidad::all();
        return view('laboratorio.directos.captura', compact('parametro', 'controlModel'));
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

    public function operacionTurbiedad(Request $request)
    {
        $resultado = 0;
        $promedio = ($request->l1 + $request->l2 + $request->l3) / 3;
        $resultado = round($promedio, 2);
        $model = LoteDetalleDirectos::find($request->idDetalle);
        $model->Resultado = $resultado;
        $model->Factor_dilucion = $request->factor;
        $model->Vol_muestra = $request->volumen;
        $model->Lectura1 = $request->l1;
        $model->Lectura2 = $request->l2;
        $model->Lectura3 = $request->l3;
        $model->Promedio = $promedio;
        $model->save();

        $data = array(
            'promedio' => $promedio,
            'res' => $resultado,
        );
        return response()->json($data);
    }
    public function operacionCloro(Request $request)
    {
        $resultado = 0;
        $dilusion = $request->dilucion;
        $promedio = ($request->l1 + $request->l2 + $request->l3) / 3;
        $resultado = round($promedio * $dilusion, 2);
        $model = LoteDetalleDirectos::find($request->idDetalle);
        $model->Factor_dilucion = $request->dilucion;
        $model->Resultado = $resultado;
        $model->Vol_muestra = $request->volumen;
        $model->Lectura1 = $request->l1;
        $model->Lectura2 = $request->l2;
        $model->Lectura3 = $request->l3;
        $model->Promedio = $promedio;
        $model->save();

        $data = array(
            'Dilu' => $dilusion,
            'promedio' => $promedio,
            'res' => $resultado,
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
        //$factor = 0;
        $dilusion = 50 / $res->volumen;
        $promedio = ($res->aparente + $res->verdadero) * $res->dilusion;
        // if ($res->verdadero != 0) {

        //     if ($promedio >= 1 && $promedio <= 50) {
        //         $factor = $factor + 1;
        //     } else if ($promedio >= 51 && $promedio <= 100) {
        //         $factor = $factor + 5;
        //     } else if ($promedio >= 101 && $promedio <= 250) {
        //         $factor = $factor + 10;
        //     } else if ($promedio >= 251 && $promedio <= 500) {
        //         $factor = $factor + 20;
        //     }
        // }
        $resultado = $promedio + $res->factor;

        $model = LoteDetalleDirectos::find($res->idDetalle);
        $model->Resultado = $resultado;
        $model->Color_a = $res->aparente;
        $model->Color_v = $res->verdadero;
        $model->Factor_dilucion = $dilusion;
        $model->Vol_muestra = $res->volumen;
        $model->Ph = $res->ph;
        $model->Factor_correcion = $res->factor;
        $model->save();

        $data = array(
            'resultado' => $resultado,
            'dilusion' => $dilusion,
            'factor' => $res->factor,
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
            'margin_top' => 40,
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
            case 14: // PH
                $model = DB::table('ViewLoteDetalleDirectos')->where('Id_lote', $idLote)->get();
                $plantilla = BitacoraDirectos::where('Id_lote', $idLote)->get();
                if ($plantilla->count()) {
                } else {
                    $plantilla = PlantillaDirectos::where('Id_parametro', $lote->Id_tecnica)->get();
                }
                $procedimiento = explode("NUEVASECCION",$plantilla[0]->Texto);
                //Comprobación de bitacora analizada
                $comprobacion = LoteDetalleDirectos::where('Liberado', 0)->where('Id_lote', $idLote)->get();
                if ($comprobacion->count()) {
                    $analizo = "";
                } else {
                    $analizo = User::where('id', $model[0]->Analizo)->first();
                }
                $reviso = User::where('id', 17)->first();

                $data = array(
                    'lote' => $lote,
                    'model' => $model,
                    'plantilla' => $plantilla,
                    'analizo' => $analizo, 
                    'reviso' => $reviso,
                    'comprobacion' => $comprobacion,
                    'procedimiento' => $procedimiento,
                );

                $htmlHeader = view('exports.laboratorio.directos.ph.bitacoraHeader', $data);
                $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                $htmlCaptura = view('exports.laboratorio.directos.ph.bitacoraBody', $data);
                $htmlFooter = view('exports.laboratorio.directos.ph.bitacoraFooter', $data);
                $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                $mpdf->CSSselectMedia = 'mpdf';
                $mpdf->WriteHTML($htmlCaptura);
                break;
            case 218: // Cloro
                $model = DB::table('ViewLoteDetalleDirectos')->where('Id_lote', $idLote)->get();
                $plantilla = PlantillaDirectos::where('Id_parametro', 218)->first();
                $data = array(
                    'lote' => $lote,
                    'model' => $model,
                    'plantilla' => $plantilla
                );

                $htmlHeader = view('exports.laboratorio.directos.cloro.bitacoraHeader', $data);
                $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                $htmlCaptura = view('exports.laboratorio.directos.cloro.bitacoraBody', $data);
                $htmlFooter = view('exports.laboratorio.directos.cloro.bitacoraFooter', $data);
                $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');  
                $mpdf->CSSselectMedia = 'mpdf';
                $mpdf->WriteHTML($htmlCaptura);
                break;
            case 110:
                $model = DB::table('ViewLoteDetalleDirectos')->where('Id_lote', $idLote)->get();
                $plantilla = PlantillaDirectos::where('Id_parametro', 110)->first();
                $data = array(
                    'lote' => $lote,
                    'model' => $model,
                    'plantilla' => $plantilla
                );

                $htmlHeader = view('exports.laboratorio.directos.ph.127.bitacoraHeader', $data);
                $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                $htmlCaptura = view('exports.laboratorio.directos.ph.127.bitacoraBody', $data);
                $htmlFooter = view('exports.laboratorio.directos.ph.127.bitacoraFooter', $data);
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
                $htmlFooter = view('exports.laboratorio.directos.color.bitacoraFooter', $data);
                $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                $htmlHeader = view('exports.laboratorio.directos.color.bitacoraHeader', $data);
                $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                $htmlCaptura = view('exports.laboratorio.directos.color.bitacoraBody', $data);
                $mpdf->CSSselectMedia = 'mpdf';
                $mpdf->WriteHTML($htmlCaptura);
                break;
            case 98: // Turbiedad

                $model = DB::table('ViewLoteDetalleDirectos')->where('Id_lote', $idLote)->get();
                $data = array(
                    'lote' => $lote,
                    'model' => $model,
                    'plantilla' => $plantilla
                );
                $htmlHeader = view('exports.laboratorio.directos.turbiedad.bitacoraHeader', $data);
                $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                $htmlCaptura = view('exports.laboratorio.directos.turbiedad.bitacoraBody', $data);
                $htmlFooter = view('exports.laboratorio.directos.turbiedad.bitacoraFooter', $data);
                $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
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
