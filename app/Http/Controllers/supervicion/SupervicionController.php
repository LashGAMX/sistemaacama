<?php

namespace App\Http\Controllers\supervicion;

use App\Http\Controllers\Controller;
use App\Models\LoteAnalisis;
use App\Models\LoteDetalleAlcalinidad;
use App\Models\LoteDetalleCloro;
use App\Models\LoteDetalleColiformes;
use App\Models\LoteDetalleDbo;
use App\Models\LoteDetalleDboIno;
use App\Models\LoteDetalleDirectos;
use App\Models\LoteDetalleDqo;
use App\Models\LoteDetalleEcoli;
use App\Models\LoteDetalleEnterococos;
use App\Models\LoteDetalleEspectro;
use App\Models\LoteDetalleGA;
use App\Models\LoteDetalleHH;
use App\Models\LoteDetalleNitrogeno;
use App\Models\LoteDetalleSolidos;
use App\Models\Parametro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SupervicionController extends Controller
{
    public function analisis()
    {
        $parametro = DB::table('ViewParametroUsuarios')->where('Id_user', Auth::user()->id)->get();
        $tipo = DB::table('tipo_formulas')
        ->where('Id_tipo_formula',20)
        ->orWhere('Id_tipo_formula',21)
        ->orWhere('Id_tipo_formula',22)
        ->orWhere('Id_tipo_formula',23)
        ->orWhere('Id_tipo_formula',24)
        ->orWhere('Id_tipo_formula',58)
        ->orWhere('Id_tipo_formula',59)
        ->get();
        $data  = array(
            'parametro' => $parametro,
            'tipo' => $tipo,
        );
        return view('supervicion.analisis.analisis',$data); 
    }
    public function getLotes(Request $res)
    {
        // $model = DB::table('viewlotedetalle')->where('Id_parametro',$res->parametro)->where('Fecha','LIKE','%'.$res->mes.'%')->get();
        $parametro = Parametro::find($res->parametro);
        if ($parametro->Id_area == 17) {
            $model = DB::table('ViewLoteAnalisis')->where('Id_area',17)->where('Fecha','LIKE','%'.$res->mes.'%')->get();
        }else{
            $model = DB::table('ViewLoteAnalisis')->where('Id_tecnica',$res->parametro)->where('Fecha','LIKE','%'.$res->mes.'%')->get();
        }
        $data = array(
            'parametro' => $parametro,
            'model' => $model,
        );
        return response()->json($data);
    }
    public function supervisarBitacora(Request $res)
    {
        $sw = false;
        $msg = "Error al liberar";
        $parametro = Parametro::find($res->parametro);
        switch ($parametro->Id_area) {
            case 2:
                $model = LoteDetalle::where('Id_lote',$res->id)->where('Liberado',0)->get();
                break;
            case 17:
                $sw = true;
                $msg = "Muestra liberada"  ;
                break;
            case 16: // Espectrofotometria
            case 5: // Fisicoquimicos
                    $model = LoteDetalleEspectro::where('Id_lote',$res->id)->where('Liberado',0)->get();
                break;
            case 13: // G&A
                    $model = LoteDetalleGA::where('Id_lote',$res->id)->where('Liberado',0)->get();
                break;
            case 15: // Solidos
                    $model = LoteDetalleSolidos::where('Id_lote',$res->id)->where('Liberado',0)->get();
                break;
            case 14: //Volumetria
                switch ($res->parametro) {
                    case 6: // Dqo
                    case 161:
                        $model = LoteDetalleDqo::where('Id_lote',$res->id)->where('Liberado',0)->get();
                        break;
                    case 33: // Cloro
                    case 64:
                    case 119:
                    case 218:
                        $model = LoteDetalleCloro::where('Id_lote',$res->id)->where('Liberado',0)->get();
                        break;
                    case 9: // Nitrogeno
                    case 10:
                    case 11:
                    case 287:
                    case 83:
                    case 108:
                        $model = LoteDetalleNitrogeno::where('Id_lote',$res->id)->where('Liberado',0)->get();
                        break;
                    case 28://Alcalinidad
                    case 29:
                    case 30:
                        $model = LoteDetalleAlcalinidad::where('Id_lote',$res->id)->where('Liberado',0)->get();
                        break;
                    default:
                        $model = LoteDetalleDirectos::where('Id_lote',$res->id)->where('Liberado',0)->get();
                        break;
                }
                break;
            case 7: // Campo
            case 19: //Directos
                $model = LoteDetalleDirectos::where('Id_lote',$res->id)->where('Liberado',0)->get();
                break;
            case 8: //Potable
                switch ($res->parametro) {
                    case 77: //Dureza
                    case 103:
                    case 251:
                    case 252: 
                        // $model = DB::table('ViewLoteDetalleDureza')->where('Id_lote', $res->idLote)->where('Liberado',0)->get();
                        $model = DB::table('ViewLoteDetalleDureza')->where('Id_lote', $res->idLote)->get();
                        break;
                    default:
                        $model = DB::table('ViewLoteDetallePotable')->where('Id_lote', $res->idLote)->where('Liberado',0)->get();
                        break;
                }
                break;
            case 6: // Mb
            case 12:
            case 3:
                switch ($res->parametro) {
                    case 135: // Coliformes fecales
                    case 132:
                    case 133:
                    case 12:
                    case 134: // E COLI
                    case 35:
                    case 51: // Coliformes totales
                    case 137:
                        $model = LoteDetalleColiformes::where('Id_lote',$res->id)->where('Liberado',0)->get();
                        break;
                    case 253: //todo  ENTEROCOCO FECAL
                        $model = LoteDetalleEnterococos::where('Id_lote',$res->id)->where('Liberado',0)->get();
                        break;
                    case 5: //todo DEMANDA BIOQUIMICA DE OXIGENO (DBO5)  
                    case 71:
                        $model = LoteDetalleDbo::where('Id_lote',$res->id)->where('Liberado',0)->get();
                        break;
                    case 70:
                        $model = LoteDetalleDboIno::where('Id_lote',$res->id)->where('Liberado',0)->get();
                        break;
                    case 16: //todo Huevos de Helminto 
                        $model = LoteDetalleHH::where('Id_lote',$res->id)->where('Liberado',0)->get();
                        break;
                    case 78:
                        $model = LoteDetalleEcoli::where('Id_lote',$res->id)->where('Liberado',0)->get();
                        break;
                    default:
                        $model = LoteDetalleDirectos::where('Id_lote',$res->id)->where('Liberado',0)->get();
                        break;
                    }
            break;
            default:
                break;
        }

        if ($model->count()) {
            $sw = false;
            $msg = "Hay muestras sin liberar";
        }else{
            $model = LoteAnalisis::where('Id_lote',$res->id)->first();
            if ($model->Supervisado == 0) {
                $model->Supervisado = 1;
                $msg = "Muestra liberada";
            }else{
                $model->Supervisado = 0;
                $msg = "Muestra desliberada";
            }   
            $model->Id_superviso = Auth::user()->id;
            $model->save();
        }

        $data = array(
            'msg' => $msg,
            'sw' => $sw,
        );
        return response()->json($data);
    }
} 
