<?php

namespace App\Http\Controllers\Informes;

use App\Http\Controllers\Controller;
use App\Http\Livewire\AnalisisQ\Parametros;
use App\Models\AreaLab;
use App\Models\CampoCompuesto;
use App\Models\Clientes;
use App\Models\CodigoParametros;
use App\Models\Cotizacion;
use App\Models\CotizacionPunto;
use App\Models\DireccionReporte;
use App\Models\GastoMuestra;
use App\Models\Limite001;
use App\Models\Limite002;
use App\Models\Norma;
use App\Models\Parametro;
use App\Models\PhMuestra;
use App\Models\TemperaturaMuestra;
use App\Models\ProcesoAnalisis;
use App\Models\PuntoMuestreoGen;
use App\Models\PuntoMuestreoSir;
use App\Models\SimbologiaParametros;
use App\Models\Solicitud;
use App\Models\SolicitudParametro;
use App\Models\SolicitudPuntos;
use App\Models\TipoReporte;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InformesController extends Controller
{
    //
    public function index()
    {
        $tipoReporte = TipoReporte::all();
        $model = DB::table('ViewSolicitud')->orderBy('Id_solicitud','desc')->where('Padre',1)->get();
        return view('informes.informes', compact('tipoReporte', 'model'));
    }
    public function getPuntoMuestro(Request $request)
    {
        $solModel = DB::table('ViewSolicitud')->where('Id_solicitud',$request->id)->first();
        $siralab = false;
        if($solModel->Siralab != 0)
        {
            $siralab = true;
            $model = DB::table('ViewPuntoMuestreoSolSir')->where('Id_solicitud', $request->id)->get();
        }else{
            $model = DB::table('ViewPuntoMuestreoGen')->where('Id_solicitud', $request->id)->get();
        }
        $data = array(
            'model' => $model,
            'siralab' => $siralab,
        );
        return response()->json($data);
    }
    public function getSolParametro(Request $request)
    {
        $model = DB::table('ViewSolicitudParametros')->where('Id_solicitud', $request->id)->get();
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }

    public function mensual()
    {
        $model = DB::table('ViewSolicitud')->OrderBy('Id_solicitud', 'DESC')->get();
        return view('informes.mensual', compact('model'));
    }
    public function getPreReporteMensual(Request $res)
    {
        $solModel = DB::table('ViewSolicitud')->where('Id_solicitud', $res->idSol)->first();
        $solModel2 = DB::table('ViewSolicitud')->where('IdPunto', $solModel->IdPunto)->OrderBy('Id_solicitud', 'DESC')->get();

        //ViewCodigoParametro
        $cont = (sizeof($solModel2) - 1);
        $model = DB::table('ViewCodigoParametro')->where('Id_solicitud', $res->idSol)->get();
        $model2 = DB::table('ViewCodigoParametro')->where('Id_solicitud', $solModel2[0]->Id_solicitud)->get();

        $data = array(
            'cont' => $cont,
            'solModel' => $solModel,
            'solModel2' => $solModel2,
            'model' => $model,
            'model2' => $model2,
        );
        return response()->json($data);
    }

    //todo Seccio de pdf
    public function pdfSinComparacion($idSol)
    {
        //Opciones del documento PDF
        $mpdf = new \Mpdf\Mpdf([
            'orientation' => 'P',
            'format' => 'letter',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 76,
            'margin_bottom' => 125,
            'defaultheaderfontstyle' => ['normal'],
            'defaultheaderline' => '0'
        ]);        

        // Hace los filtros para realizar la comparacion
        $solModel = DB::table('ViewSolicitud')->where('Id_solicitud', $idSol)->first();
        //$solModel2 = DB::table('ViewSolicitud')->where('IdPunto', $solModel->IdPunto)->OrderBy('Id_solicitud', 'DESC')->get();

        //ViewCodigoParametro
        /* $cont = (sizeof($solModel2) - 1);
        $model = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->get();
        $model2 = DB::table('ViewCodigoParametro')->where('Id_solicitud', $solModel2[0]->Id_solicitud)->get(); */

        //Recupera sin duplicados las simbologías de los parámetros
        $simbolParam = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->get();
        $simbolParam = $simbolParam->unique('Id_simbologia');
        $simbologiaParam = array();

        foreach($simbolParam as $item){
            array_push($simbologiaParam, $item->Id_simbologia);
        }

        //Formatea la fecha; Por adaptar para el informe sin comparacion
        $fechaAnalisis = DB::table('ViewLoteAnalisis')->where('Id_lote', 0)->first();
        if (!is_null($fechaAnalisis)) {
            $fechaConFormato = date("d/m/Y", strtotime($fechaAnalisis->Fecha));
        }

        //Recupera los datos de la temperatura de la muestra compuesta
        $tempCompuesta = CampoCompuesto::where('Id_solicitud', $idSol);

        //$fechaEmision = \Carbon\Carbon::now();
        $solicitud = Solicitud::where('Id_solicitud', $idSol)->first();
        $direccion = DireccionReporte::where('Id_direccion', $solicitud->Id_direccion)->first();
        $horaMuestreo = null;

        $folio = explode("-", $solicitud->Folio_servicio);
        $parte1 = strval($folio[0]);
        $parte2 = strval($folio[1]);

        $numOrden = Solicitud::where('Folio_servicio', $parte1 . "-" . $parte2)->first();
        //$cotizacion = Cotizacion::where('Folio_servicio', $folio[0])->first();
        //$cotizacion = Cotizacion::where('Folio_servicio', 'LIKE', "%{$solicitud->Folio_servicio}%")->get();
        $cliente = Clientes::where('Id_cliente', $solicitud->Id_cliente)->first();
        //$solicitudPunto = SolicitudPuntos::where('Id_solicitud', $solicitud->Id_solicitud)->first();
        $puntoMuestreo = null;

        if($solicitud->Siralab == 1){//Es cliente Siralab
            $puntoMuestreo = PuntoMuestreoSir::where('Id_sucursal', $solicitud->Id_sucursal)->get();
            // $puntoMuestreo = SolicitudPuntos::where('Id_solicitud',$idSolicitud)->get();
            $puntos = $puntoMuestreo->count();
        }else{
            $puntoMuestreo = PuntoMuestreoGen::where('Id_sucursal', $solicitud->Id_sucursal)->get();
            // $puntoMuestreo = SolicitudPuntos::where('Id_solicitud',$idSolicitud)->get();
            $puntos = $puntoMuestreo->count();
        }

        /* $solicitudParametros = DB::table('ViewSolicitudParametros')->where('Id_solicitud', $solicitud->Id_solicitud)->get();
        $solicitudParametrosLength = $solicitudParametros->count(); */

        //Recupera los parámetros
        $solicitudParametros = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Num_muestra', 1)->orderBy('Parametro', 'ASC')->get();
        $solicitudParametrosLength = $solicitudParametros->count();
        $sParam = array();

        foreach($solicitudParametros as $item){
            $paramModel = Parametro::where('Id_parametro', $item->Id_parametro)->first();
            $sP = SimbologiaParametros::where('Id_simbologia', $paramModel->Id_simbologia)->first();
            array_push($sParam, $sP->Simbologia);
        }

        //*************************************CALCULO DE CONCENTRACIÓN CUANTIFICADA DE GRASAS *************************************
        //Consulta si existe el parámetro de Grasas y Aceites en la solicitud
        $solicitudParametroGrasas = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Id_parametro', 14)->get();
        $solicitudParametroGrasasLength = $solicitudParametroGrasas->count();

        if (!is_null($solicitudParametroGrasas)) { //Encontró grasas

            //Recupera los gastos (caudales) de la solicitud
            $gastosModel = GastoMuestra::where('Id_solicitud', $idSol)->get();
            $gastosModelLength = $gastosModel->count();
            $sumaCaudales = 0;
            $sumaCaudalesFinal = 0;

            //Arreglo que almacena el resultado de cada caudal entre la sumatoria de los caudales
            $divCaudalSuma = array();

            //Arreglo que almacena los resultados de las multiplicaciones de divCaudalSuma por el resultado de cada muestra del parámetro
            $multResDivCaudal = array();

            //Paso 1: Sumatoria de los caudales
            foreach ($gastosModel as $item) {
                if ($item->Promedio === null) {
                    $sumaCaudales += 0;
                } else {
                    $sumaCaudales += $item->Promedio;
                }
            }

            //***************
            $sumaCaudales = round($sumaCaudales, 2);

            //Paso 2: División de cada caudal entre la sumatoria de los caudales
            foreach ($gastosModel as $item) {
                if ($item->Promedio === null) {
                    $div = 0 / $sumaCaudales;

                    array_push(
                        $divCaudalSuma,
                        $div
                    );
                } else {
                    $div = $item->Promedio / $sumaCaudales;

                    $div = round($div, 2);

                    array_push(
                        $divCaudalSuma,
                        $div
                    );
                }
            }

            //Paso 3: Multiplicación de cada divCaudalSuma por el resultado del parametro                                    
            for ($i = 0; $i < $solicitudParametroGrasasLength; $i++) {
                $mult = round(($divCaudalSuma[$i] * $solicitudParametroGrasas[$i]->Resultado), 2);

                array_push(
                    $multResDivCaudal,
                    $mult
                );
            }

            //Paso 4: Sumatoria de multResDivCaudal
            foreach ($multResDivCaudal as $item) {
                $sumaCaudalesFinal += $item;
            }

            $sumaCaudalesFinal = round($sumaCaudalesFinal, 2);

            //Verifica si el resultado es menor al límite de cuantificación del parámetro
            $limiteGrasas = DB::table('parametros')->where('Id_parametro', 14)->first();

            if ($sumaCaudalesFinal < $limiteGrasas->Limite) {
                $sumaCaudalesFinal = "< " . $limiteGrasas->Limite;
            }
        }

        //echo  implode(" , ", $multResDivCaudal);        

        //**************************************FIN DE CALCULO DE CONCENTRACION CUANTIFICADA DE GRASAS ******************************

        //************************************** CALCULO DE COLIFORMES FECALES ******************************************************
        //Consulta si existe el parámetro de Coliformes Fecales en la solicitud
        $solicitudParametroColiformesFe = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Id_parametro', 13)->get();
        $solicitudParametroColiformesFeLength = $solicitudParametroColiformesFe->count();
        $resColi = 0;

        if ($solicitudParametroColiformesFeLength > 0) { //Encontró coliformes fecales
            $productoColi = 1;

            //Paso 1: Multiplicación de todos los resultados de coliformes fecales
            foreach ($solicitudParametroColiformesFe as $item) {
                $productoColi *= $item->Resultado;
            }

            //Paso 2: Raíz a la N cantidad de resultados de coliformes
            $resColi = round(pow($productoColi, 1 / $solicitudParametroColiformesFeLength), 2);

            //Verifica si el resultado es menor al límite de cuantificación del parámetro
            $limiteColi = DB::table('parametros')->where('Id_parametro', $solicitudParametroColiformesFe[0]->Id_parametro)->first();

            if ($resColi < $limiteColi->Limite) {
                $resColi = "< " . $limiteColi->Limite;
            }
        }
        //************************************** FIN DE CALCULO DE COLIFORMES FECALES *********************************************** 

        //************************************** CALCULO DE NITROGENO KJELDAHL **********************************************

        //Consulta si existen los parámetros nitrogeno total y amoniacal para esta solicitud
        $solParamAmoniacal = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Id_parametro', 10)->first();
        $solParamOrganico = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Id_parametro', 11)->first();

        //Sí existen los parámetros para el cálculo kjeldahl
        if (!is_null($solParamAmoniacal) && !is_null($solParamOrganico)) {
            //Verifica si el resultado es menor al límite de cuantificación del parámetro
            $limiteAmoniacal = DB::table('parametros')->where('Id_parametro', $solParamAmoniacal->Id_parametro)->first();
            $limiteOrganico = DB::table('parametros')->where('Id_parametro', $solParamOrganico->Id_parametro)->first();

            $resKjeldahl = 0;
            $resLimAmo = 0;
            $resLimOrg = 0;

            //Nitrogeno Amoniacal
            if ($solParamAmoniacal->Resultado < $limiteAmoniacal->Limite) {
                $resLimAmo = $limiteAmoniacal->Limite;
            } else {
                $resLimAmo = $solParamAmoniacal->Resultado;
            }

            //Nitrogeno Organico
            if ($solParamOrganico->Resultado < $limiteOrganico->Limite) {
                $resLimOrg = $limiteOrganico->Limite;
            } else {
                $resLimOrg = $solParamOrganico->Resultado;
            }

            $resKjeldahl = $resLimAmo + $resLimOrg;
        }

        //************************************** FIN DE CALCULO DE NITROGENO KJELDAHL ***************************************

        //***************************************CALCULO DE NITROGENO TOTAL *************************************************
        //Consulta si existen los parámetros nitrogeno total, amoniacal, nitritos y nitratos para esta solicitud
        $solParamAmoniacal = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Id_parametro', 10)->first();
        $solParamOrganico = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Id_parametro', 11)->first();
        $solParamNitritos = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Id_parametro', 9)->first();
        $solParamNitratos = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Id_parametro', 8)->first();

        //Sí existen los parámetros para el cálculo de nitrógeno total
        if (!is_null($solParamAmoniacal) && !is_null($solParamOrganico) && !is_null($solParamNitritos) && !is_null($solParamNitratos)) {
            //Verifica si el resultado es menor al límite de cuantificación del parámetro
            $limiteAmoniacal = DB::table('parametros')->where('Id_parametro', $solParamAmoniacal->Id_parametro)->first();
            $limiteOrganico = DB::table('parametros')->where('Id_parametro', $solParamOrganico->Id_parametro)->first();
            $limiteNitritos = DB::table('parametros')->where('Id_parametro', $solParamNitritos->Id_parametro)->first();
            $limiteNitratos = DB::table('parametros')->where('Id_parametro', $solParamNitratos->Id_parametro)->first();

            $resNitrogenoT = 0;
            $resLimAmo = 0;
            $resLimOrg = 0;
            $resLimNitritos = 0;
            $resLimNitratos = 0;

            //Nitrogeno Amoniacal
            if ($solParamAmoniacal->Resultado < $limiteAmoniacal->Limite) {
                $resLimAmo = $limiteAmoniacal->Limite;
            } else {
                $resLimAmo = $solParamAmoniacal->Resultado;
            }

            //Nitrogeno Orgánico
            if ($solParamOrganico->Resultado < $limiteOrganico->Limite) {
                $resLimOrg = $limiteOrganico->Limite;
            } else {
                $resLimOrg = $solParamOrganico->Resultado;
            }

            //Nitritos
            if ($solParamNitritos->Resultado < $limiteNitritos->Limite) {
                $resLimNitritos = $limiteNitritos->Limite;
            } else {
                $resLimNitritos = $solParamNitritos->Resultado;
            }

            //Nitratos
            if ($solParamNitratos->Resultado < $limiteNitratos->Limite) {
                $resLimNitratos = $limiteNitratos->Limite;
            } else {
                $resLimNitratos = $solParamNitratos->Resultado;
            }

            $resNitrogenoT = $resLimAmo + $resLimOrg + $resLimNitritos + $resLimNitratos;
        }
        //*************************************** FIN DE CALCULO DE NITROGENO TOTAL *****************************************

        //Recupera el gasto promedio para la solicitud dada
        $gModel = GastoMuestra::where('Id_solicitud', $idSol)->get();
        $gModelLength = $gastosModel->count();
        $gastosProm = 0;

        if(!is_null($gModel)){
            foreach($gModel as $item){
                if($item->Promedio == null){
                    $gastosProm += 0;
                }else{
                    $gastosProm += $item->Promedio;
                }                
            }

            $gastosProm /= $gModelLength;
        }

        //Recupera la temperatura promedio para la solicitud dada
        $tModel = TemperaturaMuestra::where('Id_solicitud', $idSol)->get();
        $tModelLength = $tModel->count();
        $tProm = 0;

        if(!is_null($tModel)){
            foreach($tModel as $item){
                if($item->Promedio == null){
                    $tProm += 0;
                }else{
                    $tProm += $item->Promedio;
                }                
            }

            $tProm /= $tModelLength;
        }

        //Recupera el pH promedio para la solicitud dada
        $phModel = PhMuestra::where('Id_solicitud', $idSol)->get();
        $phModelLength = $phModel->count();
        $phProm = 0;

        if(!is_null($phModel)){
            foreach($phModel as $item){
                if($item->Promedio == null){
                    $phProm += 0;
                }else{
                    $phProm += $item->Promedio;
                }                
            }

            $phProm /= $phModelLength;
        }

        $limitesC = array();

        //Recupera los límites de cuantificación de los parámetros        
        foreach ($solicitudParametros as $item) {
            $limiteC = DB::table('parametros')->where('Id_parametro', $item->Id_parametro)->first();

            if($item->Id_parametro == 27){ //Gasto
                array_push($limitesC, number_format($gastosProm, 2, ".", ","));
            }else if($item->Id_parametro == 98){ //Temperatura
                array_push($limitesC, number_format($tProm, 2, ".", ","));
            }else if($item->Id_parametro == 15){ //pH
                array_push($limitesC, number_format($phProm, 2, ".", ","));
            }else if ($item->Resultado < $limiteC->Limite) {
                $limC = "< " . $limiteC->Limite;

                array_push($limitesC, $limC);
            } else {  //Si es mayor el resultado que el límite de cuantificación
                if($item->Id_parametro == 20){   //Cianuros
                    $limC = number_format($item->Resultado, 3, ".", ",");
                }else if($item->Id_parametro == 13){   //Coliformes fecales
                    $limC = number_format($item->Resultado, 2, ".", ",");
                }else if($item->Id_parametro == 22){   //Cromo total
                    $limC = number_format($item->Resultado, 3, ".", ",");
                }else if($item->Id_parametro == 72){   //DBO
                    $limC = number_format($item->Resultado, 2, ".", ",");
                }else if($item->Id_parametro == 7){   //DQO
                    $limC = number_format($item->Resultado, 2, ".", ",");
                }else if($item->Id_parametro == 16){   //Fosforo
                    $limC = number_format($item->Resultado, 3, ".", ",");
                }else if($item->Id_parametro == 14){   //Grasas y Aceites
                    $limC = number_format($item->Resultado, 2, ".", ",");
                }else if($item->Id_parametro == 17){   //Huevos de helminto
                    $limC = number_format($item->Resultado, 0, ".", ",");
                }else if($item->Id_parametro == 23){   //Mercurio
                    $limC = number_format($item->Resultado, 4, ".", ",");
                }else if($item->Id_parametro == 8){   //Nitratos
                    $limC = number_format($item->Resultado, 3, ".", ",");
                }else if($item->Id_parametro == 9){   //Nitritos
                    $limC = number_format($item->Resultado, 3, ".", ",");
                }else if($item->Id_parametro == 10 || $item->Id_parametro == 11){   //Nitrogeno Amoniacal y Nitrogeno Organico
                    $limC = number_format($item->Resultado, 2, ".", ",");
                }else{
                    $limC = number_format($item->Resultado, 3, ".", ",");
                }                

                array_push($limitesC, $limC);
            }
        }

        $modelProcesoAnalisis = ProcesoAnalisis::where('Id_solicitud', $idSol)->first();

        //Recupera el valor correcto de la hora de muestreo
        if ($solicitud->Id_muestra !== 'COMPUESTA') {
            $horaMuestreo = \Carbon\Carbon::parse($modelProcesoAnalisis->Hora_entrada)->format('H:i:s');
        } else {
            $horaMuestreo = 'COMPUESTA';
        }

        //Recupera la temperatura compuesta
        $temperaturaC = CampoCompuesto::where('Id_solicitud', $idSol)->first();

        //Recupera la obs de campo
        $obsCampo = $temperaturaC->Observaciones;

        //BODY;Por añadir validaciones, mismas que se irán implementando cuando haya una tabla en la BD para los informes
        $htmlInforme = view('exports.informes.sinComparacion.bodyInforme',  compact('solicitudParametros', 'solicitudParametrosLength', 'limitesC', 'tempCompuesta', 'sumaCaudalesFinal', 'resColi', 'sParam'));

        //HEADER-FOOTER******************************************************************************************************************                 
        $htmlHeader = view('exports.informes.sinComparacion.headerInforme', compact('solicitud', 'direccion', 'cliente', 'puntoMuestreo', 'numOrden', 'modelProcesoAnalisis', 'horaMuestreo'));
        $htmlFooter = view('exports.informes.sinComparacion.footerInforme', compact('solicitud', 'simbologiaParam', 'temperaturaC', 'obsCampo'));

        $mpdf->setHeader("{PAGENO} / {nbpg} <br><br>" . $htmlHeader);
        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
        $mpdf->WriteHTML($htmlInforme);

        $mpdf->CSSselectMedia = 'mpdf';
        $mpdf->Output('Informe de resultados sin comparacion.pdf', 'I');        
    }

    public function pdfConComparacion($idSol)
    {
        //Opciones del documento PDF
        $mpdf = new \Mpdf\Mpdf([
            'orientation' => 'P',
            'format' => 'letter',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 76,
            'margin_bottom' => 125,
            'defaultheaderfontstyle' => ['normal'],
            'defaultheaderline' => '0'
        ]);

        //Recupera el nombre de usuario y firma
        /* $usuario = DB::table('users')->where('id', auth()->user()->id)->first();
        $firma = $usuario->firma; */

        //Formatea la fecha; Por adaptar para el informe sin comparacion
        $fechaAnalisis = DB::table('ViewLoteAnalisis')->where('Id_lote', 0)->first();
        if (!is_null($fechaAnalisis)) {
            $fechaConFormato = date("d/m/Y", strtotime($fechaAnalisis->Fecha));
        }

        //$fechaEmision = \Carbon\Carbon::now();
        $solicitud = Solicitud::where('Id_solicitud', $idSol)->first();
        $direccion = DireccionReporte::where('Id_direccion', $solicitud->Id_direccion)->first();
        $folio = explode("-", $solicitud->Folio_servicio);
        $parte1 = strval($folio[0]);
        $parte2 = strval($folio[1]);

        $numOrden = Solicitud::where('Folio_servicio', $parte1 . "-" . $parte2)->first();
        //$cotizacion = Cotizacion::where('Folio_servicio', $folio[0])->first();
        //$cotizacion = Cotizacion::where('Folio_servicio', 'LIKE', "%{$solicitud->Folio_servicio}%")->get();
        $cliente = Clientes::where('Id_cliente', $solicitud->Id_cliente)->first();
        $solicitudPunto = SolicitudPuntos::where('Id_solicitud', $solicitud->Id_solicitud)->first();
        $puntoMuestreo = null;

        if($solicitud->Siralab == 1){//Es cliente Siralab
            $puntoMuestreo = PuntoMuestreoSir::where('Id_sucursal', $solicitud->Id_sucursal)->get();
            // $puntoMuestreo = SolicitudPuntos::where('Id_solicitud',$idSolicitud)->get();
            $puntos = $puntoMuestreo->count();
        }else{
            $puntoMuestreo = PuntoMuestreoGen::where('Id_sucursal', $solicitud->Id_sucursal)->get();
            // $puntoMuestreo = SolicitudPuntos::where('Id_solicitud',$idSolicitud)->get();
            $puntos = $puntoMuestreo->count();
        }

        /* $solicitudParametros = DB::table('ViewSolicitudParametros')->where('Id_solicitud', $solicitud->Id_solicitud)->get();
        $solicitudParametrosLength = $solicitudParametros->count(); */

        //Sirve para recuperar los límites de cuantificación de la norma
        //$promSol = $solicitud->Id_promedio; //Si es mensual o diario
        
        //Recupera los parámetros
        $solicitudParametros = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Num_muestra', 1)->orderBy('Parametro', 'ASC')->get();
        $solicitudParametrosLength = $solicitudParametros->count();
        $sParam = array();
        //$limNorm = array();

        foreach($solicitudParametros as $item){
            $paramModel = Parametro::where('Id_parametro', $item->Id_parametro)->first();
            $sP = SimbologiaParametros::where('Id_simbologia', $paramModel->Id_simbologia)->first();
            array_push($sParam, $sP->Simbologia);

            /* if($solicitud->Id_norma == 1){ //Norma 001
                Limite001::where('Id_parametro', $item->Id_parametro)->where('Id_')->first();
            }else if($solicitud->Id_norma == 2){ //Norma 002
                $param = Limite002::where('Id_parametro', $item->Id_parametro)->first();
            } */
        }

        //Recupera sin duplicados las simbologías de los parámetros
        $simbolParam = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->get();
        $simbolParam = $simbolParam->unique('Id_simbologia');
        $simbologiaParam = array();

        foreach($simbolParam as $item){
            array_push($simbologiaParam, $item->Id_simbologia);
        }        

        //*************************************CALCULO DE CONCENTRACIÓN CUANTIFICADA DE GRASAS *************************************
        //Consulta si existe el parámetro de Grasas y Aceites en la solicitud
        $solicitudParametroGrasas = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Id_parametro', 14)->get();
        $solicitudParametroGrasasLength = $solicitudParametroGrasas->count();

        if (!is_null($solicitudParametroGrasas)) { //Encontró grasas

            //Recupera los gastos (caudales) de la solicitud
            $gastosModel = GastoMuestra::where('Id_solicitud', $idSol)->get();
            $gastosModelLength = $gastosModel->count();
            $sumaCaudales = 0;
            $sumaCaudalesFinal = 0;

            //Arreglo que almacena el resultado de cada caudal entre la sumatoria de los caudales
            $divCaudalSuma = array();

            //Arreglo que almacena los resultados de las multiplicaciones de divCaudalSuma por el resultado de cada muestra del parámetro
            $multResDivCaudal = array();

            //Paso 1: Sumatoria de los caudales
            foreach ($gastosModel as $item) {
                if ($item->Promedio === null) {
                    $sumaCaudales += 0;
                } else {
                    $sumaCaudales += $item->Promedio;
                }
            }

            $sumaCaudales = round($sumaCaudales, 2);

            //Paso 2: División de cada caudal entre la sumatoria de los caudales
            foreach ($gastosModel as $item) {
                if ($item->Promedio === null) {
                    $div = 0 / $sumaCaudales;

                    array_push(
                        $divCaudalSuma,
                        $div
                    );
                } else {
                    $div = round(($item->Promedio / $sumaCaudales), 2);

                    array_push(
                        $divCaudalSuma,
                        $div
                    );
                }
            }

            //Paso 3: Multiplicación de cada divCaudalSuma por el resultado del parametro                                    
            for ($i = 0; $i < $solicitudParametroGrasasLength; $i++) {
                $mult = round(($divCaudalSuma[$i] * $solicitudParametroGrasas[$i]->Resultado), 2);

                array_push(
                    $multResDivCaudal,
                    $mult
                );
            }

            //Paso 4: Sumatoria de multResDivCaudal
            foreach ($multResDivCaudal as $item) {
                $sumaCaudalesFinal += $item;
            }

            $sumaCaudalesFinal = round($sumaCaudalesFinal, 2);

            //Verifica si el resultado es menor al límite de cuantificación del parámetro
            $limiteGrasas = DB::table('parametros')->where('Id_parametro', 14)->first();

            if ($sumaCaudalesFinal < $limiteGrasas->Limite) {
                $sumaCaudalesFinal = "< " . $limiteGrasas->Limite;
            }
        }

        //echo  implode(" , ", $multResDivCaudal);        

        //**************************************FIN DE CALCULO DE CONCENTRACION CUANTIFICADA DE GRASAS ******************************

        //************************************** CALCULO DE COLIFORMES FECALES ******************************************************
        //Consulta si existe el parámetro de Coliformes Fecales en la solicitud
        $solicitudParametroColiformesFe = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Id_parametro', 13)->get();
        $solicitudParametroColiformesFeLength = $solicitudParametroColiformesFe->count();
        $resColi = 0;

        if ($solicitudParametroColiformesFeLength > 0) { //Encontró coliformes fecales
            $productoColi = 1;

            //Paso 1: Multiplicación de todos los resultados de coliformes fecales
            foreach ($solicitudParametroColiformesFe as $item) {
                $productoColi *= $item->Resultado;
            }

            //Paso 2: Raíz a la N cantidad de resultados de coliformes
            $resColi = round(pow($productoColi, 1 / $solicitudParametroColiformesFeLength), 2);

            //Verifica si el resultado es menor al límite de cuantificación del parámetro
            $limiteColi = DB::table('parametros')->where('Id_parametro', $solicitudParametroColiformesFe[0]->Id_parametro)->first();

            if ($resColi < $limiteColi->Limite) {
                $resColi = "< " . $limiteColi->Limite;
            }
        }
        //************************************** FIN DE CALCULO DE COLIFORMES FECALES *********************************************** 

        //************************************** CALCULO DE NITROGENO KJELDAHL **********************************************

        //Consulta si existen los parámetros nitrogeno total y amoniacal para esta solicitud
        $solParamAmoniacal = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Id_parametro', 10)->first();
        $solParamOrganico = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Id_parametro', 11)->first();

        //Sí existen los parámetros para el cálculo kjeldahl
        if (!is_null($solParamAmoniacal) && !is_null($solParamOrganico)) {
            //Verifica si el resultado es menor al límite de cuantificación del parámetro
            $limiteAmoniacal = DB::table('parametros')->where('Id_parametro', $solParamAmoniacal->Id_parametro)->first();
            $limiteOrganico = DB::table('parametros')->where('Id_parametro', $solParamOrganico->Id_parametro)->first();

            $resKjeldahl = 0;
            $resLimAmo = 0;
            $resLimOrg = 0;

            //Nitrogeno Amoniacal
            if ($solParamAmoniacal->Resultado < $limiteAmoniacal->Limite) {
                $resLimAmo = $limiteAmoniacal->Limite;
            } else {
                $resLimAmo = $solParamAmoniacal->Resultado;
            }

            //Nitrogeno Organico
            if ($solParamOrganico->Resultado < $limiteOrganico->Limite) {
                $resLimOrg = $limiteOrganico->Limite;
            } else {
                $resLimOrg = $solParamOrganico->Resultado;
            }

            $resKjeldahl = $resLimAmo + $resLimOrg;
        }

        //************************************** FIN DE CALCULO DE NITROGENO KJELDAHL ***************************************

        //***************************************CALCULO DE NITROGENO TOTAL *************************************************
        //Consulta si existen los parámetros nitrogeno total, amoniacal, nitritos y nitratos para esta solicitud
        $solParamAmoniacal = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Id_parametro', 10)->first();
        $solParamOrganico = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Id_parametro', 11)->first();
        $solParamNitritos = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Id_parametro', 9)->first();
        $solParamNitratos = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Id_parametro', 8)->first();

        //Sí existen los parámetros para el cálculo kjeldahl
        if (!is_null($solParamAmoniacal) && !is_null($solParamOrganico) && !is_null($solParamNitritos) && !is_null($solParamNitratos)) {
            //Verifica si el resultado es menor al límite de cuantificación del parámetro
            $limiteAmoniacal = DB::table('parametros')->where('Id_parametro', $solParamAmoniacal->Id_parametro)->first();
            $limiteOrganico = DB::table('parametros')->where('Id_parametro', $solParamOrganico->Id_parametro)->first();
            $limiteNitritos = DB::table('parametros')->where('Id_parametro', $solParamNitritos->Id_parametro)->first();
            $limiteNitratos = DB::table('parametros')->where('Id_parametro', $solParamNitratos->Id_parametro)->first();

            $resNitrogenoT = 0;
            $resLimAmo = 0;
            $resLimOrg = 0;
            $resLimNitritos = 0;
            $resLimNitratos = 0;

            //Nitrogeno Amoniacal
            if ($solParamAmoniacal->Resultado < $limiteAmoniacal->Limite) {
                $resLimAmo = $limiteAmoniacal->Limite;
            } else {
                $resLimAmo = $solParamAmoniacal->Resultado;
            }

            //Nitrogeno Organico
            if ($solParamOrganico->Resultado < $limiteOrganico->Limite) {
                $resLimOrg = $limiteOrganico->Limite;
            } else {
                $resLimOrg = $solParamOrganico->Resultado;
            }

            //Nitritos
            if ($solParamNitritos->Resultado < $limiteNitritos->Limite) {
                $resLimNitritos = $limiteNitritos->Limite;
            } else {
                $resLimNitritos = $solParamNitritos->Resultado;
            }

            //Nitratos
            if ($solParamNitratos->Resultado < $limiteNitratos->Limite) {
                $resLimNitratos = $limiteNitratos->Limite;
            } else {
                $resLimNitratos = $solParamNitratos->Resultado;
            }

            $resNitrogenoT = $resLimAmo + $resLimOrg + $resLimNitritos + $resLimNitratos;
        }
        //*************************************** FIN DE CALCULO DE NITROGENO TOTAL *****************************************

        //Recupera el gasto promedio para la solicitud dada
        $gModel = GastoMuestra::where('Id_solicitud', $idSol)->get();
        $gModelLength = $gastosModel->count();
        $gastosProm = 0;

        if(!is_null($gModel)){
            foreach($gModel as $item){
                if($item->Promedio == null){
                    $gastosProm += 0;
                }else{
                    $gastosProm += $item->Promedio;
                }                
            }

            $gastosProm /= $gModelLength;
        }

        //Recupera la temperatura promedio para la solicitud dada
        $tModel = TemperaturaMuestra::where('Id_solicitud', $idSol)->get();
        $tModelLength = $tModel->count();
        $tProm = 0;

        if(!is_null($tModel)){
            foreach($tModel as $item){
                if($item->Promedio == null){
                    $tProm += 0;
                }else{
                    $tProm += $item->Promedio;
                }                
            }

            $tProm /= $tModelLength;
        }

        //Recupera el pH promedio para la solicitud dada
        $phModel = PhMuestra::where('Id_solicitud', $idSol)->get();
        $phModelLength = $phModel->count();
        $phProm = 0;

        if(!is_null($phModel)){
            foreach($phModel as $item){
                if($item->Promedio == null){
                    $phProm += 0;
                }else{
                    $phProm += $item->Promedio;
                }                
            }

            $phProm /= $phModelLength;
        }

        $limitesC = array();

        //Recupera los límites de cuantificación de los parámetros        
        foreach ($solicitudParametros as $item) {
            $limiteC = DB::table('parametros')->where('Id_parametro', $item->Id_parametro)->first();

            if($item->Id_parametro == 27){ //Gasto
                array_push($limitesC, number_format($gastosProm, 2, ".", ","));
            }else if($item->Id_parametro == 98){ //Temperatura
                array_push($limitesC, number_format($tProm, 2, ".", ","));
            }else if($item->Id_parametro == 15){ //pH
                array_push($limitesC, number_format($phProm, 2, ".", ","));
            }else if ($item->Resultado < $limiteC->Limite) {
                $limC = "< " . $limiteC->Limite;

                array_push($limitesC, $limC);
            } else {  //Si es mayor el resultado que el límite de cuantificación
                if($item->Id_parametro == 7){   //DQO
                    $limC = number_format($item->Resultado, 2, ".", ",");
                }else if($item->Id_parametro == 20){   //Cianuros
                    $limC = number_format($item->Resultado, 3, ".", ",");
                }else if($item->Id_parametro == 13){   //Coliformes fecales
                    $limC = number_format($item->Resultado, 2, ".", ",");
                }else if($item->Id_parametro == 22){   //Cromo total
                    $limC = number_format($item->Resultado, 3, ".", ",");
                }else if($item->Id_parametro == 72){   //DBO
                    $limC = number_format($item->Resultado, 2, ".", ",");
                }else if($item->Id_parametro == 7){   //DQO
                    $limC = number_format($item->Resultado, 2, ".", ",");
                }else if($item->Id_parametro == 16){   //Fosforo
                    $limC = number_format($item->Resultado, 3, ".", ",");
                }else if($item->Id_parametro == 14){   //Grasas y Aceites
                    $limC = number_format($item->Resultado, 2, ".", ",");
                }else if($item->Id_parametro == 17){   //Huevos de helminto
                    $limC = number_format($item->Resultado, 0, ".", ",");
                }else if($item->Id_parametro == 23){   //Mercurio
                    $limC = number_format($item->Resultado, 4, ".", ",");
                }else if($item->Id_parametro == 8){   //Nitratos
                    $limC = number_format($item->Resultado, 3, ".", ",");
                }else if($item->Id_parametro == 9){   //Nitritos
                    $limC = number_format($item->Resultado, 3, ".", ",");
                }else if($item->Id_parametro == 10 || $item->Id_parametro == 11){   //Nitrogeno Amoniacal y Nitrogeno Organico
                    $limC = number_format($item->Resultado, 2, ".", ",");
                }else{
                    $limC = number_format($item->Resultado, 3, ".", ",");
                }

                array_push($limitesC, $limC);
            }
        }

        $modelProcesoAnalisis = ProcesoAnalisis::where('Id_solicitud', $idSol)->first();

        //Recupera el valor correcto de la hora de muestreo
        if ($solicitud->Id_muestra !== 'COMPUESTA') {
            $horaMuestreo = \Carbon\Carbon::parse($modelProcesoAnalisis->Hora_entrada)->format('H:i:s');
        } else {
            $horaMuestreo = 'COMPUESTA';
        }

        //Recupera la obs de campo
        $modelComp = CampoCompuesto::where('Id_solicitud', $idSol)->first();
        $obsCampo = $modelComp->Observaciones;

        //BODY;Por añadir validaciones, mismas que se irán implementando cuando haya una tabla en la BD para los informes
        $htmlInforme = view('exports.informes.conComparacion.bodyComparacionInforme',  compact('solicitudParametros', 'solicitudParametrosLength', 'limitesC', 'sumaCaudalesFinal', 'resColi', 'sParam', 'puntoMuestreo'));

        //HEADER-FOOTER******************************************************************************************************************                 
        $htmlHeader = view('exports.informes.conComparacion.headerComparacionInforme', compact('solicitud', 'direccion', 'cliente', 'puntoMuestreo', 'numOrden', 'horaMuestreo', 'modelProcesoAnalisis'));
        $htmlFooter = view('exports.informes.conComparacion.footerComparacionInforme', compact('solicitud', 'simbologiaParam', 'obsCampo'));

        $mpdf->setHeader("{PAGENO} / {nbpg} <br><br>" . $htmlHeader);
        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
        $mpdf->WriteHTML($htmlInforme);

        $mpdf->CSSselectMedia = 'mpdf';
        $mpdf->Output('Informe de resultados con comparacion.pdf', 'I');
    }

    //****************ESTAS FUNCIONES SE LLAMAN A TRAVÉS DE LA RUTA PÚBLICA HACIENDO USO DEL CÓDIGO QR
    //todo Seccio de pdf
    public function pdfSinComparacionCli($idSol)
    {
        //Opciones del documento PDF
        $mpdf = new \Mpdf\Mpdf([
            'orientation' => 'P',
            'format' => 'letter',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 76,
            'margin_bottom' => 125,
            'defaultheaderfontstyle' => ['normal'],
            'defaultheaderline' => '0'
        ]);        

        // Hace los filtros para realizar la comparacion
        $solModel = DB::table('ViewSolicitud')->where('Id_solicitud', $idSol)->first();
        //$solModel2 = DB::table('ViewSolicitud')->where('IdPunto', $solModel->IdPunto)->OrderBy('Id_solicitud', 'DESC')->get();

        //ViewCodigoParametro
        /* $cont = (sizeof($solModel2) - 1);
        $model = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->get();
        $model2 = DB::table('ViewCodigoParametro')->where('Id_solicitud', $solModel2[0]->Id_solicitud)->get(); */

        //Recupera sin duplicados las simbologías de los parámetros
        $simbolParam = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->get();
        $simbolParam = $simbolParam->unique('Id_simbologia');
        $simbologiaParam = array();

        foreach($simbolParam as $item){
            array_push($simbologiaParam, $item->Id_simbologia);
        }

        //Formatea la fecha; Por adaptar para el informe sin comparacion
        $fechaAnalisis = DB::table('ViewLoteAnalisis')->where('Id_lote', 0)->first();
        if (!is_null($fechaAnalisis)) {
            $fechaConFormato = date("d/m/Y", strtotime($fechaAnalisis->Fecha));
        }

        //Recupera los datos de la temperatura de la muestra compuesta
        $tempCompuesta = CampoCompuesto::where('Id_solicitud', $idSol);

        //$fechaEmision = \Carbon\Carbon::now();
        $solicitud = Solicitud::where('Id_solicitud', $idSol)->first();
        $direccion = DireccionReporte::where('Id_direccion', $solicitud->Id_direccion)->first();
        $horaMuestreo = null;

        $folio = explode("-", $solicitud->Folio_servicio);
        $parte1 = strval($folio[0]);
        $parte2 = strval($folio[1]);

        $numOrden = Solicitud::where('Folio_servicio', $parte1 . "-" . $parte2)->first();
        //$cotizacion = Cotizacion::where('Folio_servicio', $folio[0])->first();
        //$cotizacion = Cotizacion::where('Folio_servicio', 'LIKE', "%{$solicitud->Folio_servicio}%")->get();
        $cliente = Clientes::where('Id_cliente', $solicitud->Id_cliente)->first();
        //$solicitudPunto = SolicitudPuntos::where('Id_solicitud', $solicitud->Id_solicitud)->first();
        $puntoMuestreo = null;

        if($solicitud->Siralab == 1){//Es cliente Siralab
            $puntoMuestreo = PuntoMuestreoSir::where('Id_sucursal', $solicitud->Id_sucursal)->get();
            // $puntoMuestreo = SolicitudPuntos::where('Id_solicitud',$idSolicitud)->get();
            $puntos = $puntoMuestreo->count();
        }else{
            $puntoMuestreo = PuntoMuestreoGen::where('Id_sucursal', $solicitud->Id_sucursal)->get();
            // $puntoMuestreo = SolicitudPuntos::where('Id_solicitud',$idSolicitud)->get();
            $puntos = $puntoMuestreo->count();
        }

        /* $solicitudParametros = DB::table('ViewSolicitudParametros')->where('Id_solicitud', $solicitud->Id_solicitud)->get();
        $solicitudParametrosLength = $solicitudParametros->count(); */

        //Recupera los parámetros
        $solicitudParametros = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Num_muestra', 1)->orderBy('Parametro', 'ASC')->get();
        $solicitudParametrosLength = $solicitudParametros->count();
        $sParam = array();

        foreach($solicitudParametros as $item){
            $paramModel = Parametro::where('Id_parametro', $item->Id_parametro)->first();
            $sP = SimbologiaParametros::where('Id_simbologia', $paramModel->Id_simbologia)->first();
            array_push($sParam, $sP->Simbologia);
        }

        //*************************************CALCULO DE CONCENTRACIÓN CUANTIFICADA DE GRASAS *************************************
        //Consulta si existe el parámetro de Grasas y Aceites en la solicitud
        $solicitudParametroGrasas = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Id_parametro', 14)->get();
        $solicitudParametroGrasasLength = $solicitudParametroGrasas->count();

        if (!is_null($solicitudParametroGrasas)) { //Encontró grasas

            //Recupera los gastos (caudales) de la solicitud
            $gastosModel = GastoMuestra::where('Id_solicitud', $idSol)->get();
            $gastosModelLength = $gastosModel->count();
            $sumaCaudales = 0;
            $sumaCaudalesFinal = 0;

            //Arreglo que almacena el resultado de cada caudal entre la sumatoria de los caudales
            $divCaudalSuma = array();

            //Arreglo que almacena los resultados de las multiplicaciones de divCaudalSuma por el resultado de cada muestra del parámetro
            $multResDivCaudal = array();

            //Paso 1: Sumatoria de los caudales
            foreach ($gastosModel as $item) {
                if ($item->Promedio === null) {
                    $sumaCaudales += 0;
                } else {
                    $sumaCaudales += $item->Promedio;
                }
            }

            //***************
            $sumaCaudales = round($sumaCaudales, 2);

            //Paso 2: División de cada caudal entre la sumatoria de los caudales
            foreach ($gastosModel as $item) {
                if ($item->Promedio === null) {
                    $div = 0 / $sumaCaudales;

                    array_push(
                        $divCaudalSuma,
                        $div
                    );
                } else {
                    $div = $item->Promedio / $sumaCaudales;

                    $div = round($div, 2);

                    array_push(
                        $divCaudalSuma,
                        $div
                    );
                }
            }

            //Paso 3: Multiplicación de cada divCaudalSuma por el resultado del parametro                                    
            for ($i = 0; $i < $solicitudParametroGrasasLength; $i++) {
                $mult = round(($divCaudalSuma[$i] * $solicitudParametroGrasas[$i]->Resultado), 2);

                array_push(
                    $multResDivCaudal,
                    $mult
                );
            }

            //Paso 4: Sumatoria de multResDivCaudal
            foreach ($multResDivCaudal as $item) {
                $sumaCaudalesFinal += $item;
            }

            $sumaCaudalesFinal = round($sumaCaudalesFinal, 2);

            //Verifica si el resultado es menor al límite de cuantificación del parámetro
            $limiteGrasas = DB::table('parametros')->where('Id_parametro', 14)->first();

            if ($sumaCaudalesFinal < $limiteGrasas->Limite) {
                $sumaCaudalesFinal = "< " . $limiteGrasas->Limite;
            }
        }

        //echo  implode(" , ", $multResDivCaudal);        

        //**************************************FIN DE CALCULO DE CONCENTRACION CUANTIFICADA DE GRASAS ******************************

        //************************************** CALCULO DE COLIFORMES FECALES ******************************************************
        //Consulta si existe el parámetro de Coliformes Fecales en la solicitud
        $solicitudParametroColiformesFe = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Id_parametro', 13)->get();
        $solicitudParametroColiformesFeLength = $solicitudParametroColiformesFe->count();
        $resColi = 0;

        if ($solicitudParametroColiformesFeLength > 0) { //Encontró coliformes fecales
            $productoColi = 1;

            //Paso 1: Multiplicación de todos los resultados de coliformes fecales
            foreach ($solicitudParametroColiformesFe as $item) {
                $productoColi *= $item->Resultado;
            }

            //Paso 2: Raíz a la N cantidad de resultados de coliformes
            $resColi = round(pow($productoColi, 1 / $solicitudParametroColiformesFeLength), 2);

            //Verifica si el resultado es menor al límite de cuantificación del parámetro
            $limiteColi = DB::table('parametros')->where('Id_parametro', $solicitudParametroColiformesFe[0]->Id_parametro)->first();

            if ($resColi < $limiteColi->Limite) {
                $resColi = "< " . $limiteColi->Limite;
            }
        }
        //************************************** FIN DE CALCULO DE COLIFORMES FECALES *********************************************** 

        //************************************** CALCULO DE NITROGENO KJELDAHL **********************************************

        //Consulta si existen los parámetros nitrogeno total y amoniacal para esta solicitud
        $solParamAmoniacal = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Id_parametro', 10)->first();
        $solParamOrganico = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Id_parametro', 11)->first();

        //Sí existen los parámetros para el cálculo kjeldahl
        if (!is_null($solParamAmoniacal) && !is_null($solParamOrganico)) {
            //Verifica si el resultado es menor al límite de cuantificación del parámetro
            $limiteAmoniacal = DB::table('parametros')->where('Id_parametro', $solParamAmoniacal->Id_parametro)->first();
            $limiteOrganico = DB::table('parametros')->where('Id_parametro', $solParamOrganico->Id_parametro)->first();

            $resKjeldahl = 0;
            $resLimAmo = 0;
            $resLimOrg = 0;

            //Nitrogeno Amoniacal
            if ($solParamAmoniacal->Resultado < $limiteAmoniacal->Limite) {
                $resLimAmo = $limiteAmoniacal->Limite;
            } else {
                $resLimAmo = $solParamAmoniacal->Resultado;
            }

            //Nitrogeno Organico
            if ($solParamOrganico->Resultado < $limiteOrganico->Limite) {
                $resLimOrg = $limiteOrganico->Limite;
            } else {
                $resLimOrg = $solParamOrganico->Resultado;
            }

            $resKjeldahl = $resLimAmo + $resLimOrg;
        }

        //************************************** FIN DE CALCULO DE NITROGENO KJELDAHL ***************************************

        //***************************************CALCULO DE NITROGENO TOTAL *************************************************
        //Consulta si existen los parámetros nitrogeno total, amoniacal, nitritos y nitratos para esta solicitud
        $solParamAmoniacal = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Id_parametro', 10)->first();
        $solParamOrganico = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Id_parametro', 11)->first();
        $solParamNitritos = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Id_parametro', 9)->first();
        $solParamNitratos = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Id_parametro', 8)->first();

        //Sí existen los parámetros para el cálculo de nitrógeno total
        if (!is_null($solParamAmoniacal) && !is_null($solParamOrganico) && !is_null($solParamNitritos) && !is_null($solParamNitratos)) {
            //Verifica si el resultado es menor al límite de cuantificación del parámetro
            $limiteAmoniacal = DB::table('parametros')->where('Id_parametro', $solParamAmoniacal->Id_parametro)->first();
            $limiteOrganico = DB::table('parametros')->where('Id_parametro', $solParamOrganico->Id_parametro)->first();
            $limiteNitritos = DB::table('parametros')->where('Id_parametro', $solParamNitritos->Id_parametro)->first();
            $limiteNitratos = DB::table('parametros')->where('Id_parametro', $solParamNitratos->Id_parametro)->first();

            $resNitrogenoT = 0;
            $resLimAmo = 0;
            $resLimOrg = 0;
            $resLimNitritos = 0;
            $resLimNitratos = 0;

            //Nitrogeno Amoniacal
            if ($solParamAmoniacal->Resultado < $limiteAmoniacal->Limite) {
                $resLimAmo = $limiteAmoniacal->Limite;
            } else {
                $resLimAmo = $solParamAmoniacal->Resultado;
            }

            //Nitrogeno Orgánico
            if ($solParamOrganico->Resultado < $limiteOrganico->Limite) {
                $resLimOrg = $limiteOrganico->Limite;
            } else {
                $resLimOrg = $solParamOrganico->Resultado;
            }

            //Nitritos
            if ($solParamNitritos->Resultado < $limiteNitritos->Limite) {
                $resLimNitritos = $limiteNitritos->Limite;
            } else {
                $resLimNitritos = $solParamNitritos->Resultado;
            }

            //Nitratos
            if ($solParamNitratos->Resultado < $limiteNitratos->Limite) {
                $resLimNitratos = $limiteNitratos->Limite;
            } else {
                $resLimNitratos = $solParamNitratos->Resultado;
            }

            $resNitrogenoT = $resLimAmo + $resLimOrg + $resLimNitritos + $resLimNitratos;
        }
        //*************************************** FIN DE CALCULO DE NITROGENO TOTAL *****************************************

        //Recupera el gasto promedio para la solicitud dada
        $gModel = GastoMuestra::where('Id_solicitud', $idSol)->get();
        $gModelLength = $gastosModel->count();
        $gastosProm = 0;

        if(!is_null($gModel)){
            foreach($gModel as $item){
                if($item->Promedio == null){
                    $gastosProm += 0;
                }else{
                    $gastosProm += $item->Promedio;
                }                
            }

            $gastosProm /= $gModelLength;
        }

        //Recupera la temperatura promedio para la solicitud dada
        $tModel = TemperaturaMuestra::where('Id_solicitud', $idSol)->get();
        $tModelLength = $tModel->count();
        $tProm = 0;

        if(!is_null($tModel)){
            foreach($tModel as $item){
                if($item->Promedio == null){
                    $tProm += 0;
                }else{
                    $tProm += $item->Promedio;
                }                
            }

            $tProm /= $tModelLength;
        }

        //Recupera el pH promedio para la solicitud dada
        $phModel = PhMuestra::where('Id_solicitud', $idSol)->get();
        $phModelLength = $phModel->count();
        $phProm = 0;

        if(!is_null($phModel)){
            foreach($phModel as $item){
                if($item->Promedio == null){
                    $phProm += 0;
                }else{
                    $phProm += $item->Promedio;
                }                
            }

            $phProm /= $phModelLength;
        }

        $limitesC = array();

        //Recupera los límites de cuantificación de los parámetros        
        foreach ($solicitudParametros as $item) {
            $limiteC = DB::table('parametros')->where('Id_parametro', $item->Id_parametro)->first();

            if($item->Id_parametro == 27){ //Gasto
                array_push($limitesC, number_format($gastosProm, 2, ".", ","));
            }else if($item->Id_parametro == 98){ //Temperatura
                array_push($limitesC, number_format($tProm, 2, ".", ","));
            }else if($item->Id_parametro == 15){ //pH
                array_push($limitesC, number_format($phProm, 2, ".", ","));
            }else if ($item->Resultado < $limiteC->Limite) {
                $limC = "< " . $limiteC->Limite;

                array_push($limitesC, $limC);
            } else {  //Si es mayor el resultado que el límite de cuantificación
                if($item->Id_parametro == 20){   //Cianuros
                    $limC = number_format($item->Resultado, 3, ".", ",");
                }else if($item->Id_parametro == 13){   //Coliformes fecales
                    $limC = number_format($item->Resultado, 2, ".", ",");
                }else if($item->Id_parametro == 22){   //Cromo total
                    $limC = number_format($item->Resultado, 3, ".", ",");
                }else if($item->Id_parametro == 72){   //DBO
                    $limC = number_format($item->Resultado, 2, ".", ",");
                }else if($item->Id_parametro == 7){   //DQO
                    $limC = number_format($item->Resultado, 2, ".", ",");
                }else if($item->Id_parametro == 16){   //Fosforo
                    $limC = number_format($item->Resultado, 3, ".", ",");
                }else if($item->Id_parametro == 14){   //Grasas y Aceites
                    $limC = number_format($item->Resultado, 2, ".", ",");
                }else if($item->Id_parametro == 17){   //Huevos de helminto
                    $limC = number_format($item->Resultado, 0, ".", ",");
                }else if($item->Id_parametro == 23){   //Mercurio
                    $limC = number_format($item->Resultado, 4, ".", ",");
                }else if($item->Id_parametro == 8){   //Nitratos
                    $limC = number_format($item->Resultado, 3, ".", ",");
                }else if($item->Id_parametro == 9){   //Nitritos
                    $limC = number_format($item->Resultado, 3, ".", ",");
                }else if($item->Id_parametro == 10 || $item->Id_parametro == 11){   //Nitrogeno Amoniacal y Nitrogeno Organico
                    $limC = number_format($item->Resultado, 2, ".", ",");
                }else{
                    $limC = number_format($item->Resultado, 3, ".", ",");
                }                

                array_push($limitesC, $limC);
            }
        }

        $modelProcesoAnalisis = ProcesoAnalisis::where('Id_solicitud', $idSol)->first();

        //Recupera el valor correcto de la hora de muestreo
        if ($solicitud->Id_muestra !== 'COMPUESTA') {
            $horaMuestreo = \Carbon\Carbon::parse($modelProcesoAnalisis->Hora_entrada)->format('H:i:s');
        } else {
            $horaMuestreo = 'COMPUESTA';
        }        

        //Recupera la obs de campo
        $modelComp = CampoCompuesto::where('Id_solicitud', $idSol)->first();
        $obsCampo = $modelComp->Observaciones;

        //BODY;Por añadir validaciones, mismas que se irán implementando cuando haya una tabla en la BD para los informes
        $htmlInforme = view('exports.informes.sinComparacion.bodyInforme',  compact('solicitudParametros', 'solicitudParametrosLength', 'limitesC', 'tempCompuesta', 'sumaCaudalesFinal', 'resColi', 'sParam'));

        //HEADER-FOOTER******************************************************************************************************************                 
        $htmlHeader = view('exports.informes.sinComparacion.headerInforme', compact('solicitud', 'direccion', 'cliente', 'puntoMuestreo', 'numOrden', 'modelProcesoAnalisis', 'horaMuestreo'));
        $htmlFooter = view('exports.informes.sinComparacion.footerInforme', compact('solicitud', 'simbologiaParam', 'obsCampo'));

        $mpdf->setHeader("{PAGENO} / {nbpg} <br><br>" . $htmlHeader);
        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
        $mpdf->WriteHTML($htmlInforme);

        $mpdf->CSSselectMedia = 'mpdf';
        $mpdf->Output('Informe de resultados sin comparacion.pdf', 'D');
    }

    public function pdfConComparacionCli($idSol)
    {
        //Opciones del documento PDF
        $mpdf = new \Mpdf\Mpdf([
            'orientation' => 'P',
            'format' => 'letter',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 76,
            'margin_bottom' => 125,
            'defaultheaderfontstyle' => ['normal'],
            'defaultheaderline' => '0'
        ]);

        //Recupera el nombre de usuario y firma
        /* $usuario = DB::table('users')->where('id', auth()->user()->id)->first();
        $firma = $usuario->firma; */

        //Formatea la fecha; Por adaptar para el informe sin comparacion
        $fechaAnalisis = DB::table('ViewLoteAnalisis')->where('Id_lote', 0)->first();
        if (!is_null($fechaAnalisis)) {
            $fechaConFormato = date("d/m/Y", strtotime($fechaAnalisis->Fecha));
        }

        //$fechaEmision = \Carbon\Carbon::now();
        $solicitud = Solicitud::where('Id_solicitud', $idSol)->first();
        $direccion = DireccionReporte::where('Id_direccion', $solicitud->Id_direccion)->first();
        $folio = explode("-", $solicitud->Folio_servicio);
        $parte1 = strval($folio[0]);
        $parte2 = strval($folio[1]);

        $numOrden = Solicitud::where('Folio_servicio', $parte1 . "-" . $parte2)->first();
        //$cotizacion = Cotizacion::where('Folio_servicio', $folio[0])->first();
        //$cotizacion = Cotizacion::where('Folio_servicio', 'LIKE', "%{$solicitud->Folio_servicio}%")->get();
        $cliente = Clientes::where('Id_cliente', $solicitud->Id_cliente)->first();
        $solicitudPunto = SolicitudPuntos::where('Id_solicitud', $solicitud->Id_solicitud)->first();
        $puntoMuestreo = null;

        if($solicitud->Siralab == 1){//Es cliente Siralab
            $puntoMuestreo = PuntoMuestreoSir::where('Id_sucursal', $solicitud->Id_sucursal)->get();
            // $puntoMuestreo = SolicitudPuntos::where('Id_solicitud',$idSolicitud)->get();
            $puntos = $puntoMuestreo->count();
        }else{
            $puntoMuestreo = PuntoMuestreoGen::where('Id_sucursal', $solicitud->Id_sucursal)->get();
            // $puntoMuestreo = SolicitudPuntos::where('Id_solicitud',$idSolicitud)->get();
            $puntos = $puntoMuestreo->count();
        }

        /* $solicitudParametros = DB::table('ViewSolicitudParametros')->where('Id_solicitud', $solicitud->Id_solicitud)->get();
        $solicitudParametrosLength = $solicitudParametros->count(); */

        //Sirve para recuperar los límites de cuantificación de la norma
        //$promSol = $solicitud->Id_promedio; //Si es mensual o diario
        
        //Recupera los parámetros
        $solicitudParametros = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Num_muestra', 1)->orderBy('Parametro', 'ASC')->get();
        $solicitudParametrosLength = $solicitudParametros->count();
        $sParam = array();
        //$limNorm = array();

        foreach($solicitudParametros as $item){
            $paramModel = Parametro::where('Id_parametro', $item->Id_parametro)->first();
            $sP = SimbologiaParametros::where('Id_simbologia', $paramModel->Id_simbologia)->first();
            array_push($sParam, $sP->Simbologia);

            /* if($solicitud->Id_norma == 1){ //Norma 001
                Limite001::where('Id_parametro', $item->Id_parametro)->where('Id_')->first();
            }else if($solicitud->Id_norma == 2){ //Norma 002
                $param = Limite002::where('Id_parametro', $item->Id_parametro)->first();
            } */
        }

        //Recupera sin duplicados las simbologías de los parámetros
        $simbolParam = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->get();
        $simbolParam = $simbolParam->unique('Id_simbologia');
        $simbologiaParam = array();

        foreach($simbolParam as $item){
            array_push($simbologiaParam, $item->Id_simbologia);
        }        

        //*************************************CALCULO DE CONCENTRACIÓN CUANTIFICADA DE GRASAS *************************************
        //Consulta si existe el parámetro de Grasas y Aceites en la solicitud
        $solicitudParametroGrasas = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Id_parametro', 14)->get();
        $solicitudParametroGrasasLength = $solicitudParametroGrasas->count();

        if (!is_null($solicitudParametroGrasas)) { //Encontró grasas

            //Recupera los gastos (caudales) de la solicitud
            $gastosModel = GastoMuestra::where('Id_solicitud', $idSol)->get();
            $gastosModelLength = $gastosModel->count();
            $sumaCaudales = 0;
            $sumaCaudalesFinal = 0;

            //Arreglo que almacena el resultado de cada caudal entre la sumatoria de los caudales
            $divCaudalSuma = array();

            //Arreglo que almacena los resultados de las multiplicaciones de divCaudalSuma por el resultado de cada muestra del parámetro
            $multResDivCaudal = array();

            //Paso 1: Sumatoria de los caudales
            foreach ($gastosModel as $item) {
                if ($item->Promedio === null) {
                    $sumaCaudales += 0;
                } else {
                    $sumaCaudales += $item->Promedio;
                }
            }

            $sumaCaudales = round($sumaCaudales, 2);

            //Paso 2: División de cada caudal entre la sumatoria de los caudales
            foreach ($gastosModel as $item) {
                if ($item->Promedio === null) {
                    $div = 0 / $sumaCaudales;

                    array_push(
                        $divCaudalSuma,
                        $div
                    );
                } else {
                    $div = round(($item->Promedio / $sumaCaudales), 2);

                    array_push(
                        $divCaudalSuma,
                        $div
                    );
                }
            }

            //Paso 3: Multiplicación de cada divCaudalSuma por el resultado del parametro                                    
            for ($i = 0; $i < $solicitudParametroGrasasLength; $i++) {
                $mult = round(($divCaudalSuma[$i] * $solicitudParametroGrasas[$i]->Resultado), 2);

                array_push(
                    $multResDivCaudal,
                    $mult
                );
            }

            //Paso 4: Sumatoria de multResDivCaudal
            foreach ($multResDivCaudal as $item) {
                $sumaCaudalesFinal += $item;
            }

            $sumaCaudalesFinal = round($sumaCaudalesFinal, 2);

            //Verifica si el resultado es menor al límite de cuantificación del parámetro
            $limiteGrasas = DB::table('parametros')->where('Id_parametro', 14)->first();

            if ($sumaCaudalesFinal < $limiteGrasas->Limite) {
                $sumaCaudalesFinal = "< " . $limiteGrasas->Limite;
            }
        }

        //echo  implode(" , ", $multResDivCaudal);        

        //**************************************FIN DE CALCULO DE CONCENTRACION CUANTIFICADA DE GRASAS ******************************

        //************************************** CALCULO DE COLIFORMES FECALES ******************************************************
        //Consulta si existe el parámetro de Coliformes Fecales en la solicitud
        $solicitudParametroColiformesFe = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Id_parametro', 13)->get();
        $solicitudParametroColiformesFeLength = $solicitudParametroColiformesFe->count();
        $resColi = 0;

        if ($solicitudParametroColiformesFeLength > 0) { //Encontró coliformes fecales
            $productoColi = 1;

            //Paso 1: Multiplicación de todos los resultados de coliformes fecales
            foreach ($solicitudParametroColiformesFe as $item) {
                $productoColi *= $item->Resultado;
            }

            //Paso 2: Raíz a la N cantidad de resultados de coliformes
            $resColi = round(pow($productoColi, 1 / $solicitudParametroColiformesFeLength), 2);

            //Verifica si el resultado es menor al límite de cuantificación del parámetro
            $limiteColi = DB::table('parametros')->where('Id_parametro', $solicitudParametroColiformesFe[0]->Id_parametro)->first();

            if ($resColi < $limiteColi->Limite) {
                $resColi = "< " . $limiteColi->Limite;
            }
        }
        //************************************** FIN DE CALCULO DE COLIFORMES FECALES *********************************************** 

        //************************************** CALCULO DE NITROGENO KJELDAHL **********************************************

        //Consulta si existen los parámetros nitrogeno total y amoniacal para esta solicitud
        $solParamAmoniacal = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Id_parametro', 10)->first();
        $solParamOrganico = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Id_parametro', 11)->first();

        //Sí existen los parámetros para el cálculo kjeldahl
        if (!is_null($solParamAmoniacal) && !is_null($solParamOrganico)) {
            //Verifica si el resultado es menor al límite de cuantificación del parámetro
            $limiteAmoniacal = DB::table('parametros')->where('Id_parametro', $solParamAmoniacal->Id_parametro)->first();
            $limiteOrganico = DB::table('parametros')->where('Id_parametro', $solParamOrganico->Id_parametro)->first();

            $resKjeldahl = 0;
            $resLimAmo = 0;
            $resLimOrg = 0;

            //Nitrogeno Amoniacal
            if ($solParamAmoniacal->Resultado < $limiteAmoniacal->Limite) {
                $resLimAmo = $limiteAmoniacal->Limite;
            } else {
                $resLimAmo = $solParamAmoniacal->Resultado;
            }

            //Nitrogeno Organico
            if ($solParamOrganico->Resultado < $limiteOrganico->Limite) {
                $resLimOrg = $limiteOrganico->Limite;
            } else {
                $resLimOrg = $solParamOrganico->Resultado;
            }

            $resKjeldahl = $resLimAmo + $resLimOrg;
        }

        //************************************** FIN DE CALCULO DE NITROGENO KJELDAHL ***************************************

        //***************************************CALCULO DE NITROGENO TOTAL *************************************************
        //Consulta si existen los parámetros nitrogeno total, amoniacal, nitritos y nitratos para esta solicitud
        $solParamAmoniacal = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Id_parametro', 10)->first();
        $solParamOrganico = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Id_parametro', 11)->first();
        $solParamNitritos = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Id_parametro', 9)->first();
        $solParamNitratos = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Id_parametro', 8)->first();

        //Sí existen los parámetros para el cálculo kjeldahl
        if (!is_null($solParamAmoniacal) && !is_null($solParamOrganico) && !is_null($solParamNitritos) && !is_null($solParamNitratos)) {
            //Verifica si el resultado es menor al límite de cuantificación del parámetro
            $limiteAmoniacal = DB::table('parametros')->where('Id_parametro', $solParamAmoniacal->Id_parametro)->first();
            $limiteOrganico = DB::table('parametros')->where('Id_parametro', $solParamOrganico->Id_parametro)->first();
            $limiteNitritos = DB::table('parametros')->where('Id_parametro', $solParamNitritos->Id_parametro)->first();
            $limiteNitratos = DB::table('parametros')->where('Id_parametro', $solParamNitratos->Id_parametro)->first();

            $resNitrogenoT = 0;
            $resLimAmo = 0;
            $resLimOrg = 0;
            $resLimNitritos = 0;
            $resLimNitratos = 0;

            //Nitrogeno Amoniacal
            if ($solParamAmoniacal->Resultado < $limiteAmoniacal->Limite) {
                $resLimAmo = $limiteAmoniacal->Limite;
            } else {
                $resLimAmo = $solParamAmoniacal->Resultado;
            }

            //Nitrogeno Organico
            if ($solParamOrganico->Resultado < $limiteOrganico->Limite) {
                $resLimOrg = $limiteOrganico->Limite;
            } else {
                $resLimOrg = $solParamOrganico->Resultado;
            }

            //Nitritos
            if ($solParamNitritos->Resultado < $limiteNitritos->Limite) {
                $resLimNitritos = $limiteNitritos->Limite;
            } else {
                $resLimNitritos = $solParamNitritos->Resultado;
            }

            //Nitratos
            if ($solParamNitratos->Resultado < $limiteNitratos->Limite) {
                $resLimNitratos = $limiteNitratos->Limite;
            } else {
                $resLimNitratos = $solParamNitratos->Resultado;
            }

            $resNitrogenoT = $resLimAmo + $resLimOrg + $resLimNitritos + $resLimNitratos;
        }
        //*************************************** FIN DE CALCULO DE NITROGENO TOTAL *****************************************

        //Recupera el gasto promedio para la solicitud dada
        $gModel = GastoMuestra::where('Id_solicitud', $idSol)->get();
        $gModelLength = $gastosModel->count();
        $gastosProm = 0;

        if(!is_null($gModel)){
            foreach($gModel as $item){
                if($item->Promedio == null){
                    $gastosProm += 0;
                }else{
                    $gastosProm += $item->Promedio;
                }                
            }

            $gastosProm /= $gModelLength;
        }

        //Recupera la temperatura promedio para la solicitud dada
        $tModel = TemperaturaMuestra::where('Id_solicitud', $idSol)->get();
        $tModelLength = $tModel->count();
        $tProm = 0;

        if(!is_null($tModel)){
            foreach($tModel as $item){
                if($item->Promedio == null){
                    $tProm += 0;
                }else{
                    $tProm += $item->Promedio;
                }                
            }

            $tProm /= $tModelLength;
        }

        //Recupera el pH promedio para la solicitud dada
        $phModel = PhMuestra::where('Id_solicitud', $idSol)->get();
        $phModelLength = $phModel->count();
        $phProm = 0;

        if(!is_null($phModel)){
            foreach($phModel as $item){
                if($item->Promedio == null){
                    $phProm += 0;
                }else{
                    $phProm += $item->Promedio;
                }                
            }

            $phProm /= $phModelLength;
        }

        $limitesC = array();

        //Recupera los límites de cuantificación de los parámetros        
        foreach ($solicitudParametros as $item) {
            $limiteC = DB::table('parametros')->where('Id_parametro', $item->Id_parametro)->first();

            if($item->Id_parametro == 27){ //Gasto
                array_push($limitesC, number_format($gastosProm, 2, ".", ","));
            }else if($item->Id_parametro == 98){ //Temperatura
                array_push($limitesC, number_format($tProm, 2, ".", ","));
            }else if($item->Id_parametro == 15){ //pH
                array_push($limitesC, number_format($phProm, 2, ".", ","));
            }else if ($item->Resultado < $limiteC->Limite) {
                $limC = "< " . $limiteC->Limite;

                array_push($limitesC, $limC);
            } else {  //Si es mayor el resultado que el límite de cuantificación
                if($item->Id_parametro == 7){   //DQO
                    $limC = number_format($item->Resultado, 2, ".", ",");
                }else if($item->Id_parametro == 20){   //Cianuros
                    $limC = number_format($item->Resultado, 3, ".", ",");
                }else if($item->Id_parametro == 13){   //Coliformes fecales
                    $limC = number_format($item->Resultado, 2, ".", ",");
                }else if($item->Id_parametro == 22){   //Cromo total
                    $limC = number_format($item->Resultado, 3, ".", ",");
                }else if($item->Id_parametro == 72){   //DBO
                    $limC = number_format($item->Resultado, 2, ".", ",");
                }else if($item->Id_parametro == 7){   //DQO
                    $limC = number_format($item->Resultado, 2, ".", ",");
                }else if($item->Id_parametro == 16){   //Fosforo
                    $limC = number_format($item->Resultado, 3, ".", ",");
                }else if($item->Id_parametro == 14){   //Grasas y Aceites
                    $limC = number_format($item->Resultado, 2, ".", ",");
                }else if($item->Id_parametro == 17){   //Huevos de helminto
                    $limC = number_format($item->Resultado, 0, ".", ",");
                }else if($item->Id_parametro == 23){   //Mercurio
                    $limC = number_format($item->Resultado, 4, ".", ",");
                }else if($item->Id_parametro == 8){   //Nitratos
                    $limC = number_format($item->Resultado, 3, ".", ",");
                }else if($item->Id_parametro == 9){   //Nitritos
                    $limC = number_format($item->Resultado, 3, ".", ",");
                }else if($item->Id_parametro == 10 || $item->Id_parametro == 11){   //Nitrogeno Amoniacal y Nitrogeno Organico
                    $limC = number_format($item->Resultado, 2, ".", ",");
                }else{
                    $limC = number_format($item->Resultado, 3, ".", ",");
                }

                array_push($limitesC, $limC);
            }
        }

        $modelProcesoAnalisis = ProcesoAnalisis::where('Id_solicitud', $idSol)->first();

        //Recupera el valor correcto de la hora de muestreo
        if ($solicitud->Id_muestra !== 'COMPUESTA') {
            $horaMuestreo = \Carbon\Carbon::parse($modelProcesoAnalisis->Hora_entrada)->format('H:i:s');
        } else {
            $horaMuestreo = 'COMPUESTA';
        }

        //Recupera la obs de campo
        $modelComp = CampoCompuesto::where('Id_solicitud', $idSol)->first();
        $obsCampo = $modelComp->Observaciones;

        //BODY;Por añadir validaciones, mismas que se irán implementando cuando haya una tabla en la BD para los informes
        $htmlInforme = view('exports.informes.conComparacion.bodyComparacionInforme',  compact('solicitudParametros', 'solicitudParametrosLength', 'limitesC', 'sumaCaudalesFinal', 'resColi', 'sParam', 'puntoMuestreo'));

        //HEADER-FOOTER******************************************************************************************************************                 
        $htmlHeader = view('exports.informes.conComparacion.headerComparacionInforme', compact('solicitud', 'direccion', 'cliente', 'puntoMuestreo', 'numOrden', 'horaMuestreo', 'modelProcesoAnalisis'));
        $htmlFooter = view('exports.informes.conComparacion.footerComparacionInforme', compact('solicitud', 'simbologiaParam', 'obsCampo'));

        $mpdf->setHeader("{PAGENO} / {nbpg} <br><br>" . $htmlHeader);
        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
        $mpdf->WriteHTML($htmlInforme);

        $mpdf->CSSselectMedia = 'mpdf';
        $mpdf->Output('Informe de resultados con comparacion.pdf', 'D');
    }

    //************************************************************************************************

    public function pdfSinComparacion2($idSol)
    {
        //Opciones del documento PDF
        $mpdf = new \Mpdf\Mpdf([
            'orientation' => 'L',
            'format' => 'letter',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 74,
            'margin_bottom' => 76,
            'defaultheaderfontstyle' => ['normal'],
            'defaultheaderline' => '0'
        ]);            
        
        // Hace los filtros para realizar la comparacion
        $solModel = DB::table('ViewSolicitud')->where('Id_solicitud', $idSol)->first();
        $solModel2 = DB::table('ViewSolicitud')->where('IdPunto', $solModel->IdPunto)->OrderBy('Id_solicitud', 'DESC')->get();

        //ViewCodigoParametro
        $cont = (sizeof($solModel2) - 1);
        
        $model = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Num_muestra', 1)->orderBy('Parametro', 'ASC')->get();
        $modelLength = $model->count();

        $sParam = array();

        foreach($model as $item){
            $paramModel = Parametro::where('Id_parametro', $item->Id_parametro)->first();
            $sP = SimbologiaParametros::where('Id_simbologia', $paramModel->Id_simbologia)->first();
            array_push($sParam, $sP->Simbologia);
        }

        //Recupera sin duplicados las simbologías de los parámetros
        $simbolParam = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->get();
        $simbolParam = $simbolParam->unique('Id_simbologia');
        $simbologiaParam = array();

        foreach($simbolParam as $item){
            array_push($simbologiaParam, $item->Id_simbologia);
        }

        $model2 = DB::table('ViewCodigoParametro')->where('Id_solicitud', $solModel2[0]->Id_solicitud)->where('Num_muestra', 1)->orderBy('Parametro', 'ASC')->get();

        //Formatea la fecha; Por adaptar para el informe sin comparacion
        $fechaAnalisis = DB::table('ViewLoteAnalisis')->where('Id_lote', 0)->first();
        if (!is_null($fechaAnalisis)) {
            $fechaConFormato = date("d/m/Y", strtotime($fechaAnalisis->Fecha));
        }        
        
        //Obtiene la norma------------------------
        $norma = Norma::where('Id_norma', $solModel->Id_norma)->first();
        
        //Obtiene la dirección del reporte---------------------
        $direccion = DireccionReporte::where('Id_direccion', $solModel->Id_direccion)->first();                
        
        //Recupera el nombre del cliente---------------
        $cliente = Clientes::where('Id_cliente', $solModel->Id_cliente)->first();

        //Recupera el punto de muestreo del informe-----------------
        $solicitudPunto = SolicitudPuntos::where('Id_solicitud', $solModel->Id_solicitud)->first();
        $puntoMuestreo = DB::table('puntos_muestreo')->where('Id_punto', $solicitudPunto->Id_punto)->first();

        //*************************************CALCULO DE CONCENTRACIÓN CUANTIFICADA DE GRASAS*************************************
        //Consulta si existe el parámetro de Grasas y Aceites en la solicitud
        $solicitudParametroGrasas = DB::table('ViewCodigoParametro')->where('Id_solicitud', $solModel->Id_solicitud)->where('Id_parametro', 14)->get();
        $solicitudParametroGrasasLength = $solicitudParametroGrasas->count();

        $solicitudParametroGrasas2 = DB::table('ViewCodigoParametro')->where('Id_solicitud', $solModel2[0]->Id_solicitud)->where('Id_parametro', 14)->get();
        $solicitudParametroGrasasLength2 = $solicitudParametroGrasas2->count();

        $sumaCaudales = 0;
        $sumaCaudales2 = 0;

        //Establece si debe mostrarse o no el promedio ponderado de los caudales
        $limExceed1  = 0;
        $limExceed2  = 0;

        //Límite de grasas
        $limGras = 0;
        $limGrasas = DB::table('parametros')->where('Id_parametro', 14)->first();

        //Cálculo grasas de la segunda solicitud
        $sumaCaudalesFinal = 0;

        //Cálculo grasas de la segunda solicitud
        $sumaCaudalesFinal2 = 0;                

        //Calcula Grasas para el primer folio
        if ($solicitudParametroGrasasLength > 0) { //Encontró grasas

            //Recupera los gastos (caudales) de la solicitud
            $gastosModel = GastoMuestra::where('Id_solicitud', $solModel->Id_solicitud)->get();
            $gastosModelLength = $gastosModel->count(); 
            
            if($gastosModelLength > 0){
                //Arreglo que almacena el resultado de cada caudal entre la sumatoria de los caudales
                $divCaudalSuma = array();

                //Arreglo que almacena los resultados de las multiplicaciones de divCaudalSuma por el resultado de cada muestra del parámetro
                $multResDivCaudal = array();

                //Paso 1: Sumatoria de los caudales
                foreach ($gastosModel as $item) {
                    if ($item->Promedio === null) {
                        $sumaCaudales += 0;
                    } else {
                        $sumaCaudales += $item->Promedio;
                    }
                }

                $sumaCaudales = round($sumaCaudales, 2);

                //Paso 2: División de cada caudal entre la sumatoria de los caudales
                foreach ($gastosModel as $item) {
                    if ($item->Promedio === null) {
                        $div = 0 / $sumaCaudales;

                        array_push(
                            $divCaudalSuma,
                            $div
                        );
                    } else {
                        $div = round(($item->Promedio / $sumaCaudales), 2);

                        array_push(
                            $divCaudalSuma,
                            $div
                        );
                    }
                }

                //Paso 3: Multiplicación de cada divCaudalSuma por el resultado del parametro                                    
                for ($i = 0; $i < $solicitudParametroGrasasLength; $i++) {
                    $mult = round(($divCaudalSuma[$i] * $solicitudParametroGrasas[$i]->Resultado), 2);

                    array_push(
                        $multResDivCaudal,
                        $mult
                    );
                }

                //Paso 4: Sumatoria de multResDivCaudal
                foreach ($multResDivCaudal as $item) {
                    $sumaCaudalesFinal += $item;
                }                

                $sumaCaudalesFinal = round($sumaCaudalesFinal, 2);
            }            
        }

        //Verifica si el resultado es menor al límite de cuantificación del parámetro
        $limiteGrasas = DB::table('parametros')->where('Id_parametro', 14)->first();

        if ($sumaCaudalesFinal < $limiteGrasas->Limite) {
            $sumaCaudalesFinal = "< " . $limiteGrasas->Limite;
            $limExceed1 = 1;
            $limGras = $limiteGrasas->Limite;
        }        

        //Calcula Grasas para el segundo folio        
        if ($solicitudParametroGrasasLength2 > 0) { //Encontró grasas

            //Recupera los gastos (caudales) de la solicitud
            $gastosModel = GastoMuestra::where('Id_solicitud', $solModel2[0]->Id_solicitud)->get();
            
            $gastosModelLength = $gastosModel->count(); 
            
            if($gastosModelLength > 0){ //Encontró grasas
                //Arreglo que almacena el resultado de cada caudal entre la sumatoria de los caudales
                $divCaudalSuma = array();

                //Arreglo que almacena los resultados de las multiplicaciones de divCaudalSuma por el resultado de cada muestra del parámetro
                $multResDivCaudal = array();

                //Paso 1: Sumatoria de los caudales
                foreach ($gastosModel as $item) {
                    if ($item->Promedio === null) {
                        $sumaCaudales2 += 0;
                    } else {
                        $sumaCaudales2 += $item->Promedio;
                    }
                }

                $sumaCaudales2 = round($sumaCaudales2, 2);

                //Paso 2: División de cada caudal entre la sumatoria de los caudales
                foreach ($gastosModel as $item) {
                    if ($item->Promedio === null) {
                        $div = 0 / $sumaCaudales2;

                        array_push(
                            $divCaudalSuma,
                            $div
                        );
                    } else {
                        $div = round(($item->Promedio / $sumaCaudales2), 2);

                        array_push(
                            $divCaudalSuma,
                            $div
                        );
                    }
                }

                //Paso 3: Multiplicación de cada divCaudalSuma por el resultado del parametro                                    
                for ($i = 0; $i < $solicitudParametroGrasasLength2; $i++) {
                    $mult = round(($divCaudalSuma[$i] * $solicitudParametroGrasas2[$i]->Resultado), 2);

                    array_push(
                        $multResDivCaudal,
                        $mult
                    );
                }

                //Paso 4: Sumatoria de multResDivCaudal
                foreach ($multResDivCaudal as $item) {
                    $sumaCaudalesFinal2 += $item;
                }   
                
                $sumaCaudalesFinal2 = round(($sumaCaudalesFinal2), 2);
            }                                  
        }

        //Verifica si el resultado es menor al límite de cuantificación del parámetro
        $limiteGrasas = DB::table('parametros')->where('Id_parametro', 14)->first();
                
        if ($sumaCaudalesFinal2 < $limiteGrasas->Limite) {
            $sumaCaudalesFinal2 = "< " . $limiteGrasas->Limite;
            $limExceed2 = 1;
            $limGras = $limiteGrasas->Limite;
        }        
        
        //**************************************FIN DE CALCULO DE CONCENTRACION CUANTIFICADA DE GRASAS ******************************

        //************************************** CALCULO DE COLIFORMES FECALES******************************************************
        //Consulta si existe el parámetro de Coliformes Fecales en la solicitud
        $solicitudParametroColiformesFe = DB::table('ViewCodigoParametro')->where('Id_solicitud', $solModel->Id_solicitud)->where('Id_parametro', 13)->get();
        $solicitudParametroColiformesFeLength = $solicitudParametroColiformesFe->count();

        $solicitudParametroColiformesFe2 = DB::table('ViewCodigoParametro')->where('Id_solicitud', $solModel2[0]->Id_solicitud)->where('Id_parametro', 13)->get();
        $solicitudParametroColiformesFeLength2 = $solicitudParametroColiformesFe2->count();

        //Establece si debe mostrarse o no el promedio ponderado de los coliformes
        $limExceedColi1  = 0;
        $limExceedColi2  = 0;
        $limColi = 0;

        //Calculo de coliformes de la primera solicitud
        $resColi = 0;

        //Calculo de coliformes de la segunda solicitud
        $resColi2 = 0;

        if ($solicitudParametroColiformesFeLength > 0) { //Encontró coliformes fecales para folio 1
            $productoColi = 1;

            //Paso 1: Multiplicación de todos los resultados de coliformes fecales
            foreach ($solicitudParametroColiformesFe as $item) {
                $productoColi *= $item->Resultado;
            }

            //Paso 2: Raíz a la N cantidad de resultados de coliformes
            $resColi = round(pow($productoColi, 1 / $solicitudParametroColiformesFeLength), 2);
        }        

        //Verifica si el resultado es menor al límite de cuantificación del parámetro
        $limiteColi = DB::table('parametros')->where('Id_parametro', 13)->first();

        if ($resColi < $limiteColi->Limite) {
            $resColi = "< " . $limiteColi->Limite;
            $limExceedColi1 = 1;
            $limColi = $limiteColi->Limite;
        }



        if ($solicitudParametroColiformesFeLength2 > 0) { //Encontró coliformes fecales para folio 2
            $productoColi = 1;

            //Paso 1: Multiplicación de todos los resultados de coliformes fecales
            foreach ($solicitudParametroColiformesFe2 as $item) {
                $productoColi *= $item->Resultado;
            }

            //Paso 2: Raíz a la N cantidad de resultados de coliformes
            $resColi2 = round(pow($productoColi, 1 / $solicitudParametroColiformesFeLength2), 2);
        }

        //Verifica si el resultado es menor al límite de cuantificación del parámetro
        $limiteColi2 = DB::table('parametros')->where('Id_parametro', 13)->first();

        if ($resColi2 < $limiteColi2->Limite) {
            $resColi2 = "< " . $limiteColi2->Limite;
            $limExceedColi2 = 1;
            $limColi = $limiteColi->Limite;
        }
        //************************************** FIN DE CALCULO DE COLIFORMES FECALES*********************************************** 

        //Recupera el gasto promedio para la solicitud dada
        $gModel = GastoMuestra::where('Id_solicitud', $idSol)->get();
        $gModelLength = $gastosModel->count();
        $gastosProm = 0;

        if(!is_null($gModel)){
            foreach($gModel as $item){
                if($item->Promedio == null){
                    $gastosProm += 0;
                }else{
                    $gastosProm += $item->Promedio;
                }                
            }

            $gastosProm /= $gModelLength;
        }

        //Recupera la temperatura promedio para la solicitud dada
        $tModel = TemperaturaMuestra::where('Id_solicitud', $idSol)->get();
        $tModelLength = $tModel->count();
        $tProm = 0;

        if(!is_null($tModel)){
            foreach($tModel as $item){
                if($item->Promedio == null){
                    $tProm += 0;
                }else{
                    $tProm += $item->Promedio;
                }                
            }

            $tProm /= $tModelLength;
        }

        //Recupera el pH promedio para la solicitud dada
        $phModel = PhMuestra::where('Id_solicitud', $idSol)->get();
        $phModelLength = $phModel->count();
        $phProm = 0;

        if(!is_null($phModel)){
            foreach($phModel as $item){
                if($item->Promedio == null){
                    $phProm += 0;
                }else{
                    $phProm += $item->Promedio;
                }                
            }

            $phProm /= $phModelLength;
        }

        $limiteMostrar = array();

        //Almacena ya sea el resultado del parametro o el limite de cuantificacion
        $limitesC = array();          
        
        //Almacena los resultados de los parametros
        $resultsParam = array();

        //Almacena únicamente los límites de cuantificación
        $limitesNorma = array();

        //Recupera los límites de cuantificación de los parámetros del primer folio
        foreach ($model as $item) {
            $limiteC = DB::table('parametros')->where('Id_parametro', $item->Id_parametro)->first();            
            
            echo $item->Parametro."<br>";
            echo $limiteC->Limite."<br>";

            //
            /* switch($item->Id_parametro){
                case 14: 
            } */

            if($item->Id_parametro == 27){ //Gasto
                array_push($limitesC, number_format($gastosProm, 2, ".", ","));
            }else if($item->Id_parametro == 98){ //Temperatura
                array_push($limitesC, number_format($tProm, 2, ".", ","));
            }else if($item->Id_parametro == 15){ //pH
                array_push($limitesC, number_format($phProm, 2, ".", ","));
            }else if ($item->Resultado < $limiteC->Limite) {
                $limC = "< " .$limiteC->Limite;

                array_push($limiteMostrar, 1);
                array_push($limitesC, $limC);
            } else {  //Si es mayor el resultado que el límite de cuantificación
                if($item->Id_parametro == 20){   //Cianuros
                    $limC = number_format($item->Resultado, 3, ".", ",");
                }else if($item->Id_parametro == 13){   //Coliformes fecales
                    $limC = number_format($item->Resultado, 2, ".", ",");
                }else if($item->Id_parametro == 22){   //Cromo total
                    $limC = number_format($item->Resultado, 3, ".", ",");
                }else if($item->Id_parametro == 72){   //DBO
                    $limC = number_format($item->Resultado, 2, ".", ",");
                }else if($item->Id_parametro == 7){   //DQO
                    $limC = number_format($item->Resultado, 2, ".", ",");
                }else if($item->Id_parametro == 16){   //Fosforo
                    $limC = number_format($item->Resultado, 3, ".", ",");
                }else if($item->Id_parametro == 14){   //Grasas y Aceites
                    $limC = number_format($item->Resultado, 2, ".", ",");
                }else if($item->Id_parametro == 17){   //Huevos de helminto
                    $limC = number_format($item->Resultado, 0, ".", ",");
                }else if($item->Id_parametro == 23){   //Mercurio
                    $limC = number_format($item->Resultado, 4, ".", ",");
                }else if($item->Id_parametro == 8){   //Nitratos
                    $limC = number_format($item->Resultado, 3, ".", ",");
                }else if($item->Id_parametro == 9){   //Nitritos
                    $limC = number_format($item->Resultado, 3, ".", ",");
                }else if($item->Id_parametro == 10 || $item->Id_parametro == 11){   //Nitrogeno Amoniacal y Nitrogeno Organico
                    $limC = number_format($item->Resultado, 2, ".", ",");
                }else{
                    $limC = number_format($item->Resultado, 3, ".", ",");
                }       
                
                echo $item->Parametro."<br>";
                echo $limiteC->Limite."<br>";
                                            
                array_push($limiteMostrar, 0);
                array_push($limitesC, $limC);
            }

            array_push($limitesNorma, $limiteC->Limite);
            array_push($resultsParam, $item->Resultado);
        }        

        //Recupera la fecha de recepción del primer y segundo folio
        $modelProcesoAnalisis1 = ProcesoAnalisis::where('Id_solicitud', $solModel->Id_solicitud)->first();
        $modelProcesoAnalisis2 = ProcesoAnalisis::where('Id_solicitud', $solModel2[0]->Id_solicitud)->first();

        //Calcula Gasto LPS1********************************************************************
        $gastosModel = GastoMuestra::where('Id_solicitud', $solModel->Id_solicitud)->get();
        $gastosModelLength = $gastosModel->count();
        $gastoSum1 = 0;
        $gastoLPS1 = 0;
        
        if($gastosModelLength > 0){
            //Paso 1: Sumatoria de los caudales
            foreach ($gastosModel as $item) {
                if ($item->Promedio === null) {
                    $gastoSum1 += 0;
                } else {
                    $gastoSum1 += $item->Promedio;
                }
            }

            //Paso 2: División entre el total de gastos
            $gastoLPS1 = $gastoSum1 / $gastosModelLength;
        } 

        //Calcula Gasto LPS2*********************************************************************     
                
        $gastosModel2 = GastoMuestra::where('Id_solicitud', $solModel2[0]->Id_solicitud)->get();
        $gastosModelLength2 = $gastosModel2->count();
        $gastoSum2 = 0;
        $gastoLPS2 = 0;

        if($gastosModelLength2 > 0){
            //Paso 1: Sumatoria de los caudales
            foreach ($gastosModel2 as $item) {
                if ($item->Promedio === null) {
                    $gastoSum2 += 0;
                } else {
                    $gastoSum2 += $item->Promedio;
                }
            }

            //Paso 2: División entre el total de gastos            
            $gastoLPS2 = $gastoSum2 / $gastosModelLength2;
        }      
        //*******************************************************************************************  

        //Recupera el gasto promedio para la solicitud dada
                
        $gModel2 = GastoMuestra::where('Id_solicitud', $solModel2[0]->Id_solicitud)->get();        
        $gModelLength2 = $gastosModel->count();
        $gastosProm2 = 0;

        if($gModelLength2 > 0){
            foreach($gModel2 as $item){
                if($item->Promedio == null){
                    $gastosProm2 += 0;
                }else{
                    $gastosProm2 += $item->Promedio;
                }                
            }

            $gastosProm2 /= $gModelLength2;
        }        

        //Recupera la temperatura promedio para la solicitud dada
        $tModel2 = TemperaturaMuestra::where('Id_solicitud', $solModel2[0]->Id_solicitud)->get();
        $tModelLength2 = $tModel2->count();
        $tProm2 = 0;

        if($tModelLength2 > 0){
            foreach($tModel2 as $item){
                if($item->Promedio == null){
                    $tProm2 += 0;
                }else{
                    $tProm2 += $item->Promedio;
                }                
            }

            $tProm2 /= $tModelLength2;
        }

        //Recupera el pH promedio para la solicitud dada
        $phModel2 = PhMuestra::where('Id_solicitud', $solModel2[0]->Id_solicitud)->get();
        $phModelLength2 = $phModel2->count();
        $phProm2 = 0;

        if($phModelLength2 > 0){
            foreach($phModel2 as $item){
                if($item->Promedio == null){
                    $phProm2 += 0;
                }else{
                    $phProm2 += $item->Promedio;
                }                
            }

            $phProm2 /= $phModelLength2;
        }

        $limiteMostrar2 = array();
        $limites2C = array();  
        $resultsParam2 = array();              

        /* $solicitudParametros2 = DB::table('ViewCodigoParametro')->where('Id_solicitud', $solModel2[0]->Id_solicitud)->where('Num_muestra', 1)->get();
        $solicitudParametros2Length = $solicitudParametros2->count(); */

        //Recupera los límites de cuantificación de los parámetros
        foreach ($model2 as $item) {
            $limite2C = DB::table('parametros')->where('Id_parametro', $item->Id_parametro)->first();

            if($item->Id_parametro == 27){ //Gasto
                array_push($limites2C, number_format($gastosProm2, 2, ".", ","));
            }else if($item->Id_parametro == 98){ //Temperatura
                array_push($limites2C, number_format($tProm2, 2, ".", ","));
            }else if($item->Id_parametro == 15){ //pH
                array_push($limites2C, number_format($phProm2, 2, ".", ","));
            }else if ($item->Resultado < $limite2C->Limite) {
                $lim2C = "< " . number_format($limite2C->Limite, 3, ".", ",");

                array_push($limiteMostrar2, 1);
                array_push($limites2C, $lim2C);
            } else {  //Si es mayor el resultado que el límite de cuantificación
                if($item->Id_parametro == 20){   //Cianuros
                    $lim2C = number_format($item->Resultado, 3, ".", ",");
                }else if($item->Id_parametro == 13){   //Coliformes fecales
                    $lim2C = number_format($item->Resultado, 2, ".", ",");
                }else if($item->Id_parametro == 22){   //Cromo total
                    $lim2C = number_format($item->Resultado, 3, ".", ",");
                }else if($item->Id_parametro == 72){   //DBO
                    $lim2C = number_format($item->Resultado, 2, ".", ",");
                }else if($item->Id_parametro == 7){   //DQO
                    $lim2C = number_format($item->Resultado, 2, ".", ",");
                }else if($item->Id_parametro == 16){   //Fosforo
                    $lim2C = number_format($item->Resultado, 3, ".", ",");
                }else if($item->Id_parametro == 14){   //Grasas y Aceites
                    $lim2C = number_format($item->Resultado, 2, ".", ",");
                }else if($item->Id_parametro == 17){   //Huevos de helminto
                    $lim2C = number_format($item->Resultado, 0, ".", ",");
                }else if($item->Id_parametro == 23){   //Mercurio
                    $lim2C = number_format($item->Resultado, 4, ".", ",");
                }else if($item->Id_parametro == 8){   //Nitratos
                    $lim2C = number_format($item->Resultado, 3, ".", ",");
                }else if($item->Id_parametro == 9){   //Nitritos
                    $lim2C = number_format($item->Resultado, 3, ".", ",");
                }else if($item->Id_parametro == 10 || $item->Id_parametro == 11){   //Nitrogeno Amoniacal y Nitrogeno Organico
                    $limC2 = number_format($item->Resultado, 2, ".", ",");
                }else{
                    $lim2C = number_format($item->Resultado, 3, ".", ",");
                }
                
                array_push($limiteMostrar2, 0);
                array_push($limites2C, $lim2C);
            }
            array_push($resultsParam2, $item->Resultado);
        }  

        //*************************************** CÁLCULO DQO ********************************************************
        $solicitudParamDqo = DB::table('ViewCodigoParametro')->where('Id_solicitud', $solModel->Id_solicitud)->where('Id_parametro', 7)->first();        

        $solicitudParamDqo2 = DB::table('ViewCodigoParametro')->where('Id_solicitud', $solModel2[0]->Id_solicitud)->where('Id_parametro', 7)->first();        

        //Establece si debe mostrarse o no el promedio ponderado de los DQO
        $limExceedDqo1  = 0;
        $limExceedDqo2  = 0;

        //Límite de DQO
        $limDqo = 0;

        //Cálculo DQO de la segunda solicitud
        $dqoFinal1 = 0;
        
        //Cálculo DQO de la segunda solicitud
        $dqoFinal2 = 0;

        //Cálculo DQO del primer folio
        if(!is_null($solicitudParamDqo)){            
            $preCalculo = $gastoLPS1 / ($gastoLPS1 + $gastoLPS2);
            $dqoFinal1 = round(($solicitudParamDqo->Resultado * $preCalculo), 2);
        }

        //Verifica si el resultado es menor al límite de cuantificación del parámetro
        $limiteDqo = DB::table('parametros')->where('Id_parametro', 7)->first();

        if ($dqoFinal1 < $limiteDqo->Limite) {
            $dqoFinal1 = "< " . $limiteDqo->Limite;
            $limExceedDqo1 = 1;
            $limDqo = $limiteDqo->Limite;
        }

        //Cálculo DQO del segundo folio
        if(!is_null($solicitudParamDqo2)){            
            $preCalculo = $gastoLPS1 / ($gastoLPS1 + $gastoLPS2);
            $dqoFinal2 = round(($solicitudParamDqo2->Resultado * $preCalculo), 2);
        }

        //Verifica si el resultado es menor al límite de cuantificación del parámetro
        $limiteDqo2 = DB::table('parametros')->where('Id_parametro', 7)->first();

        if ($dqoFinal2 < $limiteDqo2->Limite) {
            $dqoFinal2 = "< " . $limiteDqo2->Limite;
            $limExceedDqo2 = 1;
            $limDqo = $limiteDqo2->Limite;
        }
        //*************************************** FIN DE CÁLCULO DQO *************************************************      
        
        //Recupera la base del folio 1
        $folio = explode("-", $solModel->Folio_servicio);
        $parte1 = strval($folio[0]);
        $parte2 = strval($folio[1]);

        $numOrden = Solicitud::where('Folio_servicio', $parte1 . "-" . $parte2)->first();

        //Recupera la base del folio 2
        //Recupera la base del folio
        $folio2 = explode("-", $solModel->Folio_servicio);
        $parte12 = strval($folio2[0]);
        $parte22 = strval($folio2[1]);

        $numOrden2 = Solicitud::where('Folio_servicio', $parte12 . "-" . $parte22)->first();           
        
        //Almacena el límite de grasas
        $lGras = $limGrasas->Limite;

        //Almacena el límite coliformes fecales
        $limiteCuantiColi = DB::table('parametros')->where('Id_parametro', 13)->first();
        $lColi = $limiteCuantiColi->Limite;

        //Almacena el límite DQO
        $limiteCuantiDqo = DB::table('parametros')->where('Id_parametro', 7)->first();
        $lDqo = $limiteCuantiDqo->Limite;
        
        //BODY;Por añadir validaciones, mismas que se irán implementando cuando haya una tabla en la BD para los informes
        $htmlInforme = view('exports.informes.sinComparacion.bodyInformeMensual',  compact('limitesC', 'limites2C', 'limiteMostrar', 'limiteMostrar2', 'sumaCaudalesFinal', 'resColi', 'sumaCaudalesFinal2', 'resColi2', 'dqoFinal1', 'dqoFinal2', 'modelLength', 'solModel', 'model', 'model2', 'limExceed1', 'limExceed2', 'limGras', 'limExceedColi1', 'limExceedColi2', 'limColi', 'limExceedDqo1', 'limExceedDqo2', 'limDqo', 'lGras', 'lColi', 'lDqo', 'limitesNorma', 'resultsParam', 'resultsParam2', 'sParam'));

        //HEADER-FOOTER******************************************************************************************************************
        $htmlHeader = view('exports.informes.sinComparacion.headerInformeMensual', compact('solModel', 'solModel2', 'direccion', 'cliente', 'puntoMuestreo', 'norma', 'modelProcesoAnalisis1', 'modelProcesoAnalisis2', 'gastoLPS1', 'gastoLPS2', 'numOrden', 'numOrden2'));
        $htmlFooter = view('exports.informes.sinComparacion.footerInformeMensual', compact('solModel', 'simbologiaParam'));

        $mpdf->setHeader("{PAGENO} / {nbpg} <br><br>" . $htmlHeader);
        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
        $mpdf->WriteHTML($htmlInforme);

        $mpdf->CSSselectMedia = 'mpdf';
        $mpdf->Output('Informe de Resultados Sin Comparacion.pdf', 'I');                  
        //echo $modelLength;
        
        //echo implode(" , ", $limitesNorma);
        //echo implode(" , ", $limiteMostrar2);
    }

    public function pdfComparacion2($idSol)
    {
        //Opciones del documento PDF
        $mpdf = new \Mpdf\Mpdf([
            'orientation' => 'L',
            'format' => 'letter',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 74,
            'margin_bottom' => 76,
            'defaultheaderfontstyle' => ['normal'],
            'defaultheaderline' => '0'
        ]);            
        
        // Hace los filtros para realizar la comparacion
        $solModel = DB::table('ViewSolicitud')->where('Id_solicitud', $idSol)->first();
        $solModel2 = DB::table('ViewSolicitud')->where('IdPunto', $solModel->IdPunto)->OrderBy('Id_solicitud', 'DESC')->get();

        //ViewCodigoParametro
        $cont = (sizeof($solModel2) - 1);
        
        $model = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Num_muestra', 1)->orderBy('Parametro', 'ASC')->get();
        $modelLength = $model->count();

        $sParam = array();

        foreach($model as $item){
            $paramModel = Parametro::where('Id_parametro', $item->Id_parametro)->first();
            $sP = SimbologiaParametros::where('Id_simbologia', $paramModel->Id_simbologia)->first();
            array_push($sParam, $sP->Simbologia);
        }

        //Recupera sin duplicados las simbologías de los parámetros
        $simbolParam = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->get();
        $simbolParam = $simbolParam->unique('Id_simbologia');
        $simbologiaParam = array();

        foreach($simbolParam as $item){
            array_push($simbologiaParam, $item->Id_simbologia);
        }

        $model2 = DB::table('ViewCodigoParametro')->where('Id_solicitud', $solModel2[0]->Id_solicitud)->where('Num_muestra', 1)->orderBy('Parametro', 'ASC')->get();

        //Formatea la fecha; Por adaptar para el informe sin comparacion
        $fechaAnalisis = DB::table('ViewLoteAnalisis')->where('Id_lote', 0)->first();
        if (!is_null($fechaAnalisis)) {
            $fechaConFormato = date("d/m/Y", strtotime($fechaAnalisis->Fecha));
        }        
        
        //Obtiene la norma------------------------
        $norma = Norma::where('Id_norma', $solModel->Id_norma)->first();
        
        //Obtiene la dirección del reporte---------------------
        $direccion = DireccionReporte::where('Id_direccion', $solModel->Id_direccion)->first();                
        
        //Recupera el nombre del cliente---------------
        $cliente = Clientes::where('Id_cliente', $solModel->Id_cliente)->first();

        //Recupera el punto de muestreo del informe-----------------
        $solicitudPunto = SolicitudPuntos::where('Id_solicitud', $solModel->Id_solicitud)->first();
        $puntoMuestreo = DB::table('puntos_muestreo')->where('Id_punto', $solicitudPunto->Id_punto)->first();

        //*************************************CALCULO DE CONCENTRACIÓN CUANTIFICADA DE GRASAS*************************************
        //Consulta si existe el parámetro de Grasas y Aceites en la solicitud
        $solicitudParametroGrasas = DB::table('ViewCodigoParametro')->where('Id_solicitud', $solModel->Id_solicitud)->where('Id_parametro', 14)->get();
        $solicitudParametroGrasasLength = $solicitudParametroGrasas->count();

        $solicitudParametroGrasas2 = DB::table('ViewCodigoParametro')->where('Id_solicitud', $solModel2[0]->Id_solicitud)->where('Id_parametro', 14)->get();
        $solicitudParametroGrasasLength2 = $solicitudParametroGrasas2->count();

        $sumaCaudales = 0;
        $sumaCaudales2 = 0;

        //Establece si debe mostrarse o no el promedio ponderado de los caudales
        $limExceed1  = 0;
        $limExceed2  = 0;

        //Límite de grasas
        $limGras = 0;
        $limGrasas = DB::table('parametros')->where('Id_parametro', 14)->first();

        //Cálculo grasas de la segunda solicitud
        $sumaCaudalesFinal = 0;

        //Cálculo grasas de la segunda solicitud
        $sumaCaudalesFinal2 = 0;                

        //Calcula Grasas para el primer folio
        if ($solicitudParametroGrasasLength > 0) { //Encontró grasas

            //Recupera los gastos (caudales) de la solicitud
            $gastosModel = GastoMuestra::where('Id_solicitud', $solModel->Id_solicitud)->get();
            $gastosModelLength = $gastosModel->count(); 
            
            if($gastosModelLength > 0){
                //Arreglo que almacena el resultado de cada caudal entre la sumatoria de los caudales
                $divCaudalSuma = array();

                //Arreglo que almacena los resultados de las multiplicaciones de divCaudalSuma por el resultado de cada muestra del parámetro
                $multResDivCaudal = array();

                //Paso 1: Sumatoria de los caudales
                foreach ($gastosModel as $item) {
                    if ($item->Promedio === null) {
                        $sumaCaudales += 0;
                    } else {
                        $sumaCaudales += $item->Promedio;
                    }
                }

                $sumaCaudales = round($sumaCaudales, 2);

                //Paso 2: División de cada caudal entre la sumatoria de los caudales
                foreach ($gastosModel as $item) {
                    if ($item->Promedio === null) {
                        $div = 0 / $sumaCaudales;

                        array_push(
                            $divCaudalSuma,
                            $div
                        );
                    } else {
                        $div = round(($item->Promedio / $sumaCaudales), 2);

                        array_push(
                            $divCaudalSuma,
                            $div
                        );
                    }
                }

                //Paso 3: Multiplicación de cada divCaudalSuma por el resultado del parametro                                    
                for ($i = 0; $i < $solicitudParametroGrasasLength; $i++) {
                    $mult = round(($divCaudalSuma[$i] * $solicitudParametroGrasas[$i]->Resultado), 2);

                    array_push(
                        $multResDivCaudal,
                        $mult
                    );
                }

                //Paso 4: Sumatoria de multResDivCaudal
                foreach ($multResDivCaudal as $item) {
                    $sumaCaudalesFinal += $item;
                }                

                $sumaCaudalesFinal = round($sumaCaudalesFinal, 2);
            }            
        }

        //Verifica si el resultado es menor al límite de cuantificación del parámetro
        $limiteGrasas = DB::table('parametros')->where('Id_parametro', 14)->first();

        if ($sumaCaudalesFinal < $limiteGrasas->Limite) {
            $sumaCaudalesFinal = "< " . $limiteGrasas->Limite;
            $limExceed1 = 1;
            $limGras = $limiteGrasas->Limite;
        }        

        //Calcula Grasas para el segundo folio        
        if ($solicitudParametroGrasasLength2 > 0) { //Encontró grasas

            //Recupera los gastos (caudales) de la solicitud
            $gastosModel = GastoMuestra::where('Id_solicitud', $solModel2[0]->Id_solicitud)->get();
            
            $gastosModelLength = $gastosModel->count(); 
            
            if($gastosModelLength > 0){ //Encontró grasas
                //Arreglo que almacena el resultado de cada caudal entre la sumatoria de los caudales
                $divCaudalSuma = array();

                //Arreglo que almacena los resultados de las multiplicaciones de divCaudalSuma por el resultado de cada muestra del parámetro
                $multResDivCaudal = array();

                //Paso 1: Sumatoria de los caudales
                foreach ($gastosModel as $item) {
                    if ($item->Promedio === null) {
                        $sumaCaudales2 += 0;
                    } else {
                        $sumaCaudales2 += $item->Promedio;
                    }
                }

                $sumaCaudales2 = round($sumaCaudales2, 2);

                //Paso 2: División de cada caudal entre la sumatoria de los caudales
                foreach ($gastosModel as $item) {
                    if ($item->Promedio === null) {
                        $div = 0 / $sumaCaudales2;

                        array_push(
                            $divCaudalSuma,
                            $div
                        );
                    } else {
                        $div = round(($item->Promedio / $sumaCaudales2), 2);

                        array_push(
                            $divCaudalSuma,
                            $div
                        );
                    }
                }

                //Paso 3: Multiplicación de cada divCaudalSuma por el resultado del parametro                                    
                for ($i = 0; $i < $solicitudParametroGrasasLength2; $i++) {
                    $mult = round(($divCaudalSuma[$i] * $solicitudParametroGrasas2[$i]->Resultado), 2);

                    array_push(
                        $multResDivCaudal,
                        $mult
                    );
                }

                //Paso 4: Sumatoria de multResDivCaudal
                foreach ($multResDivCaudal as $item) {
                    $sumaCaudalesFinal2 += $item;
                }   
                
                $sumaCaudalesFinal2 = round(($sumaCaudalesFinal2), 2);
            }                                  
        }

        //Verifica si el resultado es menor al límite de cuantificación del parámetro
        $limiteGrasas = DB::table('parametros')->where('Id_parametro', 14)->first();
                
        if ($sumaCaudalesFinal2 < $limiteGrasas->Limite) {
            $sumaCaudalesFinal2 = "< " . $limiteGrasas->Limite;
            $limExceed2 = 1;
            $limGras = $limiteGrasas->Limite;
        }        
        
        //**************************************FIN DE CALCULO DE CONCENTRACION CUANTIFICADA DE GRASAS ******************************

        //************************************** CALCULO DE COLIFORMES FECALES******************************************************
        //Consulta si existe el parámetro de Coliformes Fecales en la solicitud
        $solicitudParametroColiformesFe = DB::table('ViewCodigoParametro')->where('Id_solicitud', $solModel->Id_solicitud)->where('Id_parametro', 13)->get();
        $solicitudParametroColiformesFeLength = $solicitudParametroColiformesFe->count();

        $solicitudParametroColiformesFe2 = DB::table('ViewCodigoParametro')->where('Id_solicitud', $solModel2[0]->Id_solicitud)->where('Id_parametro', 13)->get();
        $solicitudParametroColiformesFeLength2 = $solicitudParametroColiformesFe2->count();

        //Establece si debe mostrarse o no el promedio ponderado de los coliformes
        $limExceedColi1  = 0;
        $limExceedColi2  = 0;
        $limColi = 0;

        //Calculo de coliformes de la primera solicitud
        $resColi = 0;

        //Calculo de coliformes de la segunda solicitud
        $resColi2 = 0;

        if ($solicitudParametroColiformesFeLength > 0) { //Encontró coliformes fecales para folio 1
            $productoColi = 1;

            //Paso 1: Multiplicación de todos los resultados de coliformes fecales
            foreach ($solicitudParametroColiformesFe as $item) {
                $productoColi *= $item->Resultado;
            }

            //Paso 2: Raíz a la N cantidad de resultados de coliformes
            $resColi = round(pow($productoColi, 1 / $solicitudParametroColiformesFeLength), 2);
        }        

        //Verifica si el resultado es menor al límite de cuantificación del parámetro
        $limiteColi = DB::table('parametros')->where('Id_parametro', 13)->first();

        if ($resColi < $limiteColi->Limite) {
            $resColi = "< " . $limiteColi->Limite;
            $limExceedColi1 = 1;
            $limColi = $limiteColi->Limite;
        }



        if ($solicitudParametroColiformesFeLength2 > 0) { //Encontró coliformes fecales para folio 2
            $productoColi = 1;

            //Paso 1: Multiplicación de todos los resultados de coliformes fecales
            foreach ($solicitudParametroColiformesFe2 as $item) {
                $productoColi *= $item->Resultado;
            }

            //Paso 2: Raíz a la N cantidad de resultados de coliformes
            $resColi2 = round(pow($productoColi, 1 / $solicitudParametroColiformesFeLength2), 2);
        }

        //Verifica si el resultado es menor al límite de cuantificación del parámetro
        $limiteColi2 = DB::table('parametros')->where('Id_parametro', 13)->first();

        if ($resColi2 < $limiteColi2->Limite) {
            $resColi2 = "< " . $limiteColi2->Limite;
            $limExceedColi2 = 1;
            $limColi = $limiteColi->Limite;
        }
        //************************************** FIN DE CALCULO DE COLIFORMES FECALES*********************************************** 

        //Recupera el gasto promedio para la solicitud dada
        $gModel = GastoMuestra::where('Id_solicitud', $idSol)->get();
        $gModelLength = $gastosModel->count();
        $gastosProm = 0;

        if(!is_null($gModel)){
            foreach($gModel as $item){
                if($item->Promedio == null){
                    $gastosProm += 0;
                }else{
                    $gastosProm += $item->Promedio;
                }                
            }

            $gastosProm /= $gModelLength;
        }

        //Recupera la temperatura promedio para la solicitud dada
        $tModel = TemperaturaMuestra::where('Id_solicitud', $idSol)->get();
        $tModelLength = $tModel->count();
        $tProm = 0;

        if(!is_null($tModel)){
            foreach($tModel as $item){
                if($item->Promedio == null){
                    $tProm += 0;
                }else{
                    $tProm += $item->Promedio;
                }                
            }

            $tProm /= $tModelLength;
        }

        //Recupera el pH promedio para la solicitud dada
        $phModel = PhMuestra::where('Id_solicitud', $idSol)->get();
        $phModelLength = $phModel->count();
        $phProm = 0;

        if(!is_null($phModel)){
            foreach($phModel as $item){
                if($item->Promedio == null){
                    $phProm += 0;
                }else{
                    $phProm += $item->Promedio;
                }                
            }

            $phProm /= $phModelLength;
        }

        $limiteMostrar = array();
        $limitesC = array();  

        //Almacena los límites de la Norma
        $limitesNorma = array();
        
        //Almacena los resultados de los parametros
        $resultsParam = array();

        //Recupera los límites de cuantificación de los parámetros del primer folio
        foreach ($model as $item) {
            $limiteC = DB::table('parametros')->where('Id_parametro', $item->Id_parametro)->first();
            $paramLim = Limite001::where('Id_parametro', $item->Id_parametro)->first();

            //
            /* switch($item->Id_parametro){
                case 14: 
            } */

            if($item->Id_parametro == 27){ //Gasto
                array_push($limitesC, number_format($gastosProm, 2, ".", ","));
            }else if($item->Id_parametro == 98){ //Temperatura
                array_push($limitesC, number_format($tProm, 2, ".", ","));
            }else if($item->Id_parametro == 15){ //pH
                array_push($limitesC, number_format($phProm, 2, ".", ","));
            }else if ($item->Resultado < $limiteC->Limite) {
                $limC = "< " . round($limiteC->Limite, 3);

                array_push($limiteMostrar, 1);
                array_push($limitesC, $limC);
            } else {  //Si es mayor el resultado que el límite de cuantificación
                if($item->Id_parametro == 20){   //Cianuros
                    $limC = number_format($item->Resultado, 3, ".", ",");
                }else if($item->Id_parametro == 13){   //Coliformes fecales
                    $limC = number_format($item->Resultado, 2, ".", ",");
                }else if($item->Id_parametro == 22){   //Cromo total
                    $limC = number_format($item->Resultado, 3, ".", ",");
                }else if($item->Id_parametro == 72){   //DBO
                    $limC = number_format($item->Resultado, 2, ".", ",");
                }else if($item->Id_parametro == 7){   //DQO
                    $limC = number_format($item->Resultado, 2, ".", ",");
                }else if($item->Id_parametro == 16){   //Fosforo
                    $limC = number_format($item->Resultado, 3, ".", ",");
                }else if($item->Id_parametro == 14){   //Grasas y Aceites
                    $limC = number_format($item->Resultado, 2, ".", ",");
                }else if($item->Id_parametro == 17){   //Huevos de helminto
                    $limC = number_format($item->Resultado, 0, ".", ",");
                }else if($item->Id_parametro == 23){   //Mercurio
                    $limC = number_format($item->Resultado, 4, ".", ",");
                }else if($item->Id_parametro == 8){   //Nitratos
                    $limC = number_format($item->Resultado, 3, ".", ",");
                }else if($item->Id_parametro == 9){   //Nitritos
                    $limC = number_format($item->Resultado, 3, ".", ",");
                }else if($item->Id_parametro == 10 || $item->Id_parametro == 11){   //Nitrogeno Amoniacal y Nitrogeno Organico
                    $limC = number_format($item->Resultado, 2, ".", ",");
                }else{
                    $limC = number_format($item->Resultado, 3, ".", ",");
                }                
                            
                array_push($limiteMostrar, 0);
                array_push($limitesC, $limC);
            }

            array_push($limitesNorma, @$paramLim->Prom_Mmax);
            array_push($resultsParam, $item->Resultado);
        }

        //Recupera la fecha de recepción del primer y segundo folio
        $modelProcesoAnalisis1 = ProcesoAnalisis::where('Id_solicitud', $solModel->Id_solicitud)->first();
        $modelProcesoAnalisis2 = ProcesoAnalisis::where('Id_solicitud', $solModel2[0]->Id_solicitud)->first();

        //Calcula Gasto LPS1********************************************************************
        $gastosModel = GastoMuestra::where('Id_solicitud', $solModel->Id_solicitud)->get();
        $gastosModelLength = $gastosModel->count();
        $gastoSum1 = 0;
        $gastoLPS1 = 0;
        
        if($gastosModelLength > 0){
            //Paso 1: Sumatoria de los caudales
            foreach ($gastosModel as $item) {
                if ($item->Promedio === null) {
                    $gastoSum1 += 0;
                } else {
                    $gastoSum1 += $item->Promedio;
                }
            }

            //Paso 2: División entre el total de gastos
            $gastoLPS1 = $gastoSum1 / $gastosModelLength;
        } 

        //Calcula Gasto LPS2*********************************************************************     
                
        $gastosModel2 = GastoMuestra::where('Id_solicitud', $solModel2[0]->Id_solicitud)->get();
        $gastosModelLength2 = $gastosModel2->count();
        $gastoSum2 = 0;
        $gastoLPS2 = 0;

        if($gastosModelLength2 > 0){
            //Paso 1: Sumatoria de los caudales
            foreach ($gastosModel2 as $item) {
                if ($item->Promedio === null) {
                    $gastoSum2 += 0;
                } else {
                    $gastoSum2 += $item->Promedio;
                }
            }

            //Paso 2: División entre el total de gastos            
            $gastoLPS2 = $gastoSum2 / $gastosModelLength2;
        }      
        //*******************************************************************************************  

        //Recupera el gasto promedio para la solicitud dada
                
        $gModel2 = GastoMuestra::where('Id_solicitud', $solModel2[0]->Id_solicitud)->get();        
        $gModelLength2 = $gastosModel->count();
        $gastosProm2 = 0;

        if($gModelLength2 > 0){
            foreach($gModel2 as $item){
                if($item->Promedio == null){
                    $gastosProm2 += 0;
                }else{
                    $gastosProm2 += $item->Promedio;
                }                
            }

            $gastosProm2 /= $gModelLength2;
        }        

        //Recupera la temperatura promedio para la solicitud dada
        $tModel2 = TemperaturaMuestra::where('Id_solicitud', $solModel2[0]->Id_solicitud)->get();
        $tModelLength2 = $tModel2->count();
        $tProm2 = 0;

        if($tModelLength2 > 0){
            foreach($tModel2 as $item){
                if($item->Promedio == null){
                    $tProm2 += 0;
                }else{
                    $tProm2 += $item->Promedio;
                }                
            }

            $tProm2 /= $tModelLength2;
        }

        //Recupera el pH promedio para la solicitud dada
        $phModel2 = PhMuestra::where('Id_solicitud', $solModel2[0]->Id_solicitud)->get();
        $phModelLength2 = $phModel2->count();
        $phProm2 = 0;

        if($phModelLength2 > 0){
            foreach($phModel2 as $item){
                if($item->Promedio == null){
                    $phProm2 += 0;
                }else{
                    $phProm2 += $item->Promedio;
                }                
            }

            $phProm2 /= $phModelLength2;
        }

        $limiteMostrar2 = array();
        $limites2C = array();  
        $resultsParam2 = array();              

        /* $solicitudParametros2 = DB::table('ViewCodigoParametro')->where('Id_solicitud', $solModel2[0]->Id_solicitud)->where('Num_muestra', 1)->get();
        $solicitudParametros2Length = $solicitudParametros2->count(); */

        //Recupera los límites de cuantificación de los parámetros
        foreach ($model2 as $item) {
            $limite2C = DB::table('parametros')->where('Id_parametro', $item->Id_parametro)->first();

            if($item->Id_parametro == 27){ //Gasto
                array_push($limites2C, number_format($gastosProm2, 2, ".", ","));
            }else if($item->Id_parametro == 98){ //Temperatura
                array_push($limites2C, number_format($tProm2, 2, ".", ","));
            }else if($item->Id_parametro == 15){ //pH
                array_push($limites2C, number_format($phProm2, 2, ".", ","));
            }else if ($item->Resultado < $limite2C->Limite) {
                $lim2C = "< " . round($limite2C->Limite, 3);

                array_push($limiteMostrar2, 1);
                array_push($limites2C, $lim2C);
            } else {  //Si es mayor el resultado que el límite de cuantificación
                if($item->Id_parametro == 20){   //Cianuros
                    $lim2C = number_format($item->Resultado, 3, ".", ",");
                }else if($item->Id_parametro == 13){   //Coliformes fecales
                    $lim2C = number_format($item->Resultado, 2, ".", ",");
                }else if($item->Id_parametro == 22){   //Cromo total
                    $lim2C = number_format($item->Resultado, 3, ".", ",");
                }else if($item->Id_parametro == 72){   //DBO
                    $lim2C = number_format($item->Resultado, 2, ".", ",");
                }else if($item->Id_parametro == 7){   //DQO
                    $lim2C = number_format($item->Resultado, 2, ".", ",");
                }else if($item->Id_parametro == 16){   //Fosforo
                    $lim2C = number_format($item->Resultado, 3, ".", ",");
                }else if($item->Id_parametro == 14){   //Grasas y Aceites
                    $lim2C = number_format($item->Resultado, 2, ".", ",");
                }else if($item->Id_parametro == 17){   //Huevos de helminto
                    $lim2C = number_format($item->Resultado, 0, ".", ",");
                }else if($item->Id_parametro == 23){   //Mercurio
                    $lim2C = number_format($item->Resultado, 4, ".", ",");
                }else if($item->Id_parametro == 8){   //Nitratos
                    $lim2C = number_format($item->Resultado, 3, ".", ",");
                }else if($item->Id_parametro == 9){   //Nitritos
                    $lim2C = number_format($item->Resultado, 3, ".", ",");
                }else if($item->Id_parametro == 10 || $item->Id_parametro == 11){   //Nitrogeno Amoniacal y Nitrogeno Organico
                    $limC2 = number_format($item->Resultado, 2, ".", ",");
                }else{
                    $lim2C = number_format($item->Resultado, 3, ".", ",");
                }
                
                array_push($limiteMostrar2, 0);
                array_push($limites2C, $lim2C);
            }
            array_push($resultsParam2, $item->Resultado);
        }  

        //*************************************** CÁLCULO DQO ********************************************************
        $solicitudParamDqo = DB::table('ViewCodigoParametro')->where('Id_solicitud', $solModel->Id_solicitud)->where('Id_parametro', 7)->first();        

        $solicitudParamDqo2 = DB::table('ViewCodigoParametro')->where('Id_solicitud', $solModel2[0]->Id_solicitud)->where('Id_parametro', 7)->first();        

        //Establece si debe mostrarse o no el promedio ponderado de los DQO
        $limExceedDqo1  = 0;
        $limExceedDqo2  = 0;

        //Límite de DQO
        $limDqo = 0;

        //Cálculo DQO de la segunda solicitud
        $dqoFinal1 = 0;
        
        //Cálculo DQO de la segunda solicitud
        $dqoFinal2 = 0;

        //Cálculo DQO del primer folio
        if(!is_null($solicitudParamDqo)){            
            $preCalculo = $gastoLPS1 / ($gastoLPS1 + $gastoLPS2);
            $dqoFinal1 = round(($solicitudParamDqo->Resultado * $preCalculo), 2);
        }

        //Verifica si el resultado es menor al límite de cuantificación del parámetro
        $limiteDqo = DB::table('parametros')->where('Id_parametro', 7)->first();

        if ($dqoFinal1 < $limiteDqo->Limite) {
            $dqoFinal1 = "< " . $limiteDqo->Limite;
            $limExceedDqo1 = 1;
            $limDqo = $limiteDqo->Limite;
        }

        //Cálculo DQO del segundo folio
        if(!is_null($solicitudParamDqo2)){            
            $preCalculo = $gastoLPS1 / ($gastoLPS1 + $gastoLPS2);
            $dqoFinal2 = round(($solicitudParamDqo2->Resultado * $preCalculo), 2);
        }

        //Verifica si el resultado es menor al límite de cuantificación del parámetro
        $limiteDqo2 = DB::table('parametros')->where('Id_parametro', 7)->first();

        if ($dqoFinal2 < $limiteDqo2->Limite) {
            $dqoFinal2 = "< " . $limiteDqo2->Limite;
            $limExceedDqo2 = 1;
            $limDqo = $limiteDqo2->Limite;
        }
        //*************************************** FIN DE CÁLCULO DQO *************************************************      
        
        //Recupera la base del folio 1
        $folio = explode("-", $solModel->Folio_servicio);
        $parte1 = strval($folio[0]);
        $parte2 = strval($folio[1]);

        $numOrden = Solicitud::where('Folio_servicio', $parte1 . "-" . $parte2)->first();

        //Recupera la base del folio 2
        //Recupera la base del folio
        $folio2 = explode("-", $solModel->Folio_servicio);
        $parte12 = strval($folio2[0]);
        $parte22 = strval($folio2[1]);

        $numOrden2 = Solicitud::where('Folio_servicio', $parte12 . "-" . $parte22)->first();           
        
        //Almacena el límite de grasas
        $lGras = $limGrasas->Limite;

        //Almacena el límite coliformes fecales
        $limiteCuantiColi = DB::table('parametros')->where('Id_parametro', 13)->first();
        $lColi = $limiteCuantiColi->Limite;

        //Almacena el límite DQO
        $limiteCuantiDqo = DB::table('parametros')->where('Id_parametro', 7)->first();
        $lDqo = $limiteCuantiDqo->Limite;                    
        
        //BODY;Por añadir validaciones, mismas que se irán implementando cuando haya una tabla en la BD para los informes
        $htmlInforme = view('exports.informes.conComparacion.bodyInformeMensual',  compact('limitesC', 'limites2C', 'limiteMostrar', 'limiteMostrar2', 'sumaCaudalesFinal', 'resColi', 'sumaCaudalesFinal2', 'resColi2', 'dqoFinal1', 'dqoFinal2', 'modelLength', 'solModel', 'model', 'model2', 'limExceed1', 'limExceed2', 'limGras', 'limExceedColi1', 'limExceedColi2', 'limColi', 'limExceedDqo1', 'limExceedDqo2', 'limDqo', 'limitesNorma', 'lGras', 'lColi', 'lDqo', 'resultsParam', 'resultsParam2', 'sParam'));

        //HEADER-FOOTER******************************************************************************************************************
        $htmlHeader = view('exports.informes.conComparacion.headerInformeMensual', compact('solModel', 'solModel2', 'direccion', 'cliente', 'puntoMuestreo', 'norma', 'modelProcesoAnalisis1', 'modelProcesoAnalisis2', 'gastoLPS1', 'gastoLPS2', 'numOrden', 'numOrden2'));
        $htmlFooter = view('exports.informes.conComparacion.footerInformeMensual', compact('solModel', 'simbologiaParam'));

        $mpdf->setHeader("{PAGENO} / {nbpg} <br><br>" . $htmlHeader);
        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
        $mpdf->WriteHTML($htmlInforme);

        $mpdf->CSSselectMedia = 'mpdf';
        $mpdf->Output('Informe de Resultados Con Comparacion.pdf', 'I');                  
        //echo $modelDiag;
        //echo implode(" , ", $limiteMostrar);
        //echo implode(" , ", $limiteMostrar2);
    }


    //SE ACCEDE A TRAVÉS DEL PREFIJO CLIENTES DE LA RUTA
    public function pdfSinComparacionCliente($idSol)
    {
        //Opciones del documento PDF
        $mpdf = new \Mpdf\Mpdf([
            'orientation' => 'L',
            'format' => 'letter',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 74,
            'margin_bottom' => 76,
            'defaultheaderfontstyle' => ['normal'],
            'defaultheaderline' => '0'
        ]);            
        
        // Hace los filtros para realizar la comparacion
        $solModel = DB::table('ViewSolicitud')->where('Id_solicitud', $idSol)->first();
        $solModel2 = DB::table('ViewSolicitud')->where('IdPunto', $solModel->IdPunto)->OrderBy('Id_solicitud', 'DESC')->get();

        //ViewCodigoParametro
        $cont = (sizeof($solModel2) - 1);
        
        $model = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Num_muestra', 1)->orderBy('Parametro', 'ASC')->get();
        $modelLength = $model->count();

        $sParam = array();

        foreach($model as $item){
            $paramModel = Parametro::where('Id_parametro', $item->Id_parametro)->first();
            $sP = SimbologiaParametros::where('Id_simbologia', $paramModel->Id_simbologia)->first();
            array_push($sParam, $sP->Simbologia);
        }

        //Recupera sin duplicados las simbologías de los parámetros
        $simbolParam = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->get();
        $simbolParam = $simbolParam->unique('Id_simbologia');
        $simbologiaParam = array();

        foreach($simbolParam as $item){
            array_push($simbologiaParam, $item->Id_simbologia);
        }

        $model2 = DB::table('ViewCodigoParametro')->where('Id_solicitud', $solModel2[0]->Id_solicitud)->where('Num_muestra', 1)->orderBy('Parametro', 'ASC')->get();

        //Formatea la fecha; Por adaptar para el informe sin comparacion
        $fechaAnalisis = DB::table('ViewLoteAnalisis')->where('Id_lote', 0)->first();
        if (!is_null($fechaAnalisis)) {
            $fechaConFormato = date("d/m/Y", strtotime($fechaAnalisis->Fecha));
        }        
        
        //Obtiene la norma------------------------
        $norma = Norma::where('Id_norma', $solModel->Id_norma)->first();
        
        //Obtiene la dirección del reporte---------------------
        $direccion = DireccionReporte::where('Id_direccion', $solModel->Id_direccion)->first();                
        
        //Recupera el nombre del cliente---------------
        $cliente = Clientes::where('Id_cliente', $solModel->Id_cliente)->first();

        //Recupera el punto de muestreo del informe-----------------
        $solicitudPunto = SolicitudPuntos::where('Id_solicitud', $solModel->Id_solicitud)->first();
        $puntoMuestreo = DB::table('puntos_muestreo')->where('Id_punto', $solicitudPunto->Id_punto)->first();

        //*************************************CALCULO DE CONCENTRACIÓN CUANTIFICADA DE GRASAS*************************************
        //Consulta si existe el parámetro de Grasas y Aceites en la solicitud
        $solicitudParametroGrasas = DB::table('ViewCodigoParametro')->where('Id_solicitud', $solModel->Id_solicitud)->where('Id_parametro', 14)->get();
        $solicitudParametroGrasasLength = $solicitudParametroGrasas->count();

        $solicitudParametroGrasas2 = DB::table('ViewCodigoParametro')->where('Id_solicitud', $solModel2[0]->Id_solicitud)->where('Id_parametro', 14)->get();
        $solicitudParametroGrasasLength2 = $solicitudParametroGrasas2->count();

        $sumaCaudales = 0;
        $sumaCaudales2 = 0;

        //Establece si debe mostrarse o no el promedio ponderado de los caudales
        $limExceed1  = 0;
        $limExceed2  = 0;

        //Límite de grasas
        $limGras = 0;
        $limGrasas = DB::table('parametros')->where('Id_parametro', 14)->first();

        //Cálculo grasas de la segunda solicitud
        $sumaCaudalesFinal = 0;

        //Cálculo grasas de la segunda solicitud
        $sumaCaudalesFinal2 = 0;                

        //Calcula Grasas para el primer folio
        if ($solicitudParametroGrasasLength > 0) { //Encontró grasas

            //Recupera los gastos (caudales) de la solicitud
            $gastosModel = GastoMuestra::where('Id_solicitud', $solModel->Id_solicitud)->get();
            $gastosModelLength = $gastosModel->count(); 
            
            if($gastosModelLength > 0){
                //Arreglo que almacena el resultado de cada caudal entre la sumatoria de los caudales
                $divCaudalSuma = array();

                //Arreglo que almacena los resultados de las multiplicaciones de divCaudalSuma por el resultado de cada muestra del parámetro
                $multResDivCaudal = array();

                //Paso 1: Sumatoria de los caudales
                foreach ($gastosModel as $item) {
                    if ($item->Promedio === null) {
                        $sumaCaudales += 0;
                    } else {
                        $sumaCaudales += $item->Promedio;
                    }
                }

                $sumaCaudales = round($sumaCaudales, 2);

                //Paso 2: División de cada caudal entre la sumatoria de los caudales
                foreach ($gastosModel as $item) {
                    if ($item->Promedio === null) {
                        $div = 0 / $sumaCaudales;

                        array_push(
                            $divCaudalSuma,
                            $div
                        );
                    } else {
                        $div = round(($item->Promedio / $sumaCaudales), 2);

                        array_push(
                            $divCaudalSuma,
                            $div
                        );
                    }
                }

                //Paso 3: Multiplicación de cada divCaudalSuma por el resultado del parametro                                    
                for ($i = 0; $i < $solicitudParametroGrasasLength; $i++) {
                    $mult = round(($divCaudalSuma[$i] * $solicitudParametroGrasas[$i]->Resultado), 2);

                    array_push(
                        $multResDivCaudal,
                        $mult
                    );
                }

                //Paso 4: Sumatoria de multResDivCaudal
                foreach ($multResDivCaudal as $item) {
                    $sumaCaudalesFinal += $item;
                }                

                $sumaCaudalesFinal = round($sumaCaudalesFinal, 2);
            }            
        }

        //Verifica si el resultado es menor al límite de cuantificación del parámetro
        $limiteGrasas = DB::table('parametros')->where('Id_parametro', 14)->first();

        if ($sumaCaudalesFinal < $limiteGrasas->Limite) {
            $sumaCaudalesFinal = "< " . $limiteGrasas->Limite;
            $limExceed1 = 1;
            $limGras = $limiteGrasas->Limite;
        }        

        //Calcula Grasas para el segundo folio        
        if ($solicitudParametroGrasasLength2 > 0) { //Encontró grasas

            //Recupera los gastos (caudales) de la solicitud
            $gastosModel = GastoMuestra::where('Id_solicitud', $solModel2[0]->Id_solicitud)->get();
            
            $gastosModelLength = $gastosModel->count(); 
            
            if($gastosModelLength > 0){ //Encontró grasas
                //Arreglo que almacena el resultado de cada caudal entre la sumatoria de los caudales
                $divCaudalSuma = array();

                //Arreglo que almacena los resultados de las multiplicaciones de divCaudalSuma por el resultado de cada muestra del parámetro
                $multResDivCaudal = array();

                //Paso 1: Sumatoria de los caudales
                foreach ($gastosModel as $item) {
                    if ($item->Promedio === null) {
                        $sumaCaudales2 += 0;
                    } else {
                        $sumaCaudales2 += $item->Promedio;
                    }
                }

                $sumaCaudales2 = round($sumaCaudales2, 2);

                //Paso 2: División de cada caudal entre la sumatoria de los caudales
                foreach ($gastosModel as $item) {
                    if ($item->Promedio === null) {
                        $div = 0 / $sumaCaudales2;

                        array_push(
                            $divCaudalSuma,
                            $div
                        );
                    } else {
                        $div = round(($item->Promedio / $sumaCaudales2), 2);

                        array_push(
                            $divCaudalSuma,
                            $div
                        );
                    }
                }

                //Paso 3: Multiplicación de cada divCaudalSuma por el resultado del parametro                                    
                for ($i = 0; $i < $solicitudParametroGrasasLength2; $i++) {
                    $mult = round(($divCaudalSuma[$i] * $solicitudParametroGrasas2[$i]->Resultado), 2);

                    array_push(
                        $multResDivCaudal,
                        $mult
                    );
                }

                //Paso 4: Sumatoria de multResDivCaudal
                foreach ($multResDivCaudal as $item) {
                    $sumaCaudalesFinal2 += $item;
                }   
                
                $sumaCaudalesFinal2 = round(($sumaCaudalesFinal2), 2);
            }                                  
        }

        //Verifica si el resultado es menor al límite de cuantificación del parámetro
        $limiteGrasas = DB::table('parametros')->where('Id_parametro', 14)->first();
                
        if ($sumaCaudalesFinal2 < $limiteGrasas->Limite) {
            $sumaCaudalesFinal2 = "< " . $limiteGrasas->Limite;
            $limExceed2 = 1;
            $limGras = $limiteGrasas->Limite;
        }        
        
        //**************************************FIN DE CALCULO DE CONCENTRACION CUANTIFICADA DE GRASAS ******************************

        //************************************** CALCULO DE COLIFORMES FECALES******************************************************
        //Consulta si existe el parámetro de Coliformes Fecales en la solicitud
        $solicitudParametroColiformesFe = DB::table('ViewCodigoParametro')->where('Id_solicitud', $solModel->Id_solicitud)->where('Id_parametro', 13)->get();
        $solicitudParametroColiformesFeLength = $solicitudParametroColiformesFe->count();

        $solicitudParametroColiformesFe2 = DB::table('ViewCodigoParametro')->where('Id_solicitud', $solModel2[0]->Id_solicitud)->where('Id_parametro', 13)->get();
        $solicitudParametroColiformesFeLength2 = $solicitudParametroColiformesFe2->count();

        //Establece si debe mostrarse o no el promedio ponderado de los coliformes
        $limExceedColi1  = 0;
        $limExceedColi2  = 0;
        $limColi = 0;

        //Calculo de coliformes de la primera solicitud
        $resColi = 0;

        //Calculo de coliformes de la segunda solicitud
        $resColi2 = 0;

        if ($solicitudParametroColiformesFeLength > 0) { //Encontró coliformes fecales para folio 1
            $productoColi = 1;

            //Paso 1: Multiplicación de todos los resultados de coliformes fecales
            foreach ($solicitudParametroColiformesFe as $item) {
                $productoColi *= $item->Resultado;
            }

            //Paso 2: Raíz a la N cantidad de resultados de coliformes
            $resColi = round(pow($productoColi, 1 / $solicitudParametroColiformesFeLength), 2);
        }        

        //Verifica si el resultado es menor al límite de cuantificación del parámetro
        $limiteColi = DB::table('parametros')->where('Id_parametro', 13)->first();

        if ($resColi < $limiteColi->Limite) {
            $resColi = "< " . $limiteColi->Limite;
            $limExceedColi1 = 1;
            $limColi = $limiteColi->Limite;
        }



        if ($solicitudParametroColiformesFeLength2 > 0) { //Encontró coliformes fecales para folio 2
            $productoColi = 1;

            //Paso 1: Multiplicación de todos los resultados de coliformes fecales
            foreach ($solicitudParametroColiformesFe2 as $item) {
                $productoColi *= $item->Resultado;
            }

            //Paso 2: Raíz a la N cantidad de resultados de coliformes
            $resColi2 = round(pow($productoColi, 1 / $solicitudParametroColiformesFeLength2), 2);
        }

        //Verifica si el resultado es menor al límite de cuantificación del parámetro
        $limiteColi2 = DB::table('parametros')->where('Id_parametro', 13)->first();

        if ($resColi2 < $limiteColi2->Limite) {
            $resColi2 = "< " . $limiteColi2->Limite;
            $limExceedColi2 = 1;
            $limColi = $limiteColi->Limite;
        }
        //************************************** FIN DE CALCULO DE COLIFORMES FECALES*********************************************** 

        //Recupera el gasto promedio para la solicitud dada
        $gModel = GastoMuestra::where('Id_solicitud', $idSol)->get();
        $gModelLength = $gastosModel->count();
        $gastosProm = 0;

        if(!is_null($gModel)){
            foreach($gModel as $item){
                if($item->Promedio == null){
                    $gastosProm += 0;
                }else{
                    $gastosProm += $item->Promedio;
                }                
            }

            $gastosProm /= $gModelLength;
        }

        //Recupera la temperatura promedio para la solicitud dada
        $tModel = TemperaturaMuestra::where('Id_solicitud', $idSol)->get();
        $tModelLength = $tModel->count();
        $tProm = 0;

        if(!is_null($tModel)){
            foreach($tModel as $item){
                if($item->Promedio == null){
                    $tProm += 0;
                }else{
                    $tProm += $item->Promedio;
                }                
            }

            $tProm /= $tModelLength;
        }

        //Recupera el pH promedio para la solicitud dada
        $phModel = PhMuestra::where('Id_solicitud', $idSol)->get();
        $phModelLength = $phModel->count();
        $phProm = 0;

        if(!is_null($phModel)){
            foreach($phModel as $item){
                if($item->Promedio == null){
                    $phProm += 0;
                }else{
                    $phProm += $item->Promedio;
                }                
            }

            $phProm /= $phModelLength;
        }

        $limiteMostrar = array();

        //Almacena ya sea el resultado del parametro o el limite de cuantificacion
        $limitesC = array();          
        
        //Almacena los resultados de los parametros
        $resultsParam = array();

        //Almacena únicamente los límites de cuantificación
        $limitesNorma = array();

        //Recupera los límites de cuantificación de los parámetros del primer folio
        foreach ($model as $item) {
            $limiteC = DB::table('parametros')->where('Id_parametro', $item->Id_parametro)->first();            
            
            echo $item->Parametro."<br>";
            echo $limiteC->Limite."<br>";

            //
            /* switch($item->Id_parametro){
                case 14: 
            } */

            if($item->Id_parametro == 27){ //Gasto
                array_push($limitesC, number_format($gastosProm, 2, ".", ","));
            }else if($item->Id_parametro == 98){ //Temperatura
                array_push($limitesC, number_format($tProm, 2, ".", ","));
            }else if($item->Id_parametro == 15){ //pH
                array_push($limitesC, number_format($phProm, 2, ".", ","));
            }else if ($item->Resultado < $limiteC->Limite) {
                $limC = "< " .$limiteC->Limite;

                array_push($limiteMostrar, 1);
                array_push($limitesC, $limC);
            } else {  //Si es mayor el resultado que el límite de cuantificación
                if($item->Id_parametro == 20){   //Cianuros
                    $limC = number_format($item->Resultado, 3, ".", ",");
                }else if($item->Id_parametro == 13){   //Coliformes fecales
                    $limC = number_format($item->Resultado, 2, ".", ",");
                }else if($item->Id_parametro == 22){   //Cromo total
                    $limC = number_format($item->Resultado, 3, ".", ",");
                }else if($item->Id_parametro == 72){   //DBO
                    $limC = number_format($item->Resultado, 2, ".", ",");
                }else if($item->Id_parametro == 7){   //DQO
                    $limC = number_format($item->Resultado, 2, ".", ",");
                }else if($item->Id_parametro == 16){   //Fosforo
                    $limC = number_format($item->Resultado, 3, ".", ",");
                }else if($item->Id_parametro == 14){   //Grasas y Aceites
                    $limC = number_format($item->Resultado, 2, ".", ",");
                }else if($item->Id_parametro == 17){   //Huevos de helminto
                    $limC = number_format($item->Resultado, 0, ".", ",");
                }else if($item->Id_parametro == 23){   //Mercurio
                    $limC = number_format($item->Resultado, 4, ".", ",");
                }else if($item->Id_parametro == 8){   //Nitratos
                    $limC = number_format($item->Resultado, 3, ".", ",");
                }else if($item->Id_parametro == 9){   //Nitritos
                    $limC = number_format($item->Resultado, 3, ".", ",");
                }else if($item->Id_parametro == 10 || $item->Id_parametro == 11){   //Nitrogeno Amoniacal y Nitrogeno Organico
                    $limC = number_format($item->Resultado, 2, ".", ",");
                }else{
                    $limC = number_format($item->Resultado, 3, ".", ",");
                }       
                
                echo $item->Parametro."<br>";
                echo $limiteC->Limite."<br>";
                                            
                array_push($limiteMostrar, 0);
                array_push($limitesC, $limC);
            }

            array_push($limitesNorma, $limiteC->Limite);
            array_push($resultsParam, $item->Resultado);
        }        

        //Recupera la fecha de recepción del primer y segundo folio
        $modelProcesoAnalisis1 = ProcesoAnalisis::where('Id_solicitud', $solModel->Id_solicitud)->first();
        $modelProcesoAnalisis2 = ProcesoAnalisis::where('Id_solicitud', $solModel2[0]->Id_solicitud)->first();

        //Calcula Gasto LPS1********************************************************************
        $gastosModel = GastoMuestra::where('Id_solicitud', $solModel->Id_solicitud)->get();
        $gastosModelLength = $gastosModel->count();
        $gastoSum1 = 0;
        $gastoLPS1 = 0;
        
        if($gastosModelLength > 0){
            //Paso 1: Sumatoria de los caudales
            foreach ($gastosModel as $item) {
                if ($item->Promedio === null) {
                    $gastoSum1 += 0;
                } else {
                    $gastoSum1 += $item->Promedio;
                }
            }

            //Paso 2: División entre el total de gastos
            $gastoLPS1 = $gastoSum1 / $gastosModelLength;
        } 

        //Calcula Gasto LPS2*********************************************************************     
                
        $gastosModel2 = GastoMuestra::where('Id_solicitud', $solModel2[0]->Id_solicitud)->get();
        $gastosModelLength2 = $gastosModel2->count();
        $gastoSum2 = 0;
        $gastoLPS2 = 0;

        if($gastosModelLength2 > 0){
            //Paso 1: Sumatoria de los caudales
            foreach ($gastosModel2 as $item) {
                if ($item->Promedio === null) {
                    $gastoSum2 += 0;
                } else {
                    $gastoSum2 += $item->Promedio;
                }
            }

            //Paso 2: División entre el total de gastos            
            $gastoLPS2 = $gastoSum2 / $gastosModelLength2;
        }      
        //*******************************************************************************************  

        //Recupera el gasto promedio para la solicitud dada
                
        $gModel2 = GastoMuestra::where('Id_solicitud', $solModel2[0]->Id_solicitud)->get();        
        $gModelLength2 = $gastosModel->count();
        $gastosProm2 = 0;

        if($gModelLength2 > 0){
            foreach($gModel2 as $item){
                if($item->Promedio == null){
                    $gastosProm2 += 0;
                }else{
                    $gastosProm2 += $item->Promedio;
                }                
            }

            $gastosProm2 /= $gModelLength2;
        }        

        //Recupera la temperatura promedio para la solicitud dada
        $tModel2 = TemperaturaMuestra::where('Id_solicitud', $solModel2[0]->Id_solicitud)->get();
        $tModelLength2 = $tModel2->count();
        $tProm2 = 0;

        if($tModelLength2 > 0){
            foreach($tModel2 as $item){
                if($item->Promedio == null){
                    $tProm2 += 0;
                }else{
                    $tProm2 += $item->Promedio;
                }                
            }

            $tProm2 /= $tModelLength2;
        }

        //Recupera el pH promedio para la solicitud dada
        $phModel2 = PhMuestra::where('Id_solicitud', $solModel2[0]->Id_solicitud)->get();
        $phModelLength2 = $phModel2->count();
        $phProm2 = 0;

        if($phModelLength2 > 0){
            foreach($phModel2 as $item){
                if($item->Promedio == null){
                    $phProm2 += 0;
                }else{
                    $phProm2 += $item->Promedio;
                }                
            }

            $phProm2 /= $phModelLength2;
        }

        $limiteMostrar2 = array();
        $limites2C = array();  
        $resultsParam2 = array();              

        /* $solicitudParametros2 = DB::table('ViewCodigoParametro')->where('Id_solicitud', $solModel2[0]->Id_solicitud)->where('Num_muestra', 1)->get();
        $solicitudParametros2Length = $solicitudParametros2->count(); */

        //Recupera los límites de cuantificación de los parámetros
        foreach ($model2 as $item) {
            $limite2C = DB::table('parametros')->where('Id_parametro', $item->Id_parametro)->first();

            if($item->Id_parametro == 27){ //Gasto
                array_push($limites2C, number_format($gastosProm2, 2, ".", ","));
            }else if($item->Id_parametro == 98){ //Temperatura
                array_push($limites2C, number_format($tProm2, 2, ".", ","));
            }else if($item->Id_parametro == 15){ //pH
                array_push($limites2C, number_format($phProm2, 2, ".", ","));
            }else if ($item->Resultado < $limite2C->Limite) {
                $lim2C = "< " . number_format($limite2C->Limite, 3, ".", ",");

                array_push($limiteMostrar2, 1);
                array_push($limites2C, $lim2C);
            } else {  //Si es mayor el resultado que el límite de cuantificación
                if($item->Id_parametro == 20){   //Cianuros
                    $lim2C = number_format($item->Resultado, 3, ".", ",");
                }else if($item->Id_parametro == 13){   //Coliformes fecales
                    $lim2C = number_format($item->Resultado, 2, ".", ",");
                }else if($item->Id_parametro == 22){   //Cromo total
                    $lim2C = number_format($item->Resultado, 3, ".", ",");
                }else if($item->Id_parametro == 72){   //DBO
                    $lim2C = number_format($item->Resultado, 2, ".", ",");
                }else if($item->Id_parametro == 7){   //DQO
                    $lim2C = number_format($item->Resultado, 2, ".", ",");
                }else if($item->Id_parametro == 16){   //Fosforo
                    $lim2C = number_format($item->Resultado, 3, ".", ",");
                }else if($item->Id_parametro == 14){   //Grasas y Aceites
                    $lim2C = number_format($item->Resultado, 2, ".", ",");
                }else if($item->Id_parametro == 17){   //Huevos de helminto
                    $lim2C = number_format($item->Resultado, 0, ".", ",");
                }else if($item->Id_parametro == 23){   //Mercurio
                    $lim2C = number_format($item->Resultado, 4, ".", ",");
                }else if($item->Id_parametro == 8){   //Nitratos
                    $lim2C = number_format($item->Resultado, 3, ".", ",");
                }else if($item->Id_parametro == 9){   //Nitritos
                    $lim2C = number_format($item->Resultado, 3, ".", ",");
                }else if($item->Id_parametro == 10 || $item->Id_parametro == 11){   //Nitrogeno Amoniacal y Nitrogeno Organico
                    $limC2 = number_format($item->Resultado, 2, ".", ",");
                }else{
                    $lim2C = number_format($item->Resultado, 3, ".", ",");
                }
                
                array_push($limiteMostrar2, 0);
                array_push($limites2C, $lim2C);
            }
            array_push($resultsParam2, $item->Resultado);
        }  

        //*************************************** CÁLCULO DQO ********************************************************
        $solicitudParamDqo = DB::table('ViewCodigoParametro')->where('Id_solicitud', $solModel->Id_solicitud)->where('Id_parametro', 7)->first();        

        $solicitudParamDqo2 = DB::table('ViewCodigoParametro')->where('Id_solicitud', $solModel2[0]->Id_solicitud)->where('Id_parametro', 7)->first();        

        //Establece si debe mostrarse o no el promedio ponderado de los DQO
        $limExceedDqo1  = 0;
        $limExceedDqo2  = 0;

        //Límite de DQO
        $limDqo = 0;

        //Cálculo DQO de la segunda solicitud
        $dqoFinal1 = 0;
        
        //Cálculo DQO de la segunda solicitud
        $dqoFinal2 = 0;

        //Cálculo DQO del primer folio
        if(!is_null($solicitudParamDqo)){            
            $preCalculo = $gastoLPS1 / ($gastoLPS1 + $gastoLPS2);
            $dqoFinal1 = round(($solicitudParamDqo->Resultado * $preCalculo), 2);
        }

        //Verifica si el resultado es menor al límite de cuantificación del parámetro
        $limiteDqo = DB::table('parametros')->where('Id_parametro', 7)->first();

        if ($dqoFinal1 < $limiteDqo->Limite) {
            $dqoFinal1 = "< " . $limiteDqo->Limite;
            $limExceedDqo1 = 1;
            $limDqo = $limiteDqo->Limite;
        }

        //Cálculo DQO del segundo folio
        if(!is_null($solicitudParamDqo2)){            
            $preCalculo = $gastoLPS1 / ($gastoLPS1 + $gastoLPS2);
            $dqoFinal2 = round(($solicitudParamDqo2->Resultado * $preCalculo), 2);
        }

        //Verifica si el resultado es menor al límite de cuantificación del parámetro
        $limiteDqo2 = DB::table('parametros')->where('Id_parametro', 7)->first();

        if ($dqoFinal2 < $limiteDqo2->Limite) {
            $dqoFinal2 = "< " . $limiteDqo2->Limite;
            $limExceedDqo2 = 1;
            $limDqo = $limiteDqo2->Limite;
        }
        //*************************************** FIN DE CÁLCULO DQO *************************************************      
        
        //Recupera la base del folio 1
        $folio = explode("-", $solModel->Folio_servicio);
        $parte1 = strval($folio[0]);
        $parte2 = strval($folio[1]);

        $numOrden = Solicitud::where('Folio_servicio', $parte1 . "-" . $parte2)->first();

        //Recupera la base del folio 2
        //Recupera la base del folio
        $folio2 = explode("-", $solModel->Folio_servicio);
        $parte12 = strval($folio2[0]);
        $parte22 = strval($folio2[1]);

        $numOrden2 = Solicitud::where('Folio_servicio', $parte12 . "-" . $parte22)->first();           
        
        //Almacena el límite de grasas
        $lGras = $limGrasas->Limite;

        //Almacena el límite coliformes fecales
        $limiteCuantiColi = DB::table('parametros')->where('Id_parametro', 13)->first();
        $lColi = $limiteCuantiColi->Limite;

        //Almacena el límite DQO
        $limiteCuantiDqo = DB::table('parametros')->where('Id_parametro', 7)->first();
        $lDqo = $limiteCuantiDqo->Limite;
        
        //BODY;Por añadir validaciones, mismas que se irán implementando cuando haya una tabla en la BD para los informes
        $htmlInforme = view('exports.informes.sinComparacion.bodyInformeMensual',  compact('limitesC', 'limites2C', 'limiteMostrar', 'limiteMostrar2', 'sumaCaudalesFinal', 'resColi', 'sumaCaudalesFinal2', 'resColi2', 'dqoFinal1', 'dqoFinal2', 'modelLength', 'solModel', 'model', 'model2', 'limExceed1', 'limExceed2', 'limGras', 'limExceedColi1', 'limExceedColi2', 'limColi', 'limExceedDqo1', 'limExceedDqo2', 'limDqo', 'lGras', 'lColi', 'lDqo', 'limitesNorma', 'resultsParam', 'resultsParam2', 'sParam'));

        //HEADER-FOOTER******************************************************************************************************************
        $htmlHeader = view('exports.informes.sinComparacion.headerInformeMensual', compact('solModel', 'solModel2', 'direccion', 'cliente', 'puntoMuestreo', 'norma', 'modelProcesoAnalisis1', 'modelProcesoAnalisis2', 'gastoLPS1', 'gastoLPS2', 'numOrden', 'numOrden2'));
        $htmlFooter = view('exports.informes.sinComparacion.footerInformeMensual', compact('solModel', 'simbologiaParam'));

        $mpdf->setHeader("{PAGENO} / {nbpg} <br><br>" . $htmlHeader);
        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
        $mpdf->WriteHTML($htmlInforme);

        $mpdf->CSSselectMedia = 'mpdf';
        $mpdf->Output('Informe de Resultados Sin Comparacion.pdf', 'D');                  
        //echo $modelLength;
        
        //echo implode(" , ", $limitesNorma);
        //echo implode(" , ", $limiteMostrar2);
    }

    public function pdfComparacionCliente($idSol)
    {
        //Opciones del documento PDF
        $mpdf = new \Mpdf\Mpdf([
            'orientation' => 'L',
            'format' => 'letter',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 74,
            'margin_bottom' => 76,
            'defaultheaderfontstyle' => ['normal'],
            'defaultheaderline' => '0'
        ]);            
        
        // Hace los filtros para realizar la comparacion
        $solModel = DB::table('ViewSolicitud')->where('Id_solicitud', $idSol)->first();
        $solModel2 = DB::table('ViewSolicitud')->where('IdPunto', $solModel->IdPunto)->OrderBy('Id_solicitud', 'DESC')->get();

        //ViewCodigoParametro
        $cont = (sizeof($solModel2) - 1);
        
        $model = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Num_muestra', 1)->orderBy('Parametro', 'ASC')->get();
        $modelLength = $model->count();

        $sParam = array();

        foreach($model as $item){
            $paramModel = Parametro::where('Id_parametro', $item->Id_parametro)->first();
            $sP = SimbologiaParametros::where('Id_simbologia', $paramModel->Id_simbologia)->first();
            array_push($sParam, $sP->Simbologia);
        }

        //Recupera sin duplicados las simbologías de los parámetros
        $simbolParam = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->get();
        $simbolParam = $simbolParam->unique('Id_simbologia');
        $simbologiaParam = array();

        foreach($simbolParam as $item){
            array_push($simbologiaParam, $item->Id_simbologia);
        }

        $model2 = DB::table('ViewCodigoParametro')->where('Id_solicitud', $solModel2[0]->Id_solicitud)->where('Num_muestra', 1)->orderBy('Parametro', 'ASC')->get();

        //Formatea la fecha; Por adaptar para el informe sin comparacion
        $fechaAnalisis = DB::table('ViewLoteAnalisis')->where('Id_lote', 0)->first();
        if (!is_null($fechaAnalisis)) {
            $fechaConFormato = date("d/m/Y", strtotime($fechaAnalisis->Fecha));
        }        
        
        //Obtiene la norma------------------------
        $norma = Norma::where('Id_norma', $solModel->Id_norma)->first();
        
        //Obtiene la dirección del reporte---------------------
        $direccion = DireccionReporte::where('Id_direccion', $solModel->Id_direccion)->first();                
        
        //Recupera el nombre del cliente---------------
        $cliente = Clientes::where('Id_cliente', $solModel->Id_cliente)->first();

        //Recupera el punto de muestreo del informe-----------------
        $solicitudPunto = SolicitudPuntos::where('Id_solicitud', $solModel->Id_solicitud)->first();
        $puntoMuestreo = DB::table('puntos_muestreo')->where('Id_punto', $solicitudPunto->Id_punto)->first();

        //*************************************CALCULO DE CONCENTRACIÓN CUANTIFICADA DE GRASAS*************************************
        //Consulta si existe el parámetro de Grasas y Aceites en la solicitud
        $solicitudParametroGrasas = DB::table('ViewCodigoParametro')->where('Id_solicitud', $solModel->Id_solicitud)->where('Id_parametro', 14)->get();
        $solicitudParametroGrasasLength = $solicitudParametroGrasas->count();

        $solicitudParametroGrasas2 = DB::table('ViewCodigoParametro')->where('Id_solicitud', $solModel2[0]->Id_solicitud)->where('Id_parametro', 14)->get();
        $solicitudParametroGrasasLength2 = $solicitudParametroGrasas2->count();

        $sumaCaudales = 0;
        $sumaCaudales2 = 0;

        //Establece si debe mostrarse o no el promedio ponderado de los caudales
        $limExceed1  = 0;
        $limExceed2  = 0;

        //Límite de grasas
        $limGras = 0;
        $limGrasas = DB::table('parametros')->where('Id_parametro', 14)->first();

        //Cálculo grasas de la segunda solicitud
        $sumaCaudalesFinal = 0;

        //Cálculo grasas de la segunda solicitud
        $sumaCaudalesFinal2 = 0;                

        //Calcula Grasas para el primer folio
        if ($solicitudParametroGrasasLength > 0) { //Encontró grasas

            //Recupera los gastos (caudales) de la solicitud
            $gastosModel = GastoMuestra::where('Id_solicitud', $solModel->Id_solicitud)->get();
            $gastosModelLength = $gastosModel->count(); 
            
            if($gastosModelLength > 0){
                //Arreglo que almacena el resultado de cada caudal entre la sumatoria de los caudales
                $divCaudalSuma = array();

                //Arreglo que almacena los resultados de las multiplicaciones de divCaudalSuma por el resultado de cada muestra del parámetro
                $multResDivCaudal = array();

                //Paso 1: Sumatoria de los caudales
                foreach ($gastosModel as $item) {
                    if ($item->Promedio === null) {
                        $sumaCaudales += 0;
                    } else {
                        $sumaCaudales += $item->Promedio;
                    }
                }

                $sumaCaudales = round($sumaCaudales, 2);

                //Paso 2: División de cada caudal entre la sumatoria de los caudales
                foreach ($gastosModel as $item) {
                    if ($item->Promedio === null) {
                        $div = 0 / $sumaCaudales;

                        array_push(
                            $divCaudalSuma,
                            $div
                        );
                    } else {
                        $div = round(($item->Promedio / $sumaCaudales), 2);

                        array_push(
                            $divCaudalSuma,
                            $div
                        );
                    }
                }

                //Paso 3: Multiplicación de cada divCaudalSuma por el resultado del parametro                                    
                for ($i = 0; $i < $solicitudParametroGrasasLength; $i++) {
                    $mult = round(($divCaudalSuma[$i] * $solicitudParametroGrasas[$i]->Resultado), 2);

                    array_push(
                        $multResDivCaudal,
                        $mult
                    );
                }

                //Paso 4: Sumatoria de multResDivCaudal
                foreach ($multResDivCaudal as $item) {
                    $sumaCaudalesFinal += $item;
                }                

                $sumaCaudalesFinal = round($sumaCaudalesFinal, 2);
            }            
        }

        //Verifica si el resultado es menor al límite de cuantificación del parámetro
        $limiteGrasas = DB::table('parametros')->where('Id_parametro', 14)->first();

        if ($sumaCaudalesFinal < $limiteGrasas->Limite) {
            $sumaCaudalesFinal = "< " . $limiteGrasas->Limite;
            $limExceed1 = 1;
            $limGras = $limiteGrasas->Limite;
        }        

        //Calcula Grasas para el segundo folio        
        if ($solicitudParametroGrasasLength2 > 0) { //Encontró grasas

            //Recupera los gastos (caudales) de la solicitud
            $gastosModel = GastoMuestra::where('Id_solicitud', $solModel2[0]->Id_solicitud)->get();
            
            $gastosModelLength = $gastosModel->count(); 
            
            if($gastosModelLength > 0){ //Encontró grasas
                //Arreglo que almacena el resultado de cada caudal entre la sumatoria de los caudales
                $divCaudalSuma = array();

                //Arreglo que almacena los resultados de las multiplicaciones de divCaudalSuma por el resultado de cada muestra del parámetro
                $multResDivCaudal = array();

                //Paso 1: Sumatoria de los caudales
                foreach ($gastosModel as $item) {
                    if ($item->Promedio === null) {
                        $sumaCaudales2 += 0;
                    } else {
                        $sumaCaudales2 += $item->Promedio;
                    }
                }

                $sumaCaudales2 = round($sumaCaudales2, 2);

                //Paso 2: División de cada caudal entre la sumatoria de los caudales
                foreach ($gastosModel as $item) {
                    if ($item->Promedio === null) {
                        $div = 0 / $sumaCaudales2;

                        array_push(
                            $divCaudalSuma,
                            $div
                        );
                    } else {
                        $div = round(($item->Promedio / $sumaCaudales2), 2);

                        array_push(
                            $divCaudalSuma,
                            $div
                        );
                    }
                }

                //Paso 3: Multiplicación de cada divCaudalSuma por el resultado del parametro                                    
                for ($i = 0; $i < $solicitudParametroGrasasLength2; $i++) {
                    $mult = round(($divCaudalSuma[$i] * $solicitudParametroGrasas2[$i]->Resultado), 2);

                    array_push(
                        $multResDivCaudal,
                        $mult
                    );
                }

                //Paso 4: Sumatoria de multResDivCaudal
                foreach ($multResDivCaudal as $item) {
                    $sumaCaudalesFinal2 += $item;
                }   
                
                $sumaCaudalesFinal2 = round(($sumaCaudalesFinal2), 2);
            }                                  
        }

        //Verifica si el resultado es menor al límite de cuantificación del parámetro
        $limiteGrasas = DB::table('parametros')->where('Id_parametro', 14)->first();
                
        if ($sumaCaudalesFinal2 < $limiteGrasas->Limite) {
            $sumaCaudalesFinal2 = "< " . $limiteGrasas->Limite;
            $limExceed2 = 1;
            $limGras = $limiteGrasas->Limite;
        }        
        
        //**************************************FIN DE CALCULO DE CONCENTRACION CUANTIFICADA DE GRASAS ******************************

        //************************************** CALCULO DE COLIFORMES FECALES******************************************************
        //Consulta si existe el parámetro de Coliformes Fecales en la solicitud
        $solicitudParametroColiformesFe = DB::table('ViewCodigoParametro')->where('Id_solicitud', $solModel->Id_solicitud)->where('Id_parametro', 13)->get();
        $solicitudParametroColiformesFeLength = $solicitudParametroColiformesFe->count();

        $solicitudParametroColiformesFe2 = DB::table('ViewCodigoParametro')->where('Id_solicitud', $solModel2[0]->Id_solicitud)->where('Id_parametro', 13)->get();
        $solicitudParametroColiformesFeLength2 = $solicitudParametroColiformesFe2->count();

        //Establece si debe mostrarse o no el promedio ponderado de los coliformes
        $limExceedColi1  = 0;
        $limExceedColi2  = 0;
        $limColi = 0;

        //Calculo de coliformes de la primera solicitud
        $resColi = 0;

        //Calculo de coliformes de la segunda solicitud
        $resColi2 = 0;

        if ($solicitudParametroColiformesFeLength > 0) { //Encontró coliformes fecales para folio 1
            $productoColi = 1;

            //Paso 1: Multiplicación de todos los resultados de coliformes fecales
            foreach ($solicitudParametroColiformesFe as $item) {
                $productoColi *= $item->Resultado;
            }

            //Paso 2: Raíz a la N cantidad de resultados de coliformes
            $resColi = round(pow($productoColi, 1 / $solicitudParametroColiformesFeLength), 2);
        }        

        //Verifica si el resultado es menor al límite de cuantificación del parámetro
        $limiteColi = DB::table('parametros')->where('Id_parametro', 13)->first();

        if ($resColi < $limiteColi->Limite) {
            $resColi = "< " . $limiteColi->Limite;
            $limExceedColi1 = 1;
            $limColi = $limiteColi->Limite;
        }



        if ($solicitudParametroColiformesFeLength2 > 0) { //Encontró coliformes fecales para folio 2
            $productoColi = 1;

            //Paso 1: Multiplicación de todos los resultados de coliformes fecales
            foreach ($solicitudParametroColiformesFe2 as $item) {
                $productoColi *= $item->Resultado;
            }

            //Paso 2: Raíz a la N cantidad de resultados de coliformes
            $resColi2 = round(pow($productoColi, 1 / $solicitudParametroColiformesFeLength2), 2);
        }

        //Verifica si el resultado es menor al límite de cuantificación del parámetro
        $limiteColi2 = DB::table('parametros')->where('Id_parametro', 13)->first();

        if ($resColi2 < $limiteColi2->Limite) {
            $resColi2 = "< " . $limiteColi2->Limite;
            $limExceedColi2 = 1;
            $limColi = $limiteColi->Limite;
        }
        //************************************** FIN DE CALCULO DE COLIFORMES FECALES*********************************************** 

        //Recupera el gasto promedio para la solicitud dada
        $gModel = GastoMuestra::where('Id_solicitud', $idSol)->get();
        $gModelLength = $gastosModel->count();
        $gastosProm = 0;

        if(!is_null($gModel)){
            foreach($gModel as $item){
                if($item->Promedio == null){
                    $gastosProm += 0;
                }else{
                    $gastosProm += $item->Promedio;
                }                
            }

            $gastosProm /= $gModelLength;
        }

        //Recupera la temperatura promedio para la solicitud dada
        $tModel = TemperaturaMuestra::where('Id_solicitud', $idSol)->get();
        $tModelLength = $tModel->count();
        $tProm = 0;

        if(!is_null($tModel)){
            foreach($tModel as $item){
                if($item->Promedio == null){
                    $tProm += 0;
                }else{
                    $tProm += $item->Promedio;
                }                
            }

            $tProm /= $tModelLength;
        }

        //Recupera el pH promedio para la solicitud dada
        $phModel = PhMuestra::where('Id_solicitud', $idSol)->get();
        $phModelLength = $phModel->count();
        $phProm = 0;

        if(!is_null($phModel)){
            foreach($phModel as $item){
                if($item->Promedio == null){
                    $phProm += 0;
                }else{
                    $phProm += $item->Promedio;
                }                
            }

            $phProm /= $phModelLength;
        }

        $limiteMostrar = array();
        $limitesC = array();  

        //Almacena los límites de la Norma
        $limitesNorma = array();
        
        //Almacena los resultados de los parametros
        $resultsParam = array();

        //Recupera los límites de cuantificación de los parámetros del primer folio
        foreach ($model as $item) {
            $limiteC = DB::table('parametros')->where('Id_parametro', $item->Id_parametro)->first();
            $paramLim = Limite001::where('Id_parametro', $item->Id_parametro)->first();

            //
            /* switch($item->Id_parametro){
                case 14: 
            } */

            if($item->Id_parametro == 27){ //Gasto
                array_push($limitesC, number_format($gastosProm, 2, ".", ","));
            }else if($item->Id_parametro == 98){ //Temperatura
                array_push($limitesC, number_format($tProm, 2, ".", ","));
            }else if($item->Id_parametro == 15){ //pH
                array_push($limitesC, number_format($phProm, 2, ".", ","));
            }else if ($item->Resultado < $limiteC->Limite) {
                $limC = "< " . round($limiteC->Limite, 3);

                array_push($limiteMostrar, 1);
                array_push($limitesC, $limC);
            } else {  //Si es mayor el resultado que el límite de cuantificación
                if($item->Id_parametro == 20){   //Cianuros
                    $limC = number_format($item->Resultado, 3, ".", ",");
                }else if($item->Id_parametro == 13){   //Coliformes fecales
                    $limC = number_format($item->Resultado, 2, ".", ",");
                }else if($item->Id_parametro == 22){   //Cromo total
                    $limC = number_format($item->Resultado, 3, ".", ",");
                }else if($item->Id_parametro == 72){   //DBO
                    $limC = number_format($item->Resultado, 2, ".", ",");
                }else if($item->Id_parametro == 7){   //DQO
                    $limC = number_format($item->Resultado, 2, ".", ",");
                }else if($item->Id_parametro == 16){   //Fosforo
                    $limC = number_format($item->Resultado, 3, ".", ",");
                }else if($item->Id_parametro == 14){   //Grasas y Aceites
                    $limC = number_format($item->Resultado, 2, ".", ",");
                }else if($item->Id_parametro == 17){   //Huevos de helminto
                    $limC = number_format($item->Resultado, 0, ".", ",");
                }else if($item->Id_parametro == 23){   //Mercurio
                    $limC = number_format($item->Resultado, 4, ".", ",");
                }else if($item->Id_parametro == 8){   //Nitratos
                    $limC = number_format($item->Resultado, 3, ".", ",");
                }else if($item->Id_parametro == 9){   //Nitritos
                    $limC = number_format($item->Resultado, 3, ".", ",");
                }else if($item->Id_parametro == 10 || $item->Id_parametro == 11){   //Nitrogeno Amoniacal y Nitrogeno Organico
                    $limC = number_format($item->Resultado, 2, ".", ",");
                }else{
                    $limC = number_format($item->Resultado, 3, ".", ",");
                }                
                            
                array_push($limiteMostrar, 0);
                array_push($limitesC, $limC);
            }

            array_push($limitesNorma, @$paramLim->Prom_Mmax);
            array_push($resultsParam, $item->Resultado);
        }

        //Recupera la fecha de recepción del primer y segundo folio
        $modelProcesoAnalisis1 = ProcesoAnalisis::where('Id_solicitud', $solModel->Id_solicitud)->first();
        $modelProcesoAnalisis2 = ProcesoAnalisis::where('Id_solicitud', $solModel2[0]->Id_solicitud)->first();

        //Calcula Gasto LPS1********************************************************************
        $gastosModel = GastoMuestra::where('Id_solicitud', $solModel->Id_solicitud)->get();
        $gastosModelLength = $gastosModel->count();
        $gastoSum1 = 0;
        $gastoLPS1 = 0;
        
        if($gastosModelLength > 0){
            //Paso 1: Sumatoria de los caudales
            foreach ($gastosModel as $item) {
                if ($item->Promedio === null) {
                    $gastoSum1 += 0;
                } else {
                    $gastoSum1 += $item->Promedio;
                }
            }

            //Paso 2: División entre el total de gastos
            $gastoLPS1 = $gastoSum1 / $gastosModelLength;
        } 

        //Calcula Gasto LPS2*********************************************************************     
                
        $gastosModel2 = GastoMuestra::where('Id_solicitud', $solModel2[0]->Id_solicitud)->get();
        $gastosModelLength2 = $gastosModel2->count();
        $gastoSum2 = 0;
        $gastoLPS2 = 0;

        if($gastosModelLength2 > 0){
            //Paso 1: Sumatoria de los caudales
            foreach ($gastosModel2 as $item) {
                if ($item->Promedio === null) {
                    $gastoSum2 += 0;
                } else {
                    $gastoSum2 += $item->Promedio;
                }
            }

            //Paso 2: División entre el total de gastos            
            $gastoLPS2 = $gastoSum2 / $gastosModelLength2;
        }      
        //*******************************************************************************************  

        //Recupera el gasto promedio para la solicitud dada
                
        $gModel2 = GastoMuestra::where('Id_solicitud', $solModel2[0]->Id_solicitud)->get();        
        $gModelLength2 = $gastosModel->count();
        $gastosProm2 = 0;

        if($gModelLength2 > 0){
            foreach($gModel2 as $item){
                if($item->Promedio == null){
                    $gastosProm2 += 0;
                }else{
                    $gastosProm2 += $item->Promedio;
                }                
            }

            $gastosProm2 /= $gModelLength2;
        }        

        //Recupera la temperatura promedio para la solicitud dada
        $tModel2 = TemperaturaMuestra::where('Id_solicitud', $solModel2[0]->Id_solicitud)->get();
        $tModelLength2 = $tModel2->count();
        $tProm2 = 0;

        if($tModelLength2 > 0){
            foreach($tModel2 as $item){
                if($item->Promedio == null){
                    $tProm2 += 0;
                }else{
                    $tProm2 += $item->Promedio;
                }                
            }

            $tProm2 /= $tModelLength2;
        }

        //Recupera el pH promedio para la solicitud dada
        $phModel2 = PhMuestra::where('Id_solicitud', $solModel2[0]->Id_solicitud)->get();
        $phModelLength2 = $phModel2->count();
        $phProm2 = 0;

        if($phModelLength2 > 0){
            foreach($phModel2 as $item){
                if($item->Promedio == null){
                    $phProm2 += 0;
                }else{
                    $phProm2 += $item->Promedio;
                }                
            }

            $phProm2 /= $phModelLength2;
        }

        $limiteMostrar2 = array();
        $limites2C = array();  
        $resultsParam2 = array();              

        /* $solicitudParametros2 = DB::table('ViewCodigoParametro')->where('Id_solicitud', $solModel2[0]->Id_solicitud)->where('Num_muestra', 1)->get();
        $solicitudParametros2Length = $solicitudParametros2->count(); */

        //Recupera los límites de cuantificación de los parámetros
        foreach ($model2 as $item) {
            $limite2C = DB::table('parametros')->where('Id_parametro', $item->Id_parametro)->first();

            if($item->Id_parametro == 27){ //Gasto
                array_push($limites2C, number_format($gastosProm2, 2, ".", ","));
            }else if($item->Id_parametro == 98){ //Temperatura
                array_push($limites2C, number_format($tProm2, 2, ".", ","));
            }else if($item->Id_parametro == 15){ //pH
                array_push($limites2C, number_format($phProm2, 2, ".", ","));
            }else if ($item->Resultado < $limite2C->Limite) {
                $lim2C = "< " . round($limite2C->Limite, 3);

                array_push($limiteMostrar2, 1);
                array_push($limites2C, $lim2C);
            } else {  //Si es mayor el resultado que el límite de cuantificación
                if($item->Id_parametro == 20){   //Cianuros
                    $lim2C = number_format($item->Resultado, 3, ".", ",");
                }else if($item->Id_parametro == 13){   //Coliformes fecales
                    $lim2C = number_format($item->Resultado, 2, ".", ",");
                }else if($item->Id_parametro == 22){   //Cromo total
                    $lim2C = number_format($item->Resultado, 3, ".", ",");
                }else if($item->Id_parametro == 72){   //DBO
                    $lim2C = number_format($item->Resultado, 2, ".", ",");
                }else if($item->Id_parametro == 7){   //DQO
                    $lim2C = number_format($item->Resultado, 2, ".", ",");
                }else if($item->Id_parametro == 16){   //Fosforo
                    $lim2C = number_format($item->Resultado, 3, ".", ",");
                }else if($item->Id_parametro == 14){   //Grasas y Aceites
                    $lim2C = number_format($item->Resultado, 2, ".", ",");
                }else if($item->Id_parametro == 17){   //Huevos de helminto
                    $lim2C = number_format($item->Resultado, 0, ".", ",");
                }else if($item->Id_parametro == 23){   //Mercurio
                    $lim2C = number_format($item->Resultado, 4, ".", ",");
                }else if($item->Id_parametro == 8){   //Nitratos
                    $lim2C = number_format($item->Resultado, 3, ".", ",");
                }else if($item->Id_parametro == 9){   //Nitritos
                    $lim2C = number_format($item->Resultado, 3, ".", ",");
                }else if($item->Id_parametro == 10 || $item->Id_parametro == 11){   //Nitrogeno Amoniacal y Nitrogeno Organico
                    $limC2 = number_format($item->Resultado, 2, ".", ",");
                }else{
                    $lim2C = number_format($item->Resultado, 3, ".", ",");
                }
                
                array_push($limiteMostrar2, 0);
                array_push($limites2C, $lim2C);
            }
            array_push($resultsParam2, $item->Resultado);
        }  

        //*************************************** CÁLCULO DQO ********************************************************
        $solicitudParamDqo = DB::table('ViewCodigoParametro')->where('Id_solicitud', $solModel->Id_solicitud)->where('Id_parametro', 7)->first();        

        $solicitudParamDqo2 = DB::table('ViewCodigoParametro')->where('Id_solicitud', $solModel2[0]->Id_solicitud)->where('Id_parametro', 7)->first();        

        //Establece si debe mostrarse o no el promedio ponderado de los DQO
        $limExceedDqo1  = 0;
        $limExceedDqo2  = 0;

        //Límite de DQO
        $limDqo = 0;

        //Cálculo DQO de la segunda solicitud
        $dqoFinal1 = 0;
        
        //Cálculo DQO de la segunda solicitud
        $dqoFinal2 = 0;

        //Cálculo DQO del primer folio
        if(!is_null($solicitudParamDqo)){            
            $preCalculo = $gastoLPS1 / ($gastoLPS1 + $gastoLPS2);
            $dqoFinal1 = round(($solicitudParamDqo->Resultado * $preCalculo), 2);
        }

        //Verifica si el resultado es menor al límite de cuantificación del parámetro
        $limiteDqo = DB::table('parametros')->where('Id_parametro', 7)->first();

        if ($dqoFinal1 < $limiteDqo->Limite) {
            $dqoFinal1 = "< " . $limiteDqo->Limite;
            $limExceedDqo1 = 1;
            $limDqo = $limiteDqo->Limite;
        }

        //Cálculo DQO del segundo folio
        if(!is_null($solicitudParamDqo2)){            
            $preCalculo = $gastoLPS1 / ($gastoLPS1 + $gastoLPS2);
            $dqoFinal2 = round(($solicitudParamDqo2->Resultado * $preCalculo), 2);
        }

        //Verifica si el resultado es menor al límite de cuantificación del parámetro
        $limiteDqo2 = DB::table('parametros')->where('Id_parametro', 7)->first();

        if ($dqoFinal2 < $limiteDqo2->Limite) {
            $dqoFinal2 = "< " . $limiteDqo2->Limite;
            $limExceedDqo2 = 1;
            $limDqo = $limiteDqo2->Limite;
        }
        //*************************************** FIN DE CÁLCULO DQO *************************************************      
        
        //Recupera la base del folio 1
        $folio = explode("-", $solModel->Folio_servicio);
        $parte1 = strval($folio[0]);
        $parte2 = strval($folio[1]);

        $numOrden = Solicitud::where('Folio_servicio', $parte1 . "-" . $parte2)->first();

        //Recupera la base del folio 2
        //Recupera la base del folio
        $folio2 = explode("-", $solModel->Folio_servicio);
        $parte12 = strval($folio2[0]);
        $parte22 = strval($folio2[1]);

        $numOrden2 = Solicitud::where('Folio_servicio', $parte12 . "-" . $parte22)->first();           
        
        //Almacena el límite de grasas
        $lGras = $limGrasas->Limite;

        //Almacena el límite coliformes fecales
        $limiteCuantiColi = DB::table('parametros')->where('Id_parametro', 13)->first();
        $lColi = $limiteCuantiColi->Limite;

        //Almacena el límite DQO
        $limiteCuantiDqo = DB::table('parametros')->where('Id_parametro', 7)->first();
        $lDqo = $limiteCuantiDqo->Limite;                    
        
        //BODY;Por añadir validaciones, mismas que se irán implementando cuando haya una tabla en la BD para los informes
        $htmlInforme = view('exports.informes.conComparacion.bodyInformeMensual',  compact('limitesC', 'limites2C', 'limiteMostrar', 'limiteMostrar2', 'sumaCaudalesFinal', 'resColi', 'sumaCaudalesFinal2', 'resColi2', 'dqoFinal1', 'dqoFinal2', 'modelLength', 'solModel', 'model', 'model2', 'limExceed1', 'limExceed2', 'limGras', 'limExceedColi1', 'limExceedColi2', 'limColi', 'limExceedDqo1', 'limExceedDqo2', 'limDqo', 'limitesNorma', 'lGras', 'lColi', 'lDqo', 'resultsParam', 'resultsParam2', 'sParam'));

        //HEADER-FOOTER******************************************************************************************************************
        $htmlHeader = view('exports.informes.conComparacion.headerInformeMensual', compact('solModel', 'solModel2', 'direccion', 'cliente', 'puntoMuestreo', 'norma', 'modelProcesoAnalisis1', 'modelProcesoAnalisis2', 'gastoLPS1', 'gastoLPS2', 'numOrden', 'numOrden2'));
        $htmlFooter = view('exports.informes.conComparacion.footerInformeMensual', compact('solModel', 'simbologiaParam'));

        $mpdf->setHeader("{PAGENO} / {nbpg} <br><br>" . $htmlHeader);
        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
        $mpdf->WriteHTML($htmlInforme);

        $mpdf->CSSselectMedia = 'mpdf';
        $mpdf->Output('Informe de Resultados Con Comparacion.pdf', 'I');
        //echo $modelDiag;
        //echo implode(" , ", $limiteMostrar);
        //echo implode(" , ", $limiteMostrar2);
    }


    //---------------------------------------


    public function custodiaInterna($idSol)
    {
        //Opciones del documento PDF
        $mpdf = new \Mpdf\Mpdf([
            'orientation' => 'L',
            'format' => 'letter',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 15,
            'margin_bottom' => 20,
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

        $model = DB::table('ViewSolicitud')->where('Id_solicitud', $idSol)->first();

        $recepcion = ProcesoAnalisis::where('Id_solicitud', $idSol)->first();

        $paramResultado = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->orderBy('Parametro', 'ASC')->get();
        $paramResultadoLength = $paramResultado->count();

        $recibidos = PhMuestra::where('Id_solicitud',$idSol)->where('Activo', 1)->get();
        $recibidosLength = $recibidos->count();
        $gastosModel2 = GastoMuestra::where('Id_solicitud', $idSol)->get();
        $gastosModelLength2 = $gastosModel2->count();
        $gastosModel = GastoMuestra::where('Id_solicitud', $idSol)->get();
        $gastosModelLength = $gastosModel->count();
        $tempModel = TemperaturaMuestra::where('Id_solicitud', $idSol)->where('Activo', 1)->get();
        $tempLength = $tempModel->count();
        $promGastos = 0;

        //Promedio temperatura
        foreach($gastosModel2 as $item){
            if($item->Promedio == NULL){
                $promGastos += 0;
            }else{
                $promGastos += $item->Promedio;
            }
        }

        $promGastos = $promGastos / $gastosModelLength2;        

        $limitesC = array();
        $limiteGrasas = "";
        $limiteCGrasa = DB::table('parametros')->where('Id_parametro', 14)->first();              

        //Calcula el promedio ponderado de las grasas y aceites
        //if ($gaMenorLimite == false) {
            $modelParamGrasas = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Id_parametro', 14)->get();
            $modelParamGrasasLength = $modelParamGrasas->count();

            //Calcula promedio ponderado de G y A
            $promPonderadoGaArray = 0;

            //Arreglo que contiene los valores de GYA * GASTO por cada fila
            $gaPorGastoArray = array();

            //Contiene la suma de G(GYA * GASTO)
            $sumaG = 0;

            //Arreglo que contiene los valores de GYA * GASTO entre sumaG por cada fila
            $gaPorGastoDivSumaArray = array();

            //Arreglo que contiene los valores de GYA * (GYA*GASTO) / SUMAG
            $gaPorgaGastoDivSumaG = array();

            //Realiza las operaciones GYA * GASTO
            for ($i = 0; $i < $modelParamGrasasLength; $i++) {
                //Si no tiene un valor guardado lo toma como cero
                if ($gastosModel[$i]->Promedio == null) {
                    $gastos = 0;
                } else {
                    $gastos = $gastosModel[$i]->Promedio;
                }

                array_push($gaPorGastoArray, $modelParamGrasas[$i]->Resultado * $gastos);
            }

            //Realiza la suma de G (GYA * GASTO)
            for ($i = 0; $i < $modelParamGrasasLength; $i++) {
                //Si no tiene un valor guardado lo toma como cero
                $sumaG += $gaPorGastoArray[$i];
            }

            //Realiza las operaciones (GYA*GASTO)/SUMA DE G
            for ($i = 0; $i < $modelParamGrasasLength; $i++) {
                array_push($gaPorGastoDivSumaArray, $gaPorGastoArray[$i] / $sumaG);
            }

            //Realiza las operaciones GYA * (GYA*GASTO) / SUMAG
            for ($i = 0; $i < $modelParamGrasasLength; $i++) {
                $resultado = ($modelParamGrasas[$i]->Resultado * $gaPorGastoArray[$i]) / $sumaG;
                array_push($gaPorgaGastoDivSumaG, $resultado);
            }

            //Calcula el promedio ponderado de GA
            for ($i = 0; $i < $modelParamGrasasLength; $i++) {
                $promPonderadoGaArray += $gaPorgaGastoDivSumaG[$i];
            }
        //}

        $promedioPonderadoGA = "";        
        if ($promPonderadoGaArray < $limiteCGrasa->Limite) {
            $promedioPonderadoGA = "<". $limiteCGrasa->Limite;            
        } else {            
            $promedioPonderadoGA = number_format($promPonderadoGaArray, 2, ".", ",");
        }                

        $limiteColiformes = "";
        $limiteColiformes = DB::table('parametros')->where('Id_parametro', 13)->first();        
        $mediaAritmeticaColi = 0;
        $mAritmeticaColi = "";        
        
            $modelParamColiformes = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Id_parametro', 13)->get();
            $modelParamColiformesLength = $modelParamColiformes->count();
            $res = 0;

            for ($i = 0; $i < $modelParamColiformesLength; $i++) {
                $res += $modelParamColiformes[$i]->Resultado;
            }

            $mediaAritmeticaColi = $res / $modelParamColiformesLength;        

        if ($mediaAritmeticaColi < $limiteColiformes->Limite){
            $mAritmeticaColi = "<". $limiteColiformes->Limite;
        } else {
            $mAritmeticaColi = number_format($mediaAritmeticaColi, 2, ".", ",");
        }        

        //Calcula el promedio de los promedios del gasto
        $gastoPromSuma = 0;
        foreach ($gastosModel as $item) {
            if ($item->Promedio == null) {
                $gastoPromSuma += 0;
            } else {
                $gastoPromSuma += $item->Promedio;
            }
        }

        $gastoPromFinal = $gastoPromSuma / $gastosModelLength;

        //Promedio de ph
        $promPh = 0;

        foreach($recibidos as $item){
            $promPh += $item->Promedio;
        }

        $promPh /= $recibidosLength;

        //Promedio de temperatura
        $promTemp = 0;

        foreach($tempModel as $item){
            $promTemp += $item->Promedio;
        }

        $promTemp /= $tempLength;

        //Recupera los límites de cuantificación de los parámetros        
        foreach ($paramResultado as $item) {
            $limiteC = DB::table('parametros')->where('Id_parametro', $item->Id_parametro)->first();

            if ($item->Resultado < $limiteC->Limite) {
                if($item->Id_parametro == 27){
                    $limC = number_format($promGastos, 2, ".", ",");
                }else if($item->Id_parametro == 15){ //pH
                    $limC = number_format($promPh, 1, ".", ",");
                }else if($item->Id_parametro == 98){ //temperatura
                    $limC = number_format($promTemp, 2, ".", ",");
                }else{
                    $limC = "< " . $limiteC->Limite;
                }                

                array_push($limitesC, $limC);
            } else {  //Si es mayor el resultado que el límite de cuantificación
                if($item->Id_parametro == 27){ //Gasto
                    $limC = number_format($promGastos, 2, ".", ",");                    
                }if($item->Id_parametro == 15){ //pH
                    $limC = number_format($promPh, 1, ".", ",");                    
                }else if($item->Id_parametro == 98){ //temperatura
                    $limC = number_format($promTemp, 2, ".", ",");
                }else{
                    $limC = $item->Resultado;
                }                                

                array_push($limitesC, $limC);
            }
        }
        
        $paquete = DB::table('ViewPlanPaquete')->where('Id_paquete', $model->Id_subnorma)->distinct()->get();
        $paqueteLength = $paquete->count();

        $responsables = array();

        foreach ($paquete as $item) {
            //INSTRUCCIÓN TEMPORAL
            // if (!is_null($item)) {
            //     $responsableArea = AreaLab::where('Area', $item->Area)->first();
            //     $modelResponsable = DB::table('users')->where('id', $responsableArea->Id_responsable)->first();
            //     $responsable = $modelResponsable->name;

            //     array_push($responsables, $responsable);
            // }
        }

        //$fechaEmision = \Carbon\Carbon::now();        
        $norma = Norma::where('Id_norma', $model->Id_norma)->first();

        $mpdf->showWatermarkImage = true;

        $htmlInforme = view('exports.campo.cadenaCustodiaInterna.bodyCadena', 
        compact('model', 'paquete', 'paqueteLength', 'norma','recibidos', 'recepcion', 'paramResultado', 'paramResultadoLength', 'limitesC', 'limiteGrasas', 'limiteColiformes', 'responsables', 'promedioPonderadoGA', 'mAritmeticaColi', 'gastoPromFinal'));

        $mpdf->WriteHTML($htmlInforme);

        $mpdf->CSSselectMedia = 'mpdf';
        $mpdf->Output('Cadena de Custodia Interna.pdf', 'I');
    }

    //***********************************************************SE ACCEDE A TRAVÉS DEL QR DESDE LA RUTA CLIENTES
    public function custodiaInternaCli($idSol)
    {
        //Opciones del documento PDF
        $mpdf = new \Mpdf\Mpdf([
            'orientation' => 'L',
            'format' => 'letter',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 15,
            'margin_bottom' => 20,
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

        $model = DB::table('ViewSolicitud')->where('Id_solicitud', $idSol)->first();

        $paramResultado = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->orderBy('Parametro', 'ASC')->get();
        $paramResultadoLength = $paramResultado->count();

        $recibidos = PhMuestra::where('Id_solicitud',$idSol)->where('Activo', 1)->get();
        $recibidosLength = $recibidos->count();
        $gastosModel2 = GastoMuestra::where('Id_solicitud', $idSol)->where('Activo', 1)->get();
        $gastosModelLength2 = $gastosModel2->count();
        $gastosModel = GastoMuestra::where('Id_solicitud', $idSol)->get();
        $gastosModelLength = $gastosModel->count();
        $tempModel = TemperaturaMuestra::where('Id_solicitud', $idSol)->where('Activo', 1)->get();
        $tempLength = $tempModel->count();
        $promGastos = 0;

        //Promedio temperatura
        foreach($gastosModel2 as $item){
            if($item->Promedio == NULL){
                $promGastos += 0;
            }else{
                $promGastos += $item->Promedio;
            }
        }

        $promGastos = $promGastos / $gastosModelLength2;

        $gaMenorLimite = false;

        $limitesC = array();
        $limiteGrasas = "";
        $limiteCGrasa = DB::table('parametros')->where('Id_parametro', 14)->first();
        $paramGrasasResultado = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Id_parametro', 14)->first();

        if ($paramGrasasResultado->Resultado < $limiteCGrasa->Limite) {
            $limC = "< " . $limiteCGrasa->Limite;

            $limiteGrasas = $limC;

            $gaMenorLimite = true;
        } else {  //Si es mayor el resultado que el límite de cuantificación            
            $limC = $paramGrasasResultado->Resultado;
            $limiteGrasas = $limC;            
        }        

        //Calcula el promedio ponderado de las grasas y aceites
        if ($gaMenorLimite === false) {
            $modelParamGrasas = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Id_parametro', 14)->get();
            $modelParamGrasasLength = $modelParamGrasas->count();

            //Calcula promedio ponderado de G y A
            $promPonderadoGaArray = 0;
            //Arreglo que contiene los valores de GYA * GASTO por cada fila
            $gaPorGastoArray = array();
            //Contiene la suma de G(GYA * GASTO)
            $sumaG = 0;
            //Arreglo que contiene los valores de GYA * GASTO entre sumaG por cada fila
            $gaPorGastoDivSumaArray = array();
            //Arreglo que contiene los valores de GYA * (GYA*GASTO) / SUMAG
            $gaPorgaGastoDivSumaG = array();

            //Realiza las operaciones GYA * GASTO
            for ($i = 0; $i < $modelParamGrasasLength; $i++) {
                //Si no tiene un valor guardado lo toma como cero
                if ($gastosModel[$i]->Promedio === null) {
                    $gastos = 0;
                } else {
                    $gastos = $gastosModel[$i]->Promedio;
                }

                array_push($gaPorGastoArray, $modelParamGrasas[$i]->Resultado * $gastos);
            }

            //Realiza la suma de G (GYA * GASTO)
            for ($i = 0; $i < $modelParamGrasasLength; $i++) {
                //Si no tiene un valor guardado lo toma como cero
                $sumaG += $gaPorGastoArray[$i];
            }

            //Realiza las operaciones (GYA*GASTO)/SUMA DE G
            for ($i = 0; $i < $modelParamGrasasLength; $i++) {
                array_push($gaPorGastoDivSumaArray, $gaPorGastoArray[$i] / $sumaG);
            }

            //Realiza las operaciones GYA * (GYA*GASTO) / SUMAG
            for ($i = 0; $i < $modelParamGrasasLength; $i++) {
                $resultado = ($modelParamGrasas[$i]->Resultado * $gaPorGastoArray[$i]) / $sumaG;
                array_push($gaPorgaGastoDivSumaG, $resultado);
            }

            //Calcula el promedio ponderado de GA
            for ($i = 0; $i < $modelParamGrasasLength; $i++) {
                $promPonderadoGaArray += $gaPorgaGastoDivSumaG[$i];
            }
        }

        $promedioPonderadoGA = "";
        if ($gaMenorLimite === false) {
            $promedioPonderadoGA = number_format($promPonderadoGaArray, 2);
        } else {
            $promedioPonderadoGA = $limiteGrasas;
        }

        $limiteColiformes = "";
        $limiteColiformes = DB::table('parametros')->where('Id_parametro', 13)->first();
        $paramColiformesResultado = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Id_parametro', 13)->first();
        $coliformesMenorLimite = false;
        $mediaAritmeticaColi = "";
        $mAritmeticaColi = "";

        if ($paramColiformesResultado->Resultado < $limiteColiformes->Limite) {
            $limC = "< " . $limiteColiformes->Limite;

            $limiteColiformes = $limC;

            $coliformesMenorLimite = true;
        } else {  //Si es mayor el resultado que el límite de cuantificación            
            $limC = $paramColiformesResultado->Resultado;
            $limiteColiformes = $limC;            
        }

        //Calcula la media aritmética de los coliformes
        if ($coliformesMenorLimite === false) {
            $modelParamColiformes = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Id_parametro', 13)->get();
            $modelParamColiformesLength = $modelParamColiformes->count();
            $res = 0;

            for ($i = 0; $i < $modelParamColiformesLength; $i++) {
                $res += $modelParamColiformes[$i]->Resultado;
            }

            $mediaAritmeticaColi = $res / $modelParamColiformesLength;
        }

        if ($coliformesMenorLimite === false) {
            $mAritmeticaColi = number_format($mediaAritmeticaColi, 2);
        } else {
            $mAritmeticaColi = $limiteColiformes;
        }

        //Calcula el promedio de los promedios del gasto
        $gastoPromSuma = 0;
        foreach ($gastosModel as $item) {
            if ($item->Promedio === null) {
                $gastoPromSuma += 0;
            } else {
                $gastoPromSuma += $item->Promedio;
            }
        }

        $gastoPromFinal = $gastoPromSuma / $gastosModelLength;

        //Promedio de ph
        $promPh = 0;

        foreach($recibidos as $item){
            $promPh += $item->Promedio;
        }

        $promPh /= $recibidosLength;

        //Promedio de temperatura
        $promTemp = 0;

        foreach($tempModel as $item){
            $promTemp += $item->Promedio;
        }

        $promTemp /= $tempLength;

        //Recupera los límites de cuantificación de los parámetros        
        foreach ($paramResultado as $item) {
            $limiteC = DB::table('parametros')->where('Id_parametro', $item->Id_parametro)->first();

            if ($item->Resultado < $limiteC->Limite) {
                if($item->Id_parametro == 27){
                    $limC = number_format($promGastos, 2, ".", ",");
                }else if($item->Id_parametro == 15){ //pH
                    $limC = number_format($promPh, 1, ".", ",");
                }else if($item->Id_parametro == 98){ //temperatura
                    $limC = number_format($promTemp, 2, ".", ",");
                }else{
                    $limC = "< " . $limiteC->Limite;
                }

                array_push($limitesC, $limC);
            } else {  //Si es mayor el resultado que el límite de cuantificación
                if($item->Id_parametro == 27){ //Gasto
                    $limC = number_format($promGastos, 2, ".", ",");                    
                }if($item->Id_parametro == 15){ //pH
                    $limC = number_format($promPh, 1, ".", ",");                    
                }else if($item->Id_parametro == 98){ //temperatura
                    $limC = number_format($promTemp, 2, ".", ",");
                }else{
                    $limC = $item->Resultado;
                }                                

                array_push($limitesC, $limC);
            }
        }
        
        $paquete = DB::table('ViewPlanPaquete')->where('Id_paquete', $model->Id_subnorma)->distinct()->get();
        $paqueteLength = $paquete->count();

        $responsables = array();

        foreach ($paquete as $item) {
            //INSTRUCCIÓN TEMPORAL
            // if (!is_null($item)) {
            //     $responsableArea = AreaLab::where('Area', $item->Area)->first();
            //     $modelResponsable = DB::table('users')->where('id', $responsableArea->Id_responsable)->first();
            //     $responsable = $modelResponsable->name;

            //     array_push($responsables, $responsable);
            // }
        }

        $fechaEmision = \Carbon\Carbon::now();        
        $norma = Norma::where('Id_norma', $model->Id_norma)->first();

        $mpdf->showWatermarkImage = true;

        $htmlInforme = view('exports.campo.cadenaCustodiaInterna.bodyCadena', 
        compact('model', 'paquete', 'paqueteLength', 'norma','recibidos' ,'fechaEmision', 'paramResultado', 'paramResultadoLength', 'limitesC', 'limiteGrasas', 'limiteColiformes', 'responsables', 'promedioPonderadoGA', 'mAritmeticaColi', 'gastoPromFinal'));

        $mpdf->WriteHTML($htmlInforme);

        $mpdf->CSSselectMedia = 'mpdf';
        $mpdf->Output('Cadena de Custodia Interna.pdf', 'D');
    }
}
