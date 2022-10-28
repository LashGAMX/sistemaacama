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
        if ($model->Siralab == 1) {
            $puntos = DB::table('ViewPuntoMuestreoSolSir')->where('Id_solPadre', $id)->get();
            $swSir = true;
        } else {
            $puntos = DB::table('ViewPuntoMuestreoGen')->where('Id_solPadre', $id)->get();
        }
        return view('supervicion.cadena.detalleCadena', compact('model', 'puntos', 'swSir'));
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
        $codigoModel = DB::table('ViewCodigoParametro')->where('Id_codigo', $res->idCodigo)->first();
        $paraModel = DB::table('ViewParametros')->where('Id_parametro', $codigoModel->Id_parametro)->first();
        switch ($paraModel->Id_area) {
            case 8: // Potable
                $model = "Potable";
                break;
            case 2: // Metales
                $model = LoteDetalle::where('Id_analisis', $codigoModel->Id_solicitud)
                    ->where('Id_parametro', $codigoModel->Id_parametro)->where('Id_control', 1)->get();
                break;
            case 16: // Espectro
                $model = LoteDetalleEspectro::where('Id_analisis', $codigoModel->Id_solicitud)
                    ->where('Id_parametro', $codigoModel->Id_parametro)->where('Id_control', 1)->get();
                break;
            case 14: // Volumetria
                if ($codigoModel->Id_parametro == 11) {
                    $model = DB::table('ViewCodigoParametro')->where('Id_solicitud', $codigoModel->Id_solicitud)
                        ->where('Id_parametro', 83)->first();
                    $aux = DB::table('ViewLoteDetalleEspectro')->where('Id_analisis', $codigoModel->Id_solicitud)
                        ->where('Id_control', 1)
                        ->get();
                } else if ($codigoModel->Id_parametro == 6) {
                    $model = DB::table('ViewLoteDetalleDqo')->where('Id_analisis', $codigoModel->Id_solicitud)
                        ->where('Id_parametro', $codigoModel->Id_parametro)
                        ->where('Id_control', 1)->get();
                } else if ($codigoModel->Id_parametro == 9 || $codigoModel->Id_parametro == 10) {
                    $model = DB::table('ViewLoteDetalleNitrogeno')->where('Id_analisis', $codigoModel->Id_solicitud)
                        ->where('Id_control', 1)
                        ->where('Id_parametro', $codigoModel->Id_parametro)->get();
                } else if ($codigoModel->Id_parametro == 83) {
                    $model = DB::table('ViewLoteDetalleNitrogeno')
                        ->where('Id_analisis', $codigoModel->Id_solicitud)
                        ->where('Id_control', 1)
                        ->get();
                }
                break;
            case "13": // Grasas y Aceites
                $model = DB::table('ViewLoteDetalleGA')
                    ->where('Id_analisis', $codigoModel->Id_solicitud)
                    ->where('Id_parametro', $codigoModel->Id_parametro)->get();
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
            case 6: // Micro
                if ($codigoModel->Id_parametro == 5) {
                    $model = DB::table('ViewLoteDetalleDbo')->where('Id_analisis', $codigoModel->Id_solicitud)
                        ->where('Id_control', 1)
                        ->where('Id_parametro', $codigoModel->Id_parametro)->get();
                } else if ($codigoModel->Id_parametro == 12) {
                    $model = DB::table('ViewLoteDetalleColiformes')->where('Id_analisis', $codigoModel->Id_solicitud)
                        ->where('Id_control', 1)
                        ->where('Id_parametro', $codigoModel->Id_parametro)->get();
                } else if ($codigoModel->Id_parametro == 16) {
                    $model = DB::table('ViewLoteDetalleHH')->where('Id_analisis', $codigoModel->Id_solicitud)
                        ->where('Id_control', 1)
                        ->where('Id_parametro', $codigoModel->Id_parametro)->get();
                }
                break;
            case 15: // Solidos
                $model = DB::table('ViewLoteDetalleSolidos')->where('Id_analisis', $codigoModel->Id_solicitud)
                    ->where('Id_control', 1)
                    ->where('Id_parametro', $codigoModel->Id_parametro)->get();
                break;
            case 7: // Muestreo
                if ($codigoModel->Id_parametro == 26) //Gasto
                {
                    $model = GastoMuestra::where('Id_solicitud', $codigoModel->Id_solicitud)
                        ->where('Activo', 1)->get();
                } else if ($codigoModel->Id_parametro == 2) //Materia flotante
                {
                    $model = PhMuestra::where('Id_solicitud', $codigoModel->Id_solicitud)
                        ->where('Activo', 1)->get();
                } else if ($codigoModel->Id_parametro == 14) //Ph
                {
                    $model = PhMuestra::where('Id_solicitud', $codigoModel->Id_solicitud) 
                        ->where('Activo', 1)->get();
                } else if ($codigoModel->Id_parametro == 97) //Temperatura
                {
                    $model = TemperaturaMuestra::where('Id_solicitud', $codigoModel->Id_solicitud)
                        ->where('Activo', 1)->get();
                }
                break;  
 
            default:    
                # code...  
                switch ($codigoModel->Id_parametro) { 
                    case 77: //Dureza
                    case 103:
                    case 251:
                    case 252:
                        $model = LoteDetalleDureza::where('Id_solicitud', $codigoModel->Id_solicitud)
                        ->where('Activo', 1)->get();
                        break;
                    default:
                        $model = LoteDetallePotable::where('Id_solicitud', $codigoModel->Id_solicitud)
                    ->where('Activo', 1)->get();
                        break; 
                }
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
