<?php

namespace App\Http\Controllers\Laboratorio;

use App\Http\Controllers\Controller;
use App\Models\BitacoraVolumetria;
use App\Models\VolumenParametros;
use App\Models\LoteAnalisis;
use App\Models\LoteDetalle;
use App\Models\ObservacionMuestra;
use App\Models\Parametro;
use App\Models\MatrazGA;
use App\Models\ReportesFq;
use App\Models\SolicitudParametro;
use App\Models\TipoFormula;
use App\Models\CurvaConstantes;
use App\Models\estandares;
use App\Models\TecnicaLoteMetales;
use App\Models\BlancoCurvaMetales;
use App\Models\CalentamientoMatraz;
use App\Models\CodigoParametros;
use App\Models\ControlCalidad;
use App\Models\CrisolesGA;
use App\Models\CurvaCalibracionMet;
use App\Models\DqoDetalle;
use App\Models\VerificacionMetales;
use App\Models\EstandarVerificacionMet;
use App\Models\GeneradorHidrurosMet;
use App\Models\PruebaConfirmativaFq;
use App\Models\PruebaPresuntivaFq;
use App\Models\SembradoFq;
use App\Models\DqoFq;
use App\Models\EnfriadoMatraces;
use App\Models\EnfriadoMatraz;
use App\Models\LoteDetalleCloro;
use App\Models\LoteDetalleDqo;
use App\Models\LoteDetalleEspectro;
use App\Models\LoteDetalleGA;
use App\Models\LoteDetalleNitrogeno;
use App\Models\LoteDetalleSolidos;
use App\Models\LoteTecnica;
use App\Models\PlantillaVolumetria;
use App\Models\Reportes;
use App\Models\SecadoCartucho;
use App\Models\Tecnica;
use App\Models\TiempoReflujo;
use App\Models\User;
use App\Models\ValoracionCloro;
use App\Models\ValoracionDqo;
use App\Models\ValoracionDureza;
use App\Models\ValoracionNitrogeno;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VolController extends Controller
{


    // todo ******************* Inicio de lote ************************
    public function loteVol()
    {
        //* Tipo de formulas  
        $parametro = DB::table('ViewParametroUsuarios')->where('Id_user', Auth::user()->id)->get();
        $textoRecuperadoPredeterminado = ReportesFq::where('Id_reporte', 0)->first();
        return view('laboratorio.fq.loteVol', compact('parametro', 'textoRecuperadoPredeterminado'));
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

    public function createLote(Request $request)
    {
        $model = LoteAnalisis::create([
            'Id_area' => 14,
            'Id_tecnica' => $request->tipo,
            'Asignado' => 0,
            'Liberado' => 0,
            'Fecha' => $request->fecha,
        ]);
        $data = array(
            'model' => $model,
        );

        return response()->json($data);
    }
    public function buscarLote(Request $request)
    {
        $sw = false;
        $model = DB::table('ViewLoteAnalisis')->where('Id_tecnica', $request->tipo)->where('Id_area', 14)->where('Fecha', $request->fecha)->get();
        if ($model->count()) {
            $sw = true;
        }

        $data = array(
            'model' => $model,
            'sw' => $sw,

        );
        return response()->json($data);
    }
    public function guardarValidacionVol(Request $request)
    {
        switch ($request->caso) {
            case 1: // Cloro
                # code...
                $valoracionModel = ValoracionCloro::where('Id_lote', $request->idLote)->get();
                if ($valoracionModel->count()) {
                    $model = ValoracionCloro::where('Id_lote', $request->idLote)->first();
                    $model->Id_lote = $request->idLote;
                    $model->Id_parametro = $request->idParametro;
                    $model->Blanco = $request->blanco;
                    $model->Ml_titulado1 = $request->titulado1;
                    $model->Ml_titulado2 = $request->titulado2;
                    $model->Ml_titulado3 = $request->titulado3;
                    $model->Ml_trazable = $request->trazable;
                    $model->Normalidad = $request->normalidad;
                    $model->Resultado = $request->resultado;
                    $model->save();
                } else {
                    $model = ValoracionCloro::create([
                        'Id_lote' => $request->idLote,
                        'Id_parametro' => $request->idParametro,
                        'Blanco' => $request->blanco,
                        'Ml_titulado1' => $request->titulado1,
                        'Ml_titulado2' => $request->titulado2,
                        'Ml_titulado3' => $request->titulado3,
                        'Ml_trazable' => $request->trazable,
                        'Normalidad' => $request->normalidad,
                        'Resultado' => $request->resultado,
                    ]);
                }
                break;
            case 2: // DQO
                # code...
                $valoracionModel = ValoracionDqo::where('Id_lote', $request->idLote)->get();
                if ($valoracionModel->count()) {
                    $model = ValoracionDqo::where('Id_lote', $request->idLote)->first();
                    $model->Id_lote = $request->idLote;
                    $model->Id_parametro = $request->idParametro;
                    $model->Blanco = $request->blanco;
                    $model->Vol_k2 = $request->volk2D;
                    $model->Concentracion = $request->concentracion;
                    $model->Factor = $request->factor;
                    $model->Vol_titulado1 = $request->titulado1;
                    $model->Vol_titulado2 = $request->titulado2;
                    $model->Vol_titulado3 = $request->titulado3;
                    $model->Resultado = $request->resultado;
                    $model->save();
                } else {
                    $model = ValoracionDqo::create([
                        'Id_lote' => $request->idLote,
                        'Id_parametro' => $request->idParametro,
                        'Blanco' => $request->blanco,
                        'Vol_k2' => $request->volk2D,
                        'Concentracion' => $request->concentracion,
                        'Factor' => $request->factor,
                        'Vol_titulado1' => $request->titulado1,
                        'Vol_titulado2' => $request->titulado2,
                        'Vol_titulado3' => $request->titulado3,
                        'Resultado' => $request->resultado,
                    ]);
                }
                break;
            case 3:
                // Nitrogeno
                # code...
                $valoracionModel = ValoracionNitrogeno::where('Id_lote', $request->idLote)->get();
                if ($valoracionModel->count()) {
                    $model = ValoracionNitrogeno::where('Id_lote', $request->idLote)->first();
                    $model->Id_lote = $request->idLote;
                    $model->Id_parametro = $request->idParametro;
                    $model->Blanco = $request->blanco;
                    $model->Gramos = $request->gramos;
                    $model->Factor_conversion = $request->factor;
                    $model->Titulo1 = $request->titulado1;
                    $model->Titulo2 = $request->titulado2;
                    $model->Titulo3 = $request->titulado3;
                    $model->Pm = $request->pm;
                    $model->Resultado = $request->resultado;
                    $model->save();
                } else {
                    $model = ValoracionNitrogeno::create([
                        'Id_lote' => $request->idLote,
                        'Id_parametro' => $request->idParametro,
                        'Blanco' => $request->blanco,
                        'Gramos' => $request->gramos,
                        'Factor_conversion' => $request->factor,
                        'Titulo1' => $request->titulado1,
                        'Titulo2' => $request->titulado2,
                        'Titulo3' => $request->titulado3,
                        'Pm' => $request->pm,
                        'Resultado' => $request->resultado,
                    ]);
                }
                break;
            case 4:
                // Nitrogeno
                # code...
                $valoracionModel = ValoracionDureza::where('Id_lote', $request->idLote)->get();
                if ($valoracionModel->count()) {
                    $model = ValoracionDureza::where('Id_lote', $request->idLote)->first();
                    $model->Id_lote = $request->idLote;
                    $model->Id_parametro = $request->idParametro;
                    $model->Id_parametro = $request->idParametro;
                    $model->Blanco = $request->blanco;
                    $model->Disolucion1 = $request->titulado1;
                    $model->Disolucion2 = $request->titulado2;
                    $model->Disolucion3 = $request->titulado3;
                    $model->Solucion = $request->solucion;
                    $model->Resultado = $request->resultado;
                    $model->save();
                } else {
                    $model = ValoracionDureza::create([
                        'Id_lote' => $request->idLote,
                        'Id_parametro' => $request->idParametro,
                        'Blanco' => $request->blanco,
                        'Disolucion1' => $request->titulado1,
                        'Disolucion2' => $request->titulado2,
                        'Disolucion3' => $request->titulado3,
                        'Solucion' => $request->solucion,
                        'Resultado' => $request->resultado,
                    ]);
                }
                break;


            default:
                # code...
                break;
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

        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }

    //RECUPERAR DATOS PARA ENVIARLOS A LA VENTANA MODAL > EQUIPO PARA RELLENAR LOS DATOS ALMACENADOS EN LA BD
    public function getDataloteVol(Request $request)
    {
        $idLoteIf = $request->idLote;

        //RECUPERA LA PLANTILLA DEL REPORTE    
        // $reporte = ReportesFq::where('Id_lote', $request->idLote)->first();

        //RECUPERA EL APARTADO DE FÓRMULAS GLOBALES;
        $constantesModel = CurvaConstantes::where('Id_lote', $request->idLote)->get();
        if ($constantesModel->count()) {
            $constantes = CurvaConstantes::where('Id_lote', $request->idLote)->first();
        } else {
            $constantes = null;
        }
        /* Módulo DQO */
        $dqoModel = DB::table('dqo_fq')->where('Id_lote', $request->idLote)->get();

        if ($dqoModel->count()) {
            $dqo = DqoFq::where('Id_lote', $request->idLote)->first();
        } else {
            $dqo = null;
        }
        $detalleDqo = LoteDetalleDqo::where('Id_lote', $request->idLote)->first();

        //? Obtiene los valores para llenar la valoracion
        $paraModel = LoteAnalisis::find($request->idLote);
        switch ($paraModel->Id_tecnica) {
            case 6: //todo DQO
                $valoracion = ValoracionDqo::where('Id_lote', $request->idLote)->first();
                break;
            case 295: //todo CLORO RESIDUAL LIBRE
            case 64:
                $valoracion = ValoracionCloro::where('Id_lote', $request->idLote)->first();
                break;
            case 10:
            case 9:
            case 11: //todo NITROGENO TOTAL
                $valoracion = ValoracionNitrogeno::where('Id_lote', $request->idLote)->first();
                break;
            default:
                $valoracion = ValoracionNitrogeno::where('Id_lote', $request->idLote)->first();
                break;
        }
        $plantilla = BitacoraVolumetria::where('Id_lote', $request->idLote)->get();
        if ($plantilla->count()) {
        } else {
            $plantilla = PlantillaVolumetria::where('Id_parametro', $paraModel->Id_tecnica)->get();
        }


        //-------------------------------------
        $data = array(
            'plantilla' => $plantilla,
            'valoracion' => $valoracion,
            'curvaConstantes' => $constantes,
            'idLoteIf' => $idLoteIf,
            'dataDqo' => $dqo,
            'detalleDqo' => $detalleDqo,
        );

        return response()->json($data);
    }
    #OBSERVACIONES
    public function updateObsVolumetria(Request $request)
    {
        switch ($request->caso) {
            case 1: // Cloro
                # code...
                $model = LoteDetalleCloro::find($request->idDetalle);
                $model->Observacion = $request->observacion;
                $model->save();
                break;
            case 2: // Dqo
                # code...
                $model = LoteDetalleDqo::find($request->idDetalle);
                $model->Observacion = $request->observacion;
                $model->save();
                break;
            case 3:
                # code...
                $model = LoteDetalleNitrogeno::find($request->idDetalle);
                $model->Observacion = $request->observacion;
                $model->save();
                break;
            default:
                # code...
                break;
        }


        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }

    public function enviarObsGeneralVol(Request $request)
    {
        switch ($request->idParametro) {
            case 6: //dqo
                $model = LoteDetalleDqo::where('Id_lote', $request->idLote)->get();
                foreach ($model as $item) {
                    $update = LoteDetalleDqo::find($item->Id_detalle);
                    $update->Observacion = $request->observacion;
                    $update->save();
                }
                break;
        }


        $data = array(
            'model' => $update
        );
        return response()->json($data);
    }


    public function getPlantillaPred(Request $request)
    {
        $bandera = '';

        //Obtiene el parámetro que se está consultando
        $parametro = DB::table('ViewLoteDetalleDqo')->where('Id_lote', $request->idLote)->first();

        if (is_null($parametro)) {
            $parametro = DB::table('ViewLoteDetalleCloro')->where('Id_lote', $request->idLote)->first();

            if (!is_null($parametro)) {
                $bandera = 'cloro';
            } else {
                $parametro = DB::table('ViewLoteDetalleNitrogeno')->where('Id_lote', $request->idLote)->first();

                if (!is_null($parametro)) {
                    $bandera = 'nitrogeno';
                }
            }
        } else {
            $bandera = 'dqo';
        }

        if ($bandera == 'dqo') {
            if ($parametro->Id_parametro == 6 || $parametro->Id_parametro == 77) { // DQO
                $plantillaPredeterminada = ReportesFq::where('Id_reporte', 29)->first();
            } else if ($parametro->Id_parametro == 72 || $parametro->Id_parametro == 75) { // DQO ALTA
                $plantillaPredeterminada = ReportesFq::where('Id_reporte', 25)->first();
            } else if ($parametro->Id_parametro == 73 || $parametro->Id_parametro == 76) { // DQO BAJA
                $plantillaPredeterminada = ReportesFq::where('Id_reporte', 26)->first();
            } else if ($parametro->Id_parametro == 170) { // DQO SOLUBLE
                $plantillaPredeterminada = ReportesFq::where('Id_reporte', 29)->first();
            } else if ($parametro->Id_parametro == 168) { //DQO SOLUBLE ALTA
                $plantillaPredeterminada = ReportesFq::where('Id_reporte', 25)->first();
            } else if ($parametro->Id_parametro == 169) { // DQO SOLUBLE BAJA
                $plantillaPredeterminada = ReportesFq::where('Id_reporte', 26)->first();
            }
        } else if ($bandera == 'cloro') {
            if ($parametro->Id_parametro == 34 || $parametro->Id_parametro == 128 || $parametro->Id_parametro == 295) { // CLORO RESIDUAL LIBRE
                $plantillaPredeterminada = ReportesFq::where('Id_reporte', 24)->first();
            }
        } else if ($bandera == 'nitrogeno') {
            if ($parametro->Id_parametro == 11) { // NITROGENO TOTAL
                $plantillaPredeterminada = ReportesFq::where('Id_reporte', 30)->first();
            } else if ($parametro->Id_parametro == 9 || $parametro->Id_parametro == 117 || $parametro->Id_parametro == 296) { // NITROGENO AMONIACAL
                $plantillaPredeterminada = ReportesFq::where('Id_reporte', 27)->first();
            } else if ($parametro->Id_parametro == 10) { // NITROGENO ORGANICO
                $plantillaPredeterminada = ReportesFq::where('Id_reporte', 28)->first();
            }
        }

        if (!is_null($plantillaPredeterminada)) {
            return response()->json($plantillaPredeterminada);
        }
    }

    public function asignar()
    {
        $tipoFormula = TipoFormula::all();
        return view('laboratorio.metales.asignar', compact('tipoFormula'));
    }
    public function asgnarMuestraLoteVol($id)
    {
        $lote = LoteDetalle::where('Id_lote', $id)->get();
        $idLote = $id;
        return view('laboratorio.fq.asignarMuestraLoteVol', compact('lote', 'idLote'));
    }
    //* Muestra los parametros sin asignar a lote
    public function muestraSinAsignarVol(Request $request)
    {
        $lote = LoteAnalisis::find($request->idLote);
        $model = DB::table('ViewCodigoParametro')
            ->where('Id_parametro', $lote->Id_tecnica)
            ->where('Asignado', '!=', 1)
            ->get();
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    //* Muestra asigada a lote
    public function getMuestraAsignadaVol(Request $request)
    {
        $loteModel = LoteAnalisis::where('Id_lote', $request->idLote)->first();

        $model = array();
        switch ($loteModel->Id_tecnica) {
            case 6:
                $model = DB::table('ViewLoteDetalleDqo')->where('Id_lote', $request->idLote)->get();
                break;
            case 33:
            case 218:
            case 64:
                $model = DB::table('ViewLoteDetalleCloro')->where('Id_lote', $request->idLote)->get();
                break;
            case 9:
            case 10:
            case 11:
            case 287:
            case 83:
            case 108:
                $model = DB::table('ViewLoteDetalleNitrogeno')->where('Id_lote', $request->idLote)->get();
                break;
            default:
                # code...
                break;
        }

        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    //! Eliminar parametro muestra
    public function delMuestraLoteVol(Request $request)
    {
        $loteModel = LoteAnalisis::where('Id_lote', $request->idLote)->first();
        switch ($loteModel->Id_parametro) {
            case 6: //DQO
                $detModel = DB::table('lote_detalle_dqo')->where('Id_detalle', $request->idDetalle)->delete();
                $detModel = LoteDetalleEspectro::where('Id_lote', $request->idLote)->get();
                break;
            default:
                # code...
                break;
        }

        $loteModel = LoteAnalisis::find($request->idLote);
        $loteModel->Asignado = $detModel->count();
        $loteModel->Liberado = 0;
        $loteModel->save();


        $solModel = SolicitudParametro::where('Id_solicitud', $request->idSol)->where('Id_subnorma', $request->idParametro)->first();
        $solModel->Asignado = 0;
        $solModel->save();
        $solModel = SolicitudParametro::find($request->idSol);

        $data = array(
            'idDetalle' => $request->idDetalle,
        );

        return response()->json($data);
    }
    //* Asignar parametro a lote
    public function asignarMuestraLoteVol(Request $request)
    {

        $sw = false;
        $loteModel = LoteAnalisis::where('Id_lote', $request->idLote)->first();
        $paraModel = Parametro::find($loteModel->Id_tecnica);
        //!----------
        switch ($loteModel->Id_tecnica) {
            case 6:
                $model = LoteDetalleDqo::create([
                    'Id_lote' => $request->idLote,
                    'Id_analisis' => $request->idAnalisis,
                    'Id_codigo' => $request->idSol,
                    'Id_parametro' => $loteModel->Id_tecnica,
                    'Id_control' => 1,
                    'Analizo' => 1,
                    'Liberado' => 0,
                ]);
                $detModel = LoteDetalleDqo::where('Id_lote', $request->idLote)->get();
                $sw = true;
                break;
            case 33:
            case 218:
            case 64:
                $model = LoteDetalleCloro::create([
                    'Id_lote' => $request->idLote,
                    'Id_analisis' => $request->idAnalisis,
                    'Id_codigo' => $request->idSol,
                    'Id_parametro' => $loteModel->Id_tecnica,
                    'Id_control' => 1,
                    'Analizo' => 1,
                    'Liberado' => 0,
                ]);
                $detModel = LoteDetalleCloro::where('Id_lote', $request->idLote)->get();
                $sw = true;
                break;
            case "9":
            case 10:
            case 11:
            case 287:
            case 83:
            case 108:
                $model = LoteDetalleNitrogeno::create([
                    'Id_lote' => $request->idLote,
                    'Id_analisis' => $request->idAnalisis,
                    'Id_codigo' => $request->idSol,
                    'Id_parametro' => $loteModel->Id_tecnica,
                    'Id_control' => 1,
                    'Analizo' => 1,
                    'Liberado' => 0,
                ]);
                $detModel = LoteDetalleNitrogeno::where('Id_lote', $request->idLote)->get();
                $sw = true;
                break;
            default:
                # code...
                break;
        }


        $solModel = CodigoParametros::find($request->idSol);
        $solModel->Asignado = 1;
        $solModel->save();

        $loteModel = LoteAnalisis::find($request->idLote);
        $loteModel->Asignado = $detModel->count();
        $loteModel->Liberado = 0;
        $loteModel->save();

        $data = array(
            'msg' => "Mensaje",
            'sw' => $sw,
            'model' => $model,
        );
        return response()->json($data);
    }

    //Función LOTE > CREAR O MODIFICAR TEXTO DEL LOTE > PROCEDIMIENTO/VALIDACIÓN
    public function guardarTexto(Request $request)
    {
        $textoPeticion = $request->texto;
        $idLote = $request->lote;

        //$lote = Reportes::where('Id_lote', $idLote)->first();

        $lote = DB::table('reportes_fq')->where('Id_lote', $idLote)->where('Id_area', $request->idArea)->get();

        if ($lote->count()) {
            $texto = ReportesFq::where('Id_lote', $idLote)->where('Id_area', $request->idArea)->first();
            $texto->Texto = $textoPeticion;
            $texto->Id_user_m = Auth::user()->id;

            $texto->save();
        } else {
            $texto = ReportesFq::create([
                'Id_lote' => $idLote,
                'Texto' => $textoPeticion,
                'Id_area' => $request->idArea,
                'Id_user_c' => Auth::user()->id,
                'Id_user_m' => Auth::user()->id
            ]);
        }

        return response()->json(
            compact('texto')
        );
    }

    //*************************************GUARDA LOS DATOS DE LA VENTANA MODAL EN MÓDULO LOTE, PESTAÑA EQUIPO************* */
    public function guardarDatos(Request $request)
    {

        //****************************************************GRASAS*****************************************************************        
        //calentamiento_matraces
        $calentamientoFqModel = CalentamientoMatraz::where('Id_lote', $request->idLote)->get();

        if ($calentamientoFqModel->count()) {
            for ($i = 0; $i < 3; $i++) {
                $calentamientoMatraz = CalentamientoMatraz::find($calentamientoFqModel[$i]->Id_calentamiento);

                $calentamientoMatraz->Id_lote = $request->grasas_calentamiento[$i][0];
                $calentamientoMatraz->Masa_constante = $request->grasas_calentamiento[$i][1];
                $calentamientoMatraz->Temperatura = $request->grasas_calentamiento[$i][2];
                $calentamientoMatraz->Entrada = $request->grasas_calentamiento[$i][3];
                $calentamientoMatraz->Salida = $request->grasas_calentamiento[$i][4];
                $calentamientoMatraz->Id_user_m = Auth::user()->id;

                $calentamientoMatraz->save();
            }
        } else {
            for ($i = 0; $i < 3; $i++) {
                CalentamientoMatraz::create([
                    'Id_lote' => $request->grasas_calentamiento[$i][0],
                    'Masa_constante' => $request->grasas_calentamiento[$i][1],
                    'Temperatura' => $request->grasas_calentamiento[$i][2],
                    'Entrada' => $request->grasas_calentamiento[$i][3],
                    'Salida' => $request->grasas_calentamiento[$i][4],
                    'Id_user_c' => Auth::user()->id,
                    'Id_user_m' => Auth::user()->id
                ]);
            }
        }

        //enfriado_matraces
        $enfriadoFqModel = EnfriadoMatraces::where('Id_lote', $request->idLote)->get();

        if ($enfriadoFqModel->count()) {
            for ($i = 0; $i < 3; $i++) {
                $enfriadoMatraz = EnfriadoMatraces::find($enfriadoFqModel[$i]->Id_enfriado);
                $enfriadoMatraz->Id_lote = $request->grasas_enfriado[$i][0];
                $enfriadoMatraz->Masa_constante = $request->grasas_enfriado[$i][1];
                $enfriadoMatraz->Entrada = $request->grasas_enfriado[$i][2];
                $enfriadoMatraz->Salida = $request->grasas_enfriado[$i][3];
                $enfriadoMatraz->Pesado_matraz = $request->grasas_enfriado[$i][4];
                $enfriadoMatraz->Id_user_m = Auth::user()->id;

                $enfriadoMatraz->save();
            }
        } else {
            for ($i = 0; $i < 3; $i++) {
                EnfriadoMatraces::create([
                    'Id_lote' => $request->grasas_enfriado[$i][0],
                    'Masa_constante' => $request->grasas_enfriado[$i][1],
                    'Entrada' => $request->grasas_enfriado[$i][2],
                    'Salida' => $request->grasas_enfriado[$i][3],
                    'Pesado_matraz' => $request->grasas_enfriado[$i][4],
                    'Id_user_c' => Auth::user()->id,
                    'Id_user_m' => Auth::user()->id
                ]);
            }
        }

        //secado_cartuchos
        $secadoFqModel = SecadoCartucho::where('Id_lote', $request->idLote)->get();

        if ($secadoFqModel->count()) {
            $secadoCartucho = SecadoCartucho::find($secadoFqModel[0]->Id_secado);
            $secadoCartucho->Id_lote = $request->grasas_secadoLote;
            $secadoCartucho->Temperatura = $request->grasas_secadoTemp;
            $secadoCartucho->Entrada = $request->grasas_secadoEntrada;
            $secadoCartucho->Salida = $request->grasas_secadoSalida;
            $secadoCartucho->Id_user_m = Auth::user()->id;

            $secadoCartucho->save();
        } else {
            SecadoCartucho::create([
                'Id_lote' => $request->grasas_secadoLote,
                'Temperatura' => $request->grasas_secadoTemp,
                'Entrada' => $request->grasas_secadoEntrada,
                'Salida' => $request->grasas_secadoSalida,
                'Id_user_c' => Auth::user()->id,
                'Id_user_m' => Auth::user()->id
            ]);
        }

        //tiempo_reflujo
        $tiempoFqModel = TiempoReflujo::where('Id_lote', $request->idLote)->get();

        if ($tiempoFqModel->count()) {
            $tiempoReflujo = TiempoReflujo::find($tiempoFqModel[0]->Id_tiempo);
            $tiempoReflujo->Id_lote = $request->grasas_tiempoLote;
            $tiempoReflujo->Entrada = $request->grasas_tiempoEntrada;
            $tiempoReflujo->Salida = $request->grasas_tiempoSalida;
            $tiempoReflujo->Id_user_m = Auth::user()->id;

            $tiempoReflujo->save();
        } else {
            TiempoReflujo::create([
                'Id_lote' => $request->grasas_tiempoLote,
                'Entrada' => $request->grasas_tiempoEntrada,
                'Salida' => $request->grasas_tiempoSalida,
                'Id_user_c' => Auth::user()->id,
                'Id_user_m' => Auth::user()->id
            ]);
        }

        //enfriado_matraz
        $enfriadoFqModel = EnfriadoMatraz::where('Id_lote', $request->idLote)->get();

        if ($enfriadoFqModel->count()) {
            $enfriadoMatraz2 = EnfriadoMatraz::find($enfriadoFqModel[0]->Id_enfriado);
            $enfriadoMatraz2->Id_lote = $request->grasas_enfriadoLote;
            $enfriadoMatraz2->Entrada = $request->grasas_enfriadoEntrada;
            $enfriadoMatraz2->Salida = $request->grasas_enfriadoSalida;
            $enfriadoMatraz2->Id_user_m = Auth::user()->id;

            $enfriadoMatraz2->save();
        } else {
            EnfriadoMatraz::create([
                'Id_lote' => $request->grasas_enfriadoLote,
                'Entrada' => $request->grasas_enfriadoEntrada,
                'Salida' => $request->grasas_enfriadoSalida,
                'Id_user_c' => Auth::user()->id,
                'Id_user_m' => Auth::user()->id
            ]);
        }


        //****************************************************COLIFORMES*************************************************************
        //******************************************************SEMBRADO FQ**********************************************************
        $sembradoFqModel = SembradoFq::where('Id_lote', $request->idLote)->get();

        if ($sembradoFqModel->count()) {
            $sembradoFq = SembradoFq::where('Id_lote', $request->idLote)->first();

            $sembradoFq->Sembrado = $request->sembrado_sembrado;
            $sembradoFq->Fecha_resiembra = $request->sembrado_fechaResiembra;
            $sembradoFq->Tubo_n = $request->sembrado_tuboN;
            $sembradoFq->Bitacora = $request->sembrado_bitacora;
            $sembradoFq->Id_user_m = Auth::user()->id;

            $sembradoFq->save();
        } else {
            SembradoFq::create([
                'Id_lote' => $request->idLote,
                'Sembrado' => $request->sembrado_sembrado,
                'Fecha_resiembra' => $request->sembrado_fechaResiembra,
                'Tubo_n' => $request->sembrado_tuboN,
                'Bitacora' => $request->sembrado_bitacora,
                'Id_user_c' => Auth::user()->id,
                'Id_user_m' => Auth::user()->id
            ]);
        }
        //*******************************************PRUEBA PRESUNTIVA FQ******************************************/
        $pruebaPresuntivaModel = PruebaPresuntivaFq::where('Id_lote', $request->idLote)->get();

        if ($pruebaPresuntivaModel->count()) {
            $pruebaPresuntivaFq = PruebaPresuntivaFq::where('Id_lote', $request->idLote)->first();

            $pruebaPresuntivaFq->Preparacion = $request->pruebaPresuntiva_preparacion;
            $pruebaPresuntivaFq->Lectura = $request->pruebaPresuntiva_lectura;
            $pruebaPresuntivaFq->Id_user_m = Auth::user()->id;

            $pruebaPresuntivaFq->save();
        } else {
            PruebaPresuntivaFq::create([
                'Id_lote' => $request->idLote,
                'Preparacion' => $request->pruebaPresuntiva_preparacion,
                'Lectura' => $request->pruebaPresuntiva_lectura,
                'Id_user_c' => Auth::user()->id,
                'Id_user_m' => Auth::user()->id,
            ]);
        }

        //***********************************************PRUEBA CONFIRMATIVA FQ***********************************
        $pruebaConfirmativaModel = PruebaConfirmativaFq::where('Id_lote', $request->idLote)->get();

        if ($pruebaConfirmativaModel->count()) {
            $pruebaConfirmativa = PruebaConfirmativaFq::where('Id_lote', $request->idLote)->first();

            $pruebaConfirmativa->Medio = $request->pruebaConfirmativa_medio;
            $pruebaConfirmativa->Preparacion = $request->pruebaConfirmativa_preparacion;
            $pruebaConfirmativa->Lectura = $request->pruebaConfirmativa_lectura;
            $pruebaConfirmativa->Id_user_m = Auth::user()->id;

            $pruebaConfirmativa->save();
        } else {
            PruebaConfirmativaFq::create([
                'Id_lote' => $request->idLote,
                'Medio' => $request->pruebaConfirmativa_medio,
                'Preparacion' => $request->pruebaConfirmativa_preparacion,
                'Lectura' => $request->pruebaConfirmativa_lectura,
                'Id_user_c' => Auth::user()->id,
                'Id_user_m' => Auth::user()->id,
            ]);
        }

        //************************************DQO********************************
        $dqoModel = DqoFq::where('Id_lote', $request->idLote)->get();

        if ($dqoModel->count()) {
            $dqoFq = DqoFq::where('Id_lote', $request->idLote)->first();

            $dqoFq->Inicio = $request->ebullicion_inicio;
            $dqoFq->Fin = $request->ebullicion_fin;
            $dqoFq->Invlab = $request->ebullicion_invlab;
            $dqoFq->Id_user_m = Auth::user()->id;

            $dqoFq->save();
        } else {
            DqoFq::create([
                'Id_lote' => $request->idLote,
                'Inicio' => $request->ebullicion_inicio,
                'Fin' => $request->ebullicion_fin,
                'Invlab' => $request->ebullicion_invlab,
                'Id_user_c' => Auth::user()->id,
                'Id_user_m' => Auth::user()->id,
            ]);
        }

        //*******************************************************************************************************
        return response()->json(
            compact('sembradoFqModel', 'pruebaPresuntivaModel', 'pruebaConfirmativaModel', 'dqoModel')
        );
    }



    //todo *******************************************
    //todo Inicio Seccion de Volumetria
    //todo *******************************************
    public function capturaVolumetria()
    {
        $parametro = DB::table('ViewParametroUsuarios')->where('Id_user', Auth::user()->id)->get();
        // $formulas = DB::table('ViewTipoFormula')->where('Id_area',2)->get();
        // var_dump($parametro); 
        $controlModel = ControlCalidad::all();
        return view('laboratorio.fq.capturaVolumetria', compact('parametro', 'controlModel'));
    }
    public function operacionVolumetriaNitrogeno(Request $request)
    {
        $res1 = $request->A - $request->B;
        $res2 = $res1 * $request->C;
        $res3 = $res2 * $request->D;
        $res4 = $res3 * $request->E;
        $res = $res4 / $request->G;

        $data = array(

            'res' => $res,

        );
        return response()->json($data);
    }
    public function operacionVolumetriaNitrogenoEquipo(Request $request)
    {
        $a = $request->A * $request->B;
        $d = 100 + $request->D;
        $c = 100 + $request->C;

        $res = $a * ($d / $c);

        $data = array(

            'res' => $res,

        );
        return response()->json($data);
    }

    public function operacionVolumetriaCloro(Request $request)
    {
        $res1 = $request->A - $request->B;
        $res2 = $res1 * $request->C;
        $res3 = $res2 * $request->D;
        $res = $res3 / $request->E;

        $data = array(

            'res' => $res,

        );
        return response()->json($data);
    }
    public function operacionVolumetriaDqo(Request $request)
    {
        $x = 0;
        $d = 0;
        if ($request->sw == 1) {
            $res1 = ($request->CA - $request->B);
            $res2 = ($res1 * $request->C);
            $res3 = ($res2 * $request->D);
            $res = ($res3 / $request->E);
        } else {
            $d = 2 / $request->E;
            $x = ($request->X + $request->Y + $request->Z) / 3;
            $res = ((($x - $request->CB) / $request->CM) * $d);
        }


        $data = array(
            'd' => $d,
            'x' => $x,
            'res' => $res
        );
        return response()->json($data);
    }
    public function guardarDqo(Request $request)
    {
        if ($request->sw == 1) {
            $model = LoteDetalleDqo::find($request->idDetalle);
            $model->Titulo_muestra = $request->B;
            $model->Molaridad = $request->C;
            $model->Titulo_blanco = $request->CA;
            $model->Equivalencia = $request->D;
            $model->Vol_muestra = $request->E;
            $model->Resultado = $request->resultado;
            $model->Tecnica = $request->radio;
            $model->Analizo = Auth::user()->id;
            $model->save();
        } else {
            $model = LoteDetalleDqo::find($request->idDetalle);
            $model->Vol_muestra = $request->Vol_muestra;
            $model->Abs_prom = $request->ABS;
            $model->Blanco = $request->CA;
            $model->Factor_dilucion = $request->D;
            $model->Vol_muestra = $request->Vol_muestra;
            $model->Abs1 = $request->X;
            $model->Abs2 = $request->Y;
            $model->Abs3 = $request->Z;
            $model->Resultado = $request->resultado;
            $model->Tecnica = $request->radio;
            $model->Analizo = Auth::user()->id;
            $model->save();
        }



        $data = array(
            'model' => $model,
            'radio' => $request->radio,
        );
        return response()->json($data);
    }
    public function guardarNitrogeno(Request $request)
    {

        $model = LoteDetalleNitrogeno::find($request->idDetalle);
        $model->Titulado_muestra = $request->A;
        $model->Titulado_blanco = $request->B;
        $model->Molaridad = $request->C;
        $model->Factor_equivalencia = $request->D;
        $model->Factor_conversion = $request->E;
        $model->Vol_muestra = $request->G;
        $model->Resultado = $request->resultado;
        $model->analizo = Auth::user()->id;
        $model->save();

        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function guardarNitrogenoEquipo(Request $request)
    {

        $model = LoteDetalleNitrogeno::find($request->idDetalle);
        $model->Titulado_muestra = $request->A; //Facor de dilución
        $model->Titulado_blanco = $request->B; //Concentracion de NH3 en mg/L
        $model->Molaridad = $request->C; //Volumen Añadido al std
        $model->Factor_equivalencia = $request->D; //Volumen añadido a la muestra
        $model->Vol_muestra = $request->V; //Volumen de la muestra en mL
        $model->Resultado = $request->resultado; //Resultado
        $model->Observacion = $request->O; //observacion
        $model->analizo = Auth::user()->id;
        $model->save();

        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function guardarCloro(Request $request)
    {

        $model = LoteDetalleCloro::find($request->idDetalle);
        $model->Vol_muestra = $request->A;
        $model->Ml_muestra = $request->E;
        $model->Vol_blanco = $request->B;
        $model->Normalidad = $request->C;
        $model->Ph_inicial = $request->G;
        $model->Ph_final = $request->H;
        $model->Factor_conversion = $request->D;
        $model->Resultado = round($request->Resultado);
        $model->analizo = Auth::user()->id;
        $model->save();

        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }

    public function createControlCalidadVol(Request $request)
    {

        switch ($request->idParametro) {
            case 295:
            case 64:
                # Cloro
                $muestra = LoteDetalleCloro::where('Id_detalle', $request->idMuestra)->first();
                $model = $muestra->replicate();
                $model->Id_control = $request->idControl;
                break;
            case 6:
                # DQO
                $muestra = LoteDetalleDqo::where('Id_detalle', $request->idMuestra)->first();
                $model = $muestra->replicate();
                $model->Id_control = $request->idControl;
                break;
            case 9:
            case 10:
            case 11:
                # Nitrogeno
                $muestra = LoteDetalleNitrogeno::where('Id_detalle', $request->idMuestra)->first();
                $model = $muestra->replicate();
                $model->Id_control = $request->idControl;
                break;
            default:
                # code...
                # Nitrogeno
                $muestra = LoteDetalleNitrogeno::where('Id_detalle', $request->idMuestra)->first();
                $model = $muestra->replicate();
                $model->Id_control = $request->idControl;
                break;
        }

        $model->save();

        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function createControlesCalidadEspectro(Request $res)
    {
        $muestra = LoteDetalleEspectro::where('Id_lote', $res->idLote)->first();

        switch ($res->idParametro) {
            case 295:
                # Cloro

                break;
            case 6:
                # DQO
                //? Blanco reactivo
                $muestra = LoteDetalleDqo::where('Id_lote', $res->idLote)->first();
                $model = $muestra->replicate();
                $model->Id_control = 9;
                $model->Resultado = NULL;
                $model->save();

                //? Estandar
                $muestra = LoteDetalleDqo::where('Id_lote', $res->idLote)->first();
                $model = $muestra->replicate();
                $model->Id_control = 4;
                $model->Resultado = NULL;
                $model->save();

                //? Muestra Adicionada  
                $muestra = LoteDetalleDqo::where('Id_lote', $res->idLote)->first();
                $model = $muestra->replicate();
                $model->Id_control = 3;
                $model->Resultado = NULL;
                $model->save();

                $muestra = LoteDetalleDqo::where('Id_lote', $res->idLote)->get();
                $loteModel = LoteAnalisis::find($res->idLote);
                $loteModel->Asignado = $muestra->count();
                $loteModel->save();
                break;
            case 9:
            case 10:
            case 11:
                # Nitrogeno

                break;
            default:
                # code...

                break;
        }

        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }

    public function getLotevol(Request $request)
    {
        $model = DB::table('ViewLoteAnalisis')->where('Id_tecnica', $request->formulaTipo)->where('Fecha', $request->fechaAnalisis)->get();
        $data = array(
            'lote' => $model,

        );
        return response()->json($data);
    }

    public function getLoteCapturaVol(Request $request)
    {
        $tipo = "";
        $tipoDetalle = "";
        if ($request->formulaTipo == 6) //todo DQO
        {
            $detalle = DB::table('ViewLoteDetalleDqo')->where('Id_lote', $request->idLote)->get(); // Asi se hara con las otras
            $tipoDetalle = LoteDetalleDqo::where('Id_lote', $request->idLote)->first();
            if ($tipoDetalle->Tipo == 1) {
                $tipo = "DQO ALTA";
            } elseif ($tipoDetalle->Tipo == 2) {
                $tipo = "DQO BAJA";
            } elseif ($tipoDetalle->Tipo == 3) {
                $tipo = "DQO TUBO SELLADO ALTA";
            } elseif ($tipoDetalle->Tipo == 4) {
                $tipo = "DQO TUBO SELLADO BAJA";
            } else {
                $tipo = "";
            }
        } else if ($request->formulaTipo == 33 || $request->formulaTipo == 218 || $request->formulaTipo == 64) //todo CLORO RESIDUAL LIBRE
        {
            $detalle = DB::table('ViewLoteDetalleCloro')->where('Id_lote', $request->idLote)->get(); // Asi se hara con las otras
        } else if ($request->formulaTipo == 9 || $request->formulaTipo == 10 || $request->formulaTipo == 11 || $request->formulaTipo == 287 || $request->formulaTipo == 108) //todo Nitrógeno Total,
        {
            $detalle = DB::table('ViewLoteDetalleNitrogeno')->where('Id_lote', $request->idLote)->get(); // Asi se hara con las otras
        }

        $data = array(
            'detalle' => $detalle,
            'tipo' => $tipo,
            'tipodetalle' => @$tipoDetalle->Tipo,
        );
        return response()->json($data);
    }
    public function setPlantillaDetalleVol(Request $res)
    {
        $lote = DB::table('ViewLoteAnalisis')->where('Id_lote', $res->id)->first();
        $temp = BitacoraVolumetria::where('Id_lote', $res->id)->get();
        if ($temp->count()) {
            $model = BitacoraVolumetria::where('Id_lote', $res->id)->first();
            $model->Titulo = $res->titulo;
            $model->Texto = $res->texto;
            $model->Rev = $res->rev;
            $model->save();
        } else {
            $model = BitacoraVolumetria::create([
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
    public function getDetalleVol(Request $request)
    {
        $curva = '';
        $valoracion = '';
        switch ($request->caso) {
            case 1: //todo Cloro
                $model = DB::table('ViewLoteDetalleCloro')->where('Id_detalle', $request->idDetalle)->first(); // Asi se hara con las otras
                $valoracion = ValoracionCloro::where('Id_lote', $model->Id_lote)->first();
                break;
            case 2: //todo DQO
                $fecha = new Carbon($request->fechaAnalisis);
                $today = $fecha->toDateString();
                $model = DB::table("ViewLoteDetalleDqo")->where('Id_detalle', $request->idDetalle)->first();
                if ($model->Tecnica == 1) {
                    if ($model->Tipo == 3) {
                        $curva = CurvaConstantes::whereDate('Fecha_inicio', '<=', $today)->whereDate('Fecha_fin', '>=', $today)->where('Id_area', 16)->where('Id_parametro', 74)->first();
                    } else {
                        $curva = CurvaConstantes::whereDate('Fecha_inicio', '<=', $today)->whereDate('Fecha_fin', '>=', $today)->where('Id_area', 16)->where('Id_parametro', 75)->first();
                    }
                } else {
                    $valoracion = ValoracionDqo::where('Id_lote', $model->Id_lote)->first();
                }
                break;
            case 3: //todo  Nitrogeno
                $model = DB::table("ViewLoteDetalleNitrogeno")->where('Id_detalle', $request->idDetalle)->first();
                $valoracion = ValoracionNitrogeno::where('Id_lote', $model->Id_lote)->first();
                break;
            default:
                # code...
                break;
        }

        $data = array(
            'model' => $model,
            'curva' => $curva,
            'valoracion' => $valoracion,
            'estadoRadio' => $request->radio,
        );
        return response()->json($data);
    }
    public function liberarTodo(Request $res)
    {
        $sw = false;

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
                $modelCod->Analizo = Auth::user()->id;
                $modelCod->save();
            }
        }
    }
    public function liberarMuestraVol(Request $request)
    {
        $sw = false;
        $model = 0;

        switch ($request->formulaTipo) {
            case 6:
                $model = LoteDetalleDqo::find($request->idMuestra);
                $model->Liberado = 1;
                if ($model->Resultado != null) {
                    $sw = true;
                    $model->save();
                }

                $modelCod = CodigoParametros::find($model->Id_codigo);
                $modelCod->Resultado = $model->Resultado;
                $modelCod->save();


                $model = LoteDetalleDqo::where('Id_lote', $request->idLote)->where('Liberado', 1)->get();
                break;
                //Cloruros y cloro
            case 295:
            case 64:
                $model = LoteDetalleCloro::find($request->idMuestra);
                $model->Liberado = 1;
                if ($model->Resultado != null) {
                    $sw = true;
                    $model->save();
                }

                $modelCod = CodigoParametros::find($model->Id_codigo);
                $modelCod->Resultado = $model->Resultado;
                $modelCod->save();


                $model = LoteDetalleCloro::where('Id_lote', $request->idLote)->where('Liberado', 1)->get();
                break;
            case 10:
            case 9:
            case 11:
            case 108:
                $model = LoteDetalleNitrogeno::find($request->idMuestra);
                $model->Liberado = 1;
                if ($model->Resultado != null) {
                    $sw = true;
                    $model->save();
                }

                $modelCod = CodigoParametros::find($model->Id_codigo);
                $modelCod->Resultado = $model->Resultado;
                $modelCod->save();


                $model = LoteDetalleNitrogeno::where('Id_lote', $request->idLote)->where('Liberado', 1)->get();
                break;
            default:
                # code...
                break;
        }

        $loteModel = LoteAnalisis::find($request->idLote);
        $loteModel->Liberado = $model->count();
        $loteModel->save();


        $data = array(
            'model' => $model,
            'sw' => $sw,
        );
        return response()->json($data);
    }
    public function sendMuestrasLote(Request $res)
    {
        $mensaje = "";
        $sw = false;
        $loteModel = LoteAnalisis::where('Id_lote', $res->idLote)->first();
        $paraModel = Parametro::find($loteModel->Id_tecnica);

        switch ($paraModel->Id_parametro) {
            case 6: //Volumetria
                for ($i = 0; $i < sizeof($res->idCodigos); $i++) {
                    $sol = CodigoParametros::where('Id_codigo', $res->idCodigos[$i])->first();
                    $model = LoteDetalleDqo::create([
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

                $sw = true;
                $mensaje = "case";
                break;
            default:
                $sw = false;
                $mensaje = "default";
                break;
        }
        $detModel = LoteDetalleDqo::where('Id_lote', $res->idLote)->get();
        $loteModel = LoteAnalisis::find($res->idLote);
        $loteModel->Asignado = $detModel->count();
        $loteModel->Liberado = 0;
        $loteModel->save();

        $data = array(
            'idArea' => $paraModel->Id_area,
            'sw' => $sw,
            'model' => $paraModel,
            'mensaje' => $mensaje,
        );
        return response()->json($data);
    }





    //todo *******************************************
    //todo Fin Seccion de Volumetria
    //todo *******************************************     

    public function exportPdfBitacoraVol($idLote)
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
        $lote = DB::table('ViewLoteAnalisis')->where('Id_lote', $idLote)->first();
        switch ($lote->Id_tecnica) {
            case 6:
                $detalle = DqoDetalle::where('Id_lote', $idLote)->first();
                switch ($detalle->Tipo) {
                    case 1: // Dqo Alta
                        $loteDetalle = DB::table('ViewLoteDetalleDqo')->where('Id_lote', $idLote)->get();
                        $valDqo = ValoracionDqo::where('Id_lote', $idLote)->first();
                        $plantilla = BitacoraVolumetria::where('Id_lote', $idLote)->get();
                        if ($plantilla->count()) {
                            if ($detalle->Soluble == 1) {
                                $plantilla = PlantillaVolumetria::where('Id_parametro', 159)->get(); 
                            }else{
                                $plantilla = PlantillaVolumetria::where('Id_parametro', 72)->get();
                            }
                        } else {
                            if ($detalle->Soluble == 1) {
                                $plantilla = PlantillaVolumetria::where('Id_parametro', 159)->get(); 
                            }else{
                                $plantilla = PlantillaVolumetria::where('Id_parametro', 72)->get();
                            }
                        }
                        $procedimiento = explode("NUEVASECCION", $plantilla[0]->Texto);

                        $comprobacion = LoteDetalleDqo::where('Liberado', 0)->where('Id_lote', $idLote)->get();
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
                        $loteDetalle = DB::table('ViewLoteDetalleDqo')->where('Id_lote', $idLote)->get();
                        $valDqo = ValoracionDqo::where('Id_lote', $idLote)->first();
                        $plantilla = BitacoraVolumetria::where('Id_lote', $idLote)->get();
                        if ($plantilla->count()) {
                            if ($detalle->Soluble == 1) {
                                $plantilla = PlantillaVolumetria::where('Id_parametro', 160)->get(); 
                            }else{
                                $plantilla = PlantillaVolumetria::where('Id_parametro', 73)->get();
                            }
                        } else {
                            if ($detalle->Soluble == 1) {
                                $plantilla = PlantillaVolumetria::where('Id_parametro', 160)->get(); 
                            }else{
                                $plantilla = PlantillaVolumetria::where('Id_parametro', 73)->get();
                            }
                        }
                        $procedimiento = explode("NUEVASECCION", $plantilla[0]->Texto);

                        $comprobacion = LoteDetalleDqo::where('Liberado', 0)->where('Id_lote', $idLote)->get();
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
            case 64:
                $loteDetalle = DB::table('ViewLoteDetalleCloro')->where('Id_lote', $idLote)->get();
                $plantilla = BitacoraVolumetria::where('Id_lote', $idLote)->get();
                if ($plantilla->count()) {
                } else {
                    $plantilla = PlantillaVolumetria::where('Id_parametro', $lote->Id_tecnica)->get();
                }
                $procedimiento = explode("NUEVASECCION", $plantilla[0]->Texto);
                $valoracion = ValoracionCloro::where('Id_lote', $idLote)->first();
                $comprobacion = LoteDetalleEspectro::where('Liberado', 0)->where('Id_lote', $idLote)->get();
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
                $loteDetalle = DB::table('ViewLoteDetalleCloro')->where('Id_lote', $idLote)->get();
                $textProcedimiento = DB::table('plantilla_volumetria')->where('Id_parametro', 218)->first();
                $data = array(
                    'lote' => $lote,
                    'loteDetalle' => $loteDetalle,
                    'textProcedimiento' => $textProcedimiento,
                );
                $htmlCaptura = view('exports.laboratorio.volumetria.cloro.capturaBody', $data);
                $htmlHeader = view('exports.laboratorio.volumetria.cloro.capturaHeader', $data);

                $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                $mpdf->SetHTMLFooter("", 'O', 'E');
                $mpdf->WriteHTML($htmlCaptura);
                $mpdf->CSSselectMedia = 'mpdf';
                break;
            case 287:
            case 9:// Nitrogeno amoniacal
                $loteDetalle = DB::table('ViewLoteDetalleNitrogeno')->where('Id_lote', $idLote)->get();
                $valNitrogenoA = ValoracionNitrogeno::where('Id_lote', $idLote)->first();
                $plantilla = BitacoraVolumetria::where('Id_lote', $idLote)->get();
                if ($plantilla->count()) {
                } else {
                    $plantilla = PlantillaVolumetria::where('Id_parametro', $lote->Id_tecnica)->get();
                }
                $procedimiento = explode("NUEVASECCION", $plantilla[0]->Texto);
                $valoracion = ValoracionCloro::where('Id_lote', $idLote)->first();

                $comprobacion = LoteDetalleNitrogeno::where('Liberado', 0)->where('Id_lote', $idLote)->get();
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

                $loteDetalle = DB::table('ViewLoteDetalleNitrogeno')->where('Id_lote', $idLote)->get();
                $valNitrogenoA = ValoracionNitrogeno::where('Id_lote', $idLote)->first();
                $plantilla = BitacoraVolumetria::where('Id_lote', $idLote)->get();
                if ($plantilla->count()) {
                } else {
                    $plantilla = PlantillaVolumetria::where('Id_parametro', $lote->Id_tecnica)->get();
                }
                $procedimiento = explode("NUEVASECCION", $plantilla[0]->Texto);
                $comprobacion = LoteDetalleEspectro::where('Liberado', 0)->where('Id_lote', $idLote)->get();
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
                $mpdf->CSSselectMedia = 'mpdf';
                break;
            case 10:
                $loteDetalle = DB::table('ViewLoteDetalleNitrogeno')->where('Id_lote', $idLote)->get();
                $valNitrogenoA = ValoracionNitrogeno::where('Id_lote', $idLote)->first();
                $plantilla = BitacoraVolumetria::where('Id_lote', $idLote)->get();
                if ($plantilla->count()) {
                } else {
                    $plantilla = PlantillaVolumetria::where('Id_parametro', $lote->Id_tecnica)->get();
                }
                $procedimiento = explode("NUEVASECCION", $plantilla[0]->Texto);
                $valoracion = ValoracionCloro::where('Id_lote', $idLote)->first();

                $comprobacion = LoteDetalleNitrogeno::where('Liberado', 0)->where('Id_lote', $idLote)->get();
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
        $mpdf->Output();
    }

}
