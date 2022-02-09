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
use App\Models\LoteDetalleCloro;
use App\Models\LoteDetalleDqo;
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

class VolController extends Controller
{
  

    // todo ******************* Inicio de lote ************************
    public function loteVol()
    {
        //* Tipo de formulas  
        $parametro = DB::table('ViewParametros')
        ->orWhere('Id_area',14)
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
        $model = DB::table('ViewSolicitudParametros')
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
        $paraModel = Parametro::find($loteModel->Id_tecnica);
        $model = array();
        switch ($loteModel->Id_tecnica) {
            case 7: //todo DQO
                $model = DB::table('ViewLoteDetalleDqo')->where('Id_lote', $request->idLote)->get();
                break;
            case 295: //todo CLORO RESIDUAL LIBRE
                $model = DB::table('ViewLoteDetalleCloro')->where('Id_lote', $request->idLote)->get();
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
    public function asignarMuestraLoteVol(Request $request)
    {
        $sw = false;
        $loteModel = LoteAnalisis::where('Id_lote', $request->idLote)->first();
        $paraModel = Parametro::find($loteModel->Id_tecnica);
        switch ($loteModel->Id_tecnica) {
            case 7: //todo DQO
                $model = LoteDetalleDqo::create([
                    'Id_lote' => $request->idLote,
                    'Id_analisis' => $request->idAnalisis,
                    'Id_parametro' => $loteModel->Id_tecnica,
                    'Id_control' => 1,
                ]);
                $detModel = LoteDetalleDqo::where('Id_lote',$request->idLote)->get();
                $sw = true;
                break;
            case 295: //todo CLORO RESIDUAL LIBRE
                $model = LoteDetalleCloro::create([
                    'Id_lote' => $request->idLote,
                    'Id_analisis' => $request->idAnalisis,
                    'Id_parametro' => $loteModel->Id_tecnica,
                    'Id_control' => 1,
                ]);
                $detModel = LoteDetalleCloro::where('Id_lote',$request->idLote)->get();
                $sw = true;
                break;
            default:
          
                break;
        }
      
        $solModel = SolicitudParametro::find($request->idSol);
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



    //todo *******************************************
    //todo Inicio Seccion de Volumetria
    //todo *******************************************
    public function capturaVolumetria()
    {
        $parametro = DB::table("ViewParametros")->where('Id_area', 14)->get();
        // $formulas = DB::table('ViewTipoFormula')->where('Id_area',2)->get();
        // var_dump($parametro); 
        return view('laboratorio.fq.capturaVolumetria', compact('parametro')); 
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
    public function operacionVolumetria(Request $request)
    {
        $parametro = Parametro::where('Id_parametro', $request->idParametro)->first();

        switch ($parametro->Id_parametro)
        {
            case 6:
            // case 77:
            // case 74:
            // case 76:
            // case 73:
            // case 75:
                $res1 = ($request->CA - $request->B);
                $res2 = ($res1 * $request->C); 
                $res3 = ($res2 * $request->D);
                $res = ($res3 / $request->E);

            break;

                
        }
        $data = array(
            'id' => $parametro->Id_parametro, 
            'res' => $res,
            'ca' => $request->CA,
            'b' => $request->B,
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
        switch ($request->formulaTipo) {
            case 7: //todo DQO
                $detalle = DB::table('ViewLoteDetalleDqo')->where('Id_lote', $request->idLote)->get(); // Asi se hara con las otras
                break;
            case 295: //todo CLORO RESIDUAL LIBRE 
                $detalle = DB::table('ViewLoteDetalleCloro')->where('Id_lote', $request->idLote)->get(); // Asi se hara con las otras
                break;
            default:
                # code...
                break;
        }
        $data = array(
            'detalle' => $detalle,
        );
        return response()->json($data);
    }
    public function getDetalleVol(Request $request)
    {
        switch ($request->formulaTipo) {
            case 7: //todo DQO
                $model = DB::table("ViewLoteDetalleDqo")->where('Id_lote',$request->idDetalle)->first();
                break;
            case 295: //todo CLORO RESIDUAL LIBRE 
                $model = DB::table('ViewLoteDetalleCloro')->where('Id_lote', $request->idLote)->get(); // Asi se hara con las otras
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
          if(!is_null($fechaAnalisis)){
              $fechaConFormato = date("d/m/Y", strtotime($fechaAnalisis->Fecha));
          }else{
              $fechaAnalisis = DB::table('ViewLoteAnalisis')->where('Id_lote', 0)->first();
              $fechaConFormato = date("d/m/Y", strtotime($fechaAnalisis->Fecha));
              echo '<script> alert("Valores predeterminados para la fecha de análisis. Rellena este campo.") </script>';
          }   
          
        //Recupera el parámetro que se está utilizando
        $parametro = DB::table('ViewLoteDetalleCloro')->where('Id_lote', $id_lote)->first();
        if(is_null($parametro)){
            $parametro = DB::table('ViewLoteDetalleDqo')->where('Id_lote', $id_lote)->first();            
        }
  
         //Recupera el texto dinámico Procedimientos de la tabla reportes****************************************************
         $textProcedimiento = ReportesFq::where('Id_lote', $id_lote)->first();
         if(!is_null($textProcedimiento)){
            //Hoja1    
            $proced = true;        
            if($parametro->Parametro == 'Demanda Química de Oxigeno  (DQO))'){
                $data = DB::table('ViewLoteDetalleDqo')->where('Id_lote', $id_lote)->get();
 
                if(!is_null($data)){                                             
                    $dataLength = DB::table('ViewLoteDetalleDqo')->where('Id_lote', $id_lote)->count();

                    $separador = "Valoración";
                    $textoProcedimiento = explode($separador, $textProcedimiento->Texto);

                    $htmlCaptura = view('exports.laboratorio.fq.volumetria.dqo.capturaBody', compact('textoProcedimiento', 'data', 'dataLength'));
                }else{
                    $sw = false;
                    $mpdf->SetJS('print("No se han llenado datos en el reporte. Verifica que todos los datos estén ingresados.");');
                }
            }else if($parametro->Parametro == 'DEMANDA QUIMICA DE OXIGENO ALTA (DQO)' || $parametro->Parametro == 'DEMANDA QUÍMICA DE OXIGENO SOLUBLE ALTA (DQO)'){                
                $data = DB::table('ViewLoteDetalleDqo')->where('Id_lote', $id_lote)->get();
 
                if(!is_null($data)){                                             
                    $dataLength = DB::table('ViewLoteDetalleDqo')->where('Id_lote', $id_lote)->count();        
                    
                    $separador = "Valoración";
                    $textoProcedimiento = explode($separador, $textProcedimiento->Texto);

                    $htmlCaptura = view('exports.laboratorio.fq.volumetria.dqoA.capturaBody', compact('textoProcedimiento', 'data', 'dataLength'));
                }else{
                    $sw = false;
                    $mpdf->SetJS('print("No se han llenado datos en el reporte. Verifica que todos los datos estén ingresados.");');
                }
             }else if($parametro->Parametro == 'DEMANDA QUIMICA DE OXIGENO BAJAS (DQO)' || $parametro->Parametro == 'DEMANDA QUÍMICA DE OXIGENO SOLUBLE BAJA (DQO)'){                
                $data = DB::table('ViewLoteDetalleDqo')->where('Id_lote', $id_lote)->get();
 
                if(!is_null($data)){                                             
                    $dataLength = DB::table('ViewLoteDetalleDqo')->where('Id_lote', $id_lote)->count();

                    $separador = "Valoración";
                    $textoProcedimiento = explode($separador, $textProcedimiento->Texto);

                    $htmlCaptura = view('exports.laboratorio.fq.volumetria.dqoB.capturaBody', compact('textoProcedimiento', 'data', 'dataLength'));
                }else{
                    $sw = false;
                    $mpdf->SetJS('print("No se han llenado llenado datos en el reporte. Verifica que todos los datos estén ingresados.");');
                }
            }else if($parametro->Parametro == 'Nitrógeno Total *'){                 
                $data = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $id_lote)->get();

                if(!is_null($data)){                                             
                    $dataLength = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $id_lote)->count();

                    $htmlCaptura = view('exports.laboratorio.fq.volumetria.nitrogenoTotal.capturaBody', compact('textoProcedimiento', 'data', 'dataLength'));
                }else{
                    $sw = false;
                    $mpdf->SetJS('print("No se han llenado datos en el reporte. Verifica que todos los datos estén ingresados.");');
                }
            }else if($parametro->Parametro == 'Nitrógeno Amoniacal'){                 
                 $data = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $id_lote)->get();
 
                 if(!is_null($data)){                                              
                     $dataLength = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $id_lote)->count();               

                     $htmlCaptura = view('exports.laboratorio.fq.volumetria.nitrogenoA.capturaBody', compact('textoProcedimiento', 'data', 'dataLength'));
                 }else{
                     $sw = false;
                     $mpdf->SetJS('print("No se han llenado datos en el reporte. Verifica que todos los datos estén ingresados.");');
                 }
             }else if($parametro->Parametro == 'Nitrógeno Orgánico'){ //POR REVISAR EN LA TABLA DE DATOS                                  
                 $data = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $id_lote)->get();

                 if(!is_null($data)){                                             
                    $dataLength = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $id_lote)->count();

                    $htmlCaptura = view('exports.laboratorio.fq.volumetria.nitrogenoO.capturaBody', compact('textoProcedimiento', 'data', 'dataLength'));
                }else{
                    $sw = false;
                    $mpdf->SetJS('print("No se han llenado datos en el reporte. Verifica que todos los datos estén ingresados.");');
                }
            }else if($parametro->Parametro == 'CLORO RESIDUAL LIBRE'){
                $data = DB::table('ViewLoteDetalleCloro')->where('Id_lote', $id_lote)->get();

                if(!is_null($data)){
                    $dataLength = DB::table('ViewLoteDetalleCloro')->where('Id_lote', $id_lote)->count();
                                     
                    $separador = "Valoración";
                    $textoProcedimiento = explode($separador, $textProcedimiento->Texto);

                    $htmlCaptura = view('exports.laboratorio.fq.volumetria.cloroR.capturaBody', compact('textoProcedimiento', 'data', 'dataLength'));
                }else{
                    $sw = false;
                    $mpdf->SetJS('print("No se han llenado datos en el reporte. Verifica que todos los datos estén ingresados.");');
                }
            }
         }else{   //---------------                     
            if($parametro->Parametro == 'Demanda Química de Oxigeno  (DQO))'){
                $data = DB::table('ViewLoteDetalleDqo')->where('Id_lote', $id_lote)->get();
 
                if(!is_null($data)){                    
                    $dataLength = DB::table('ViewLoteDetalleDqo')->where('Id_lote', $id_lote)->count();

                    $textProcedimiento = ReportesFq::where('Id_reporte', 29)->first();
                    $separador = "Valoración";
                    $textoProcedimiento = explode($separador, $textProcedimiento->Texto);
                    
                    $htmlCaptura = view('exports.laboratorio.fq.volumetria.dqo.capturaBody', compact('textoProcedimiento', 'data', 'dataLength'));
                }else{
                    $sw = false;
                    $mpdf->SetJS('No se han llenado datos en el reporte. Verifica que todos los datos estén ingresados.");');
                }                
            }else if($parametro->Parametro == 'DEMANDA QUIMICA DE OXIGENO ALTA (DQO)' || $parametro->Parametro == 'DEMANDA QUÍMICA DE OXIGENO SOLUBLE ALTA (DQO)'){                
                $data = DB::table('ViewLoteDetalleDqo')->where('Id_lote', $id_lote)->get();
 
                if(!is_null($data)){                    
                    $dataLength = DB::table('ViewLoteDetalleDqo')->where('Id_lote', $id_lote)->count();

                    $textProcedimiento = ReportesFq::where('Id_reporte', 25)->first();                    
                    $separador = "Valoración";
                    $textoProcedimiento = explode($separador, $textProcedimiento->Texto);

                    $htmlCaptura = view('exports.laboratorio.fq.volumetria.dqoA.capturaBody', compact('textoProcedimiento', 'data', 'dataLength'));
                }else{
                    $sw = false;
                    $mpdf->SetJS('No se han llenado datos en el reporte. Verifica que todos los datos estén ingresados.");');
                }                  
             }else if($parametro->Parametro == 'DEMANDA QUIMICA DE OXIGENO BAJAS (DQO)' || $parametro->Parametro == 'DEMANDA QUÍMICA DE OXIGENO SOLUBLE BAJA (DQO)'){                
                $data = DB::table('ViewLoteDetalleDqo')->where('Id_lote', $id_lote)->get();
 
                if(!is_null($data)){                    
                    $dataLength = DB::table('ViewLoteDetalleDqo')->where('Id_lote', $id_lote)->count();
                    
                    $textProcedimiento = ReportesFq::where('Id_reporte', 26)->first();                    
                    $separador = "Valoración";
                    $textoProcedimiento = explode($separador, $textProcedimiento->Texto);

                    $htmlCaptura = view('exports.laboratorio.fq.volumetria.dqoB.capturaBody', compact('textoProcedimiento', 'data', 'dataLength'));
                }else{
                    $sw = false;
                    $mpdf->SetJS('No se han llenado datos en el reporte. Verifica que todos los datos estén ingresados.");');
                }
             }else if($parametro->Parametro == 'Nitrógeno Total *'){
                $data = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $id_lote)->get();

                if(!is_null($data)){                    
                    $dataLength = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $id_lote)->count();
                    $textoProcedimiento = ReportesFq::where('Id_reporte', 30)->first();

                    $htmlCaptura = view('exports.laboratorio.fq.volumetria.nitrogenoTotal.capturaBody', compact('textoProcedimiento', 'data', 'dataLength'));
                }else{
                    $sw = false;
                    $mpdf->SetJS('No se han llenado datos en el reporte. Verifica que todos los datos estén ingresados.");');
                }                                
            }else if($parametro->Parametro == 'Nitrógeno Amoniacal'){                 
                 $data = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $id_lote)->get();
 
                 if(!is_null($data)){                     
                     $dataLength = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $id_lote)->count();

                     $textoProcedimiento = ReportesFq::where('Id_reporte', 27)->first();

                     $htmlCaptura = view('exports.laboratorio.fq.volumetria.nitrogenoA.capturaBody', compact('textoProcedimiento', 'data', 'dataLength'));
                 }else{
                     $sw = false;
                     $mpdf->SetJS('No se han llenado datos en el reporte. Verifica que todos los datos estén ingresados.");');
                 }                                
             }else if($parametro->Parametro == 'Nitrógeno Orgánico'){ //POR REVISAR EN LA TABLA DE DATOS                
                $data = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $id_lote)->get();
 
                if(!is_null($data)){                                       
                    $dataLength = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $id_lote)->count();

                    $textoProcedimiento = ReportesFq::where('Id_reporte', 28)->first();

                    $htmlCaptura = view('exports.laboratorio.fq.volumetria.nitrogenoO.capturaBody', compact('textoProcedimiento', 'data', 'dataLength', 'curva'));
                }else{
                    $sw = false;
                    $mpdf->SetJS('No se han llenado datos en el reporte. Verifica que todos los datos estén ingresados.");');
                }
            }else if($parametro->Parametro == 'CLORO RESIDUAL LIBRE'){
                $data = DB::table('ViewLoteDetalleCloro')->where('Id_lote', $id_lote)->get();

                if(!is_null($data)){
                    $dataLength = DB::table('ViewLoteDetalleCloro')->where('Id_lote', $id_lote)->count();
                    
                    $textProcedimiento = ReportesFq::where('Id_reporte', 24)->first();                                     
                    $separador = "Valoración";
                    $textoProcedimiento = explode($separador, $textProcedimiento->Texto);

                    $htmlCaptura = view('exports.laboratorio.fq.volumetria.cloroR.capturaBody', compact('textoProcedimiento', 'data', 'dataLength'));
                }else{
                    $sw = false;
                    $mpdf->SetJS('No se han llenado datos en el reporte. Verifica que todos los datos estén ingresados.");');
                }
            }
         }   
 
        //HEADER-FOOTER******************************************************************************************************************         
        if($sw === true){                    
            if($parametro->Parametro == 'Demanda Química de Oxigeno  (DQO))'){                
                $htmlHeader = view('exports.laboratorio.fq.volumetria.dqo.capturaHeader', compact('fechaConFormato'));
                $htmlFooter = view('exports.laboratorio.fq.volumetria.dqo.capturaFooter', compact('usuario', 'firma'));                
            }else if($parametro->Parametro == 'DEMANDA QUIMICA DE OXIGENO ALTA (DQO)' || $parametro->Parametro == 'DEMANDA QUÍMICA DE OXIGENO SOLUBLE ALTA (DQO)'){
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
            }else if($parametro->Parametro == 'CLORO RESIDUAL LIBRE'){                
                $htmlHeader = view('exports.laboratorio.fq.volumetria.cloroR.capturaHeader', compact('fechaConFormato'));
                $htmlFooter = view('exports.laboratorio.fq.volumetria.cloroR.capturaFooter', compact('usuario', 'firma'));
            }else if($parametro->Parametro == 'Nitrógeno Total *'){
                $htmlHeader = view('exports.laboratorio.fq.volumetria.nitrogenoTotal.capturaHeader', compact('fechaConFormato'));
                $htmlFooter = view('exports.laboratorio.fq.volumetria.nitrogenoTotal.capturaFooter', compact('usuario', 'firma'));
            }
        }                                  
 
        if($sw === true){            
           $mpdf->setHeader("{PAGENO}<br><br>" . $htmlHeader);
           $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
           $mpdf->WriteHTML($htmlCaptura);

           //Hoja 2
            $hoja2 = false;

            if($parametro->Parametro == 'Demanda Química de Oxigeno  (DQO))'){
                $mpdf->AddPage('', '', '', '', '', '', '', 35, 45, 6.5, '', '', '', '', '', -1, -1, -1, -1);

                $data = DB::table('ViewLoteDetalleDqo')->where('Id_lote', $id_lote)->get();
 
                if(!is_null($data)){                    
                    $dataLength = DB::table('ViewLoteDetalleDqo')->where('Id_lote', $id_lote)->count();

                    if($proced === true){
                        $separador = "Valoración";
                        $textoProcedimiento = explode($separador, $textProcedimiento->Texto);
                     }else{
                        $textProcedimiento = ReportesFq::where('Id_reporte', 29)->first();
                        $separador = "Valoración";
                        $textoProcedimiento = explode($separador, $textProcedimiento->Texto);
                    }
                    
                    $htmlCaptura1 = view('exports.laboratorio.fq.volumetria.dqo.capturaBody1', compact('textoProcedimiento', 'data', 'dataLength'));
                    $htmlHeader = view('exports.laboratorio.fq.volumetria.dqo.capturaHeader', compact('fechaConFormato'));
                    $htmlFooter = view('exports.laboratorio.fq.volumetria.dqo.capturaFooter', compact('usuario', 'firma'));                    
                }                              

               $hoja2 = true;
                
            }else if($parametro->Parametro == 'DEMANDA QUIMICA DE OXIGENO ALTA (DQO)' || $parametro->Parametro == 'DEMANDA QUÍMICA DE OXIGENO SOLUBLE ALTA (DQO)'){
                $mpdf->AddPage('', '', '', '', '', '', '', 35, 45, 6.5, '', '', '', '', '', -1, -1, -1, -1);

                $data = DB::table('ViewLoteDetalleDqo')->where('Id_lote', $id_lote)->get();
 
                if(!is_null($data)){                    
                    $dataLength = DB::table('ViewLoteDetalleDqo')->where('Id_lote', $id_lote)->count();

                    if($proced === true){
                        $separador = "Valoración";
                        $textoProcedimiento = explode($separador, $textProcedimiento->Texto);
                     }else{
                        $textProcedimiento = ReportesFq::where('Id_reporte', 25)->first();
                        $separador = "Valoración";
                        $textoProcedimiento = explode($separador, $textProcedimiento->Texto);
                    }
                    
                    $htmlCaptura1 = view('exports.laboratorio.fq.volumetria.dqoA.capturaBody1', compact('textoProcedimiento', 'data', 'dataLength'));
                    $htmlHeader = view('exports.laboratorio.fq.volumetria.dqoA.capturaHeader', compact('fechaConFormato'));
                    $htmlFooter = view('exports.laboratorio.fq.volumetria.dqoA.capturaFooter', compact('usuario', 'firma'));
                }                              

               $hoja2 = true;
            }else if($parametro->Parametro == 'DEMANDA QUIMICA DE OXIGENO BAJAS (DQO)' || $parametro->Parametro == 'DEMANDA QUÍMICA DE OXIGENO SOLUBLE BAJA (DQO)'){
                $mpdf->AddPage('', '', '', '', '', '', '', 35, 45, 6.5, '', '', '', '', '', -1, -1, -1, -1);

                $data = DB::table('ViewLoteDetalleDqo')->where('Id_lote', $id_lote)->get();
 
                if(!is_null($data)){                    
                    $dataLength = DB::table('ViewLoteDetalleDqo')->where('Id_lote', $id_lote)->count();

                    if($proced === true){
                        $separador = "Valoración";
                        $textoProcedimiento = explode($separador, $textProcedimiento->Texto);
                     }else{
                        $textProcedimiento = ReportesFq::where('Id_reporte', 26)->first();
                        $separador = "Valoración";
                        $textoProcedimiento = explode($separador, $textProcedimiento->Texto);
                    }
                    
                    $htmlCaptura1 = view('exports.laboratorio.fq.volumetria.dqoB.capturaBody1', compact('textoProcedimiento', 'data', 'dataLength'));
                    $htmlHeader = view('exports.laboratorio.fq.volumetria.dqoB.capturaHeader', compact('fechaConFormato'));
                    $htmlFooter = view('exports.laboratorio.fq.volumetria.dqoB.capturaFooter', compact('usuario', 'firma'));
                }                              

               $hoja2 = true;
            }else if($parametro->Parametro == 'Nitrógeno Total *'){
                $mpdf->AddPage('', '', '', '', '', '', '', 35, 45, 6.5, '', '', '', '', '', -1, -1, -1, -1);

                $data = DB::table('ViewLoteDetalleDqo')->where('Id_lote', $id_lote)->get();
 
                if(!is_null($data)){                    
                    $dataLength = DB::table('ViewLoteDetalleDqo')->where('Id_lote', $id_lote)->count();

                    if($proced === true){
                        $separador = "Valoración";
                        $textoProcedimiento = explode($separador, $textProcedimiento->Texto);
                     }else{
                        $textProcedimiento = ReportesFq::where('Id_reporte', 30)->first();
                        $separador = "Valoración";
                        $textoProcedimiento = explode($separador, $textProcedimiento->Texto);
                    }
                    
                    $htmlCaptura1 = view('exports.laboratorio.fq.volumetria.nitrogenoTotal.capturaBody1', compact('textoProcedimiento', 'data', 'dataLength'));
                    $htmlHeader = view('exports.laboratorio.fq.volumetria.nitrogenoTotal.capturaHeader', compact('fechaConFormato'));
                    $htmlFooter = view('exports.laboratorio.fq.volumetria.nitrogenoTotal.capturaFooter', compact('usuario', 'firma'));
                }                              

               $hoja2 = true;
            }else if($parametro->Parametro == 'Nitrógeno Amoniacal'){
                $mpdf->AddPage('', '', '', '', '', '', '', 35, 45, 6.5, '', '', '', '', '', -1, -1, -1, -1);

                $data = DB::table('ViewLoteDetalleDqo')->where('Id_lote', $id_lote)->get();
 
                if(!is_null($data)){                    
                    $dataLength = DB::table('ViewLoteDetalleDqo')->where('Id_lote', $id_lote)->count();

                    if($proced === true){
                        $separador = "Valoración";
                        $textoProcedimiento = explode($separador, $textProcedimiento->Texto);
                     }else{
                        $textProcedimiento = ReportesFq::where('Id_reporte', 27)->first();
                        $separador = "Valoración";
                        $textoProcedimiento = explode($separador, $textProcedimiento->Texto);
                    }
                    
                    $htmlCaptura1 = view('exports.laboratorio.fq.volumetria.nitrogenoA.capturaBody1', compact('textoProcedimiento', 'data', 'dataLength'));
                    $htmlHeader = view('exports.laboratorio.fq.volumetria.nitrogenoA.capturaHeader', compact('fechaConFormato'));
                    $htmlFooter = view('exports.laboratorio.fq.volumetria.nitrogenoA.capturaFooter', compact('usuario', 'firma'));
                }                              

               $hoja2 = true;
            }else if($parametro->Parametro == 'Nitrógeno Orgánico'){
                $mpdf->AddPage('', '', '', '', '', '', '', 35, 45, 6.5, '', '', '', '', '', -1, -1, -1, -1);

                $data = DB::table('ViewLoteDetalleDqo')->where('Id_lote', $id_lote)->get();
 
                if(!is_null($data)){                    
                    $dataLength = DB::table('ViewLoteDetalleDqo')->where('Id_lote', $id_lote)->count();

                    if($proced === true){
                        $separador = "Valoración";
                        $textoProcedimiento = explode($separador, $textProcedimiento->Texto);
                     }else{
                        $textProcedimiento = ReportesFq::where('Id_reporte', 27)->first();
                        $separador = "Valoración";
                        $textoProcedimiento = explode($separador, $textProcedimiento->Texto);
                    }
                    
                    $htmlCaptura1 = view('exports.laboratorio.fq.volumetria.nitrogenoO.capturaBody1', compact('textoProcedimiento', 'data', 'dataLength'));
                    $htmlHeader = view('exports.laboratorio.fq.volumetria.nitrogenoO.capturaHeader', compact('fechaConFormato'));
                    $htmlFooter = view('exports.laboratorio.fq.volumetria.nitrogenoO.capturaFooter', compact('usuario', 'firma'));
                }                              

               $hoja2 = true;
            }            

            if($hoja2 === true){
                $mpdf->SetHTMLHeader('{PAGENO}<br><br>' . $htmlHeader, 'O', 'E');
                $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                $mpdf->WriteHTML($htmlCaptura1);
            }
        }        
  
        if($sw === true){            
           $mpdf->CSSselectMedia = 'mpdf';
           $mpdf->Output();
        }        
     }
}