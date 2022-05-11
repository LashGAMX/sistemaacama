<?php

namespace App\Http\Controllers\Laboratorio;

use App\Http\Controllers\Controller;
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
use App\Models\Reportes;
use App\Models\SecadoCartucho;
use App\Models\Tecnica;
use App\Models\TiempoReflujo;
use App\Models\ValoracionCloro;
use App\Models\ValoracionDqo;
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
        $parametro = DB::table('ViewParametros')
            ->orWhere('Id_area', 14)
            ->get();

        $textoRecuperadoPredeterminado = ReportesFq::where('Id_reporte', 0)->first();
        return view('laboratorio.fq.loteVol', compact('parametro', 'textoRecuperadoPredeterminado'));
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
            case 3: // DQO
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


            default:
                # code...
                break;
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
        $reporte = ReportesFq::where('Id_lote', $request->idLote)->first();

        //RECUPERA EL APARTADO DE FÓRMULAS GLOBALES;
        $constantesModel = CurvaConstantes::where('Id_lote', $request->idLote)->get();
        if ($constantesModel->count()) {
            $constantes = CurvaConstantes::where('Id_lote', $request->idLote)->first();
        } else {
            $constantes = null;
        }

        /*Módulo de Grasas*/
        $dataGrasas = array();

        $calentamientoFq = DB::table('calentamiento_matraces')->where('Id_lote', $request->idLote)->get();
        $enfriadoFq = DB::table('enfriado_matraces')->where('Id_lote', $request->idLote)->get();
        $secadoFq = DB::table('secado_cartuchos')->where('Id_lote', $request->idLote)->get();
        $tiempoFq = DB::table('tiempo_reflujo')->where('Id_lote', $request->idLote)->get();
        $enfriadoMatrazFq = DB::table('enfriado_matraz')->where('Id_lote', $request->idLote)->get();

        if ($calentamientoFq->count()) {
            array_push($dataGrasas, $calentamientoFq);
        } else {
            array_push($dataGrasas, null);
        }

        if ($enfriadoFq->count()) {
            array_push($dataGrasas, $enfriadoFq);
        } else {
            array_push($dataGrasas, null);
        }

        if ($secadoFq->count()) {
            $secadoCartucho = SecadoCartucho::where('Id_lote', $request->idLote)->first();
            array_push($dataGrasas, $secadoCartucho);
        } else {
            array_push($dataGrasas, null);
        }

        if ($tiempoFq->count()) {
            $tiempoReflujo = TiempoReflujo::where('Id_lote', $request->idLote)->first();
            array_push($dataGrasas, $tiempoReflujo);
        } else {
            array_push($dataGrasas, null);
        }

        if ($enfriadoMatrazFq->count()) {
            $enfriadoMatraz = EnfriadoMatraz::where('Id_lote', $request->idLote)->first();
            array_push($dataGrasas, $enfriadoMatraz);
        } else {
            array_push($dataGrasas, null);
        }


        /* Módulo de coliformes */
        $dataColi = array();

        $sembradoFq = DB::table('sembrado_fq')->where('Id_lote', $request->idLote)->get();
        $pruebaPresuntivaFq = DB::table('prueba_presuntiva_fq')->where('Id_lote', $request->idLote)->get();
        $pruebaConfirmativaFq = DB::table('prueba_confirmativa_fq')->where('Id_lote', $request->idLote)->get();

        if ($sembradoFq->count() && $pruebaPresuntivaFq->count() && $pruebaConfirmativaFq->count()) {
            $sembradoFq = SembradoFq::where('Id_lote', $request->idLote)->first(); //Array 0
            $pruebaPresunFq = PruebaPresuntivaFq::where('Id_lote', $request->idLote)->first(); //Array 1
            $pruebaConfirFq = PruebaConfirmativaFq::where('Id_lote', $request->idLote)->first(); //Array 2

            array_push(
                $dataColi,
                $sembradoFq,
                $pruebaPresunFq,
                $pruebaConfirFq
            );
        } else {
            array_push(
                $dataColi,
                null,
                null,
                null
            );
        }
        //-------------------------------------

        /* Módulo DQO */
        $dqoModel = DB::table('dqo_fq')->where('Id_lote', $request->idLote)->get();

        if ($dqoModel->count()) {
            $dqo = DqoFq::where('Id_lote', $request->idLote)->first();
        } else {
            $dqo = null;
        }

        //? Obtiene los valores para llenar la valoracion
        $paraModel = LoteAnalisis::find($request->idLote);
        switch ($paraModel->Id_tecnica) {
            case 7: //todo DQO
                $valoracion = ValoracionDqo::where('Id_lote', $request->idLote)->first();
                break;
            case 295: //todo CLORO RESIDUAL LIBRE
                $valoracion = ValoracionCloro::where('Id_lote', $request->idLote)->first();
                break;
            case 12: //todo NITROGENO TOTAL
                $valoracion = ValoracionNitrogeno::where('Id_lote', $request->idLote)->first();
                break;
            default:

                break;
        }

        //-------------------------------------
        $data = array(
            'valoracion' => $valoracion,
            'curvaConstantes' => $constantes,
            'idLoteIf' => $idLoteIf,
            'reporte' => $reporte,
            'dataGrasas' => $dataGrasas,
            'dataColi' => $dataColi,
            'dataDqo' => $dqo
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
            case 3: // Nitrogeno
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


    public function getPlantillaPred(Request $request)
    {
        $bandera = '';

        //Obtiene el parámetro que se está consultando
        $parametro = DB::table('ViewLoteDetalleDqo')->where('Id_lote', $request->idLote)->first();

        if (is_null($parametro)) {
            $parametro = DB::table('ViewLoteDetalleCloro')->where('Id_lote', $request->idLote)->first();

            if (!is_null($parametro)) {
                $bandera = 'cloro';
            }else{
                $parametro = DB::table('ViewLoteDetalleNitrogeno')->where('Id_lote', $request->idLote)->first();

                if(!is_null($parametro)){
                    $bandera = 'nitrogeno';
                }
            }
        } else {
            $bandera = 'dqo';
        }

        if ($bandera == 'dqo') {
            if ($parametro->Id_parametro == 7 || $parametro->Id_parametro == 77) { // DQO
                $plantillaPredeterminada = ReportesFq::where('Id_reporte', 29)->first();
            } else if ($parametro->Id_parametro == 73 || $parametro->Id_parametro == 75) { // DQO ALTA
                $plantillaPredeterminada = ReportesFq::where('Id_reporte', 25)->first();
            } else if ($parametro->Id_parametro == 74 || $parametro->Id_parametro == 76) { // DQO BAJA
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
        }else if($bandera == 'nitrogeno'){            
            if ($parametro->Id_parametro == 12) { // NITROGENO TOTAL
                $plantillaPredeterminada = ReportesFq::where('Id_reporte', 30)->first();
            }else if($parametro->Id_parametro == 10 || $parametro->Id_parametro == 117 || $parametro->Id_parametro == 296){ // NITROGENO AMONIACAL
                $plantillaPredeterminada = ReportesFq::where('Id_reporte', 27)->first();
            }else if($parametro->Id_parametro == 11){ // NITROGENO ORGANICO
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

        if($loteModel->Id_tecnica == 7) //todo DQO
        {
            $model = DB::table('ViewLoteDetalleDqo')->where('Id_lote', $request->idLote)->get();
        } else if($loteModel->Id_tecnica == 295) //todo CLORO RESIDUAL LIBRE
        {
            $model = DB::table('ViewLoteDetalleCloro')->where('Id_lote', $request->idLote)->get();
        } else if($loteModel->Id_tecnica == 10 || $loteModel->Id_tecnica == 11 || $loteModel->Id_tecnica == 12) //todo Nitrógeno Total,
        {
            $model = DB::table('ViewLoteDetalleNitrogeno')->where('Id_lote', $request->idLote)->get();
        }
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    //! Eliminar parametro muestra
    public function delMuestraLote(Request $request)
    {
        $loteModel = LoteAnalisis::where('Id_lote', $request->idLote)->first();
        switch ($loteModel->Id_tecnica) {
            case 9: //todo Espectrofotometria
                $detModel = DB::table('lote_detalle_espectro')->where('Id_detalle', $request->idDetalle)->delete();
                $detModel = LoteDetalleEspectro::where('Id_lote', $request->idLote)->get();
                break;
            case 10: //todo Gravimetia
                # code...
                break;
            case 15: //todo Volumetria
                # code...
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
    public function liberarMuestraMetal(Request $request)
    {

        $detalle = LoteDetalle::find($request->idDetalle);
        $detalle->Liberado = 1;
        $detalle->save();

        $detalleModel = LoteDetalle::where('Id_lote', $detalle->Id_lote)->where('Liberado', 1)->get();

        $lote = LoteAnalisis::find($detalle->Id_lote);
        $lote->Liberado = $detalleModel->count();
        $lote->save();

        $detalleModel = DB::table('ViewLoteDetalle')->where('Id_lote', $detalle->Id_lote)->get();

        $loteModel = LoteAnalisis::where('Id_lote', $detalle->Id_lote)->first();


        $data = array(
            'detalleModel' => $detalleModel,
            'liberado' => $detalleModel->count(),
            'lote' => $loteModel,
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
        if($loteModel->Id_tecnica == 7) //todo DQO
        {
            $model = LoteDetalleDqo::create([
                'Id_lote' => $request->idLote,
                'Id_analisis' => $request->idAnalisis,
                'Id_codigo' => $request->idSol,
                'Id_parametro' => $loteModel->Id_tecnica,
                'Id_control' => 1,
            ]);
            $detModel = LoteDetalleDqo::where('Id_lote', $request->idLote)->get();
            $sw = true;
        } else if($loteModel->Id_tecnica == 295) //todo CLORO RESIDUAL LIBRE
        {
            $model = LoteDetalleCloro::create([
                'Id_lote' => $request->idLote,
                'Id_analisis' => $request->idAnalisis,
                'Id_codigo' => $request->idSol,
                'Id_parametro' => $loteModel->Id_tecnica,
                'Id_control' => 1,
            ]);
            $detModel = LoteDetalleCloro::where('Id_lote', $request->idLote)->get();
            $sw = true;
        } else if($loteModel->Id_tecnica == 10 || $loteModel->Id_tecnica == 11 || $loteModel->Id_tecnica == 12) //todo Nitrógeno Total,
        {
            $model = LoteDetalleNitrogeno::create([
                'Id_lote' => $request->idLote,
                'Id_analisis' => $request->idAnalisis,
                'Id_codigo' => $request->idSol,
                'Id_parametro' => $loteModel->Id_tecnica,
                'Id_control' => 1,
            ]);
            $detModel = LoteDetalleNitrogeno::where('Id_lote', $request->idLote)->get();
            $sw = true;
        }
     


        $solModel = CodigoParametros::find($request->idSol);
        $solModel->Asignado = 1;
        $solModel->save();

        $loteModel = LoteAnalisis::find($request->idLote);
        $loteModel->Asignado = $detModel->count();
        $loteModel->Liberado = 0;
        $loteModel->save();

        $data = array(
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
        $parametro = DB::table("ViewParametros")->where('Id_area', 14)->get();
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

        $res1 = ($request->CA - $request->B);
        $res2 = ($res1 * $request->C);
        $res3 = ($res2 * $request->D);
        $res = ($res3 / $request->E);

        $data = array(
            'res' => $res
        );
        return response()->json($data);
    }
    public function guardarDqo(Request $request)
    {

        $model = LoteDetalleDqo::find($request->idDetalle);
        $model->Titulo_muestra = $request->B;
        $model->Molaridad = $request->C;
        $model->Titulo_blanco = $request->CA;
        $model->Equivalencia = $request->D;
        $model->Vol_muestra = $request->E;
        $model->Resultado = $request->resultado;
        $model->save();

        $data = array(
            'model' => $model,
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
        $model->Resultado = $request->Resultado;
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
                # Cloro
                $muestra = LoteDetalleCloro::where('Id_detalle', $request->idMuestra)->first();
                $model = $muestra->replicate();
                $model->Id_control = $request->idControl;
                break;
            case 7:
                # DQO
                $muestra = LoteDetalleDqo::where('Id_detalle', $request->idMuestra)->first();
                $model = $muestra->replicate();
                $model->Id_control = $request->idControl;
                break;
            case 12:
                # Nitrogeno
                $muestra = LoteDetalleNitrogeno::where('Id_detalle', $request->idMuestra)->first();
                $model = $muestra->replicate();
                $model->Id_control = $request->idControl;
                break;
            default:
                # code...
                break;
        }

        $model->save();

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
        if($request->formulaTipo == 7) //todo DQO
        {
            $detalle = DB::table('ViewLoteDetalleDqo')->where('Id_lote', $request->idLote)->get(); // Asi se hara con las otras
        } else if($request->formulaTipo == 295) //todo CLORO RESIDUAL LIBRE
        {
            $detalle = DB::table('ViewLoteDetalleCloro')->where('Id_lote', $request->idLote)->get(); // Asi se hara con las otras
        } else if($request->formulaTipo == 10 || $request->formulaTipo == 11 || $request->formulaTipo == 12) //todo Nitrógeno Total,
        {
            $detalle = DB::table('ViewLoteDetalleNitrogeno')->where('Id_lote', $request->idLote)->get(); // Asi se hara con las otras
        }
        $data = array(
            'detalle' => $detalle,
        );
        return response()->json($data);
    }
    public function getDetalleVol(Request $request)
    {
        switch ($request->caso) {
            case 1: //todo Cloro
                $model = DB::table('ViewLoteDetalleCloro')->where('Id_detalle', $request->idDetalle)->first(); // Asi se hara con las otras
                $valoracion = ValoracionCloro::where('Id_lote', $model->Id_lote)->first();
                break;
            case 2: //todo DQO
                $model = DB::table("ViewLoteDetalleDqo")->where('Id_detalle', $request->idDetalle)->first();
                $valoracion = ValoracionDqo::where('Id_lote', $model->Id_lote)->first();
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
            'valoracion' => $valoracion,
        );
        return response()->json($data);
    }
    public function liberarMuestraVol(Request $request)
    {
        $sw = false;

        if($request->formulaTipo == 7) //todo DQO
        {
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
        } else if($request->formulaTipo == 295) //todo CLORO RESIDUAL LIBRE
        {
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
        } else if($request->formulaTipo == 10 || $request->formulaTipo == 11 || $request->formulaTipo == 12) //todo Nitrógeno Total,
        {
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
    //todo *******************************************
    //todo Fin Seccion de Volumetria
    //todo *******************************************     

    public function exportPdfCapturaVolumetria($idLote)
    {
        $sw = true;
        $proced = false;

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

        //Establece la marca de agua del documento PDF
        $mpdf->SetWatermarkImage(
            asset('/public/storage/MembreteVertical.png'),
            1,
            array(215, 280),
            array(0, 0),
        );

        $mpdf->showWatermarkImage = true;

        $id_lote = $idLote;
        $semaforo = true;

        //Recupera el nombre de usuario y firma
        $usuario = DB::table('users')->where('id', auth()->user()->id)->first();
        $firma = $usuario->firma;

        //Formatea la fecha
        $fechaAnalisis = DB::table('ViewLoteAnalisis')->where('Id_lote', $id_lote)->first();
        if (!is_null($fechaAnalisis)) {
            $fechaConFormato = date("d/m/Y", strtotime($fechaAnalisis->Fecha));
        } else {
            $fechaAnalisis = DB::table('ViewLoteAnalisis')->where('Id_lote', 0)->first();
            $fechaConFormato = date("d/m/Y", strtotime($fechaAnalisis->Fecha));
            echo '<script> alert("Valores predeterminados para la fecha de análisis. Rellena este campo.") </script>';
        }

        //Recupera el parámetro que se está utilizando
        $parametro = DB::table('ViewLoteDetalleCloro')->where('Id_lote', $id_lote)->first();
        if (is_null($parametro)) {
            $parametro = DB::table('ViewLoteDetalleDqo')->where('Id_lote', $id_lote)->first();
            if (is_null($parametro)) {
                $parametro = DB::table('ViewLoteDetalleNitrogeno')->where('Id_lote', $id_lote)->first();
            }
        }

        //Recupera el texto dinámico Procedimientos de la tabla reportes****************************************************
        $textProcedimiento = ReportesFq::where('Id_lote', $id_lote)->first();
        if (!is_null($textProcedimiento)) {
            //Hoja1    
            $proced = true;
            if ($parametro->Id_parametro == 7 || $parametro->Id_parametro == 77) { // DQO
                $data = DB::table('ViewLoteDetalleDqo')->where('Id_lote', $id_lote)->get();

                if (!is_null($data)) {
                    $dataLength = DB::table('ViewLoteDetalleDqo')->where('Id_lote', $id_lote)->count();

                    $separador = "Valoración";
                    $textoProcedimiento = explode($separador, $textProcedimiento->Texto);

                    $htmlCaptura = view('exports.laboratorio.fq.volumetria.dqo.capturaBody', compact('textoProcedimiento', 'data', 'dataLength'));
                } else {
                    $sw = false;
                    $mpdf->SetJS('print("No se han llenado datos en el reporte. Verifica que todos los datos estén ingresados.");');
                }
            } else if ($parametro->Id_parametro == 73 || $parametro->Id_parametro == 75) { //DQO ALTA
                $data = DB::table('ViewLoteDetalleDqo')->where('Id_lote', $id_lote)->get();

                if (!is_null($data)) {
                    $dataLength = DB::table('ViewLoteDetalleDqo')->where('Id_lote', $id_lote)->count();

                    $separador = "Valoración";
                    $textoProcedimiento = explode($separador, $textProcedimiento->Texto);

                    $htmlCaptura = view('exports.laboratorio.fq.volumetria.dqoA.capturaBody', compact('textoProcedimiento', 'data', 'dataLength'));
                } else {
                    $sw = false;
                    $mpdf->SetJS('print("No se han llenado datos en el reporte. Verifica que todos los datos estén ingresados.");');
                }
            } else if ($parametro->Id_parametro == 74 || $parametro->Id_parametro == 76) { // DQO BAJA
                $data = DB::table('ViewLoteDetalleDqo')->where('Id_lote', $id_lote)->get();

                if (!is_null($data)) {
                    $dataLength = DB::table('ViewLoteDetalleDqo')->where('Id_lote', $id_lote)->count();

                    $separador = "Valoración";
                    $textoProcedimiento = explode($separador, $textProcedimiento->Texto);

                    $htmlCaptura = view('exports.laboratorio.fq.volumetria.dqoB.capturaBody', compact('textoProcedimiento', 'data', 'dataLength'));
                } else {
                    $sw = false;
                    $mpdf->SetJS('print("No se han llenado llenado datos en el reporte. Verifica que todos los datos estén ingresados.");');
                }
            } else if ($parametro->Id_parametro == 170) { // DQO SOLUBLE
                $data = DB::table('ViewLoteDetalleDqo')->where('Id_lote', $id_lote)->get();

                if (!is_null($data)) {
                    $dataLength = DB::table('ViewLoteDetalleDqo')->where('Id_lote', $id_lote)->count();

                    $separador = "Valoración";
                    $textoProcedimiento = explode($separador, $textProcedimiento->Texto);

                    $htmlCaptura = view('exports.laboratorio.fq.volumetria.dqoSoluble.capturaBody', compact('textoProcedimiento', 'data', 'dataLength'));
                } else {
                    $sw = false;
                    $mpdf->SetJS('print("No se han llenado datos en el reporte. Verifica que todos los datos estén ingresados.");');
                }
            } else if ($parametro->Id_parametro == 168) { // DQO SOLUBLE ALTA
                $data = DB::table('ViewLoteDetalleDqo')->where('Id_lote', $id_lote)->get();

                if (!is_null($data)) {
                    $dataLength = DB::table('ViewLoteDetalleDqo')->where('Id_lote', $id_lote)->count();

                    $separador = "Valoración";
                    $textoProcedimiento = explode($separador, $textProcedimiento->Texto);

                    $htmlCaptura = view('exports.laboratorio.fq.volumetria.dqoSolubleAlta.capturaBody', compact('textoProcedimiento', 'data', 'dataLength'));
                } else {
                    $sw = false;
                    $mpdf->SetJS('print("No se han llenado datos en el reporte. Verifica que todos los datos estén ingresados.");');
                }
            } else if ($parametro->Id_parametro == 169) { // DQO SOLUBLE BAJA
                $data = DB::table('ViewLoteDetalleDqo')->where('Id_lote', $id_lote)->get();

                if (!is_null($data)) {
                    $dataLength = DB::table('ViewLoteDetalleDqo')->where('Id_lote', $id_lote)->count();

                    $separador = "Valoración";
                    $textoProcedimiento = explode($separador, $textProcedimiento->Texto);

                    $htmlCaptura = view('exports.laboratorio.fq.volumetria.dqoSolubleBaja.capturaBody', compact('textoProcedimiento', 'data', 'dataLength'));
                } else {
                    $sw = false;
                    $mpdf->SetJS('print("No se han llenado datos en el reporte. Verifica que todos los datos estén ingresados.");');
                }
            } else if ($parametro->Id_parametro == 12) { // NITROGENO TOTAL
                $data = DB::table('ViewLoteDetalleNitrogeno')->where('Id_lote', $id_lote)->get();

                if (!is_null($data)) {
                    $dataLength = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $id_lote)->count();

                    $htmlCaptura = view('exports.laboratorio.fq.volumetria.nitrogenoTotal.capturaBody', compact('textoProcedimiento', 'data', 'dataLength'));
                } else {
                    $sw = false;
                    $mpdf->SetJS('print("No se han llenado datos en el reporte. Verifica que todos los datos estén ingresados.");');
                }
            } else if ($parametro->Id_parametro == 10 || $parametro->Id_parametro == 117 || $parametro->Id_parametro == 296) { // NITROGENO AMONIACAL
                $data = DB::table('ViewLoteDetalleNitrogeno')->where('Id_lote', $id_lote)->get();

                if (!is_null($data)) {
                    $dataLength = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $id_lote)->count();

                    $htmlCaptura = view('exports.laboratorio.fq.volumetria.nitrogenoA.capturaBody', compact('textoProcedimiento', 'data', 'dataLength'));
                } else {
                    $sw = false;
                    $mpdf->SetJS('print("No se han llenado datos en el reporte. Verifica que todos los datos estén ingresados.");');
                }
            } else if ($parametro->Id_parametro == 11) { //NITROGENO ORGANICO
                $data = DB::table('ViewLoteDetalleNitrogeno')->where('Id_lote', $id_lote)->get();

                if (!is_null($data)) {
                    $dataLength = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $id_lote)->count();

                    $htmlCaptura = view('exports.laboratorio.fq.volumetria.nitrogenoO.capturaBody', compact('textoProcedimiento', 'data', 'dataLength'));
                } else {
                    $sw = false;
                    $mpdf->SetJS('print("No se han llenado datos en el reporte. Verifica que todos los datos estén ingresados.");');
                }
            } else if ($parametro->Id_parametro == 34 || $parametro->Id_parametro == 128 || $parametro->Id_parametro == 295) { // CLORO RESIDUAL LIBRE
                $data = DB::table('ViewLoteDetalleCloro')->where('Id_lote', $id_lote)->get();

                if (!is_null($data)) {
                    $dataLength = DB::table('ViewLoteDetalleCloro')->where('Id_lote', $id_lote)->count();

                    $separador = "Valoración";
                    $textoProcedimiento = explode($separador, $textProcedimiento->Texto);

                    $htmlCaptura = view('exports.laboratorio.fq.volumetria.cloroR.capturaBody', compact('textoProcedimiento', 'data', 'dataLength'));
                } else {
                    $sw = false;
                    $mpdf->SetJS('print("No se han llenado datos en el reporte. Verifica que todos los datos estén ingresados.");');
                }
            }
        } else {   //---------------                     
            if ($parametro->Id_parametro == 7 || $parametro->Id_parametro == 77) { // DQO
                $data = DB::table('ViewLoteDetalleDqo')->where('Id_lote', $id_lote)->get();

                if (!is_null($data)) {
                    $dataLength = DB::table('ViewLoteDetalleDqo')->where('Id_lote', $id_lote)->count();

                    $textProcedimiento = ReportesFq::where('Id_reporte', 29)->first();
                    $separador = "Valoración";
                    $textoProcedimiento = explode($separador, $textProcedimiento->Texto);

                    $htmlCaptura = view('exports.laboratorio.fq.volumetria.dqo.capturaBody', compact('textoProcedimiento', 'data', 'dataLength'));
                } else {
                    $sw = false;
                    $mpdf->SetJS('No se han llenado datos en el reporte. Verifica que todos los datos estén ingresados.");');
                }
            } else if ($parametro->Id_parametro == 73 || $parametro->Id_parametro == 75) { // DQO ALTA
                $data = DB::table('ViewLoteDetalleDqo')->where('Id_lote', $id_lote)->get();

                if (!is_null($data)) {
                    $dataLength = DB::table('ViewLoteDetalleDqo')->where('Id_lote', $id_lote)->count();

                    $textProcedimiento = ReportesFq::where('Id_reporte', 25)->first();
                    $separador = "Valoración";
                    $textoProcedimiento = explode($separador, $textProcedimiento->Texto);

                    $htmlCaptura = view('exports.laboratorio.fq.volumetria.dqoA.capturaBody', compact('textoProcedimiento', 'data', 'dataLength'));
                } else {
                    $sw = false;
                    $mpdf->SetJS('No se han llenado datos en el reporte. Verifica que todos los datos estén ingresados.");');
                }
            } else if ($parametro->Id_parametro == 74 || $parametro->Id_parametro == 76) { // DQO BAJA
                $data = DB::table('ViewLoteDetalleDqo')->where('Id_lote', $id_lote)->get();

                if (!is_null($data)) {
                    $dataLength = DB::table('ViewLoteDetalleDqo')->where('Id_lote', $id_lote)->count();

                    $textProcedimiento = ReportesFq::where('Id_reporte', 26)->first();
                    $separador = "Valoración";
                    $textoProcedimiento = explode($separador, $textProcedimiento->Texto);

                    $htmlCaptura = view('exports.laboratorio.fq.volumetria.dqoB.capturaBody', compact('textoProcedimiento', 'data', 'dataLength'));
                } else {
                    $sw = false;
                    $mpdf->SetJS('No se han llenado datos en el reporte. Verifica que todos los datos estén ingresados.");');
                }
            } else if ($parametro->Id_parametro == 170) { // DQO SOLUBLE
                $data = DB::table('ViewLoteDetalleDqo')->where('Id_lote', $id_lote)->get();

                if (!is_null($data)) {
                    $dataLength = DB::table('ViewLoteDetalleDqo')->where('Id_lote', $id_lote)->count();

                    $textProcedimiento = ReportesFq::where('Id_reporte', 29)->first();
                    $separador = "Valoración";
                    $textoProcedimiento = explode($separador, $textProcedimiento->Texto);

                    $htmlCaptura = view('exports.laboratorio.fq.volumetria.dqoSoluble.capturaBody', compact('textoProcedimiento', 'data', 'dataLength'));
                } else {
                    $sw = false;
                    $mpdf->SetJS('No se han llenado datos en el reporte. Verifica que todos los datos estén ingresados.");');
                }
            } else if ($parametro->Id_parametro == 168) { // DQO SOLUBLE ALTA
                $data = DB::table('ViewLoteDetalleDqo')->where('Id_lote', $id_lote)->get();

                if (!is_null($data)) {
                    $dataLength = DB::table('ViewLoteDetalleDqo')->where('Id_lote', $id_lote)->count();

                    $textProcedimiento = ReportesFq::where('Id_reporte', 25)->first();
                    $separador = "Valoración";
                    $textoProcedimiento = explode($separador, $textProcedimiento->Texto);

                    $htmlCaptura = view('exports.laboratorio.fq.volumetria.dqoSolubleAlta.capturaBody', compact('textoProcedimiento', 'data', 'dataLength'));
                } else {
                    $sw = false;
                    $mpdf->SetJS('No se han llenado datos en el reporte. Verifica que todos los datos estén ingresados.");');
                }
            } else if ($parametro->Id_parametro == 169) { // DQO SOLUBLE BAJA
                $data = DB::table('ViewLoteDetalleDqo')->where('Id_lote', $id_lote)->get();

                if (!is_null($data)) {
                    $dataLength = DB::table('ViewLoteDetalleDqo')->where('Id_lote', $id_lote)->count();

                    $textProcedimiento = ReportesFq::where('Id_reporte', 26)->first();
                    $separador = "Valoración";
                    $textoProcedimiento = explode($separador, $textProcedimiento->Texto);

                    $htmlCaptura = view('exports.laboratorio.fq.volumetria.dqoSolubleBaja.capturaBody', compact('textoProcedimiento', 'data', 'dataLength'));
                } else {
                    $sw = false;
                    $mpdf->SetJS('No se han llenado datos en el reporte. Verifica que todos los datos estén ingresados.");');
                }
            } else if ($parametro->Id_parametro == 12) { // NITROGENO TOTAL
                $data = DB::table('ViewLoteDetalleNitrogeno')->where('Id_lote', $id_lote)->get();

                if (!is_null($data)) {
                    $dataLength = DB::table('ViewLoteDetalleNitrogeno')->where('Id_lote', $id_lote)->count();

                    $textProcedimiento = ReportesFq::where('Id_reporte', 30)->first();
                    $separador = "Valoración";
                    $textoProcedimiento = explode($separador, $textProcedimiento->Texto);

                    $htmlCaptura = view('exports.laboratorio.fq.volumetria.nitrogenoTotal.capturaBody', compact('textoProcedimiento', 'data', 'dataLength'));
                } else {
                    $sw = false;
                    $mpdf->SetJS('No se han llenado datos en el reporte. Verifica que todos los datos estén ingresados.");');
                }
            } else if ($parametro->Id_parametro == 10 || $parametro->Id_parametro == 117 || $parametro->Id_parametro == 296) { // NITROGENO AMONIACAL
                $data = DB::table('ViewLoteDetalleNitrogeno')->where('Id_lote', $id_lote)->get();

                if (!is_null($data)) {
                    $dataLength = DB::table('ViewLoteDetalleNitrogeno')->where('Id_lote', $id_lote)->count();

                    $textoProcedimiento = ReportesFq::where('Id_reporte', 27)->first();

                    $htmlCaptura = view('exports.laboratorio.fq.volumetria.nitrogenoA.capturaBody', compact('textoProcedimiento', 'data', 'dataLength'));
                } else {
                    $sw = false;
                    $mpdf->SetJS('No se han llenado datos en el reporte. Verifica que todos los datos estén ingresados.");');
                }
            } else if ($parametro->Id_parametro == 11) { // NITROGENO ORGANICO
                $data = DB::table('ViewLoteDetalleNitrogeno')->where('Id_lote', $id_lote)->get();

                if (!is_null($data)) {
                    $dataLength = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $id_lote)->count();

                    $textoProcedimiento = ReportesFq::where('Id_reporte', 28)->first();

                    $htmlCaptura = view('exports.laboratorio.fq.volumetria.nitrogenoO.capturaBody', compact('textoProcedimiento', 'data', 'dataLength', 'curva'));
                } else {
                    $sw = false;
                    $mpdf->SetJS('No se han llenado datos en el reporte. Verifica que todos los datos estén ingresados.");');
                }
            } else if ($parametro->Id_parametro == 34 || $parametro->Id_parametro == 128 || $parametro->Id_parametro == 295) { // CLORO RESIDUAL LIBRE
                $data = DB::table('ViewLoteDetalleCloro')->where('Id_lote', $id_lote)->get();

                if (!is_null($data)) {
                    $dataLength = DB::table('ViewLoteDetalleCloro')->where('Id_lote', $id_lote)->count();

                    $textProcedimiento = ReportesFq::where('Id_reporte', 24)->first();
                    $separador = "Valoración";
                    $textoProcedimiento = explode($separador, $textProcedimiento->Texto);

                    $htmlCaptura = view('exports.laboratorio.fq.volumetria.cloroR.capturaBody', compact('textoProcedimiento', 'data', 'dataLength'));
                } else {
                    $sw = false;
                    $mpdf->SetJS('No se han llenado datos en el reporte. Verifica que todos los datos estén ingresados.");');
                }
            }
        }

        //HEADER-FOOTER******************************************************************************************************************         
        if ($sw === true) {
            if ($parametro->Id_parametro == 7 || $parametro->Id_parametro == 77) { // DQO
                $htmlHeader = view('exports.laboratorio.fq.volumetria.dqo.capturaHeader', compact('fechaConFormato'));
                $htmlFooter = view('exports.laboratorio.fq.volumetria.dqo.capturaFooter', compact('usuario', 'firma'));
            } else if ($parametro->Id_parametro == 73 || $parametro->Id_parametro == 75) { // DQO ALTA Y DQO SOLUBLE ALTA
                $htmlHeader = view('exports.laboratorio.fq.volumetria.dqoA.capturaHeader', compact('fechaConFormato'));
                $htmlFooter = view('exports.laboratorio.fq.volumetria.dqoA.capturaFooter', compact('usuario', 'firma'));
            } else if ($parametro->Id_parametro == 74 || $parametro->Id_parametro == 76) { // DQO BAJA
                $htmlHeader = view('exports.laboratorio.fq.volumetria.dqoB.capturaHeader', compact('fechaConFormato'));
                $htmlFooter = view('exports.laboratorio.fq.volumetria.dqoB.capturaFooter', compact('usuario', 'firma'));
            } else if ($parametro->Id_parametro == 170) { // DQO SOLUBLE 
                $htmlHeader = view('exports.laboratorio.fq.volumetria.dqoSoluble.capturaHeader', compact('fechaConFormato'));
                $htmlFooter = view('exports.laboratorio.fq.volumetria.dqoSoluble.capturaFooter', compact('usuario', 'firma'));
            } else if ($parametro->Id_parametro == 168) { //DQO SOLUBLE ALTA
                $htmlHeader = view('exports.laboratorio.fq.volumetria.dqoSolubleAlta.capturaHeader', compact('fechaConFormato'));
                $htmlFooter = view('exports.laboratorio.fq.volumetria.dqoSolubleAlta.capturaFooter', compact('usuario', 'firma'));
            } else if ($parametro->Id_parametro == 169) { // DQO SOLUBLE BAJA
                $htmlHeader = view('exports.laboratorio.fq.volumetria.dqoSolubleBaja.capturaHeader', compact('fechaConFormato'));
                $htmlFooter = view('exports.laboratorio.fq.volumetria.dqoSolubleBaja.capturaFooter', compact('usuario', 'firma'));
            } else if ($parametro->Id_parametro == 10 || $parametro->Id_parametro == 117 || $parametro->Id_parametro == 296) { // NITROGENO AMONIACAL
                $htmlHeader = view('exports.laboratorio.fq.volumetria.nitrogenoA.capturaHeader', compact('fechaConFormato'));
                $htmlFooter = view('exports.laboratorio.fq.volumetria.nitrogenoA.capturaFooter', compact('usuario', 'firma'));
            } else if ($parametro->Id_parametro == 11) { // NITROGENO ORGANICO
                $htmlHeader = view('exports.laboratorio.fq.volumetria.nitrogenoO.capturaHeader', compact('fechaConFormato'));
                $htmlFooter = view('exports.laboratorio.fq.volumetria.nitrogenoO.capturaFooter', compact('usuario', 'firma'));
            } else if ($parametro->Id_parametro == 34 || $parametro->Id_parametro == 128 || $parametro->Id_parametro == 295) { // CLORO RESIDUAL LIBRE 
                $htmlHeader = view('exports.laboratorio.fq.volumetria.cloroR.capturaHeader', compact('fechaConFormato'));
                $htmlFooter = view('exports.laboratorio.fq.volumetria.cloroR.capturaFooter', compact('usuario', 'firma'));
            } else if ($parametro->Id_parametro == 12) { // NITROGENO TOTAL
                $htmlHeader = view('exports.laboratorio.fq.volumetria.nitrogenoTotal.capturaHeader', compact('fechaConFormato'));
                $htmlFooter = view('exports.laboratorio.fq.volumetria.nitrogenoTotal.capturaFooter', compact('usuario', 'firma'));
            }
        }

        if ($sw === true) {
            $mpdf->setHeader("{PAGENO}<br><br>" . $htmlHeader);
            $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
            $mpdf->WriteHTML($htmlCaptura);

            //Hoja 2
            $hoja2 = false;

            if ($parametro->Id_parametro == 7 || $parametro->Id_parametro == 77) { // DQO
                //$mpdf->AddPage('', '', '', '', '', '', '', 35, 45, 6.5, '', '', '', '', '', -1, -1, -1, -1);

                $data = DB::table('ViewLoteDetalleDqo')->where('Id_lote', $id_lote)->get();

                if (!is_null($data)) {
                    $dataLength = DB::table('ViewLoteDetalleDqo')->where('Id_lote', $id_lote)->count();

                    if ($proced === true) {
                        $separador = "Valoración";
                        $textoProcedimiento = explode($separador, $textProcedimiento->Texto);
                    } else {
                        $textProcedimiento = ReportesFq::where('Id_reporte', 29)->first();
                        $separador = "Valoración";
                        $textoProcedimiento = explode($separador, $textProcedimiento->Texto);
                    }

                    $htmlCaptura1 = view('exports.laboratorio.fq.volumetria.dqo.capturaBody1', compact('textoProcedimiento', 'data', 'dataLength'));
                    $htmlHeader = view('exports.laboratorio.fq.volumetria.dqo.capturaHeader', compact('fechaConFormato'));
                    $htmlFooter = view('exports.laboratorio.fq.volumetria.dqo.capturaFooter', compact('usuario', 'firma'));
                }

                //$hoja2 = true;

            } else if ($parametro->Id_parametro == 73 || $parametro->Id_parametro == 75 || $parametro->Id_parametro == 168) { // DQO ALTA Y DQO SOLUBLE ALTA
                //$mpdf->AddPage('', '', '', '', '', '', '', 35, 45, 6.5, '', '', '', '', '', -1, -1, -1, -1);

                $data = DB::table('ViewLoteDetalleDqo')->where('Id_lote', $id_lote)->get();

                if (!is_null($data)) {
                    $dataLength = DB::table('ViewLoteDetalleDqo')->where('Id_lote', $id_lote)->count();

                    if ($proced === true) {
                        $separador = "Valoración";
                        $textoProcedimiento = explode($separador, $textProcedimiento->Texto);
                    } else {
                        $textProcedimiento = ReportesFq::where('Id_reporte', 25)->first();
                        $separador = "Valoración";
                        $textoProcedimiento = explode($separador, $textProcedimiento->Texto);
                    }

                    $htmlCaptura1 = view('exports.laboratorio.fq.volumetria.dqoA.capturaBody1', compact('textoProcedimiento', 'data', 'dataLength'));
                    $htmlHeader = view('exports.laboratorio.fq.volumetria.dqoA.capturaHeader', compact('fechaConFormato'));
                    $htmlFooter = view('exports.laboratorio.fq.volumetria.dqoA.capturaFooter', compact('usuario', 'firma'));
                }

                //$hoja2 = true;
            } else if ($parametro->Id_parametro == 74 || $parametro->Id_parametro == 76 || $parametro->Id_parametro == 169) { // DQO BAJA Y DQO SOLUBLE BAJA
                //$mpdf->AddPage('', '', '', '', '', '', '', 35, 45, 6.5, '', '', '', '', '', -1, -1, -1, -1);

                $data = DB::table('ViewLoteDetalleDqo')->where('Id_lote', $id_lote)->get();

                if (!is_null($data)) {
                    $dataLength = DB::table('ViewLoteDetalleDqo')->where('Id_lote', $id_lote)->count();

                    if ($proced === true) {
                        $separador = "Valoración";
                        $textoProcedimiento = explode($separador, $textProcedimiento->Texto);
                    } else {
                        $textProcedimiento = ReportesFq::where('Id_reporte', 26)->first();
                        $separador = "Valoración";
                        $textoProcedimiento = explode($separador, $textProcedimiento->Texto);
                    }

                    $htmlCaptura1 = view('exports.laboratorio.fq.volumetria.dqoB.capturaBody1', compact('textoProcedimiento', 'data', 'dataLength'));
                    $htmlHeader = view('exports.laboratorio.fq.volumetria.dqoB.capturaHeader', compact('fechaConFormato'));
                    $htmlFooter = view('exports.laboratorio.fq.volumetria.dqoB.capturaFooter', compact('usuario', 'firma'));
                }

                //$hoja2 = true;
            } else if ($parametro->Id_parametro == 12) { // NITROGENO TOTAL
                //$mpdf->AddPage('', '', '', '', '', '', '', 35, 45, 6.5, '', '', '', '', '', -1, -1, -1, -1);

                $data = DB::table('ViewLoteDetalleDqo')->where('Id_lote', $id_lote)->get();

                if (!is_null($data)) {
                    $dataLength = DB::table('ViewLoteDetalleDqo')->where('Id_lote', $id_lote)->count();

                    if ($proced === true) {
                        $separador = "Valoración";
                        $textoProcedimiento = explode($separador, $textProcedimiento->Texto);
                    } else {
                        $textProcedimiento = ReportesFq::where('Id_reporte', 30)->first();
                        $separador = "Valoración";
                        $textoProcedimiento = explode($separador, $textProcedimiento->Texto);
                    }

                    $htmlCaptura1 = view('exports.laboratorio.fq.volumetria.nitrogenoTotal.capturaBody1', compact('textoProcedimiento', 'data', 'dataLength'));
                    $htmlHeader = view('exports.laboratorio.fq.volumetria.nitrogenoTotal.capturaHeader', compact('fechaConFormato'));
                    $htmlFooter = view('exports.laboratorio.fq.volumetria.nitrogenoTotal.capturaFooter', compact('usuario', 'firma'));
                }

                //$hoja2 = true;
            } else if ($parametro->Id_parametro == 10 || $parametro->Id_parametro == 117 || $parametro->Id_parametro == 296) { // NITROGENO AMONIACAL
                //$mpdf->AddPage('', '', '', '', '', '', '', 35, 45, 6.5, '', '', '', '', '', -1, -1, -1, -1);

                $data = DB::table('ViewLoteDetalleDqo')->where('Id_lote', $id_lote)->get();

                if (!is_null($data)) {
                    $dataLength = DB::table('ViewLoteDetalleDqo')->where('Id_lote', $id_lote)->count();

                    if ($proced === true) {
                        $separador = "Valoración";
                        $textoProcedimiento = explode($separador, $textProcedimiento->Texto);
                    } else {
                        $textProcedimiento = ReportesFq::where('Id_reporte', 27)->first();
                        $separador = "Valoración";
                        $textoProcedimiento = explode($separador, $textProcedimiento->Texto);
                    }

                    $htmlCaptura1 = view('exports.laboratorio.fq.volumetria.nitrogenoA.capturaBody1', compact('textoProcedimiento', 'data', 'dataLength'));
                    $htmlHeader = view('exports.laboratorio.fq.volumetria.nitrogenoA.capturaHeader', compact('fechaConFormato'));
                    $htmlFooter = view('exports.laboratorio.fq.volumetria.nitrogenoA.capturaFooter', compact('usuario', 'firma'));
                }

                //$hoja2 = true;
            } else if ($parametro->Id_parametro == 11) { // NITROGENO ORGANICO
                //$mpdf->AddPage('', '', '', '', '', '', '', 35, 45, 6.5, '', '', '', '', '', -1, -1, -1, -1);

                $data = DB::table('ViewLoteDetalleDqo')->where('Id_lote', $id_lote)->get();

                if (!is_null($data)) {
                    $dataLength = DB::table('ViewLoteDetalleDqo')->where('Id_lote', $id_lote)->count();

                    if ($proced === true) {
                        $separador = "Valoración";
                        $textoProcedimiento = explode($separador, $textProcedimiento->Texto);
                    } else {
                        $textProcedimiento = ReportesFq::where('Id_reporte', 27)->first();
                        $separador = "Valoración";
                        $textoProcedimiento = explode($separador, $textProcedimiento->Texto);
                    }

                    $htmlCaptura1 = view('exports.laboratorio.fq.volumetria.nitrogenoO.capturaBody1', compact('textoProcedimiento', 'data', 'dataLength'));
                    $htmlHeader = view('exports.laboratorio.fq.volumetria.nitrogenoO.capturaHeader', compact('fechaConFormato'));
                    $htmlFooter = view('exports.laboratorio.fq.volumetria.nitrogenoO.capturaFooter', compact('usuario', 'firma'));
                }

                //$hoja2 = true;
            }

            /* if($hoja2 === true){
                $mpdf->SetHTMLHeader('{PAGENO}<br><br>' . $htmlHeader, 'O', 'E');
                $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                $mpdf->WriteHTML($htmlCaptura1);
            } */
        }

        if ($sw === true) {
            $mpdf->CSSselectMedia = 'mpdf';
            $mpdf->Output();
        }
    }
}
