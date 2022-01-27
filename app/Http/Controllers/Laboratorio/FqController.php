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
use App\Models\LoteDetalleEspectro;
use App\Models\LoteDetalleGA;
use App\Models\LoteDetalleSolidos;
use App\Models\LoteTecnica;
use App\Models\Reportes;
use App\Models\SecadoCartucho;
use App\Models\Tecnica;
use App\Models\TiempoReflujo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FqController extends Controller
{
    //
    public function analisis()
    {
        $model = DB::table('proceso_analisis')->get();

        //Devuelve el tamaño del arreglo model
        $elements = DB::table('proceso_analisis')->count();

        //Para buscar el punto de muestreo
        $puntoMuestreo = DB::table('cotizacion_puntos')->get();
        $puntoMuestreoLength = DB::table('cotizacion_puntos')->count();
        $solicitudPuntos = DB::table('solicitud_puntos')->get();
        $solicitudPuntosLength = DB::table('solicitud_puntos')->count();

        //Para select Filtro de vista análisis
        $tecnicas = DB::table('tecnicas')->get();

        //Para buscar la Norma de la solicitud
        $solicitud = DB::table('ViewSolicitud')->get();
        $solicitudLength = DB::table('ViewSolicitud')->count();

        //Para buscar los parámetros de la solicitud
        $parametros = DB::table('parametros')->get();
        $parametrosLength = DB::table('parametros')->count();

        return view('laboratorio.metales.analisis', compact('model', 'elements', 'solicitud', 'solicitudLength', 'tecnicas', 'solicitudPuntos', 'solicitudPuntosLength', 'parametros', 'parametrosLength', 'puntoMuestreo', 'puntoMuestreoLength'));
    }
    public function observacion()
    {
        $formulas = DB::table('tipo_formulas')
        ->orWhere('Id_tipo_formula', 8) 
        ->orWhere('Id_tipo_formula',9)
        ->get();
        return view('laboratorio.fq.observacion', compact('formulas'));
    }

    public function getObservacionanalisis(Request $request)
    {
        // todo - Area analisis = id 5
        $solicitudModel = DB::table('ViewSolicitud')->get();
        $sw = false;
        foreach($solicitudModel as $item)
        {
            $paramModel = DB::table('ViewSolicitudParametros')->where('Id_solicitud',$item->Id_solicitud)->where('Id_tipo_formula',$request->id)->get();
            $sw = false;
            foreach($paramModel as $item2)
            {
                $areaModel = DB::table('ViewTipoFormulaAreas')->where('Id_formula',$item2->Id_tipo_formula)->where('Id_area',5)->get();
                if($areaModel->count())
                {
                    $sw = true;
                }
            }
            if($sw == true)
            {
                // $model = DB::table('ViewObservacionMuestra')->where('Id_area',5)->where('Id_analisis',$item->Id_solicitud)->get();
                $model = ObservacionMuestra::where('Id_analisis',$item->Id_solicitud)->where('Id_area',5)->get();
                if($model->count()){
                }else{
                    ObservacionMuestra::create([
                        'Id_analisis' => $item->Id_solicitud,
                        'Id_area' => 5,
                        'Ph' => '',
                        'Solido' => '',
                        'Olor' => '',
                        'Color' => '',
                        'Observacion' => '',     
                    ]);
                }
                $sw = false;
            }
        }
        $model = DB::table('ViewObservacionMuestra')->where('Id_area',5)->get();

        $data = array(
            'model' => $model,
        );

        return response()->json($data);
    }  


    public function aplicarObservacion(Request $request)
    {
        $viewObservacion = DB::table('ViewObservacionMuestra')->where('Id_area',5)->where('Folio','LIKE',"%{$request->folioActual}%")->first();


        $observacion = ObservacionMuestra::find($viewObservacion->Id_observacion);
        $observacion->Ph = $request->ph;
        $observacion->Solido = $request->solidos;
        $observacion->Olor = $request->olor;
        $observacion->Color = $request->color;
        $observacion->Observaciones = $request->observacionGeneral;
        $observacion->save();


        $model = DB::table('ViewObservacionMuestra')->where('Id_area',5)->get();

        $data = array(
            'model' => $model, 
            'view' => $viewObservacion, 
        );
        return response()->json($data);
    } 

    //*****************************************CAPTURA****************************************************************** */
    public function tipoAnalisis()
    {
        return view('laboratorio.fq.tipoAnalisis');  
    }
    //***********espectrofotometrico ***************/
    public function capturaEspectro()
    {

        $parametro = Parametro::where('Id_area', 5)->get();
        // $formulas = DB::table('ViewTipoFormula')->where('Id_area',2)->get();
        // var_dump($parametro); 
        return view('laboratorio.fq.capturaEspectro', compact('parametro')); 
    }
    public function guardarEspectro(Request $request)
    {
        //$lote = DB::table('ViewLoteAnalisis')->where('Fecha', $request->fechaAnalisis)->get();
        $model = LoteDetalleEspectro::find($request->idMuestra);
        $model->Resultado = $request->resultado;  
        $model->Abs1 = $request->X;
        $model->Abs2 = $request->Y;
        $model->Abs3 = $request->Z;
        $model->Promedio = $request->ABS;
        $model->Vol_dilucion = $request->D;
        $model->Vol_muestra = $request->E;
        $model->Blanco = $request->CA;
        $model->save();

        $data = array(
            'model' => $model,
            
        );
        return response()->json($data);

    } 
    public function operacionEspectro(Request $request)
    {
        $volumen = VolumenParametros::where('Id_parametro', $request->parametro)->first();
        
        
        
        switch ($request->parametro) {
            case 96: 
                //code

                break;
            case 70:
                # Cromo Hexavalente
                $x = ($request->X + $request->Y+ $request->Z) / 3;
                $r1 = ($x-$request->CB)/$request->CM;
                $r2 = 100/$request->E;
                $resultado = $r1 * $r2; 

                $data = array( 
                    'x' => $x,
                    'resultado' => $resultado,
                ); 
                return response()->json($data); 

                break;
            case 20:
                 # Cianuros
                 $x = ($request->X + $request->Y+ $request->Z) / 3;
                 $r1 = ($x-$request->CB)/$request->CM;
                 $resultado = ($r1 * 12500) / (500 * $request->E);
                 
                 $data = array( 
                    'x' => $x,
                    'resultado' => $resultado,
                ); 
                return response()->json($data); 
                break;
            case 97:
                # Sustancias activas al Azul de Metileno
                $x = ($request->X + $request->Y+ $request->Z) / 3;
                $r1 = ($x-$request->CB)/$request->CM;
                $r2 = 1000/$request->E;
                $resultado = $r1 * $r2; 

                $data = array( 
                    'x' => $x,
                    'resultado' => $resultado,
                ); 
                return response()->json($data);   
                break;
            
            default:
                # code...
                $x = ($request->X + $request->Y+ $request->Z) / 3;
                $d =   $volumen->Volumen  / $request->E; 
                $resultado = (($x-$request->CB)/$request->CM) * $d; 

                $data = array( 
                    'x' => $x,
                    'resultado' => $resultado,
                    'd' => $d,
                    
                ); 
                return response()->json($data); 
                
                break;
        }
        

    }

    public function getDataCapturaEspectro(Request $request)
    {
        //$parametro = Parametro::where('Id_parametro',$request->formulaTipo)->first();
        $lote = DB::table('ViewLoteAnalisis')->where('Fecha', $request->fechaAnalisis)->get();
        $idLote = 0;
        foreach($lote as $item)
        { 
            $detModel = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $item->Id_lote)->first();
            if($detModel->Id_parametro == $request->formulaTipo)
            { 
                $idLote = $detModel->Id_lote;
            } 
        }

        // $detalleModel = DB::tables'ViewLoteDetalle')->where('Id_lote', $lote->Id_lote)->get();
        $detalle = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $idLote)->get();
        $loteModel = DB::table('ViewLoteAnalisis')->where('Id_lote', $idLote)->first();
        $curvaConst = CurvaConstantes::where('Id_lote',$idLote)->first();
        $data = array( 
            'idL' => $idLote,
            'de' => $detModel,
            'lote' => $loteModel,
            'curvaConst' => $curvaConst,
            'detalle' => $detalle,
        ); 
        return response()->json($data); 
    }
    public function getDetalleEspectro(Request $request)
    {
        $model = DB::table("ViewLoteDetalleEspectro")->where('Id_detalle',$request->idDetalle)->first();
        $curva = CurvaConstantes::where('Id_lote',$model->Id_lote)->first();

        $data = array(
            'model' => $model,
            'curva' => $curva,
        );
        return response()->json($data);
    }

    //NUEVA FUNCIÓN BUSQUEDA FILTROS > CAPTURA.JS
    public function busquedaFiltros(Request $request)
    {
        //REALIZARÁ CONSULTA A LA BASE DE DATOS VIEWLOTEDETALLE PARA RECUPERAR LOS DATOS

        //*************************************
        $idLote = $request->id_Lote;
 
        $consultas = [
            'Id_lote' => $idLote
        ];
        //*************************************

        //ASIGNACIONES DE PRUEBA
        //$tipoFormula = $request->formulaTipo;
        //$numeroMuestra = $request->numMuestra;
        //$analisisFecha = $request->fechaAnalisis;

        /*$consultas = [
            'Folio_servicio' => $numeroMuestra,
            'Parametro' => $tipoFormula
        ];*/

        $loteDetail = DB::table('ViewLoteDetalle')->where($consultas)->first();

        return response()->json(
            compact('loteDetail')
        );
    }

    public function setControlCalidad(Request $request)
    {
        //! Blanco reactivo
        $loteModel = LoteDetalle::where('Id_detalle', $request->numMuestra[$request->ranCon[0]])->first();
        LoteDetalle::create([
            'Id_lote' => $loteModel->Id_lote,
            'Id_analisis' => $loteModel->Id_analisis,
            'Id_parametro' => $loteModel->Id_parametro,
            'Descripcion' => "Blanco reactivo",
            'Vol_muestra' => 50,
            'Abs1' => 0,
            'Abs2' => 0,
            'Abs3' => 0,
            'Abs_promedio' => 0,
            'Factor_dilucion' => 1,
            'Factor_conversion' => 0,
            'Vol_disolucion' => 0,
            'Liberado' => 0,
        ]);
        //! Estandar de verificacion
        $loteModel = LoteDetalle::where('Id_detalle', $request->numMuestra[$request->ranCon[0]])->first();
        LoteDetalle::create([
            'Id_lote' => $loteModel->Id_lote,
            'Id_analisis' => $loteModel->Id_analisis,
            'Id_parametro' => $loteModel->Id_parametro,
            'Descripcion' => "Estandar de verificacion",
            'Vol_muestra' => 50,
            'Abs1' => 0,
            'Abs2' => 0,
            'Abs3' => 0,
            'Abs_promedio' => 0,
            'Factor_dilucion' => 1,
            'Factor_conversion' => 0,
            'Vol_disolucion' => 0,
            'Liberado' => 0,
        ]);
        //! Muestra duplicada
        $loteModel = LoteDetalle::where('Id_detalle', $request->numMuestra[$request->ranCon[0]])->first();
        LoteDetalle::create([
            'Id_lote' => $loteModel->Id_lote,
            'Id_analisis' => $loteModel->Id_analisis,
            'Id_parametro' => $loteModel->Id_parametro,
            'Descripcion' => "Muestra duplicada",
            'Vol_muestra' => 50,
            'Abs1' => 0,
            'Abs2' => 0,
            'Abs3' => 0,
            'Abs_promedio' => 0,
            'Factor_dilucion' => 1,
            'Factor_conversion' => 0,
            'Vol_disolucion' => 0,
            'Liberado' => 0,
        ]);
        //! Muestra adicionada
        $loteModel = LoteDetalle::where('Id_detalle', $request->numMuestra[$request->ranCon[0]])->first();
        LoteDetalle::create([
            'Id_lote' => $loteModel->Id_lote,
            'Id_analisis' => $loteModel->Id_analisis,
            'Id_parametro' => $loteModel->Id_parametro,
            'Descripcion' => "Muestra adicionada",
            'Vol_muestra' => 50,
            'Abs1' => 0,
            'Abs2' => 0,
            'Abs3' => 0,
            'Abs_promedio' => 0,
            'Factor_dilucion' => 1,
            'Factor_conversion' => 0,
            'Vol_disolucion' => 0,
            'Liberado' => 0,
        ]);
        //! Estandar
        $loteModel = LoteDetalle::where('Id_detalle', $request->numMuestra[$request->ranCon[0]])->first();
        LoteDetalle::create([
            'Id_lote' => $loteModel->Id_lote,
            'Id_analisis' => $loteModel->Id_analisis,
            'Id_parametro' => $loteModel->Id_parametro,
            'Descripcion' => "Estandar",
            'Vol_muestra' => 50,
            'Abs1' => 0,
            'Abs2' => 0,
            'Abs3' => 0,
            'Abs_promedio' => 0,
            'Factor_dilucion' => 1,
            'Factor_conversion' => 0,
            'Vol_disolucion' => 0,
            'Liberado' => 0,
        ]);

        $detalleModel = LoteDetalle::where('Id_lote',$loteModel->Id_lote)->get();
        $lote = LoteAnalisis::find($loteModel->Id_lote);
        $lote->Asignado = $detalleModel->count();
        $lote->save();

        $detalle = DB::table('ViewLoteDetalle')->where('Id_lote', $loteModel->Id_lote)->get();


        $data = array(
            'detalle' => $detalle
        );
        return response()->json($data);
    }
    public function operacion(Request $request)
    {

        $detalleModel = LoteDetalle::where('Id_detalle',$request->idDetalle)->first();
        $parametroModel = Parametro::where('Id_matriz',12)->where('Id_parametro',$detalleModel->Id_parametro)->get();
        $curvaConstantes = CurvaConstantes::where('Id_lote', $request->idlote)->first();
        //$parametroPurificada = Parametro::where('Id_matriz',9)->where('Id_parametro',$detalleModel->Id_parametro)->get();

        $curva = CurvaConstantes::where('Id_lote', $request->idlote)->first();
        $x = $request->x;
        $y = $request->y;
        $z = $request->z;
        $FD = $request->FD;
        $suma = ($x + $y + $z);
        $promedio = $suma / 3;

       //if($parametroPurificada->count()){    //todo:: Verificar filtro con la norma!!!
            $paso1 = (($promedio - $curvaConstantes->B) /$curvaConstantes->M ) * $FD;
            $resultado = ($paso1 * 1)/1000;
       // }else{

        //if($parametroModel->count())
        //{
            //if($detalleModel->Descripcion != "Resultado"){
                $resultado = (($promedio - $curvaConstantes->B) /$curvaConstantes->M ) * $FD;
        //     }else{
        //         $resultado = ((($promedio - $curvaConstantes->B) /$curvaConstantes->M ) * $FD) / 1000;
        //     }
        // }else{
        //     $resultado = (($promedio - $curvaConstantes->B) /$curvaConstantes->M ) * $FD;
        // }

        // }

        $detalle = LoteDetalle::find($request->idDetalle);
        $detalle->Vol_muestra = $request->volMuestra;
        $detalle->Abs1 = $request->x;
        $detalle->Abs2 = $request->y;
        $detalle->Abs3 = $request->z;
        $detalle->Abs_promedio = $promedio;
        $detalle->Factor_dilucion = $request->FD;
        $detalle->Factor_conversion = 0;
        $detalle->Vol_disolucion = $resultado;
        $detalle->save();

        $data = array(
            'idDeta' => $request->idDetalle,
            'curva' => $curva,
            'promedio' => $promedio,
            'resultado' => $resultado
        );

        return response()->json($data);
    }

    // todo ******************* Inicio de lote ************************
    public function lote()
    {
        //* Tipo de formulas  
        $formulas = DB::table('tipo_formulas')
        ->orWhere('Id_tipo_formula', 7)
        ->orWhere('Id_tipo_formula', 8)
        ->orWhere('Id_tipo_formula',9)
        ->get();
        $tecnica = Tecnica::all(); 
        $textoRecuperadoPredeterminado = ReportesFq::where('Id_reporte', 0)->first();
        return view('laboratorio.fq.lote', compact('formulas', 'textoRecuperadoPredeterminado','tecnica'));
    }

    public function createLote(Request $request)   
    {
        $model = LoteAnalisis::create([
            'Id_tipo' => $request->tipo,
            'Id_area' => 5,
            'Id_tecnica' => $request->tecnica,
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
        //$model = LoteAnalisis::where('Id_tipo',$request->tipo)->where('Fecha',$request->fecha)->get();
        $model = DB::table('ViewLoteAnalisis')->where('Id_tipo', $request->tipo)->where('Id_area',5)->where('Fecha', $request->fecha)->get();
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }

    //RECUPERAR DATOS PARA ENVIARLOS A LA VENTANA MODAL > EQUIPO PARA RELLENAR LOS DATOS ALMACENADOS EN LA BD
    public function getDatalote(Request $request)
    {        
        $idLoteIf = $request->idLote;

        //RECUPERA LA PLANTILLA DEL REPORTE    
        $reporte = ReportesFq::where('Id_lote',$request->idLote)->first();
        
        //RECUPERA EL APARTADO DE FÓRMULAS GLOBALES;
        $constantesModel = CurvaConstantes::where('Id_lote', $request->idLote)->get();
        if($constantesModel->count()){
            $constantes = CurvaConstantes::where('Id_lote', $request->idLote)->first();            
        }else{
            $constantes = null;
        }

        /*Módulo de Grasas*/
        $dataGrasas = array();

        $calentamientoFq = DB::table('calentamiento_matraces')->where('Id_lote', $request->idLote)->get();
        $enfriadoFq = DB::table('enfriado_matraces')->where('Id_lote', $request->idLote)->get();
        $secadoFq = DB::table('secado_cartuchos')->where('Id_lote', $request->idLote)->get();
        $tiempoFq = DB::table('tiempo_reflujo')->where('Id_lote', $request->idLote)->get();
        $enfriadoMatrazFq = DB::table('enfriado_matraz')->where('Id_lote', $request->idLote)->get();

        if($calentamientoFq->count()){            
            array_push($dataGrasas, $calentamientoFq);
        }else{
            array_push($dataGrasas, null);
        }

        if($enfriadoFq->count()){            
            array_push($dataGrasas, $enfriadoFq);
        }else{
            array_push($dataGrasas, null);
        }

        if($secadoFq->count()){
            $secadoCartucho = SecadoCartucho::where('Id_lote', $request->idLote)->first();
            array_push($dataGrasas, $secadoCartucho);
        }else{
            array_push($dataGrasas, null);
        }

        if($tiempoFq->count()){
            $tiempoReflujo = TiempoReflujo::where('Id_lote', $request->idLote)->first();
            array_push($dataGrasas, $tiempoReflujo);
        }else{
            array_push($dataGrasas, null);
        }

        if($enfriadoMatrazFq->count()){
            $enfriadoMatraz = EnfriadoMatraz::where('Id_lote', $request->idLote)->first();
            array_push($dataGrasas, $enfriadoMatraz);
        }else{
            array_push($dataGrasas, null);
        }


        /* Módulo de coliformes */
        $dataColi = array();

        $sembradoFq = DB::table('sembrado_fq')->where('Id_lote', $request->idLote)->get();
        $pruebaPresuntivaFq = DB::table('prueba_presuntiva_fq')->where('Id_lote', $request->idLote)->get();
        $pruebaConfirmativaFq = DB::table('prueba_confirmativa_fq')->where('Id_lote', $request->idLote)->get();

        if($sembradoFq->count() && $pruebaPresuntivaFq->count() && $pruebaConfirmativaFq->count()){
            $sembradoFq = SembradoFq::where('Id_lote', $request->idLote)->first(); //Array 0
            $pruebaPresunFq = PruebaPresuntivaFq::where('Id_lote', $request->idLote)->first(); //Array 1
            $pruebaConfirFq = PruebaConfirmativaFq::where('Id_lote', $request->idLote)->first(); //Array 2

            array_push(
                $dataColi,
                $sembradoFq,
                $pruebaPresunFq,
                $pruebaConfirFq
            );

        }else{
            array_push(
                $dataColi, null, null, null
            );
        }
        //-------------------------------------

        /* Módulo DQO */                
        $dqoModel = DB::table('dqo_fq')->where('Id_lote', $request->idLote)->get();

        if($dqoModel->count()){
            $dqo = DqoFq::where('Id_lote', $request->idLote)->first();
        }else{
            $dqo = null;
        }


        //-------------------------------------
        $data = array(
            'curvaConstantes' => $constantes,
            'idLoteIf' => $idLoteIf,
            'reporte' => $reporte,
            'dataGrasas' => $dataGrasas,
            'dataColi' => $dataColi,
            'dataDqo' => $dqo
        );

        return response()->json($data);
    }

    public function getPlantillaPred(Request $request){
        $bandera = '';

        //Obtiene el parámetro que se está consultando
        $parametro = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $request->idLote)->first();

        if(is_null($parametro)){
            $parametro = DB::table('ViewLoteDetalleGA')->where('Id_lote', $request->idLote)->first();

            if(!is_null($parametro)){
                $bandera = 'ga';
            }            
        }else{
            $bandera = 'espectro';
        }
        
        if($bandera === 'espectro'){
            if($parametro->Parametro == 'N-Nitritos'){
                $plantillaPredeterminada = ReportesFq::where('Id_reporte', 1)->first();
            }else if($parametro->Parametro == 'N-Nitratos'){
                $plantillaPredeterminada = ReportesFq::where('Id_reporte', 2)->first();
            }else if($parametro->Parametro == 'BORO (B)'){
                $plantillaPredeterminada = ReportesFq::where('Id_reporte', 3)->first();
            }else if($parametro->Parametro == 'Cianuros (CN)-'){
                $plantillaPredeterminada = ReportesFq::where('Id_reporte', 4)->first();
            }else if($parametro->Parametro == 'Conductividad'){ //POR VERIFICAR EN LA TABLA DE PARAMETROS
                $plantillaPredeterminada = ReportesFq::where('Id_reporte', 5)->first();
            }else if($parametro->Parametro == 'CROMO HEXAVALENTE (Cr+6)'){
                $plantillaPredeterminada = ReportesFq::where('Id_reporte', 6)->first();
            }else if($parametro->Parametro == 'Fosforo-Total'){
                $plantillaPredeterminada = ReportesFq::where('Id_reporte', 7)->first();
            }else if($parametro->Parametro == 'Materia Flotante'){ //POR VERIFICAR EN LA TABLA DE PARAMETROS
                $plantillaPredeterminada = ReportesFq::where('Id_reporte', 8)->first();
            }else if($parametro->Parametro == 'SILICE (SiO₂)'){
                $plantillaPredeterminada = ReportesFq::where('Id_reporte', 9)->first();
            }else if($parametro->Parametro == 'FENOLES TOTALES'){
                $plantillaPredeterminada = ReportesFq::where('Id_reporte', 10)->first();
            }else if($parametro->Parametro == 'FLUORUROS (F¯)'){
                $plantillaPredeterminada = ReportesFq::where('Id_reporte', 11)->first();
            }else if($parametro->Parametro == 'SUSTANCIAS ACTIVAS AL AZUL DE METILENO (SAAM )'){
                $plantillaPredeterminada = ReportesFq::where('Id_reporte', 12)->first();
            }else if($parametro->Parametro == 'SULFATOS (SO4˭)'){
                $plantillaPredeterminada = ReportesFq::where('Id_reporte', 13)->first();
            }else{
                $plantillaPredeterminada = ReportesFq::where('Id_reporte', 0)->first();
            } 
        }else if($bandera === 'ga'){
            if($parametro->Parametro == 'Grasas y Aceites ++'){
                $plantillaPredeterminada = ReportesFq::where('Id_reporte', 0)->first();                
            }else if($parametro->Parametro == 'SOLIDOS DISUELTOS FIJOS (SDF)'){
                $plantillaPredeterminada = ReportesFq::where('Id_reporte', 14)->first();                
            }else if($parametro->Parametro == 'SOLIDOS DISUELTOS TOTALES (SDT)'){
                $plantillaPredeterminada = ReportesFq::where('Id_reporte', 15)->first();                
            }else if($parametro->Parametro == 'SOLIDOS DISUELTOS VOLÁTILES (SDV)'){
                $plantillaPredeterminada = ReportesFq::where('Id_reporte', 16)->first();                
            }else if($parametro->Parametro == 'SOLIDOS SEDIMENTABLES (S.S)'){
                $plantillaPredeterminada = ReportesFq::where('Id_reporte', 17)->first();                
            }else if($parametro->Parametro == 'SOLIDOS SUSPENDIDOS FIJOS (SSF)'){
                $plantillaPredeterminada = ReportesFq::where('Id_reporte', 18)->first();                
            }else if($parametro->Parametro == 'SOLIDOS SUSPENDIDOS TOTALES (SST)'){
                $plantillaPredeterminada = ReportesFq::where('Id_reporte', 19)->first();                
            }else if($parametro->Parametro == 'SOLIDOS SUSPENDIDOS VOLÁTILES (SSV)'){
                $plantillaPredeterminada = ReportesFq::where('Id_reporte', 20)->first();                
            }else if($parametro->Parametro == 'SOLIDOS TOTALES (ST)'){
                $plantillaPredeterminada = ReportesFq::where('Id_reporte', 21)->first();                
            }else if($parametro->Parametro == 'SOLIDOS TOTALES FIJOS (STF)'){
                $plantillaPredeterminada = ReportesFq::where('Id_reporte', 22)->first();                
            }else if($parametro->Parametro == 'SOLIDOS TOTALES VOLATILES (STV)'){
                $plantillaPredeterminada = ReportesFq::where('Id_reporte', 23)->first();                
            }
        }           
        
        if(!is_null($plantillaPredeterminada)){
            return response()->json($plantillaPredeterminada);
        }        
    }

    public function asignar()
    {
        $tipoFormula = TipoFormula::all();
        return view('laboratorio.metales.asignar', compact('tipoFormula'));
    }
    public function asgnarMuestraLote($id)
    {
        $lote = LoteDetalle::where('Id_lote', $id)->get();
        $idLote = $id;
        return view('laboratorio.fq.asignarMuestraLote', compact('lote', 'idLote'));
    }
    //* Muestra los parametros sin asignar a lote
    public function muestraSinAsignar(Request $request)
    {
        $model = DB::table('ViewSolicitudParametros')
        ->orWhere('Id_tipo_formula',7)
        ->orWhere('Id_tipo_formula',8)
        ->orWhere('Id_tipo_formula',9)
        ->where('Asignado', '!=', 1)
        ->get();
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    //* Muestra asigada a lote
    public function getMuestraAsignada(Request $request)
    {
        $loteModel = LoteAnalisis::where('Id_lote',$request->idLote)->first();
        switch ($loteModel->Id_tecnica) {
            case 9: //todo Espectrofotometria
                $model = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $request->idLote)->get(); 
                break;
            case 10: //todo Gravimetia
                switch ($request->idParametro) {
                    case 14:
                        $model = LoteDetalleGA::where('Id_lote',$request->idLote)->get();
                        break;
                    
                    default:
                        # code...
                        break;
                }
                break;
            case 15: //todo Volumetria
                        # code...
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
    public function delMuestraLote(Request $request)
    {
        $loteModel = LoteAnalisis::where('Id_lote',$request->idLote)->first();
        switch ($loteModel->Id_tecnica) {
            case 9: //todo Espectrofotometria
                $detModel = DB::table('lote_detalle_espectro')->where('Id_detalle',$request->idDetalle)->delete();
                $detModel = LoteDetalleEspectro::where('Id_lote',$request->idLote)->get();
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

 
        $solModel = SolicitudParametro::where('Id_solicitud',$request->idSol)->where('Id_subnorma',$request->idParametro)->first();
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

        $detalleModel = LoteDetalle::where('Id_lote',$detalle->Id_lote)->where('Liberado',1)->get();
 
        $lote = LoteAnalisis::find($detalle->Id_lote);
        $lote->Liberado = $detalleModel->count();
        $lote->save();

        $detalleModel = DB::table('ViewLoteDetalle')->where('Id_lote',$detalle->Id_lote)->get();

        $loteModel = LoteAnalisis::where('Id_lote',$detalle->Id_lote)->first();


        $data = array(
            'detalleModel' => $detalleModel,
            'liberado' => $detalleModel->count(),
            'lote' => $loteModel,
        );
        return response()->json($data);
    }
    //* Asignar parametro a lote
    public function asignarMuestraLote(Request $request)
    {
        $sw = false; 
        $loteModel = LoteAnalisis::where('Id_lote',$request->idLote)->first();
        switch ($loteModel->Id_tecnica) {
            case 9: //todo Espectrofotometria
                $detModel = LoteDetalleEspectro::where('Id_lote',$request->idLote)->get();
                break;
            case 10: //todo Gravimetia
                switch ($request->idParametro) {
                    case 14:
                        $detModel = LoteDetalleGA::where('Id_lote',$request->idLote)->get();
                        break;
                    default:
                        # code...
                        $detModel = LoteDetalleSolidos::where('Id_lote',$request->idLote)->get();
                        break;
                }
                break;
            case 15: //todo Volumetria
                $detModel = LoteDetalleEspectro::where('Id_lote',$request->idLote)->get();
                        # code...
                break;
            default:
            $detModel = LoteDetalleEspectro::where('Id_lote',$request->idLote)->get();
                # code...
                break;
        }
        if($detModel->count())
        {
           if($detModel[0]->Id_parametro == $request->idParametro)
           {
            $sw = true;
           }
        }else{
            $sw = true;
        }

        if($sw = true)
        {
            switch ($loteModel->Id_tecnica) {
                case 9: //todo Espectrofotometria
                    $model = LoteDetalleEspectro::create([
                        'Id_lote' => $request->idLote,
                        'Id_analisis' => $request->idAnalisis,
                        'Id_parametro' => $request->idParametro,
                        'Descripcion' => 'Resultado',
                        'Abs1' => 0,
                        'Abs2' => 0,
                        'Abs3' => 0,
                        'De_color' => 0,
                        'Nitratos' => 0,
                        'Nitritos' => 0,
                        'Sulfuros' => 0,
                        'Vol_aforo' => 0,
                        'Vol_destilacion' => 0,
                        'Vol_muestra' => 0,
                    ]);
                    break;
                case 10: //todo Gravimetia
                    switch ($request->idParametro) {
                        case 14:
                            $model = LoteDetalleGA::create([ 
                                'Id_lote' => $request->idLote,
                                'Id_analisis' => $request->idAnalisis,
                                'Id_parametro' => $request->idParametro,
                                'Id_control' => 0,
                                'M_final' => 0,
                                'M_inicial1' => 0,
                                'M_inicial2' => 0,
                                'M_inicial3' => 0,
                                'Ph' => 0,
                                'Vol_muestra' => 1000,
                                'Blanco' => 0,
                                'F_conversion' => 0,
                            ]);
                            break;
                        default:
                            $model = LoteDetalleSolidos::create([
                                'Id_lote' => $request->idLote,
                                'Id_analisis' => $request->idAnalisis,
                                'Id_parametro' => $request->idParametro,
                                'Id_control' => 1,
                                'Masa1' => 0,
                                'Masa2' => 0,
                                'Peso_muestra1' => 0,
                                'Peso_muestra2' => 0, 
                                'Peso_constante1' => 0,
                                'Peso_constante2' => 0,
                                'Vol_muestra' => 0,
                                'Factor_conversion' => 0,
                                'Observacion' => '',
                            ]);
                            break;
                    }
                    break;
                
                    break;
                case 15: //todo Volumetria
                            # code...
                            $model = LoteDetalleEspectro::create([
                                'Id_lote' => $request->idLote,
                                'Id_analisis' => $request->idAnalisis,
                                'Id_parametro' => $request->idParametro,
                                'Descripcion' => 'Resultado',
                                'Abs1' => 0,
                                'Abs2' => 0,
                                'Abs3' => 0,
                                'De_color' => 0,
                                'Nitratos' => 0,
                                'Nitritos' => 0,
                                'Sulfuros' => 0,
                                'Vol_aforo' => 0,
                                'Vol_destilacion' => 0,
                                'Vol_muestra' => 0,
                            ]);
                    break;
                default:
                $model = LoteDetalleEspectro::create([
                    'Id_lote' => $request->idLote,
                    'Id_analisis' => $request->idAnalisis,
                    'Id_parametro' => $request->idParametro,
                    'Descripcion' => 'Resultado',
                    'Abs1' => 0,
                    'Abs2' => 0,
                    'Abs3' => 0,
                    'De_color' => 0,
                    'Nitratos' => 0,
                    'Nitritos' => 0,
                    'Sulfuros' => 0,
                    'Vol_aforo' => 0,
                    'Vol_destilacion' => 0,
                    'Vol_muestra' => 0,
                ]);
                    # code...
                    break;
            }
            $solModel = SolicitudParametro::find($request->idSol);
            $solModel->Asignado = 1;
            $solModel->save();

            // $detModel = LoteDetalle::where('Id_lote',$request->idLote)->get();

            $loteModel = LoteAnalisis::find($request->idLote);
            $loteModel->Asignado = $detModel->count();
            $loteModel->Liberado = 0;
            $loteModel->save();
        }else{
            
        }

        //? Muestra datos de lote detalle
        switch ($loteModel->Id_tecnica) {
            case 9: //todo Espectrofotometria
                $paraModel = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $request->idLote)->get();
                break;
            case 10: //todo Gravimetia
                    # code...ViewLoteDetalleGA
                    switch ($request->idParametro) {
                        case 14:
                            $paraModel = DB::table('ViewLoteDetalleGA')->where('Id_lote', $request->idLote)->get();
                            break;
                        default:
                            $paraModel = DB::table('ViewLoteDetalleSolidos')->where('Id_lote', $request->idLote)->get();
                            break;
                    }
                    break;
                break;
            case 15: //todo Volumetria
                        # code...
                break;
            default:
                # code...
                $paraModel = "";
                break;
        }

        $data = array(
            'sw' => $sw,
            'model' => $paraModel,
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
    public function guardarDatos(Request $request){

        //****************************************************GRASAS*****************************************************************        
        //calentamiento_matraces
        $calentamientoFqModel = CalentamientoMatraz::where('Id_lote', $request->idLote)->get();

        if($calentamientoFqModel->count()){
            for($i = 0; $i < 3; $i++){
                $calentamientoMatraz = CalentamientoMatraz::find($calentamientoFqModel[$i]->Id_calentamiento);

                $calentamientoMatraz->Id_lote = $request->grasas_calentamiento[$i][0];
                $calentamientoMatraz->Masa_constante = $request->grasas_calentamiento[$i][1];
                $calentamientoMatraz->Temperatura = $request->grasas_calentamiento[$i][2];
                $calentamientoMatraz->Entrada = $request->grasas_calentamiento[$i][3];
                $calentamientoMatraz->Salida = $request->grasas_calentamiento[$i][4];
                $calentamientoMatraz->Id_user_m = Auth::user()->id;

                $calentamientoMatraz->save();
            }
        }else{
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

        if($enfriadoFqModel->count()){
            for($i = 0; $i < 3; $i++){
                $enfriadoMatraz = EnfriadoMatraces::find($enfriadoFqModel[$i]->Id_enfriado);
                $enfriadoMatraz->Id_lote = $request->grasas_enfriado[$i][0];
                $enfriadoMatraz->Masa_constante = $request->grasas_enfriado[$i][1];
                $enfriadoMatraz->Entrada = $request->grasas_enfriado[$i][2];
                $enfriadoMatraz->Salida = $request->grasas_enfriado[$i][3];
                $enfriadoMatraz->Pesado_matraz = $request->grasas_enfriado[$i][4];
                $enfriadoMatraz->Id_user_m = Auth::user()->id;

                $enfriadoMatraz->save();
            }            
        }else{
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

        if($secadoFqModel->count()){
            $secadoCartucho = SecadoCartucho::find($secadoFqModel[0]->Id_secado);
            $secadoCartucho->Id_lote = $request->grasas_secadoLote;
            $secadoCartucho->Temperatura = $request->grasas_secadoTemp;
            $secadoCartucho->Entrada = $request->grasas_secadoEntrada;
            $secadoCartucho->Salida = $request->grasas_secadoSalida;
            $secadoCartucho->Id_user_m = Auth::user()->id;

            $secadoCartucho->save();
        }else{
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
        
        if($tiempoFqModel->count()){
            $tiempoReflujo = TiempoReflujo::find($tiempoFqModel[0]->Id_tiempo);
            $tiempoReflujo->Id_lote = $request->grasas_tiempoLote;
            $tiempoReflujo->Entrada = $request->grasas_tiempoEntrada;
            $tiempoReflujo->Salida = $request->grasas_tiempoSalida;
            $tiempoReflujo->Id_user_m = Auth::user()->id;

            $tiempoReflujo->save();
        }else{
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

        if($enfriadoFqModel->count()){
            $enfriadoMatraz2 = EnfriadoMatraz::find($enfriadoFqModel[0]->Id_enfriado);
            $enfriadoMatraz2->Id_lote = $request->grasas_enfriadoLote;
            $enfriadoMatraz2->Entrada = $request->grasas_enfriadoEntrada;
            $enfriadoMatraz2->Salida = $request->grasas_enfriadoSalida;
            $enfriadoMatraz2->Id_user_m = Auth::user()->id;

            $enfriadoMatraz2->save();
        }else{
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
            compact('sembradoFqModel', 'pruebaPresuntivaModel','pruebaConfirmativaModel', 'dqoModel')
        );
    }
    
    // todo ***************************
    // todo Inicia Captura GA
    // todo ***************************
    public function operacionGASimple(Request $request)
    { 
        $modelMatraz = MatrazGA::all();
        $cont = 0;
        //? Aplica la busqueda de matraz hasta encontrar un matraz desocupado
        do{
            $id = rand(0,$modelMatraz->count());
            $matraz = MatrazGA::where('id_matraz', $id)->first();
            $cont++;
        }while($matraz->Estado == 1);        

        //$m3 = mt_rand($matraz->Min, $matraz->Max);
        $dif = ($matraz->Max - $matraz->Min); 
        $ran = (round($dif,4))/10;
        $m3 = $matraz->Max - $ran;


        $mf = ((($request->R/$request->E) * $request->I)+$m3);
        $m1 = ($m3 - 0.0002); 
        $m2 = ($m3 - 0.0001);


        $data = array(
           'mf' => $mf,
           'm1' => $m1,
           'm2' => $m2,
           'm3' => $m3,
           'serie' => $matraz->Num_serie,        
           'min' => $matraz->Min,
           'max' => $matraz->Max,
           //'potencia' => $potencia,
        ); 
        return response()->json($data); 
    }
    public function operacionGALarga(Request $request){
        // $res = (((($request->H - $request->C) / $request->I) * $request->E) - $request->G);
        $res1 = $request->H - $request->C;
        $res2 = $res1 / $request->I;
        $res3 = $res2 * $request->E;
        $res = $res3 - $request->G;
        
        $data = array(

            'res' => $res,
        );
        return response()->json($data);
    }
    public function capturaGA()  
    {
        $parametro = Parametro::where('Id_area', 13)->get();
        $controlModel = ControlCalidad::all();
        // $formulas = DB::table('ViewTipoFormula')->where('Id_area',2)->get();
        // var_dump($parametro); 
        return view('laboratorio.fq.capturaGA', compact('parametro','controlModel'));
    } 
    public function getDataCapturaGA(Request $request)
    {
        //$parametro = Parametro::where('Id_parametro',$request->formulaTipo)->first();
        $lote = DB::table('ViewLoteAnalisis')->where('Fecha', $request->fechaAnalisis)->get();
        $idLote = 0;
        foreach($lote as $item)
        { 
            $detModel = DB::table('ViewLoteDetalleGA')->where('Id_lote', $item->Id_lote)->first();
            if($detModel->Id_parametro == $request->formulaTipo) 
            { 
                $idLote = $detModel->Id_lote;
            } 
        } 

        // $detalleModel = DB::tables'ViewLoteDetalle')->where('Id_lote', $lote->Id_lote)->get();
        $detalle = DB::table('ViewLoteDetalleGA')->where('Id_lote', $idLote)->get();
        $loteModel = DB::table('ViewLoteAnalisis')->where('Id_lote', $idLote)->first();
        $curvaConst = CurvaConstantes::where('Id_lote',$idLote)->first();
        $data = array( 
            'idL' => $idLote,
            'de' => $detModel,
            'lote' => $loteModel,
            'detalle' => $detalle,
        ); 
        return response()->json($data); 
    }
    public function getDetalleGA(Request $request)
    {
        $model = DB::table("ViewLoteDetalleGA")->where('Id_detalle',$request->idDetalle)->first();
        
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function createControlCalidad(Request $request)
    {
        $muestra = LoteDetalleGA::where('Id_detalle',$request->idMuestra)->first();

        $model = LoteDetalleGA::create([ 
            'Id_lote' => $muestra->Id_lote,
            'Id_analisis' => $muestra->Id_analisis,
            'Id_parametro' => $muestra->Id_parametro,
            'Id_control' => $request->idControl,
            'M_final' => 0,
            'M_inicial1' => 0,
            'M_inicial2' => 0,
            'M_inicial3' => 0,
            'Ph' => 0,
            'Vol_muestra' => 0,
            'Blanco' => 0,
            'F_conversion' => 0, 
        ]);
        
        $data = array(
            'model' => $model,
            'muestra' => $muestra,
        );
        return response()->json($data);
    }
    public function updateObsMuestraGA(Request $request)
    {
        $model = LoteDetalleGA::where('Id_detalle',$request->idMuestra)->first();
        $model->Observacion = $request->observacion;
        $model->save();

        $data = array(
            'model' => $model, 
        );
        return response()->json($data);
    }
    // todo ***************************
    // todo Fin Captura GA
    // todo ***************************

    // todo ***************************
    // todo Inicia Captura Solidos
    // todo ***************************

    public function capturaSolidos()
    {
        $parametro = Parametro::where('Id_area', 15)->get();
        $controlModel = ControlCalidad::all(); 
        // $formulas = DB::table('ViewTipoFormula')->where('Id_area',2)->get();
        // var_dump($parametro); 
        return view('laboratorio.fq.capturaSolidos', compact('parametro','controlModel')); 
    }

    public function getDataCapturaSolidos(Request $request)
    {
        //$parametro = Parametro::where('Id_parametro',$request->formulaTipo)->first();
        $lote = DB::table('ViewLoteAnalisis')->where('Fecha', $request->fechaAnalisis)->get();
        $idLote = 0;
        foreach($lote as $item)
        { 
            $detModel = DB::table('ViewLoteDetalleSolidos')->where('Id_lote', $item->Id_lote)->first();
            if($detModel->Id_parametro == $request->formulaTipo) 
            { 
                $idLote = $detModel->Id_lote;
            } 
        } 

        // $detalleModel = DB::tables'ViewLoteDetalle')->where('Id_lote', $lote->Id_lote)->get();
        $detalle = DB::table('ViewLoteDetalleSolidos')->where('Id_lote', $idLote)->get();
        $loteModel = DB::table('ViewLoteAnalisis')->where('Id_lote', $idLote)->first();
        $curvaConst = CurvaConstantes::where('Id_lote',$idLote)->first();
        $data = array( 
            'idL' => $idLote,
            'de' => $detModel,
            'lote' => $loteModel,
            'detalle' => $detalle,
        ); 
        return response()->json($data); 
    }

    public function getDetalleSolidos(Request $request)
    {
        $model = DB::table("ViewLoteDetalleGA")->where('Id_detalle',$request->idDetalle)->first();
        
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }

    public function operacionSolidosSimple(Request $request)
    { 
        $modelCrisol = CrisolesGA::all();
        //? Aplica la busqueda de crisol hasta encontrar un crisol desocupado
        do{
            $id = rand(0,$modelCrisol->count());
            $crisol = CrisolesGA::where('Id_crisol', $id)->first();
        }while($crisol->Estado == 1);     

        $mf = ((($request->R/$request->factor) * $request->volumen)+$crisol->Peso);
    
        $data = array(
           'masa1' => $crisol->Peso,
           'masa2' => $mf,
           'pesoConMuestra1' => ($crisol->Peso + 0.0002),
           'pesoConMuestra2' => ($crisol->Peso + 0.0004),
           'pesoC1' => $mf + 0.0002,
           'pesoC2' => $mf + 0.0004,
           'serie' => $crisol->Num_serie,  
           //'potencia' => $potencia,
        );
        return response()->json($data);
    }
    public function operacionSolidosLarga(Request $request){
        $res1 = $request->masa2 - $request->masa1;
        $res2 = $res1 / $request->volumen;
        $res = $res2 * $request->factor;
        
        $data = array(

            'res' => $res,
        );
        return response()->json($data);
    }
    

    public function createControlCalidadSolidos(Request $request){
        $muestra = LoteDetalleSolidos::where('Id_detalle',$request->idMuestra)->first();

        $model = LoteDetalleSolidos::create([
            'Id_lote' => $muestra->Id_lote,
            'Id_analisis' => $muestra->Id_analisis,
            'Id_parametro' => $muestra->Id_parametro,
            'Id_control' => $request->idControl,
            'Masa1' => 0,
            'Masa2' => 0,
            'Peso_muestra1' => 0,
            'Peso_muestra2' => 0, 
            'Peso_constante1' => 0,
            'Peso_constante2' => 0,
            'Vol_muestra' => 0,
            'Factor_conversion' => 0,
            'Observacion' => $muestra->Observacion,
        ]);
        
        $data = array(
            'model' => $model,
            'muestra' => $muestra,
        );
        return response()->json($data);
    }

    public function updateObsMuestraSolidos(Request $request)
    {
        $model = LoteDetalleGA::where('Id_detalle',$request->idMuestra)->first();
        $model->Observacion = $request->observacion;
        $model->save();

        $data = array(
            'model' => $model, 
        );
        return response()->json($data);
    }
    
    // todo ***************************
    // todo Fin Captura Solidos
    // todo ***************************


    //todo *******************************************
    //todo Inicio Seccion de Volumetria
    //todo *******************************************
    public function capturaVolumetria()
    {
        $parametro = Parametro::where('Id_area', 14)->get();
        // $formulas = DB::table('ViewTipoFormula')->where('Id_area',2)->get();
        // var_dump($parametro); 
        return view('laboratorio.fq.capturaVolumetria', compact('parametro')); 
    }
    public function operacionVolumetria()
    {
 
    }

    public function getDataCapturaVolumetria(Request $request)
    {
        //$parametro = Parametro::where('Id_parametro',$request->formulaTipo)->first();
        $lote = DB::table('ViewLoteAnalisis')->where('Fecha', $request->fechaAnalisis)->get();
        $idLote = 0;
        foreach($lote as $item)
        { 
            $detModel = DB::table('ViewLoteDetalleVolumetria')->where('Id_lote', $item->Id_lote)->first();
            if($detModel->Id_parametro == $request->formulaTipo)
            { 
                $idLote = $detModel->Id_lote;
            } 
        }

        // $detalleModel = DB::tables'ViewLoteDetalle')->where('Id_lote', $lote->Id_lote)->get();
        $detalle = DB::table('ViewLoteDetalleVolumetria')->where('Id_lote', $idLote)->get();
        $loteModel = DB::table('ViewLoteAnalisis')->where('Id_lote', $idLote)->first();
        $curvaConst = CurvaConstantes::where('Id_lote',$idLote)->first();
        $data = array( 
            'idL' => $idLote,
            'de' => $detModel,
            'lote' => $loteModel,
            'curvaConst' => $curvaConst,
            'detalle' => $detalle,
        ); 
        return response()->json($data); 
    }
    public function getDetalleVolumetria(Request $request)
    {
        $model = DB::table("ViewLoteDetalleVolumetria")->where('Id_detalle',$request->idDetalle)->first();
        $curva = CurvaConstantes::where('Id_lote',$model->Id_lote)->first();

        $data = array(
            'model' => $model,
            'curva' => $curva,
        );
        return response()->json($data);
    }
    
    //todo *******************************************
    //todo Fin Seccion de Volumetria
    //todo *******************************************     

    //FUNCIÓN PARA GENERAR EL DOCUMENTO PDF; DE MOMENTO NO RECIBE UN IDLOTE
    public function exportPdfCapturaEspectro($idLote)
    {
         //Var. de prueba temporal
         //$idLote = 11;

        $horizontal = false;
        $sw = true;

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

         $mpdfH = new \Mpdf\Mpdf([    
            'orientation' => 'L',        
            'format' => 'letter',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 31,
            'margin_bottom' => 45,
            'defaultheaderfontstyle' => ['normal'],
            'defaultheaderline' => '0'
        ]);
 
         //Establece la marca de agua del documento PDF
        /* $mpdf->SetWatermarkImage( 
            asset('HojaMembretada2.png'),
            1,
            array(215, 280),
            array(0, 0),
        ); 
 
        $mpdf->showWatermarkImage = true; */

        $id_lote = $idLote;
        $semaforo = true;
 
         //Recupera el nombre de usuario y firma
         $usuario = DB::table('users')->where('id', auth()->user()->id)->first();
         $firma = $usuario->firma;
 
         //Formatea la fecha
         $fechaAnalisis = DB::table('ViewLoteAnalisis')->where('Id_lote', $id_lote)->first();
         if(!is_null($fechaAnalisis)){
             $fechaConFormato = date("d/m/Y", strtotime($fechaAnalisis->Fecha));
         }else{
             $fechaAnalisis = DB::table('ViewLoteAnalisis')->where('Id_lote', 0)->first();
             $fechaConFormato = date("d/m/Y", strtotime($fechaAnalisis->Fecha));
             echo '<script> alert("Valores predeterminados para la fecha de análisis. Rellena este campo.") </script>';
         }   
         
         //Recupera el parámetro que se está utilizando
         $parametro = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $id_lote)->first();
 
        //Recupera el texto dinámico Procedimientos de la tabla reportes****************************************************
        $textoProcedimiento = ReportesFq::where('Id_lote', $id_lote)->first();
        if(!is_null($textoProcedimiento)){
           //Hoja1            
            if($parametro->Parametro == 'BORO (B)'){                
                $horizontal = false;
                $data = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $id_lote)->get();

                if(!is_null($data)){                         
                    $curva = CurvaConstantes::where('Id_lote', $id_lote)->first();
                    $dataLength = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $id_lote)->count();               
                    $htmlCaptura = view('exports.laboratorio.fq.espectro.boro.capturaBody', compact('textoProcedimiento', 'data', 'dataLength', 'curva'));
                }else{
                    $sw = false;
                    $mpdf->SetJS('print("No se han llenado todos los datos del reporte. Verifica que todos los datos estén ingresados.");');
                }
            }else if($parametro->Parametro == 'Cianuros (CN)-'){
                $horizontal = false;                
                $data = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $id_lote)->get();

                if(!is_null($data)){                         
                    $curva = CurvaConstantes::where('Id_lote', $id_lote)->first();
                    $dataLength = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $id_lote)->count();               
                    $htmlCaptura = view('exports.laboratorio.fq.espectro.cianuros.capturaBody', compact('textoProcedimiento', 'data', 'dataLength', 'curva'));
                }else{
                    $sw = false;
                    $mpdf->SetJS('print("No se han llenado todos los datos del reporte. Verifica que todos los datos estén ingresados.");');
                }
            }else if($parametro->Parametro == 'Conductividad'){ //POR REVISAR EN LA TABLA DE DATOS                
                $horizontal = false;                              
                $data = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $id_lote)->get();

                if(!is_null($data)){                         
                    $curva = CurvaConstantes::where('Id_lote', $id_lote)->first();
                    $dataLength = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $id_lote)->count();               
                    $htmlCaptura = view('exports.laboratorio.fq.espectro.condElec.capturaBody', compact('textoProcedimiento', 'data', 'dataLength', 'curva'));
                }else{
                    $sw = false;
                    $mpdf->SetJS('print("No se han llenado todos los datos del reporte. Verifica que todos los datos estén ingresados.");');
                }
            }else if($parametro->Parametro == 'CROMO HEXAVALENTE (Cr+6)'){
                $horizontal = false;                
                $data = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $id_lote)->get();

                if(!is_null($data)){                         
                    $curva = CurvaConstantes::where('Id_lote', $id_lote)->first();
                    $dataLength = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $id_lote)->count();               
                    $htmlCaptura = view('exports.laboratorio.fq.espectro.cromoHex.capturaBody', compact('textoProcedimiento', 'data', 'dataLength', 'curva'));
                }else{
                    $sw = false;
                    $mpdf->SetJS('print("No se han llenado todos los datos del reporte. Verifica que todos los datos estén ingresados.");');
                }
            }else if($parametro->Parametro == 'Fosforo-Total'){
                $horizontal = false;                
                $data = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $id_lote)->get();

                if(!is_null($data)){                         
                    $curva = CurvaConstantes::where('Id_lote', $id_lote)->first();
                    $dataLength = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $id_lote)->count();               
                    $htmlCaptura = view('exports.laboratorio.fq.espectro.fosforoTotal.capturaBody', compact('textoProcedimiento', 'data', 'dataLength', 'curva'));
                }else{
                    $sw = false;
                    $mpdf->SetJS('print("No se han llenado todos los datos del reporte. Verifica que todos los datos estén ingresados.");');
                }                
            }else if($parametro->Parametro == 'Materia flotante'){ //POR REVISAR EN LA TABLA DE DATOS
                $horizontal = false;                
                $data = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $id_lote)->get();

                if(!is_null($data)){                         
                    $curva = CurvaConstantes::where('Id_lote', $id_lote)->first();
                    $dataLength = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $id_lote)->count();               
                    $htmlCaptura = view('exports.laboratorio.fq.espectro.materiaF.capturaBody', compact('textoProcedimiento', 'data', 'dataLength', 'curva'));
                }else{
                    $sw = false;
                    $mpdf->SetJS('print("No se han llenado todos los datos del reporte. Verifica que todos los datos estén ingresados.");');
                }
            }else if($parametro->Parametro == 'SILICE (SiO₂)'){
                $horizontal = false;                
                $data = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $id_lote)->get();

                if(!is_null($data)){                         
                    $curva = CurvaConstantes::where('Id_lote', $id_lote)->first();
                    $dataLength = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $id_lote)->count();               
                    $htmlCaptura = view('exports.laboratorio.fq.espectro.silice.capturaBody', compact('textoProcedimiento', 'data', 'dataLength', 'curva'));
                }else{
                    $sw = false;
                    $mpdf->SetJS('print("No se han llenado todos los datos del reporte. Verifica que todos los datos estén ingresados.");');
                }

                //$htmlCaptura = view('exports.laboratorio.fq.espectro.silice.capturaBody', compact('textoProcedimiento'));                
            }else if($parametro->Parametro == 'FENOLES TOTALES'){
                $horizontal = false;                
                $data = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $id_lote)->get();

                if(!is_null($data)){                         
                    $curva = CurvaConstantes::where('Id_lote', $id_lote)->first();
                    $dataLength = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $id_lote)->count();               
                    $htmlCaptura = view('exports.laboratorio.fq.espectro.fenoles.capturaBody', compact('textoProcedimiento', 'data', 'dataLength', 'curva'));
                }else{
                    $sw = false;
                    $mpdf->SetJS('print("No se han llenado todos los datos del reporte. Verifica que todos los datos estén ingresados.");');
                }                
            }else if($parametro->Parametro == 'FLUORUROS (F¯)'){
                $horizontal = false;                
                $data = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $id_lote)->get();

                if(!is_null($data)){                         
                    $curva = CurvaConstantes::where('Id_lote', $id_lote)->first();
                    $dataLength = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $id_lote)->count();               
                    $htmlCaptura = view('exports.laboratorio.fq.espectro.fluoruros.capturaBody', compact('textoProcedimiento', 'data', 'dataLength', 'curva'));
                }else{
                    $sw = false;
                    $mpdf->SetJS('print("No se han llenado todos los datos del reporte. Verifica que todos los datos estén ingresados.");');
                }                                                
            }else if($parametro->Parametro == 'N-Nitratos'){
                $horizontal = false;                
                $data = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $id_lote)->get();

                if(!is_null($data)){                         
                    $curva = CurvaConstantes::where('Id_lote', $id_lote)->first();
                    $dataLength = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $id_lote)->count();               
                    $htmlCaptura = view('exports.laboratorio.fq.espectro.nitratos.capturaBody', compact('textoProcedimiento', 'data', 'dataLength', 'curva'));
                }else{
                    $sw = false;
                    $mpdf->SetJS('print("No se han llenado todos los datos del reporte. Verifica que todos los datos estén ingresados.");');
                }
            }else if($parametro->Parametro == 'N-Nitritos'){
                $horizontal = false;                
                $data = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $id_lote)->get();

                if(!is_null($data)){                         
                    $curva = CurvaConstantes::where('Id_lote', $id_lote)->first();
                    $dataLength = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $id_lote)->count();               
                                        
                    $htmlCaptura = view('exports.laboratorio.fq.espectro.nitritos.capturaBody', compact('textoProcedimiento', 'data', 'dataLength', 'curva'));
                }else{
                    $sw = false;
                    $mpdf->SetJS('print("No se han llenado todos los datos del reporte. Verifica que todos los datos estén ingresados.");');
                }
            }else if($parametro->Parametro == 'SUSTANCIAS ACTIVAS AL AZUL DE METILENO (SAAM )'){
                $horizontal = false;                
                $data = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $id_lote)->get();

                if(!is_null($data)){
                    $curva = CurvaConstantes::where('Id_lote', $id_lote)->first();
                    $dataLength = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $id_lote)->count();               
                    $htmlCaptura = view('exports.laboratorio.fq.espectro.saam.capturaBody', compact('textoProcedimiento', 'data', 'dataLength', 'curva'));
                }else{
                    $sw = false;
                    $mpdf->SetJS('print("No se han llenado todos los datos del reporte. Verifica que todos los datos estén ingresados.");');
                }                                
            }else if($parametro->Parametro == 'SULFATOS (SO4˭)'){
                $horizontal = true;                
                $data = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $id_lote)->get();

                if(!is_null($data)){                         
                    $curva = CurvaConstantes::where('Id_lote', $id_lote)->first();
                    $dataLength = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $id_lote)->count();               
                    $htmlCaptura = view('exports.laboratorio.fq.espectro.sulfatos.capturaBody', compact('textoProcedimiento', 'data', 'dataLength', 'curva'));
                }else{
                    $sw = false;
                    $mpdf->SetJS('print("No se han llenado todos los datos del reporte. Verifica que todos los datos estén ingresados.");');
                }                
            }
        }else{                        
            if($parametro->Parametro == 'BORO (B)'){                                
                $horizontal = false;                
                $data = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $id_lote)->get();

                if(!is_null($data)){
                    $curva = CurvaConstantes::where('Id_lote', $id_lote)->first();                    
                    $dataLength = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $id_lote)->count();
                    $textoProcedimiento = ReportesFq::where('Id_reporte', 3)->first();
                    $htmlCaptura = view('exports.laboratorio.fq.espectro.boro.capturaBody', compact('textoProcedimiento', 'data', 'dataLength', 'curva'));
                }else{
                    $sw = false;
                    $mpdf->SetJS('No se han llenado todos los datos del reporte. Verifica que todos los datos estén ingresados.");');
                }
            }else if($parametro->Parametro == 'Cianuros (CN)-'){
                $horizontal = false;                
                $data = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $id_lote)->get();

                if(!is_null($data)){
                    $curva = CurvaConstantes::where('Id_lote', $id_lote)->first();                    
                    $dataLength = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $id_lote)->count();
                    $textoProcedimiento = ReportesFq::where('Id_reporte', 4)->first();
                    $htmlCaptura = view('exports.laboratorio.fq.espectro.cianuros.capturaBody', compact('textoProcedimiento', 'data', 'dataLength', 'curva'));
                }else{
                    $sw = false;
                    $mpdf->SetJS('No se han llenado todos los datos del reporte. Verifica que todos los datos estén ingresados.");');
                }                                
            }else if($parametro->Parametro == 'Conductividad'){ //POR REVISAR EN LA TABLA DE DATOS                                
                $horizontal = false;                               
                $data = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $id_lote)->get();

                if(!is_null($data)){
                    $curva = CurvaConstantes::where('Id_lote', $id_lote)->first();                    
                    $dataLength = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $id_lote)->count();
                    $textoProcedimiento = ReportesFq::where('Id_reporte', 5)->first();
                    $htmlCaptura = view('exports.laboratorio.fq.espectro.condElec.capturaBody', compact('textoProcedimiento', 'data', 'dataLength', 'curva'));
                }else{
                    $sw = false;
                    $mpdf->SetJS('No se han llenado todos los datos del reporte. Verifica que todos los datos estén ingresados.");');
                }
            }else if($parametro->Parametro == 'CROMO HEXAVALENTE (Cr+6)'){
                $horizontal = false;                
                $data = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $id_lote)->get();

                if(!is_null($data)){
                    $curva = CurvaConstantes::where('Id_lote', $id_lote)->first();                    
                    $dataLength = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $id_lote)->count();
                    $textoProcedimiento = ReportesFq::where('Id_reporte', 6)->first();
                    $htmlCaptura = view('exports.laboratorio.fq.espectro.cromoHex.capturaBody', compact('textoProcedimiento', 'data', 'dataLength', 'curva'));
                }else{
                    $sw = false;
                    $mpdf->SetJS('No se han llenado todos los datos del reporte. Verifica que todos los datos estén ingresados.");');
                }                                                
            }else if($parametro->Parametro == 'Fosforo-Total'){
                $horizontal = false;                
                $data = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $id_lote)->get();

                if(!is_null($data)){
                    $curva = CurvaConstantes::where('Id_lote', $id_lote)->first();                    
                    $dataLength = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $id_lote)->count();
                    $textoProcedimiento = ReportesFq::where('Id_reporte', 7)->first();
                    $htmlCaptura = view('exports.laboratorio.fq.espectro.fosforoTotal.capturaBody', compact('textoProcedimiento', 'data', 'dataLength', 'curva'));
                }else{
                    $sw = false;
                    $mpdf->SetJS('No se han llenado todos los datos del reporte. Verifica que todos los datos estén ingresados.");');
                }
            }else if($parametro->Parametro == 'Materia flotante'){ //POR REVISAR EN LA TABLA DE DATOS                
                $horizontal = false;                
                $data = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $id_lote)->get();

                if(!is_null($data)){
                    $curva = CurvaConstantes::where('Id_lote', $id_lote)->first();                    
                    $dataLength = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $id_lote)->count();
                    $textoProcedimiento = ReportesFq::where('Id_reporte', 8)->first();
                    $htmlCaptura = view('exports.laboratorio.fq.espectro.materiaF.capturaBody', compact('textoProcedimiento', 'data', 'dataLength', 'curva'));
                }else{
                    $sw = false;
                    $mpdf->SetJS('No se han llenado todos los datos del reporte. Verifica que todos los datos estén ingresados.");');
                }
            }else if($parametro->Parametro == 'SILICE (SiO₂)'){
                $horizontal = false;                
                $data = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $id_lote)->get();

                if(!is_null($data)){
                    $curva = CurvaConstantes::where('Id_lote', $id_lote)->first();                    
                    $dataLength = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $id_lote)->count();
                    $textoProcedimiento = ReportesFq::where('Id_reporte', 9)->first();
                    $htmlCaptura = view('exports.laboratorio.fq.espectro.silice.capturaBody', compact('textoProcedimiento', 'data', 'dataLength', 'curva'));
                }else{
                    $sw = false;
                    $mpdf->SetJS('No se han llenado todos los datos del reporte. Verifica que todos los datos estén ingresados.");');
                }                                
            }else if($parametro->Parametro == 'FENOLES TOTALES'){
                $horizontal = false;                
                $data = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $id_lote)->get();

                if(!is_null($data)){
                    $curva = CurvaConstantes::where('Id_lote', $id_lote)->first();                    
                    $dataLength = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $id_lote)->count();
                    $textoProcedimiento = ReportesFq::where('Id_reporte', 10)->first();
                    $htmlCaptura = view('exports.laboratorio.fq.espectro.fenoles.capturaBody', compact('textoProcedimiento', 'data', 'dataLength', 'curva'));
                }else{
                    $sw = false;
                    $mpdf->SetJS('No se han llenado todos los datos del reporte. Verifica que todos los datos estén ingresados.");');
                }
            }else if($parametro->Parametro == 'FLUORUROS (F¯)'){
                $horizontal = false;                
                $data = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $id_lote)->get();

                if(!is_null($data)){
                    $curva = CurvaConstantes::where('Id_lote', $id_lote)->first();                    
                    $dataLength = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $id_lote)->count();
                    $textoProcedimiento = ReportesFq::where('Id_reporte', 11)->first();
                    $htmlCaptura = view('exports.laboratorio.fq.espectro.fluoruros.capturaBody', compact('textoProcedimiento', 'data', 'dataLength', 'curva'));
                }else{
                    $sw = false;
                    $mpdf->SetJS('No se han llenado todos los datos del reporte. Verifica que todos los datos estén ingresados.");');
                }                                
            }else if($parametro->Parametro == 'N-Nitratos'){              
                $horizontal = false;
                $data = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $id_lote)->get();

                if(!is_null($data)){
                    $curva = CurvaConstantes::where('Id_lote', $id_lote)->first();                    
                    $dataLength = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $id_lote)->count();
                    $textoProcedimiento = ReportesFq::where('Id_reporte', 2)->first();
                    $htmlCaptura = view('exports.laboratorio.fq.espectro.nitratos.capturaBody', compact('textoProcedimiento', 'data', 'dataLength', 'curva'));
                }else{
                    $sw = false;
                    $mpdf->SetJS('No se han llenado todos los datos del reporte. Verifica que todos los datos estén ingresados.");');
                }
            }else if($parametro->Parametro == 'N-Nitritos'){
                $horizontal = false;                
                $data = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $id_lote)->get();

                if(!is_null($data)){
                    $curva = CurvaConstantes::where('Id_lote', $id_lote)->first();                    
                    $dataLength = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $id_lote)->count();
                    $textoProcedimiento = ReportesFq::where('Id_reporte', 1)->first();
                                                    
                    $htmlCaptura = view('exports.laboratorio.fq.espectro.nitritos.capturaBody', compact('textoProcedimiento', 'data', 'dataLength', 'curva'));
                }else{
                    $sw = false;
                    $mpdf->SetJS('No se han llenado todos los datos del reporte. Verifica que todos los datos estén ingresados.");');
                }                                
            }else if($parametro->Parametro == 'SUSTANCIAS ACTIVAS AL AZUL DE METILENO (SAAM )'){
                $horizontal = false;
                $data = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $id_lote)->get();

                if(!is_null($data)){
                    $curva = CurvaConstantes::where('Id_lote', $id_lote)->first();                    
                    $dataLength = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $id_lote)->count();
                    $textoProcedimiento = ReportesFq::where('Id_reporte', 12)->first();
                    $htmlCaptura = view('exports.laboratorio.fq.espectro.saam.capturaBody', compact('textoProcedimiento', 'data', 'dataLength', 'curva'));
                }else{
                    $sw = false;
                    $mpdf->SetJS('No se han llenado todos los datos del reporte. Verifica que todos los datos estén ingresados.");');
                }
            }else if($parametro->Parametro == 'SULFATOS (SO4˭)'){
                $horizontal = true;                
                $data = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $id_lote)->get();

                if(!is_null($data)){
                    $curva = CurvaConstantes::where('Id_lote', $id_lote)->first();                    
                    $dataLength = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $id_lote)->count();
                    $textoProcedimiento = ReportesFq::where('Id_reporte', 13)->first();
                    $htmlCaptura = view('exports.laboratorio.fq.espectro.sulfatos.capturaBody', compact('textoProcedimiento', 'data', 'dataLength', 'curva'));
                }else{
                    $sw = false;
                    $mpdf->SetJS('No se han llenado todos los datos del reporte. Verifica que todos los datos estén ingresados.");');
                }
            }
        }   

        //HEADER-FOOTER******************************************************************************************************************         
        if($sw === true){        
            if($parametro->Parametro == 'BORO (B)'){
                $htmlHeader = view('exports.laboratorio.fq.espectro.boro.capturaHeader', compact('fechaConFormato'));
                $htmlFooter = view('exports.laboratorio.fq.espectro.boro.capturaFooter', compact('usuario', 'firma'));
            }else if($parametro->Parametro == 'Cianuros (CN)-'){
                $htmlHeader = view('exports.laboratorio.fq.espectro.cianuros.capturaHeader', compact('fechaConFormato'));
                $htmlFooter = view('exports.laboratorio.fq.espectro.cianuros.capturaFooter', compact('usuario', 'firma'));
            }else if($parametro->Parametro == 'Conductividad'){ //POR REVISAR EN LA TABLA DE DATOS
                $htmlHeader = view('exports.laboratorio.fq.espectro.condElec.capturaHeader', compact('fechaConFormato'));
                $htmlFooter = view('exports.laboratorio.fq.espectro.condElec.capturaFooter', compact('usuario', 'firma'));
            }else if($parametro->Parametro == 'CROMO HEXAVALENTE (Cr+6)'){
                $htmlHeader = view('exports.laboratorio.fq.espectro.cromoHex.capturaHeader', compact('fechaConFormato'));
                $htmlFooter = view('exports.laboratorio.fq.espectro.cromoHex.capturaFooter', compact('usuario', 'firma'));
            }else if($parametro->Parametro == 'Fosforo-Total'){
                $htmlHeader = view('exports.laboratorio.fq.espectro.fosforoTotal.capturaHeader', compact('fechaConFormato'));
                $htmlFooter = view('exports.laboratorio.fq.espectro.fosforoTotal.capturaFooter', compact('usuario', 'firma'));
            }else if($parametro->Parametro == 'Materia flotante'){ //POR REVISAR EN LA TABLA DE DATOS
                $htmlHeader = view('exports.laboratorio.fq.espectro.materiaF.capturaHeader', compact('fechaConFormato'));
                $htmlFooter = view('exports.laboratorio.fq.espectro.materiaF.capturaFooter', compact('usuario', 'firma'));
            }else if($parametro->Parametro == 'SILICE (SiO₂)'){
                $htmlHeader = view('exports.laboratorio.fq.espectro.silice.capturaHeader', compact('fechaConFormato'));
                $htmlFooter = view('exports.laboratorio.fq.espectro.silice.capturaFooter', compact('usuario', 'firma'));
            }else if($parametro->Parametro == 'FENOLES TOTALES'){
                $htmlHeader = view('exports.laboratorio.fq.espectro.fenoles.capturaHeader', compact('fechaConFormato'));
                $htmlFooter = view('exports.laboratorio.fq.espectro.fenoles.capturaFooter', compact('usuario', 'firma'));
            }else if($parametro->Parametro == 'FLUORUROS (F¯)'){
                $htmlHeader = view('exports.laboratorio.fq.espectro.fluoruros.capturaHeader', compact('fechaConFormato'));
                $htmlFooter = view('exports.laboratorio.fq.espectro.fluoruros.capturaFooter', compact('usuario', 'firma'));
            }else if($parametro->Parametro == 'N-Nitratos'){
                $htmlHeader = view('exports.laboratorio.fq.espectro.nitratos.capturaHeader', compact('fechaConFormato'));
                $htmlFooter = view('exports.laboratorio.fq.espectro.nitratos.capturaFooter', compact('usuario', 'firma'));
            }else if($parametro->Parametro == 'N-Nitritos'){                     
                $htmlHeader = view('exports.laboratorio.fq.espectro.nitritos.capturaHeader', compact('fechaConFormato'));
                $htmlFooter = view('exports.laboratorio.fq.espectro.nitritos.capturaFooter', compact('usuario', 'firma'));
            }else if($parametro->Parametro == 'SUSTANCIAS ACTIVAS AL AZUL DE METILENO (SAAM )'){
                $htmlHeader = view('exports.laboratorio.fq.espectro.saam.capturaHeader', compact('fechaConFormato'));
                $htmlFooter = view('exports.laboratorio.fq.espectro.saam.capturaFooter', compact('usuario', 'firma'));
            }else if($parametro->Parametro == 'SULFATOS (SO4˭)'){
                $htmlHeader = view('exports.laboratorio.fq.espectro.sulfatos.capturaHeader', compact('fechaConFormato'));
                $htmlFooter = view('exports.laboratorio.fq.espectro.sulfatos.capturaFooter', compact('usuario', 'firma'));
            }
        }                                  

        if($horizontal === false && $sw === true){            
            $mpdf->setHeader("{PAGENO}<br><br>" . $htmlHeader);
            $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
            $mpdf->WriteHTML($htmlCaptura);            
        }else if($horizontal === true && $sw === true){            
            $mpdfH->setHeader("{PAGENO}<br><br>" . $htmlHeader);
            $mpdfH->SetHTMLFooter($htmlFooter, 'O', 'E');
            $mpdfH->WriteHTML($htmlCaptura);            
        }
 
        if($horizontal === false && $sw === true){            
            $mpdf->CSSselectMedia = 'mpdf';
            $mpdf->Output();

        }else if($horizontal === true && $sw === true){  //Es vertical la bitácora
            $mpdfH->CSSselectMedia = 'mpdf';
            $mpdfH->Output();
        }        
    }

    //FUNCIÓN PARA GENERAR EL DOCUMENTO PDF; DE MOMENTO NO RECIBE UN IDLOTE
    public function exportPdfCapturaGA($idLote)
    {
         //Var. de prueba temporal
         //$idLote = 11;        

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
 
        //  //Establece la marca de agua del documento PDF
        /* $mpdf->SetWatermarkImage(
              asset('/public/storage/HojaMembretada3.png'),
              1,
              array(215, 280),
              array(0, 0),
          );         
          $mpdf->showWatermarkImage = true; */ 
 
         $id_lote = $idLote;
         $semaforo = true;             
 
         //Recupera el nombre de usuario y firma
         $usuario = DB::table('users')->where('id', auth()->user()->id)->first();
         $firma = $usuario->firma;
 
         //Formatea la fecha
         $fechaAnalisis = DB::table('ViewLoteAnalisis')->where('Id_lote', $id_lote)->first();
         if(!is_null($fechaAnalisis)){
             $fechaConFormato = date("d/m/Y", strtotime($fechaAnalisis->Fecha));
         }else{
             $fechaAnalisis = DB::table('ViewLoteAnalisis')->where('Id_lote', 0)->first();
             $fechaConFormato = date("d/m/Y", strtotime($fechaAnalisis->Fecha));
             echo '<script> alert("Valores predeterminados para la fecha de análisis. Rellena este campo.") </script>';
         }   
                   
        //Recupera (PRUEBA) el texto dinámico Procedimientos de la tabla reportes****************************************************
        $textoProcedimiento = ReportesFq::where('Id_lote', $id_lote)->first();
        if(!is_null($textoProcedimiento)){
            $calMatraces = CalentamientoMatraz::where('Id_lote', $id_lote)->get();
            $enfMatraces = EnfriadoMatraces::where('Id_lote', $id_lote)->get();
            $secCartuchos = SecadoCartucho::where('Id_lote', $id_lote)->first();
            $tiempoReflujo = TiempoReflujo::where('Id_lote', $id_lote)->first();
            $enfMatraz = EnfriadoMatraz::where('Id_lote', $id_lote)->first();  

            $htmlCaptura = view('exports.laboratorio.fq.ga.ga.capturaBody', compact('textoProcedimiento', 'calMatraces', 'enfMatraces', 'secCartuchos', 'tiempoReflujo', 'enfMatraz'));
        }else{                                                                                                          
            $textoProcedimiento = ReportesFq::where('Id_reporte', 0)->first();

            $calMatraces = CalentamientoMatraz::where('Id_lote', $id_lote)->get();
            $enfMatraces = EnfriadoMatraces::where('Id_lote', $id_lote)->get();
            $secCartuchos = SecadoCartucho::where('Id_lote', $id_lote)->first();
            $tiempoReflujo = TiempoReflujo::where('Id_lote', $id_lote)->first();
            $enfMatraz = EnfriadoMatraz::where('Id_lote', $id_lote)->first();                                  
 
            $mpdf->SetJS('print("Valores predeterminados para el reporte. Rellena este campo.");');

            $htmlCaptura = view('exports.laboratorio.fq.ga.ga.capturaBody', compact('textoProcedimiento', 'calMatraces', 'enfMatraces', 'secCartuchos', 'tiempoReflujo', 'enfMatraz'));
             
        }   

        //HEADER-FOOTER******************************************************************************************************************                 
        $htmlHeader = view('exports.laboratorio.fq.ga.ga.capturaHeader', compact('fechaConFormato'));
        $htmlFooter = view('exports.laboratorio.fq.ga.ga.capturaFooter', compact('usuario', 'firma'));
                                                 
        $mpdf->setHeader("{PAGENO}<br><br>" . $htmlHeader);
        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
        $mpdf->WriteHTML($htmlCaptura);   
                        
        $mpdf->AddPage('', '', '1', '', '', '', '', 35, 45, 6.5, '', '', '', '', '', -1, -1, -1, -1);
                    
        $htmlCaptura1 = view('exports.laboratorio.fq.ga.ga.captura1Body');
        $htmlCurvaHeader = view('exports.laboratorio.fq.ga.ga.capturaHeader', compact('fechaConFormato'));
        $htmlCurvaFooter = view('exports.laboratorio.fq.ga.ga.capturaFooter', compact('usuario', 'firma'));
        
        //Hoja 2
        $mpdf->SetHTMLHeader('{PAGENO}<br><br>' . $htmlCurvaHeader, 'O', 'E');
        $mpdf->SetHTMLFooter($htmlCurvaFooter, 'O', 'E');
        $mpdf->WriteHTML($htmlCaptura1);                                    
         
        $mpdf->CSSselectMedia = 'mpdf';
        $mpdf->Output();        
    }
    
    //FUNCIÓN PARA GENERAR EL DOCUMENTO PDF; DE MOMENTO NO RECIBE UN IDLOTE
    public function exportPdfCapturaSolidos($idLote)
    {
         //Var. de prueba temporal
         //$idLote = 11;

        $horizontal = false;

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

         $mpdfH = new \Mpdf\Mpdf([    
            'orientation' => 'L',        
            'format' => 'letter',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 31,
            'margin_bottom' => 45,
            'defaultheaderfontstyle' => ['normal'],
            'defaultheaderline' => '0'
        ]);
 
         //Establece la marca de agua del documento PDF
         $mpdfH->SetWatermarkImage(
             asset('/public/storage/HojaMembretadaHorizontal.png'),
             1,
             array(215, 280),
             array(0, 0),
         ); 
 
         $mpdfH->showWatermarkImage = true;         
 
         $id_lote = $idLote;
         $semaforo = true;             
 
         //Recupera el nombre de usuario y firma
         $usuario = DB::table('users')->where('id', auth()->user()->id)->first();
         $firma = $usuario->firma;
 
         //Formatea la fecha
         $fechaAnalisis = DB::table('ViewLoteAnalisis')->where('Id_lote', $id_lote)->first();
         if(!is_null($fechaAnalisis)){
             $fechaConFormato = date("d/m/Y", strtotime($fechaAnalisis->Fecha));
         }else{
             $fechaAnalisis = DB::table('ViewLoteAnalisis')->where('Id_lote', 0)->first();
             $fechaConFormato = date("d/m/Y", strtotime($fechaAnalisis->Fecha));
             echo '<script> alert("Valores predeterminados para la fecha de análisis. Rellena este campo.") </script>';
         }   
         
         //Recupera el parámetro que se está utilizando
         $parametro = DB::table('ViewLoteDetalleSolidos')->where('Id_lote', $id_lote)->first();
 
        //Recupera (PRUEBA) el texto dinámico Procedimientos de la tabla reportes****************************************************
        $textoProcedimiento = ReportesFq::where('Id_lote', $id_lote)->first();
        if(!is_null($textoProcedimiento)){
           //Hoja1
            
            if($parametro->Parametro == 'SOLIDOS DISUELTOS FIJOS (SDF)'){
                $horizontal = false;                
                $data = DB::table('ViewLoteDetalleSolidos')->where('Id_lote', $id_lote)->get();

                if(!is_null($data)){                                                           
                    $htmlCaptura = view('exports.laboratorio.fq.sdf.capturaBody', compact('textoProcedimiento',));
                }else{
                    $sw = false;
                    $mpdf->SetJS('print("No se han llenado todos los datos del reporte. Verifica que todos los datos estén ingresados.");');
                }                                
            }else if($parametro->Parametro == 'SOLIDOS DISUELTOS TOTALES (SDT)'){ //POR REVISAR EN LA TABLA DE DATOS
                $horizontal = false;                
                $data = DB::table('ViewLoteDetalleSolidos')->where('Id_lote', $id_lote)->get();

                if(!is_null($data)){                                                           
                    $htmlCaptura = view('exports.laboratorio.fq.sdt.capturaBody', compact('textoProcedimiento',));
                }else{
                    $sw = false;
                    $mpdf->SetJS('print("No se han llenado todos los datos del reporte. Verifica que todos los datos estén ingresados.");');
                }                                                  
            }else if($parametro->Parametro == 'SOLIDOS DISUELTOS VOLÁTILES (SDV)'){
                $horizontal = false;                
                $data = DB::table('ViewLoteDetalleSolidos')->where('Id_lote', $id_lote)->get();

                if(!is_null($data)){                                                           
                    $htmlCaptura = view('exports.laboratorio.fq.sdv.capturaBody', compact('textoProcedimiento',));
                }else{
                    $sw = false;
                    $mpdf->SetJS('print("No se han llenado todos los datos del reporte. Verifica que todos los datos estén ingresados.");');
                }                              
            }else if($parametro->Parametro == 'SOLIDOS SEDIMENTABLES (S.S)'){
                $horizontal = false;                
                $data = DB::table('ViewLoteDetalleSolidos')->where('Id_lote', $id_lote)->get();

                if(!is_null($data)){                                                           
                    $htmlCaptura = view('exports.laboratorio.fq.ss.capturaBody', compact('textoProcedimiento',));
                }else{
                    $sw = false;
                    $mpdf->SetJS('print("No se han llenado todos los datos del reporte. Verifica que todos los datos estén ingresados.");');
                }
            }else if($parametro->Parametro == 'SOLIDOS SUSPENDIDOS FIJOS (SSF)'){ //POR REVISAR EN LA TABLA DE DATOS
                $horizontal = false;                
                $data = DB::table('ViewLoteDetalleSolidos')->where('Id_lote', $id_lote)->get();

                if(!is_null($data)){                                                           
                    $htmlCaptura = view('exports.laboratorio.fq.ssf.capturaBody', compact('textoProcedimiento',));
                }else{
                    $sw = false;
                    $mpdf->SetJS('print("No se han llenado todos los datos del reporte. Verifica que todos los datos estén ingresados.");');
                }                                  
            }else if($parametro->Parametro == 'SOLIDOS SUSPENDIDOS TOTALES (SST)'){
                $horizontal = true;                
                $data = DB::table('ViewLoteDetalleSolidos')->where('Id_lote', $id_lote)->get();

                if(!is_null($data)){                                                           
                    $htmlCaptura = view('exports.laboratorio.fq.sst.capturaBody', compact('textoProcedimiento',));
                }else{
                    $sw = false;
                    $mpdf->SetJS('print("No se han llenado todos los datos del reporte. Verifica que todos los datos estén ingresados.");');
                }                                  
            }else if($parametro->Parametro == 'SOLIDOS SUSPENDIDOS VOLÁTILES (SSV)'){
                $horizontal = true;
                $data = DB::table('ViewLoteDetalleSolidos')->where('Id_lote', $id_lote)->get();

                if(!is_null($data)){                                                           
                    $htmlCaptura = view('exports.laboratorio.fq.ssv.capturaBody', compact('textoProcedimiento',));
                }else{
                    $sw = false;
                    $mpdf->SetJS('print("No se han llenado todos los datos del reporte. Verifica que todos los datos estén ingresados.");');
                }                                  
            }else if($parametro->Parametro == 'SOLIDOS TOTALES (ST)'){
                $horizontal = true;                
                $data = DB::table('ViewLoteDetalleSolidos')->where('Id_lote', $id_lote)->get();

                if(!is_null($data)){                                                           
                    $htmlCaptura = view('exports.laboratorio.fq.st.capturaBody', compact('textoProcedimiento',));
                }else{
                    $sw = false;
                    $mpdf->SetJS('print("No se han llenado todos los datos del reporte. Verifica que todos los datos estén ingresados.");');
                }                                  
            }else if($parametro->Parametro == 'SOLIDOS TOTALES FIJOS (STF)'){
                $horizontal = false;                
                $data = DB::table('ViewLoteDetalleSolidos')->where('Id_lote', $id_lote)->get();

                if(!is_null($data)){                                                           
                    $htmlCaptura = view('exports.laboratorio.fq.stf.capturaBody', compact('textoProcedimiento',));
                }else{
                    $sw = false;
                    $mpdf->SetJS('print("No se han llenado todos los datos del reporte. Verifica que todos los datos estén ingresados.");');
                }                                  
            }else if($parametro->Parametro == 'SOLIDOS TOTALES VOLATILES (STV)'){
                $horizontal = true;                
                $data = DB::table('ViewLoteDetalleSolidos')->where('Id_lote', $id_lote)->get();

                if(!is_null($data)){                                                           
                    $htmlCaptura = view('exports.laboratorio.fq.stv.capturaBody', compact('textoProcedimiento',));
                }else{
                    $sw = false;
                    $mpdf->SetJS('print("No se han llenado todos los datos del reporte. Verifica que todos los datos estén ingresados.");');
                }                                                  
            }
        }else{                        
            if($parametro->Parametro == 'SOLIDOS DISUELTOS FIJOS (SDF)'){
                $textoProcedimiento = ReportesFq::where('Id_reporte', 14)->first();
                $htmlCaptura = view('exports.laboratorio.fq.sdf.capturaBody', compact('textoProcedimiento'));
                $horizontal = false;
            }else if($parametro->Parametro == 'SOLIDOS DISUELTOS TOTALES (SDT)'){
                $textoProcedimiento = ReportesFq::where('Id_reporte', 15)->first();
                $htmlCaptura = view('exports.laboratorio.fq.sdt.capturaBody', compact('textoProcedimiento'));
                $horizontal = false;
            }else if($parametro->Parametro == 'SOLIDOS DISUELTOS VOLÁTILES (SDV)'){
                $textoProcedimiento = ReportesFq::where('Id_reporte', 16)->first();
                $htmlCaptura = view('exports.laboratorio.fq.sdv.capturaBody', compact('textoProcedimiento'));
                $horizontal = false;
            }else if($parametro->Parametro == 'SOLIDOS SEDIMENTABLES (S.S)'){
                $textoProcedimiento = ReportesFq::where('Id_reporte', 17)->first();
                $htmlCaptura = view('exports.laboratorio.fq.ss.capturaBody', compact('textoProcedimiento'));
                $horizontal = false;
            }else if($parametro->Parametro == 'SOLIDOS SUSPENDIDOS FIJOS (SSF)'){
                $textoProcedimiento = ReportesFq::where('Id_reporte', 18)->first();
                $htmlCaptura = view('exports.laboratorio.fq.ssf.capturaBody', compact('textoProcedimiento'));
                $horizontal = false;
            }else if($parametro->Parametro == 'SOLIDOS SUSPENDIDOS TOTALES (SST)'){
                $textoProcedimiento = ReportesFq::where('Id_reporte', 19)->first();
                $htmlCaptura = view('exports.laboratorio.fq.sst.capturaBody', compact('textoProcedimiento'));
                $horizontal = true;
            }else if($parametro->Parametro == 'SOLIDOS SUSPENDIDOS VOLÁTILES (SSV)'){
                $textoProcedimiento = ReportesFq::where('Id_reporte', 20)->first();
                $htmlCaptura = view('exports.laboratorio.fq.ssv.capturaBody', compact('textoProcedimiento'));
                $horizontal = true;
            }else if($parametro->Parametro == 'SOLIDOS TOTALES (ST)'){
                $textoProcedimiento = ReportesFq::where('Id_reporte', 21)->first();
                $htmlCaptura = view('exports.laboratorio.fq.st.capturaBody', compact('textoProcedimiento'));
                $horizontal = true;
            }else if($parametro->Parametro == 'SOLIDOS TOTALES FIJOS (STF)'){
                $textoProcedimiento = ReportesFq::where('Id_reporte', 22)->first();
                $htmlCaptura = view('exports.laboratorio.fq.stf.capturaBody', compact('textoProcedimiento'));
                $horizontal = false;
            }else if($parametro->Parametro == 'SOLIDOS TOTALES VOLATILES (STV)'){
                $textoProcedimiento = ReportesFq::where('Id_reporte', 23)->first();
                $htmlCaptura = view('exports.laboratorio.fq.stv.capturaBody', compact('textoProcedimiento'));
                $horizontal = true;
            }
 
            if($horizontal === false){
                $mpdf->SetJS('print("Valores predeterminados para el reporte. Rellena este campo.");');
            }else{
                $mpdfH->SetJS('print("Valores predeterminados para el reporte. Rellena este campo.");');
            }                         
        }   

        //HEADER-FOOTER******************************************************************************************************************         
        if($parametro->Parametro == 'SOLIDOS DISUELTOS FIJOS (SDF)'){
            $htmlHeader = view('exports.laboratorio.fq.ga.sdf.capturaHeader', compact('fechaConFormato'));
            $htmlFooter = view('exports.laboratorio.fq.ga.sdf.capturaFooter', compact('usuario', 'firma'));
        }else if($parametro->Parametro == 'SOLIDOS DISUELTOS TOTALES (SDT)'){ //POR REVISAR EN LA TABLA DE DATOS
            $htmlHeader = view('exports.laboratorio.fq.ga.sdt.capturaHeader', compact('fechaConFormato'));
            $htmlFooter = view('exports.laboratorio.fq.ga.sdt.capturaFooter', compact('usuario', 'firma'));
        }else if($parametro->Parametro == 'SOLIDOS DISUELTOS VOLÁTILES (SDV)'){
            $htmlHeader = view('exports.laboratorio.fq.ga.sdv.capturaHeader', compact('fechaConFormato'));
            $htmlFooter = view('exports.laboratorio.fq.ga.sdv.capturaFooter', compact('usuario', 'firma'));
        }else if($parametro->Parametro == 'SOLIDOS SEDIMENTABLES (S.S)'){
            $htmlHeader = view('exports.laboratorio.fq.ga.ss.capturaHeader', compact('fechaConFormato'));
            $htmlFooter = view('exports.laboratorio.fq.ga.ss.capturaFooter', compact('usuario', 'firma'));
        }else if($parametro->Parametro == 'SOLIDOS SUSPENDIDOS FIJOS (SSF)'){ //POR REVISAR EN LA TABLA DE DATOS
            $htmlHeader = view('exports.laboratorio.fq.ga.ssf.capturaHeader', compact('fechaConFormato'));
            $htmlFooter = view('exports.laboratorio.fq.ga.ssf.capturaFooter', compact('usuario', 'firma'));
        }else if($parametro->Parametro == 'SOLIDOS SUSPENDIDOS TOTALES (SST)'){
            $htmlHeader = view('exports.laboratorio.fq.ga.sst.capturaHeader', compact('fechaConFormato'));
            $htmlFooter = view('exports.laboratorio.fq.ga.sst.capturaFooter', compact('usuario', 'firma'));
        }else if($parametro->Parametro == 'SOLIDOS SUSPENDIDOS VOLÁTILES (SSV)'){
            $htmlHeader = view('exports.laboratorio.fq.ga.ssv.capturaHeader', compact('fechaConFormato'));
            $htmlFooter = view('exports.laboratorio.fq.ga.ssv.capturaFooter', compact('usuario', 'firma'));
        }else if($parametro->Parametro == 'SOLIDOS TOTALES (ST)'){
            $htmlHeader = view('exports.laboratorio.fq.ga.st.capturaHeader', compact('fechaConFormato'));
            $htmlFooter = view('exports.laboratorio.fq.ga.st.capturaFooter', compact('usuario', 'firma'));
        }else if($parametro->Parametro == 'SOLIDOS TOTALES FIJOS (STF)'){
            $htmlHeader = view('exports.laboratorio.fq.ga.stf.capturaHeader', compact('fechaConFormato'));
            $htmlFooter = view('exports.laboratorio.fq.ga.stf.capturaFooter', compact('usuario', 'firma'));
        }else if($parametro->Parametro == 'SOLIDOS TOTALES VOLATILES (STV)'){                      
            $htmlHeader = view('exports.laboratorio.fq.ga.stv.capturaHeader', compact('fechaConFormato'));
            $htmlFooter = view('exports.laboratorio.fq.ga.stv.capturaFooter', compact('usuario', 'firma'));
        }                                  

        if($horizontal === false){            
            $mpdf->setHeader("{PAGENO}<br><br>" . $htmlHeader);
            $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
            $mpdf->WriteHTML($htmlCaptura);                           
        }else{ //Es horizontal la bitácora
            $mpdfH->setHeader("{PAGENO}<br><br>" . $htmlHeader);
            $mpdfH->SetHTMLFooter($htmlFooter, 'O', 'E');
            $mpdfH->WriteHTML($htmlCaptura);            
        }                  
 
        if($horizontal === false){            
            $mpdf->CSSselectMedia = 'mpdf';
            $mpdf->Output();

        }else{  //Es vertical la bitácora
            $mpdfH->CSSselectMedia = 'mpdf';
            $mpdfH->Output();
        }        
    }
    
     //FUNCIÓN PARA GENERAR EL DOCUMENTO PDF; DE MOMENTO NO RECIBE UN IDLOTE
     public function exportPdfCapturaVolumetria($idLote)
     {
          //Var. de prueba temporal
          //$idLote = 11;         
         $sw = true;
 
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
         //  $mpdf->SetWatermarkImage( 
         //      asset('HojaMembretada2.png'),
         //      1,
         //      array(215, 280),
         //      array(0, 0),
         //  ); 
  
         //  $mpdf->showWatermarkImage = true;         
 
          $id_lote = $idLote;
          $semaforo = true;
  
          //Recupera el nombre de usuario y firma
          $usuario = DB::table('users')->where('id', auth()->user()->id)->first();
          $firma = $usuario->firma;
  
          //Formatea la fecha
          $fechaAnalisis = DB::table('ViewLoteAnalisis')->where('Id_lote', $id_lote)->first();
          if(!is_null($fechaAnalisis)){
              $fechaConFormato = date("d/m/Y", strtotime($fechaAnalisis->Fecha));
          }else{
              $fechaAnalisis = DB::table('ViewLoteAnalisis')->where('Id_lote', 0)->first();
              $fechaConFormato = date("d/m/Y", strtotime($fechaAnalisis->Fecha));
              echo '<script> alert("Valores predeterminados para la fecha de análisis. Rellena este campo.") </script>';
          }   
          
          //Recupera el parámetro que se está utilizando
          $parametro = DB::table('ViewLoteDetalleVolumetria')->where('Id_lote', $id_lote)->first();
  
         //Recupera el texto dinámico Procedimientos de la tabla reportes****************************************************
         $textoProcedimiento = ReportesFq::where('Id_lote', $id_lote)->first();
         if(!is_null($textoProcedimiento)){
            //Hoja1            
             if($parametro->Parametro == 'DEMANDA QUIMICA DE OXIGENO ALTA (DQO)' || $parametro->Parametro == 'DEMANDA QUÍMICA DE OXIGENO SOLUBLE ALTA (DQO)'){                
                $data = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $id_lote)->get();
 
                if(!is_null($data)){                         
                    $curva = CurvaConstantes::where('Id_lote', $id_lote)->first();
                    $dataLength = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $id_lote)->count();               
                    $htmlCaptura = view('exports.laboratorio.fq.volumetria.dqoA.capturaBody', compact('textoProcedimiento', 'data', 'dataLength', 'curva'));
                }else{
                    $sw = false;
                    $mpdf->SetJS('print("No se han llenado todos los datos del reporte. Verifica que todos los datos estén ingresados.");');
                }
             }else if($parametro->Parametro == 'DEMANDA QUIMICA DE OXIGENO BAJAS (DQO)' || $parametro->Parametro == 'DEMANDA QUÍMICA DE OXIGENO SOLUBLE BAJA (DQO)'){                
                $data = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $id_lote)->get();
 
                if(!is_null($data)){                         
                    $curva = CurvaConstantes::where('Id_lote', $id_lote)->first();
                    $dataLength = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $id_lote)->count();               
                    $htmlCaptura = view('exports.laboratorio.fq.volumetria.dqoB.capturaBody', compact('textoProcedimiento', 'data', 'dataLength', 'curva'));
                }else{
                    $sw = false;
                    $mpdf->SetJS('print("No se han llenado todos los datos del reporte. Verifica que todos los datos estén ingresados.");');
                }
             }else if($parametro->Parametro == 'Nitrógeno Amoniacal'){                 
                 $data = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $id_lote)->get();
 
                 if(!is_null($data)){                         
                     $curva = CurvaConstantes::where('Id_lote', $id_lote)->first();
                     $dataLength = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $id_lote)->count();               
                     $htmlCaptura = view('exports.laboratorio.fq.volumetria.nitrogenoA.capturaBody', compact('textoProcedimiento', 'data', 'dataLength', 'curva'));
                 }else{
                     $sw = false;
                     $mpdf->SetJS('print("No se han llenado todos los datos del reporte. Verifica que todos los datos estén ingresados.");');
                 }
             }else if($parametro->Parametro == 'Nitrógeno Orgánico'){ //POR REVISAR EN LA TABLA DE DATOS                                  
                 $data = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $id_lote)->get();

                 if(!is_null($data)){                         
                    $curva = CurvaConstantes::where('Id_lote', $id_lote)->first();
                    $dataLength = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $id_lote)->count();               
                    $htmlCaptura = view('exports.laboratorio.fq.volumetria.nitrogenoO.capturaBody', compact('textoProcedimiento', 'data', 'dataLength', 'curva'));
                }else{
                    $sw = false;
                    $mpdf->SetJS('print("No se han llenado todos los datos del reporte. Verifica que todos los datos estén ingresados.");');
                }
             }
         }else{                        
             if($parametro->Parametro == 'DEMANDA QUIMICA DE OXIGENO ALTA (DQO)' || $parametro->Parametro == 'DEMANDA QUÍMICA DE OXIGENO SOLUBLE ALTA (DQO)'){                
                $data = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $id_lote)->get();
 
                if(!is_null($data)){
                    $curva = CurvaConstantes::where('Id_lote', $id_lote)->first();                    
                    $dataLength = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $id_lote)->count();
                    $textoProcedimiento = ReportesFq::where('Id_reporte', 25)->first();
                    $htmlCaptura = view('exports.laboratorio.fq.volumetria.dqoA.capturaBody', compact('textoProcedimiento', 'data', 'dataLength', 'curva'));
                }else{
                    $sw = false;
                    $mpdf->SetJS('No se han llenado todos los datos del reporte. Verifica que todos los datos estén ingresados.");');
                }                  
             }else if($parametro->Parametro == 'DEMANDA QUIMICA DE OXIGENO BAJAS (DQO)' || $parametro->Parametro == 'DEMANDA QUÍMICA DE OXIGENO SOLUBLE BAJA (DQO)'){                
                $data = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $id_lote)->get();
 
                if(!is_null($data)){
                    $curva = CurvaConstantes::where('Id_lote', $id_lote)->first();                    
                    $dataLength = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $id_lote)->count();
                    $textoProcedimiento = ReportesFq::where('Id_reporte', 26)->first();
                    $htmlCaptura = view('exports.laboratorio.fq.volumetria.dqoB.capturaBody', compact('textoProcedimiento', 'data', 'dataLength', 'curva'));
                }else{
                    $sw = false;
                    $mpdf->SetJS('No se han llenado todos los datos del reporte. Verifica que todos los datos estén ingresados.");');
                }
             }else if($parametro->Parametro == 'Nitrógeno Amoniacal'){                 
                 $data = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $id_lote)->get();
 
                 if(!is_null($data)){
                     $curva = CurvaConstantes::where('Id_lote', $id_lote)->first();                    
                     $dataLength = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $id_lote)->count();
                     $textoProcedimiento = ReportesFq::where('Id_reporte', 27)->first();
                     $htmlCaptura = view('exports.laboratorio.fq.volumetria.nitrogenoA.capturaBody', compact('textoProcedimiento', 'data', 'dataLength', 'curva'));
                 }else{
                     $sw = false;
                     $mpdf->SetJS('No se han llenado todos los datos del reporte. Verifica que todos los datos estén ingresados.");');
                 }                                
             }else if($parametro->Parametro == 'Nitrógeno Orgánico'){ //POR REVISAR EN LA TABLA DE DATOS                
                $data = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $id_lote)->get();
 
                if(!is_null($data)){
                    $curva = CurvaConstantes::where('Id_lote', $id_lote)->first();                    
                    $dataLength = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $id_lote)->count();
                    $textoProcedimiento = ReportesFq::where('Id_reporte', 28)->first();
                    $htmlCaptura = view('exports.laboratorio.fq.volumetria.nitrogenoO.capturaBody', compact('textoProcedimiento', 'data', 'dataLength', 'curva'));
                }else{
                    $sw = false;
                    $mpdf->SetJS('No se han llenado todos los datos del reporte. Verifica que todos los datos estén ingresados.");');
                }                
             }
         }   
 
         //HEADER-FOOTER******************************************************************************************************************         
         if($sw === true){        
            if($parametro->Parametro == 'DEMANDA QUIMICA DE OXIGENO ALTA (DQO)' || $parametro->Parametro == 'DEMANDA QUÍMICA DE OXIGENO SOLUBLE ALTA (DQO)'){
                $htmlHeader = view('exports.laboratorio.fq.volumetria.dqoA.capturaHeader', compact('fechaConFormato'));
                $htmlFooter = view('exports.laboratorio.fq.volumetria.dqoA.capturaFooter', compact('usuario', 'firma'));
            }else if($parametro->Parametro == 'DEMANDA QUIMICA DE OXIGENO BAJAS (DQO)' || $parametro->Parametro == 'DEMANDA QUÍMICA DE OXIGENO SOLUBLE BAJA (DQO)'){
                $htmlHeader = view('exports.laboratorio.fq.volumetria.dqoB.capturaHeader', compact('fechaConFormato'));
                $htmlFooter = view('exports.laboratorio.fq.volumetria.dqoB.capturaFooter', compact('usuario', 'firma'));
            }else if($parametro->Parametro == 'Nitrógeno Amoniacal'){
                $htmlHeader = view('exports.laboratorio.fq.volumetria.nitrogenoA.capturaHeader', compact('fechaConFormato'));
                $htmlFooter = view('exports.laboratorio.fq.volumetria.nitrogenoA.capturaFooter', compact('usuario', 'firma'));
            }else if($parametro->Parametro == 'Nitrógeno Orgánico'){ //POR REVISAR EN LA TABLA DE DATOS
                $htmlHeader = view('exports.laboratorio.fq.volumetria.nitrogenoO.capturaHeader', compact('fechaConFormato'));
                $htmlFooter = view('exports.laboratorio.fq.volumetria.nitrogenoO.capturaFooter', compact('usuario', 'firma'));
            }
         }                                  
 
        if($sw === true){            
           $mpdf->setHeader("{PAGENO}<br><br>" . $htmlHeader);
           $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
           $mpdf->WriteHTML($htmlCaptura);            
        }
  
        if($sw === true){            
           $mpdf->CSSselectMedia = 'mpdf';
           $mpdf->Output();
        }        
     }
}