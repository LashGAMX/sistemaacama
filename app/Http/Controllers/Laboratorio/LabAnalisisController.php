<?php

namespace App\Http\Controllers\Laboratorio;

use App\Http\Controllers\Controller;
use App\Http\Livewire\AnalisisQ\Parametros;
use App\Http\Livewire\Config\CrisolGA;
use App\Models\BitacoraColiformes;
use App\Models\Bitacoras;
use App\Models\CampoCompuesto;
use App\Models\Capsulas;
use App\Models\CodigoParametros;
use App\Models\ConductividadMuestra;
use App\Models\ConfiguracionMetales;
use App\Models\ControlCalidad;
use App\Models\ConvinacionesEcoli;
use App\Models\CrisolesGA;
use App\Models\CurvaConstantes;
use App\Models\DqoDetalle;
use App\Models\GrasasDetalle;
use App\Models\LoteAnalisis;
use App\Models\LoteDetalle;
use App\Models\LoteDetalleAlcalinidad;
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
use App\Models\MetalesDetalle;
use App\Models\MetalesDetalle2;
use App\Models\Nmp1Micro;
use App\Models\Parametro;
use App\Models\PhMuestra;
use App\Models\PlantillaBitacora;
use App\Models\ProcesoAnalisis;
use App\Models\Promedio;
use App\Models\SembradoFq;
use App\Models\Solicitud;
use App\Models\SolicitudPuntos;
use App\Models\User;
use App\Models\ValoracionAlcalinidad;
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
        $codigo = DB::table('ViewCodigoPendientes')->where('Asignado', 0)->where('Cancelado','!=',1)->get();
        $param = DB::table('ViewParametroUsuarios')->where('Id_user', Auth::user()->id)->get();

        foreach ($codigo as $item) {
            $temp = array();
            foreach ($param as $item2) {
                if ($item->Id_parametro == $item2->Id_parametro) {
                    array_push($temp, $item->Codigo);
                    array_push($temp, "(" . $item->Id_parametro . ") " . $item->Parametro);
                    array_push($temp, $item->Hora_recepcion);
                    array_push($temp, $item->Empresa);
                    array_push($temp, $item->Historial);

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
        $aux = array();
        if ($res->folio != "") {
            $temp = DB::table('codigo_parametro')->where('Codigo','LIKE', '%'.$res->folio.'%')->where('Id_parametro', $res->id)->first();
            $model = DB::table('ViewLoteAnalisis')->where('Id_lote', $temp->Id_lote)->get();
        } else {
            $model = DB::table('ViewLoteAnalisis')->where('Id_tecnica', $res->id)->where('Fecha', $res->fecha)->get();
        }

        switch ($res->id) {
            case 6:
                $titulo = "";
                foreach ($model as $item) {
                    $temp = DqoDetalle::where('Id_lote',$item->Id_lote)->get();
                    if ($temp->count()) {
                        switch ($temp[0]->Tipo) {
                            case 1:
                                $titulo = "Alta";
                                break;
                            case 2:
                                $titulo = "Baja";
                                break;
                            case 3:
                                $titulo = "Alta";
                                break;
                            case 4:
                                $titulo = "Baja";
                                break;
                            default:
                                
                                break;
                        }
                        array_push($aux,$titulo);
                    }else{
                        array_push($aux,"Sin tipo seleccionado");
                    }
                }
                break;
            
            default:
                # code...
                break;
        }

        $data = array(
            'aux' => $aux,
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
            case 5:
            case 71:
                $model = DqoDetalle::create([
                    'Id_lote' => $model->Id_lote,
                    'N' => "RE-12-001-01",
                    'Estandares_bit' => "RE-12-001-1A-13",
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
        $idCodigo = array();
        $historial = array();
        $lote = DB::table('ViewLoteAnalisis')->where('Id_lote', $res->idLote)->first();
        if ($res->fecha != "") {
        } else {
            $model = DB::table('codigo_parametro')->where('Asignado','!=' ,1)->where('Id_parametro', $lote->Id_tecnica)->where('Cancelado','!=',1)->get();
            for ($i = 0; $i < $model->count(); $i++) {
                $puntoModel = SolicitudPuntos::where('Id_solicitud', $model[$i]->Id_solicitud)->first();   
                $normaModel = DB::table('ViewSolicitud2')->where('Id_solicitud',$model[$i]->Id_solicitud)->first();
                $proceso = ProcesoAnalisis::where('Id_solicitud',$model[$i]->Id_solicitud)->get();
                if ($proceso->count()) {
                    array_push($idCodigo, $model[$i]->Id_codigo);
                    array_push($folio, $model[$i]->Codigo);
                    array_push($norma, @$normaModel->Clave_norma);
                    array_push($punto, @$puntoModel->Punto);
                    array_push($fecha, $proceso[0]->Hora_recepcion);    
                    array_push($historial,@$model[$i]->Historial);
                }
                
            }
        }

        $data = array(
            'idCodigo' => $idCodigo,
            'model' => $model,
            'folio' => $folio,
            'norma' => $norma,
            'fecha' => $fecha,
            'punto' => $punto,
            'lote' => $lote,
            'historial' => $historial,
        );
        return response()->json($data);
    }
    // public function getHistorialParametro(Request $res)
    // {
    //     $folio = $res->input('folio');
    //     $codigoParametro=CodigoParametros :: where('Codigo',$folio)->first();
    //    if($codigoParametro)
    //    {
    //     $historial=$codigoParametro ->loteDetalles()->orderBy('Fecha','desc')->take(3)->get();//muestra los tres ultimos regsitros 
    //     return response()->json($historial);
    //    }else {
    //     return response()->json(['error'=>'No se Encontro regsitros para el Historial']);
    //    }
    // }

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
                        'Liberado' => 0,
                        'Analizo' => 1,
                    ]);
                    $tempModel = LoteDetalleGA::where('Id_lote', $res->idLote)->get();
                    break;
                case 15: //Solidos
                    switch ($model->Id_parametro) {
                        case 4:
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
                        case 90:
                            $temp = LoteDetalleSolidos::create([
                                'Id_lote' => $res->idLote,
                                'Id_analisis' => $model->Id_solicitud,
                                'Id_codigo' => $model->Id_codigo,
                                'Id_parametro' => $model->Id_parametro,
                                'Id_control' => 1,
                                'Factor_conversion' => 1000000,
                                'Liberado' => 0,
                                'Analizo' => 1,
                            ]);
                            $tempModel = LoteDetalleSolidos::where('Id_lote', $res->idLote)->get();
                            break;
                        default:
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
                    }
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
                                'Equivalencia' => 8000,
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
                        case 28:
                        case 29:
                        case 30:
                        case 27:
                            $temp = LoteDetalleAlcalinidad::create([
                                'Id_lote' => $res->idLote,
                                'Id_analisis' => $model->Id_solicitud,
                                'Id_codigo' => $model->Id_codigo,
                                'Id_parametro' => $model->Id_parametro,
                                'Id_control' => 1,
                                'Factor_conversion' => 50000, 
                                'Analizo' => 1,
                                'Liberado' => 0,
                            ]);
                            $tempModel = LoteDetalleAlcalinidad::where('Id_lote', $res->idLote)->get();
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
                                'Factor_conversionVal1' => 1000,
                                'Factor_conversionVal2' => 1000,
                                'Factor_conversionVal3' => 1000,
                                'Vol_muestraVal1' => 50,
                                'Vol_muestraVal2' => 50,
                                'Vol_muestraVal3' => 50,
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
                        case 350:
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
                        switch ($model->Id_parametro) {
                            case 98:
                            case 89:
                            case 115:
                                $temp = LoteDetalleDirectos::create([
                                    'Id_lote' => $res->idLote,
                                    'Id_analisis' => $model->Id_solicitud,
                                    'Id_codigo' => $model->Id_codigo,
                                    'Id_parametro' => $model->Id_parametro,
                                    'Id_control' => 1,
                                    'Analizo' => 1,
                                    'Liberado' => 0,
                                    'Vol_muestra' => 15,
                                ]);
                                $tempModel = LoteDetalleDirectos::where('Id_lote', $res->idLote)->get();
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
                        case 87:
                            $model = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $res->idLote)->get();  
                            break;
                        default:
                        $model = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $res->idLote)->where('Liberado',0)->get();
                        // $model = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $res->idLote)->get();
                        break;
                    }
                    // $model = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $res->idLote)->where('Liberado',0)->get();
                    $model = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $res->idLote)->get();
                    break;
                case 13: // G&A
                    // $model = DB::table('ViewLoteDetalleGA')->where('Id_lote', $res->idLote)->where('Liberado',0)->get();
                    $model = DB::table('ViewLoteDetalleGA')->where('Id_lote', $res->idLote)->get();
                    break;
                case 15: // Solidos
                     $model = DB::table('ViewLoteDetalleSolidos')->where('Id_lote', $res->idLote)->get();
                    // $model = DB::table('ViewLoteDetalleSolidos')->where('Id_lote', $res->idLote)->where('Liberado',0)->get();
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
                            $model = DB::table('ViewLoteDetalleNitrogeno')->where('Id_lote', $res->idLote)->where('Liberado',0)->get();
                            // $model = DB::table('ViewLoteDetalleNitrogeno')->where('Id_lote', $res->idLote)->get();
                            break;
                        case 28://Alcalinidad
                        case 29:
                        case 30:
                        case 27:
                            $model = DB::table('ViewLoteDetalleAlcalinidad')->where('Id_lote', $res->idLote)->where('Liberado',0)->get();
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
                            // $model = DB::table('ViewLoteDetalleEnterococos')->where('Id_lote', $res->idLote)->where('Liberado',0)->get();
                            $model = DB::table('ViewLoteDetalleEnterococos')->where('Id_lote', $res->idLote)->get();
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
        $phCampo = "";
        $masa = array();
        $conductividad = "";

        $lote = LoteAnalisis::where('Id_lote', $res->idLote)->get();
        if ($lote->count()) {
            switch ($lote[0]->Id_area) {
                case 16: // Espectrofotometria
                case 5: // Fisicoquimicos
                    $fecha = new Carbon($lote[0]->Fecha);
                    $today = $fecha->toDateString();
                    $model = DB::table("ViewLoteDetalleEspectro")->where('Id_detalle', $res->id)->first();
                    $curva = CurvaConstantes::whereDate('Fecha_inicio', '<=', $today)->whereDate('Fecha_fin', '>=', $today)
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
                            // $dif1 = CodigoParametros::where('Id_solicitud',$model->Id_analisis)->where('Id_parametro', 90)->first();
                            $dif1 = LoteDetalleSolidos::where('Id_analisis',$model->Id_analisis)->where('Id_control',$model->Id_control)->where('Id_parametro',90)->first();
                            $nom2 = "SST";
                            // $dif2 = CodigoParametros::where('Id_solicitud',$model->Id_analisis)->where('Id_parametro', 4)->first();
                            $dif2 = LoteDetalleSolidos::where('Id_analisis',$model->Id_analisis)->where('Id_control',$model->Id_control)->where('Id_parametro',4)->first();
                            $model2 = ConductividadMuestra::where('Id_solicitud',$dif1->Id_analisis)->get();
                            if($model2->count() <= 0) {
                                $conductividad = "no exist";
                            } else {
                                if ($model2->count() > 1){
                                    $aux =  0;
                                    $cont = 0;
                                    foreach ($model2 as $item){
                                        $aux = $aux + $item->Promedio;
                                        $cont++;
                                    }
                                    $promedio = $aux / $cont;
                                    $conductividad = $promedio;
                                 } else {
                                     $conductividad = $model2[0]->Promedio;
                                 }
                            }
                           
                            break;
                        case 44: // SDV
                            $nom1 = "STV";
                            // $dif1 = CodigoParametros::where('Id_solicitud',$model->Id_analisis)->where('Id_parametro', 49)->first();
                            $dif1 = LoteDetalleSolidos::where('Id_analisis',$model->Id_analisis)->where('Id_control',$model->Id_control)->where('Id_parametro',48)->first();
                            $nom2 = "SSV";
                            // $dif2 = CodigoParametros::where('Id_solicitud',$model->Id_analisis)->where('Id_parametro', 47)->first();
                            $dif2 = LoteDetalleSolidos::where('Id_analisis',$model->Id_analisis)->where('Id_control',$model->Id_control)->where('Id_parametro',46)->first();
                            break;
                        case 43: // SDF
                            $nom1 = "SDT";
                            // $dif1 = CodigoParametros::where('Id_solicitud',$model->Id_analisis)->where('Id_parametro', 89)->first();
                            $dif1 = LoteDetalleSolidos::where('Id_analisis',$model->Id_analisis)->where('Id_control',$model->Id_control)->where('Id_parametro',88)->first();
                            $nom2 = "SDV";
                            // $dif2 = CodigoParametros::where('Id_solicitud',$model->Id_analisis)->where('Id_parametro', 45)->first();
                            $dif2 = LoteDetalleSolidos::where('Id_analisis',$model->Id_analisis)->where('Id_control',$model->Id_control)->where('Id_parametro',44)->first();
                            break;
                        case 45: // SSF
                            $nom1 = "SST";
                            // $dif1 = CodigoParametros::where('Id_solicitud',$model->Id_analisis)->where('Id_parametro', 93)->first();
                            $dif1 = LoteDetalleSolidos::where('Id_analisis',$model->Id_analisis)->where('Id_control',$model->Id_control)->where('Id_parametro',4)->first();
                            $nom2 = "SSV";
                            // $dif2 = CodigoParametros::where('Id_solicitud',$model->Id_analisis)->where('Id_parametro', 47)->first();
                            $dif2 = LoteDetalleSolidos::where('Id_analisis',$model->Id_analisis)->where('Id_control',$model->Id_control)->where('Id_parametro',46)->first();
                            break;
                        case 47: // STF
                            $nom1 = "ST";
                            // $dif1 = CodigoParametros::where('Id_solicitud',$model->Id_analisis)->where('Id_parametro', 91)->first();
                            $dif1 = LoteDetalleSolidos::where('Id_analisis',$model->Id_analisis)->where('Id_control',$model->Id_control)->where('Id_parametro',90)->first();
                            $nom2 = "STV";
                            // $dif2 = CodigoParametros::where('Id_solicitud',$model->Id_analisis)->where('Id_parametro', 49)->first();
                            $dif2 = LoteDetalleSolidos::where('Id_analisis',$model->Id_analisis)->where('Id_control',$model->Id_control)->where('Id_parametro',48)->first();
                            break;
                        case 3: // SS
                            // $dif1 = DB::table("ViewLoteDetalleSolidos")->where("Folio_servicio", $model->Folio_servicio)->where('Id_parametro', 3)->first();
                            break;
                        case 46:
                            $model2 = ConductividadMuestra::where('Id_solicitud',$model->Id_analisis)->get();
                            $dif1 = DB::table('ViewLoteDetalleSolidos')->where('Id_parametro',4)->where('Id_control',$model->Id_control)->where('Id_analisis',$model->Id_analisis)->first(); // Asi se hara con las otras
                                if($model2->count() <= 0) {
                                    $conductividad = "no exist";
                                } else {
                                    if ($model2->count() > 1){
                                        $aux =  0;
                                        $cont = 0;
                                        foreach ($model2 as $item){
                                            $aux = $aux + $item->Promedio;
                                            $cont++;
                                        }
                                        $promedio = $aux / $cont;
                                        $conductividad = $promedio;
                                     } else {
                                         $conductividad = $model2[0]->Promedio;
                                     }
                                }
                                $dif2 = "Sin datos";
                                $nom1 = 'sin nombre';
                                $nom2 = 'sin nombre';
                            break;
                        case 48:
                            $model2 = ConductividadMuestra::where('Id_solicitud',$model->Id_analisis)->get();
                            $dif1 = DB::table('ViewLoteDetalleSolidos')->where('Id_parametro',90)->where('Id_control',$model->Id_control)->where('Id_analisis',$model->Id_analisis)->first(); // Asi se hara con las otras
                                if($model2->count() <= 0) {
                                    $conductividad = "no exist";
                                } else {
                                    if ($model2->count() > 1){
                                        $aux =  0;
                                        $cont = 0;
                                        foreach ($model2 as $item){
                                            $aux = $aux + $item->Promedio;
                                            $cont++;
                                        }
                                        $promedio = $aux / $cont;
                                        $conductividad = $promedio;
                                     } else {
                                         $conductividad = $model2[0]->Promedio;
                                     }
                                } 
                                $dif2 = "Sin datos";
                                $nom1 = 'sin nombre';
                                $nom2 = 'sin nombre';
                            break;
                        default:
                        $model2 = ConductividadMuestra::where('Id_solicitud',$model->Id_analisis)->get();
                            if($model2->count() <= 0) {
                                $conductividad = "no exist";
                            } else {
                                if ($model2->count() > 1){
                                    $aux =  0;
                                    $cont = 0;
                                    foreach ($model2 as $item){
                                        $aux = $aux + $item->Promedio;
                                        $cont++;
                                    }
                                    $promedio = $aux / $cont;
                                    $conductividad = $promedio;
                                 } else {
                                     $conductividad = $model2[0]->Promedio;
                                 }
                            }
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
                        case 161:
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
                        case 28:
                        case 29:
                        case 27:
                            $model = LoteDetalleAlcalinidad::where("Id_detalle", $res->id)->first();
                            $temp = LoteAnalisis::where('Id_lote',$model->Id_lote)->first();
                            $valoracion = ValoracionAlcalinidad::whereDate('Fecha_inicio','<=',$temp->Fecha)
                            ->whereDate('Fecha_fin','>=',$temp->Fecha)
                            ->where('Id_parametro',$temp->Id_tecnica)->first();
                            break;
                        case 30:
                            $model = LoteDetalleAlcalinidad::where("Id_detalle", $res->id)->first();
                            $dif1 = LoteDetalleAlcalinidad::where('Id_parametro',28)->where('Id_analisis',$model->Id_analisis)->where('Id_control',$model->Id_control)->first();
                            $dif2 = LoteDetalleAlcalinidad::where('Id_parametro',29)->where('Id_analisis',$model->Id_analisis)->where('Id_control',$model->Id_control)->first();
                            break;
                        default: // Default Directos
                            // tab += '<td><input hidden id="idMuestra' + item.Id_detalle + '" value="' + item.Id_detalle + '"><button '+status+' type="button" class="btn btn-'+color+'" onclick="getDetalleMuestra(' + item.Id_detalle + ',1);" data-toggle="modal" data-target="#modalCapturaSolidos">Capturar</button>';
                            break;
                    }
                    break;
                case 7: //Campo
                case 19: //Directo
                    switch ($lote[0]->Id_tecnica) {
                        case 130:
                            $model = DB::table("ViewLoteDetalleDirectos")->where('Id_detalle', $res->id)->first();
                            $model2 = LoteDetalleDureza::where('Id_analisis',$model->Id_analisis)
                                ->where('Id_control',$model->Id_control)->where('Id_parametro',251)->first();
                            break;                        
                        case 261:
                            $model = DB::table("ViewLoteDetalleDirectos")->where('Id_detalle', $res->id)->first();
                            $model2 = LoteDetalleDureza::where('Id_analisis',$model->Id_analisis)
                                ->where('Id_control',$model->Id_control)->where('Id_parametro',252)->first();
                            break;
                        case 14: 
                            $model = DB::table("ViewLoteDetalleDirectos")->where('Id_detalle', $res->id)->first();
                            $model2 = PhMuestra::where('Id_solicitud', $model->Id_analisis)->get();
                            if ($model2->count() > 1){
                                $model3 =  CampoCompuesto::where('Id_solicitud' , $model->Id_analisis)->first();
                             $phCampo = $model3->Ph_muestraComp;
                            } else if ($model2->count() <= 0) {
                                $phcampo = "Remitida";
                            }
                            else {
                             $phCampo = $model2[0]->Promedio;
                            }
                            break;
                        default:
                        $model = DB::table("ViewLoteDetalleDirectos")->where('Id_detalle', $res->id)->first();
                            break;
                    }
                    break;
                case 8: //Potable
                    switch ($lote[0]->Id_tecnica) {
                        case 77: //Dureza 
                        case 103:
                        case 251:
                            $model2 = LoteDetalleDureza::where("Id_detalle", $res->id)->first();
                            $valoracion = ValoracionDureza::where('Id_lote', $model2->Id_lote)->first();
                            break;
                        case 252:
                            
                            $model2 = LoteDetalleDureza::where("Id_detalle", $res->id)->first();
                            $solModel = Solicitud::where('Id_solicitud',$model2->Id_analisis)->first();
                            $temp = LoteDetalleDureza::where('Id_analisis',$model2->Id_analisis)->where('Id_control',$model2->Id_control)->where('Id_parametro',103)->get();
                            if ($temp->count()) {
                                $d1 = LoteDetalleDureza::where('Id_analisis',$model2->Id_analisis)
                                ->where('Id_control',$model2->Id_control)->where('Id_parametro',251)->first();
                                $d2 = LoteDetalleDureza::where('Id_analisis',$model2->Id_analisis)
                                ->where('Id_control',$model2->Id_control)->where('Id_parametro',103)->first();   
                            }else{
                                $d1 = LoteDetalleDureza::where('Id_analisis',$model2->Id_analisis)
                                ->where('Id_control',$model2->Id_control)->where('Id_parametro',251)->first();
                                $d2 = LoteDetalleDureza::where('Id_analisis',$model2->Id_analisis)
                                ->where('Id_control',$model2->Id_control)->where('Id_parametro',77)->first();
                            }
                            // switch ($solModel->Id_norma) {
                            //     case 5:
                            //     case 30:
                            //         $d1 = LoteDetalleDureza::where('Id_analisis',$model2->Id_analisis)
                            //             ->where('Id_control',$model2->Id_control)->where('Id_parametro',251)->first();
                            //         $d2 = LoteDetalleDureza::where('Id_analisis',$model2->Id_analisis)
                            //             ->where('Id_control',$model2->Id_control)->where('Id_parametro',103)->first();       
                            //         break;
                            //     default:
                            //         $d1 = LoteDetalleDureza::where('Id_analisis',$model2->Id_analisis)
                            //             ->where('Id_control',$model2->Id_control)->where('Id_parametro',251)->first();
                            //         $d2 = LoteDetalleDureza::where('Id_analisis',$model2->Id_analisis)
                            //             ->where('Id_control',$model2->Id_control)->where('Id_parametro',77)->first();
                            //         break;
                            // }
                            
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
            'phCampo' => $phCampo,
            'masa' => $masa,
            'conductividad' => $conductividad,
        );
        return response()->json($data);
    }
    public function setDetalleMuestra(Request $res)
    {
        $r2 = 0;
        $std = true;
        $tipo = 0;
        $resultado = 0;
        $aux = array();
        $model = array();
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
                                    break;
                            }
                            $model = LoteDetalleEspectro::find($res->idMuestra);
                            $model->Resultado = $resultado;
                            $model->Abs1 = $res->X;
                            $model->Abs2 = $res->Y;
                            $model->Abs3 = $res->Z;
                            $model->B = $res->CB;
                            $model->M = $res->CM;
                            $model->R = $res->CR;
                            $model->Promedio = number_format($promedio,3,'.','');
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
                            //$model->Vol_dilucion = round($d,3);
                            $model->Vol_dilucion = $r2;
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
                            $x = round((($res->X + $res->Y + $res->Z) / 3),3);
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
                            $resultado = ((round($x,3) - $res->CB) / $res->CM) * round($d,3);

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
                            $xround = round($x,3);
                            $resultado = (($xround - $res->CB) / $res->CM) * round($d,3);

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

                        case 113:
                            // Sulfatos Residual
                            $x = ($res->X + $res->Y + $res->Z + $res->ABS4 + $res->ABS5 + $res->ABS6 + $res->ABS7 + $res->ABS8) / 8;
                            $d =   100  / $res->E;
                            $res1 = round($x, 3) - ($res->CB);
                            $res2 = $res1 / $res->CM;
                            $resultado = round($res2,4) * round($d, 3);

                            $model = LoteDetalleEspectro::find($res->idMuestra);
                            $model->Resultado = round($resultado,3);
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
                            $resultado = round($res2,3) * round($d, 3);

                            $model = LoteDetalleEspectro::find($res->idMuestra);
                            $model->Resultado = round($resultado,3);
                            $model->Abs1 = $res->X;
                            $model->Abs2 = $res->Y;
                            $model->Abs3 = $res->Z;
                            $model->Abs4 = $res->X2;
                            $model->Abs5 = $res->Y2;
                            $model->Abs6 = $res->Z2;
                            $model->Abs7 = round($prom1,3);
                            $model->Abs8 = round($prom2,3);
                            $model->B = $res->CB;
                            $model->M = $res->CM;
                            $model->R = $res->CR;
                            $model->Promedio = round($x,3);
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
                        case 80: //floruros
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
                        $aux = $matraz;
                        if ($matraz->count()) {                
                            regresar:            
                            $mat = rand(0, $matraz->count());
                            $valMatraz = LoteDetalleGA::where('Id_matraz',$matraz[$mat]->Id_matraz)->where('Id_lote',$res->idLote)->get();
                            if ($valMatraz->count()) {
                                goto regresar;
                            }else{

                                $matraz[$mat]->Estado = 1;
                                $matraz[$mat]->save();
                            }
                         

                        } else {
                            $std = false;
                        }

                        //$m3 = mt_rand($matraz->Min, $matraz->Max);
                        $dif = ($matraz[$mat]->Max - $matraz[$mat]->Min);
                        $ran = (round($dif, 4)) / 10;
                        $m3 = $matraz[$mat]->Max - $ran;

                        $mf = ((($res->R / $res->E) * $res->I) + $m3);
                        
                                                
                        $numeroAleatorio = rand(1, 2); // Genera un número entre 1 y 5
                        $valRandom = number_format($numeroAleatorio / 10000,4); // Divide por 1000 para obtener el rango deseado
                        $m2 = ($m3 + $valRandom);

                        $numeroAleatorio = rand(1, 3); // Genera un número entre 1 y 5
                        $valRandom = number_format($numeroAleatorio / 10000,4); // Divide por 1000 para obtener el rango deseado
                        $m1 = ($m2 + $valRandom);


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
                            $model->Observacion = $res->obs;
                            $model->save();
                            $resultado = $res->resultado;
                            break;
                        case 47: // Por diferencia
                        case 88:
                        case 44:
                        case 45:
                        case 43:
                            $model = LoteDetalleSolidos::find($res->idMuestra);
                            $model->Resultado = $res->resultado;
                            $model->Masa1 = $res->val1;
                            $model->Masa2 = $res->val2;
                            $model->Observacion = $res->obs;
                            $model->Analizo = Auth::user()->id;
                            $model->save();
                            $resultado = $res->resultado;
                            break;
                        case 4:
                            if ($res->R != "") {

                                $modelCrisol = CrisolesGA::where('Estado',0)->get();
                                $aux = $modelCrisol;

                                if ($modelCrisol->count()) {
                                    $mat = rand(0, $modelCrisol->count());
                                    $valCrisol = LoteDetalleSolidos::where('Id_crisol',$modelCrisol[$mat]->Id_matraz)->where('Id_lote',$res->idLote)->get();
                                    if ($valCrisol->count()) {
                                        goto regresar;
                                    }else{
        
                                        $modelCrisol[$mat]->Estado = 1;
                                        $modelCrisol[$mat]->save();
                                    }
                                }else{
                                    $std = false;
                                }

                                $mf = ((($res->R / $res->factor) * $res->volumen) + round($modelCrisol[$mat]->Peso,4));
                                $auxMf =  (((round($mf,4) - round($modelCrisol[$mat]->Peso,4)) / $res->volumen) * $res->factor);
                                $resultado = $auxMf;

                                $numeroAleatorio = rand(0, 3); // Genera un número entre 1 y 5
                                $valRandomPm1 = number_format($numeroAleatorio / 10000,4); // Divide por 1000 para obtener el rango deseado
                                $numeroAleatorio = rand(0, 3); // Genera un número entre 1 y 5
                                $valRandomPm2 = number_format($numeroAleatorio / 10000,4); // Divide por 1000 para obtener el rango deseado
                                $numeroAleatorio = rand(0, 3); // Genera un número entre 1 y 5
                                $valRandomPc1 = number_format($numeroAleatorio / 10000,4); // Divide por 1000 para obtener el rango deseado
                                $numeroAleatorio = rand(0, 3); // Genera un número entre 1 y 5
                                $valRandomPc2 = number_format($numeroAleatorio / 10000,4); // Divide por 1000 para obtener el rango deseado

                                $model = LoteDetalleSolidos::find($res->idMuestra);
                                $model->Id_crisol = $modelCrisol[$mat]->Id_crisol;
                                $model->Crisol = $modelCrisol[$mat]->Num_serie;
                                $model->Masa1 = round($modelCrisol[$mat]->Peso,4);
                                $model->Masa2 = round($mf,4);
                                $model->Peso_muestra1 = round(($modelCrisol[$mat]->Peso + $valRandomPm1),4);
                                $model->Peso_muestra2 = round(($modelCrisol[$mat]->Peso + $valRandomPm2),4);
                                $model->Peso_constante1 = round(($mf + $valRandomPc1),4);
                                $model->Peso_constante2 = round(($mf + $valRandomPc2),4);
                                $model->Vol_muestra = $res->volumen;
                                $model->Factor_conversion = $res->factor;
                                $model->Resultado = $resultado;
                                $model->Analizo = Auth::user()->id;
                                $model->Observacion = $res->obs;
                                $model->save();
                            } else { //operacion larga
                                $res1 = $res->masa2 - $res->masa1;
                                $res2 = $res1 / $res->volumen;
                                $resultado = $res2 * $res->factor;

                                $model = LoteDetalleSolidos::find($res->idMuestra);
                                $model->Crisol = $res->crisol;
                                $model->Masa1 = $res->masa1;
                                $model->Masa2 = $res->masa2;
                                $model->Peso_muestra1 = $res->pesoC1;
                                $model->Peso_muestra2 = $res->pesoC2;
                                $model->Peso_constante1 = $res->pesoConMuestra1;
                                $model->Peso_constante2 = $res->pesoConMuestra2;
                                $model->Vol_muestra = $res->volumen;
                                $model->Factor_conversion = $res->factor;
                                $model->Resultado = $resultado;
                                $model->Observacion = $res->obs;
                                $model->Analizo = Auth::user()->id;
                                $model->save();
                            }
                            break;
                        case 90:
                            if ($res->R != "") {

                                $modelCrisol = Capsulas::where('Estado',0)->get();
                                $aux = $modelCrisol;

                                if ($modelCrisol->count()) {
                                    $mat = rand(0, $modelCrisol->count());
                                    $valCrisol = LoteDetalleSolidos::where('Id_crisol',$modelCrisol[$mat]->Id_capsula)->where('Id_lote',$res->idLote)->get();
                                    if ($valCrisol->count()) {
                                        goto regresar;
                                    }else{
        
                                        $modelCrisol[$mat]->Estado = 1;
                                        $modelCrisol[$mat]->save();
                                    }
                                }else{
                                    $std = false;
                                }

                                $mf = ((($res->R / $res->factor) * $res->volumen) + round($modelCrisol[$mat]->Peso,4));
                                $auxMf =  (((round($mf,4) - round($modelCrisol[$mat]->Peso,4)) / $res->volumen) * $res->factor);
                                $resultado = $auxMf;

                                $numeroAleatorio = rand(0, 3); // Genera un número entre 1 y 5
                                $valRandomPm1 = number_format($numeroAleatorio / 10000,4); // Divide por 1000 para obtener el rango deseado
                                $numeroAleatorio = rand(0, 3); // Genera un número entre 1 y 5
                                $valRandomPm2 = number_format($numeroAleatorio / 10000,4); // Divide por 1000 para obtener el rango deseado
                                $numeroAleatorio = rand(0, 3); // Genera un número entre 1 y 5
                                $valRandomPc1 = number_format($numeroAleatorio / 10000,4); // Divide por 1000 para obtener el rango deseado
                                $numeroAleatorio = rand(0, 3); // Genera un número entre 1 y 5
                                $valRandomPc2 = number_format($numeroAleatorio / 10000,4); // Divide por 1000 para obtener el rango deseado

                                $model = LoteDetalleSolidos::find($res->idMuestra);
                                $model->Id_crisol = $modelCrisol[$mat]->Id_crisol;
                                $model->Crisol = $modelCrisol[$mat]->Num_serie;
                                $model->Masa1 = round($modelCrisol[$mat]->Peso,4);
                                $model->Masa2 = round($mf,4);
                                $model->Peso_muestra1 = round(($modelCrisol[$mat]->Peso + $valRandomPm1),4);
                                $model->Peso_muestra2 = round(($modelCrisol[$mat]->Peso + $valRandomPm2),4);
                                $model->Peso_constante1 = round(($mf + $valRandomPc1),4);
                                $model->Peso_constante2 = round(($mf + $valRandomPc2),4);
                                $model->Vol_muestra = $res->volumen;
                                $model->Factor_conversion = $res->factor;
                                $model->Resultado = $resultado;
                                $model->Analizo = Auth::user()->id;
                                $model->Observacion = $res->obs;
                                $model->save();
                            } else { //operacion larga
                                $res1 = $res->masa2 - $res->masa1;
                                $res2 = $res1 / $res->volumen;
                                $resultado = $res2 * $res->factor;

                                $model = LoteDetalleSolidos::find($res->idMuestra);
                                $model->Crisol = $res->crisol;
                                $model->Masa1 = $res->masa1;
                                $model->Masa2 = $res->masa2;
                                $model->Peso_muestra1 = $res->pesoC1;
                                $model->Peso_muestra2 = $res->pesoC2;
                                $model->Peso_constante1 = $res->pesoConMuestra1;
                                $model->Peso_constante2 = $res->pesoConMuestra2;
                                $model->Vol_muestra = $res->volumen;
                                $model->Factor_conversion = $res->factor;
                                $model->Resultado = $resultado;
                                $model->Analizo = Auth::user()->id;
                                $model->Observacion = $res->obs;
                                $model->save();
                            }
                            break;
                        case 46:
                            if ($res->R != "") {

                                $modelCrisol = CrisolesGA::where('Estado',0)->get();
                                $aux = $modelCrisol;

                                if ($modelCrisol->count()) {
                                    $mat = rand(0, $modelCrisol->count());
                                    $valCrisol = LoteDetalleSolidos::where('Id_crisol',$modelCrisol[$mat]->Id_matraz)->where('Id_lote',$res->idLote)->get();
                                    if ($valCrisol->count()) {
                                        goto regresar;
                                    }else{
        
                                        $modelCrisol[$mat]->Estado = 1;
                                        $modelCrisol[$mat]->save();
                                    }
                                }else{
                                    $std = false;
                                }

                                $mf = ((($res->R / $res->factor) * $res->volumen) + round($modelCrisol[$mat]->Peso,4));
                                $auxMf =  (((round($mf,4) - round($modelCrisol[$mat]->Peso,4)) / $res->volumen) * $res->factor);
                                $resultado = $auxMf;

                                $numeroAleatorio = rand(0, 3); // Genera un número entre 1 y 5
                                $valRandomPm1 = number_format($numeroAleatorio / 10000,4); // Divide por 1000 para obtener el rango deseado
                                $numeroAleatorio = rand(0, 3); // Genera un número entre 1 y 5
                                $valRandomPm2 = number_format($numeroAleatorio / 10000,4); // Divide por 1000 para obtener el rango deseado
                                $numeroAleatorio = rand(0, 3); // Genera un número entre 1 y 5
                                $valRandomPc1 = number_format($numeroAleatorio / 10000,4); // Divide por 1000 para obtener el rango deseado
                                $numeroAleatorio = rand(0, 3); // Genera un número entre 1 y 5
                                $valRandomPc2 = number_format($numeroAleatorio / 10000,4); // Divide por 1000 para obtener el rango deseado

                                $model = LoteDetalleSolidos::find($res->idMuestra);
                                $model->Id_crisol = $modelCrisol[$mat]->Id_crisol;
                                $model->Crisol = $modelCrisol[$mat]->Num_serie;
                                $model->Masa1 = round($modelCrisol[$mat]->Peso,4);
                                $model->Masa2 = round($mf,4);
                                $model->Peso_muestra1 = round(($modelCrisol[$mat]->Peso + $valRandomPm1),4);
                                $model->Peso_muestra2 = round(($modelCrisol[$mat]->Peso + $valRandomPm2),4);
                                $model->Peso_constante1 = round(($mf + $valRandomPc1),4);
                                $model->Peso_constante2 = round(($mf + $valRandomPc2),4);
                                $model->Vol_muestra = $res->volumen;
                                $model->Factor_conversion = $res->factor;
                                $model->Resultado = $resultado;
                                $model->Analizo = Auth::user()->id;
                                $model->Observacion = $res->obs;
                                $model->save();
                            } else { //operacion larga
                                $res1 = $res->masa2 - $res->masa1;
                                $res2 = $res1 / $res->volumen;
                                $resultado = $res2 * $res->factor;

                                $model = LoteDetalleSolidos::find($res->idMuestra);
                                $model->Crisol = $res->crisol;
                                $model->Masa1 = $res->masa1;
                                $model->Masa2 = $res->masa2;
                                $model->Peso_muestra1 = $res->pesoC1;
                                $model->Peso_muestra2 = $res->pesoC2;
                                $model->Peso_constante1 = $res->pesoConMuestra1;
                                $model->Peso_constante2 = $res->pesoConMuestra2;
                                $model->Vol_muestra = $res->volumen;
                                $model->Factor_conversion = $res->factor;
                                $model->Resultado = $resultado;
                                $model->Analizo = Auth::user()->id;
                                $model->Observacion = $res->obs;
                                $model->save();
                            }
                            break;
                        case 48:
                         
                              $res1 = $res->masa2 - $res->masa1;
                                $res2 = $res1 / $res->volumen;
                                $resultado = $res2 * $res->factor;

                                $model = LoteDetalleSolidos::find($res->idMuestra);
                                $model->Crisol = $res->crisol;
                                $model->Masa1 = $res->masa1;
                                $model->Masa2 = $res->masa2;
                                $model->Peso_muestra1 = $res->pesoC1;
                                $model->Peso_muestra2 = $res->pesoC2;
                                $model->Peso_constante1 = $res->pesoConMuestra1;
                                $model->Peso_constante2 = $res->pesoConMuestra2;
                                $model->Vol_muestra = $res->volumen;
                                $model->Factor_conversion = $res->factor;
                                $model->Resultado = $resultado;
                                $model->Analizo = Auth::user()->id;
                                $model->Observacion = $res->obs;
                                $model->save();
                        break;
                        
                        default: // Default
                            if ($res->R != "") {

                                $modelCrisol = CrisolesGA::where('Estado',0)->get();
                                $aux = $modelCrisol;

                                if ($modelCrisol->count()) {
                                    $mat = rand(0, $modelCrisol->count());
                                    $valCrisol = LoteDetalleSolidos::where('Id_crisol',$modelCrisol[$mat]->Id_matraz)->where('Id_lote',$res->idLote)->get();
                                    if ($valCrisol->count()) {
                                        goto regresar;
                                    }else{
        
                                        $modelCrisol[$mat]->Estado = 1;
                                        $modelCrisol[$mat]->save();
                                    }
                                }else{
                                    $std = false;
                                }

                                $mf = ((($res->R / $res->factor) * $res->volumen) + round($modelCrisol[$mat]->Peso,4));
                                $auxMf =  (((round($mf,4) - round($modelCrisol[$mat]->Peso,4)) / $res->volumen) * $res->factor);
                                $resultado = $auxMf;

                                $numeroAleatorio = rand(0, 3); // Genera un número entre 1 y 5
                                $valRandomPm1 = number_format($numeroAleatorio / 10000,4); // Divide por 1000 para obtener el rango deseado
                                $numeroAleatorio = rand(0, 3); // Genera un número entre 1 y 5
                                $valRandomPm2 = number_format($numeroAleatorio / 10000,4); // Divide por 1000 para obtener el rango deseado
                                $numeroAleatorio = rand(0, 3); // Genera un número entre 1 y 5
                                $valRandomPc1 = number_format($numeroAleatorio / 10000,4); // Divide por 1000 para obtener el rango deseado
                                $numeroAleatorio = rand(0, 3); // Genera un número entre 1 y 5
                                $valRandomPc2 = number_format($numeroAleatorio / 10000,4); // Divide por 1000 para obtener el rango deseado

                                $model = LoteDetalleSolidos::find($res->idMuestra);
                                $model->Id_crisol = $modelCrisol[$mat]->Id_crisol;
                                $model->Crisol = $modelCrisol[$mat]->Num_serie;
                                $model->Masa1 = round($modelCrisol[$mat]->Peso,4);
                                $model->Masa2 = round($mf,4);
                                $model->Peso_muestra1 = round(($modelCrisol[$mat]->Peso + $valRandomPm1),4);
                                $model->Peso_muestra2 = round(($modelCrisol[$mat]->Peso + $valRandomPm2),4);
                                $model->Peso_constante1 = round(($mf + $valRandomPc1),4);
                                $model->Peso_constante2 = round(($mf + $valRandomPc2),4);
                                $model->Vol_muestra = $res->volumen;
                                $model->Factor_conversion = $res->factor;
                                $model->Resultado = $resultado;
                                $model->Analizo = Auth::user()->id;
                                $model->Observacion = $res->obs;
                                $model->save();
                            } else { //operacion larga
                                $res1 = $res->masa2 - $res->masa1;
                                $res2 = $res1 / $res->volumen;
                                $resultado = $res2 * $res->factor;

                                $model = LoteDetalleSolidos::find($res->idMuestra);
                                $model->Crisol = $res->crisol;
                                $model->Masa1 = $res->masa1;
                                $model->Masa2 = $res->masa2;
                                $model->Peso_muestra1 = $res->pesoC1;
                                $model->Peso_muestra2 = $res->pesoC2;
                                $model->Peso_constante1 = $res->pesoConMuestra1;
                                $model->Peso_constante2 = $res->pesoConMuestra2;
                                $model->Vol_muestra = $res->volumen;
                                $model->Factor_conversion = $res->factor;
                                $model->Resultado = $resultado;
                                $model->Analizo = Auth::user()->id;
                                $model->Observacion = $res->obs;
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
                            $model->Resultado = $resultado;
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
                        case 28:
                        case 29:
                            $resultado = (($res->A * $res->B) * $res->C) / $res->D;

                            $model = LoteDetalleAlcalinidad::find($res->idMuestra);
                            $model->Titulados = $res->A; 
                            $model->Ph_muestra = $res->E; 
                            $model->Vol_muestra = $res->D; 
                            $model->Normalidad = $res->B; 
                            $model->Factor_conversion = $res->C; 
                            $model->Resultado = number_format($resultado,2);
                            $model->analizo = Auth::user()->id;
                            $model->save();
                            break; 
                        case 27:
                            $resultado = (($res->A * $res->B) * $res->C) / $res->D;

                            $model = LoteDetalleAlcalinidad::find($res->idMuestra);
                            $model->Titulados = $res->A; 
                            $model->Ph_muestra = $res->E; 
                            $model->Vol_muestra = $res->D; 
                            $model->Normalidad = $res->B; 
                            $model->Factor_conversion = $res->C; 
                            $model->Resultado = number_format($resultado,2);
                            $model->analizo = Auth::user()->id;
                            $model->save();
                            break;
                        case 30:
                            $resultado = $res->A + $res->B;

                            $model = LoteDetalleAlcalinidad::find($res->idMuestra);
                            $model->Titulados = $res->A; 
                            $model->Normalidad = $res->B; 
                            $model->Resultado = number_format($resultado,2,'.','');
                            $model->analizo = Auth::user()->id;
                            $model->save();
                            break;
                        default:
                            # code...
                            break;
                    }
                    break;
                case 8:
                    switch ($lote[0]->Id_tecnica) {
                        case 77:
                        case 251:

                            $resultado = round((($res->edta1 * $res->conversion1 * $res->real1) / $res->vol1),4);
                            $model = LoteDetalleDureza::find($res->idMuestra);
                            $model->EdtaVal1 = $res->edta1;
                            $model->Ph_muestraVal1 = $res->ph1; 
                            $model->Vol_muestraVal1 = $res->vol1;
                            $model->Factor_realVal1 = $res->real1;
                            $model->Factor_conversionVal1 = $res->conversion1;
                            $model->ResultadoVal1 = number_format($resultado,2,'.','');
                            $model->Resultado = number_format($resultado,2,'.','');
                            $model->save();
                            break;
                        case 103:
                            $resultado1 = round((round(($res->edta1 * $res->conversion1 * $res->real1),4) / $res->vol1),4);
                            $resultado2 = round((round(($res->edta2 * $res->conversion2 * $res->real2),4) / $res->vol2),4);
                            $resultado3 = round((round(($res->edta3 * $res->conversion3 * $res->real3),4) / $res->vol3),4);
                            $promEdta = ($res->edta1 + $res->edta2 + $res->edta3) / 3;
                            $resultado = round((round($promEdta,2,PHP_ROUND_HALF_UP) * $res->real1 * $res->conversion1)  / $res->vol1 , 4);
                            
                            $model = LoteDetalleDureza::find($res->idMuestra);
                            $model->EdtaVal1 = $res->edta1;
                            $model->Ph_muestraVal1 = $res->ph1; 
                            $model->Vol_muestraVal1 = $res->vol1;
                            $model->Factor_realVal1 = $res->real1;
                            $model->Factor_conversionVal1 = $res->conversion1;
                            $model->ResultadoVal1 = $resultado1;

                            $model->EdtaVal2 = $res->edta2;
                            $model->Ph_muestraVal2 = $res->ph2; 
                            $model->Vol_muestraVal2 = $res->vol2;
                            $model->Factor_realVal2 = $res->real2;
                            $model->Factor_conversionVal2 = $res->conversion2;
                            $model->ResultadoVal2 = $resultado2;

                            $model->EdtaVal3 = $res->edta3;
                            $model->Ph_muestraVal3 = $res->ph3; 
                            $model->Vol_muestraVal3 = $res->vol3;
                            $model->Factor_realVal3 = $res->real3;
                            $model->Factor_conversionVal3 = $res->conversion3;
                            $model->ResultadoVal3 = $resultado3;
                            

                            $model->Resultado = $resultado;
                            $model->save();
                            break;
                        case 252:
                            $resultado = $res->durezaT - $res->durezaC; 
                            $model = LoteDetalleDureza::find($res->idMuestra);
                            $model->Lectura1 = $res->durezaT;
                            $model->Lectura2 = $res->durezaC; 
                            $model->Resultado = number_format($resultado,2,'.','');
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
                        case 5:
                        case 71:
                            $temp = LoteDetalleDbo::where('Id_detalle',$res->idMuestra)->first();
                            switch ($temp->Id_control) {
                                case 5: 
                                    $resultado = $res->OIB - $res->OFB;
                                    $d = 300 / $res->VB;
                                    $resultado = round($resultado / $d,2);
                                    $model = LoteDetalleDbo::find($res->idMuestra);
                                    $model->Botella_final = $res->H;
                                    $model->Botella_od = $res->G;
                                    $model->Odf = $res->OFB;
                                    $model->Odi = $res->OIB;
                                    $model->Ph_final = $res->J;
                                    $model->Ph_inicial = $res->I;
                                    $model->Vol_muestra = $res->VB;
                                    $model->Dilucion = $res->E;
                                    $model->Vol_botella = $res->C;
                                    $model->Resultado = $resultado;
                                    $model->Analizo = Auth::user()->id;
                                    $model->Sugerido = $res->S;
                                    $model->save();
                                    $tipo = 2;
                                    break;
                                default:
                                if ($res->tipo == 1) {
                                    if ($res->D <= 0.1) {
                                        $E = $res->D / $res->C;
                                        $resultadoTemp = ($res->A - $res->B) / $E;  
                                    }else{
                                        $E = $res->D / $res->C;
                                        $resultadoTemp = ($res->A - $res->B) / round($E, 3);
                                    }
                                    $resultado = round($resultadoTemp,2);    
                                    
                                    $model = LoteDetalleDbo::find($res->idMuestra);
                                    $model->Botella_final = $res->H;
                                    $model->Botella_od = $res->G;
                                    $model->Odf = $res->B;
                                    $model->Odi = $res->A;
                                    $model->Ph_final = $res->J;
                                    $model->Ph_inicial = $res->I;
                                    $model->Vol_muestra = $res->D;
                                    $model->Dilucion = $res->E;
                                    $model->Vol_botella = $res->C;
                                    $model->Resultado = $resultado;
                                    $model->Analizo = Auth::user()->id;
                                    $model->Sugerido = $res->S;
                                    $model->save();
                                    $tipo = 1;
                                } else {
                                    $resultado = ($res->OI - $res->OF);
                                    $model = LoteDetalleDbo::find($res->idMuestra);
                                    $model->Odf = $res->OF;
                                    $model->Odi = $res->OI;
                                    $model->Vol_muestra = $res->V;
                                    $model->Dilucion = $res->E;
                                    $model->Resultado = $resultado;
                                    $model->Analizo = Auth::user()->id;
                                    $model->Sugerido = $res->S;
                                    $model->save();
                                    $tipo = 2;
                                }
                                    break;
                            }
                        
            
                            break;
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
                            $model->Lectura1 = $res->l1;$model->Lectura2 = $res->l2;
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
                            $fd = 10 / $res->volumen ;
                            $promedio = ($res->l1 + $res->l2 + $res->l3) / 3;
                            $resultado = round($promedio * $fd, 2);
                            $model = LoteDetalleDirectos::find($res->idMuestra);
                            $model->Factor_dilucion = $fd;
                            $model->Resultado = $resultado;
                            $model->Vol_muestra = $res->volumen;
                            $model->Lectura1 = $res->l1;
                            $model->Lectura2 = $res->l2;
                            $model->Lectura3 = $res->l3;
                            $model->Promedio = $promedio;
                            $model->save();
                            break;
                        case 98:
                        case 89:
                        case 115:
                            $resultado = 0;
                            $fd =  15 / $res->volumen;
                            $promedio = (($res->l1 + $res->l2 + $res->l3) / 3) / $fd;
                            $resultado = round($promedio, 2);
                            $model = LoteDetalleDirectos::find($res->idMuestra);
                            $model->Resultado = $resultado;
                            $model->Factor_dilucion = $fd;
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
                        case 130:
                            $resultado = ($res->resultado / 50) * 20;
                            $model = LoteDetalleDirectos::find($res->idMuestra);
                            $model->Lectura1 = $res->resultado;
                            $model->Resultado = $resultado;
                            $model->save();
                            break;
                        case 261:
                            $resultado = ($res->resultado / 50) * 12;
                            $model = LoteDetalleDirectos::find($res->idMuestra);
                            $model->Lectura1 = $res->resultado; 
                            $model->Resultado = $resultado;
                            $model->save();
                            break;
                        default: // Default Directos
                            $resultado = $res->resultado;
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
            if ($model->Id_control == 1) {
                $codigoParametro = CodigoParametros::find($model->Id_codigo);
                $codigoParametro->Resultado = @$resultado;
                $codigoParametro->save();   
            }
        }
        $data = array(
            'tipo' => $tipo,
            'resultado' => $resultado,
            'aux' => $aux,
            'model' => $model,
            'std' => $std,
            'r2' => $r2,
        );
        return response()->json($data);
    }
    public function getDetalleLote(Request $res)
    {
        $lote = DB::table('ViewLoteAnalisis')->where('Id_lote', $res->id)->get();
        $model = array();
        $aux = array();

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
                            $model = ValoracionCloro::where('Id_lote',$res->id)->first();
                            break;
                        case 28: //Alcalinidad
                        case 29:
                        case 27:
                            $lote = LoteAnalisis::where('Id_lote',$res->id)->first();
                            $model = ValoracionAlcalinidad::whereDate('Fecha_inicio','<=',$lote->Fecha)
                            ->whereDate('Fecha_fin','>=',$lote->Fecha)
                            ->where('Id_parametro',$lote->Id_tecnica)->first();

                            $aux = ValoracionAlcalinidad::where('Id_parametro',$lote->Id_tecnica)->orderBy('Id_valoracion','DESC')->limit(2)->get();
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
                        case 77:
                        case 251:
                        case 252:
                            $model = ValoracionDureza::where('Id_lote',$res->id)->first();
                            break;

                        default:
                            break;
                    }
                    break;
                case 7: //campo
                case 19: //directo
                    break;
                case 6: // MB
                case 12: // Mb Alimentos
                    $model = DqoDetalle::where('Id_lote',$res->id)->first();
                    break;
                default:
                    $model = array();
                    break;
            }
        }

        $lote = DB::table('ViewLoteAnalisis')->where('Id_lote', $res->id)->first();
        $data = array(
            'aux' => $aux,
            'model' => $model,
            'plantilla' => $plantilla,
            'lote' => $lote,
            
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
                        case 69:
                        case 152:
                            $muestra = LoteDetalleEspectro::where('Id_detalle', $res->idMuestra)->first();
                            $model = $muestra->replicate();
                            $model->Id_control = $res->idControl;
                            $model->Resultado = "";
                            $model->Liberado = 0;
                            $model->save();
                            $model = LoteDetalleEspectro::where('Id_lote', $res->idLote)->get();
                            break;
                        default:
                            $muestra = LoteDetalleEspectro::where('Id_detalle', $res->idMuestra)->first();
                     
                            
                            $temp = LoteDetalleEspectro::where('Id_lote',$muestra->Id_lote)->where('Id_control',$res->idControl)->get();
                            if($temp->count()){
                                
                            }else{
                                $model = $muestra->replicate();
                                $model->Id_control = $res->idControl;
                                $model->Resultado = "";
                                $model->Liberado = 0;
                                $model->save();
                            }


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
                            $temp = LoteDetalleDqo::where('Id_lote',$muestra->Id_lote)->where('Id_control',$res->idControl)->get();
                            if($temp->count()){

                            }else{
                                $model = $muestra->replicate();
                                $model->Id_control = $res->idControl;
                                $model->Resultado = NULL;
                                $model->Liberado = 0;
                                $model->save();
                            }

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
                        case 28:
                        case 29:
                        case 30:
                        case 27:
                            $muestra = LoteDetalleAlcalinidad::where('Id_detalle', $res->idMuestra)->first();
                            $model = $muestra->replicate();
                            $model->Id_control = $res->idControl;
                            $model->Resultado = NULL;
                            $model->Liberado = 0;
                            $model->save();

                            $model = LoteDetalleAlcalinidad::where('Id_lote', $res->idLote)->get();
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
        $aux = Parametro::where('Id_parametro',$lote[0]->Id_tecnica)->first();
        $idLibero = Auth::user()->id;
        if ($aux->Usuario_default != 0) {
            $idLibero = $aux->Usuario_default;
        }
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
                                if (strval($model->Resultado) != null) {
                                    $sw = true;
                                    $model->save();
                                }
                                if ($item->Id_control == 1) {
                                    $modelCod = CodigoParametros::find($model->Id_codigo);
                                    $modelCod->Resultado = $model->Resultado;
                                    $modelCod->Resultado2 = $model->Resultado;
                                    $modelCod->Analizo = $idLibero;
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
                        if (strval($model->Resultado) != null) {
                            $sw = true;
                            $model->Analizo = $idLibero;
                            $model->save();

                            $modelMatraz = MatrazGA::find($model->Id_matraz);
                            $modelMatraz->Estado = 0;
                            $modelMatraz->save();
                        }
                        if ($item->Id_control == 1) {
                            $modelCod = CodigoParametros::find($model->Id_codigo);
                            $modelCod->Resultado = $model->Resultado;
                            $modelCod->Resultado2 = $model->Resultado;
                            $modelCod->Analizo = $idLibero;
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
                        if (strval($model->Resultado) != null) {
                            $sw = true;
                            $model->Analizo = $idLibero;
                            $model->save();   
                        }
                        if ($item->Id_control == 1) {
                            $modelCod = CodigoParametros::find($model->Id_codigo);
                            $modelCod->Resultado = $model->Resultado;
                            $modelCod->Resultado2 = $model->Resultado;
                            $modelCod->Analizo = $idLibero;
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
                                if (strval($model->Resultado) != null) {
                                    $sw = true;
                                    $model->save();
                                }
                                if ($item->Id_control == 1) {
                                    $modelCod = CodigoParametros::find($model->Id_codigo);
                                    $modelCod->Resultado = $model->Resultado;
                                    $modelCod->Resultado2 = $model->Resultado;
                                    $modelCod->Analizo = $idLibero;
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
                                //$model->Liberado = Auth::user()->id;
                                if (strval($model->Resultado) != null) {
                                    $sw = true;
                                    $model->save();
                                }
                                if ($item->Id_control == 1) {
                                    $modelCod = CodigoParametros::find($model->Id_codigo);
                                    $modelCod->Resultado = $model->Resultado;
                                    $modelCod->Resultado2 = $model->Resultado;
                                    $modelCod->Analizo = $idLibero;
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
                        
                            $muestras = LoteDetalleNitrogeno::where('Id_lote', $res->idLote)->where('Liberado', 0)->get();
                            foreach ($muestras as $item) {
                                $model = LoteDetalleNitrogeno::find($item->Id_detalle);
                                $model->Liberado = 1;
                                if (strval($model->Resultado)!= null) {
                                    $sw = true;
                                    $model->save();
                                }
                                if ($item->Id_control == 1) {
                                    $modelCod = CodigoParametros::find($model->Id_codigo);
                                    $modelCod->Resultado = $model->Resultado;
                                    $modelCod->Resultado2 = $model->Resultado;
                                    $modelCod->Analizo = $idLibero;
                                    $modelCod->save();
                                }
                            }

                            $model = LoteDetalleNitrogeno::where('Id_lote', $res->idLote)->where('Liberado', 1)->get();
                            break;
                        case 28://Alcalinidad
                        case 29:
                        case 30:
                        case 27:
                            $muestras = LoteDetalleAlcalinidad::where('Id_lote', $res->idLote)->where('Liberado', 0)->get();
                            foreach ($muestras as $item) {
                                $model = LoteDetalleAlcalinidad::find($item->Id_detalle);
                                $model->Liberado = 1;
                                if (strval($model->Resultado)!= null) {
                                    $sw = true;
                                    $model->save();
                                }
                                if ($item->Id_control == 1) {
                                    $modelCod = CodigoParametros::find($model->Id_codigo);
                                    $modelCod->Resultado = $model->Resultado;
                                    $modelCod->Resultado2 = $model->Resultado;
                                    $modelCod->Analizo = $idLibero;
                                    $modelCod->save();
                                }
                            }

                            $model = LoteDetalleAlcalinidad::where('Id_lote', $res->idLote)->where('Liberado', 1)->get();
                            break;
                        default:
                            $muestras = LoteDetalleDirectos::where('Id_lote', $res->idLote)->where('Liberado', 0)->get();
                            foreach ($muestras as $item) {
                                $model = LoteDetalleDirectos::find($item->Id_detalle);
                                $model->Liberado = 1;
                                if (strval($model->Resultado) != null) {
                                    $sw = true;
                                    $model->save();
                                }
                                if ($item->Id_control == 1) {
                                    $modelCod = CodigoParametros::find($model->Id_codigo);
                                    $modelCod->Resultado = $model->Resultado;
                                    $modelCod->Resultado2 = $model->Resultado;
                                    $modelCod->Analizo = $idLibero;
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
                        $model->Analizo = Auth::user()->id;
                        if (strval($model->Resultado) != null) {
                            $sw = true;
                            $model->save();
                        }
                        if ($item->Id_control == 1) {
                            $modelCod = CodigoParametros::find($model->Id_codigo);
                            $modelCod->Resultado = $model->Resultado;
                            $modelCod->Resultado2 = $model->Resultado;
                            $modelCod->Analizo = $idLibero;
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
                                $model->Analizo = $idLibero;
                                if (strval($model->Resultado) != null) {
                                    $sw = true;
                                    $model->save();
                                }
                                if ($item->Id_control == 1) {
                                    $modelCod = CodigoParametros::find($model->Id_codigo);
                                    $modelCod->Resultado = $model->Resultado;
                                    $modelCod->Resultado2 = $model->Resultado;
                                    $modelCod->Analizo = $idLibero;
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
                                $model->Analizo = $idLibero;
                                if (strval($model->Resultado) != null) {
                                    $sw = true;
                                    $model->save();
                                }
                                if ($item->Id_control == 1) {
                                    $modelCod = CodigoParametros::find($model->Id_codigo);
                                    $modelCod->Resultado = $model->Resultado;
                                    $modelCod->Resultado2 = $model->Resultado;
                                    $modelCod->Analizo = $idLibero;
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
                                $model->Analizo = $idLibero;
                                if (strval($model->Resultado) != null) {
                                    $sw = true;
                                    $model->save();
                                }
                                if ($item->Id_control == 1) {
                                    $modelCod = CodigoParametros::find($model->Id_codigo);
                                    $modelCod->Resultado = $model->Resultado;
                                    $modelCod->Resultado2 = $model->Resultado;
                                    $modelCod->Analizo = $idLibero;
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
                                $model->Analizo = $idLibero;
                                if (strval($model->Resultado) != null) {
                                    $sw = true;
                                    $model->save();
                                }
                                if ($item->Id_control == 1) {
                                    $modelCod = CodigoParametros::find($model->Id_codigo);
                                    $modelCod->Resultado = $model->Resultado;
                                    $modelCod->Resultado2 = $model->Resultado;
                                    $modelCod->Analizo = $idLibero;
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
                                if (strval($model->Resultado) != null) {
                                    $sw = true;
                                    $model->save();
                                }
                                if ($item->Id_control == 1) {
                                    $modelCod = CodigoParametros::find($model->Id_codigo);
                                    $modelCod->Resultado = $model->Resultado;
                                    $modelCod->Resultado2 = $model->Resultado;
                                    $modelCod->Analizo = $idLibero;
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
                                if (strval($model->Resultado) != null) {
                                    $sw = true;
                                    $model->save();
                                }
                                if ($item->Id_control == 1) {
                                    $modelCod = CodigoParametros::find($model->Id_codigo);
                                    $modelCod->Resultado = $model->Resultado;
                                    $modelCod->Resultado2 = $model->Resultado;
                                    $modelCod->Analizo = $idLibero;
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
                                if (strval($model->Resultado) != null) {
                                    $sw = true;
                                    $model->save();
                                }
                                if ($item->Id_control == 1) {
                                    $modelCod = CodigoParametros::find($model->Id_codigo);
                                    $modelCod->Resultado = $model->Resultado;
                                    $modelCod->Resultado2 = $model->Resultado;
                                    $modelCod->Analizo = $idLibero;
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
                                if (strval($model->Resultado) != null) {
                                    $sw = true;
                                    $model->save();
                                }
                                if ($item->Id_control == 1) {
                                    $modelCod = CodigoParametros::find($model->Id_codigo);
                                    $modelCod->Resultado = $model->Resultado;
                                    $modelCod->Resultado2 = $model->Resultado;
                                    $modelCod->Analizo = $idLibero;
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
                        if (strval($model->Resultado) != null) {
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
        $aux = Parametro::where('Id_parametro',$lote[0]->Id_tecnica)->first();
        $idLibero = Auth::user()->id;
        if ($aux->Usuario_default != 0) {
            $idLibero = $aux->Usuario_default;
        }
        switch ($lote[0]->Id_area) {
            case 16: // Espectrofotometria
            case 5: // Fisicoquimicos
                switch ($lote[0]->Id_tecnica) {
                    case 0:

                        break;
                    default:
                        $model = LoteDetalleEspectro::find($res->idMuestra);
                        $model->Liberado = 1;
                        if (strval($model->Resultado) != null) {
                            $sw = true;
                            $model->Analizo = $idLibero;
                            $model->save();
                        }

                        if ($model->Id_control == 1) {
                            $modelCod = CodigoParametros::find($model->Id_codigo);
                            $modelCod->Resultado = $model->Resultado;
                            $modelCod->Resultado2 = $model->Resultado;
                            $modelCod->Analizo = $idLibero;
                            $modelCod->save();
                        }

                        $model = LoteDetalleEspectro::where('Id_lote', $res->idLote)->where('Liberado', 1)->get();
                        break;
                }
                break;
            case 13: // G&A
                $model = LoteDetalleGA::find($res->idMuestra);
                $model->Liberado = 1;
                if (strval($model->Resultado) != null) {
                    $sw = true;
                    $model->Analizo = $idLibero;
                    $model->save();
                }
                $modelMatraz = MatrazGA::find($model->Id_matraz);
                $modelMatraz->Estado = 0;
                $modelMatraz->save();

                if ($model->Id_control == 1) {
                    $modelCod = CodigoParametros::find($model->Id_codigo);
                    $modelCod->Resultado = $model->Resultado;
                    $modelCod->Resultado2 = $model->Resultado;
                    $modelCod->Analizo = $idLibero;
                    $modelCod->save();
                }
                $model = LoteDetalleGA::where('Id_lote', $res->idLote)->where('Liberado', 1)->get();
                break;
            case 15: //Solidos
                $model = LoteDetalleSolidos::find($res->idMuestra);
                $model->Liberado = 1;
                if (strval($model->Resultado) != null) {
                    $sw = true;
                    $model->Analizo = $idLibero;
                    $model->save();
                }

                if ($model->Id_control == 1) {
                    $modelCod = CodigoParametros::find($model->Id_codigo);
                    $modelCod->Resultado = $model->Resultado;
                    $modelCod->Resultado2 = $model->Resultado;
                    $modelCod->Analizo = $idLibero;
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
                        if (strval($model->Resultado) != null) {
                            $sw = true;
                            $model->Analizo = $idLibero;
                            $model->save();
                        }

                        if ($model->Id_control == 1) {
                            $modelCod = CodigoParametros::find($model->Id_codigo);
                            $modelCod->Resultado = $model->Resultado;
                            $modelCod->Resultado2 = $model->Resultado;
                            $modelCod->Analizo = $idLibero;
                            $modelCod->save();
                        }
                        $model = LoteDetalleDqo::where('Id_lote', $res->idLote)->where('Liberado', 1)->get();
                        break;
                    case 33: // Cloro
                    case 218:
                    case 64:
                        $model = LoteDetalleCloro::find($res->idMuestra);
                        $model->Liberado = 1;
                        if (strval($model->Resultado) != null) {
                            $sw = true;
                            $model->Analizo = $idLibero;
                            $model->save();
                        }

                        if ($model->Id_control == 1) {
                            $modelCod = CodigoParametros::find($model->Id_codigo);
                            $modelCod->Resultado = $model->Resultado;
                            $modelCod->Resultado2 = $model->Resultado;
                            $modelCod->Analizo = $idLibero;
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
                        $model = LoteDetalleNitrogeno::find($res->idMuestra);
                        $model->Liberado = 1;
                        if (strval($model->Resultado) != null) {
                            $sw = true;
                            $model->Analizo = $idLibero;
                            $model->save();
                        }
                        if ($model->Id_control == 1) {
                            $modelCod = CodigoParametros::find($model->Id_codigo);
                            $modelCod->Resultado = $model->Resultado;
                            $modelCod->Resultado2 = $model->Resultado;
                            $modelCod->Analizo = $idLibero;
                            $modelCod->save();
                        }

                        $model = LoteDetalleNitrogeno::where('Id_lote', $res->idLote)->where('Liberado', 1)->get();
                        break;
                    case 28://Alcalinidad
                    case 29:
                    case 30:
                    case 27:
                        $model = LoteDetalleAlcalinidad::find($res->idMuestra);
                        $model->Liberado = 1;
                        if (strval($model->Resultado) != null) {
                            $sw = true;
                            $model->Analizo = $idLibero;
                            $model->save();
                        }
                        if ($model->Id_control == 1) {
                            $modelCod = CodigoParametros::find($model->Id_codigo);
                            $modelCod->Resultado = $model->Resultado;
                            $modelCod->Resultado2 = $model->Resultado;
                            $modelCod->Analizo = $idLibero;
                            $modelCod->save();
                        }

                        $model = LoteDetalleAlcalinidad::where('Id_lote', $res->idLote)->where('Liberado', 1)->get();
                        break;
                    default:
                        $model = LoteDetalleDirectos::find($res->idMuestra);
                        $model->Liberado = 1;
                        if (strval($model->Resultado) != null) {
                            $sw = true;
                            $model->Analizo = $idLibero;
                            $model->save();
                        }
                        if ($model->Id_control == 1) {
                            $modelCod = CodigoParametros::find($model->Id_codigo);
                            $modelCod->Resultado = $model->Resultado;
                            $modelCod->Resultado2 = $model->Resultado;
                            $modelCod->Analizo = $idLibero;
                            $modelCod->save();
                        }
                        $model = LoteDetalleDirectos::where('Id_lote', $res->idLote)->where('Liberado', 1)->get();
                        break;
                }
                break;
            case 8:
                    switch ($lote[0]->Id_tecnica) {
                        case 77:
                        case 103:
                        case 251:
                        case 252:
                            $model = LoteDetalleDureza::find($res->idMuestra);
                            $model->Liberado = 1;
                            // if (strval($model->Resultado) != null) {
                              
                            // }

                            $sw = true;
                            $model->Analizo = $idLibero;
                            $model->save();
            
                            if ($model->Id_control == 1) {
                                $modelCod = CodigoParametros::find($model->Id_codigo);
                                $modelCod->Resultado = $model->Resultado; 
                                $modelCod->Resultado2 = $model->Resultado;
                                $modelCod->Analizo = $idLibero;
                                $modelCod->save();
                            }
            
                            $model = LoteDetalleDureza::where('Id_lote', $res->idLote)->where('Liberado', 1)->get();
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
                        if (strval($model->Resultado) != null) {
                            $sw = true;
                            $model->save();
                        }

                        if ($model->Id_control == 1) {
                            $modelCod = CodigoParametros::find($model->Id_codigo);
                            $modelCod->Resultado = $model->Resultado;
                            $modelCod->Resultado2 = $model->Resultado;
                            $modelCod->Analizo = $idLibero;
                            $modelCod->save();
                        }

                        $model = LoteDetalleColiformes::where('Id_lote', $res->idLote)->where('Liberado', 1)->get();
                        break;
                    case 253: //todo  ENTEROCOCO FECAL
                        $model = LoteDetalleEnterococos::find($res->idMuestra);
                        $model->Liberado = 1;
                        if (strval($model->Resultado) != null) {
                            $sw = true;
                            $model->save();
                        }

                        if ($model->Id_control == 1) {
                            $modelCod = CodigoParametros::find($model->Id_codigo);
                            $modelCod->Resultado = $model->Resultado;
                            $modelCod->Resultado2 = $model->Resultado;
                            $modelCod->Analizo = $idLibero;
                            $modelCod->save();
                        }
                        $model = LoteDetalleEnterococos::where('Id_lote', $res->idLote)->where('Liberado', 1)->get();
                        break;
                    case 5: //todo DEMANDA BIOQUIMICA DE OXIGENO (DBO5) 
                    case 71:
                        $model = LoteDetalleDbo::find($res->idMuestra);
                        $model->Liberado = 1;
                        if (strval($model->Resultado) != null) {
                            $sw = true;
                            $model->save();
                        }

                        if ($model->Id_control == 1) {
                            $modelCod = CodigoParametros::find($model->Id_codigo);
                            $modelCod->Resultado = $model->Resultado;
                            $modelCod->Resultado2 = $model->Resultado;
                            $modelCod->Analizo = $idLibero;
                            $modelCod->save();
                        }

                        $model = LoteDetalleDbo::where('Id_lote', $res->idLote)->where('Liberado', 1)->get();
                        break;
                    case 16: //todo Huevos de Helminto 
                        $model = LoteDetalleHH::find($res->idMuestra);
                        $model->Liberado = 1;
                        if (strval($model->Resultado) != null) {
                            $sw = true;
                            $model->save();
                        }

                        if ($model->Id_control == 1) {
                            $modelCod = CodigoParametros::find($model->Id_codigo);
                            $modelCod->Resultado = $model->Resultado;
                            $modelCod->Resultado2 = $model->Resultado;
                            $modelCod->Analizo = $idLibero;
                            $modelCod->save();
                        }

                        $model = LoteDetalleHH::where('Id_lote', $res->idLote)->where('Liberado', 1)->get();
                        break;

                    case 78:
                        $model = LoteDetalleEcoli::find($res->idMuestra);
                        $model->Liberado = 1;
                        if (strval($model->Resultado) != null) { 
                            $sw = true;
                            $model->save();
                        }
                        if ($model->Id_control == 1) {
                            $modelCod = CodigoParametros::find($model->Id_codigo);
                            $modelCod->Resultado = $model->Resultado;
                            $modelCod->Resultado2 = $model->Resultado;
                            $modelCod->Analizo = $idLibero;
                            $modelCod->save();
                        }
                        $model = LoteDetalleEcoli::where('Id_lote', $res->idLote)->where('Liberado', 1)->get();
                        break;
                        case 70: // inoculko
                            $model = LoteDetalleDboIno::where('Id_detalle' , $res->idMuestra)->first();
                            $model->Liberado = 1;
                            if (strval($model->Resultado) != null) {
                                $sw = true;
                                $model->Analizo = $idLibero;
                                $model->save();
                            }
            
                            if ($model->Id_control == 1) {
                                $modelCod = CodigoParametros::find($model->Id_codigo);
                                $modelCod->Resultado = $model->Resultado;
                                $modelCod->Resultado2 = $model->Resultado;
                                $modelCod->Analizo = $idLibero;
                                $modelCod->save();
                            }
                            $model = LoteDetalleDboIno::where('Id_lote', $res->idLote)->where('Liberado', 1)->get();
                        break;
                    default:
                        $model = LoteDetalleDirectos::find($res->idMuestra);
                        $model->Liberado = 1;
                        if (strval($model->Resultado) != null) {
                            $sw = true;
                            $model->save();
                        }

                        if ($model->Id_control == 1) {
                            $modelCod = CodigoParametros::find($model->Id_codigo);
                            $modelCod->Resultado = $model->Resultado; 
                            $modelCod->Resultado2 = $model->Resultado;
                            $modelCod->Analizo = $idLibero;
                            $modelCod->save();
                        }

                        $model = LoteDetalleDirectos::where('Id_lote', $res->idLote)->where('Liberado', 1)->get();
                        break;
                }
                break;
            
            case 7: //Campo
            case 19: //directos
                $model = LoteDetalleDirectos::find($res->idMuestra);
                $userReviso = 0;
                $model->Liberado = 1;

                if ($model->Id_parametro == 14 || $model->Id_parametro == 110){
                    $userReviso = 14; //Guadalupe
                } else {
                    $userReviso = Auth::user()->id;
                }

                $model->Analizo = $userReviso;
                if (strval($model->Resultado) != null) {
                    $sw = true;
                    $model->save();
                }

                if ($model->Id_control == 1) {
                    $modelCod = CodigoParametros::find($model->Id_codigo);
                    $modelCod->Resultado = $model->Resultado;
                    $modelCod->Resultado2 = $model->Resultado;
                    $modelCod->Analizo = $userReviso;
                    $modelCod->save();
                }
                $model = LoteDetalleDirectos::where('Id_lote', $res->idLote)->where('Liberado', 1)->get();
                break;
            default:
                $model = LoteDetalleDirectos::find($res->idMuestra);
                $model->Liberado = 1;
                if (strval($model->Resultado) != null) {
                    $sw = true;
                    $model->save();
                }

                if ($model->Id_control == 1) {
                    $modelCod = CodigoParametros::find($model->Id_codigo);
                    $modelCod->Resultado = $model->Resultado;
                    $modelCod->Resultado2 = $model->Resultado;
                    $modelCod->Analizo = $idLibero;
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
                        $model = LoteDetalleNitrogeno::where('Id_detalle', $res->idMuestra)->first();
                        $model->Observacion = $res->observacion;
                        $model->save();
                        break;
                    case 28://Alcalinidad
                    case 29:
                    case 30:
                    case 27:
                        $model = LoteDetalleAlcalinidad::where('Id_detalle', $res->idMuestra)->first();
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
                    @$analizo = User::where('id', @$model[0]->Analizo)->first();
                }
                $reviso = User::where('id', @$lote->Id_superviso)->first();
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
                        $mpdf = new \Mpdf\Mpdf([
                            'orientation' => 'P',
                            'format' => 'letter',
                            'margin_left' => 10,
                            'margin_right' => 10,
                            'margin_top' => 40,
                            'margin_bottom' => 50,
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
                        $mpdf = new \Mpdf\Mpdf([
                            'orientation' => "L",
                            'format' => 'letter',
                            'margin_left' => 5,
                            'margin_right' => 5,
                            'margin_top' => 40,
                            'margin_bottom' => 45,
                            'defaultheaderfontstyle' => ['normal'],
                            'defaultheaderline' => '0'
                        ]);
                        //Establece la marca de agua del documento PDF
                        $mpdf->SetWatermarkImage(
                            asset('/public/storage/HojaMembretadaHorizontal.png'),
                            1,
                            array(215, 280), 
                            array(0, 0),
                        );
                        $mpdf->showWatermarkImage = true;
                        $htmlFooter = view('exports.laboratorio.fq.espectro.sulfatos.127.capturaFooter', $data);
                        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                        $htmlHeader = view('exports.laboratorio.fq.espectro.sulfatos.127.capturaHeader', $data);
                        $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                        $htmlCaptura = view('exports.laboratorio.fq.espectro.sulfatos.127.capturaBody', $data);
                        $mpdf->CSSselectMedia = 'mpdf';
                        $mpdf->WriteHTML($htmlCaptura);
                        break;
                    case 113://ion
                        $mpdf = new \Mpdf\Mpdf([
                            'orientation' => "L",
                            'format' => 'letter',
                            'margin_left' => 5,
                            'margin_right' => 5,
                            'margin_top' => 40,
                            'margin_bottom' => 45,
                            'defaultheaderfontstyle' => ['normal'],
                            'defaultheaderline' => '0'
                        ]);
                        //Establece la marca de agua del documento PDF
                        $mpdf->SetWatermarkImage(
                            asset('/public/storage/HojaMembretadaHorizontal.png'),
                            1,
                            array(215, 280), 
                            array(0, 0),
                        );
                
                        $mpdf->showWatermarkImage = true;
                        $mpdf->CSSselectMedia = 'mpdf';
                        $htmlFooter = view('exports.laboratorio.fq.espectro.sulfatos.capturaFooter', $data);
                        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                        $htmlHeader = view('exports.laboratorio.fq.espectro.sulfatos.capturaHeader', $data);
                        $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                        $htmlCaptura = view('exports.laboratorio.fq.espectro.sulfatos.capturaBody', $data);
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
                    case 80: //Floruros
                        $htmlFooter = view('exports.laboratorio.fq.espectro.fluoruros.capturaFooter', $data);
                        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                        $htmlHeader = view('exports.laboratorio.fq.espectro.fluoruros.capturaHeader', $data);
                        $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                        $htmlCaptura = view('exports.laboratorio.fq.espectro.fluoruros.capturaBody', $data);
                        $mpdf->CSSselectMedia = 'mpdf';
                        $mpdf->WriteHTML($htmlCaptura);
                        break;
                    case 95: //Sulfatos
                        $htmlFooter = view('exports.laboratorio.fq.espectro.sulfatos.capturaFooter', $data);
                        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                        $htmlHeader = view('exports.laboratorio.fq.espectro.sulfatos.capturaHeader', $data);
                        $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                        $htmlCaptura = view('exports.laboratorio.fq.espectro.sulfatos.capturaBody', $data);
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
                        $mpdf = new \Mpdf\Mpdf([
                            'orientation' => 'P',
                            'format' => 'letter',
                            'margin_left' => 10,
                            'margin_right' => 10,
                            'margin_top' => 40,
                            'margin_bottom' => 50,
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
                        $mpdf = new \Mpdf\Mpdf([
                            'orientation' => 'P',
                            'format' => 'letter',
                            'margin_left' => 10,
                            'margin_right' => 10,
                            'margin_top' => 40,
                            'margin_bottom' => 55,
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
                    case 87:
                        $mpdf = new \Mpdf\Mpdf([
                            'orientation' => 'P',
                            'format' => 'letter',
                            'margin_left' => 10,
                            'margin_right' => 10,
                            'margin_top' => 40, 
                            'margin_bottom' => 55,
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
                        $htmlFooter = view('exports.laboratorio.fq.espectro.silice.capturaFooter', $data);
                        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                        $htmlHeader = view('exports.laboratorio.fq.espectro.silice.capturaHeader', $data);
                        $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                        $htmlCaptura = view('exports.laboratorio.fq.espectro.silice.capturaBody', $data);
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
                        $comprobacion = LoteDetalleNitrogeno::where('Liberado', 0)->where('Id_lote', $id)->get();
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
                        $htmlCaptura = view('exports.laboratorio.volumetria.nitrogenoA.capturaBody', $data);
                        $htmlHeader = view('exports.laboratorio.volumetria.nitrogenoA.capturaHeader', $data);

                        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E'); 
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
                $reviso = User::where('id', @$lote->Id_superviso)->first();

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
                        $reviso = User::where('id', @$lote->Id_superviso)->first();
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
                        $mpdf = new \Mpdf\Mpdf([
                            'orientation' => 'P',
                            'format' => 'letter',
                            'margin_left' => 10,
                            'margin_right' => 10,
                            'margin_top' => 40,
                            'margin_bottom' => 55,
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
                        $reviso = User::where('id', @$lote->Id_superviso)->first();
                        $data = array(
                            'lote' => $lote,
                            'model' => $model,
                            'plantilla' => $plantilla,
                            'procedimiento' => $procedimiento,
                            'analizo' => $analizo,
                            'reviso' => $reviso,
                            'comprobacion' => $comprobacion,
                        );

                        $htmlFooter = view('exports.laboratorio.fq.ga.sdt2.capturaFooter', $data);
                        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                        $htmlHeader = view('exports.laboratorio.fq.ga.sdt2.capturaHeader', $data);
                        $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                        $htmlCaptura = view('exports.laboratorio.fq.ga.sdt2.capturaBody', $data);
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
                        $reviso = User::where('id', @$lote->Id_superviso)->first();
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
                    case 90:
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
                        $reviso = User::where('id', @$lote->Id_superviso)->first();
                        $data = array(
                            'lote' => $lote,
                            'model' => $model,
                            'plantilla' => $plantilla,
                            'procedimiento' => $procedimiento,
                            'analizo' => $analizo,
                            'reviso' => $reviso,
                            'comprobacion' => $comprobacion,
                        );

                        $htmlFooter = view('exports.laboratorio.fq.ga.st.capturaFooter', $data);
                        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                        $htmlHeader = view('exports.laboratorio.fq.ga.st.capturaHeader', $data);
                        $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                        $htmlCaptura = view('exports.laboratorio.fq.ga.st.capturaBody', $data);
                        $mpdf->CSSselectMedia = 'mpdf';
                        $mpdf->WriteHTML($htmlCaptura);
                        break;
                        case 88:
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
                            $reviso = User::where('id', @$lote->Id_superviso)->first();
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
                        case 46:
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
                            $reviso = User::where('id', @$lote->Id_superviso)->first();
                            $data = array(
                                'lote' => $lote,
                                'model' => $model,
                                'plantilla' => $plantilla,
                                'procedimiento' => $procedimiento,
                                'analizo' => $analizo,
                                'reviso' => $reviso,
                                'comprobacion' => $comprobacion,
                            );
    
                            $htmlFooter = view('exports.laboratorio.fq.ga.ssv.capturaFooter', $data);
                            $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                            $htmlHeader = view('exports.laboratorio.fq.ga.ssv.capturaHeader', $data);
                            $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                            $htmlCaptura = view('exports.laboratorio.fq.ga.ssv.capturaBody', $data);
                            $mpdf->CSSselectMedia = 'mpdf';
                            $mpdf->WriteHTML($htmlCaptura);
                        break;
                        case 48://STV
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
                            $reviso = User::where('id', @$lote->Id_superviso)->first();
                            $data = array(
                                'lote' => $lote,
                                'model' => $model,
                                'plantilla' => $plantilla,
                                'procedimiento' => $procedimiento,
                                'analizo' => $analizo,
                                'reviso' => $reviso,
                                'comprobacion' => $comprobacion,
                            );
    
                            $htmlFooter = view('exports.laboratorio.fq.ga.stv.capturaFooter', $data);
                            $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                            $htmlHeader = view('exports.laboratorio.fq.ga.stv.capturaHeader', $data);
                            $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                            $htmlCaptura = view('exports.laboratorio.fq.ga.stv.capturaBody', $data);
                            $mpdf->CSSselectMedia = 'mpdf';
                            $mpdf->WriteHTML($htmlCaptura);
                        break;
                        case 47://STF
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
                            $reviso = User::where('id', @$lote->Id_superviso)->first();
                            $data = array(
                                'lote' => $lote,
                                'model' => $model,
                                'plantilla' => $plantilla,
                                'procedimiento' => $procedimiento,
                                'analizo' => $analizo,
                                'reviso' => $reviso,
                                'comprobacion' => $comprobacion,
                            );
    
                            $htmlFooter = view('exports.laboratorio.fq.ga.stf.capturaFooter', $data);
                            $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                            $htmlHeader = view('exports.laboratorio.fq.ga.stf.capturaHeader', $data);
                            $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                            $htmlCaptura = view('exports.laboratorio.fq.ga.stf.capturaBody', $data);
                            $mpdf->CSSselectMedia = 'mpdf';
                            $mpdf->WriteHTML($htmlCaptura);
                        break;
                        case 44://SDV
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
                            $reviso = User::where('id', @$lote->Id_superviso)->first();
                            $data = array(
                                'lote' => $lote,
                                'model' => $model,
                                'plantilla' => $plantilla,
                                'procedimiento' => $procedimiento,
                                'analizo' => $analizo,
                                'reviso' => $reviso,
                                'comprobacion' => $comprobacion,
                            );
    
                            $htmlFooter = view('exports.laboratorio.fq.ga.sdv.capturaFooter', $data);
                            $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                            $htmlHeader = view('exports.laboratorio.fq.ga.sdv.capturaHeader', $data);
                            $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                            $htmlCaptura = view('exports.laboratorio.fq.ga.sdv.capturaBody', $data);
                            $mpdf->CSSselectMedia = 'mpdf';
                            $mpdf->WriteHTML($htmlCaptura);
                        break;
                        case 45://SSF
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
                            $reviso = User::where('id', @$lote->Id_superviso)->first();
                            $data = array(
                                'lote' => $lote,
                                'model' => $model,
                                'plantilla' => $plantilla,
                                'procedimiento' => $procedimiento,
                                'analizo' => $analizo,
                                'reviso' => $reviso,
                                'comprobacion' => $comprobacion,
                            );
    
                            $htmlFooter = view('exports.laboratorio.fq.ga.ssf.capturaFooter', $data);
                            $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                            $htmlHeader = view('exports.laboratorio.fq.ga.ssf.capturaHeader', $data);
                            $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                            $htmlCaptura = view('exports.laboratorio.fq.ga.ssf.capturaBody', $data);
                            $mpdf->CSSselectMedia = 'mpdf';
                            $mpdf->WriteHTML($htmlCaptura);
                        break;
                        case 43://SDF
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
                            $reviso = User::where('id', @$lote->Id_superviso)->first();
                            $data = array(
                                'lote' => $lote,
                                'model' => $model,
                                'plantilla' => $plantilla,
                                'procedimiento' => $procedimiento,
                                'analizo' => $analizo,
                                'reviso' => $reviso,
                                'comprobacion' => $comprobacion,
                            );
     
                            $htmlFooter = view('exports.laboratorio.fq.ga.sdf.capturaFooter', $data);
                            $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                            $htmlHeader = view('exports.laboratorio.fq.ga.sdf.capturaHeader', $data);
                            $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                            $htmlCaptura = view('exports.laboratorio.fq.ga.sdf.capturaBody', $data);
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
                                $reviso = User::where('id', @$lote->Id_superviso)->first();
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
                                $reviso = User::where('id', @$lote->Id_superviso)->first();
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
                        $reviso = User::where('id', @$lote->Id_superviso)->first();
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
                            $analizo = User::where('id', @$loteDetalle[0]->Analizo)->first();
                        }
                        $reviso = User::where('id', @$lote->Id_superviso)->first();
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
                        

                        $htmlFooter = view('exports.laboratorio.volumetria.cloro.capturaFooter', $data);
                        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                        $htmlHeader = view('exports.laboratorio.volumetria.cloro.capturaHeader', $data);
                        $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                        $htmlCaptura = view('exports.laboratorio.volumetria.cloro.capturaBody', $data);
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
                        $reviso = User::where('id', @$lote->Id_superviso)->first();
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
                        $reviso = User::where('id', @$lote->Id_superviso)->first();
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
                        $mpdf->CSSselectMedia = 'mpdf';
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
                        $reviso = User::where('id', @$lote->Id_superviso)->first();
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
                    case 28:
                    case 29:
                        $loteDetalle = DB::table('viewlotedetallealcalinidad')->where('Id_lote', $id)->get();
                        $temp = LoteAnalisis::where('Id_lote',$id)->first();
                        $valoracion = ValoracionAlcalinidad::whereDate('Fecha_inicio','<=',$temp->Fecha)
                            ->whereDate('Fecha_fin','>=',$temp->Fecha)
                            ->where('Id_parametro',$temp->Id_tecnica)->first();
                        $plantilla = Bitacoras::where('Id_lote', $id)->get();
                        if ($plantilla->count()) {
                        } else {
                            $plantilla = PlantillaBitacora::where('Id_parametro', $lote->Id_tecnica)->get();
                        }
                        $procedimiento = explode("NUEVASECCION", $plantilla[0]->Texto);

                        $comprobacion = LoteDetalleAlcalinidad::where('Liberado', 0)->where('Id_lote', $id)->get();
                        if ($comprobacion->count()) {
                            $analizo = "";
                        } else {
                            $analizo = User::where('id', @$loteDetalle[0]->Analizo)->first();
                        }
                        $reviso = User::where('id', @$lote->Id_superviso)->first();
                        
                        $data = array(
                            'analizo' => $analizo,
                            'procedimiento' => $procedimiento,
                            'comprobacion' => $comprobacion,
                            'reviso' => $reviso,
                            'lote' => $lote,
                            'loteDetalle' => $loteDetalle,
                            'valoracion' => $valoracion,
                            'plantilla' => $plantilla,
                            'procedimiento' => $procedimiento,
                        );
                        $htmlFooter = view('exports.laboratorio.volumetria.alcalinidad.capturaFooter', $data);
                        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                        $htmlHeader = view('exports.laboratorio.volumetria.alcalinidad.capturaHeader', $data);
                        $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                        $htmlCaptura = view('exports.laboratorio.volumetria.alcalinidad.capturaBody', $data);
                        $mpdf->CSSselectMedia = 'mpdf';
                        $mpdf->WriteHTML($htmlCaptura);
                        break;
                    case 27:
                        $loteDetalle = DB::table('viewlotedetallealcalinidad')->where('Id_lote', $id)->get();
                        $temp = LoteAnalisis::where('Id_lote',$id)->first();
                        $valoracion = ValoracionAlcalinidad::whereDate('Fecha_inicio','<=',$temp->Fecha)
                            ->whereDate('Fecha_fin','>=',$temp->Fecha)
                            ->where('Id_parametro',$temp->Id_tecnica)->first();
                        $plantilla = Bitacoras::where('Id_lote', $id)->get();
                        if ($plantilla->count()) {
                        } else {
                            $plantilla = PlantillaBitacora::where('Id_parametro', $lote->Id_tecnica)->get();
                        }
                        $procedimiento = explode("NUEVASECCION", $plantilla[0]->Texto);

                        $comprobacion = LoteDetalleAlcalinidad::where('Liberado', 0)->where('Id_lote', $id)->get();
                        if ($comprobacion->count()) {
                            $analizo = "";
                        } else {
                            $analizo = User::where('id', @$loteDetalle[0]->Analizo)->first();
                        }
                        $reviso = User::where('id', @$lote->Id_superviso)->first();
                        
                        $data = array(
                            'analizo' => $analizo,
                            'procedimiento' => $procedimiento,
                            'comprobacion' => $comprobacion,
                            'reviso' => $reviso,
                            'lote' => $lote,
                            'loteDetalle' => $loteDetalle,
                            'valoracion' => $valoracion,
                            'plantilla' => $plantilla,
                            'procedimiento' => $procedimiento,
                        );
                        $htmlFooter = view('exports.laboratorio.volumetria.acidez.capturaFooter', $data);
                        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                        $htmlHeader = view('exports.laboratorio.volumetria.acidez.capturaHeader', $data);
                        $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                        $htmlCaptura = view('exports.laboratorio.volumetria.acidez.capturaBody', $data);
                        $mpdf->CSSselectMedia = 'mpdf';
                        $mpdf->WriteHTML($htmlCaptura);
                        break;
                    case 30:
                        $loteDetalle = DB::table('viewlotedetallealcalinidad')->where('Id_lote', $id)->get();
                        $temp = LoteAnalisis::where('Id_lote',$id)->first();
                        $valoracion = ValoracionAlcalinidad::whereDate('Fecha_inicio','<=',$temp->Fecha)
                            ->whereDate('Fecha_fin','>=',$temp->Fecha)
                            ->where('Id_parametro',$temp->Id_tecnica)->first();
                        $plantilla = Bitacoras::where('Id_lote', $id)->get();
                        if ($plantilla->count()) {
                        } else {
                            $plantilla = PlantillaBitacora::where('Id_parametro', $lote->Id_tecnica)->get();
                        }
                        $procedimiento = explode("NUEVASECCION", $plantilla[0]->Texto);

                        $comprobacion = LoteDetalleAlcalinidad::where('Liberado', 0)->where('Id_lote', $id)->get();
                        if ($comprobacion->count()) {
                            $analizo = "";
                        } else {
                            $analizo = User::where('id', $loteDetalle[0]->Analizo)->first();
                        }
                        $reviso = User::where('id', @$lote->Id_superviso)->first();
                        $data = array(
                            'analizo' => $analizo,
                            'procedimiento' => $procedimiento,
                            'comprobacion' => $comprobacion,
                            'reviso' => $reviso,
                            'lote' => $lote,
                            'loteDetalle' => $loteDetalle,
                            'valoracion' => $valoracion,
                            'plantilla' => $plantilla,
                            'procedimiento' => $procedimiento,
                        );
                        $htmlFooter = view('exports.laboratorio.volumetria.alcalinidad.total.capturaFooter', $data);
                        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                        $htmlHeader = view('exports.laboratorio.volumetria.alcalinidad.total.capturaHeader', $data);
                        $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                        $htmlCaptura = view('exports.laboratorio.volumetria.alcalinidad.total.capturaBody', $data);
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
                    'margin_bottom' => 50,
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
                    case 2:
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
                        $reviso = User::where('id', @$lote->Id_superviso)->first();

                        $data = array(
                            'lote' => $lote,
                            'model' => $model,
                            'plantilla' => $plantilla,
                            'analizo' => $analizo,
                            'reviso' => $reviso,
                            'comprobacion' => $comprobacion,
                            'procedimiento' => $procedimiento,
                        );

                        $htmlHeader = view('exports.laboratorio.directos.materia_flotante.bitacoraHeader', $data);
                        $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                        $htmlCaptura = view('exports.laboratorio.directos.materia_flotante.bitacoraBody', $data);
                        $htmlFooter = view('exports.laboratorio.directos.materia_flotante.bitacoraFooter', $data);
                        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                        $mpdf->CSSselectMedia = 'mpdf';
                        $mpdf->WriteHTML($htmlCaptura);
                        break;
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
                        $reviso = User::where('id', @$lote->Id_superviso)->first();

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
                            'margin_top' => 47,
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
                        $reviso = User::where('id', @$lote->Id_superviso)->first();
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
                        $reviso = User::where('id', @$lote->Id_superviso)->first();

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
                        $reviso = User::where('id', @$lote->Id_superviso)->first();

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
                        $reviso = User::where('id', @$lote->Id_superviso)->first();

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
                    case 120:
                    case 65:
                        $mpdf = new \Mpdf\Mpdf([
                            'orientation' => 'P',
                            'format' => 'letter',
                            'margin_left' => 10,
                            'margin_right' => 10,
                            'margin_top' => 48,
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
                        $reviso = User::where('id', @$lote->Id_superviso)->first();
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
                        $reviso = User::where('id', @$lote->Id_superviso)->first();
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
                    case 130:
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
                        $reviso = User::where('id', @$lote->Id_superviso)->first();
                        $data = array(
                            'lote' => $lote,
                            'model' => $model,
                            'plantilla' => $plantilla,
                            'analizo' => $analizo,
                            'reviso' => $reviso,
                            'comprobacion' => $comprobacion,
                            'procedimiento' => $procedimiento,
                        );


                        $htmlHeader = view('exports.laboratorio.potable.calcio.bitacoraHeader', $data);
                        $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                        $htmlCaptura = view('exports.laboratorio.potable.calcio.bitacoraBody', $data);
                        $htmlFooter = view('exports.laboratorio.potable.calcio.bitacoraFooter', $data);
                        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                        $mpdf->CSSselectMedia = 'mpdf';
                        $mpdf->WriteHTML($htmlCaptura);
                        break;
                        case 261:
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
                            $reviso = User::where('id', @$lote->Id_superviso)->first();
                            $data = array(
                                'lote' => $lote,
                                'model' => $model,
                                'plantilla' => $plantilla,
                                'analizo' => $analizo,
                                'reviso' => $reviso,
                                'comprobacion' => $comprobacion,
                                'procedimiento' => $procedimiento,
                            );
                            $htmlHeader = view('exports.laboratorio.potable.magnesio.bitacoraHeader', $data);
                            $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                            $htmlCaptura = view('exports.laboratorio.potable.magnesio.bitacoraBody', $data);
                            $htmlFooter = view('exports.laboratorio.potable.magnesio.bitacoraFooter', $data);
                            $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                            $mpdf->CSSselectMedia = 'mpdf';
                            $mpdf->WriteHTML($htmlCaptura);
                            break;
                        case 28:
                        case 29:
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
                            $reviso = User::where('id', @$lote->Id_superviso)->first();
                            $data = array(
                                'lote' => $lote,
                                'model' => $model,
                                'plantilla' => $plantilla,
                                'analizo' => $analizo,
                                'reviso' => $reviso,
                                'comprobacion' => $comprobacion,
                                'procedimiento' => $procedimiento,
                            );
                            $htmlHeader = view('exports.laboratorio.directos.alcalinidad.bitacoraHeader', $data);
                            $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                            $htmlCaptura = view('exports.laboratorio.directos.alcalinidad.bitacoraBody', $data);
                            $htmlFooter = view('exports.laboratorio.directos.alcalinidad.bitacoraFooter', $data);
                            $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                            $mpdf->CSSselectMedia = 'mpdf';
                            $mpdf->WriteHTML($htmlCaptura);
                            break;
                        case 30:
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
                            $reviso = User::where('id', @$lote->Id_superviso)->first();
                            $data = array(
                                'lote' => $lote,
                                'model' => $model,
                                'plantilla' => $plantilla,
                                'analizo' => $analizo,
                                'reviso' => $reviso,
                                'comprobacion' => $comprobacion,
                                'procedimiento' => $procedimiento,
                            );
                            $htmlHeader = view('exports.laboratorio.directos.alcalinidadTotal.bitacoraHeader', $data);
                            $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                            $htmlCaptura = view('exports.laboratorio.directos.alcalinidadTotal.bitacoraBody', $data);
                            $htmlFooter = view('exports.laboratorio.directos.alcalinidadTotal.bitacoraFooter', $data);
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
                switch ($lote->Id_tecnica) {
                    case 77: //Dureza
                    case 251:
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
                        $reviso = User::where('id', @$lote->Id_superviso)->first();
                        $data = array(
                            'lote' => $lote,
                            'model' => $model,
                            'plantilla' => $plantilla,
                            'analizo' => $analizo,
                            'reviso' => $reviso,
                            'comprobacion' => $comprobacion,
                            'procedimiento' => $procedimiento,
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
                        $reviso = User::where('id', @$lote->Id_superviso)->first();
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
                    case 252:
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
                        $reviso = User::where('id', @$lote->Id_superviso)->first();
                        $data = array(
                            'lote' => $lote,
                            'model' => $model,
                            'plantilla' => $plantilla,
                            'analizo' => $analizo,
                            'reviso' => $reviso,
                            'comprobacion' => $comprobacion,
                            'procedimiento' => $procedimiento,
                        );


                        $htmlHeader = view('exports.laboratorio.potable.durezaDirecto.bitacoraHeader', $data);
                        $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                        $htmlCaptura = view('exports.laboratorio.potable.durezaDirecto.bitacoraBody', $data);
                        $htmlFooter = view('exports.laboratorio.potable.durezaDirecto.bitacoraFooter', $data);
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
                        $reviso = User::where('id', @$lote->Id_superviso)->first();
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
                        $reviso = User::where('id', @$lote->Id_superviso)->first();


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
                        $reviso = User::where('id', @$lote->Id_superviso)->first();


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
                        $reviso = User::where('id', @$lote->Id_superviso)->first();


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
                        $reviso = User::where('id', @$lote->Id_superviso)->first();


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
                        $reviso = User::where('id', @$lote->Id_superviso)->first();


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

                        $htmlFooter = view('exports.laboratorio.mb.coliformesTotales137.capturaFooter', $data);
                        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                        $htmlHeader = view('exports.laboratorio.mb.coliformesTotales137.capturaHeader', $data);
                        $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                        $htmlCaptura = view('exports.laboratorio.mb.coliformesTotales137.capturaBody', $data);
                        $mpdf->CSSselectMedia = 'mpdf';
                        $mpdf->WriteHTML($htmlCaptura);
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
                        $reviso = User::where('id', @$lote->Id_superviso)->first();

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
                        $reviso = User::where('id', @$lote->Id_superviso)->first();
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
                        $reviso = User::where('id', @$lote->Id_superviso)->first();
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
                        $reviso = User::where('id', @$lote->Id_superviso)->first();
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
                    case 39:
                        $mpdf = new \Mpdf\Mpdf([
                            'orientation' => 'P',
                            'format' => 'letter',
                            'margin_left' => 10,
                            'margin_right' => 10,
                            'margin_top' => 48,
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
                        $reviso = User::where('id', @$lote->Id_superviso)->first();
                        $data = array(
                            'lote' => $lote,
                            'model' => $model,
                            'plantilla' => $plantilla,
                            'analizo' => $analizo,
                            'reviso' => $reviso,
                            'comprobacion' => $comprobacion,
                            'procedimiento' => $procedimiento,
                        );
                        $htmlFooter = view('exports.laboratorio.mb.oxigenoD.capturaFooter', $data);
                        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                        $htmlHeader = view('exports.laboratorio.mb.oxigenoD.capturaHeader', $data);
                        $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                        $htmlCaptura = view('exports.laboratorio.mb.oxigenoD.capturaBody', $data);
                        $mpdf->WriteHTML($htmlCaptura);
                        $mpdf->CSSselectMedia = 'mpdf';
                        break;
                    default:
                        break;
                }
                break;
            case 2:
                return redirect()->to('admin/laboratorio/metales/exportPdfCaptura/'.$id);
                break;
            case 17:
                return redirect()->to('admin/laboratorio/metales/bitacoraIcp/'.$id);
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

        $model = Capsulas::all();

        foreach ($model as $item) {
            $temp = LoteDetalleSolidos::where('Id_crisol',$item->Id_capsula)->where('Liberado',0)->get();
            if ($temp->count()) {
                
            }else{
                $aux = Capsulas::find($item->Id_capsula);
                $aux->Estado = 0;
                $aux->save();
            }
        }

        $model = CrisolesGA::all();

        foreach ($model as $item) {
            $temp = LoteDetalleSolidos::where('Id_crisol',$item->Id_crisol)->where('Liberado',0)->get();
            if ($temp->count()) {
                
            }else{
                $aux = CrisolesGA::find($item->Id_crisol);
                $aux->Estado = 0;
                $aux->save();
            }
        }
    }
    public function setNormalidadAlc(Request $res)
    {
        $lote = LoteAnalisis::where('Id_lote',$res->idLote)->first();
        $model = ValoracionAlcalinidad::whereDate('Fecha_inicio','<=',$res->fechaIni)
            ->whereDate('Fecha_fin','>=',$res->fechaFin)
            ->where('Id_parametro',$lote->Id_tecnica)->get();
        $resultado = $res->granoCarbon1 / ($res->tituladodeH1 * $res->equivalenteAlc) * $res->factConversionAlc;
        if($model->count()){
            $temp = ValoracionAlcalinidad::find($model[0]->Id_valoracion);
            $temp->Id_parametro = $lote->Id_tecnica;
            $temp->Granos_carbon1 = $res->granoCarbon1;
            $temp->Granos_carbon2 = $res->granoCarbon2;
            $temp->Granos_carbon3 = $res->granoCarbon3;
            $temp->Titulado1 = $res->tituladodeH1;
            $temp->Titulado2 = $res->tituladodeH2;
            $temp->Titulado3 = $res->tituladodeH3;
            $temp->Granos_equivalente = $res->equivalenteAlc;
            $temp->Factor_conversion = $res->factConversionAlc;
            // $temp->Fecha_inicio = $res->fechaIni;
            // $temp->Fecha_fin = $res->fechaFin;
            $temp->Resultado = number_format($resultado,3);
            $temp->save();
        }else{
            ValoracionAlcalinidad::create([ 
                'Id_parametro' => $lote->Id_tecnica, 
                'Granos_carbon1' => $res->granoCarbon1,
                'Granos_carbon2' => $res->granoCarbon2,
                'Granos_carbon3' => $res->granoCarbon3,
                'Titulado1' => $res->tituladodeH1,
                'Titulado2' => $res->tituladodeH2,
                'Titulado3' => $res->tituladodeH3,
                'Granos_equivalente' => $res->equivalenteAlc,
                'Factor_conversion' => $res->factConversionAlc,
                'Fecha_inicio' => $res->fechaIni,
                'Fecha_fin' => $res->fechaFin,
                'Resultado' => number_format($resultado,3),
            ]);
        }

        $model = ValoracionAlcalinidad::whereDate('Fecha_inicio','<=',$res->fechaIni)
        ->whereDate('Fecha_fin','>=',$res->fechaFin)
        ->where('Id_parametro',$lote->Id_tecnica)->get();

        $data = array(
            'model' => $model,
        );

        return response()->json($data); 
            
    }
    public function updateTituloBitacora($id)
    { 
        $model = LoteAnalisis::where('Id_tecnica',$id)->get();
        $bitModel = PlantillaBitacora::where('Id_parametro',$id)->first();
        foreach ($model as $item) {
            echo "<br> Id_lote".$item->Id_lote;
            $temp = Bitacoras::where('Id_lote',$item->Id_lote)->get();
            if ($temp->count()) {
                $temp[0]->Titulo = $bitModel->Titulo;
                $temp[0]->Rev = $bitModel->Rev;
                $temp[0]->save();
            }
        }
    }
    public function updateVolumenMetales()
    {
        $volMuestra = 0;
        $model = LoteDetalle::all();
        foreach($model as $item)
        {
            $volMuestra = 0;
            switch ($item->Id_parametro) {
                case 20:
                case 21:
                case 23:
                case 18:
                case 24:
                case 25:
                case 232:
                case 187:
                case 226:
                case 197:
                case 351:
                case 41:
                case 354:
                case 353:
                case 355:
                case 356:
                case 22:
                case 352:
                    $volMuestra = 50;
                    break;
                case 216:
                case 210:
                case 208:
                    $volMuestra = 45;
                    break;
                case 215:
                    $volMuestra = 45;
                    break;
                case 191:
                case 194:
                case 189:
                case 192:
                case 204:
                case 190:
                case 196:
                    $volMuestra = 100;
                break;
                case 188:
                case 219:
                case 195:
                    $volMuestra = 80;
                break;
                case 230:
                    $volMuestra = 100;
                    break;
                case 215:
                    $volMuestra = 45;
                    break;
                default:
                    $volMuestra = 100;
                    break;
                }
            
            if ($item->Vol_muestra == NULL) {
                $temp = LoteDetalle::find($item->Id_detalle);
                $temp->Vol_muestra = $volMuestra;
                $temp->save();
            }
        }
    }
    public function updateVolMuestraMetales($id)
    {
        $model = LoteDetalle::where('Id_parametro',$id)->get();
        
        foreach ($model as $item) {
            switch ($item->Id_control) { 
                case 2:
                case 9:
                case 3:
                case 1:
                    $temp = LoteDetalle::find($item->Id_detalle); 
                    $temp->Vol_muestra = 45;
                    $temp->save();
                    break;
                default:
                    
                    break;
            }
        }
    }
    public function updateVolFinalMetales()
    {
        $model = LoteDetalle::all();
        foreach ($model as $item) {
            switch ($item->Id_parametro) {
                case 216:
                case 210:
                case 208:
                    if ($item->Vol_final == 0) {
                        $temp = LoteDetalle::where('Id_detalle',$item->Id_detalle)->first();
                        $temp->Vol_final = 50;
                        $temp->save();
                    }
                    break;
                case 215:
                    if ($item->Vol_final == 0) {
                        $temp = LoteDetalle::where('Id_detalle',$item->Id_detalle)->first();
                        $temp->Vol_final = 100;
                        $temp->save();
                    }
                    break;
                case 191:
                case 194:
                case 189:
                case 192:
                case 204:
                case 190:
                case 196:
                case 188:
                case 219:
                case 195:
                case 230:
                case 215:
                    if ($item->Vol_final == 0) {
                        $temp = LoteDetalle::where('Id_detalle',$item->Id_detalle)->first();
                        $temp->Vol_final = 100;
                        $temp->save();
                    }
                    break;
                default:
                    
                    break;
            }
        }
    }
    public function getHistorial(Request $res)
    {
        $codigo = CodigoParametros::where('Id_codigo',$res->idCodigo)->first();
        $solicitud = Solicitud::where('Id_solicitud',$codigo->Id_solicitud)->first();
        
        $histSol = Solicitud::where('Padre',0)->where('Id_sucursal',$solicitud->Id_sucursal)->get();
        $data = array(
            
            
        );
        return response()->json($data);
    }
    public function updateContadorLotes()
    {
        $lote = LoteAnalisis::all();
        foreach ($lote as $item) {
            $parametro = Parametro::where('Id_parametro',$item->Id_tecnica)->first();
            switch ($parametro->Id_area) {
                case 16: // Espectrofotometria
                case 5: // Fisicoquimicos
                    switch ($item->Id_tecnica) {
                        case 152: // COT
                        case 99: // Cianuros
                        case 19:
                        case 118:
                            $temp = LoteDetalleEspectro::where('Id_lote',$item->Id_lote)->get();
                            $temp2 = LoteDetalleEspectro::where('Id_lote',$item->Id_lote)->where('Liberado',1)->get();
                            break;
                        default:
                            $temp = LoteDetalleEspectro::where('Id_lote',$item->Id_lote)->get();
                            $temp2 = LoteDetalleEspectro::where('Id_lote',$item->Id_lote)->where('Liberado',1)->get();
                            break;
                    }
                    break;
                case 13: // G&A
                    $temp = LoteDetalleGA::where('Id_lote',$item->Id_lote)->get();
                    $temp2 = LoteDetalleGA::where('Id_lote',$item->Id_lote)->where('Liberado',1)->get();
                    break;
                case 15: //Solidos
                    $temp = LoteDetalleSolidos::where('Id_lote',$item->Id_lote)->get();
                    $temp2 = LoteDetalleSolidos::where('Id_lote',$item->Id_lote)->where('Liberado',1)->get();
                    break;
                case 14: //volumetria
                    switch ($item->Id_tecnica) {
                        case 6:
                        case 161:
                            $temp = LoteDetalleDqo::where('Id_lote',$item->Id_lote)->get();
                            $temp2 = LoteDetalleDqo::where('Id_lote',$item->Id_lote)->where('Liberado',1)->get();
                            break;
                        case 33:
                        case 218:
                        case 119:
                        case 64:
                            $temp = LoteDetalleCloro::where('Id_lote',$item->Id_lote)->get();
                            $temp2 = LoteDetalleCloro::where('Id_lote',$item->Id_lote)->where('Liberado',1)->get();
                            break;
                        case 9:
                        case 10:
                        case 11:
                        case 287:
                        case 83:
                        case 108:
                            $temp = LoteDetalleNitrogeno::where('Id_lote',$item->Id_lote)->get();
                            $temp2 = LoteDetalleNitrogeno::where('Id_lote',$item->Id_lote)->where('Liberado',1)->get();
                            break;
                        case 28:
                        case 29:
                        case 30:
                            $temp = LoteDetalleAlcalinidad::where('Id_lote',$item->Id_lote)->get();
                            $temp2 = LoteDetalleAlcalinidad::where('Id_lote',$item->Id_lote)->where('Liberado',1)->get();
                            break;
                        default:
                            # code...
                            break;
                    }
                    break;
                case 7: //Campo
                case 19: // Directos
                    $temp = LoteDetalleDirectos::where('Id_lote',$item->Id_lote)->get();
                    $temp2 = LoteDetalleDirectos::where('Id_lote',$item->Id_lote)->where('Liberado',1)->get();
                    break;
                case 8: //Potable
                    switch ($item->Id_tecnica) {
                        case 77: //Dureza
                        case 103:
                        case 251:
                        case 252:
                            $temp = LoteDetalleDureza::where('Id_lote',$item->Id_lote)->get();
                            $temp2 = LoteDetalleDureza::where('Id_lote',$item->Id_lote)->where('Liberado',1)->get();
                            break;
                        default:
                            $temp = LoteDetallePotable::where('Id_lote',$item->Id_lote)->get();
                            $temp2 = LoteDetallePotable::where('Id_lote',$item->Id_lote)->where('Liberado',1)->get();
                            break;
                    }
                    break;
                case 6: // Mb
                case 12:
                case 3:
                    switch ($item->Id_tecnica) {
                        case 135: // Coliformes fecales
                        case 132:
                        case 133:
                        case 12:
                        case 134: // termotolerantes
                        case 35:
                        case 51: // Coliformes totales
                        case 137:
                            $temp = LoteDetalleColiformes::where('Id_lote',$item->Id_lote)->get();
                            $temp2 = LoteDetalleColiformes::where('Id_lote',$item->Id_lote)->where('Liberado',1)->get();
                            break;
                        case 253: //todo  ENTEROCOCO FECAL
                            $temp = LoteDetalleEnterococos::where('Id_lote',$item->Id_lote)->get();
                            $temp2 = LoteDetalleEnterococos::where('Id_lote',$item->Id_lote)->where('Liberado',1)->get();
                            break;
                        case 5: //todo DEMANDA BIOQUIMICA DE OXIGENO (DBO5) 
                        case 71:
                
                            $temp = LoteDetalleDbo::where('Id_lote',$item->Id_lote)->get();
                            $temp2 = LoteDetalleDbo::where('Id_lote',$item->Id_lote)->where('Liberado',1)->get();
                            break;
                        case 70:
                            $temp = LoteDetalleDboIno::where('Id_lote',$item->Id_lote)->get();
                            $temp2 = LoteDetalleDboIno::where('Id_lote',$item->Id_lote)->where('Liberado',1)->get();
                            break;
                        case 16: //todo Huevos de Helminto 
                            $temp = LoteDetalleHH::where('Id_lote',$item->Id_lote)->get();
                            $temp2 = LoteDetalleHH::where('Id_lote',$item->Id_lote)->where('Liberado',1)->get();
                            break;
                        case 78:
                            $temp = LoteDetalleEcoli::where('Id_lote',$item->Id_lote)->get();
                            $temp2 = LoteDetalleEcoli::where('Id_lote',$item->Id_lote)->where('Liberado',1)->get();
                            break;
                        default:
                            $temp = LoteDetalleDirectos::where('Id_lote',$item->Id_lote)->get();
                            $temp2 = LoteDetalleDirectos::where('Id_lote',$item->Id_lote)->where('Liberado',1)->get();
                            break;
                    }
                    break;
                default:
                     $temp = LoteDetalleDirectos::where('Id_lote',$item->Id_lote)->get();
                     $temp2 = LoteDetalleDirectos::where('Id_lote',$item->Id_lote)->where('Liberado',1)->get();
                    break;
            }
            $lote = LoteAnalisis::find($item->Id_lote);
            $lote->Asignado = $temp->count();
            $lote->Liberado = $temp2->count();
            $lote->save();
        }
    }
    public function replicarDatosEcoli()
    {
        $lote = LoteDetalleColiformes::where('Id_parametro',35)->get();
        foreach ($lote as $item) {
            if ($item->Confirmativa10 == null && $item->Confirmativa13 == null && $item->Confirmativa18 == null) {
                $temp = LoteDetalleColiformes::where('Id_detalle',$item->Id_detalle)->first();
                $temp->Confirmativa10 = $item->Confirmativa1;
                $temp->Confirmativa11 = $item->Confirmativa2;
                $temp->Confirmativa12 = $item->Confirmativa3;
                $temp->Confirmativa13 = $item->Confirmativa4;
                $temp->Confirmativa14 = $item->Confirmativa5;
                $temp->Confirmativa15 = $item->Confirmativa6;
                $temp->Confirmativa16 = $item->Confirmativa7;
                $temp->Confirmativa17 = $item->Confirmativa8;
                $temp->Confirmativa18 = $item->Confirmativa9;
                $temp->save();
            }
        }
    }
    public function updateDefaultLoteMetales()
    {
        $lote = LoteAnalisis::where('Id_area',2)->get();
        foreach ($lote as $item) {
            $confModel = ConfiguracionMetales::where('Id_parametro',$item->Id_tecnica)->get();
            $aux = MetalesDetalle::where('Id_lote',$item->Id_lote)->get();
           
            if ($confModel->count()) {
                if ($aux->count()) {
                    $temp = MetalesDetalle::where('Id_lote',$item->Id_lote)->first();
                    $temp->Longitud_onda = $confModel[0]->Longitud_onda;
                    $temp->No_inventario = $confModel[0]->No_Inventario;
                    $temp->Corriente = $confModel[0]->Lampara;
                    $temp->Gas = $confModel[0]->Acetileno;
                    $temp->Flujo_gas = $confModel[0]->Flujo_gas; 
                    $temp->No_lampara = $confModel[0]->No_lampara;
                    $temp->Energia = $confModel[0]->Energia;
                    $temp->Aire = $confModel[0]->Aire;
                    $temp->Equipo = $confModel[0]->Equipo;
                    $temp->Slit = $confModel[0]->Slit;
                    $temp->Conc_std = $confModel[0]->Concentracion;
                    $temp->Oxido_nitroso = $confModel[0]->Oxido_nitroso;
                    $temp->Valor = $confModel[0]->Valor;
                    $temp->save();
                }
               
            }
            
        }
    }
    public function updateBitacoraFolioMetales()
    {
        $temp = MetalesDetalle2::all();
        foreach ($temp as $item) {
            $temp2 = MetalesDetalle2::where('Id_lote',$item->Id_lote)->first();
            $aux = MetalesDetalle::where('Id_lote',$item->Id_lote)->first();
            $aux->Bitacora = $temp2->Bitacora;
            $aux->Folio = $temp2->Folio;
            $aux->save();
        }
    
    }
    public function updateMicroGramoMetales()
    {
        $model = LoteDetalle::where('Liberado',1)->get();
        foreach ($model as $item) {
            $detalleModel = LoteDetalle::where('Id_detalle', $item->Id_detalle)->first();
            $loteTemp = LoteAnalisis::where('Id_lote',$detalleModel->Id_lote)->first();
            $fecha = new Carbon($loteTemp->Fecha);
            $today = $fecha->toDateString();
            $parametroModel = Parametro::where('Id_matriz', 12)->where('Id_parametro', $detalleModel->Id_parametro)->get();
            $parametro = Parametro::where('Id_parametro', $item->Id_parametro)->first();
            $curvaConstantes  = CurvaConstantes::whereDate('Fecha_inicio', '<=', $today)->whereDate('Fecha_fin', '>=', $today)
                ->where('Id_parametro', $parametro->Id_parametro)->first();

                $x = $item->Abs1;
                $y = $item->Abs2;
                $z = $item->Abs3;
                $FD = $item->Factor_dilucion;
                $FC = $item->Factor_conversion;
                $suma = ($x + $y + $z);
                $promedio = round(($suma / 3),4);
                $volFinal = 0;
                $resMicro = 0;
                
                $resultado = "";

                switch ($parametro->Id_matriz) {
                    case 14: // MetalPotable
                    case 8:
                        switch ($parametro->Id_parametro) {
                            case  215:
                            $temp =   (($promedio - $curvaConstantes->B) / $curvaConstantes->M) * $FD * $item->Vol_final;   
                            $resMicro = $temp;
                            @$resultado = ($temp ) / ($item->Vol_muestra * $FC);
                                break;
                            default:
                            $paso1 = (($promedio - $curvaConstantes->B) / $curvaConstantes->M) * $FD;
                            $resMicro = $paso1;
                            @$resultado = ($paso1 * 1) / $FC;   
                                break;
                        }
                        break;
                    case 13: //Metal purificado
                    case 9:
                        switch ($parametro->Id_parametro) {
                            case 190: 
                            case 192:
                            case 204:
                            case 196:
                            case 191:
                            case 194:
                            case 189:
                                $volFinal = $item->Vol_final;
                                $temp =   (((($promedio - $curvaConstantes->B) / $curvaConstantes->M) * $FD) * $item->Vol_final);   
                                $resMicro = $temp;
                               @$resultado = (($temp ) / ($item->Vol_muestra * $FC)); 
                                break;
                            default:
                                $temp =   (((($promedio - $curvaConstantes->B) / $curvaConstantes->M) * $FD) * $item->Vol_final);   
                                $resMicro = $temp;
                                @$resultado = ($temp ) / ($item->Vol_muestra * $FC); 
                                break;
                        }
                        break;
                    default:            
                        $fechaResidual = \Carbon\Carbon::parse(@$loteTemp->Fecha)->format('Y-m-d');
                        $fechaLimite = \Carbon\Carbon::parse("2024-01-08")->format('Y-m-d');
                        if ($fechaResidual <= $fechaLimite) {
                            $sw = false;
                            $promedio = round(($suma / 3),4);
                        }else{
                            $sw = true;
                            $promedio = round(($suma / 3),3);
                        }
                
                        if ($parametroModel->count()) {
                            if ($detalleModel->Descripcion != "Resultado") {
                                $resultado = (($promedio - $curvaConstantes->B) / $curvaConstantes->M) * $FD;
                            } else {
                                $resultado = ((($promedio - $curvaConstantes->B) / $curvaConstantes->M) * $FD);
                                $resMicro = $resultado;
                                $resultado = $resultado / $FC;
                            }
                        } else {
                                $resultado = (($promedio - $curvaConstantes->B) / $curvaConstantes->M) * $FD;      
                        } 
                    break;
                }
                 
                    $detalle = LoteDetalle::find($item->Id_detalle);
                    $detalle->Resultado_microgramo = $resMicro;
                    $detalle->save();
        }
    }

    public function getUltimoLote(Request $res)
    {
        $model = LoteAnalisis::where('Id_tecnica',$res->id)->orderBy('Id_lote','DESC')->first();
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function matracesDuplicados(){
        $cont = 0;
        $lote = LoteAnalisis::where('Id_tecnica',13)->get();
        foreach ($lote as $item) {
            $model = DB::table('viewlotedetallega')->where('Id_lote',$item->Id_lote)->get();
            foreach ($model as $item2) {
                $temp = LoteDetalleGA::where('Id_matraz',$item2->Id_matraz)->where('Id_lote',$item2->Id_lote)->get();
                if ($temp->count() > 1) {
                    echo '<br>Fecha: '.$item->Fecha .'  -  '.'Id: '.$item2->Id_lote.'  -  '.$item2->Codigo . '  -  Matraz: '.$item2->Matraz;
                    $cont++;
                }
            }
        }
        echo "<br> Total: ".($cont / 2);
    } 
    public function crisolDuplicado($id){ 
        $cont = 0;
        $lote = LoteAnalisis::where('Id_tecnica',$id)->get();
        foreach ($lote as $item) {
            $model = DB::table('viewlotedetallesolidos')->where('Id_lote',$item->Id_lote)->get();
            foreach ($model as $item2) {
                $temp = LoteDetalleSolidos::where('Id_crisol',$item2->Id_crisol)->where('Id_lote',$item2->Id_lote)->get();
                if ($temp->count() > 1) {
                    echo '<br>Fecha: '.$item->Fecha .'  -  '.'Id: '.$item2->Id_lote.'  -  '.$item2->Codigo . '  -  Matraz: '.$item2->Crisol;
                    $cont++;
                }
            }
        }
        echo "<br> Total: ".($cont / 2);
    }
    public function pruebaRandom(){
        $numeroAleatorio = rand(1, 5); // Genera un número entre 1 y 5
        $resultado = $numeroAleatorio / 10000; // Divide por 1000 para obtener el rango deseado
        echo "Número aleatorio entre 0.001 y 0.005: " . number_format($resultado, 4);
    
    }
    public function updateMatrazDuplicado(){
        $lote = LoteAnalisis::where('Id_tecnica',13)->get();
        $aux = 0;
        $idMatraz = 0;
        foreach ($lote as $item) {
            $model = DB::table('viewlotedetallega')->where('Id_lote',$item->Id_lote)->get();
            foreach ($model as $item2) {
                $aux = 0;
                $temp = LoteDetalleGA::where('Id_matraz',$item2->Id_matraz)->where('Id_lote',$item2->Id_lote)->get();
                if ($temp->count() > 1) {
                    
                    foreach ($temp as $value) {
                        $idMatraz = 0;
                        if ($aux != 0) {
                           if ($value->Matraz != 0) {
                                $matraz = MatrazGA::all();

                                regresar:
                                $mat = rand(0, $matraz->count());
                                $valMatraz = LoteDetalleGA::where('Id_matraz',$matraz[$mat]->Id_matraz)->where('Id_lote',$item2->Id_lote)->get();
                                if ($valMatraz->count()) {
                                    goto regresar;
                                }
                                $idMatraz = $matraz[$mat]->Id_Matraz;

                                $loteDetalle = LoteDetalleGA::where('Id_detalle',$value->Id_detalle)->first();

                                $dif = ($matraz[$mat]->Max - $matraz[$mat]->Min);
                                $ran = (round($dif, 4)) / 10;
                                $m3 = $matraz[$mat]->Max - $ran;

                                $mf = (((($loteDetalle->Resultado + $loteDetalle->Blanco) / $loteDetalle->F_conversion) * $loteDetalle->Vol_muestra) + $m3);
                            
                                $numeroAleatorio = rand(0, 3); // Genera un número entre 1 y 5
                                $valRandom = number_format($numeroAleatorio / 10000,4); // Divide por 1000 para obtener el rango deseado
                                $m2 = ($m3 + $valRandom);

                                $numeroAleatorio = rand(0, 3); // Genera un número entre 1 y 5
                                $valRandom = number_format($numeroAleatorio / 10000,4); // Divide por 1000 para obtener el rango deseado
                                $m1 = ($m2 + $valRandom);


                                $auxMf = ((round($mf,4) - $m3) / $loteDetalle->Vol_muestra) * $loteDetalle->F_conversion;
                                $resultado = $auxMf - $loteDetalle->Blanco;

                                $model = LoteDetalleGA::find($value->Id_detalle);
                                $model->Id_matraz = $matraz[$mat]->Id_matraz;
                                $model->Matraz = $matraz[$mat]->Num_serie;
                                $model->M_final = round($mf,4);
                                $model->M_inicial1 = $m1;
                                $model->M_inicial2 = $m2;
                                $model->M_inicial3 = $m3;
                                $model->Resultado = round($resultado,2);
                                $model->Analizo = Auth::user()->id;
                                $model->save();
                           }
                        }else{
                            $aux++;
                        }
                    }
                }
            }
        }
    }
    public function metodoCortoColiformes (Request $res)
    {
        $msg = "";
        $convinacion = Nmp1Micro::where('Nmp', $res->NMP)->get();
        $metodoCorto = 1;
        $auxCon = array();
        $auxCon2 = array();
        $auxPre1 = array();
        $auxPre2 = array();
        switch ($res->idParametro) {
            case 12:
                if ($convinacion->count()){
                    $convinacion = Nmp1Micro::where('Nmp', $res->NMP)->first();
                    $positivos = $convinacion->Col1 + $convinacion->Col2 + $convinacion->Col3;
                    if($res->D1 == 10 && $res->D2 == 1 && $res->D3 == 0.1){
                        $resultado = $convinacion->Nmp;
                    } else {
                        $resultado = (10 / $res->D1) * $convinacion->Nmp;
                    }
        
                    for ($i = 0; $i < 3; $i++) { 
                        if ($i + 1 <= $convinacion->Col1) {
                            $auxCon[$i] = 1;
                        }else{
                            $auxCon[$i] = 0;
                        }
                        if ($i + 1 <= $convinacion->Col2) {
                            $auxCon[$i + 3] = 1;
                        }else{
                            $auxCon[$i + 3] = 0;
                        }
                        if ($i + 1 <= $convinacion->Col3) {
                            $auxCon[$i + 6] = 1;
                        }else{
                            $auxCon[$i + 6] = 0;
                        }
                    }
        
                    for ($i=0; $i < sizeof($auxCon) ; $i++) { 
                        if ($auxCon[$i] == 1) {
                            $auxPre2[$i] = 1; 
                        }else{
                            $bit_aleatorio = rand() & 1;
                            if($bit_aleatorio == 1){
                                $auxPre2[$i] = 1; 
                            }else{
                                $auxPre2[$i] = 0; 
                            }
                        }
                    }
                    for ($i=0; $i < sizeof($auxCon) ; $i++) { 
                        if ($auxPre2[$i] == 0) {
                            $auxPre1[$i] = 0; 
                        }else{
                            $bit_aleatorio = rand() & 1;
                            if($bit_aleatorio == 1){
                                $auxPre1[$i] = 1; 
                            }else{
                                $auxPre1[$i] = 0; 
                            }
                        }
                    }
                    
                    $metodoCorto = 1;
                    $model = LoteDetalleColiformes::find($res->idDetalle);
                    $model->Tipo = 1;
                    $model->Dilucion1 = $res->D1;
                    $model->Dilucion2 = $res->D2;
                    $model->Dilucion3 = $res->D3;
                    $model->Indice = $res->NMP;
                    $model->Tubos_negativos = 9 - $positivos;
                    $model->Tubos_positivos = $positivos;
        
                    $model->Confirmativa1 = $auxCon[0];
                    $model->Confirmativa2 = $auxCon[1];
                    $model->Confirmativa3 = $auxCon[2];
                    $model->Confirmativa4 = $auxCon[3];
                    $model->Confirmativa5 = $auxCon[4];
                    $model->Confirmativa6 = $auxCon[5];
                    $model->Confirmativa7 = $auxCon[6];
                    $model->Confirmativa8 = $auxCon[7];
                    $model->Confirmativa9 = $auxCon[8];
        
                    $model->Presuntiva1 = $auxPre1[0];
                    $model->Presuntiva2 = $auxPre1[1];
                    $model->Presuntiva3 = $auxPre1[2];
                    $model->Presuntiva4 = $auxPre1[3];
                    $model->Presuntiva5 = $auxPre1[4];
                    $model->Presuntiva6 = $auxPre1[5];
                    $model->Presuntiva7 = $auxPre1[6];
                    $model->Presuntiva8 = $auxPre1[7];
                    $model->Presuntiva9 = $auxPre1[8];
        
                    $model->Presuntiva10 = $auxPre2[0];
                    $model->Presuntiva11 = $auxPre2[1];
                    $model->Presuntiva12 = $auxPre2[2];
                    $model->Presuntiva13 = $auxPre2[3];
                    $model->Presuntiva14 = $auxPre2[4];
                    $model->Presuntiva15 = $auxPre2[5];
                    $model->Presuntiva16 = $auxPre2[6];
                    $model->Presuntiva17 = $auxPre2[7];
                    $model->Presuntiva18 = $auxPre2[8];
                    $model->Resultado = $resultado;
                    $model->save();
                    $msg = "Resultado calculado";
        
                }else{
                    $msg = "Este valor no se encuentra en tabla";
                }
                break;
            case 137:
                if ($convinacion->count()){
                    $convinacion = Nmp1Micro::where('Nmp', $res->NMP)->first();
                    $positivos = $convinacion->Col1 + $convinacion->Col2 + $convinacion->Col3;
                    if($res->D1 == 10 && $res->D2 == 1 && $res->D3 == 0.1){
                        $resultado = $convinacion->Nmp;
                    } else {
                        $resultado = (10 / $res->D1) * $convinacion->Nmp;
                    }
        
                    for ($i = 0; $i < 3; $i++) { 
                        if ($i + 1 <= $convinacion->Col1) {
                            $auxCon[$i] = 1;
                        }else{
                            $auxCon[$i] = 0;
                        }
                        if ($i + 1 <= $convinacion->Col2) {
                            $auxCon[$i + 3] = 1;
                        }else{
                            $auxCon[$i + 3] = 0;
                        }
                        if ($i + 1 <= $convinacion->Col3) {
                            $auxCon[$i + 6] = 1;
                        }else{
                            $auxCon[$i + 6] = 0;
                        }
                    }
                    for ($i=0; $i < sizeof($auxCon) ; $i++) { 
                        if ($auxCon[$i] == 0) {
                            $auxCon2[$i] = 0; 
                        }else{
                            $bit_aleatorio = rand() & 1;
                            if($bit_aleatorio == 1){
                                $auxCon2[$i] = 1; 
                            }else{
                                $auxCon2[$i] = 0; 
                            }
                        }
                    }
                    for ($i=0; $i < sizeof($auxCon) ; $i++) { 
                        if ($auxCon[$i] == 1) {
                            $auxPre2[$i] = 1; 
                        }else{
                            $bit_aleatorio = rand() & 1;
                            if($bit_aleatorio == 1){
                                $auxPre2[$i] = 1; 
                            }else{                                
                                if($bit_aleatorio == 1){
                                    $auxPre2[$i] = 1; 
                                }else{
                                    if($bit_aleatorio == 1){
                                        $auxPre2[$i] = 1; 
                                    }else{
                                        $auxPre2[$i] = 0; 
                                    }
                                }
                            }
                        }
                    }
                    for ($i=0; $i < sizeof($auxCon) ; $i++) { 
                        if ($auxPre2[$i] == 0) {
                            $auxPre1[$i] = 0; 
                        }else{
                            $bit_aleatorio = rand() & 1;
                            if($bit_aleatorio == 1){
                                $auxPre1[$i] = 1; 
                            }else{
                                $auxPre1[$i] = 0; 
                            }
                        }
                    }
                    
                    $metodoCorto = 1;
                    $model = LoteDetalleColiformes::find($res->idDetalle);
                    $model->Tipo = 1;
                    $model->Dilucion1 = $res->D1;
                    $model->Dilucion2 = $res->D2;
                    $model->Dilucion3 = $res->D3;
                    $model->Indice = $res->NMP;
                    $model->Tubos_negativos = 9 - $positivos;
                    $model->Tubos_positivos = $positivos;
        
                    $model->Confirmativa1 =  $auxCon2[0];
                    $model->Confirmativa2 =  $auxCon2[1];
                    $model->Confirmativa3 =  $auxCon2[2];
                    $model->Confirmativa4 =  $auxCon2[3];
                    $model->Confirmativa5 =  $auxCon2[4];
                    $model->Confirmativa6 =  $auxCon2[5];
                    $model->Confirmativa7 =  $auxCon2[6];
                    $model->Confirmativa8 =  $auxCon2[7];
                    $model->Confirmativa9 =  $auxCon2[8];

                    $model->Confirmativa10 = $auxCon[0];
                    $model->Confirmativa11 = $auxCon[1];
                    $model->Confirmativa12 = $auxCon[2];
                    $model->Confirmativa13 = $auxCon[3];
                    $model->Confirmativa14 = $auxCon[4];
                    $model->Confirmativa15 = $auxCon[5];
                    $model->Confirmativa16 = $auxCon[6];
                    $model->Confirmativa17 = $auxCon[7];
                    $model->Confirmativa18 = $auxCon[8];
        
                    $model->Presuntiva1 = $auxPre1[0];
                    $model->Presuntiva2 = $auxPre1[1];
                    $model->Presuntiva3 = $auxPre1[2];
                    $model->Presuntiva4 = $auxPre1[3];
                    $model->Presuntiva5 = $auxPre1[4];
                    $model->Presuntiva6 = $auxPre1[5];
                    $model->Presuntiva7 = $auxPre1[6];
                    $model->Presuntiva8 = $auxPre1[7];
                    $model->Presuntiva9 = $auxPre1[8];
                    
        
                    $model->Presuntiva10 = $auxPre2[0];
                    $model->Presuntiva11 = $auxPre2[1];
                    $model->Presuntiva12 = $auxPre2[2];
                    $model->Presuntiva13 = $auxPre2[3];
                    $model->Presuntiva14 = $auxPre2[4];
                    $model->Presuntiva15 = $auxPre2[5];
                    $model->Presuntiva16 = $auxPre2[6];
                    $model->Presuntiva17 = $auxPre2[7];
                    $model->Presuntiva18 = $auxPre2[8];
                    $model->Resultado = $resultado;
                    $model->save();
                    $msg = "Resultado calculado";
        
                }else{
                    $msg = "Este valor no se encuentra en tabla";
                }
            break;
            case 35:
                if ($convinacion->count()){
                    $convinacion = Nmp1Micro::where('Nmp', $res->NMP)->first();
                    $positivos = $convinacion->Col1 + $convinacion->Col2 + $convinacion->Col3;
                    if($res->D1 == 10 && $res->D2 == 1 && $res->D3 == 0.1){
                        $resultado = $convinacion->Nmp;
                    } else {
                        $resultado = (10 / $res->D1) * $convinacion->Nmp;
                    }
        
                    for ($i = 0; $i < 3; $i++) { 
                        if ($i + 1 <= $convinacion->Col1) {
                            $auxCon[$i] = 1;
                        }else{
                            $auxCon[$i] = 0;
                        }
                        if ($i + 1 <= $convinacion->Col2) {
                            $auxCon[$i + 3] = 1;
                        }else{
                            $auxCon[$i + 3] = 0;
                        }
                        if ($i + 1 <= $convinacion->Col3) {
                            $auxCon[$i + 6] = 1;
                        }else{
                            $auxCon[$i + 6] = 0;
                        }
                    }
                    for ($i=0; $i < sizeof($auxCon) ; $i++) { 
                        if ($auxCon[$i] == 1) {
                            $auxCon2[$i] = 1; 
                        }else{
                            $bit_aleatorio = rand() & 1;
                            if($bit_aleatorio == 1){
                                $auxCon2[$i] = 1; 
                            }else{
                                $auxCon2[$i] = 0; 
                            }
                        }
                    }
                    for ($i=0; $i < sizeof($auxCon) ; $i++) { 
                        if ($auxCon2[$i] == 1) {
                            $auxPre2[$i] = 1; 
                        }else{
                            $bit_aleatorio = rand() & 1;
                            if($bit_aleatorio == 1){
                                $auxPre2[$i] = 1; 
                            }else{
                                $auxPre2[$i] = 0; 
                            }
                        }
                    }
                    for ($i=0; $i < sizeof($auxCon) ; $i++) { 
                        if ($auxPre2[$i] == 0) {
                            $auxPre1[$i] = 0; 
                        }else{
                            $bit_aleatorio = rand() & 1;
                            if($bit_aleatorio == 1){
                                $auxPre1[$i] = 1; 
                            }else{
                                $auxPre1[$i] = 0; 
                            }
                        }
                    }
                    
                    $metodoCorto = 1;
                    $model = LoteDetalleColiformes::find($res->idDetalle);
                    $model->Tipo = 1;
                    $model->Dilucion1 = $res->D1;
                    $model->Dilucion2 = $res->D2;
                    $model->Dilucion3 = $res->D3;
                    $model->Indice = $res->NMP;
                    $model->Tubos_negativos = 9 - $positivos;
                    $model->Tubos_positivos = $positivos;
        
                    $model->Confirmativa1 =  $auxCon[0];
                    $model->Confirmativa2 =  $auxCon[1];
                    $model->Confirmativa3 =  $auxCon[2];
                    $model->Confirmativa4 =  $auxCon[3];
                    $model->Confirmativa5 =  $auxCon[4];
                    $model->Confirmativa6 =  $auxCon[5];
                    $model->Confirmativa7 =  $auxCon[6];
                    $model->Confirmativa8 =  $auxCon[7];
                    $model->Confirmativa9 =  $auxCon[8];

                    $model->Confirmativa10 = $auxCon2[0];
                    $model->Confirmativa11 = $auxCon2[1];
                    $model->Confirmativa12 = $auxCon2[2];
                    $model->Confirmativa13 = $auxCon2[3];
                    $model->Confirmativa14 = $auxCon2[4];
                    $model->Confirmativa15 = $auxCon2[5];
                    $model->Confirmativa16 = $auxCon2[6];
                    $model->Confirmativa17 = $auxCon2[7];
                    $model->Confirmativa18 = $auxCon2[8];
        
                    $model->Presuntiva1 = $auxPre1[0];
                    $model->Presuntiva2 = $auxPre1[1];
                    $model->Presuntiva3 = $auxPre1[2];
                    $model->Presuntiva4 = $auxPre1[3];
                    $model->Presuntiva5 = $auxPre1[4];
                    $model->Presuntiva6 = $auxPre1[5];
                    $model->Presuntiva7 = $auxPre1[6];
                    $model->Presuntiva8 = $auxPre1[7];
                    $model->Presuntiva9 = $auxPre1[8];
                    
        
                    $model->Presuntiva10 = $auxPre2[0];
                    $model->Presuntiva11 = $auxPre2[1];
                    $model->Presuntiva12 = $auxPre2[2];
                    $model->Presuntiva13 = $auxPre2[3];
                    $model->Presuntiva14 = $auxPre2[4];
                    $model->Presuntiva15 = $auxPre2[5];
                    $model->Presuntiva16 = $auxPre2[6];
                    $model->Presuntiva17 = $auxPre2[7];
                    $model->Presuntiva18 = $auxPre2[8];
                    $model->Resultado = $resultado;
                    $model->save();
                    $msg = "Resultado calculado";
        
                }else{
                    $msg = "Este valor no se encuentra en tabla";
                }
                break;
            case 253:
                if ($convinacion->count()){
                    $convinacion = Nmp1Micro::where('Nmp', $res->NMP)->first();
                    $positivos = $convinacion->Col1 + $convinacion->Col2 + $convinacion->Col3;
                    if ($res->D1 != 10 && $res->D2 != 1 && $res->D3 != 0.1) {
                        //Formula escrita 1
                        if ($res->idParametro == 35) {
                            $op1 = 10 / $res->D1;
                            $resultado = $op1 * $convinacion->Nmp;
                        } else {
                            $resultado =  round($convinacion->Nmp / $res->D3);
                        }

                        $tipo = 2; // Formula 1
                    } else {
                        //Formula comparación por tabla  
                        $resultado = $convinacion->Nmp;
                        $tipo = 1; // Formula Tabla
                    }
                   
                    for ($i = 0; $i < 3; $i++) { 
                        if ($i + 1 <= $convinacion->Col1) {
                            $auxCon[$i] = 1;
                        }else{
                            $auxCon[$i] = 0;
                        }
                        if ($i + 1 <= $convinacion->Col2) {
                            $auxCon[$i + 3] = 1;
                        }else{
                            $auxCon[$i + 3] = 0;
                        }
                        if ($i + 1 <= $convinacion->Col3) {
                            $auxCon[$i + 6] = 1;
                        }else{
                            $auxCon[$i + 6] = 0;
                        }
                    }
                    for ($i=0; $i < sizeof($auxCon) ; $i++) { 
                        if ($auxCon[$i] == 1) {
                            $auxCon2[$i] = 1; 
                        }else{
                            $bit_aleatorio = rand() & 1;
                            if($bit_aleatorio == 1){
                                $auxCon2[$i] = 1; 
                            }else{
                                $auxCon2[$i] = 0; 
                            }
                        }
                    }
                    for ($i=0; $i < sizeof($auxCon) ; $i++) { 
                        if ($auxCon2[$i] == 1) {
                            $auxPre2[$i] = 1; 
                        }else{
                            $bit_aleatorio = rand() & 1;
                            if($bit_aleatorio == 1){
                                $auxPre2[$i] = 1; 
                            }else{
                                $auxPre2[$i] = 0; 
                            }
                        }
                    }
                    for ($i=0; $i < sizeof($auxCon) ; $i++) { 
                        if ($auxPre2[$i] == 0) {
                            $auxPre1[$i] = 0; 
                        }else{
                            $bit_aleatorio = rand() & 1;
                            if($bit_aleatorio == 1){
                                $auxPre1[$i] = 1; 
                            }else{
                                $auxPre1[$i] = 0; 
                            }
                        }
                    }
                    
                    $metodoCorto = 1;
                    $model = LoteDetalleEnterococos::find($res->idDetalle);
                    $model->Tipo = 1;
                    $model->Dilucion1 = $res->D1;
                    $model->Dilucion2 = $res->D2;
                    $model->Dilucion3 = $res->D3;
                    $model->Indice = $res->NMP;
                    $model->Tubos_negativos = 9 - $positivos;
                    $model->Tubos_positivos = $positivos;
        
                    $model->Presuntiva11 = $auxPre1[0];
                    $model->Presuntiva12 = $auxPre1[1];
                    $model->Presuntiva13 = $auxPre1[2];
                    $model->Presuntiva14 = $auxPre1[3];
                    $model->Presuntiva15 = $auxPre1[4];
                    $model->Presuntiva16 = $auxPre1[5];
                    $model->Presuntiva17 = $auxPre1[6];
                    $model->Presuntiva18 = $auxPre1[7];
                    $model->Presuntiva19 = $auxPre1[8];

                    $model->Presuntiva21 = $auxPre2[0];
                    $model->Presuntiva22 = $auxPre2[1];
                    $model->Presuntiva23 = $auxPre2[2];
                    $model->Presuntiva24 = $auxPre2[3];
                    $model->Presuntiva25 = $auxPre2[4];
                    $model->Presuntiva26 = $auxPre2[5];
                    $model->Presuntiva27 = $auxPre2[6];
                    $model->Presuntiva28 = $auxPre2[7];
                    $model->Presuntiva29 = $auxPre2[8];
        
                    $model->Confirmativa11 = $auxCon2[0];
                    $model->Confirmativa12 = $auxCon2[1];
                    $model->Confirmativa13 = $auxCon2[2];
                    $model->Confirmativa14 = $auxCon2[3];
                    $model->Confirmativa15 = $auxCon2[4];
                    $model->Confirmativa16 = $auxCon2[5];
                    $model->Confirmativa17 = $auxCon2[6];
                    $model->Confirmativa18 = $auxCon2[7];
                    $model->Confirmativa19 = $auxCon2[8];
                    
                    $model->Confirmativa21 = $auxCon[0];
                    $model->Confirmativa22 = $auxCon[1];
                    $model->Confirmativa23 = $auxCon[2];
                    $model->Confirmativa24 = $auxCon[3];
                    $model->Confirmativa25 = $auxCon[4];
                    $model->Confirmativa26 = $auxCon[5];
                    $model->Confirmativa27 = $auxCon[6];
                    $model->Confirmativa28 = $auxCon[7];
                    $model->Confirmativa29 = $auxCon[8];
                    $model->Resultado = $resultado;
                    $model->save(); 
                    $msg = "Resultado calculado nmp ";
                   
        
                }else{
                    $msg = "Este valor no se encuentra en tabla";
                }
                break;
            default:
                $msg = "No existe metodo para este parametro";
                break;
        }
     

        $data = array(
            'msg' => $msg,
        );
        return response()->json($data);
    }
    public function updatePruebaConfirmativaCol()
    {
        $model = LoteDetalleColiformes::where('Id_parametro',137)->get();   
        foreach ($model as $item) {
            if ($item->Id_control != 1) {
                $aux = LoteDetalleColiformes::where('Id_detalle',$item->Id_detalle)->first();
                $temp = LoteDetalleColiformes::where('Id_detalle',$item->Id_detalle)->first();
                $temp->Confirmativa10 = $aux->Confirmativa1;
                $temp->Confirmativa11 = $aux->Confirmativa2;
                $temp->Confirmativa12 = $aux->Confirmativa3;
                $temp->save();
            }else{
                $aux = LoteDetalleColiformes::where('Id_detalle',$item->Id_detalle)->first();
                $temp = LoteDetalleColiformes::where('Id_detalle',$item->Id_detalle)->first();
                $temp->Confirmativa10 = $aux->Confirmativa1;
                $temp->Confirmativa11 = $aux->Confirmativa2;
                $temp->Confirmativa12 = $aux->Confirmativa3;
                $temp->Confirmativa13 = $aux->Confirmativa4;
                $temp->Confirmativa14 = $aux->Confirmativa5;
                $temp->Confirmativa15 = $aux->Confirmativa6;
                $temp->Confirmativa16 = $aux->Confirmativa7;
                $temp->Confirmativa17 = $aux->Confirmativa8;
                $temp->Confirmativa18 = $aux->Confirmativa9;
                $temp->save();
            }
        }
    }
    public function updateDetalleDbo(){
        $model = LoteAnalisis::where('Id_tecnica',5)->get();
        foreach ($model as $item) {
            $aux = DqoDetalle::where('Id_lote',$item->Id_lote)->get();
            if ($aux->count()) {
                $temp = DqoDetalle::where('Id_lote',$item->Id_lote)->first();
                $temp->N = "RE-12-001-01";
                $temp->Estandares_bit = "RE-12-001-1A-13";
                $temp->save();    
            }else{
                $model = DqoDetalle::create([
                    'Id_lote' => $item->Id_lote,
                    'N' => "RE-12-001-01",
                    'Estandares_bit' => "RE-12-001-1A-13",
                ]);
            }
        }
    }
    public function updateCrisolDuplicado($id)
    {
        $lote = LoteAnalisis::where('Id_tecnica',$id)->get();
        $aux = 0;
        $idCrisol = 0;
        foreach ($lote as $item) {
            $model = DB::table('viewlotedetallesolidos')->where('Id_lote',$item->Id_lote)->get();
            foreach ($model as $item2) {
                $aux = 0;
                $temp = LoteDetalleSolidos::where('Id_crisol',$item2->Id_crisol)->where('Id_lote',$item2->Id_lote)->get();
                if ($temp->count() > 1) {
                    
                    foreach ($temp as $value) {
                        $idCrisol = 0;
                        if ($aux != 0) {
                           if ($value->Crisol != 0) {
                                $crisol = CrisolesGA::all();

                                regresar:
                                $mat = rand(0, $crisol->count());
                                $valCrisol = LoteDetalleSolidos::where('Id_crisol',$crisol[$mat]->Id_crisol)->where('Id_lote',$item2->Id_lote)->get();
                                if ($valCrisol->count()) {
                                    goto regresar;
                                }
                                $idCrisol = $crisol[$mat]->Id_crisol;

                                $loteDetalle = LoteDetalleSolidos::where('Id_detalle',$value->Id_detalle)->first();
                                // echo "id detalle: ".$loteDetalle->Id_detalle.'<br>';

                                $mf = ((($loteDetalle->Resultado / $loteDetalle->Factor_conversion) * $loteDetalle->Vol_muestra) + round($crisol[$mat]->Peso,4));
                                $auxMf =  (((round($mf,4) - round($crisol[$mat]->Peso,4)) / $loteDetalle->Vol_muestra) * $loteDetalle->Factor_conversion);
                                $resultado = $auxMf;

                                $numeroAleatorio = rand(0, 3); // Genera un número entre 1 y 5
                                $valRandomPm1 = number_format($numeroAleatorio / 10000,4); // Divide por 1000 para obtener el rango deseado
                                $numeroAleatorio = rand(0, 3); // Genera un número entre 1 y 5
                                $valRandomPm2 = number_format($numeroAleatorio / 10000,4); // Divide por 1000 para obtener el rango deseado
                                $numeroAleatorio = rand(0, 3); // Genera un número entre 1 y 5
                                $valRandomPc1 = number_format($numeroAleatorio / 10000,4); // Divide por 1000 para obtener el rango deseado
                                $numeroAleatorio = rand(0, 3); // Genera un número entre 1 y 5
                                $valRandomPc2 = number_format($numeroAleatorio / 10000,4); // Divide por 1000 para obtener el rango deseado

                                $model = LoteDetalleSolidos::find($value->Id_detalle);
                                $model->Id_crisol = $crisol[$mat]->Id_crisol;
                                $model->Crisol = $crisol[$mat]->Num_serie;
                                $model->Masa1 = round($crisol[$mat]->Peso,4);
                                $model->Masa2 = round($mf,4);
                                $model->Peso_muestra1 = round(($crisol[$mat]->Peso + $valRandomPm1),4);
                                $model->Peso_muestra2 = round(($crisol[$mat]->Peso + $valRandomPm2),4);
                                $model->Peso_constante1 = round(($mf + $valRandomPc1),4);
                                $model->Peso_constante2 = round(($mf + $valRandomPc2),4);
                                // $model->Resultado = $resultado;
                                // $model->Analizo = Auth::user()->id;
                                $model->save();
                           }
                        }else{
                            $aux++;
                        }
                    }
                }
            }
        }
    }
    public function updateCrisolDuplicado2()
    {
        $model = DB::table('viewlotedetallesolidos')->where('Id_parametro',46)->get();

        foreach ($model as $item) {
            $temp = LoteDetalleSolidos::where('Id_analisis',$item->Id_analisis)->where('Id_control',$item->Id_control)->where('Id_parametro',4)->get();
            if($temp->count()){
                $temp = LoteDetalleSolidos::where('Id_analisis',$item->Id_analisis)->where('Id_control',$item->Id_control)->where('Id_parametro',4)->first();
                $crisol = CrisolesGA::where('Num_serie',$temp->Crisol)->first();
                $mf = ((($item->Resultado / $item->Factor_conversion) * $item->Vol_muestra) + round($crisol->Peso,4));
                $auxMf =  (((round($mf,4) - round($crisol->Peso,4)) / $item->Vol_muestra) * $item->Factor_conversion);
                $resultado = $auxMf;

                $masa1 = ((($item->Resultado / 1000000) * $item->Vol_muestra) - $temp->Masa2) * (-1);

                $numeroAleatorio = rand(0, 3); // Genera un número entre 1 y 5
                $valRandomPm1 = number_format($numeroAleatorio / 10000,3); // Divide por 1000 para obtener el rango deseado
                $numeroAleatorio = rand(0, 3); // Genera un número entre 1 y 5
                $valRandomPm2 = number_format($numeroAleatorio / 10000,3); // Divide por 1000 para obtener el rango deseado
                $numeroAleatorio = rand(0, 3); // Genera un número entre 1 y 5
                $valRandomPc1 = number_format($numeroAleatorio / 10000,2); // Divide por 1000 para obtener el rango deseado
                $numeroAleatorio = rand(0, 3); // Genera un número entre 1 y 5
                $valRandomPc2 = number_format($numeroAleatorio / 10000,2); // Divide por 1000 para obtener el rango deseado

                $aux = LoteDetalleSolidos::find($item->Id_detalle);
                $aux->Id_crisol = $crisol->Id_crisol;
                $aux->Crisol = $crisol->Num_serie;
                $aux->Masa1 = round($masa1,4);
                $aux->Masa2 = round($temp->Masa2,4);
                $aux->Peso_muestra1 = round(($masa1 + $valRandomPm1),4);
                $aux->Peso_muestra2 = round(($masa1 + $valRandomPm2),4);
                $aux->Peso_constante1 = round($temp->Peso_constante1 + $valRandomPc1,4);
                $aux->Peso_constante2 = round($temp->Peso_constante2 + $valRandomPc2,4);
                $aux->save();
            }
        }

      
    }
    public function regresarMuestrasDbo($id)
    {
        $model = LoteDetalleDbo::where('Id_lote',$id)->where('Liberado',0)->where('Id_control',1)->get();
        foreach ($model as $item) {
            echo "<br> ".$item->Id_codigo;
            $aux = CodigoParametros::where('Id_codigo',$item->Id_codigo)->get();
            if ($aux->count()) {
                $temp = CodigoParametros::where('Id_codigo',$item->Id_codigo)->first();
                $temp->Asignado = 0;
                $temp->save();
    
                DB::table('lote_detalle_dbo')->where('Id_detalle', $item->Id_detalle)->delete();
            }
         
        }
        $temp = LoteDetalleDbo::where('Id_lote',$id)->get();
        $aux = LoteAnalisis::find($id);
        $aux->Asignado = $temp->count();
        $aux->save();
    }
    public function pruebaValores()
    {
     
    }
    public function getDetalleElegido(Request $res){
        $data = array();
        $modelCloruros = CodigoParametros::where('Codigo', $res->folio)
        ->where(function ($query){
            $query->where('Id_parametro', '=', 64);
            $query->orWhere('Id_parametro', '=', 368);
        })
        ->select('Resultado2')
        ->get();
        if(!empty($modelCloruros)){
            $data["cloruros"] = $modelCloruros;
        }
        else{
            $data["cloruros"] = "NULL";
        }

        $modelConductividad = CodigoParametros::where('Codigo', $res->folio)
        ->where('Id_parametro', '=', 67)
        ->select('Resultado2')
        ->get();
        if(!empty($modelConductividad)){
            $data["conductividad"] = $modelConductividad;
        }
        else{
            $data["conductividad"] = "NULL";
        }

        $modelPh = CodigoParametros::where('Codigo', $res->folio)
        ->where(function ($query){
            $query->where('Id_parametro', '=', 110);
            $query->orWhere('Id_parametro', '=', 14);
        })
        ->select('Resultado2')
        ->get();
        if(!empty($modelPh)){
            $data["ph"] = $modelPh;
        }
        else{
            $data["ph"] = "NULL";
        }

        return response()->json($data);
    }
}
