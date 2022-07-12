<?php

namespace App\Http\Controllers\Supervicion;

use App\Http\Controllers\Controller;
use App\Models\LoteDetalle;
use App\Models\LoteDetalleColiformes;
use App\Models\LoteDetalleEspectro;
use App\Models\LoteDetalleGA;
use App\Models\LoteDetalleNitrogeno;
use App\Models\Solicitud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CadenaCustodiaController extends Controller
{
    //
    public function cadenaCustodia()
    {  
        $model = DB::table('ViewSolicitud')->orderby('Id_solicitud','desc')->where('Padre',1)->get();         
        return view('supervicion.cadena.cadena',compact('model'));
    } 
    public function detalleCadena($id)
    {
        $swSir = false;
        $model = DB::table('ViewSolicitud')->where('Id_solicitud',$id)->first();                  
        if ($model->Siralab == 1) {
            $puntos = DB::table('ViewPuntoMuestreoSolSir')->where('Id_solPadre',$id)->get();  
            $swSir = true;
        } else {
            $puntos = DB::table('ViewPuntoMuestreoGen')->where('Id_solPadre',$id)->get();
        }
        return view('supervicion.cadena.detalleCadena',compact('model','puntos','swSir'));
    }
    public function getParametroCadena(Request $res)
    {

        $model = DB::table('ViewCodigoParametro')->where('Id_solicitud',$res->idPunto)->where('Num_muestra',1)->get();
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    } 
    public function getDetalleAnalisis(Request $res)
    {
        $model = DB::table('ViewSolicitud')->where('Id_solicitud',$res->idSol)->first();                  
        if ($model->Siralab == 1) {
            $puntos = DB::table('ViewPuntoMuestreoSolSir')->where('Id_solPadre',$res->idSol)->get();  
            $swSir = true;
        } else {
            $puntos = DB::table('ViewPuntoMuestreoGen')->where('Id_solPadre',$res->idSol)->get();
        }

        $codigoModel = DB::table('ViewCodigoParametro')->where('Id_codigo',$res->idCodigo)->where('Id_codigo',$res->idCodigo)->first();
        $paraModel = DB::table('ViewParametros')->where('Id_parametro',$codigoModel->Id_parametro)->first();
        switch ($paraModel->Id_area) {
            case 2:
                $model = LoteDetalle::where('Id_codigo',$codigoModel->Id_codigo)->first();
                break;
            case 6: // Microbiologia
                    if($codigoModel->Id_parametro == 13)
                    {
                        $model = LoteDetalleColiformes::where('Id_analisis',$res->idSol)->where('Id_control',1)->get();
                    }
                    break;
            case 16: //Espectrofotometria
                $model = LoteDetalleEspectro::where('Id_codigo',$codigoModel->Id_codigo)->first();
                break;
            case 13: // G&A
                $model = LoteDetalleGA::where('Id_analisis',$res->idSol)->where('Id_control',1)->get();
                break;
            case 14: //volmetria
                if($codigoModel->Id_parametro == 12)
                {
                    $model = LoteDetalleNitrogeno::where('Id_codigo',$codigoModel->Id_codigo)->first();
                    $nAmo = LoteDetalleNitrogeno::where('Id_')->first();
                }
                break;
            default:
                # code... 
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
        if($res->liberado == true)
        {
            $model->Liberado = 1;
            $sw = true;
        }else{
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
   
