<?php

namespace App\Http\Controllers\Laboratorio;

use App\Http\Controllers\Controller;
use App\Models\BitacoraColiformes;
use App\Models\Bitacoras;
use App\Models\CodigoParametros;
use App\Models\ControlCalidad;
use App\Models\ConvinacionesEcoli;
use App\Models\CrisolesGA;
use App\Models\CurvaConstantes;
use App\Models\DqoDetalle;
use App\Models\GrasasDetalle;
use App\Models\LoteAnalisis;
use App\Models\LoteDetalle;
use App\Models\LoteDetalleCloro;
use App\Models\LoteDetalleColiformes;
use App\Models\LoteDetalleDbo;
use App\Models\LoteDetalleDboIno;
use App\Models\LoteDetalleDirectos;
use App\Models\LoteDetalleDqo;
use App\Models\LoteDetalleDureza;
use App\Models\LoteDetalleEcoli;
use App\Models\LoteDetalleEnterococos;
use App\Models\LoteDetalleEspectro;
use App\Models\LoteDetalleGA;
use App\Models\LoteDetalleHH;
use App\Models\LoteDetalleNitrogeno;
use App\Models\LoteDetallePotable;
use App\Models\LoteDetalleSolidos;
use App\Models\MatrazGA;
use App\Models\Parametro;
use App\Models\PlantillaBitacora;
use App\Models\ProcesoAnalisis;
use App\Models\SembradoFq;
use App\Models\SolicitudPuntos;
use App\Models\User;
use App\Models\ValoracionCloro;
use App\Models\ValoracionDqo;
use App\Models\ValoracionDureza;
use App\Models\ValoracionNitrogeno;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LabAnalisisController extends Controller
{
    //
    public function captura()
    {
        $parametro = DB::table('ViewParametroUsuarios')->where('Id_user', Auth::user()->id)->get();
        $control = ControlCalidad::all();
        $data = array(
            'control' => $control,
            'model' => $parametro,
        );
        return view('laboratorio.analisis.captura', $data);
    }
    public function getPendientes(Request $res)
    {
        $model = array();
        $temp = array();
        $codigo = DB::table('ViewCodigoParametro')->where('Asignado', 0)->where('Cancelado','!=',1)->get();
        $param = DB::table('ViewParametroUsuarios')->where('Id_user', Auth::user()->id)->get();

        foreach ($codigo as $item) {
            $temp = array();
            foreach ($param as $item2) {
                if ($item->Id_parametro == $item2->Id_parametro) {
                    array_push($temp, $item->Codigo);
                    array_push($temp, "(" . $item->Id_parametro . ") " . $item->Parametro);
                    array_push($temp, $item->Hora_recepcion);
                    array_push($temp, $item->Empresa);
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
    public function setTipoDqo(Request $res)
    {
        $model = DqoDetalle::where('Id_lote', $res->idLote)->get();
        if ($model->count()) {
            $model[0]->Tipo = $res->tipo;
            $model[0]->Tecnica = $res->tecnica;
            $model[0]->Soluble = $res->soluble;
            $model[0]->save();
        } else {
            DqoDetalle::create([
                'Id_lote' => $res->idLote,
                'Tecnica' => $res->tecnica,
                'Soluble' => $res->soluble,
            ]);
        }

        $updated = DqoDetalle::where('Id_lote', $res->idLote)->first();
        $data = array(
            'model' => $model,
            'tipo' => $updated,
        );
        return response()->json($data);
    }
    public function getLote(Request $res)
    {
        if ($res->folio != "") {
            $temp = DB::table('ViewCodigoParametro')->where('Codigo', $res->folio)->where('Id_parametro', $res->id)->first();
            $model = DB::table('ViewLoteAnalisis')->where('Id_lote', $temp->Id_lote)->get();
        } else {
            $model = DB::table('ViewLoteAnalisis')->where('Id_tecnica', $res->id)->where('Fecha', $res->fecha)->get();
        }

        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function setLote(Request $res)
    {
        $parametro = Parametro::where('Id_parametro', $res->id)->first();
        $model = LoteAnalisis::create([
            'Id_area' => $parametro->Id_area,
            'Id_tecnica' => $res->id,
            'Asignado' => 0,
            'Liberado' => 0,
            'Fecha' => $res->fecha,
            'Id_user_c' => Auth::user()->id,
            'Id_user_m' => Auth::user()->id,
        ]);
        switch ($parametro->Id_parametro) {
            case 13:
                GrasasDetalle::create([
                    'Id_lote' => $model->Id_lote,
                ]);     
                break;
            case 6:
                DqoDetalle::create([
                    'Id_lote' => $model->Id_lote,
                    'Tecnica' => 2,
                    'Soluble' => 2,
                ]);
                break;
            default:
                # code...
                break;
        }
        $data = array(
            'model' => $model
        );
        return response()->json($data);
    }
    public function getMuestraSinAsignar(Request $res)
    {
        $folio = array();
        $norma = array();
        $punto = array();
        $fecha = array();
        $lote = DB::table('ViewLoteAnalisis')->where('Id_lote', $res->idLote)->first();
        if ($res->fecha != "") {
        } else {
            $model = DB::table('ViewCodigoParametro')->where('Asignado', 0)->where('Id_parametro', $res->idParametro)->where('Cancelado','!=',1)->get();
            for ($i = 0; $i < $model->count(); $i++) {
                $puntoModel = SolicitudPuntos::where('Id_solicitud', $model[$i]->Id_solicitud)->first();
                $proceso = ProcesoAnalisis::where('Id_solicitud', $model[$i]->Id_solicitud)->first();
                array_push($folio, $model[$i]->Codigo);
                array_push($norma, $model[$i]->Norma);
                array_push($punto, $puntoModel->Punto);
                array_push($fecha, $proceso->Hora_recepcion);
            }
        }

        $data = array(
            'model' => $model,
            'folio' => $folio,
            'norma' => $norma,
            'fecha' => $fecha,
            'punto' => $punto,
            'lote' => $lote,
        );
        return response()->json($data);
    }
    public function setMuestraLote(Request $res)
    {
        // $lote = DB::table('ViewLoteAnalisis')->where('Id_lote',$res->idLote)->first();
        $lote = LoteAnalisis::where('Id_lote', $res->idLote)->first();

        for ($i = 0; $i < sizeof($res->codigos); $i++) {
            $model = CodigoParametros::where('Id_codigo', $res->codigos[$i])->first();
            $model->Id_lote = $res->idLote;
            $model->Asignado = 1;
            $model->save();
            switch ($lote->Id_area) {
                case 16: // Espectrofotometria
                case 5: // Fisicoquimicos
                    switch ($model->Id_parametro) {
                        case 152: // COT
                        case 99: // Cianuros
                        case 19:
                        case 118:
                            $temp = LoteDetalleEspectro::create([
                                'Id_lote' => $res->idLote,
                                'Id_analisis' => $model->Id_solicitud,
                                'Id_codigo' => $model->Id_codigo,
                                'Id_parametro' => $model->Id_parametro,
                                'Id_control' => 1,
                                'Vol_muestra' => 40,
                                'Liberado' => 0,
                                'Analizo' => 1,
                            ]);
                            $tempModel = LoteDetalleEspectro::where('Id_lote', $res->idLote)->get();
                            break;
                        default:
                            $temp = LoteDetalleEspectro::create([
                                'Id_lote' => $res->idLote,
                                'Id_analisis' => $model->Id_solicitud,
                                'Id_codigo' => $model->Id_codigo,
                                'Id_parametro' => $model->Id_parametro,
                                'Id_control' => 1,
                                'Vol_muestra' => 50,
                                'Liberado' => 0,
                                'Analizo' => 1,
                            ]);
                            $tempModel = LoteDetalleEspectro::where('Id_lote', $res->idLote)->get();
                            break;
                    }
                    break;
                case 13: // G&A
                    $temp = LoteDetalleGA::create([
                        'Id_lote' => $res->idLote,
                        'Id_analisis' => $model->Id_solicitud,
                        'Id_codigo' => $model->Id_codigo,
                        'Id_parametro' => $model->Id_parametro,
                        'Id_control' => 1,
                        'Vol_muestra' => 50,
                        'Liberado' => 0,
                        'Analizo' => 1,
                    ]);
                    $tempModel = LoteDetalleGA::where('Id_lote', $res->idLote)->get();
                    break;
                case 15: //Solidos
                    $temp = LoteDetalleSolidos::create([
                        'Id_lote' => $res->idLote,
                        'Id_analisis' => $model->Id_solicitud,
                        'Id_codigo' => $model->Id_codigo,
                        'Id_parametro' => $model->Id_parametro,
                        'Id_control' => 1,
                        'Vol_muestra' => 100,
                        'Factor_conversion' => 1000000,
                        'Liberado' => 0,
                        'Analizo' => 1,
                    ]);
                    $tempModel = LoteDetalleSolidos::where('Id_lote', $res->idLote)->get();
                    break;
                case 14: //volumetria
                    switch ($model->Id_parametro) {
                        case 6:
                        case 161:
                            $temp = LoteDetalleDqo::create([
                                'Id_lote' => $res->idLote,
                                'Id_analisis' => $model->Id_solicitud,
                                'Id_codigo' => $model->Id_codigo,
                                'Id_parametro' => $model->Id_parametro,
                                'Id_control' => 1,
                                'Analizo' => 1,
                                'Liberado' => 0,
                            ]);
                            $tempModel = LoteDetalleDqo::where('Id_lote', $res->idLote)->get();
                            break;
                        case 33:
                        case 218:
                        case 119:
                        case 64:
                            $temp = LoteDetalleCloro::create([
                                'Id_lote' => $res->idLote,
                                'Id_analisis' => $model->Id_solicitud,
                                'Id_codigo' => $model->Id_codigo,
                                'Id_parametro' => $model->Id_parametro,
                                'Id_control' => 1,
                                'Analizo' => 1,
                                'Liberado' => 0,
                            ]);
                            $tempModel = LoteDetalleCloro::where('Id_lote', $res->idLote)->get();
                            break;
                        case 9:
                        case 10:
                        case 11:
                        case 287:
                        case 83:
                        case 108:
                        case 28: // Alcalinidadd
                            $temp = LoteDetalleNitrogeno::create([
                                'Id_lote' => $res->idLote,
                                'Id_analisis' => $model->Id_solicitud,
                                'Id_codigo' => $model->Id_codigo,
                                'Id_parametro' => $model->Id_parametro,
                                'Id_control' => 1,
                                'Analizo' => 1,
                                'Liberado' => 0,
                            ]);
                            $tempModel = LoteDetalleNitrogeno::where('Id_lote', $res->idLote)->get();
                            break;
                        default:
                            # code...
                            break;
                    }
                    break;
                case 7: //Campo
                case 19: // Directos
                    $temp = LoteDetalleDirectos::create([
                        'Id_lote' => $res->idLote,
                        'Id_analisis' => $model->Id_solicitud,
                        'Id_codigo' => $model->Id_codigo,
                        'Id_parametro' => $model->Id_parametro,
                        'Id_control' => 1,
                        'Analizo' => 1,
                        'Liberado' => 0,
                    ]);
                    $tempModel = LoteDetalleDirectos::where('Id_lote', $res->idLote)->get();
                    break;
                case 8: //Potable
                    switch ($model->Id_parametro) {
                        case 77: //Dureza
                        case 103:
                        case 251:
                        case 252:
                            $temp = LoteDetalleDureza::create([
                                'Id_lote' => $res->idLote,
                                'Id_analisis' => $model->Id_solicitud,
                                'Id_codigo' => $model->Id_codigo,
                                'Id_parametro' => $model->Id_parametro,
                                'Id_control' => 1,
                                'Analizo' => 1,
                                'Liberado' => 0,
                            ]);
                            $tempModel = LoteDetalleDureza::where('Id_lote', $res->idLote)->get();
                            break;
                        default:
                            $temp = LoteDetallePotable::create([
                                'Id_lote' => $res->idLote,
                                'Id_analisis' => $model->Id_solicitud,
                                'Id_codigo' => $model->Id_codigo,
                                'Id_parametro' => $model->Id_parametro,
                                'Id_control' => 1,
                                'Analizo' => 1,
                                'Liberado' => 0,
                            ]);
                            $tempModel = LoteDetallePotable::where('Id_lote', $res->idLote)->get();
                            break;
                    }
                    break;
                case 6: // Mb
                case 12:
                case 3:
                    switch ($model->Id_parametro) {
                        case 135: // Coliformes fecales
                        case 132:
                        case 133:
                        case 12:
                        case 134: // termotolerantes
                        case 35:
                        case 51: // Coliformes totales
                        case 137:
                            $temp = LoteDetalleColiformes::create([
                                'Id_lote' => $res->idLote,
                                'Id_analisis' => $model->Id_solicitud,
                                'Id_codigo' => $model->Id_codigo,
                                'Id_parametro' => $model->Id_parametro,
                                'Id_control' => 1,
                                'Analizo' => 1,
                                'Liberado' => 0,
                            ]);
                            $tempModel = LoteDetalleColiformes::where('Id_lote', $res->idLote)->get();
                            break;
                        case 253: //todo  ENTEROCOCO FECAL
                            $temp = LoteDetalleEnterococos::create([
                                'Id_lote' => $res->idLote,
                                'Id_analisis' => $model->Id_solicitud,
                                'Id_codigo' => $model->Id_codigo,
                                'Id_parametro' => $model->Id_parametro,
                                'Id_control' => 1,
                                'Analizo' => 1,
                                'Liberado' => 0,
                            ]);
                            $tempModel = LoteDetalleEnterococos::where('Id_lote', $res->idLote)->get();
                            break;
                        case 5: //todo DEMANDA BIOQUIMICA DE OXIGENO (DBO5) 
                        case 71:
                            $temp = LoteDetalleDbo::create([
                                'Id_lote' => $res->idLote,
                                'Id_analisis' => $model->Id_solicitud,
                                'Id_codigo' => $model->Id_codigo,
                                'Id_parametro' => $model->Id_parametro,
                                'Id_control' => 1,
                                'Analizo' => 1,
                                'Liberado' => 0,
                            ]);
                            $tempModel = LoteDetalleDbo::where('Id_lote', $res->idLote)->get();
                            break;
                        case 70:
                            $temp = LoteDetalleDboIno::create([
                                'Id_lote' => $res->idLote,
                                'Id_analisis' => $model->Id_solicitud,
                                'Id_codigo' => $model->Id_codigo,
                                'Id_parametro' => $model->Id_parametro,
                                'Id_control' => 1,
                                'Analizo' => 1, 
                                'Liberado' => 0,
                            ]);
                            $tempModel = LoteDetalleDboIno::where('Id_lote', $res->idLote)->get();
                            break;
                        case 16: //todo Huevos de Helminto 
                            $temp = LoteDetalleHH::create([
                                'Id_lote' => $res->idLote,
                                'Id_analisis' => $model->Id_solicitud,
                                'Id_codigo' => $model->Id_codigo,
                                'Id_parametro' => $model->Id_parametro,
                                'Id_control' => 1,
                                'Analizo' => 1,
                                'Liberado' => 0,
                            ]);
                            $tempModel = LoteDetalleHH::where('Id_lote', $res->idLote)->get();
                            break;
                        case 78:
                            $temp = LoteDetalleEcoli::create([
                                'Id_lote' => $res->idLote,
                                'Id_analisis' => $model->Id_solicitud,
                                'Id_codigo' => $model->Id_codigo,
                                'Id_parametro' => $model->Id_parametro,
                                'Id_control' => 1,
                                'Analizo' => 1,
                                'Liberado' => 0,
                            ]);
                            $tempModel = LoteDetalleEcoli::where('Id_lote', $res->idLote)->get();
                            break;
                        default:
                            $temp = LoteDetalleDirectos::create([
                                'Id_lote' => $res->idLote,
                                'Id_analisis' => $model->Id_solicitud,
                                'Id_codigo' => $model->Id_codigo,
                                'Id_parametro' => $model->Id_parametro,
                                'Id_control' => 1,
                                'Analizo' => 1,
                                'Liberado' => 0,
                            ]);
                            $tempModel = LoteDetalleDirectos::where('Id_lote', $res->idLote)->get();
                            break;
                    }
                    break;
                default:
                    $temp = LoteDetalleDirectos::create([
                        'Id_lote' => $res->idLote,
                        'Id_analisis' => $model->Id_solicitud,
                        'Id_codigo' => $model->Id_codigo,
                        'Id_parametro' => $model->Id_parametro,
                        'Id_control' => 1,
                        'Analizo' => 1,
                        'Liberado' => 0,
                    ]);
                    $tempModel = LoteDetalleDirectos::where('Id_lote', $res->idLote)->get();
                    break;
            }
            $lote->Asignado = $tempModel->count();
            $lote->save();
        }
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function getCapturaLote(Request $res)
    {
        
        $lote = LoteAnalisis::where('Id_lote', $res->idLote)->get();
        $aux = array();
        $indice = array();
        $valores = array();
        if ($lote->count()) {
            switch ($lote[0]->Id_area) {
                case 16: // Espectrofotometria
                case 5: // Fisicoquimicos
                    switch ($lote[0]->Id_tecnica) {
                        case 79: 
                            $model = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $res->idLote)->get();  
                            break;
                        default:
                        $model = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $res->idLote)->where('Liberado',0)->get();
                        break;
                    }
                    $model = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $res->idLote)->where('Liberado',0)->get();
                    // $model = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $res->idLote)->get();
                    break;
                case 13: // G&A
                    $model = DB::table('ViewLoteDetalleGA')->where('Id_lote', $res->idLote)->where('Liberado',0)->get();
                    // $model = DB::table('ViewLoteDetalleGA')->where('Id_lote', $res->idLote)->get();
                    break;
                case 15: // Solidos
                    $model = DB::table('ViewLoteDetalleSolidos')->where('Id_lote', $res->idLote)->get();
                    break;
                case 14: //Volumetria
                    switch ($lote[0]->Id_tecnica) {
                        case 6: // Dqo
                        case 161:
                            $aux = DqoDetalle::where('Id_lote', $res->idLote)->first();
                            $model = DB::table('ViewLoteDetalleDqo')->where('Id_lote', $res->idLote)->where('Liberado',0)->get();
                            break;
                        case 33: // Cloro
                        case 64:
                        case 119:
                        case 218:
                            $model = DB::table('ViewLoteDetalleCloro')->where('Id_lote', $res->idLote)->where('Liberado',0)->get();
                            break;
                        case 9: // Nitrogeno
                        case 10:
                        case 11:
                        case 287:
                        case 83:
                        case 108:
                        case 28://Alcalinidad
                            $model = DB::table('ViewLoteDetalleNitrogeno')->where('Id_lote', $res->idLote)->where('Liberado',0)->get();
                            // $model = DB::table('ViewLoteDetalleNitrogeno')->where('Id_lote', $res->idLote)->get();
                            break;
                        default:
                            $model = DB::table('ViewLoteDetalleDirectos')->where('Id_lote', $res->idLote)->where('Liberado',0)->get();
                            break;
                    }
                    break;
                case 7: // Campo
                case 19: //Directos
                    // $model = DB::table('ViewLoteDetalleDirectos')->where('Id_lote', $res->idLote)->where('Liberado',0)->get();
                    $model = DB::table('ViewLoteDetalleDirectos')->where('Id_lote', $res->idLote)->get();
                    break;
                case 8: //Potable
                    switch ($lote[0]->Id_tecnica) {
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
                    switch ($lote[0]->Id_tecnica) {
                        case 135: // Coliformes fecales
                        case 132:
                        case 133:
                        case 12:
                        case 134: // E COLI
                        case 35:
                        case 51: // Coliformes totales
                        case 137:
                            // $model = DB::table('ViewLoteDetalleColiformes')->where('Id_lote', $res->idLote)->where('Liberado',0)->get();
                            $model = DB::table('ViewLoteDetalleColiformes')->where('Id_lote', $res->idLote)->get();
                            break;
                        case 253: //todo  ENTEROCOCO FECAL
                            $model = DB::table('ViewLoteDetalleEnterococos')->where('Id_lote', $res->idLote)->where('Liberado',0)->get();
                            break;
                        case 5: //todo DEMANDA BIOQUIMICA DE OXIGENO (DBO5)  
                        case 71:
                            $model = DB::table('ViewLoteDetalleDbo')->where('Id_lote', $res->idLote)->where('Liberado',0)->get();
                            break;
                        case 70:
                            $model = DB::table('ViewLoteDetalleDboIno')->where('Id_lote', $res->idLote)->where('Liberado',0)->get();
                            break;
                        case 16: //todo Huevos de Helminto 
                            // $model = DB::table('ViewLoteDetalleHH')->where('Id_lote', $res->idLote)->where('Liberado',0)->get();
                            $model = DB::table('ViewLoteDetalleHH')->where('Id_lote', $res->idLote)->get();
                            break;
                        case 78:
                            $detalle = array();
                            $model = DB::table('ViewLoteDetalleEcoli')->where('Id_lote', $res->idLote)->get();
                            foreach ($model as $item) { 
                                $ecoli = CodigoParametros::where('Id_codigo', $item->Id_codigo)->first();            
                                   // $coliformes = CodigoParametros::where('Id_solicitud', $ecoli->Id_solicitud)->where('Num_muestra', $ecoli->Num_muestra)->where('Id_parametro', 134)->first();                
                                    $detalleColiformes = LoteDetalleColiformes::where('Id_parametro', 134)->where('Id_analisis', $item->Id_analisis)->first();
                                    if ($detalleColiformes != null) {
                                        if ($detalleColiformes->Indice == 0) {
                                            array_push($indice,1);
                                        }else {
                                            array_push($indice, $detalleColiformes->Indice);
                                        } 
                                    }else {
                                        array_push($indice,1);
                                    }
                                
                            } 
                           
                            break;
                        default:
                            $model = DB::table('ViewLoteDetalleDirectos')->where('Id_lote', $res->idLote)->where('Liberado',0)->get();
                            break;
                    }
                    break;
                default:
                    $model = DB::table('ViewLoteDetalleDirectos')->where('Id_lote', $res->idLote)->where('Liberado',0)->get();
                    break;
            }
        } else {
            $model = array();
        }

        $data = array(
            'indice' => $indice,
            'aux' => $aux,
            'model' => $model,
            'lote' => $lote,
        );
        return response()->json($data);
    }
    public function getDetalleMuestra(Request $res)
    {
        $model = array();
        $model2 = array();
        $curva = array();
        $blanco = array();
        $valoracion = array();
        $convinaciones = array();
        $d1 = array();
        $d2 = array();
        $dif1 = "Sin datos";
        $dif2 = "Sin datos";
        $nom1 = 'sin nombre';
        $nom2 = 'sin nombre';

        $lote = LoteAnalisis::where('Id_lote', $res->idLote)->get();
        if ($lote->count()) {
            switch ($lote[0]->Id_area) {
                case 16: // Espectrofotometria
                case 5: // Fisicoquimicos
                    $fecha = new Carbon($lote[0]->Fecha);
                    $today = $fecha->toDateString();
                    $model = DB::table("ViewLoteDetalleEspectro")->where('Id_detalle', $res->id)->first();
                    $curva = CurvaConstantes::whereDate('Fecha_inicio', '<=', $today)->whereDate('Fecha_fin', '>', $today)
                        ->where('Id_parametro', $lote[0]->Id_tecnica)->first();
                    $blanco = DB::table("ViewLoteDetalleEspectro")->where('Id_codigo', $model->Id_codigo)->where('Id_control', 5)->first();
                    break;
                case 13: //G&A
                    $model = DB::table("ViewLoteDetalleGA")->where('Id_detalle', $res->id)->first();
                    $blanco = DB::table("ViewLoteDetalleGA")->where('Id_lote', $model->Id_lote)->where('Id_control', 5)->first();
                    break;
                case 15: // Solidos
                    $model = DB::table('ViewLoteDetalleSolidos')->where('Id_detalle', $res->id)->first(); // Asi se hara con las otras
                    switch ($lote[0]->Id_tecnica) {
                        case 88: // SDT 
                            $nom1 = "ST";
                            $dif1 = CodigoParametros::where('Id_solicitud',$model->Id_analisis)->where('Id_parametro', 90)->first();
                            // $dif1 = LoteDetalleSolidos::where('Id_analisis',$model->Id_analisis)->where('Id_control',$model->Id_control)->where('Id_parametro',90)->first();
                            $nom2 = "SST";
                            $dif2 = CodigoParametros::where('Id_solicitud',$model->Id_analisis)->where('Id_parametro', 4)->first();
                            // $dif2 = LoteDetalleSolidos::where('Id_analisis',$model->Id_analisis)->where('Id_control',$model->Id_control)->where('Id_parametro',4)->first();
                            break;
                        case 44: // SDV
                            $nom1 = "STV";
                            $dif1 = CodigoParametros::where('Id_solicitud',$model->Id_analisis)->where('Id_parametro', 49)->first();
                            $nom2 = "SSV";
                            $dif2 = CodigoParametros::where('Id_solicitud',$model->Id_analisis)->where('Id_parametro', 47)->first();
                            break;
                        case 43: // SDF
                            $nom1 = "SDT";
                            $dif1 = CodigoParametros::where('Id_solicitud',$model->Id_analisis)->where('Id_parametro', 89)->first();
                            $nom2 = "SDV";
                            $dif2 = CodigoParametros::where('Id_solicitud',$model->Id_analisis)->where('Id_parametro', 45)->first();
                            break;
                        case 45: // SSF
                            $nom1 = "SST";
                            $dif1 = CodigoParametros::where('Id_solicitud',$model->Id_analisis)->where('Id_parametro', 93)->first();
                            $nom2 = "SSV";
                            $dif2 = CodigoParametros::where('Id_solicitud',$model->Id_analisis)->where('Id_parametro', 47)->first();
                            break;
                        case 47: // STF
                            $nom1 = "ST";
                            $dif1 = CodigoParametros::where('Id_solicitud',$model->Id_analisis)->where('Id_parametro', 91)->first();
                            $nom2 = "STV";
                            $dif2 = CodigoParametros::where('Id_solicitud',$model->Id_analisis)->where('Id_parametro', 49)->first();
                            break;
                        case 3: // SS
                            $dif1 = DB::table("ViewLoteDetalleSolidos")->where("Folio_servicio", $model->Folio_servicio)->where('Id_parametro', 3)->first();
                            break;
                        default:
                            $dif1 = "Sin datos";
                            $dif2 = "Sin datos";
                            $nom1 = 'sin nombre';
                            $nom2 = 'sin nombre';
                            break;
                    }
                    break;
                case 14: //Volumetria
                    switch ($lote[0]->Id_tecnica) {
                        case 218: // Cloro
                        case 33:
                        case 64:
                        case 119:
                            $model = DB::table('ViewLoteDetalleCloro')->where('Id_detalle', $res->id)->first(); // Asi se hara con las otras
                            $valoracion = ValoracionCloro::where('Id_lote', $model->Id_lote)->first();
                            break;
                        case 6: // Dqo
                            $model = DB::table("ViewLoteDetalleDqo")->where('Id_detalle', $res->id)->first();
                            if ($model->Tecnica == 1) {
                                if ($model->Tipo == 3) {
                                    $curva = CurvaConstantes::whereDate('Fecha_inicio', '<=', $lote[0]->Fecha)->whereDate('Fecha_fin', '>=', $lote[0]->Fecha)->where('Id_area', 16)->where('Id_parametro', 74)->first();
                                } else {
                                    $curva = CurvaConstantes::whereDate('Fecha_inicio', '<=', $lote[0]->Fecha)->whereDate('Fecha_fin', '>=', $lote[0]->Fecha)->where('Id_area', 16)->where('Id_parametro', 75)->first();
                                }
                            } else {
                                $valoracion = ValoracionDqo::where('Id_lote', $model->Id_lote)->first();
                            }
                            break;
                        case 9: // Nitrogeno
                        case 287:
                        case 10:
                        case 11:
                        case 108: // Nitrogeno Amon
                            $model = DB::table("ViewLoteDetalleNitrogeno")->where('Id_detalle', $res->id)->first();
                            $valoracion = ValoracionNitrogeno::where('Id_lote', $model->Id_lote)->first();
                            break;
                        case 103:
                            $model = LoteDetalleDureza::where("Id_detalle", $res->id)->first();
                            $valoracion = ValoracionDureza::where('Id_lote', $model->Id_lote)->first();
                            break;
                        default: // Default Directos
                            // tab += '<td><input hidden id="idMuestra' + item.Id_detalle + '" value="' + item.Id_detalle + '"><button '+status+' type="button" class="btn btn-'+color+'" onclick="getDetalleMuestra(' + item.Id_detalle + ',1);" data-toggle="modal" data-target="#modalCapturaSolidos">Capturar</button>';
                            break;
                    }
                    break;
                case 7: //Campo
                case 19: //Directo
                    $model = DB::table("ViewLoteDetalleDirectos")->where('Id_detalle', $res->id)->first();
                    break;
                case 8: //Potable
                    switch ($lote[0]->Id_tecnica) {
                        case 77: //Dureza 
                        case 103:
                        case 251:
                            $model = LoteDetalleDureza::where("Id_detalle", $res->id)->first();
                            $valoracion = ValoracionDureza::where('Id_lote', $model->Id_lote)->first();
                            break;
                        case 252:
                            $model = DB::table('ViewLoteDetalleDureza')->where('Id_detalle', $res->id)->first();
                            $d1 = DB::table('ViewLoteDetalleDureza')->where('Codigo', $model->Folio_servicio)->where('Id_parametro', 251)->first();
                            $d2 = DB::table('ViewLoteDetalleDureza')->where('Codigo', $model->Folio_servicio)->where('Id_parametro', 77)->first();
                            break;
                        default:
                            # code...
                            $model = DB::table('ViewLoteDetallePotable')->where('Id_lote', $res->idLote)->get();
                            break;
                    }
                case 6: // Mb
                case 12:
                case 3:
                    switch ($lote[0]->Id_tecnica) {
                        case 135: // Coliformes fecales
                        case 132:
                        case 133:
                        case 12:
                        case 134: // E COLI
                        case 35:
                        case 51: // Coliformes totales
                        case 137:
                            $model = DB::table('ViewLoteDetalleColiformes')->where('Id_detalle', $res->id)->first(); // Asi se hara con las otras
                            break;
                        case 253: //todo  ENTEROCOCO FECAL
                            $model = DB::table('ViewLoteDetalleEnterococos')->where('Id_detalle', $res->id)->first();
                            break;
                        case 5: //todo DEMANDA BIOQUIMICA DE OXIGENO (DBO5) 
                            $model = DB::table('ViewLoteDetalleDbo')->where('Id_detalle', $res->id)->first(); // Asi se hara con las otras
                            $model2 = DB::table('ViewLoteDetalleDqo')->where('Id_analisis', $model->Id_analisis)->first();
                        break;
                        case 70:
                            $model = DB::table('ViewLoteDetalleDboIno')->where('Id_detalle', $res->id)->first(); // Asi se hara con las otras
                            break;
                        case 16: //todo Huevos de Helminto 
                            $model = DB::table('ViewLoteDetalleHH')->where('Id_detalle', $res->id)->first(); // Asi se hara con las otras
                            break;
                        case 78:
                            $model = DB::table('ViewLoteDetalleEcoli')->where('Id_detalle', $res->id)->first(); // Asi se hara con las otras
                            $convinaciones = ConvinacionesEcoli::where('Id_detalle', $res->id)->where('Colonia', $res->colonia)->first();
                            break;
                        default:
                            $model = array();
                            break;
                    }
                    break;
                default:
                    $model = array();
                    break;
            }
        }

        $data = array(
            'd1' => $d1,
            'd2' => $d2,
            'model' => $model,
            'convinaciones' => $convinaciones,
            'model2' => $model2,
            'valoracion' => $valoracion,
            'curva' => $curva,
            'lote' => $lote,
            'blanco' => $blanco,
            'nom1' => $nom1,
            'nom2' => $nom2,
            'dif1' => $dif1,
            'dif2' => $dif2,
        );
        return response()->json($data);
    }
    public function setDetalleMuestra(Request $res)
    {
        $std = true;
        $lote = LoteAnalisis::where('Id_lote', $res->idLote)->get();
        if ($lote->count()) {
            switch ($lote[0]->Id_area) {
                case 16: // Espectrofotometria
                case 5: // Fisicoquimicos
                    switch ($lote[0]->Id_tecnica) {
                        case 152: // COT
                            $model = LoteDetalleEspectro::where('Id_detalle', $res->idMuestra)->first();
                            $dilucion = 40 / $res->E;
                            $promedio = ($res->X + $res->Y + $res->Z) / 3;
                            switch ($model->Id_control) {
                                case 14: //estandar de verificación
                                    $resultado = ((($promedio - $res->CB) / $res->CM) * $dilucion);
                                    break;
                                case 5: // blanco
                                    $resultado = ($res->X + $res->Y + $res->Z) / 3;
                                    break;
                                default:
                                    $resultado = ((($promedio - $res->CA) / $res->CM) * $dilucion);
                            }
                            $model = LoteDetalleEspectro::find($res->idMuestra);
                            $model->Resultado = $resultado;
                            $model->Abs1 = $res->X;
                            $model->Abs2 = $res->Y;
                            $model->Abs3 = $res->Z;
                            $model->B = $res->CB;
                            $model->M = $res->CM;
                            $model->R = $res->CR;
                            $model->Promedio = $promedio;
                            $model->Vol_dilucion = $dilucion;
                            $model->Vol_muestra = $res->E;
                            $model->Blanco = $res->CA;
                            $model->Analizo = Auth::user()->id;
                            $model->save();
                            break;
                        case 69:
                            # Cromo Hexavalente
                            $d =  $res->CM;
                            $x = ($res->X + $res->Y + $res->Z) / 3;
                            $r1 = ($x - $res->CB) / $d;
                            $r2 = 100 / $res->E;
                            $resultado = $r1 * $r2;

                            $model = LoteDetalleEspectro::find($res->idMuestra);
                            $model->Resultado = $resultado;
                            $model->Abs1 = $res->X;
                            $model->Abs2 = $res->Y;
                            $model->Abs3 = $res->Z;
                            $model->B = $res->CB;
                            $model->M = $res->CM;
                            $model->R = $res->CR;
                            $model->Ph_ini = $res->phIni;
                            $model->Ph_fin = $res->phFin;
                            $model->Promedio = $x;
                            $model->Vol_dilucion = round($d,3);
                            $model->Vol_muestra = $res->E;
                            $model->Blanco = $res->CA;
                            $model->Analizo = Auth::user()->id;
                            $model->save();
                            break;
                        case 19:
                        case 99:
                        case 118:
                            # Cianuros
                            $d = 500 * $res->E;
                            $x = ($res->X + $res->Y + $res->Z) / 3;
                            $r1 = ($x - $res->CB) / $res->CM;
                            $resultado = ($r1 * 12500) / $d;

                            $model = LoteDetalleEspectro::find($res->idMuestra);
                            $model->Resultado = round($resultado, 3);
                            $model->Abs1 = $res->X;
                            $model->Abs2 = $res->Y;
                            $model->Abs3 = $res->Z;
                            $model->B = $res->CB;
                            $model->M = $res->CM;
                            $model->R = $res->CR;
                            $model->Nitratos = $res->nitratos;
                            $model->Nitritos = $res->nitritos;
                            $model->Sulfuros = $res->sulfuros;
                            $model->Promedio = $x;
                            $model->Vol_dilucion = $d;
                            $model->Vol_muestra = $res->E;
                            $model->Blanco = $res->CA;
                            $model->Analizo = Auth::user()->id;
                            $model->save();
                            break;
                        case 114:
                        case 96:
                        case 124:
                            # Sustancias activas al Azul de Metileno
                            $x = ($res->X + $res->Y + $res->Z) / 3;
                            $r1 = (round($x,3) - $res->CB) / $res->CM;
                            $r2 = 1000 / $res->E;
                            $resultado = $r1 * $r2;
                            $d = $r2;

                            $model = LoteDetalleEspectro::find($res->idMuestra);
                            $model->Resultado = $resultado;
                            $model->Abs1 = $res->X;
                            $model->Abs2 = $res->Y;
                            $model->Abs3 = $res->Z;
                            $model->B = $res->CB;
                            $model->M = $res->CM;
                            $model->R = $res->CR;
                            $model->Promedio = $x;
                            $model->Vol_dilucion = $d;
                            $model->Vol_muestra = $res->E;
                            $model->Blanco = $res->CA;
                            $model->Analizo = Auth::user()->id;
                            $model->save();
                            break;
                        case 15:
                        case 38: //ORTOFOSFATO
                            # Fosforo-Total 
                            $d = 100 / $res->E;
                            $x = ($res->X + $res->Y + $res->Z) / 3;
                            $resultado = (($x - $res->CB) / $res->CM) * $d;

                            $model = LoteDetalleEspectro::find($res->idMuestra);
                            $model->Resultado = round($resultado,3);
                            $model->Abs1 = $res->X;
                            $model->Abs2 = $res->Y;
                            $model->Abs3 = $res->Z;
                            $model->B = $res->CB;
                            $model->M = $res->CM;
                            $model->R = $res->CR;
                            $model->Promedio = $x;
                            $model->Vol_dilucion = $d;
                            $model->Vol_muestra = $res->E;
                            $model->Blanco = $res->CA;
                            $model->Analizo = Auth::user()->id;
                            $model->save();
                            break;
                        case 117:
                        case 222:
                            # Boro (B) 
                            $x = ($res->X + $res->Y + $res->Z) / 3;
                            $d = 1 / $res->E;
                            $resultado = (($x - $res->CB) / $res->CM) * $d;

                            $model = LoteDetalleEspectro::find($res->idMuestra);
                            $model->Resultado = $resultado;
                            $model->Abs1 = $res->X;
                            $model->Abs2 = $res->Y;
                            $model->Abs3 = $res->Z;
                            $model->B = $res->CB;
                            $model->M = $res->CM;
                            $model->R = $res->CR;
                            $model->Promedio = $x;
                            $model->Vol_dilucion = $d;
                            $model->Vol_muestra = $res->E;
                            $model->Blanco = $res->CA;
                            $model->Analizo = Auth::user()->id;
                            $model->save();
                            break;
                        case 7:
                        case 122:
                            # N-Nitratos
                            $d = 10 / $res->E;
                            $x = ($res->X + $res->Y + $res->Z) / 3;
                            $resultado = ((round($x,3) - $res->CB) / $res->CM) * $d;

                            $model = LoteDetalleEspectro::find($res->idMuestra);
                            $model->Resultado = $resultado;
                            $model->Abs1 = $res->X;
                            $model->Abs2 = $res->Y;
                            $model->Abs3 = $res->Z;
                            $model->B = $res->CB;
                            $model->M = $res->CM;
                            $model->R = $res->CR;
                            $model->Promedio = $x;
                            $model->Vol_dilucion = $d;
                            $model->Vol_muestra = $res->E;
                            $model->Blanco = $res->CA;
                            $model->Analizo = Auth::user()->id;
                            $model->save();
                            break;
                        case 8:
                        case 107:
                            # N-nitritos
                            $d = 50 / $res->E;
                            $x = ($res->X + $res->Y + $res->Z) / 3;
                            $resultado = ((($x - $res->CB) / $res->CM) * $d);

                            $model = LoteDetalleEspectro::find($res->idMuestra);
                            $model->Resultado = $resultado;
                            $model->Abs1 = $res->X;
                            $model->Abs2 = $res->Y;
                            $model->Abs3 = $res->Z;
                            $model->B = $res->CB;
                            $model->M = $res->CM;
                            $model->R = $res->CR;
                            $model->Promedio = $x;
                            $model->Vol_dilucion = $d;
                            $model->Vol_muestra = $res->E;
                            $model->Blanco = $res->CA;
                            $model->Analizo = Auth::user()->id;
                            $model->save();
                            break;
                        case 106:
                            # N-nitratos (potable)
                            $d = 10 / $res->E;
                            $x = ($res->X + $res->Y + $res->Z) / 3;
                            $xround = round($x, 3);
                            $resultado = ((($xround - $res->CB) / $res->CM) * $d);

                            $model = LoteDetalleEspectro::find($res->idMuestra);
                            $model->Resultado = $resultado;
                            $model->Abs1 = $res->X;
                            $model->Abs2 = $res->Y;
                            $model->Abs3 = $res->Z;
                            $model->B = $res->CB;
                            $model->M = $res->CM;
                            $model->R = $res->CR;
                            $model->Promedio = $x;
                            $model->Vol_dilucion = $d;
                            $model->Vol_muestra = $res->E;
                            $model->Blanco = $res->CA;
                            $model->Analizo = Auth::user()->id;
                            $model->save();
                            break;
                        case 103: //Dureza
                            $x = $res->A - $res->B;
                            $d = ($x * $res->RE) * 1000;
                            $resultado = $d / $res->D;

                            $model = LoteDetalleEspectro::find($res->idMuestra);
                            $model->Resultado = round($resultado,2);
                            $model->Abs1 = $res->X;
                            $model->Abs2 = $res->Y;
                            $model->Abs3 = $res->Z;
                            $model->B = $res->CB;
                            $model->M = $res->CM;
                            $model->R = $res->CR;
                            $model->Promedio = $x;
                            $model->Vol_dilucion = $d;
                            $model->Vol_muestra = $res->E;
                            $model->Blanco = $res->CA;
                            $model->Analizo = Auth::user()->id;
                            $model->save();
                            break;
                        case 105: //Fluoruros (potable)
                        case 121:
                            $x = ($res->X + $res->Y + $res->Z) / 3;
                            $d =  50 / $res->E;
                            $xround = round($x, 3);
                            $resultado = (($xround - $res->CB) / $res->CM) * $d;

                            $model = LoteDetalleEspectro::find($res->idMuestra);
                            $model->Resultado = $resultado;
                            $model->Abs1 = $res->X;
                            $model->Abs2 = $res->Y;
                            $model->Abs3 = $res->Z;
                            $model->B = $res->CB;
                            $model->M = $res->CM;
                            $model->R = $res->CR;
                            $model->Promedio = $x;
                            $model->Vol_dilucion = $d;
                            $model->Vol_muestra = $res->E;
                            $model->Blanco = $res->CA;
                            $model->Analizo = Auth::user()->id;
                            $model->save();
                            break;

                        case 113:
                            // Sulfatos Residual
                            $x = ($res->X + $res->Y + $res->Z + $res->ABS4 + $res->ABS5 + $res->ABS6 + $res->ABS7 + $res->ABS8) / 8;
                            $d =   100  / $res->E;
                            $res1 = round($x, 3) - ($res->CB);
                            $res2 = $res1 / $res->CM;
                            $resultado = $res2 * round($d, 3);

                            $model = LoteDetalleEspectro::find($res->idMuestra);
                            $model->Resultado = $resultado;
                            $model->Abs1 = $res->X;
                            $model->Abs2 = $res->Y;
                            $model->Abs3 = $res->Z;
                            $model->Abs4 = $res->ABS4;
                            $model->Abs5 = $res->ABS5;
                            $model->Abs6 = $res->ABS6;
                            $model->Abs7 = $res->ABS7;
                            $model->Abs8 = $res->ABS8;
                            $model->B = $res->CB;
                            $model->M = $res->CM;
                            $model->R = $res->CR;
                            $model->Promedio = $x;
                            $model->Vol_dilucion = $d;
                            $model->Vol_muestra = $res->E;
                            $model->Blanco = $res->CA;
                            $model->Analizo = Auth::user()->id;
                            $model->save();
                            break;
                        case 95:
                            // Sulfatos Potable 
                            $val1 = $res->X2 - $res->X;
                            $val2 = $res->Y2 - $res->Y;
                            $val3 = $res->Z2 - $res->Z; 
                            $prom1 = ($res->X + $res->Y + $res->Z) / 3;
                            $prom2 = ($res->X2 + $res->Y2 + $res->Z2) / 3;
                            $x = ($val1 + $val2 + $val3) / 3;
                            $d =   100  / $res->E; 
                            $res1 = round($x, 3) - ($res->CB);
                            $res2 = $res1 / $res->CM;
                            $resultado = $res2 * round($d, 3);

                            $model = LoteDetalleEspectro::find($res->idMuestra);
                            $model->Resultado = $resultado;
                            $model->Abs1 = $res->X;
                            $model->Abs2 = $res->Y;
                            $model->Abs3 = $res->Z;
                            $model->Abs4 = $res->X2;
                            $model->Abs5 = $res->Y2;
                            $model->Abs6 = $res->Z2;
                            $model->Abs7 = $prom1;
                            $model->Abs8 = $prom2;
                            $model->B = $res->CB;
                            $model->M = $res->CM;
                            $model->R = $res->CR;
                            $model->Promedio = $x;
                            $model->Vol_dilucion = $d;
                            $model->Vol_muestra = $res->E;
                            $model->Blanco = $res->CA;
                            $model->Analizo = Auth::user()->id;
                            $model->save();
                            break;
                        case 79:
                            $promedio = ($res->X + $res->Y + $res->Z) / 3;
                            $dilucion =  500 / $res->E;
                            $resultado = (($promedio - $res->CB) / $res->CM) * $dilucion;

                            $model = LoteDetalleEspectro::find($res->idMuestra);
                            $model->Resultado = round($resultado,3);
                            $model->Abs1 = $res->X;
                            $model->Abs2 = $res->Y;
                            $model->Abs3 = $res->Z;
                            $model->B = $res->CB;
                            $model->M = $res->CM;
                            $model->R = $res->CR;
                            $model->Promedio = $promedio;
                            $model->Vol_dilucion = $dilucion;
                            $model->Vol_muestra = $res->E;
                            $model->Blanco = $res->CA;
                            $model->Analizo = Auth::user()->id;
                            $model->save();
                            break;
                        
                        case 87:
                            $promedio = ($res->X + $res->Y + $res->Z) / 3;
                            $dilucion =  50 / $res->E;
                            $resultado = (($promedio - $res->CB) / $res->CM) * $dilucion;

                            $model = LoteDetalleEspectro::find($res->idMuestra);
                            $model->Resultado = round($resultado,3);
                            $model->Abs1 = $res->X;
                            $model->Abs2 = $res->Y;
                            $model->Abs3 = $res->Z;
                            $model->B = $res->CB;
                            $model->M = $res->CM;
                            $model->R = $res->CR;
                            $model->Promedio = $promedio;
                            $model->Vol_dilucion = $dilucion;
                            $model->Vol_muestra = $res->E;
                            $model->Blanco = $res->CA;
                            $model->Analizo = Auth::user()->id;
                            $model->save();
                            break;
                        case 87:
                                $promedio = round(($res->X + $res->Y + $res->Z) / 3,3);
                                $dilucion =  50 / $res->E;
                                $resultado = (($promedio - $res->CB) / $res->CM) * $dilucion;
    
                                $model = LoteDetalleEspectro::find($res->idMuestra);
                                $model->Resultado = round($resultado,3);
                                $model->Abs1 = $res->X;
                                $model->Abs2 = $res->Y;
                                $model->Abs3 = $res->Z;
                                $model->B = $res->CB;
                                $model->M = $res->CM;
                                $model->R = $res->CR;
                                $model->Promedio = $promedio;
                                $model->Vol_dilucion = $dilucion;
                                $model->Vol_muestra = $res->E;
                                $model->Blanco = $res->CA;
                                $model->Analizo = Auth::user()->id;
                                $model->save();
                                break;
                        default:
                            $promedio = ($res->X + $res->Y + $res->Z) / 3;
                            $dilucion =  50 / $res->E;
                            $resultado = (($promedio - $res->CB) / $res->CM) * $dilucion;

                            $model = LoteDetalleEspectro::find($res->idMuestra);
                            $model->Resultado = round($resultado,3);
                            $model->Abs1 = $res->X;
                            $model->Abs2 = $res->Y;
                            $model->Abs3 = $res->Z;
                            $model->B = $res->CB;
                            $model->M = $res->CM;
                            $model->R = $res->CR;
                            $model->Promedio = $promedio;
                            $model->Vol_dilucion = $dilucion;
                            $model->Vol_muestra = $res->E;
                            $model->Blanco = $res->CA;
                            $model->Analizo = Auth::user()->id;
                            $model->save();
                            break;
                    }
                    break;
                case 13: //G&A
                    if ($res->R != '') {
                        $matraz = MatrazGA::where('Estado', 0)->get();
                        if ($matraz->count()) {
                            $mat = rand(0, $matraz->count());
                            $matraz[$mat]->Estado = 1;
                            $matraz[$mat]->save();
                        } else {
                            $std = false;
                        }

                        //$m3 = mt_rand($matraz->Min, $matraz->Max);
                        $dif = ($matraz[$mat]->Max - $matraz[$mat]->Min);
                        $ran = (round($dif, 4)) / 10;
                        $m3 = $matraz[$mat]->Max - $ran;

                        $mf = ((($res->R / $res->E) * $res->I) + $m3);
                        $m1 = ($m3 - 0.0002);
                        $m2 = ($m3 - 0.0001);

                        $auxMf = ((round($mf,4) - $m3) / $res->I) * $res->E;
                        $resultado = $auxMf - $res->G;

                        $model = LoteDetalleGA::find($res->idMuestra);
                        $model->Id_matraz = $matraz[$mat]->Id_matraz;
                        $model->Matraz = $matraz[$mat]->Num_serie;
                        $model->M_final = round($mf,4);
                        $model->M_inicial1 = $m1;
                        $model->M_inicial2 = $m2;
                        $model->M_inicial3 = $m3;
                        $model->Ph = $res->L;
                        $model->Blanco = $res->G;
                        $model->F_conversion = $res->E;
                        $model->Vol_muestra = $res->I;
                        $model->Resultado = round($resultado,2);
                        $model->Analizo = Auth::user()->id;
                        $model->save();
                    } else {
                        $res1 = $res->H - $res->C;
                        $res2 = $res1 / $res->I;
                        $res3 = $res2 * $res->E;
                        $resultado = $res3 - $res->G;

                        $matraz = MatrazGA::where('Num_serie', $res->P)->first();

                        $model = LoteDetalleGA::find($res->idMuestra);
                        $model->M_final = $res->H;
                        $model->Id_matraz = $matraz->Id_matraz;
                        $model->Matraz = $matraz->Num_serie;
                        $model->M_inicial1 = $res->J;
                        $model->M_inicial2 = $res->K;
                        $model->M_inicial3 = $res->C;
                        $model->Ph = $res->L;
                        $model->Blanco = $res->G;
                        $model->F_conversion = $res->E;
                        $model->Vol_muestra = $res->I;
                        $model->Resultado = $resultado;
                        $model->Analizo = Auth::user()->id;
                        $model->save();
                    }
                    break;
                case 15: // Solidos
                    switch ($lote[0]->Id_tecnica) {
                        case 3: // Directos
                            $model = LoteDetalleSolidos::find($res->idMuestra);
                            $model->Resultado = $res->resultado;
                            $model->Inmhoff = $res->inmhoff;
                            $model->Temp_muestraLlegada = $res->temperaturaLlegada;
                            $model->Temp_muestraAnalizada = $res->temperaturaAnalizada;
                            $model->save();
                            break;
                        case 47: // Por diferencia
                        case 88:
                        case 44:
                        case 45:
                            $model = LoteDetalleSolidos::find($res->idMuestra);
                            $model->Resultado = $res->resultado;
                            $model->Masa1 = $res->val1;
                            $model->Masa2 = $res->val2;
                            $model->Analizo = Auth::user()->id;
                            $model->save();

                            break;
                        default: // Default
                            if ($res->R != "") {

                                $modelCrisol = CrisolesGA::all();
                                //? Aplica la busqueda de crisol hasta encontrar un crisol desocupado
                                $cont = $modelCrisol->count();

                                for ($i = 0; $i < $cont; $i++) {
                                    # code...
                                    $id = rand(0, $modelCrisol->count());
                                    $crisol = CrisolesGA::where('Id_crisol', $id)->first();
                                    if ($crisol->Estado == 0) {
                                        break;
                                    }
                                }


                                $mf = ((($res->R / $res->factor) * $res->volumen) + round($crisol->Peso,4));
                                $auxMf =  (((round($mf,4) - round($crisol->Peso,4)) / $res->volumen) * $res->factor);
                                $resultado = $auxMf;

                                $model = LoteDetalleSolidos::find($res->idMuestra);
                                $model->Id_crisol = $crisol->Id_crisol;
                                $model->Crisol = $crisol->Num_serie;
                                $model->Masa1 = round($crisol->Peso,4);
                                $model->Masa2 = round($mf,4);
                                $model->Peso_muestra1 = round(($crisol->Peso + 0.0001),4);
                                $model->Peso_muestra2 = round(($crisol->Peso + 0.0002),4);
                                $model->Peso_constante1 = round(($mf + 0.0001),4);
                                $model->Peso_constante2 = round(($mf + 0.0002),4);
                                $model->Vol_muestra = $res->volumen;
                                $model->Factor_conversion = $res->factor;
                                $model->Resultado = $resultado;
                                $model->Analizo = Auth::user()->id;
                                $model->save();
                            } else {
                                $res1 = $res->masa2 - $res->masa1;
                                $res2 = $res1 / $res->volumen;
                                $resultado = $res2 * $res->factor;


                                $model = LoteDetalleSolidos::find($res->idMuestra);
                                $model->Crisol = $res->crisol;
                                $model->Masa1 = $res->masa1;
                                $model->Masa2 = $res->masa2;
                                $model->Peso_muestra1 = $res->pesoConMuestra1;
                                $model->Peso_muestra2 = $res->pesoConMuestra2;
                                $model->Peso_constante1 = $res->pesoC1;
                                $model->Peso_constante2 = $res->pesoC2;
                                $model->Vol_muestra = $res->volumen;
                                $model->Factor_conversion = $res->factor;
                                $model->Resultado = $resultado;
                                $model->Analizo = Auth::user()->id;
                                $model->save();
                            }

                            break;
                    }
                    break;
                case 14: // volumetria
                    switch ($lote[0]->Id_tecnica) {
                        case 6: // Dqo
                        case 161:
                            $x = 0;
                            $d = 0;
                            if ($res->sw == 2) {
                                $res1 = ($res->CA - $res->B);
                                $res2 = ($res1 * $res->C);
                                $res3 = ($res2 * $res->D);
                                $resultado = ($res3 / $res->E);

                                $model = LoteDetalleDqo::find($res->idMuestra);
                                $model->Titulo_muestra = $res->B;
                                $model->Molaridad = $res->C;
                                $model->Titulo_blanco = $res->CA;
                                $model->Equivalencia = $res->D;
                                $model->Vol_muestra = $res->E;
                                $model->Resultado = $resultado;
                                $model->Tecnica = $res->radio;
                                $model->Analizo = Auth::user()->id;
                                $model->save();
                            } else {
                                $d = 2 / $res->E;
                                $x = ($res->X + $res->Y + $res->Z) / 3;
                                $resultado = ((($x - $res->CB) / $res->CM) * $d);

                                $model = LoteDetalleDqo::find($res->idMuestra);
                                $model->Vol_muestra = $res->Vol_muestra;
                                $model->Abs_prom = $res->ABS;
                                $model->Blanco = $res->CA;
                                $model->Factor_dilucion = $res->D;
                                $model->Vol_muestra = $res->Vol_muestra;
                                $model->Abs1 = $res;
                                $model->Abs2 = $res->Y;
                                $model->Abs3 = $res->Z;
                                $model->Resultado = $res->resultado;
                                $model->Tecnica = $res->radio;
                                $model->Analizo = Auth::user()->id;
                                $model->save();
                            }

                            break;
                        case 33: // Cloro
                        case 218:
                        case 119:
                            $res1 = $res->A - $res->B;
                            $res2 = $res1 * $res->C;
                            $res3 = $res2 * $res->D;
                            $resultado = $res3 / $res->E;

                            $model = LoteDetalleCloro::find($res->idMuestra);
                            $model->Vol_muestra = $res->A;
                            $model->Ml_muestra = $res->E;
                            $model->Vol_blanco = $res->B;
                            $model->Normalidad = $res->C;
                            $model->Ph_inicial = $res->G;
                            $model->Ph_final = $res->H;
                            $model->Factor_conversion = $res->D;
                            $model->Resultado = round($resultado,2);
                            $model->analizo = Auth::user()->id;
                            $model->save();
                            break;
                        case 64:
                            $res1 = $res->A - $res->B;
                            $res2 = $res1 * $res->C;
                            $res3 = $res2 * $res->D;
                            $resultado = $res3 / $res->E;

                            $model = LoteDetalleCloro::find($res->idMuestra);
                            $model->Vol_muestra = $res->A;
                            $model->Ml_muestra = $res->E;
                            $model->Vol_blanco = $res->B;
                            $model->Normalidad = $res->C;
                            $model->Ph_inicial = $res->G;
                            $model->Ph_final = $res->H;
                            $model->Factor_conversion = $res->D;
                            $model->Resultado = round($resultado,2);
                            $model->analizo = Auth::user()->id;
                            $model->save();
                            break;
                        case 9: // Nitrogeno
                        case 10:
                        case 11: //Nitrogeno total
                        case 287:
                        case 83:
                            $res1 = $res->A - $res->B;
                            $res2 = $res1 * $res->C;
                            $res3 = $res2 * $res->D;
                            $res4 = $res3 * $res->E;
                            $resultado = $res4 / $res->G;

                            $model = LoteDetalleNitrogeno::find($res->idMuestra);
                            $model->Titulado_muestra = $res->A;
                            $model->Titulado_blanco = $res->B;
                            $model->Molaridad = $res->C;
                            $model->Factor_equivalencia = $res->D;
                            $model->Factor_conversion = $res->E;
                            $model->Vol_muestra = $res->G;
                            $model->Resultado = $resultado;
                            $model->analizo = Auth::user()->id;
                            $model->save();
                            break;
                        case 108:
                            $a = $res->A * $res->B;
                            $d = 100 + $res->D;
                            $c = 100 + $res->C;

                            $resultado = $a * ($d / $c);

                            $model = LoteDetalleNitrogeno::find($res->idMuestra);
                            $model->Titulado_muestra = $res->A; //Facor de dilución
                            $model->Titulado_blanco = $res->B; //Concentracion de NH3 en mg/L
                            $model->Molaridad = $res->C; //Volumen Añadido al std
                            $model->Factor_equivalencia = $res->D; //Volumen añadido a la muestra
                            $model->Vol_muestra = $res->V; //Volumen de la muestra en mL 
                            $model->Resultado = $resultado; //Resultado
                            $model->Observacion = $res->O; //observacion
                            $model->analizo = Auth::user()->id;
                            $model->save();
                            break;
                        default:
                            # code...
                            break;
                    }
                    break;
                case 6: //Mb
                case 12:
                case 3:
                    switch ($lote[0]->Id_tecnica) {
                        default:
                            $model = LoteDetalleDirectos::find($res->idMuestra);
                            $model->Resultado = $res->resultado;
                            $model->save();
                            break;
                    }
                    break;
                case 7: //Muestreo
                case 19: //Directos
                    switch ($lote[0]->Id_tecnica) {
                        case 14:
                        case 110:
                            $resultado = "";
                            $promedio = ($res->l1 + $res->l2 + $res->l3) / 3;
                            $resultado = round($promedio, 1);
                            $model = LoteDetalleDirectos::find($res->idMuestra);
                            $model->Resultado = $resultado;
                            $model->Lectura1 = $res->l1;
                            $model->Lectura2 = $res->l2;
                            $model->Lectura3 = $res->l3;
                            $model->Temperatura = $res->temp;
                            $model->Promedio = $res->promedio;
                            $model->save();

                            break;
                        case 67:
                        case 68:
                            $resultado = "";
                            $promedio = ($res->l1 + $res->l2 + $res->l3) / 3;
                            $resultado = round($promedio, 0);
                            $model = LoteDetalleDirectos::find($res->idMuestra);
                            $model->Resultado = $resultado;
                            $model->Lectura1 = $res->l1;
                            $model->Lectura2 = $res->l2;
                            $model->Lectura3 = $res->l3;
                            $model->Temperatura = $res->temp;
                            $model->Promedio = $res->promedio;
                            $model->save();

                            break;
                        case 119:
                        case 218:
                            $resultado = 0;
                            $dilusion = $res->dilucion;
                            $promedio = ($res->l1 + $res->l2 + $res->l3) / 3;
                            $resultado = round($promedio * $dilusion, 2);
                            $model = LoteDetalleDirectos::find($res->idMuestra);
                            $model->Factor_dilucion = $res->dilucion;
                            $model->Resultado = $resultado;
                            $model->Vol_muestra = $res->volumen;
                            $model->Lectura1 = $res->l1;
                            $model->Lectura2 = $res->l2;
                            $model->Lectura3 = $res->l3;
                            $model->Promedio = $promedio;
                            $model->save();
                            break;
                        case 98:
                        case 58:
                        case 89:
                        case 115:
                            $resultado = 0;
                            $promedio = ($res->l1 + $res->l2 + $res->l3) / 3;
                            $resultado = round($promedio, 2);
                            $model = LoteDetalleDirectos::find($res->idMuestra);
                            $model->Resultado = $resultado;
                            $model->Factor_dilucion = $res->factor;
                            $model->Vol_muestra = $res->volumen;
                            $model->Lectura1 = $res->l1;
                            $model->Lectura2 = $res->l2;
                            $model->Lectura3 = $res->l3;
                            $model->Promedio = $promedio;
                            $model->save();

                            break;
                        case 97:
                        case 33:
                            $resultado = "";

                            $promedio = ($res->l1 + $res->l2 + $res->l3) / 3;
                            $resultado = round($promedio, 3);

                            $model = LoteDetalleDirectos::find($res->idMuestra);
                            $model->Resultado = $resultado;
                            $model->Lectura1 = $res->l1;
                            $model->Lectura2 = $res->l2;
                            $model->Lectura3 = $res->l3;
                            $model->Promedio = $res->promedio;
                            $model->save();
                            break;
                        case 102:
                        case 66:
                        case 65:
                        case 120:
                            $resultado = 0;
                            //$factor = 0;
                            $dilusion = 50 / $res->volumen;
                            $promedio = ($res->aparente + $res->verdadero) * $res->dilusion;

                            $resultado = $promedio + $res->factor;

                            $model = LoteDetalleDirectos::find($res->idMuestra);
                            $model->Resultado = $resultado;
                            $model->Color_a = $res->aparente;
                            $model->Color_v = $res->verdadero;
                            $model->Factor_dilucion = $dilusion;
                            $model->Vol_muestra = $res->volumen;
                            $model->Ph = $res->ph;
                            $model->Factor_correcion = $res->factor;
                            $model->save();
                            break;
                        default: // Default Directos
                            $model = LoteDetalleDirectos::find($res->idMuestra);
                            $model->Resultado = $res->resultado;
                            $model->save();
                            break; 
                    }
                    break;
                default:
                    $model = array();
                    break;
            }
        }
        $data = array(
            'model' => $model,
            'std' => $std,
        );
        return response()->json($data);
    }
    public function getDetalleLote(Request $res)
    {
        $lote = DB::table('ViewLoteAnalisis')->where('Id_lote', $res->id)->get();
        $model = array();

        $plantilla = Bitacoras::where('Id_lote', $res->id)->get();
        if ($plantilla->count()) {
        } else {
            $plantilla = PlantillaBitacora::where('Id_parametro', $lote[0]->Id_tecnica)->get();
        }

        if ($lote->count()) {
            switch ($lote[0]->Id_area) {
                case 16: // Espectrofotometria
                case 5: // Fisicoquimicos

                    break;
                case 13: //G&A
                    $model = GrasasDetalle::where('Id_lote', $res->id)->first();
                    break;
                case 15: //Solidos
                    break;
                case 14: //volumetria
                case 8: //potable
                    switch ($lote[0]->Id_tecnica) {
                        case 33: // CLORO RESIDUAL LIBRE
                        case 64:

                            break;
                        case 28: //Alcalinidad
                        case 29:

                            break;
                        case 6: // DQO
                        case 161:
                            $temp = DqoDetalle::where('Id_lote', $res->id)->get();
                            switch ($temp[0]->Tipo) {
                                case 1: // Dqo Alta
                                    $plantilla = Bitacoras::where('Id_lote', $res->id)->get();
                                    if ($plantilla->count()) {
                                        // if ($temp[0]->Soluble == 1) {
                                        //     $plantilla = PlantillaBitacora::where('Id_parametro', 159)->get();
                                        // } else {
                                        //     $plantilla = PlantillaBitacora::where('Id_parametro', 72)->get();
                                        // }
                                    } else {
                                        if ($temp[0]->Soluble == 1) {
                                            $plantilla = PlantillaBitacora::where('Id_parametro', 159)->get();
                                        } else {
                                            $plantilla = PlantillaBitacora::where('Id_parametro', 72)->get();
                                        }
                                    }
                                    break;
                                case 2:
                                    $plantilla = Bitacoras::where('Id_lote', $res->id)->get();
                                    if ($plantilla->count()) {
                                        // if ($temp[0]->Soluble == 1) {
                                        //     $plantilla = PlantillaBitacora::where('Id_parametro', 160)->get();
                                        // } else {
                                        //     $plantilla = PlantillaBitacora::where('Id_parametro', 73)->get();
                                        // }
                                    } else {
                                        if ($temp[0]->Soluble == 1) {
                                            $plantilla = PlantillaBitacora::where('Id_parametro', 160)->get();
                                        } else {
                                            $plantilla = PlantillaBitacora::where('Id_parametro', 73)->get();
                                        }
                                    }
    
                                    break;
                            }
                            break;
                        case 11: //Nitrogeno Total
                        case 9: //Nitrogeno Amoniacal
                        case 108:
                        case 10: //Nitrogeno Organico

                            break;
                        case 103: //Dureza

                        default:
                            break;
                    }
                    break;
                case 7: //campo
                case 19: //directo
                    break;
                case 6: // MB
                case 12: // Mb Alimentos
                    break;
                default:
                    $model = array();
                    break;
            }
        }

       
        $data = array(
            'model' => $model,
            'plantilla' => $plantilla,
            'lote' => $lote[0],
        );
        return response()->json($data);
    }
    public function setControlCalidad(Request $res)
    {
        $lote = LoteAnalisis::where('Id_lote', $res->idLote)->get();
        if ($lote->count()) {
            switch ($lote[0]->Id_area) {
                case 16: // Espectrofotometria
                case 5: // Fisicoquimicos
                    switch ($lote[0]->Id_tecnica) {
                        case 0:

                            break;
                        default:
                            $muestra = LoteDetalleEspectro::where('Id_detalle', $res->idMuestra)->first();
                            $model = $muestra->replicate();
                            $model->Id_control = $res->idControl;
                            $model->Resultado = "";
                            $model->Liberado = 0;
                            $model->save();

                            $model = LoteDetalleEspectro::where('Id_lote', $res->idLote)->get();
                            break;
                    }
                    break;
                case 13: //G&A
                    $muestra = LoteDetalleGA::where('Id_detalle', $res->idMuestra)->first();
                    $model = $muestra->replicate();
                    $model->Id_control = $res->idControl;
                    $model->Resultado = NULL;
                    $model->Liberado = 0;
                    $model->save();

                    $model = LoteDetalleGA::where('Id_lote', $res->idLote)->get();
                    break;
                case 15: //Solidos
                    $muestra = LoteDetalleSolidos::where('Id_detalle', $res->idMuestra)->first();
                    $model = $muestra->replicate();
                    $model->Id_control = $res->idControl;
                    $model->Resultado = NULL;
                    $model->Liberado = 0;
                    $model->save();

                    $model = LoteDetalleSolidos::where('Id_lote', $res->idLote)->get();
                    break;
                case 14: //Volumetria
                    switch ($lote[0]->Id_tecnica) {
                        case 6: // Dqo
                        case 161:
                            $muestra = LoteDetalleDqo::where('Id_detalle', $res->idMuestra)->first();
                            $model = $muestra->replicate();
                            $model->Id_control = $res->idControl;
                            $model->Resultado = NULL;
                            $model->Liberado = 0;
                            $model->save();

                            $model = LoteDetalleDqo::where('Id_lote', $res->idLote)->get();
                            break;
                        case 33: // Cloro
                        case 218:
                        case 64:
                            $muestra = LoteDetalleCloro::where('Id_detalle', $res->idMuestra)->first();
                            $model = $muestra->replicate();
                            $model->Id_control = $res->idControl;
                            $model->Resultado = NULL;
                            $model->Liberado = 0;
                            $model->save();

                            $model = LoteDetalleCloro::where('Id_lote', $res->idLote)->get();
                            break;
                        case 9: // Nitrogeno
                        case 10:
                        case 11:
                        case 287:
                        case 83:
                        case 108:
                            $muestra = LoteDetalleNitrogeno::where('Id_detalle', $res->idMuestra)->first();
                            $model = $muestra->replicate();
                            $model->Id_control = $res->idControl;
                            $model->Resultado = NULL;
                            $model->Liberado = 0;
                            $model->save();

                            $model = LoteDetalleNitrogeno::where('Id_lote', $res->idLote)->get();
                            break;
                        default:

                            $muestra = LoteDetalleDirectos::where('Id_detalle', $res->idMuestra)->first();
                            $model = $muestra->replicate();
                            $model->Id_control = $res->idControl;
                            $model->Resultado = NULL;
                            $model->Liberado = 0;
                            $model->save();

                            $model = LoteDetalleDirectos::where('Id_lote', $res->idLote)->get();
                            break;
                    }
                    break;
                case 7: //Campo
                case 19: //Directos
                    $muestra = LoteDetalleDirectos::where('Id_detalle', $res->idMuestra)->first();
                    $model = $muestra->replicate();
                    $model->Id_control = $res->idControl;
                    $model->Resultado = NULL;
                    $model->Liberado = 0;
                    $model->save();

                    $model = LoteDetalleDirectos::where('Id_lote', $res->idLote)->get();
                    break;
                case 6://Mb
                case 12://Mb Alimentos
                    switch ($lote[0]->Id_tecnica) {
                        case 135: // Coliformes fecales
                        case 132:
                        case 133:
                        case 12:
                        case 134: // E COLI
                        case 35:
                        case 51: // Coliformes totales
                        case 137:
                            $muestra = LoteDetalleColiformes::where('Id_detalle', $res->idMuestra)->first();
                            $model = $muestra->replicate();
                            $model->Id_control = $res->idControl;
                            $model->Resultado = NULL;
                            $model->Liberado = 0;
                            $model->save();

                            $model = LoteDetalleColiformes::where('Id_lote', $res->idLote)->get();
                            break;
                        case 253: //todo  ENTEROCOCO FECAL
                            $muestra = LoteDetalleEnterococos::where('Id_detalle', $res->idMuestra)->first();
                            $model = $muestra->replicate();
                            $model->Id_control = $res->idControl;
                            $model->Resultado = NULL;
                            $model->Liberado = 0;
                            $model->save();

                            $model = LoteDetalleEnterococos::where('Id_lote', $res->idLote)->get();
                            break;
                        case 5: //todo DEMANDA BIOQUIMICA DE OXIGENO (DBO5) 
                        case 71:
                            $muestra = LoteDetalleDbo::where('Id_detalle', $res->idMuestra)->first();
                            $model = $muestra->replicate();
                            $model->Id_control = $res->idControl;
                            $model->Resultado = NULL;
                            $model->Liberado = 0;
                            $model->save();

                            $model = LoteDetalleDbo::where('Id_lote', $res->idLote)->get();
                            break;
                        case 70:
                            $muestra = LoteDetalleDboIno::where('Id_detalle', $res->idMuestra)->first();
                            $model = $muestra->replicate();
                            $model->Id_control = $res->idControl;
                            $model->Resultado = NULL;
                            $model->Liberado = 0;
                            $model->save();

                            $model = LoteDetalleDboIno::where('Id_lote', $res->idLote)->get();
                            break;
                        case 16: //todo Huevos de Helminto 
                            $muestra = LoteDetalleHH::where('Id_detalle', $res->idMuestra)->first();
                            $model = $muestra->replicate();
                            $model->Id_control = $res->idControl;
                            $model->Resultado = NULL;
                            $model->Liberado = 0;
                            $model->save();

                            $model = LoteDetalleHH::where('Id_lote', $res->idLote)->get();
                            break;
                        case 78:
                            $muestra = LoteDetalleEcoli::where('Id_detalle', $res->idMuestra)->first();
                            $model = $muestra->replicate();
                            $model->Id_control = $res->idControl;
                            $model->Resultado = NULL;
                            $model->Liberado = 0;
                            $model->save();

                            $model = LoteDetalleEcoli::where('Id_lote', $res->idLote)->get();
                            break;
                        default:
                            $model = array();
                            break;
                    }
                    break;
                    case 8: //Potable
                        switch ($lote[0]->Id_tecnica) {
                            case 77: //Dureza
                            case 103:
                            case 251:
                            case 252:
                                $muestra = LoteDetalleDureza::where('Id_detalle', $res->idMuestra)->first();
                                $model = $muestra->replicate();
                                $model->Id_control = $res->idControl;
                                $model->Resultado = NULL;
                                $model->Liberado = 0;
                                $model->save();

                                $model = LoteDetalleDureza::where('Id_lote', $res->idLote)->get();
                                break;
                            default:
                                $muestra = LoteDetallePotable::where('Id_detalle', $res->idMuestra)->first();
                                $model = $muestra->replicate();
                                $model->Id_control = $res->idControl;
                                $model->Resultado = NULL;
                                $model->Liberado = 0;
                                $model->save();

                                $model = LoteDetallePotable::where('Id_lote', $res->idLote)->get();

                                break;
                        }
                        break;
                    default:
                    $muestra = LoteDetalleDirectos::where('Id_detalle', $res->idMuestra)->first();
                    $model = $muestra->replicate();
                    $model->Id_control = $res->idControl;
                    $model->Resultado = NULL;
                    $model->Liberado = 0;
                    $model->save();

                    $model = LoteDetalleDirectos::where('Id_lote', $res->idLote)->get();
                    break;
            }
        }

        $lote = LoteAnalisis::find($res->idLote);
        $lote->Asignado = $model->count();
        $lote->save();

        $data = array(
            'lote' => $lote,
            'model' => $model,
        );
        return response()->json($data);
    }
    public function setLiberarTodo(Request $res)
    {
        $sw = false;
        $lote = LoteAnalisis::where('Id_lote', $res->idLote)->get();
        if ($lote->count()) {
            switch ($lote[0]->Id_area) {
                case 16: // Espectrofotometria
                case 5: // Fisicoquimicos
                    switch ($lote[0]->Id_tecnica) {
                        case 0:

                            break;
                        default:
                            $muestras = LoteDetalleEspectro::where('Id_lote', $res->idLote)->where('Liberado', 0)->get();
                            foreach ($muestras as $item) {
                                $model = LoteDetalleEspectro::find($item->Id_detalle);
                                $model->Liberado = 1;
                                if ($model->Resultado != null) {
                                    $sw = true;
                                    $model->save();
                                }
                                if ($item->Id_control == 1) {
                                    $modelCod = CodigoParametros::find($model->Id_codigo);
                                    $modelCod->Resultado = $model->Resultado;
                                    $modelCod->Resultado2 = $model->Resultado;
                                    $modelCod->Analizo = Auth::user()->id;
                                    $modelCod->save();
                                }
                            }
                            $model = LoteDetalleEspectro::where('Id_lote', $res->idLote)->where('Liberado', 1)->get();
                            break;
                    }
                    break;
                case 13: // G&A
                    $muestras = LoteDetalleGA::where('Id_lote', $res->idLote)->where('Liberado', 0)->get();
                    foreach ($muestras as $item) {
                        $model = LoteDetalleGA::find($item->Id_detalle);
                        $model->Liberado = 1;
                        if ($model->Resultado != null) {
                            $sw = true;
                            $model->save();

                            $modelMatraz = MatrazGA::find($model->Id_matraz);
                            $modelMatraz->Estado = 0;
                            $modelMatraz->save();
                        }
                        if ($item->Id_control == 1) {
                            $modelCod = CodigoParametros::find($model->Id_codigo);
                            $modelCod->Resultado = $model->Resultado;
                            $modelCod->Resultado2 = $model->Resultado;
                            $modelCod->Analizo = Auth::user()->id;
                            $modelCod->save();
                        }
                    }
                    $model = LoteDetalleGA::where('Id_lote', $res->idLote)->where('Liberado', 1)->get();
                    break;
                case 15: //Solidos
                    $muestras = LoteDetalleSolidos::where('Id_lote', $res->idLote)->where('Liberado', 0)->get();
                    foreach ($muestras as $item) {
                        $model = LoteDetalleSolidos::find($item->Id_detalle);
                        $model->Liberado = 1;
                        if ($model->Resultado != null) {
                            $sw = true;
                            $model->save();
                        }
                        if ($item->Id_control == 1) {
                            $modelCod = CodigoParametros::find($model->Id_codigo);
                            $modelCod->Resultado = $model->Resultado;
                            $modelCod->Resultado2 = $model->Resultado;
                            $modelCod->Analizo = Auth::user()->id;
                            $modelCod->save();
                        }
                    }
                    $model = LoteDetalleSolidos::where('Id_lote', $res->idLote)->where('Liberado', 1)->get();
                    break;
                case 14: //Volumetria
                    switch ($lote[0]->Id_tecnica) {
                        case 6: // Dqo
                        case 161:
                            $muestras = LoteDetalleDqo::where('Id_lote', $res->idLote)->where('Liberado', 0)->get();
                            foreach ($muestras as $item) {
                                $model = LoteDetalleDqo::find($item->Id_detalle);
                                $model->Liberado = 1;
                                if ($model->Resultado != null) {
                                    $sw = true;
                                    $model->save();
                                }
                                if ($item->Id_control == 1) {
                                    $modelCod = CodigoParametros::find($model->Id_codigo);
                                    $modelCod->Resultado = $model->Resultado;
                                    $modelCod->Resultado2 = $model->Resultado;
                                    $modelCod->Analizo = Auth::user()->id;
                                    $modelCod->save();
                                }
                            }

                            $model = LoteDetalleDqo::where('Id_lote', $res->idLote)->where('Liberado', 1)->get();
                            break;
                        case 33: // Cloro
                        case 218:
                        case 64:

                            $muestras = LoteDetalleCloro::where('Id_lote', $res->idLote)->where('Liberado', 0)->get();
                            foreach ($muestras as $item) {
                                $model = LoteDetalleCloro::find($item->Id_detalle);
                                $model->Liberado = 1;
                                if ($model->Resultado != null) {
                                    $sw = true;
                                    $model->save();
                                }
                                if ($item->Id_control == 1) {
                                    $modelCod = CodigoParametros::find($model->Id_codigo);
                                    $modelCod->Resultado = $model->Resultado;
                                    $modelCod->Resultado2 = $model->Resultado;
                                    $modelCod->Analizo = Auth::user()->id;
                                    $modelCod->save();
                                }
                            }

                            $model = LoteDetalleCloro::where('Id_lote', $res->idLote)->where('Liberado', 1)->get();
                            break;
                        case 9: // Nitrogeno
                        case 10:
                        case 11:
                        case 287:
                        case 83:
                        case 108:
                        case 28://Alcalinidad
                            $muestras = LoteDetalleNitrogeno::where('Id_lote', $res->idLote)->where('Liberado', 0)->get();
                            foreach ($muestras as $item) {
                                $model = LoteDetalleNitrogeno::find($item->Id_detalle);
                                $model->Liberado = 1;
                                if ($model->Resultado != null) {
                                    $sw = true;
                                    $model->save();
                                }
                                if ($item->Id_control == 1) {
                                    $modelCod = CodigoParametros::find($model->Id_codigo);
                                    $modelCod->Resultado = $model->Resultado;
                                    $modelCod->Resultado2 = $model->Resultado;
                                    $modelCod->Analizo = Auth::user()->id;
                                    $modelCod->save();
                                }
                            }

                            $model = LoteDetalleNitrogeno::where('Id_lote', $res->idLote)->where('Liberado', 1)->get();
                            break;
                        default:
                            $muestras = LoteDetalleDirectos::where('Id_lote', $res->idLote)->where('Liberado', 0)->get();
                            foreach ($muestras as $item) {
                                $model = LoteDetalleDirectos::find($item->Id_detalle);
                                $model->Liberado = 1;
                                if ($model->Resultado != null) {
                                    $sw = true;
                                    $model->save();
                                }
                                if ($item->Id_control == 1) {
                                    $modelCod = CodigoParametros::find($model->Id_codigo);
                                    $modelCod->Resultado = $model->Resultado;
                                    $modelCod->Resultado2 = $model->Resultado;
                                    $modelCod->Analizo = Auth::user()->id;
                                    $modelCod->save();
                                }
                            }

                            $model = LoteDetalleDirectos::where('Id_lote', $res->idLote)->where('Liberado', 1)->get();
                            break;
                    }
                    break;
                case 7: //Campo
                case 19: //Directos

                    $muestras = LoteDetalleDirectos::where('Id_lote', $res->idLote)->where('Liberado', 0)->get();
                    foreach ($muestras as $item) {
                        $model = LoteDetalleDirectos::find($item->Id_detalle);
                        $model->Liberado = 1;
                        if ($model->Resultado != null) {
                            $sw = true;
                            $model->save();
                        }
                        if ($item->Id_control == 1) {
                            $modelCod = CodigoParametros::find($model->Id_codigo);
                            $modelCod->Resultado = $model->Resultado;
                            $modelCod->Resultado2 = $model->Resultado;
                            $modelCod->Analizo = Auth::user()->id;
                            $modelCod->save();
                        }
                    }

                    $model = LoteDetalleDirectos::where('Id_lote', $res->idLote)->where('Liberado', 1)->get();
                    break;

                case 6: //Mb
                case 12:
                case 3:
                    switch ($lote[0]->Id_tecnica) {
                        case 135: // Coliformes fecales
                        case 132:
                        case 133:
                        case 12:
                        case 134: // E COLI
                        case 35:
                        case 51: // Coliformes totales
                        case 137:
                            $muestras = LoteDetalleColiformes::where('Id_lote', $res->idLote)->where('Liberado', 0)->get();
                            foreach ($muestras as $item) {
                                $model = LoteDetalleColiformes::find($item->Id_detalle);
                                $model->Liberado = 1;
                                if ($model->Resultado != null) {
                                    $sw = true;
                                    $model->save();
                                }
                                if ($item->Id_control == 1) {
                                    $modelCod = CodigoParametros::find($model->Id_codigo);
                                    $modelCod->Resultado = $model->Resultado;
                                    $modelCod->Resultado2 = $model->Resultado;
                                    $modelCod->Analizo = Auth::user()->id;
                                    $modelCod->save();
                                }
                            }

                            $model = LoteDetalleColiformes::where('Id_lote', $res->idLote)->where('Liberado', 1)->get();
                            break;
                        case 253: //todo  ENTEROCOCO FECAL
                            $muestras = LoteDetalleEnterococos::where('Id_lote', $res->idLote)->where('Liberado', 0)->get();
                            foreach ($muestras as $item) {
                                $model = LoteDetalleEnterococos::find($item->Id_detalle);
                                $model->Liberado = 1;
                                if ($model->Resultado != null) {
                                    $sw = true;
                                    $model->save();
                                }
                                if ($item->Id_control == 1) {
                                    $modelCod = CodigoParametros::find($model->Id_codigo);
                                    $modelCod->Resultado = $model->Resultado;
                                    $modelCod->Resultado2 = $model->Resultado;
                                    $modelCod->Analizo = Auth::user()->id;
                                    $modelCod->save();
                                }
                            }

                            $model = LoteDetalleEnterococos::where('Id_lote', $res->idLote)->where('Liberado', 1)->get();
                            break;
                        case 5: //todo DEMANDA BIOQUIMICA DE OXIGENO (DBO5) 
                        case 71:
                            $muestras = LoteDetalleDbo::where('Id_lote', $res->idLote)->where('Liberado', 0)->get();
                            foreach ($muestras as $item) {
                                $model = LoteDetalleDbo::find($item->Id_detalle);
                                $model->Liberado = 1;
                                if ($model->Resultado != null) {
                                    $sw = true;
                                    $model->save();
                                }
                                if ($item->Id_control == 1) {
                                    $modelCod = CodigoParametros::find($model->Id_codigo);
                                    $modelCod->Resultado = $model->Resultado;
                                    $modelCod->Resultado2 = $model->Resultado;
                                    $modelCod->Analizo = Auth::user()->id;
                                    $modelCod->save();
                                }
                            }

                            $model = LoteDetalleDbo::where('Id_lote', $res->idLote)->where('Liberado', 1)->get();
                            break;
                        case 16: //todo Huevos de Helminto 
                            $muestras = LoteDetalleHH::where('Id_lote', $res->idLote)->where('Liberado', 0)->get();
                            foreach ($muestras as $item) {
                                $model = LoteDetalleHH::find($item->Id_detalle);
                                $model->Liberado = 1;
                                if ($model->Resultado != null) {
                                    $sw = true;
                                    $model->save();
                                }
                                if ($item->Id_control == 1) {
                                    $modelCod = CodigoParametros::find($model->Id_codigo);
                                    $modelCod->Resultado = $model->Resultado;
                                    $modelCod->Resultado2 = $model->Resultado;
                                    $modelCod->Analizo = Auth::user()->id;
                                    $modelCod->save();
                                }
                            }

                            $model = LoteDetalleHH::where('Id_lote', $res->idLote)->where('Liberado', 1)->get();
                            break;
                        case 78:
                            $muestras = LoteDetalleEcoli::where('Id_lote', $res->idLote)->where('Liberado', 0)->get();
                            foreach ($muestras as $item) {
                                $model = LoteDetalleEcoli::find($item->Id_detalle);
                                $model->Liberado = 1;
                                if ($model->Resultado != null) {
                                    $sw = true;
                                    $model->save();
                                }
                                if ($item->Id_control == 1) {
                                    $modelCod = CodigoParametros::find($model->Id_codigo);
                                    $modelCod->Resultado = $model->Resultado;
                                    $modelCod->Resultado2 = $model->Resultado;
                                    $modelCod->Analizo = Auth::user()->id;
                                    $modelCod->save();
                                }
                            }

                            $model = LoteDetalleEcoli::where('Id_lote', $res->idLote)->where('Liberado', 1)->get();
                            break;
                        default:
                            $muestras = LoteDetalleDirectos::where('Id_lote', $res->idLote)->where('Liberado', 0)->get();
                            foreach ($muestras as $item) {
                                $model = LoteDetalleDirectos::find($item->Id_detalle);
                                $model->Liberado = 1;
                                if ($model->Resultado != null) {
                                    $sw = true;
                                    $model->save();
                                }
                                if ($item->Id_control == 1) {
                                    $modelCod = CodigoParametros::find($model->Id_codigo);
                                    $modelCod->Resultado = $model->Resultado;
                                    $modelCod->Resultado2 = $model->Resultado;
                                    $modelCod->Analizo = Auth::user()->id;
                                    $modelCod->save();
                                }
                            }

                            $model = LoteDetalleDirectos::where('Id_lote', $res->idLote)->where('Liberado', 1)->get();
                            break;
                    }
                    break;
                    case 8: //Potable
                        switch ($lote[0]->Id_tecnica) {
                            case 77: //Dureza
                            case 103:
                            case 251:
                            case 252:
    
                                $muestras = LoteDetalleDureza::where('Id_lote', $res->idLote)->where('Liberado', 0)->get();
                            foreach ($muestras as $item) {
                                $model = LoteDetalleDureza::find($item->Id_detalle);
                                $model->Liberado = 1;
                                if ($model->Resultado != null) {
                                    $sw = true;
                                    $model->save();
                                }
                                if ($item->Id_control == 1) {
                                    $modelCod = CodigoParametros::find($model->Id_codigo);
                                    $modelCod->Resultado = $model->Resultado;
                                    $modelCod->Resultado2 = $model->Resultado;
                                    $modelCod->Analizo = Auth::user()->id;
                                    $modelCod->save();
                                }
                            }

                            $model = LoteDetalleDureza::where('Id_lote', $res->idLote)->where('Liberado', 1)->get();
                                break;
                            default:
    
                                
                                $muestras = LoteDetallePotable::where('Id_lote', $res->idLote)->where('Liberado', 0)->get();
                            foreach ($muestras as $item) {
                                $model = LoteDetallePotable::find($item->Id_detalle);
                                $model->Liberado = 1;
                                if ($model->Resultado != null) {
                                    $sw = true;
                                    $model->save();
                                }
                                if ($item->Id_control == 1) {
                                    $modelCod = CodigoParametros::find($model->Id_codigo);
                                    $modelCod->Resultado = $model->Resultado;
                                    $modelCod->Resultado2 = $model->Resultado;
                                    $modelCod->Analizo = Auth::user()->id;
                                    $modelCod->save();
                                }
                            }

                            $model = LoteDetallePotable::where('Id_lote', $res->idLote)->where('Liberado', 1)->get();

                                break;
                        }
                        break;
                default:
                    $muestras = LoteDetalleDirectos::where('Id_lote', $res->idLote)->where('Liberado', 0)->get();
                    foreach ($muestras as $item) {
                        $model = LoteDetalleDirectos::find($item->Id_detalle);
                        $model->Liberado = 1;
                        if ($model->Resultado != null) {
                            $sw = true;
                            $model->save();
                        }
                        if ($item->Id_control == 1) {
                            $modelCod = CodigoParametros::find($model->Id_codigo);
                            $modelCod->Resultado = $model->Resultado;
                            $modelCod->Resultado2 = $model->Resultado;
                            $modelCod->Analizo = Auth::user()->id;
                            $modelCod->save();
                        }
                    }

                    $model = LoteDetalleDirectos::where('Id_lote', $res->idLote)->where('Liberado', 1)->get();
                    break;
            }
        }

        $loteModel = LoteAnalisis::find($res->idLote);
        $loteModel->Liberado = $model->count();
        $loteModel->save();


        $data = array(
            'model' => $model,
            'sw' => $sw,
        );
        return response()->json($data);
    }
    public function setLiberar(Request $res)
    {
        $lote = LoteAnalisis::where('Id_lote', $res->idLote)->get();
        switch ($lote[0]->Id_area) {
            case 16: // Espectrofotometria
            case 5: // Fisicoquimicos
                switch ($lote[0]->Id_tecnica) {
                    case 0:

                        break;
                    default:
                        $model = LoteDetalleEspectro::find($res->idMuestra);
                        $model->Liberado = 1;
                        if ($model->Resultado != null) {
                            $sw = true;
                            $model->Analizo = Auth::user()->id;
                            $model->save();
                        }

                        if ($model->Id_control == 1) {
                            $modelCod = CodigoParametros::find($model->Id_codigo);
                            $modelCod->Resultado = $model->Resultado;
                            $modelCod->Resultado2 = $model->Resultado;
                            $modelCod->Analizo = Auth::user()->id;
                            $modelCod->save();
                        }

                        $model = LoteDetalleEspectro::where('Id_lote', $res->idLote)->where('Liberado', 1)->get();
                        break;
                }
                break;
            case 13: // G&A
                $model = LoteDetalleGA::find($res->idMuestra);
                $model->Liberado = 1;
                if ($model->Resultado != null) {
                    $sw = true;
                    $model->Analizo = Auth::user()->id;
                    $model->save();
                }
                $modelMatraz = MatrazGA::find($model->Id_matraz);
                $modelMatraz->Estado = 0;
                $modelMatraz->save();

                if ($model->Id_control == 1) {
                    $modelCod = CodigoParametros::find($model->Id_codigo);
                    $modelCod->Resultado = $model->Resultado;
                    $modelCod->Resultado2 = $model->Resultado;
                    $modelCod->Analizo = Auth::user()->id;
                    $modelCod->save();
                }
                $model = LoteDetalleGA::where('Id_lote', $res->idLote)->where('Liberado', 1)->get();
                break;
            case 15: //Solidos
                $model = LoteDetalleSolidos::find($res->idMuestra);
                $model->Liberado = 1;
                if ($model->Resultado != null) {
                    $sw = true;
                    $model->Analizo = Auth::user()->id;
                    $model->save();
                }

                if ($model->Id_control == 1) {
                    $modelCod = CodigoParametros::find($model->Id_codigo);
                    $modelCod->Resultado = $model->Resultado;
                    $modelCod->Resultado2 = $model->Resultado;
                    $modelCod->Analizo = Auth::user()->id;
                    $modelCod->save();
                }

                $model = LoteDetalleSolidos::where('Id_lote', $res->idLote)->where('Liberado', 1)->get();
                break;
            case 14: //Volumetria
                switch ($lote[0]->Id_tecnica) {
                    case 6: // Dqo
                    case 161:
                        $model = LoteDetalleDqo::find($res->idMuestra);
                        $model->Liberado = 1;
                        if ($model->Resultado != null) {
                            $sw = true;
                            $model->Analizo = Auth::user()->id;
                            $model->save();
                        }

                        if ($model->Id_control == 1) {
                            $modelCod = CodigoParametros::find($model->Id_codigo);
                            $modelCod->Resultado = $model->Resultado;
                            $modelCod->Resultado2 = $model->Resultado;
                            $modelCod->Analizo = Auth::user()->id;
                            $modelCod->save();
                        }
                        $model = LoteDetalleDqo::where('Id_lote', $res->idLote)->where('Liberado', 1)->get();
                        break;
                    case 33: // Cloro
                    case 218:
                    case 64:
                        $model = LoteDetalleCloro::find($res->idMuestra);
                        $model->Liberado = 1;
                        if ($model->Resultado != null) {
                            $sw = true;
                            $model->Analizo = Auth::user()->id;
                            $model->save();
                        }

                        if ($model->Id_control == 1) {
                            $modelCod = CodigoParametros::find($model->Id_codigo);
                            $modelCod->Resultado = $model->Resultado;
                            $modelCod->Resultado2 = $model->Resultado;
                            $modelCod->Analizo = Auth::user()->id;
                            $modelCod->save();
                        }

                        $model = LoteDetalleCloro::where('Id_lote', $res->idLote)->where('Liberado', 1)->get();
                        break;
                    case 9: // Nitrogeno
                    case 10:
                    case 11:
                    case 287:
                    case 83:
                    case 108:
                    case 28://Alcalinidad
                        $model = LoteDetalleNitrogeno::find($res->idMuestra);
                        $model->Liberado = 1;
                        if ($model->Resultado != null) {
                            $sw = true;
                            $model->Analizo = Auth::user()->id;
                            $model->save();
                        }
                        if ($model->Id_control == 1) {
                            $modelCod = CodigoParametros::find($model->Id_codigo);
                            $modelCod->Resultado = $model->Resultado;
                            $modelCod->Resultado2 = $model->Resultado;
                            $modelCod->Analizo = Auth::user()->id;
                            $modelCod->save();
                        }

                        $model = LoteDetalleNitrogeno::where('Id_lote', $res->idLote)->where('Liberado', 1)->get();
                        break;
                    default:
                        $model = LoteDetalleDirectos::find($res->idMuestra);
                        $model->Liberado = 1;
                        if ($model->Resultado != null) {
                            $sw = true;
                            $model->Analizo = Auth::user()->id;
                            $model->save();
                        }
                        if ($model->Id_control == 1) {
                            $modelCod = CodigoParametros::find($model->Id_codigo);
                            $modelCod->Resultado = $model->Resultado;
                            $modelCod->Resultado2 = $model->Resultado;
                            $modelCod->Analizo = Auth::user()->id;
                            $modelCod->save();
                        }
                        $model = LoteDetalleDirectos::where('Id_lote', $res->idLote)->where('Liberado', 1)->get();
                        break;
                }
                break;
         
            case 6: //Mb
            case 12:
            case 3:
                switch ($lote[0]->Id_tecnica) {
                    case 135: // Coliformes fecales
                    case 132:
                    case 133:
                    case 12:
                    case 134: // E COLI
                    case 35:
                    case 51: // Coliformes totales
                    case 137:
                        $model = LoteDetalleColiformes::find($res->idMuestra);
                        $model->Liberado = 1;
                        if ($model->Resultado != null) {
                            $sw = true;
                            $model->save();
                        }

                        if ($model->Id_control == 1) {
                            $modelCod = CodigoParametros::find($model->Id_codigo);
                            $modelCod->Resultado = $model->Resultado;
                            $modelCod->Resultado2 = $model->Resultado;
                            $modelCod->Analizo = Auth::user()->id;
                            $modelCod->save();
                        }

                        $model = LoteDetalleColiformes::where('Id_lote', $res->idLote)->where('Liberado', 1)->get();
                        break;
                    case 253: //todo  ENTEROCOCO FECAL
                        $model = LoteDetalleEnterococos::find($res->idMuestra);
                        $model->Liberado = 1;
                        if ($model->Resultado != null) {
                            $sw = true;
                            $model->save();
                        }

                        if ($model->Id_control == 1) {
                            $modelCod = CodigoParametros::find($model->Id_codigo);
                            $modelCod->Resultado = $model->Resultado;
                            $modelCod->Resultado2 = $model->Resultado;
                            $modelCod->Analizo = Auth::user()->id;
                            $modelCod->save();
                        }
                        $model = LoteDetalleEnterococos::where('Id_lote', $res->idLote)->where('Liberado', 1)->get();
                        break;
                    case 5: //todo DEMANDA BIOQUIMICA DE OXIGENO (DBO5) 
                    case 71:
                        $model = LoteDetalleDbo::find($res->idMuestra);
                        $model->Liberado = 1;
                        if ($model->Resultado != null) {
                            $sw = true;
                            $model->save();
                        }

                        if ($model->Id_control == 1) {
                            $modelCod = CodigoParametros::find($model->Id_codigo);
                            $modelCod->Resultado = $model->Resultado;
                            $modelCod->Resultado2 = $model->Resultado;
                            $modelCod->Analizo = Auth::user()->id;
                            $modelCod->save();
                        }

                        $model = LoteDetalleDbo::where('Id_lote', $res->idLote)->where('Liberado', 1)->get();
                        break;
                    case 16: //todo Huevos de Helminto 
                        $model = LoteDetalleHH::find($res->idMuestra);
                        $model->Liberado = 1;
                        if ($model->Resultado != null) {
                            $sw = true;
                            $model->save();
                        }

                        if ($model->Id_control == 1) {
                            $modelCod = CodigoParametros::find($model->Id_codigo);
                            $modelCod->Resultado = $model->Resultado;
                            $modelCod->Resultado2 = $model->Resultado;
                            $modelCod->Analizo = Auth::user()->id;
                            $modelCod->save();
                        }

                        $model = LoteDetalleHH::where('Id_lote', $res->idLote)->where('Liberado', 1)->get();
                        break;

                    case 78:
                        $model = LoteDetalleEcoli::find($res->idMuestra);
                        $model->Liberado = 1;
                        if ($model->Resultado != null) { 
                            $sw = true;
                            $model->save();
                        }
                        if ($model->Id_control == 1) {
                            $modelCod = CodigoParametros::find($model->Id_codigo);
                            $modelCod->Resultado = $model->Resultado;
                            $modelCod->Resultado2 = $model->Resultado;
                            $modelCod->Analizo = Auth::user()->id;
                            $modelCod->save();
                        }
                        $model = LoteDetalleEcoli::where('Id_lote', $res->idLote)->where('Liberado', 1)->get();
                        break;
                        case 70: // inoculko
                            $model = LoteDetalleDboIno::where('Id_detalle' , $res->idMuestra)->first();
                            $model->Liberado = 1;
                            if ($model->Resultado != null) {
                                $sw = true;
                                $model->Analizo = Auth::user()->id;
                                $model->save();
                            }
            
                            if ($model->Id_control == 1) {
                                $modelCod = CodigoParametros::find($model->Id_codigo);
                                $modelCod->Resultado = $model->Resultado;
                                $modelCod->Resultado2 = $model->Resultado;
                                $modelCod->Analizo = Auth::user()->id;
                                $modelCod->save();
                            }
                            $model = LoteDetalleDboIno::where('Id_lote', $res->idLote)->where('Liberado', 1)->get();
                        break;
                    default:
                        $model = LoteDetalleDirectos::find($res->idMuestra);
                        $model->Liberado = 1;
                        if ($model->Resultado != null) {
                            $sw = true;
                            $model->save();
                        }

                        if ($model->Id_control == 1) {
                            $modelCod = CodigoParametros::find($model->Id_codigo);
                            $modelCod->Resultado = $model->Resultado;
                            $modelCod->Resultado2 = $model->Resultado;
                            $modelCod->Analizo = Auth::user()->id;
                            $modelCod->save();
                        }

                        $model = LoteDetalleDirectos::where('Id_lote', $res->idLote)->where('Liberado', 1)->get();
                        break;
                }
                break;
            
            case 7: //Campo
            case 19: //directos
                $model = LoteDetalleDirectos::find($res->idMuestra);
                $model->Liberado = 1;
                if ($model->Resultado != null) {
                    $sw = true;
                    $model->save();
                }

                if ($model->Id_control == 1) {
                    $modelCod = CodigoParametros::find($model->Id_codigo);
                    $modelCod->Resultado = $model->Resultado;
                    $modelCod->Resultado2 = $model->Resultado;
                    $modelCod->Analizo = Auth::user()->id;
                    $modelCod->save();
                }
                $model = LoteDetalleDirectos::where('Id_lote', $res->idLote)->where('Liberado', 1)->get();
                break;
            default:
                $model = LoteDetalleDirectos::find($res->idMuestra);
                $model->Liberado = 1;
                if ($model->Resultado != null) {
                    $sw = true;
                    $model->save();
                }

                if ($model->Id_control == 1) {
                    $modelCod = CodigoParametros::find($model->Id_codigo);
                    $modelCod->Resultado = $model->Resultado;
                    $modelCod->Resultado2 = $model->Resultado;
                    $modelCod->Analizo = Auth::user()->id;
                    $modelCod->save();
                }

                $model = LoteDetalleDirectos::where('Id_lote', $res->idLote)->where('Liberado', 1)->get();
                break;
        }

        $loteModel = LoteAnalisis::find($res->idLote);
        $loteModel->Liberado = $model->count();
        $loteModel->save();

        $data = array(
            'model' => $model,
            'sw' => $sw,
           // 'muestra' => $res->idMuestra
        );
        return response()->json($data);
    }
    public function setObservacion(Request $res)
    {
        $lote = LoteAnalisis::where('Id_lote', $res->idLote)->get();
        switch ($lote[0]->Id_area) {
            case 16: // Espectrofotometria 
            case 5: // Fisicoquimicos
                switch ($lote[0]->Id_tecnica) {
                    case 0:
                        break;
                    default:
                        $model = LoteDetalleEspectro::where('Id_detalle', $res->idMuestra)->first();
                        $model->Observacion = $res->observacion;
                        $model->save();
                        break;
                }
                break;
            case 13: //G&A
                $model = LoteDetalleGA::where('Id_detalle', $res->idMuestra)->first();
                $model->Observacion = $res->observacion;
                $model->save();
                break;
            case 15: //Solidos
                $model = LoteDetalleSolidos::where('Id_detalle', $res->idMuestra)->first();
                $model->Observacion = $res->observacion;
                $model->save();
                break;
            case 14: //Volumetria
                switch ($lote[0]->Id_tecnica) {
                    case 218: // Cloro
                    case 33:
                    case 64:
                        $model = LoteDetalleCloro::where('Id_detalle', $res->idMuestra)->first();
                        $model->Observacion = $res->observacion;
                        $model->save();
                        break;
                    case 6: // Dqo
                    case 161:
                        $model = LoteDetalleDqo::where('Id_detalle', $res->idMuestra)->first();
                        $model->Observacion = $res->observacion;
                        $model->save();
                        break;
                    case 9: // Nitrogeno
                    case 287:
                    case 10:
                    case 11:
                    case 108: // Nitrogeno Amon
                    case 28://Alcalinidad
                        $model = LoteDetalleNitrogeno::where('Id_detalle', $res->idMuestra)->first();
                        $model->Observacion = $res->observacion;
                        $model->save();
                        break;
                    default: // Default Directos
                        $model = LoteDetalleDirectos::where('Id_detalle', $res->idMuestra)->first();
                        $model->Observacion = $res->observacion;
                        $model->save();
                        break;
                }
                break;
            case 7: //Campo
            case 19: //Directos
                $model = LoteDetalleDirectos::where('Id_detalle', $res->idMuestra)->first();
                $model->Observacion = $res->observacion;
                $model->save();
                break;
            case 6: //Mb
            case 12:
                switch ($lote[0]->Id_tecnica) {
                    case 135: // Coliformes fecales
                    case 132:
                    case 133:
                    case 12:
                    case 134: // E COLI
                    case 35:
                    case 51: // Coliformes totales
                    case 137:
                        $model = LoteDetalleColiformes::where('Id_detalle', $res->idMuestra)->first();
                        $model->Observacion = $res->observacion;
                        $model->save();
                        break;
                    case 253: //todo  ENTEROCOCO FECAL
                        $model = LoteDetalleEnterococos::where('Id_detalle', $res->idMuestra)->first();
                        $model->Observacion = $res->observacion;
                        $model->save();
                        break;
                    case 5: //todo DEMANDA BIOQUIMICA DE OXIGENO (DBO5) 
                    case 71:
                        $model = LoteDetalleDbo::where('Id_detalle', $res->idMuestra)->first();
                        $model->Observacion = $res->observacion;
                        $model->save();
                        break;
                    case 70:
                        $model = LoteDetalleDboIno::where('Id_detalle', $res->idMuestra)->first();
                        $model->Observacion = $res->observacion;
                        $model->save();
                        break;
                    case 16: //todo Huevos de Helminto 
                        $model = LoteDetalleHH::where('Id_detalle', $res->idMuestra)->first();
                        $model->Observacion = $res->observacion;
                        $model->save();
                        break;
                    case 78:
                        $model = LoteDetalleEcoli::where('Id_detalle', $res->idMuestra)->first();
                        $model->Observacion = $res->observacion;
                        $model->save();
                        break;
                    default:
                        $model = LoteDetalleDirectos::where('Id_detalle', $res->idMuestra)->first();
                        $model->Observacion = $res->observacion;
                        $model->save();
                        break;
                }
                break;
                case 8: //Potable
                    switch ($lote[0]->Id_tecnica) {
                        case 77: //Dureza
                        case 103:
                        case 251:
                        case 252:
                            $model = LoteDetalleDureza::where('Id_detalle', $res->idMuestra)->first();
                            $model->Observacion = $res->observacion;
                            $model->save();
                            break;
                        default:
                            $model = LoteDetallePotable::where('Id_detalle', $res->idMuestra)->first();
                            $model->Observacion = $res->observacion;
                            $model->save();
                            break;
                    }
                    break;
                default:
                $model = LoteDetalleDirectos::where('Id_detalle', $res->idMuestra)->first();
                $model->Observacion = $res->observacion;
                $model->save();
                break;
        }
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function setBitacora(Request $res)
    {
        $lote = DB::table('ViewLoteAnalisis')->where('Id_lote', $res->id)->first();
        $temp = Bitacoras::where('Id_lote', $res->id)->get();
        if ($temp->count()) {
            $model = Bitacoras::where('Id_lote', $res->id)->first();
            $model->Titulo = $res->titulo;
            $model->Texto = $res->texto;
            $model->Rev = $res->rev;
            $model->save();
        } else {
            $model = Bitacoras::create([
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
    public function exportBitacora($id)
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
        //Establece la marca de agua del documento PDF
        $mpdf->SetWatermarkImage(
            asset('/public/storage/MembreteVertical.png'),
            1,
            array(215, 280),
            array(0, 0),
        );

        $mpdf->showWatermarkImage = true;
        $mpdf->CSSselectMedia = 'mpdf';

        $lote = DB::table('ViewLoteAnalisis')->where('Id_lote', $id)->first();
        // echo $lote->Id_area;
        switch ($lote->Id_area) {
            case 16: // Espectrofotometria
            case 5: // Fisicoquimicos
                $model = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $id)->get();
                $plantilla = Bitacoras::where('Id_lote', $id)->get();
                if ($plantilla->count()) {
                } else {
                    $plantilla = PlantillaBitacora::where('Id_parametro', $lote->Id_tecnica)->get();
                }
                $procedimiento = explode("NUEVASECCION", $plantilla[0]->Texto);
                $curva = CurvaConstantes::where('Id_parametro', $lote->Id_tecnica)->where('Fecha_inicio', '<=', $lote->Fecha)->where('Fecha_fin', '>=', $lote->Fecha)->first();
                //Comprobación de bitacora analizada
                $comprobacion = LoteDetalleEspectro::where('Liberado', 0)->where('Id_lote', $id)->get();
                if ($comprobacion->count()) {
                    $analizo = "";
                } else {
                    @$analizo = User::where('id', $model[0]->Analizo)->first();
                }
                $reviso = User::where('id', 17)->first();
                $data = array(
                    'lote' => $lote,
                    'model' => $model,
                    'curva' => $curva, 
                    'plantilla' => $plantilla,
                    'procedimiento' => $procedimiento,
                    'analizo' => $analizo,
                    'reviso' => $reviso,
                    'comprobacion' => $comprobacion,
                );

                switch ($lote->Id_tecnica) {
                    case 152: // COT
                        $htmlFooter = view('exports.laboratorio.fq.espectro.cot.capturaFooter', $data);
                        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                        $htmlHeader = view('exports.laboratorio.fq.espectro.cot.capturaHeader', $data);
                        $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                        $htmlCaptura = view('exports.laboratorio.fq.espectro.cot.capturaBody', $data);
                        $mpdf->CSSselectMedia = 'mpdf';
                        $mpdf->WriteHTML($htmlCaptura);
                        break;
                    case 103: // Dureza potable
                        
                        // $htmlFooter = view('exports.laboratorio.fq.espectro.nitratos.capturaFooter', $data);
                        $htmlHeader = view('exports.laboratorio.potable.durezaTotal.127.bitacoraHeader', $data);
                        $htmlCaptura = view('exports.laboratorio.potable.durezaTotal.127.bitacoraBody', $data);
                        // $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                        $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                        $mpdf->CSSselectMedia = 'mpdf';
                        $mpdf->WriteHTML($htmlCaptura);
                        break;
                    case 7: //Nitratos residual
                    case 122:
                        $htmlFooter = view('exports.laboratorio.fq.espectro.nitratos.capturaFooter', $data);
                        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                        $htmlHeader = view('exports.laboratorio.fq.espectro.nitratos.capturaHeader', $data);
                        $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                        $htmlCaptura = view('exports.laboratorio.fq.espectro.nitratos.capturaBody', $data);
                        $mpdf->CSSselectMedia = 'mpdf';
                        $mpdf->WriteHTML($htmlCaptura);
                        break;
                    case 8: //Nitritos residual
                    case 123:
                        $htmlFooter = view('exports.laboratorio.fq.espectro.nitritos.capturaFooter', $data);
                        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                        $htmlHeader = view('exports.laboratorio.fq.espectro.nitritos.capturaHeader', $data);
                        $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                        $htmlCaptura = view('exports.laboratorio.fq.espectro.nitritos.capturaBody', $data);
                        $mpdf->CSSselectMedia = 'mpdf';
                        $mpdf->WriteHTML($htmlCaptura);
                        break;
                    case 107: // Nitritos Potable
                        $htmlFooter = view('exports.laboratorio.fq.espectro.nitritos.127.capturaFooter', $data);
                        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                        $htmlHeader = view('exports.laboratorio.fq.espectro.nitritos.127.capturaHeader', $data);
                        $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                        $htmlCaptura = view('exports.laboratorio.fq.espectro.nitritos.127.capturaBody', $data);
                        $mpdf->CSSselectMedia = 'mpdf';
                        $mpdf->WriteHTML($htmlCaptura);
                        break;
                    case 106: // Nitritos Potable
                        $htmlFooter = view('exports.laboratorio.fq.espectro.nitratos.127.capturaFooter', $data);
                        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                        $htmlHeader = view('exports.laboratorio.fq.espectro.nitratos.127.capturaHeader', $data);
                        $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                        $htmlCaptura = view('exports.laboratorio.fq.espectro.nitratos.127.capturaBody', $data);
                        $mpdf->CSSselectMedia = 'mpdf';
                        $mpdf->WriteHTML($htmlCaptura);
                        break;
                    case 99: //cianuros
                    case 118:
                        $htmlFooter = view('exports.laboratorio.fq.espectro.cianuros.127.capturaFooter', $data);
                        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                        $htmlHeader = view('exports.laboratorio.fq.espectro.cianuros.127.capturaHeader', $data);
                        $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                        $htmlCaptura = view('exports.laboratorio.fq.espectro.cianuros.127.capturaBody', $data);
                        $mpdf->CSSselectMedia = 'mpdf';
                        $mpdf->WriteHTML($htmlCaptura);
                        break;
                    case 95: // Sulfatos
                        $htmlFooter = view('exports.laboratorio.fq.espectro.sulfatos.127.capturaFooter', $data);
                        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                        $htmlHeader = view('exports.laboratorio.fq.espectro.sulfatos.127.capturaHeader', $data);
                        $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                        $htmlCaptura = view('exports.laboratorio.fq.espectro.sulfatos.127.capturaBody', $data);
                        $mpdf->CSSselectMedia = 'mpdf';
                        $mpdf->WriteHTML($htmlCaptura);
                        break;
                    case 105: // Fluoruros
                    case 121:
                        $htmlFooter = view('exports.laboratorio.fq.espectro.fluoruros.127.capturaFooter', $data);
                        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                        $htmlHeader = view('exports.laboratorio.fq.espectro.fluoruros.127.capturaHeader', $data);
                        $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                        $htmlCaptura = view('exports.laboratorio.fq.espectro.fluoruros.127.capturaBody', $data);
                        $mpdf->CSSselectMedia = 'mpdf';
                        $mpdf->WriteHTML($htmlCaptura);
                        break;
                    case 96: // SAAM 
                    case 114:
                    case 124:
                        $htmlFooter = view('exports.laboratorio.fq.espectro.saam.127.capturaFooter', $data);
                        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                        $htmlHeader = view('exports.laboratorio.fq.espectro.saam.127.capturaHeader', $data);
                        $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                        $htmlCaptura = view('exports.laboratorio.fq.espectro.saam.127.capturaBody', $data);
                        $mpdf->CSSselectMedia = 'mpdf';
                        $mpdf->WriteHTML($htmlCaptura);
                        break;
                    case 116: // Yodo
                        $htmlFooter = view('exports.laboratorio.fq.espectro.yodo.127.capturaFooter', $data);
                        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                        $htmlHeader = view('exports.laboratorio.fq.espectro.yodo.127.capturaHeader', $data);
                        $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                        $htmlCaptura = view('exports.laboratorio.fq.espectro.yodo.127.capturaBody', $data);
                        $mpdf->CSSselectMedia = 'mpdf';
                        $mpdf->WriteHTML($htmlCaptura);
                        break;
                    case 69: //Cromo Hexa
                        $htmlFooter = view('exports.laboratorio.fq.espectro.cromoHex.capturaFooter', $data);
                        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                        $htmlHeader = view('exports.laboratorio.fq.espectro.cromoHex.capturaHeader', $data);
                        $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                        $htmlCaptura = view('exports.laboratorio.fq.espectro.cromoHex.capturaBody', $data);
                        $mpdf->CSSselectMedia = 'mpdf';
                        $mpdf->WriteHTML($htmlCaptura);
                        break;
                    case 19: // Cianuros
                        $htmlFooter = view('exports.laboratorio.fq.espectro.cianuros.capturaFooter', $data);
                        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                        $htmlHeader = view('exports.laboratorio.fq.espectro.cianuros.capturaHeader', $data);
                        $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                        $htmlCaptura = view('exports.laboratorio.fq.espectro.cianuros.capturaBody', $data);
                        $mpdf->CSSselectMedia = 'mpdf';
                        $mpdf->WriteHTML($htmlCaptura);
                        break;
                    case 15: // Fosforo
                        $htmlFooter = view('exports.laboratorio.fq.espectro.fosforoTotal.capturaFooter', $data);
                        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                        $htmlHeader = view('exports.laboratorio.fq.espectro.fosforoTotal.capturaHeader', $data);
                        $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                        $htmlCaptura = view('exports.laboratorio.fq.espectro.fosforoTotal.capturaBody', $data);
                        $mpdf->CSSselectMedia = 'mpdf';
                        $mpdf->WriteHTML($htmlCaptura);
                        break;
                    case 222: // Boro
                    case 117:
                        $htmlFooter = view('exports.laboratorio.fq.espectro.boro.capturaFooter', $data);
                        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                        $htmlHeader = view('exports.laboratorio.fq.espectro.boro.capturaHeader', $data);
                        $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                        $htmlCaptura = view('exports.laboratorio.fq.espectro.boro.capturaBody', $data);
                        $mpdf->CSSselectMedia = 'mpdf';
                        $mpdf->WriteHTML($htmlCaptura);
                        break;
                    case 79:
                        $htmlFooter = view('exports.laboratorio.fq.espectro.fenoles.capturaFooter', $data);
                        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                        $htmlHeader = view('exports.laboratorio.fq.espectro.fenoles.capturaHeader', $data);
                        $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                        $htmlCaptura = view('exports.laboratorio.fq.espectro.fenoles.capturaBody', $data);
                        $mpdf->CSSselectMedia = 'mpdf';
                        $mpdf->WriteHTML($htmlCaptura);
                        break;
                    case 108: //Nitrogeno Amoniacal
                        // echo "Entra a funcion";
                        $mpdf = new \Mpdf\Mpdf([
                            'orientation' => 'P',
                            'format' => 'letter',
                            'margin_left' => 10,
                            'margin_right' => 10,
                            'margin_top' => 35,
                            'margin_bottom' => 45,
                            'defaultheaderfontstyle' => ['normal'],
                            'defaultheaderline' => '0'
                        ]);
                        //Establece la marca de agua del documento PDF
                        $mpdf->SetWatermarkImage(
                            asset('/public/storage/MembreteVertical.png'),
                            1,
                            array(215, 280),
                            array(0, 0),
                        );
                        $mpdf->showWatermarkImage = true;
                        $mpdf->CSSselectMedia = 'mpdf';

                        $loteDetalle = DB::table('ViewLoteDetalleNitrogeno')->where('Id_lote', $id)->get();
                        $valNitrogenoA = ValoracionNitrogeno::where('Id_lote', $id)->first();
                        $plantilla = Bitacoras::where('Id_lote', $id)->get();
                        if ($plantilla->count()) {
                        } else {
                            $plantilla = PlantillaBitacora::where('Id_parametro', $lote->Id_tecnica)->get();
                        }
                        $procedimiento = explode("NUEVASECCION", $plantilla[0]->Texto);
                        // var_dump($procedimiento[0]);
                        $comprobacion = LoteDetalleEspectro::where('Liberado', 0)->where('Id_lote', $id)->get();
                        if ($comprobacion->count()) {
                            $analizo = "";
                        } else {
                            $analizo = User::where('id', $loteDetalle[0]->Analizo)->first();
                        }
                        $reviso = User::where('id', 17)->first();
                        $data = array(
                            'comprobacion' => $comprobacion,
                            'analizo' => $analizo,
                            'reviso' => $reviso,
                            'lote' => $lote,
                            'loteDetalle' => $loteDetalle,
                            'plantilla' => $plantilla,
                            'valNitrogenoA' => $valNitrogenoA,
                            'procedimiento' => $procedimiento,
                        );

                        $htmlFooter = view('exports.laboratorio.volumetria.nitrogenoA.capturaFooter', $data);
                        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                        $htmlCaptura = view('exports.laboratorio.volumetria.nitrogenoA.capturaBody', $data);
                        $htmlHeader = view('exports.laboratorio.volumetria.nitrogenoA.capturaHeader', $data);

                        $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                        $mpdf->SetHTMLFooter("", 'O', 'E');
                        $mpdf->WriteHTML($htmlCaptura);
                        break;
                    case 112:
                        $model = DB::table('ViewLoteDetalleSolidos')->where('Id_lote', $id)->get();
                        $plantilla = Bitacoras::where('Id_lote', $id)->get();
                        if ($plantilla->count()) {
                        } else {
                            $plantilla = PlantillaBitacora::where('Id_parametro', $lote->Id_tecnica)->get();
                        }
                        $procedimiento = explode("NUEVASECCION", $plantilla[0]->Texto);
                        //Comprobación de bitacora analizada
                        $comprobacion = LoteDetalleSolidos::where('Liberado', 0)->where('Id_lote', $id)->get();
                        if ($comprobacion->count()) {
                            $analizo = "";
                        } else {
                            $analizo = User::where('id', $model[0]->Analizo)->first();
                        }
                        $reviso = User::where('id', 14)->first();
                        $data = array(
                            'lote' => $lote,
                            'model' => $model,
                            'plantilla' => $plantilla,
                            'procedimiento' => $procedimiento,
                            'analizo' => $analizo,
                            'reviso' => $reviso,
                            'comprobacion' => $comprobacion,
                        );

                        $htmlFooter = view('exports.laboratorio.fq.ga.sdt.capturaFooter', $data);
                        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                        $htmlHeader = view('exports.laboratorio.fq.ga.sdt.capturaHeader', $data);
                        $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                        $htmlCaptura = view('exports.laboratorio.fq.ga.sdt.capturaBody', $data);
                        $mpdf->CSSselectMedia = 'mpdf';
                        $mpdf->WriteHTML($htmlCaptura);
                        break;
                    default:


                        break;
                }
                break;
            case 13: // G&A
                $model = DB::table('ViewLoteDetalleGA')->where('Id_lote', $id)->get();
                $plantilla = Bitacoras::where('Id_lote', $id)->get();
                if ($plantilla->count()) {
                } else {
                    $plantilla = PlantillaBitacora::where('Id_parametro', $lote->Id_tecnica)->get();
                }
                $procedimiento = explode("NUEVASECCION", $plantilla[0]->Texto);
                $curva = CurvaConstantes::where('Id_parametro', $lote->Id_tecnica)->where('Fecha_inicio', '<=', $lote->Fecha)->where('Fecha_fin', '>=', $lote->Fecha)->first();
                //Comprobación de bitacora analizada
                $comprobacion = LoteDetalleGA::where('Liberado', 0)->where('Id_lote', $id)->get();
                if ($comprobacion->count()) {
                    $analizo = "";
                } else {
                    $analizo = User::where('id', $model[0]->Analizo)->first();
                }
                $reviso = User::where('id', 17)->first();

                $matraz = DB::table('ViewMatrazConMuestra')->where('Id_lote', $id)->get();
                $detalle = GrasasDetalle::where('Id_lote', $id)->first();

                $modelConControl = DB::table('ViewLoteDetalleGA')->where('Id_lote', $id)->where('Id_control', '!=', 1)->get();
                $modelSinControl = DB::table('ViewLoteDetalleGA')->where('Id_lote', $id)->where('Id_control', 1)->get();
                $data = array(
                    'modelConControl' => $modelConControl,
                    'modelSinControl' => $modelSinControl,
                    'detalle' => $detalle,
                    'matraz' => $matraz,
                    'lote' => $lote,
                    'model' => $model,
                    'curva' => $curva,
                    'plantilla' => $plantilla,
                    'procedimiento' => $procedimiento,
                    'analizo' => $analizo,
                    'reviso' => $reviso,
                    'comprobacion' => $comprobacion,
                );
                $htmlFooter = view('exports.laboratorio.fq.ga.ga.capturaFooter', $data);
                $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                $htmlHeader = view('exports.laboratorio.fq.ga.ga.capturaHeader', $data);
                $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                $htmlCaptura = view('exports.laboratorio.fq.ga.ga.capturaBody', $data);
                $mpdf->CSSselectMedia = 'mpdf';
                $mpdf->WriteHTML($htmlCaptura);
                break;
            case 15: // Solidos
                switch ($lote->Id_tecnica) {
                    case 4: // SST
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
                        //Establece la marca de agua del documento PDF
                        $mpdf->SetWatermarkImage(
                            asset('/public/storage/MembreteVertical.png'),
                            1,
                            array(215, 280),
                            array(0, 0),
                        );

                        $mpdf->showWatermarkImage = true;
                        $mpdf->CSSselectMedia = 'mpdf';

                        $model = DB::table('ViewLoteDetalleSolidos')->where('Id_lote', $id)->get();
                        $plantilla = Bitacoras::where('Id_lote', $id)->get();
                        if ($plantilla->count()) {
                        } else {
                            $plantilla = PlantillaBitacora::where('Id_parametro', $lote->Id_tecnica)->get();
                        }
                        $procedimiento = explode("NUEVASECCION", $plantilla[0]->Texto);
                        //Comprobación de bitacora analizada
                        $comprobacion = LoteDetalleSolidos::where('Liberado', 0)->where('Id_lote', $id)->get();
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
                            'procedimiento' => $procedimiento,
                            'analizo' => $analizo,
                            'reviso' => $reviso,
                            'comprobacion' => $comprobacion,
                        );

                        $htmlFooter = view('exports.laboratorio.fq.ga.sst.capturaFooter', $data);
                        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                        $htmlHeader = view('exports.laboratorio.fq.ga.sst.capturaHeader', $data);
                        $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                        $htmlCaptura = view('exports.laboratorio.fq.ga.sst.capturaBody', $data);
                        $mpdf->CSSselectMedia = 'mpdf';
                        $mpdf->WriteHTML($htmlCaptura);
                        break;
                    case 112:
                        $model = DB::table('ViewLoteDetalleSolidos')->where('Id_lote', $id)->get();
                        $plantilla = Bitacoras::where('Id_lote', $id)->get();
                        if ($plantilla->count()) {
                        } else {
                            $plantilla = PlantillaBitacora::where('Id_parametro', $lote->Id_tecnica)->get();
                        }
                        $procedimiento = explode("NUEVASECCION", $plantilla[0]->Texto);
                        //Comprobación de bitacora analizada
                        $comprobacion = LoteDetalleSolidos::where('Liberado', 0)->where('Id_lote', $id)->get();
                        if ($comprobacion->count()) {
                            $analizo = "";
                        } else {
                            $analizo = User::where('id', $model[0]->Analizo)->first();
                        }
                        $reviso = User::where('id', 14)->first();
                        $data = array(
                            'lote' => $lote,
                            'model' => $model,
                            'plantilla' => $plantilla,
                            'procedimiento' => $procedimiento,
                            'analizo' => $analizo,
                            'reviso' => $reviso,
                            'comprobacion' => $comprobacion,
                        );

                        $htmlFooter = view('exports.laboratorio.fq.ga.sdt.capturaFooter', $data);
                        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                        $htmlHeader = view('exports.laboratorio.fq.ga.sdt.capturaHeader', $data);
                        $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                        $htmlCaptura = view('exports.laboratorio.fq.ga.sdt.capturaBody', $data);
                        $mpdf->CSSselectMedia = 'mpdf';
                        $mpdf->WriteHTML($htmlCaptura);
                        break;
                    case 3:
                        $model = DB::table('ViewLoteDetalleSolidos')->where('Id_lote', $id)->get();
                        $plantilla = Bitacoras::where('Id_lote', $id)->get();
                        if ($plantilla->count()) {
                        } else {
                            $plantilla = PlantillaBitacora::where('Id_parametro', $lote->Id_tecnica)->get();
                        }
                        $procedimiento = explode("NUEVASECCION", $plantilla[0]->Texto);
                        //Comprobación de bitacora analizada
                        $comprobacion = LoteDetalleSolidos::where('Liberado', 0)->where('Id_lote', $id)->get();
                        if ($comprobacion->count()) {
                            $analizo = "";
                        } else {
                            @$analizo = User::where('id', $model[0]->Analizo)->first();
                        }
                        $reviso = User::where('id', 17)->first();
                        $data = array(
                            'lote' => $lote,
                            'model' => $model,
                            'plantilla' => $plantilla,
                            'procedimiento' => $procedimiento,
                            'analizo' => $analizo,
                            'reviso' => $reviso,
                            'comprobacion' => $comprobacion,
                        );

                        $htmlFooter = view('exports.laboratorio.fq.ga.ss.capturaFooter', $data);
                        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                        $htmlHeader = view('exports.laboratorio.fq.ga.ss.capturaHeader', $data);
                        $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                        $htmlCaptura = view('exports.laboratorio.fq.ga.ss.capturaBody', $data);
                        $mpdf->CSSselectMedia = 'mpdf';
                        $mpdf->WriteHTML($htmlCaptura);
                        break;
                }
                break;
            case 14: // Volumetria
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
                //Establece la marca de agua del documento PDF
                $mpdf->SetWatermarkImage(
                    asset('/public/storage/MembreteVertical.png'),
                    1,
                    array(215, 280),
                    array(0, 0),
                );
                $mpdf->showWatermarkImage = true;
                switch ($lote->Id_tecnica) {
                        //bitacora dqo
                    case 6:
                    case 161:
                        $dqoDetalle = DB::table('dqo_detalle')->where('Id_lote', $id)->get();
                        $loteDetalle = DB::table('ViewLoteDetalleDqo')->where('Id_lote', $id)->get();
                        switch ($dqoDetalle[0]->Tipo) {
                            case 1: // Dqo Alta
                                $valDqo = ValoracionDqo::where('Id_lote', $id)->first();
                                $plantilla = Bitacoras::where('Id_lote', $id)->get();
                                if ($plantilla->count()) {
                                    // if ($loteDetalle[0]->Soluble == 1) {
                                    //     $plantilla = PlantillaBitacora::where('Id_parametro', 159)->get();
                                    // } else {
                                    //     $plantilla = PlantillaBitacora::where('Id_parametro', 72)->get();
                                    // } 
                                } else {
                                    if ($loteDetalle[0]->Soluble == 1) {
                                        $plantilla = PlantillaBitacora::where('Id_parametro', 159)->get();
                                    } else {
                                        $plantilla = PlantillaBitacora::where('Id_parametro', 72)->get();
                                    }
                                }
                                $procedimiento = explode("NUEVASECCION", $plantilla[0]->Texto);

                                $comprobacion = LoteDetalleDqo::where('Liberado', 0)->where('Id_lote', $id)->get();
                                if ($comprobacion->count()) {
                                    $analizo = "";
                                } else {
                                    $analizo = User::where('id', $loteDetalle[0]->Analizo)->first();
                                }
                                $reviso = User::where('id', 17)->first();
                                $data = array(
                                    'analizo' => $analizo,
                                    'procedimiento' => $procedimiento,
                                    'comprobacion' => $comprobacion, 
                                    'reviso' => $reviso,
                                    'lote' => $lote,
                                    'loteDetalle' => $loteDetalle,
                                    'valDqo' => $valDqo,
                                    'plantilla' => $plantilla,
                                    'procedimiento' => $procedimiento,
                                );
                                $htmlFooter = view('exports.laboratorio.volumetria.dqo.dqoReflujo.bitacoraFooter', $data);
                                $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                                $htmlHeader = view('exports.laboratorio.volumetria.dqo.dqoReflujo.bitacoraHeader', $data);
                                $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                                $htmlCaptura = view('exports.laboratorio.volumetria.dqo.dqoReflujo.bitacoraBody', $data);
                                $mpdf->CSSselectMedia = 'mpdf';
                                $mpdf->WriteHTML($htmlCaptura);
                                break;
                            case 2:
                                $valDqo = ValoracionDqo::where('Id_lote', $id)->first();
                                $plantilla = Bitacoras::where('Id_lote', $id)->get();
                                if ($plantilla->count()) {
                                    // if ($loteDetalle[0]->Soluble == 1) {
                                    //     $plantilla = PlantillaBitacora::where('Id_parametro', 160)->get();
                                    // } else {
                                    //     $plantilla = PlantillaBitacora::where('Id_parametro', 73)->get();
                                    // }
                                } else {
                                    if ($loteDetalle[0]->Soluble == 1) {
                                        $plantilla = PlantillaBitacora::where('Id_parametro', 160)->get();
                                    } else {
                                        $plantilla = PlantillaBitacora::where('Id_parametro', 73)->get();
                                    }
                                }
                                $procedimiento = explode("NUEVASECCION", $plantilla[0]->Texto);

                                $comprobacion = LoteDetalleDqo::where('Liberado', 0)->where('Id_lote', $id)->get();
                                if ($comprobacion->count()) {
                                    $analizo = "";
                                } else {
                                    $analizo = User::where('id', $loteDetalle[0]->Analizo)->first();
                                }
                                $reviso = User::where('id', 17)->first();
                                $data = array(
                                    'analizo' => $analizo,
                                    'procedimiento' => $procedimiento,
                                    'comprobacion' => $comprobacion,
                                    'reviso' => $reviso,
                                    'lote' => $lote,
                                    'loteDetalle' => $loteDetalle,
                                    'valDqo' => $valDqo,
                                    'plantilla' => $plantilla,
                                    'procedimiento' => $procedimiento,
                                );
                                $htmlFooter = view('exports.laboratorio.volumetria.dqo.dqoReflujo.bitacoraFooter', $data);
                                $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                                $htmlHeader = view('exports.laboratorio.volumetria.dqo.dqoReflujo.bitacoraHeader', $data);
                                $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                                $htmlCaptura = view('exports.laboratorio.volumetria.dqo.dqoReflujo.bitacoraBody', $data);
                                $mpdf->CSSselectMedia = 'mpdf';
                                $mpdf->WriteHTML($htmlCaptura);
                                break;
                        }
                        break;
                    case 64: //Cloruros Totales
                        $loteDetalle = DB::table('ViewLoteDetalleCloro')->where('Id_lote', $id)->get();
                        $plantilla = Bitacoras::where('Id_lote', $id)->get();
                        if ($plantilla->count()) {
                        } else {
                            $plantilla = PlantillaBitacora::where('Id_parametro', $lote->Id_tecnica)->get();
                        }
                        $procedimiento = explode("NUEVASECCION", $plantilla[0]->Texto);
                        $valoracion = ValoracionCloro::where('Id_lote', $id)->first();
                        $comprobacion = LoteDetalleCloro::where('Liberado', 0)->where('Id_lote', $id)->get();
                        if ($comprobacion->count()) {
                            $analizo = "";
                        } else {
                            $analizo = User::where('id', $loteDetalle[0]->Analizo)->first();
                        }
                        $reviso = User::where('id', 17)->first();
                        $data = array(
                            'lote' => $lote,
                            'valoracion' => $valoracion,
                            'loteDetalle' => $loteDetalle,
                            'plantilla' => $plantilla,
                            'procedimiento' => $procedimiento,
                            'comprobacion' => $comprobacion,
                            'analizo' => $analizo,
                            'reviso' => $reviso,
                        );
                        $htmlFooter = view('exports.laboratorio.volumetria.cloruros.capturaFooter', $data);
                        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                        $htmlHeader = view('exports.laboratorio.volumetria.cloruros.capturaHeader', $data);
                        $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                        $htmlCaptura = view('exports.laboratorio.volumetria.cloruros.capturaBody', $data);
                        $mpdf->WriteHTML($htmlCaptura);
                        $mpdf->CSSselectMedia = 'mpdf';
                        break;
                    case 33:
                    case 218:
                    case 119:
                    case 64:
                        $loteDetalle = DB::table('ViewLoteDetalleCloro')->where('Id_lote', $id)->get();
                        $plantilla = Bitacoras::where('Id_lote', $id)->get();
                        if ($plantilla->count()) {
                        } else {
                            $plantilla = PlantillaBitacora::where('Id_parametro', $lote->Id_tecnica)->get();
                        }
                        $procedimiento = explode("NUEVASECCION", $plantilla[0]->Texto);
                        $valoracion = ValoracionCloro::where('Id_lote', $id)->first();
                        $comprobacion = LoteDetalleCloro::where('Liberado', 0)->where('Id_lote', $id)->get();
                        if ($comprobacion->count()) {
                            $analizo = "";
                        } else {
                            $analizo = User::where('id', $loteDetalle[0]->Analizo)->first();
                        }
                        $reviso = User::where('id', 17)->first();
                        $data = array(
                            'lote' => $lote,
                            'valoracion' => $valoracion,
                            'loteDetalle' => $loteDetalle,
                            'plantilla' => $plantilla,
                            'procedimiento' => $procedimiento,
                            'comprobacion' => $comprobacion,
                            'analizo' => $analizo,
                            'reviso' => $reviso,
                        );
                        $htmlCaptura = view('exports.laboratorio.volumetria.cloro.capturaBody', $data);
                        $htmlHeader = view('exports.laboratorio.volumetria.cloro.capturaHeader', $data);

                        $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                        $mpdf->SetHTMLFooter("", 'O', 'E');
                        $mpdf->WriteHTML($htmlCaptura);
                        $mpdf->CSSselectMedia = 'mpdf';
                        break;
                    case 287:
                    case 9: // Nitrogeno amoniacal
                        $loteDetalle = DB::table('ViewLoteDetalleNitrogeno')->where('Id_lote', $id)->get();
                        $valNitrogenoA = ValoracionNitrogeno::where('Id_lote', $id)->first();
                        $plantilla = Bitacoras::where('Id_lote', $id)->get();
                        if ($plantilla->count()) {
                        } else {
                            $plantilla = PlantillaBitacora::where('Id_parametro', $lote->Id_tecnica)->get();
                        }
                        $procedimiento = explode("NUEVASECCION", $plantilla[0]->Texto);
                        $valoracion = ValoracionCloro::where('Id_lote', $id)->first();

                        $comprobacion = LoteDetalleNitrogeno::where('Liberado', 0)->where('Id_lote', $id)->get();
                        if ($comprobacion->count()) {
                            $analizo = "";
                        } else {
                            $analizo = User::where('id', $loteDetalle[0]->Analizo)->first();
                        }
                        $reviso = User::where('id', 17)->first();
                        $data = array(
                            'analizo' => $analizo,
                            'procedimiento' => $procedimiento,
                            'comprobacion' => $comprobacion,
                            'reviso' => $reviso,
                            'lote' => $lote,
                            'loteDetalle' => $loteDetalle,
                            'valNitrogenoA' => $valNitrogenoA,
                            'plantilla' => $plantilla,
                            'procedimiento' => $procedimiento,
                        );
                        $htmlFooter = view('exports.laboratorio.volumetria.nitrogenoA.capturaFooter', $data);
                        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                        $htmlHeader = view('exports.laboratorio.volumetria.nitrogenoA.capturaHeader', $data);
                        $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                        $htmlCaptura = view('exports.laboratorio.volumetria.nitrogenoA.capturaBody1', $data);
                        $mpdf->CSSselectMedia = 'mpdf';
                        $mpdf->WriteHTML($htmlCaptura);

                        break;
                    case 108: //Nitrogeno Amoniacal
                        $mpdf = new \Mpdf\Mpdf([
                            'orientation' => 'P',
                            'format' => 'letter',
                            'margin_left' => 10,
                            'margin_right' => 10,
                            'margin_top' => 35,
                            'margin_bottom' => 45,
                            'defaultheaderfontstyle' => ['normal'],
                            'defaultheaderline' => '0'
                        ]);
                        //Establece la marca de agua del documento PDF
                        $mpdf->SetWatermarkImage(
                            asset('/public/storage/MembreteVertical.png'),
                            1,
                            array(215, 280),
                            array(0, 0),
                        );
                        $mpdf->showWatermarkImage = true;
                        $mpdf->CSSselectMedia = 'mpdf';

                        $loteDetalle = DB::table('ViewLoteDetalleNitrogeno')->where('Id_lote', $id)->get();
                        $valNitrogenoA = ValoracionNitrogeno::where('Id_lote', $id)->first();
                        $plantilla = Bitacoras::where('Id_lote', $id)->get();
                        if ($plantilla->count()) {
                        } else {
                            $plantilla = PlantillaBitacora::where('Id_parametro', $lote->Id_tecnica)->get();
                        }
                        $procedimiento = explode("NUEVASECCION", $plantilla[0]->Texto);
                        // var_dump($procedimiento[0]);
                        $comprobacion = LoteDetalleEspectro::where('Liberado', 0)->where('Id_lote', $id)->get();
                        if ($comprobacion->count()) {
                            $analizo = "";
                        } else {
                            $analizo = User::where('id', $loteDetalle[0]->Analizo)->first();
                        }
                        $reviso = User::where('id', 17)->first();
                        $data = array(
                            'comprobacion' => $comprobacion,
                            'analizo' => $analizo,
                            'reviso' => $reviso,
                            'lote' => $lote,
                            'loteDetalle' => $loteDetalle,
                            'plantilla' => $plantilla,
                            'valNitrogenoA' => $valNitrogenoA,
                            'procedimiento' => $procedimiento,
                        );

                        $htmlFooter = view('exports.laboratorio.volumetria.nitrogenoA.capturaFooter', $data);
                        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                        $htmlCaptura = view('exports.laboratorio.volumetria.nitrogenoA.capturaBody', $data);
                        $htmlHeader = view('exports.laboratorio.volumetria.nitrogenoA.capturaHeader', $data);

                        $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                        $mpdf->SetHTMLFooter("", 'O', 'E');
                        $mpdf->WriteHTML($htmlCaptura);
                        break;
                    case 10:
                        $loteDetalle = DB::table('ViewLoteDetalleNitrogeno')->where('Id_lote', $id)->get();
                        $valNitrogenoA = ValoracionNitrogeno::where('Id_lote', $id)->first();
                        $plantilla = Bitacoras::where('Id_lote', $id)->get();
                        if ($plantilla->count()) {
                        } else {
                            $plantilla = PlantillaBitacora::where('Id_parametro', $lote->Id_tecnica)->get();
                        }
                        $procedimiento = explode("NUEVASECCION", $plantilla[0]->Texto);
                        $valoracion = ValoracionCloro::where('Id_lote', $id)->first();

                        $comprobacion = LoteDetalleNitrogeno::where('Liberado', 0)->where('Id_lote', $id)->get();
                        if ($comprobacion->count()) {
                            $analizo = "";
                        } else {
                            $analizo = User::where('id', $loteDetalle[0]->Analizo)->first();
                        }
                        $reviso = User::where('id', 17)->first();
                        $data = array(
                            'analizo' => $analizo,
                            'procedimiento' => $procedimiento,
                            'comprobacion' => $comprobacion,
                            'reviso' => $reviso,
                            'lote' => $lote,
                            'loteDetalle' => $loteDetalle,
                            'valNitrogenoA' => $valNitrogenoA,
                            'plantilla' => $plantilla,
                            'procedimiento' => $procedimiento,
                        );
                        $htmlFooter = view('exports.laboratorio.volumetria.nitrogenoO.capturaFooter', $data);
                        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                        $htmlHeader = view('exports.laboratorio.volumetria.nitrogenoO.capturaHeader', $data);
                        $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                        $htmlCaptura = view('exports.laboratorio.volumetria.nitrogenoO.capturaBody', $data);
                        $mpdf->CSSselectMedia = 'mpdf';
                        $mpdf->WriteHTML($htmlCaptura);
                        break;
                }
                break;
            case 7: //Campo
            case 19: // Diretos
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
                switch ($lote->Id_tecnica) {
                    case 14: // PH
                        // case 110:
                        $model = DB::table('ViewLoteDetalleDirectos')->where('Id_lote', $id)->get();
                        $plantilla = Bitacoras::where('Id_lote', $id)->get();
                        if ($plantilla->count()) {
                        } else {
                            $plantilla = PlantillaBitacora::where('Id_parametro', $lote->Id_tecnica)->get();
                        }
                        $procedimiento = explode("NUEVASECCION", $plantilla[0]->Texto);
                        //Comprobación de bitacora analizada
                        $comprobacion = LoteDetalleDirectos::where('Liberado', 0)->where('Id_lote', $id)->get();
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
                    case 119:
                        $mpdf = new \Mpdf\Mpdf([
                            'orientation' => 'P',
                            'format' => 'letter',
                            'margin_left' => 10,
                            'margin_right' => 10,
                            'margin_top' => 40,
                            'margin_bottom' => 51,
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
                        $model = DB::table('ViewLoteDetalleDirectos')->where('Id_lote', $id)->get();
                        $plantilla = Bitacoras::where('Id_lote', $id)->get();
                        if ($plantilla->count()) {
                        } else {
                            $plantilla = PlantillaBitacora::where('Id_parametro', $lote->Id_tecnica)->get();
                        }
                        $procedimiento = explode("NUEVASECCION", $plantilla[0]->Texto);
                        $comprobacion = LoteDetalleDirectos::where('Liberado', 0)->where('Id_lote', $id)->get();
                        if ($comprobacion->count()) {
                            $analizo = "";
                        } else {
                            @$analizo = User::where('id', $model[0]->Analizo)->first();
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

                        $htmlHeader = view('exports.laboratorio.directos.cloro.bitacoraHeader', $data);
                        $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                        $htmlCaptura = view('exports.laboratorio.directos.cloro.bitacoraBody', $data);
                        $htmlFooter = view('exports.laboratorio.directos.cloro.bitacoraFooter', $data);
                        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                        $mpdf->CSSselectMedia = 'mpdf';
                        $mpdf->WriteHTML($htmlCaptura);
                        break;
                    case 110:
                        $model = DB::table('ViewLoteDetalleDirectos')->where('Id_lote', $id)->get();
                        $plantilla = Bitacoras::where('Id_lote', $id)->get();
                        if ($plantilla->count()) {
                        } else {
                            $plantilla = PlantillaBitacora::where('Id_parametro', $lote->Id_tecnica)->get();
                        }
                        $procedimiento = explode("NUEVASECCION", $plantilla[0]->Texto);
                        //Comprobación de bitacora analizada
                        $comprobacion = LoteDetalleDirectos::where('Liberado', 0)->where('Id_lote', $id)->get();
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
                        $htmlHeader = view('exports.laboratorio.directos.ph.127.bitacoraHeader', $data);
                        $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                        $htmlCaptura = view('exports.laboratorio.directos.ph.127.bitacoraBody', $data);
                        $htmlFooter = view('exports.laboratorio.directos.ph.127.bitacoraFooter', $data);
                        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                        $mpdf->CSSselectMedia = 'mpdf';
                        $mpdf->WriteHTML($htmlCaptura);
                        break;
                    case 67: // Conductividad

                        $model = DB::table('ViewLoteDetalleDirectos')->where('Id_lote', $id)->get();
                        $plantilla = Bitacoras::where('Id_lote', $id)->get();
                        if ($plantilla->count()) {
                        } else {
                            $plantilla = PlantillaBitacora::where('Id_parametro', $lote->Id_tecnica)->get();
                        }
                        $procedimiento = explode("NUEVASECCION", $plantilla[0]->Texto);
                        //Comprobación de bitacora analizada
                        $comprobacion = LoteDetalleDirectos::where('Liberado', 0)->where('Id_lote', $id)->get();
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

                        $htmlHeader = view('exports.laboratorio.directos.conductividad.bitacoraHeader', $data);
                        $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                        $htmlCaptura = view('exports.laboratorio.directos.conductividad.bitacoraBody', $data);
                        $htmlFooter = view('exports.laboratorio.directos.ph.bitacoraFooter', $data);
                        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                        $mpdf->CSSselectMedia = 'mpdf';
                        $mpdf->WriteHTML($htmlCaptura);
                        break;

                    case 97: // Temperatura
                        $model = DB::table('ViewLoteDetalleDirectos')->where('Id_lote', $id)->get();
                        $plantilla = Bitacoras::where('Id_lote', $id)->get();
                        if ($plantilla->count()) {
                        } else {
                            $plantilla = PlantillaBitacora::where('Id_parametro', $lote->Id_tecnica)->get();
                        }
                        $procedimiento = explode("NUEVASECCION", $plantilla[0]->Texto);
                        //Comprobación de bitacora analizada
                        $comprobacion = LoteDetalleDirectos::where('Liberado', 0)->where('Id_lote', $id)->get();
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

                        $htmlHeader = view('exports.laboratorio.directos.temperatura.bitacoraHeader', $data);
                        $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                        $htmlCaptura = view('exports.laboratorio.directos.temperatura.bitacoraBody', $data);
                        $htmlFooter = view('exports.laboratorio.directos.temperatura.bitacoraFooter', $data);
                        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                        $mpdf->CSSselectMedia = 'mpdf';
                        $mpdf->WriteHTML($htmlCaptura);
                        break;
                    case 66:
                    case 102: // COLOR VERDADERO
                        $model = DB::table('ViewLoteDetalleDirectos')->where('Id_lote', $id)->get();
                        $plantilla = Bitacoras::where('Id_lote', $id)->get();
                        if ($plantilla->count()) {
                        } else {
                            $plantilla = PlantillaBitacora::where('Id_parametro', $lote->Id_tecnica)->get();
                        }
                        $procedimiento = explode("NUEVASECCION", $plantilla[0]->Texto);
                        $comprobacion = LoteDetalleDirectos::where('Liberado', 0)->where('Id_lote', $id)->get();
                        if ($comprobacion->count()) {
                            $analizo = "";
                        } else {
                            @$analizo = User::where('id', $model[0]->Analizo)->first();
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
                        $htmlFooter = view('exports.laboratorio.directos.color.bitacoraFooter', $data);
                        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                        $htmlHeader = view('exports.laboratorio.directos.color.bitacoraHeader', $data);
                        $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                        $htmlCaptura = view('exports.laboratorio.directos.color.bitacoraBody', $data);
                        $mpdf->CSSselectMedia = 'mpdf';
                        $mpdf->WriteHTML($htmlCaptura);
                        break;
                    case 58: //turbiedad
                    case 89:
                    case 98:
                    case 115:
                        $model = DB::table('ViewLoteDetalleDirectos')->where('Id_lote', $id)->get();
                        $plantilla = Bitacoras::where('Id_lote', $id)->get();
                        if ($plantilla->count()) {
                        } else {
                            $plantilla = PlantillaBitacora::where('Id_parametro', $lote->Id_tecnica)->get();
                        }
                        $procedimiento = explode("NUEVASECCION", $plantilla[0]->Texto);
                        $comprobacion = LoteDetalleDirectos::where('Liberado', 0)->where('Id_lote', $id)->get();
                        if ($comprobacion->count()) {
                            $analizo = "";
                        } else {
                            @$analizo = User::where('id', $model[0]->Analizo)->first();
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
                break;
            case 8: //Potable
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
                $mpdf->CSSselectMedia = 'mpdf';
                switch ($lote->Id_tecnica) {
                    case 77: //Dureza
                    case 251:
                    case 252:
                        $model = DB::table('ViewLoteDetalleDureza')->where('Id_lote', $id)->get();
                        $plantilla = Bitacoras::where('Id_lote', $id)->get();
                        if ($plantilla->count()) {
                        } else {
                            $plantilla = PlantillaBitacora::where('Id_parametro', $lote->Id_tecnica)->get();
                        }
                        $data = array(
                            'lote' => $lote,
                            'model' => $model,
                        );


                        $htmlHeader = view('exports.laboratorio.potable.durezaTotal.bitacoraHeader', $data);
                        $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                        $htmlCaptura = view('exports.laboratorio.potable.durezaTotal.bitacoraBody', $data);
                        $htmlFooter = view('exports.laboratorio.potable.durezaTotal.bitacoraFooter', $data);
                        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                        $mpdf->CSSselectMedia = 'mpdf';
                        $mpdf->WriteHTML($htmlCaptura);
                        break;
                    case 103:
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
                        $mpdf->CSSselectMedia = 'mpdf';
                        $model = DB::table('ViewLoteDetalleDureza')->where('Id_lote', $id)->get();
                        $plantilla = Bitacoras::where('Id_lote', $id)->get();
                        if ($plantilla->count()) {
                        } else {
                            $plantilla = PlantillaBitacora::where('Id_parametro', $lote->Id_tecnica)->get();
                        }
                        $procedimiento = explode("NUEVASECCION", $plantilla[0]->Texto);
                        $comprobacion = LoteDetalleDureza::where('Liberado', 0)->where('Id_lote', $id)->get();
                        if ($comprobacion->count()) {
                            $analizo = "";
                        } else {
                            @$analizo = User::where('id', $model[0]->Analizo)->first();
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


                        $htmlHeader = view('exports.laboratorio.potable.durezaTotal.127.bitacoraHeader', $data);
                        $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                        $htmlCaptura = view('exports.laboratorio.potable.durezaTotal.127.bitacoraBody', $data);
                        $htmlFooter = view('exports.laboratorio.potable.durezaTotal.127.bitacoraFooter', $data);
                        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                        $mpdf->CSSselectMedia = 'mpdf';
                        $mpdf->WriteHTML($htmlCaptura);
                        break;
                    case 98:
                        $model = DB::table('ViewLoteDetallePotable')->where('Id_lote', $id)->get();
                        $textoProcedimiento = Bitacoras::where('Id_parametro', 77)->first();
                        $data = array(
                            'lote' => $lote,
                            'model' => $model,
                            'plantilla' => $textoProcedimiento
                        );

                        $htmlHeader = view('exports.laboratorio.potable.turbiedad.bitacoraHeader', $data);
                        $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                        $htmlCaptura = view('exports.laboratorio.potable.turbiedad.bitacoraBody', $data);
                        $htmlFooter = view('exports.laboratorio.potable.turbiedad.bitacoraFooter', $data);
                        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                        $mpdf->CSSselectMedia = 'mpdf';
                        $mpdf->WriteHTML($htmlCaptura);
                        break;
                    case 108: //Nitrogeno Amoniacal
                        // echo "Entra a funcion";
                        $mpdf = new \Mpdf\Mpdf([
                            'orientation' => 'P',
                            'format' => 'letter',
                            'margin_left' => 10,
                            'margin_right' => 10,
                            'margin_top' => 35,
                            'margin_bottom' => 45,
                            'defaultheaderfontstyle' => ['normal'],
                            'defaultheaderline' => '0'
                        ]);
                        //Establece la marca de agua del documento PDF
                        $mpdf->SetWatermarkImage(
                            asset('/public/storage/MembreteVertical.png'),
                            1,
                            array(215, 280),
                            array(0, 0),
                        );
                        $mpdf->showWatermarkImage = true;
                        $mpdf->CSSselectMedia = 'mpdf';

                        $loteDetalle = DB::table('ViewLoteDetalleNitrogeno')->where('Id_lote', $id)->get();
                        $valNitrogenoA = ValoracionNitrogeno::where('Id_lote', $id)->first();
                        $plantilla = Bitacoras::where('Id_lote', $id)->get();
                        if ($plantilla->count()) {
                        } else {
                            $plantilla = PlantillaBitacora::where('Id_parametro', $lote->Id_tecnica)->get();
                        }
                        $procedimiento = explode("NUEVASECCION", $plantilla[0]->Texto);
                        // var_dump($procedimiento[0]);
                        $comprobacion = LoteDetalleEspectro::where('Liberado', 0)->where('Id_lote', $id)->get();
                        if ($comprobacion->count()) {
                            $analizo = "";
                        } else {
                            $analizo = User::where('id', $loteDetalle[0]->Analizo)->first();
                        }
                        $reviso = User::where('id', 17)->first();
                        $data = array(
                            'comprobacion' => $comprobacion,
                            'analizo' => $analizo,
                            'reviso' => $reviso,
                            'lote' => $lote,
                            'loteDetalle' => $loteDetalle,
                            'plantilla' => $plantilla,
                            'valNitrogenoA' => $valNitrogenoA,
                            'procedimiento' => $procedimiento,
                        );
                        $htmlFooter = view('exports.laboratorio.volumetria.nitrogenoA.capturaFooter', $data);
                        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                        $htmlCaptura = view('exports.laboratorio.volumetria.nitrogenoA.capturaBody', $data);
                        $htmlHeader = view('exports.laboratorio.volumetria.nitrogenoA.capturaHeader', $data);

                        $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                        $mpdf->SetHTMLFooter("", 'O', 'E');
                        $mpdf->WriteHTML($htmlCaptura);
                        break;
                }
                break;
            case 6: //Mb
            case 12:
            case 3:
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

                $mpdf->showWatermarkImage = true;
                switch ($lote->Id_tecnica) {
                    case 35:
                        $mpdf = new \Mpdf\Mpdf([
                            'orientation' => "L",
                            'format' => 'letter',
                            'margin_left' => 10,
                            'margin_right' => 10,
                            'margin_top' => 40,
                            'margin_bottom' => 50,
                            'defaultheaderfontstyle' => ['normal'],
                            'defaultheaderline' => '0'
                        ]);
                        $mpdf->SetWatermarkImage(
                            asset('/public/storage/HojaMembretadaHorizontal.png'),
                            1,
                            array(215, 280),
                            array(0, 0),
                        );
                        $mpdf->showWatermarkImage = true;
                        $loteDetalleControles = DB::table('ViewLoteDetalleColiformes')->where('Id_lote', $id)->where('Id_control', '!=', 1)->get();
                        $loteDetalle = DB::table('ViewLoteDetalleColiformes')->where('Id_lote', $id)->where('Id_control',1)->get();
                        $plantilla = Bitacoras::where('Id_lote', $id)->get();
                        if ($plantilla->count()) {
                        } else {
                            $plantilla = PlantillaBitacora::where('Id_parametro', $lote->Id_tecnica)->get();
                        }
                        //Comprobación de bitacora analizada
                        $comprobacion = LoteDetalleColiformes::where('Liberado', 0)->where('Id_lote', $id)->get();
                        if ($comprobacion->count()) {
                            $analizo = "";
                        } else {
                            $analizo = User::where('id', $loteDetalle[0]->Analizo)->first();
                        }
                        $reviso = User::where('id', 17)->first();


                        $data = array(
                            'lote' => $lote,
                            'loteDetalle' => $loteDetalle,
                            'plantilla' => $plantilla,
                            'loteDetalleControles' => $loteDetalleControles,
                            'analizo' => $analizo,
                            'reviso' => $reviso,
                            'comprobacion' => $comprobacion,
                        );

                        $htmlFooter = view('exports.laboratorio.mb.ecoli.bitacoraFooter', $data);
                        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                        $htmlHeader = view('exports.laboratorio.mb.ecoli.bitacoraHeader', $data);
                        $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                        $htmlCaptura = view('exports.laboratorio.mb.ecoli.bitacoraBody', $data);
                        $mpdf->CSSselectMedia = 'mpdf';
                        $mpdf->WriteHTML($htmlCaptura);
                        break;
                    case 253:
                        $mpdf = new \Mpdf\Mpdf([
                            'orientation' => "L",
                            'format' => 'letter',
                            'margin_left' => 10,
                            'margin_right' => 10,
                            'margin_top' => 40,
                            'margin_bottom' => 50,
                            'defaultheaderfontstyle' => ['normal'],
                            'defaultheaderline' => '0'
                        ]);
                        $mpdf->SetWatermarkImage(
                            asset('/public/storage/HojaMembretadaHorizontal.png'),
                            1,
                            array(215, 280),
                            array(0, 0),
                        );
                        $mpdf->showWatermarkImage = true;

                        $loteDetalle = DB::table('ViewLoteDetalleEnterococos')->where('Id_lote', $id)->where('Id_control',1)->get();
                        $loteDetalleControles = DB::table('ViewLoteDetalleEnterococos')->where('Id_lote', $id)->where('Id_control','!=',1)->get();
                        $plantilla = Bitacoras::where('Id_lote', $id)->get();
                        if ($plantilla->count()) {
                        } else {
                            $plantilla = PlantillaBitacora::where('Id_parametro', $lote->Id_tecnica)->get();
                        }
                        //Comprobación de bitacora analizada
                        $comprobacion = LoteDetalleEnterococos::where('Liberado', 0)->where('Id_lote', $id)->get();
                        if ($comprobacion->count()) {
                            $analizo = "";
                        } else {
                            @$analizo = User::where('id', $loteDetalle[0]->Analizo)->first();
                        }
                        $reviso = User::where('id', 17)->first();


                        $data = array(
                            'lote' => $lote,
                            'loteDetalleControles' => $loteDetalleControles,
                            'loteDetalle' => $loteDetalle,
                            'plantilla' => $plantilla,
                            'analizo' => $analizo,
                            'reviso' => $reviso,
                            'comprobacion' => $comprobacion,
                        );

                        $htmlFooter = view('exports.laboratorio.mb.enterococos.bitacoraFooter', $data);
                        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                        $htmlHeader = view('exports.laboratorio.mb.enterococos.bitacoraHeader', $data);
                        $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                        $htmlCaptura = view('exports.laboratorio.mb.enterococos.bitacoraBody', $data);
                        $mpdf->CSSselectMedia = 'mpdf';
                        $mpdf->WriteHTML($htmlCaptura);
                        break;
                    case 134:
                    case 132:
                    
                        $mpdf = new \Mpdf\Mpdf([
                            'orientation' => "L",
                            'format' => 'letter',
                            'margin_left' => 10,
                            'margin_right' => 10,
                            'margin_top' => 40,
                            'margin_bottom' => 30,
                            'defaultheaderfontstyle' => ['normal'],
                            'defaultheaderline' => '0'
                        ]);
                        $mpdf->SetWatermarkImage(
                            asset('/public/storage/HojaMembretadaHorizontal.png'),
                            1,
                            array(215, 280),
                            array(0, 0),
                        );
                        $mpdf->showWatermarkImage = true;

                        $loteDetalle = DB::table('ViewLoteDetalleColiformes')->where('Id_lote', $id)->get();
                        $plantilla = Bitacoras::where('Id_lote', $id)->get();
                        if ($plantilla->count()) {
                        } else {
                            $plantilla = PlantillaBitacora::where('Id_parametro', $lote->Id_tecnica)->get();
                        }
                        //Comprobación de bitacora analizada
                        $comprobacion = LoteDetalleColiformes::where('Liberado', 0)->where('Id_lote', $id)->get();
                        if ($comprobacion->count()) {
                            $analizo = "";
                        } else {
                            @$analizo = User::where('id', $loteDetalle[0]->Analizo)->first();
                        }
                        $reviso = User::where('id', 17)->first();


                        $data = array(
                            'lote' => $lote,
                            'loteDetalle' => $loteDetalle,
                            'plantilla' => $plantilla,
                            'analizo' => $analizo,
                            'reviso' => $reviso,
                            'comprobacion' => $comprobacion,
                        );

                        $htmlFooter = view('exports.laboratorio.mb.127.coliformes.capturaFooter', $data);
                        $htmlHeader = view('exports.laboratorio.mb.127.coliformes.capturaHeader', $data);
                        $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                        $htmlCaptura = view('exports.laboratorio.mb.127.coliformes.capturaBody', $data);
                        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                        $mpdf->CSSselectMedia = 'mpdf';
                        $mpdf->WriteHTML($htmlCaptura);
                        break;
                    case 12:
                    case 137:
                        $mpdf = new \Mpdf\Mpdf([
                            'orientation' => "L",
                            'format' => 'letter',
                            'margin_left' => 10,
                            'margin_right' => 10,
                            'margin_top' => 40,
                            'margin_bottom' => 50,
                            'defaultheaderfontstyle' => ['normal'],
                            'defaultheaderline' => '0'
                        ]);
                        $mpdf->SetWatermarkImage(
                            asset('/public/storage/HojaMembretadaHorizontal.png'),
                            1,
                            array(215, 280),
                            array(0, 0),
                        ); 
                        $mpdf->showWatermarkImage = true;
                        $loteDetalleControles = DB::table('ViewLoteDetalleColiformes')->where('Id_lote', $id)->where('Id_control', '!=', 1)->get();
                        $loteDetalle = DB::table('ViewLoteDetalleColiformes')->where('Id_lote', $id)->get();
                        $bitacora = BitacoraColiformes::where('Id_lote', $id)->first();
                        $plantilla = Bitacoras::where('Id_lote', $id)->get();
                        if ($plantilla->count()) {
                        } else {
                            $plantilla = PlantillaBitacora::where('Id_parametro', $lote->Id_tecnica)->get();
                        }
                        //Comprobación de bitacora analizada
                        $comprobacion = LoteDetalleColiformes::where('Liberado', 0)->where('Id_lote', $id)->get();
                        if ($comprobacion->count()) {
                            $analizo = "";
                        } else {
                            $analizo = User::where('id', $loteDetalle[0]->Analizo)->first();
                        }
                        $reviso = User::where('id', 17)->first();


                        $data = array(
                            'lote' => $lote,
                            'bitacora' => $bitacora,
                            'data' => $loteDetalle,
                            'plantilla' => $plantilla,
                            'loteDetalleControles' => $loteDetalleControles,
                            'analizo' => $analizo,
                            'reviso' => $reviso,
                            'comprobacion' => $comprobacion,
                        );

                        $htmlFooter = view('exports.laboratorio.mb.coliformes.capturaFooter', $data);
                        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                        $htmlHeader = view('exports.laboratorio.mb.coliformes.capturaHeader', $data);
                        $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                        $htmlCaptura = view('exports.laboratorio.mb.coliformes.capturaBody', $data);
                        $mpdf->CSSselectMedia = 'mpdf';
                        $mpdf->WriteHTML($htmlCaptura);
                        // return redirect('/admin/laboratorio/micro/captura/exportPdfCaptura/' . $id);
                        break;
                        // case 135:
                    case 133: //Coliformes totales
                        $mpdf = new \Mpdf\Mpdf([
                            'orientation' => "L",
                            'format' => 'letter',
                            'margin_left' => 10,
                            'margin_right' => 10,
                            'margin_top' => 45,
                            'margin_bottom' => 45,
                            'defaultheaderfontstyle' => ['normal'],
                            'defaultheaderline' => '0'
                        ]);
                        $mpdf->SetWatermarkImage(
                            asset('/public/storage/HojaMembretadaHorizontal.png'),
                            1,
                            array(215, 280),
                            array(0, 0),
                        );
                        $mpdf->showWatermarkImage = true;

                        $model = DB::table('ViewLoteDetalleColiformes')->where('Id_lote', $id)->get();
                        $plantilla = Bitacoras::where('Id_lote', $id)->get();
                        if ($plantilla->count()) {
                        } else {
                            $plantilla = PlantillaBitacora::where('Id_parametro', $lote->Id_tecnica)->get();
                        }
                        $procedimiento = explode("NUEVASECCION", $plantilla[0]->Texto);
                        //Comprobación de bitacora analizada
                        $comprobacion = LoteDetalleColiformes::where('Liberado', 0)->where('Id_lote', $id)->get();
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
                        $htmlFooter = view('exports.laboratorio.mb.127.coliformes.capturaFooter2', $data);
                        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                        $htmlHeader = view('exports.laboratorio.mb.127.coliformes.capturaHeader2', $data);
                        $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                        $htmlCaptura = view('exports.laboratorio.mb.127.coliformes.capturaBody2', $data);
                        $mpdf->CSSselectMedia = 'mpdf';
                        $mpdf->WriteHTML($htmlCaptura);
                        break;
                    case 135:
                        $mpdf = new \Mpdf\Mpdf([
                            'orientation' => "L",
                            'format' => 'letter',
                            'margin_left' => 10,
                            'margin_right' => 10,
                            'margin_top' => 31,
                            'margin_bottom' => 45,
                            'defaultheaderfontstyle' => ['normal'],
                            'defaultheaderline' => '0'
                        ]);
                        $mpdf->SetWatermarkImage(
                            asset('/public/storage/HojaMembretadaHorizontal.png'),
                            1,
                            array(215, 280),
                            array(0, 0),
                        );
                        $mpdf->showWatermarkImage = true;

                        $loteDetalle = DB::table('ViewLoteDetalleColiformes')->where('Id_lote', $id)->get();
                        $bitacora = Bitacoras::where('Id_parametro', 134)->first();

                        $data = array(
                            'lote' => $lote,
                            'loteDetalle' => $loteDetalle,
                            'bitacora' => $bitacora,
                        );

                        $htmlHeader = view('exports.laboratorio.mb.127.coliformes.capturaHeader', $data);
                        $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                        $htmlCaptura = view('exports.laboratorio.mb.127.coliformes.capturaBody', $data);
                        $mpdf->CSSselectMedia = 'mpdf';
                        $mpdf->WriteHTML($htmlCaptura);
                        break;
                    case 78: // E.Coli Potable
                        $mpdf = new \Mpdf\Mpdf([
                            'orientation' => "L",
                            'format' => 'letter',
                            'margin_left' => 10,
                            'margin_right' => 10,
                            'margin_top' => 31,
                            'margin_bottom' => 45,
                            'defaultheaderfontstyle' => ['normal'],
                            'defaultheaderline' => '0'
                        ]);
                        $mpdf->SetWatermarkImage(
                            asset('/public/storage/HojaMembretadaHorizontal.png'),
                            1,
                            array(215, 280),
                            array(0, 0),
                        );
                        $mpdf->showWatermarkImage = true;

                        $loteDetalle = DB::table('ViewLoteDetalleEcoli')->where('Id_lote', $id)->get();
                        $convinaciones = ConvinacionesEcoli::where('Id_lote', $id)->get();
                        $bitacora = Bitacoras::where('Id_parametro', 78)->first();

                        $data = array(
                            'lote' => $lote,
                            'loteDetalle' => $loteDetalle,
                            'convinaciones' => $convinaciones,
                            'bitacora' => $bitacora,
                        );

                        $htmlHeader = view('exports.laboratorio.mb.127.ecoli.capturaHeader', $data);
                        $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                        $htmlCaptura = view('exports.laboratorio.mb.127.ecoli.capturaBody', $data);
                        $mpdf->CSSselectMedia = 'mpdf';
                        $mpdf->WriteHTML($htmlCaptura);
                        break;
                    case 5:
                    case 71:
                        $mpdf = new \Mpdf\Mpdf([
                            'orientation' => "L",
                            'format' => 'letter',
                            'margin_left' => 10,
                            'margin_right' => 10,
                            'margin_top' => 40,
                            'margin_bottom' => 50,
                            'defaultheaderfontstyle' => ['normal'],
                            'defaultheaderline' => '0'
                        ]);
                        $mpdf->SetWatermarkImage(
                            asset('/public/storage/HojaMembretadaHorizontal.png'),
                            1,
                            array(215, 280),
                            array(0, 0),
                        );
                        $mpdf->showWatermarkImage = true;

                        $loteDetalle = DB::table('ViewLoteDetalleDbo')->where('Id_lote', $id)->get();
                        $plantilla = Bitacoras::where('Id_lote', $id)->get();
                        if ($plantilla->count()) {
                        } else {
                            $plantilla = PlantillaBitacora::where('Id_parametro', $lote->Id_tecnica)->get();
                        }
                        $procedimiento = explode("NUEVASECCION", $plantilla[0]->Texto);
                        //Comprobación de bitacora analizada
                        $comprobacion = LoteDetalleDbo::where('Liberado', 0)->where('Id_lote', $id)->get();
                        if ($comprobacion->count()) {
                            $analizo = "";
                        } else {
                            $analizo = User::where('id', $loteDetalle[0]->Analizo)->first();
                        }
                        $reviso = User::where('id', 17)->first();
                        $detalleLote = DqoDetalle::where('Id_lote', $id)->first();

                        $data = array(
                            'lote' => $lote,
                            'detalleLote' => $detalleLote,
                            'procedimiento' => $procedimiento,
                            'loteDetalle' => $loteDetalle,
                            'plantilla' => $plantilla,
                            'analizo' => $analizo,
                            'reviso' => $reviso,
                            'comprobacion' => $comprobacion,
                        );

                        $htmlFooter = view('exports.laboratorio.mb.dbo.capturaFooter', $data);
                        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                        $htmlHeader = view('exports.laboratorio.mb.dbo.capturaHeader', $data);
                        $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                        $htmlCaptura = view('exports.laboratorio.mb.dbo.capturaBody', $data);
                        $mpdf->CSSselectMedia = 'mpdf';
                        $mpdf->WriteHTML($htmlCaptura);
                        break;
                    case 70:
                        $mpdf = new \Mpdf\Mpdf([
                            'orientation' => "L",
                            'format' => 'letter',
                            'margin_left' => 10,
                            'margin_right' => 10,
                            'margin_top' => 40,
                            'margin_bottom' => 50,
                            'defaultheaderfontstyle' => ['normal'],
                            'defaultheaderline' => '0'
                        ]);
                        $mpdf->SetWatermarkImage(
                            asset('/public/storage/HojaMembretadaHorizontal.png'),
                            1,
                            array(215, 280),
                            array(0, 0),
                        );
                        $mpdf->showWatermarkImage = true;

                        $loteDetalle = DB::table('ViewLoteDetalleDboIno')->where('Id_lote', $id)->get();
                        $plantilla = Bitacoras::where('Id_lote', $id)->get();
                        if ($plantilla->count()) {
                        } else {
                            $plantilla = PlantillaBitacora::where('Id_parametro', $lote->Id_tecnica)->get();
                        }
                        $procedimiento = explode("NUEVASECCION", $plantilla[0]->Texto);
                        //Comprobación de bitacora analizada
                        $comprobacion = LoteDetalleDboIno::where('Liberado', 0)->where('Id_lote', $id)->get();
                        if ($comprobacion->count()) {
                            $analizo = "";
                        } else {
                            $analizo = User::where('id', $loteDetalle[0]->Analizo)->first();
                        }
                        $reviso = User::where('id', 17)->first();
                        // $detalleLote = DqoDetalle::where('Id_lote', $id)->first();

                        $data = array(
                            'lote' => $lote,
                            // 'detalleLote' => $detalleLote,
                            'procedimiento' => $procedimiento,
                            'loteDetalle' => $loteDetalle,
                            'plantilla' => $plantilla,
                            'analizo' => $analizo,
                            'reviso' => $reviso,
                            'comprobacion' => $comprobacion,
                        ); 
 
                        $htmlFooter = view('exports.laboratorio.mb.dboIn.capturaFooter', $data);
                        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                        $htmlHeader = view('exports.laboratorio.mb.dboIn.capturaHeader', $data);
                        $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                        $htmlCaptura = view('exports.laboratorio.mb.dboIn.capturaBody', $data);
                        $mpdf->CSSselectMedia = 'mpdf';
                        $mpdf->WriteHTML($htmlCaptura);
                        break;
                    case 16:
                        $mpdf = new \Mpdf\Mpdf([
                            'orientation' => 'P',
                            'format' => 'letter',
                            'margin_left' => 10,
                            'margin_right' => 10,
                            'margin_top' => 35,
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
                        $loteDetalle = DB::table('ViewLoteDetalleHH')->where('Id_lote', $id)->get();
                        $plantilla = Bitacoras::where('Id_lote', $id)->get();
                        if ($plantilla->count()) {
                        } else {
                            $plantilla = PlantillaBitacora::where('Id_parametro', $lote->Id_tecnica)->get();
                        }
                        $procedimiento = explode("NUEVASECCION", $plantilla[0]->Texto);
                        $comprobacion = LoteDetalleHH::where('Liberado', 0)->where('Id_lote', $id)->get();
                        if ($comprobacion->count()) {
                            $analizo = "";
                        } else {
                            $analizo = User::where('id', $loteDetalle[0]->Analizo)->first();
                        }
                        $reviso = User::where('id', 17)->first();
                        $data = array(
                            'lote' => $lote,
                            'loteDetalle' => $loteDetalle,
                            'plantilla' => $plantilla,
                            'procedimiento' => $procedimiento,
                            'comprobacion' => $comprobacion,
                            'analizo' => $analizo,
                            'reviso' => $reviso,
                        );
                        $htmlFooter = view('exports.laboratorio.mb.hh.capturaFooter', $data);
                        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                        $htmlHeader = view('exports.laboratorio.mb.hh.capturaHeader', $data);
                        $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                        $htmlCaptura = view('exports.laboratorio.mb.hh.capturaBody', $data);
                        $mpdf->WriteHTML($htmlCaptura);
                        $mpdf->CSSselectMedia = 'mpdf';
                        break;
                    default:
                        break;
                }
                break;
            default:
                break;
        }
        $mpdf->Output();
    }
    public function setDetalleGrasas(Request $request)
    {
        $model = GrasasDetalle::where('Id_lote', $request->id)->first();
        $model->Calentamiento_temp1 = $request->temp1;
        $model->Calentamiento_entrada1 = $request->entrada1;
        $model->Calentamiento_salida1 = $request->salida1;
        $model->Calentamiento_temp2 = $request->temp2;
        $model->Calentamiento_entrada2 = $request->entrada2;
        $model->Calentamiento_salida2 = $request->salida2;
        $model->Calentamiento_temp3 = $request->temp3;
        $model->Calentamiento_entrada3 = $request->entrada3;
        $model->Calentamiento_salida3 = $request->salida3;
        $model->Enfriado_entrada1 = $request->dosentrada1;
        $model->Enfriado_salida1 = $request->dosalida1;
        $model->Enfriado_pesado1 = $request->dospesado1;
        $model->Enfriado_entrada2 = $request->dosentrada2;
        $model->Enfriado_salida2 = $request->dosalida2;
        $model->Enfriado_pesado2 = $request->dospesado2;
        $model->Enfriado_entrada3 = $request->dosentrada3;
        $model->Enfriado_salida3 = $request->dosalida3;
        $model->Enfriado_pesado3 = $request->dospesado3;
        $model->Secado_temp = $request->trestemperatura;
        $model->Secado_entrada = $request->tresentrada;
        $model->Secado_salida = $request->tressalida;
        $model->Reflujo_entrada = $request->cuatroentrada;
        $model->Reflujo_salida = $request->cuatrosalida;
        $model->Enfriado_matraces_entrada = $request->cincoentrada;
        $model->Enfriado_matraces_salida = $request->cincosalida;
        $model->save(); 

        $data = array(
            'model' => $model,
        );

        return response()->json($data);
    }
    public function liberarMatraz(){
        $model = MatrazGA::all();

        foreach ($model as $item) {
            $temp = LoteDetalleGA::where('Id_matraz',$item->Id_matraz)->where('Liberado',0)->get();
            if ($temp->count()) {
                
            }else{
                $aux = MatrazGA::find($item->Id_matraz);
                $aux->Estado = 0;
                $aux->save();
            }
        }
    }
}
