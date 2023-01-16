<?php

namespace App\Http\Controllers\Supervicion;

use App\Http\Controllers\Controller;
use App\Models\CodigoParametros;
use App\Models\GastoMuestra;
use App\Models\LoteDetalle;
use App\Models\LoteDetalleDureza;
use App\Models\LoteDetalleEspectro;
use App\Models\LoteDetallePotable;
use App\Models\PhMuestra;
use App\Models\Solicitud;
use App\Models\TemperaturaMuestra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CadenaController extends Controller
{
    //cadena  
    public function cadenaCustodia()
    {
        $model = DB::table('ViewSolicitud')->orderby('Id_solicitud', 'desc')->where('Padre', 1)->get();
        return view('supervicion.cadena.cadena', compact('model'));
    }
    public function detalleCadena($id)
    {
        $swSir = false;
        $model = DB::table('ViewSolicitud')->where('Id_solicitud', $id)->first();
        $intermediario = DB::table('ViewIntermediarios')->where('Id_intermediario',$model->Id_intermediario)->first();
        if ($model->Siralab == 1) {
            $puntos = DB::table('ViewPuntoMuestreoSolSir')->where('Id_solPadre', $id)->get();
            $swSir = true;
        } else {
            $puntos = DB::table('ViewPuntoMuestreoGen')->where('Id_solPadre', $id)->get();
        }
        return view('supervicion.cadena.detalleCadena', compact('model', 'puntos', 'swSir','intermediario'));
    }
    public function getParametroCadena(Request $res)
    {
        $model = DB::table('ViewCodigoParametro')->where('Id_solicitud', $res->idPunto)->where('Num_muestra', 1)->get();
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function liberarMuestra(Request $res)
    {
        $sw = true;
        $model = CodigoParametros::where('Id_codigo', $res->idCod)->first();
        $model->Resultado2 = round($res->resLiberado, 3);
        $model->Cadena = 1;
        $model->save();
        $data = array(
            'sw' => $sw,
        );
        return response()->json($data);
    }
    public function getDetalleAnalisis(Request $res)
    {
        $aux = 0;
        $model = "Model vacio 2";
        $codigoModel = DB::table('ViewCodigoParametro')->where('Id_codigo', $res->idCodigo)->first();
        $paraModel = DB::table('ViewParametros')->where('Id_parametro', $codigoModel->Id_parametro)->first();
        switch ($paraModel->Id_parametro) {
                // Metales
            case 17: // Arsenico
            case 20: // Cobre
            case 22: //Mercurio
            case 25: //Zinc
            case 24: //Plomo
            case 21: //Cromoa
            case 18: //Cadmio
                $model = LoteDetalle::where('Id_analisis', $codigoModel->Id_solicitud)
                    ->where('Id_parametro', $codigoModel->Id_parametro)->where('Id_control', 1)->get();
                break;
            case "15": // fosforo
            case "19": // Cianuros
            case "7": //Nitrats 
            case "8": //Nitritos
                $model = LoteDetalleEspectro::where('Id_analisis', $codigoModel->Id_solicitud)
                    ->where('Id_parametro', $codigoModel->Id_parametro)->where('Id_control', 1)->get();
                break;
            case 11:
                $model = DB::table('ViewCodigoParametro')->where('Id_solicitud', $codigoModel->Id_solicitud)
                    ->where('Id_parametro', 83)->first();
                $aux = DB::table('ViewLoteDetalleEspectro')->where('Id_analisis', $codigoModel->Id_solicitud)
                    ->where('Id_control', 1)
                    ->get();
                break;
            case "6":
                $model = DB::table('ViewLoteDetalleDqo')->where('Id_analisis', $codigoModel->Id_solicitud)
                    ->where('Id_parametro', $codigoModel->Id_parametro)
                    ->where('Id_control', 1)->get();
                break;
            case 9:
            case 10:
                $model = DB::table('ViewLoteDetalleNitrogeno')->where('Id_analisis', $codigoModel->Id_solicitud)
                    ->where('Id_control', 1)
                    ->where('Id_parametro', $codigoModel->Id_parametro)->get();
                break;
            case 83:
                $model = DB::table('ViewLoteDetalleNitrogeno')
                    ->where('Id_analisis', $codigoModel->Id_solicitud)
                    ->where('Id_control', 1)
                    ->get();
                break;
            case 218: //Cloro
            case 64:
                $model = DB::table('ViewLoteDetalleCloro')
                    ->where('Id_analisis', $codigoModel->Id_solicitud)
                    ->where('Id_control', 1)
                    ->get();
                break;
            case "13": // Grasas y Aceites
                $model = DB::table('ViewLoteDetalleGA')
                    ->where('Id_analisis', $codigoModel->Id_solicitud)
                    ->where('Id_control',1)->get();
                $gasto = GastoMuestra::where('Id_solicitud', $codigoModel->Id_solicitud)
                    ->where('Activo', 1)->get();
                $res1 = array();
                $promTemp = 0;
                foreach ($gasto as $item) {
                    $promTemp = $promTemp + $item->Promedio;
                }
                $promGasto = $promTemp / $gasto->count();

                $res = 0;
                for ($i = 0; $i < sizeof($model); $i++) {
                    $res = $res + (($model[$i]->Resultado * $gasto[$i]->Promedio) / $promGasto);
                }

                $aux = $res / $model->count();
                break;
                //Mb
            case "5":
                $model = DB::table('ViewLoteDetalleDbo')->where('Id_analisis', $codigoModel->Id_solicitud)
                    ->where('Id_control', 1)
                    ->where('Id_parametro', $codigoModel->Id_parametro)->get();
                break;
            case 12: 
            case 134:
                $model = DB::table('ViewLoteDetalleColiformes')->where('Id_analisis', $codigoModel->Id_solicitud)
                    ->where('Id_control', 1)
                    ->where('Id_parametro', $codigoModel->Id_parametro)->get();
                break;
            case 16:
                $model = DB::table('ViewLoteDetalleHH')->where('Id_analisis', $codigoModel->Id_solicitud)
                    ->where('Id_control', 1)
                    ->where('Id_parametro', $codigoModel->Id_parametro)->get();
                break;
            case 78:
                $model = DB::table('ViewLoteDetalleEcoli')->where('Id_analisis', $codigoModel->Id_solicitud)
                    ->where('Id_control', 1)
                    ->where('Id_parametro', $codigoModel->Id_parametro)->get();
                break;
            case 3: // Solidos
            case 4:
                $model = DB::table('ViewLoteDetalleSolidos')->where('Id_analisis', $codigoModel->Id_solicitud)
                    ->where('Id_control', 1)
                    ->where('Id_parametro', $codigoModel->Id_parametro)->get();
                break;
            case "26": //Gasto
                $model = GastoMuestra::where('Id_solicitud', $codigoModel->Id_solicitud)
                    ->where('Activo', 1)->get();
                break;
            case "2": //Materia flotante
                $model = PhMuestra::where('Id_solicitud', $codigoModel->Id_solicitud)
                    ->where('Activo', 1)->get();
                break;
            case "14": //ph
                $model = PhMuestra::where('Id_solicitud', $codigoModel->Id_solicitud)
                    ->where('Activo', 1)->get();
                break;
            case "97": //Temperatura
                $model = TemperaturaMuestra::where('Id_solicitud', $codigoModel->Id_solicitud)
                    ->where('Activo', 1)->get();
                break;

            //Potable
                //Dureza
            case 77:
            case 103:
            case 251:
            case 252:
                $model = DB::table('ViewLoteDetalleDureza')->where('Id_analisis', $codigoModel->Id_solicitud)
                    ->where('Id_control', 1)
                    ->where('Id_parametro', $codigoModel->Id_parametro)->get();
                break;
            case 66:
                $model = DB::table('ViewLoteDetalleDirectos')->where('Id_analisis', $codigoModel->Id_solicitud)
                    ->where('Id_control', 1)
                    ->where('Id_parametro', $codigoModel->Id_parametro)->get();
                break;
            case 114:
                $model = DB::table('ViewLoteDetalleEspectro')->where('Id_analisis', $codigoModel->Id_solicitud)
                ->where('Id_control', 1)
                ->where('Id_parametro', $codigoModel->Id_parametro)->get();
                break;
            case 69:
                $model = DB::table('ViewLoteDetalleEspectro')->where('Id_analisis', $codigoModel->Id_solicitud)
                ->where('Id_control', 1)
                ->where('Id_parametro', $codigoModel->Id_parametro)->get();
                break;
            default:
            break;
        }
        $data = array(
            'aux' => $aux,
            'paraModel' => $paraModel,
            'codigoModel' => $codigoModel,
            'model' => $model,
        );
        return response()->json($data);
    }
    public function regresarRes(Request $res)
    {
        $codigoModel = DB::table('ViewCodigoParametro')->where('Id_codigo', $res->idCodigo)->first();
        $paraModel = DB::table('ViewParametros')->where('Id_parametro', $codigoModel->Id_parametro)->first();
        switch ($paraModel->Id_area) {
            case 2:
                $model = LoteDetalle::where('Id_analisis', $codigoModel->Id_solicitud)
                    ->where('Id_parametro', $codigoModel->Id_parametro)->where('Id_control', 1)->get();
                foreach ($model as $item) {
                    $mod = LoteDetalle::find($item->Id_detalle);
                    $mod->Liberado = 0;
                    $mod->save();
                    $mod2 = CodigoParametros::find($codigoModel->Id_codigo);
                    $mod2->Resultado = NULL;
                    $mod2->save();
                }
                break;
            case 16:
                $model = LoteDetalleEspectro::where('Id_analisis', $codigoModel->Id_solicitud)
                    ->where('Id_parametro', $codigoModel->Id_parametro)->where('Id_control', 1)->get();
                foreach ($model as $item) {
                    $mod = LoteDetalleEspectro::find($item->Id_detalle);
                    $mod->Liberado = 0;
                    $mod->save();
                    $mod2 = CodigoParametros::find($codigoModel->Id_codigo);
                    $mod2->Resultado = NULL;
                    $mod2->save();
                }
                break;
            default:
                # code... 
                $model = "Default";
                break;
        }
        $data = array(
            'paraModel' => $paraModel,
            'model' => $model,
        );
        return response()->json($data);
    }
    public function liberarSolicitud(Request $res)
    {
        $sw = true;
        $model = Solicitud::find($res->idSol);
        if ($res->liberado == true) {
            $model->Liberado = 1;
            $sw = true;
        } else {
            $model->Liberado = 0;
            $sw = false;
        }
        $model->save();

        $data = array(
            'sw' => $sw,
        );
        return response()->json($data);
    }
}
