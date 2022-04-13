<?php

namespace App\Http\Controllers\Informes;

use App\Http\Controllers\Controller;
use App\Models\AreaLab;
use App\Models\CampoCompuesto;
use App\Models\Clientes;
use App\Models\CodigoParametros;
use App\Models\Cotizacion;
use App\Models\CotizacionPunto;
use App\Models\DireccionReporte;
use App\Models\GastoMuestra;
use App\Models\Norma;
use App\Models\Parametro;
use App\Models\ProcesoAnalisis;
use App\Models\PuntoMuestreoSir;
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
        $model = DB::table('ViewSolicitud')->get();
        return view('informes.informes', compact('tipoReporte', 'model'));
    }
    public function getPuntoMuestro(Request $request)
    {
        $model = DB::table('ViewPuntoMuestreoGen')->where('Id_solicitud', $request->id)->get();
        $data = array(
            'model' => $model,
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
        $model = DB::table('ViewSolicitud')->OrderBy('Id_solicitud','DESC')->get();
        return view('informes.mensual', compact('model'));
    }
    public function getPreReporteMensual(Request $res)
    {
        $solModel = DB::table('ViewSolicitud')->where('Id_solicitud', $res->idSol)->first();
        $solModel2 = DB::table('ViewSolicitud')->where('IdPunto', $solModel->IdPunto)->get();

        //ViewCodigoParametro
        $cont = (sizeof($solModel2) - 1);
        $model = DB::table('ViewCodigoParametro')->where('Id_solicitud', $solModel2[0]->Id_solicitud)->get();
        $model2 = DB::table('ViewCodigoParametro')->where('Id_solicitud', $solModel2[$cont]->Id_solicitud)->get();

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

        //Recupera el nombre de usuario y firma
        /* $usuario = DB::table('users')->where('id', auth()->user()->id)->first();
        $firma = $usuario->firma; */

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
        $solicitudPunto = SolicitudPuntos::where('Id_solicitud', $solicitud->Id_solicitud)->first();
        $puntoMuestreo = DB::table('puntos_muestreo')->where('Id_punto', $solicitudPunto->Id_punto)->first();

        /* $solicitudParametros = DB::table('ViewSolicitudParametros')->where('Id_solicitud', $solicitud->Id_solicitud)->get();
        $solicitudParametrosLength = $solicitudParametros->count(); */

        //Recupera los parámetros
        $solicitudParametros = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Num_muestra', 1)->orderBy('Parametro', 'ASC')->get();
        $solicitudParametrosLength = $solicitudParametros->count();


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

                    array_push(
                        $divCaudalSuma,
                        $div
                    );
                }
            }

            //Paso 3: Multiplicación de cada divCaudalSuma por el resultado del parametro                                    
            for ($i = 0; $i < $solicitudParametroGrasasLength; $i++) {
                $mult = $divCaudalSuma[$i] * $solicitudParametroGrasas[$i]->Resultado;

                array_push(
                    $multResDivCaudal,
                    $mult
                );
            }

            //Paso 4: Sumatoria de multResDivCaudal
            foreach ($multResDivCaudal as $item) {
                $sumaCaudalesFinal += $item;
            }

            //Verifica si el resultado es menor al límite de cuantificación del parámetro
            $limiteGrasas = DB::table('parametros')->where('Id_parametro', $solicitudParametroGrasas[0]->Id_parametro)->first();

            if ($sumaCaudalesFinal < $limiteGrasas->Limite) {
                $sumaCaudalesFinal = "< " . $limiteGrasas->Limite;
            }
        }

        //echo  implode(" , ", $multResDivCaudal);        

        //**************************************FIN DE CALCULO DE CONCENTRACION CUANTIFICADA DE GRASAS ******************************

        //************************************** CALCULO DE COLIFORMES FECALES ******************************************************
        //Consulta si existe el parámetro de Coliformes Fecales en la solicitud
        $solicitudParametroColiformesFe = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Id_parametro', 13)->get();
        $solicitudParametroColiformesFeLength = $solicitudParametroGrasas->count();

        if (!is_null($solicitudParametroColiformesFe)) { //Encontró coliformes fecales
            $productoColi = 1;

            //Paso 1: Multiplicación de todos los resultados de coliformes fecales
            foreach ($solicitudParametroColiformesFe as $item) {
                $productoColi *= $item->Resultado;
            }

            //Paso 2: Raíz a la N cantidad de resultados de coliformes
            $resColi = pow($productoColi, 1 / $solicitudParametroColiformesFeLength);

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

        $limitesC = array();

        //Recupera los límites de cuantificación de los parámetros        
        foreach ($solicitudParametros as $item) {
            $limiteC = DB::table('parametros')->where('Id_parametro', $item->Id_parametro)->first();

            if ($item->Resultado < $limiteC->Limite) {
                $limC = "< " . $limiteC->Limite;

                array_push($limitesC, $limC);
            } else {  //Si es mayor el resultado que el límite de cuantificación
                $limC = $item->Resultado;

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

        //BODY;Por añadir validaciones, mismas que se irán implementando cuando haya una tabla en la BD para los informes
        $htmlInforme = view('exports.informes.sinComparacion.bodyInforme',  compact('solicitudParametros', 'solicitudParametrosLength', 'limitesC', 'tempCompuesta', 'sumaCaudalesFinal', 'resColi'));

        //HEADER-FOOTER******************************************************************************************************************                 
        $htmlHeader = view('exports.informes.sinComparacion.headerInforme', compact('solicitud', 'direccion', 'cliente', 'puntoMuestreo', 'numOrden', 'modelProcesoAnalisis', 'horaMuestreo'));
        $htmlFooter = view('exports.informes.sinComparacion.footerInforme', compact('solicitud'/* 'usuario', 'firma' */));

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
        $puntoMuestreo = DB::table('puntos_muestreo')->where('Id_punto', $solicitudPunto->Id_punto)->first();

        /* $solicitudParametros = DB::table('ViewSolicitudParametros')->where('Id_solicitud', $solicitud->Id_solicitud)->get();
        $solicitudParametrosLength = $solicitudParametros->count(); */

        //Recupera los parámetros
        $solicitudParametros = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Num_muestra', 1)->orderBy('Parametro', 'ASC')->get();
        $solicitudParametrosLength = $solicitudParametros->count();

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

                    array_push(
                        $divCaudalSuma,
                        $div
                    );
                }
            }

            //Paso 3: Multiplicación de cada divCaudalSuma por el resultado del parametro                                    
            for ($i = 0; $i < $solicitudParametroGrasasLength; $i++) {
                $mult = $divCaudalSuma[$i] * $solicitudParametroGrasas[$i]->Resultado;

                array_push(
                    $multResDivCaudal,
                    $mult
                );
            }

            //Paso 4: Sumatoria de multResDivCaudal
            foreach ($multResDivCaudal as $item) {
                $sumaCaudalesFinal += $item;
            }

            //Verifica si el resultado es menor al límite de cuantificación del parámetro
            $limiteGrasas = DB::table('parametros')->where('Id_parametro', $solicitudParametroGrasas[0]->Id_parametro)->first();

            if ($sumaCaudalesFinal < $limiteGrasas->Limite) {
                $sumaCaudalesFinal = "< " . $limiteGrasas->Limite;
            }
        }

        //echo  implode(" , ", $multResDivCaudal);        

        //**************************************FIN DE CALCULO DE CONCENTRACION CUANTIFICADA DE GRASAS ******************************

        //************************************** CALCULO DE COLIFORMES FECALES ******************************************************
        //Consulta si existe el parámetro de Coliformes Fecales en la solicitud
        $solicitudParametroColiformesFe = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Id_parametro', 13)->get();
        $solicitudParametroColiformesFeLength = $solicitudParametroGrasas->count();

        if (!is_null($solicitudParametroColiformesFe)) { //Encontró coliformes fecales
            $productoColi = 1;

            //Paso 1: Multiplicación de todos los resultados de coliformes fecales
            foreach ($solicitudParametroColiformesFe as $item) {
                $productoColi *= $item->Resultado;
            }

            //Paso 2: Raíz a la N cantidad de resultados de coliformes
            $resColi = pow($productoColi, 1 / $solicitudParametroColiformesFeLength);

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

        $limitesC = array();

        //Recupera los límites de cuantificación de los parámetros        
        foreach ($solicitudParametros as $item) {
            $limiteC = DB::table('parametros')->where('Id_parametro', $item->Id_parametro)->first();

            if ($item->Resultado < $limiteC->Limite) {
                $limC = "< " . $limiteC->Limite;

                array_push($limitesC, $limC);
            } else {  //Si es mayor el resultado que el límite de cuantificación
                $limC = $item->Resultado;

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

        //BODY;Por añadir validaciones, mismas que se irán implementando cuando haya una tabla en la BD para los informes
        $htmlInforme = view('exports.informes.conComparacion.bodyComparacionInforme',  compact('solicitudParametros', 'solicitudParametrosLength', 'limitesC', 'sumaCaudalesFinal', 'resColi'));

        //HEADER-FOOTER******************************************************************************************************************                 
        $htmlHeader = view('exports.informes.conComparacion.headerComparacionInforme', compact('solicitud', 'direccion', 'cliente', 'puntoMuestreo', 'numOrden', 'horaMuestreo', 'modelProcesoAnalisis'));
        $htmlFooter = view('exports.informes.conComparacion.footerComparacionInforme', compact('solicitud'/* 'usuario', 'firma' */));

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

        //Establece la marca de agua del documento PDF
        $mpdf->SetWatermarkImage(
            asset('/public/storage/MembreteVertical.png'),
            1,
            array(215, 280),
            array(0, 0),
        );

        $mpdf->showWatermarkImage = true;

        //Recupera el nombre de usuario y firma
        /* $usuario = DB::table('users')->where('id', auth()->user()->id)->first();
        $firma = $usuario->firma; */

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
        $folio = explode("-", $solicitud->Folio_servicio);
        $parte1 = strval($folio[0]);
        $parte2 = strval($folio[1]);

        $numOrden = Solicitud::where('Folio_servicio', $parte1 . "-" . $parte2)->first();
        //$cotizacion = Cotizacion::where('Folio_servicio', $folio[0])->first();
        //$cotizacion = Cotizacion::where('Folio_servicio', 'LIKE', "%{$solicitud->Folio_servicio}%")->get();
        $cliente = Clientes::where('Id_cliente', $solicitud->Id_cliente)->first();
        $solicitudPunto = SolicitudPuntos::where('Id_solicitud', $solicitud->Id_solicitud)->first();
        $puntoMuestreo = DB::table('puntos_muestreo')->where('Id_punto', $solicitudPunto->Id_punto)->first();

        /* $solicitudParametros = DB::table('ViewSolicitudParametros')->where('Id_solicitud', $solicitud->Id_solicitud)->get();
        $solicitudParametrosLength = $solicitudParametros->count(); */

        //Recupera los parámetros
        $solicitudParametros = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Num_muestra', 1)->orderBy('Parametro', 'ASC')->get();
        $solicitudParametrosLength = $solicitudParametros->count();

        $limitesC = array();

        //Recupera los límites de cuantificación de los parámetros        
        foreach ($solicitudParametros as $item) {
            $limiteC = DB::table('parametros')->where('Id_parametro', $item->Id_parametro)->first();

            if ($item->Resultado < $limiteC->Limite) {
                $limC = "< " . $limiteC->Limite;

                array_push($limitesC, $limC);
            } else {  //Si es mayor el resultado que el límite de cuantificación
                $limC = $item->Resultado;

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

        //BODY;Por añadir validaciones, mismas que se irán implementando cuando haya una tabla en la BD para los informes
        $htmlInforme = view('exports.informes.sinComparacion.bodyInforme',  compact('solicitudParametros', 'solicitudParametrosLength', 'limitesC', 'tempCompuesta'));

        //HEADER-FOOTER******************************************************************************************************************                 
        $htmlHeader = view('exports.informes.sinComparacion.headerInforme', compact('solicitud', 'direccion', 'cliente', 'puntoMuestreo', 'numOrden', 'modelProcesoAnalisis', 'horaMuestreo'));
        $htmlFooter = view('exports.informes.sinComparacion.footerInforme', compact('solicitud'/* 'usuario', 'firma' */));

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

        //Establece la marca de agua del documento PDF
        $mpdf->SetWatermarkImage(
            asset('/public/storage/MembreteVertical.png'),
            1,
            array(215, 280),
            array(0, 0),
        );

        $mpdf->showWatermarkImage = true;

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
        $puntoMuestreo = DB::table('puntos_muestreo')->where('Id_punto', $solicitudPunto->Id_punto)->first();

        /* $solicitudParametros = DB::table('ViewSolicitudParametros')->where('Id_solicitud', $solicitud->Id_solicitud)->get();
        $solicitudParametrosLength = $solicitudParametros->count(); */

        //Recupera los parámetros
        $solicitudParametros = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Num_muestra', 1)->orderBy('Parametro', 'ASC')->get();
        $solicitudParametrosLength = $solicitudParametros->count();

        $limitesC = array();

        //Recupera los límites de cuantificación de los parámetros        
        foreach ($solicitudParametros as $item) {
            $limiteC = DB::table('parametros')->where('Id_parametro', $item->Id_parametro)->first();

            if ($item->Resultado < $limiteC->Limite) {
                $limC = "< " . $limiteC->Limite;

                array_push($limitesC, $limC);
            } else {  //Si es mayor el resultado que el límite de cuantificación
                $limC = $item->Resultado;

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

        //BODY;Por añadir validaciones, mismas que se irán implementando cuando haya una tabla en la BD para los informes
        $htmlInforme = view('exports.informes.conComparacion.bodyComparacionInforme',  compact('solicitudParametros', 'solicitudParametrosLength', 'limitesC'));

        //HEADER-FOOTER******************************************************************************************************************                 
        $htmlHeader = view('exports.informes.conComparacion.headerComparacionInforme', compact('solicitud', 'direccion', 'cliente', 'puntoMuestreo', 'numOrden', 'horaMuestreo', 'modelProcesoAnalisis'));
        $htmlFooter = view('exports.informes.conComparacion.footerComparacionInforme', compact('solicitud'/* 'usuario', 'firma' */));

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

        //Recupera el nombre de usuario y firma
        $usuario = DB::table('users')->where('id', auth()->user()->id)->first();
        $firma = $usuario->firma;

        //Formatea la fecha; Por adaptar para el informe sin comparacion
        $fechaAnalisis = DB::table('ViewLoteAnalisis')->where('Id_lote', 0)->first();
        if (!is_null($fechaAnalisis)) {
            $fechaConFormato = date("d/m/Y", strtotime($fechaAnalisis->Fecha));
        }

        $solicitud = Solicitud::where('Id_solicitud', $idSol)->first();
        $norma = Norma::where('Id_norma', $solicitud->Id_norma)->first();
        $fechaEmision = \Carbon\Carbon::now();
        $direccion = DireccionReporte::where('Id_direccion', $solicitud->Id_direccion)->first();

        $folio = explode("-", $solicitud->Folio_servicio);
        $parte1 = strval($folio[0]);
        $parte2 = strval($folio[1]);

        $numOrden = Solicitud::where('Folio_servicio', $parte1 . "-" . $parte2)->first();

        //$cotizacion = Cotizacion::where('Folio_servicio', $folio[0])->first();
        //$cotizacion = Cotizacion::where('Folio_servicio', 'LIKE', "%{$solicitud->Folio_servicio}%")->get();
        $cliente = Clientes::where('Id_cliente', $solicitud->Id_cliente)->first();
        $solicitudPunto = SolicitudPuntos::where('Id_solicitud', $solicitud->Id_solicitud)->first();
        $puntoMuestreo = DB::table('puntos_muestreo')->where('Id_punto', $solicitudPunto->Id_punto)->first();

        //Encuentra el folio secundario para la comparación a través de si el cliente y titulo de consecion es el mismo que el de la solicitud primaria
        $comparacion = DB::table('ViewSolicitud')->where('Folio_servicio', 'LIKE', "%{$numOrden->Folio_servicio}%")->get();
        $data = array();
        $comparacionEncontrada = null;

        foreach ($comparacion as $item) {
            if (($item->Id_cliente == $solicitud->Id_cliente) && ($item->Folio_servicio !== $solicitud->Folio_servicio)) {
                $solicitudComparacionPunto = SolicitudPuntos::where('Id_solicitud', $item->Id_solicitud)->first();
                $puntoMuestreoComparacion = DB::table('puntos_muestreo')->where('Id_punto', $solicitudComparacionPunto->Id_punto)->first();

                //Si ambos titulos de consecion y anexos son los mismos entonces se almacena en la var.comparación encontrada la solicitud correspondiente
                if (($puntoMuestreo->Titulo_consecion == $puntoMuestreoComparacion->Titulo_consecion) && ($puntoMuestreo->Anexo == $puntoMuestreoComparacion->Anexo)) {
                    $comparacionEncontrada = $item;

                    //Obtiene el número de orden para el informe; Ej si la comparación encontrada es 60-1/22-2 estas instrucciones devuelven 60-1/22
                    $folioComparacion = explode("-", $item->Folio_servicio);
                    $parte1C = strval($folio[0]);
                    $parte2C = strval($folio[1]);
                    $folioC = $parte1 . "-" . $parte2;

                    array_push($data, $comparacionEncontrada, $folioC);
                    break;
                }
            }
        }

        //Recupera los resultados de los parámetros de la primera muestra
        $solicitudParametros = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Num_muestra', 1)->get();
        $solicitudParametrosLength = $solicitudParametros->count();
        $limiteMostrar = array();
        $limitesC = array();

        //Recupera los límites de cuantificación de los parámetros
        foreach ($solicitudParametros as $item) {
            $limiteC = DB::table('parametros')->where('Id_parametro', $item->Id_parametro)->first();

            if ($item->Resultado < $limiteC->Limite) {
                $limC = "< " . $limiteC->Limite;

                array_push($limiteMostrar, true);
                array_push($limitesC, $limC);
            } else {  //Si es mayor el resultado que el límite de cuantificación
                $limC = $item->Resultado;

                array_push($limiteMostrar, false);
                array_push($limitesC, $limC);
            }
        }

        //Recupera la fecha de recepción del primer y segundo folio
        $modelProcesoAnalisis1 = ProcesoAnalisis::where('Id_solicitud', $solicitud->Id_solicitud)->first();
        $modelProcesoAnalisis2 = null;

        //Recupera el gasto LPS del folio 1        
        $gastosModel = GastoMuestra::where('Id_solicitud', $idSol)->get();
        $gastosModelLength = $gastosModel->count();
        $gastoSum1 = 0;

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
        $gastoLPS2 = 0;

        $limiteMostrar2 = array();
        $limites2C = array();
        
        //Recupera los resultados de los parámetros de la segunda muestra
        if (!is_null($comparacionEncontrada)) {
            $modelProcesoAnalisis2 = ProcesoAnalisis::where('Id_solicitud', $comparacionEncontrada->Id_solicitud)->first();

            $gastosModel2 = GastoMuestra::where('Id_solicitud', $comparacionEncontrada->Id_solicitud)->get();
            $gastosModelLength2 = $gastosModel2->count();
            $gastoSum2 = 0;

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

            $solicitudParametros2 = DB::table('ViewCodigoParametro')->where('Id_solicitud', $comparacionEncontrada->Id_solicitud)->where('Num_muestra', 1)->get();
            $solicitudParametros2Length = $solicitudParametros2->count();

            //Recupera los límites de cuantificación de los parámetros
            foreach ($solicitudParametros2 as $item) {
                $limite2C = DB::table('parametros')->where('Id_parametro', $item->Id_parametro)->first();

                if ($item->Resultado < $limite2C->Limite) {
                    $lim2C = "< " . $limite2C->Limite;

                    array_push($limiteMostrar2, true);
                    array_push($limites2C, $lim2C);
                } else {  //Si es mayor el resultado que el límite de cuantificación
                    $lim2C = $item->Resultado;

                    array_push($limiteMostrar2, false);
                    array_push($limites2C, $lim2C);
                }
            }
        }

        //BODY;Por añadir validaciones, mismas que se irán implementando cuando haya una tabla en la BD para los informes
        $htmlInforme = view('exports.informes.sinComparacion.bodyInformeMensual',  compact('solicitudParametros', 'solicitudParametrosLength', 'limitesC', 'limites2C', 'limiteMostrar', 'limiteMostrar2'));

        //HEADER-FOOTER******************************************************************************************************************
        $htmlHeader = view('exports.informes.sinComparacion.headerInformeMensual', compact('solicitud', 'direccion', 'cliente', 'puntoMuestreo', 'numOrden', 'norma', 'comparacionEncontrada', 'data', 'modelProcesoAnalisis1', 'modelProcesoAnalisis2', 'fechaEmision', 'gastoLPS1', 'gastoLPS2'));
        $htmlFooter = view('exports.informes.sinComparacion.footerInformeMensual', compact('solicitud'/* 'usuario', 'firma' */));

        $mpdf->setHeader("{PAGENO} / {nbpg} <br><br>" . $htmlHeader);
        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
        $mpdf->WriteHTML($htmlInforme);

        $mpdf->CSSselectMedia = 'mpdf';
        $mpdf->Output('Informe de Resultados Sin Comparacion.pdf', 'I');
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
            'margin_bottom' => 80,
            'defaultheaderfontstyle' => ['normal'],
            'defaultheaderline' => '0'
        ]);

        //Recupera el nombre de usuario y firma
        $usuario = DB::table('users')->where('id', auth()->user()->id)->first();
        $firma = $usuario->firma;

        //Formatea la fecha; Por adaptar para el informe sin comparacion
        $fechaAnalisis = DB::table('ViewLoteAnalisis')->where('Id_lote', 0)->first();
        if (!is_null($fechaAnalisis)) {
            $fechaConFormato = date("d/m/Y", strtotime($fechaAnalisis->Fecha));
        }

        $solicitud = Solicitud::where('Id_solicitud', $idSol)->first();
        $norma = Norma::where('Id_norma', $solicitud->Id_norma)->first();
        $fechaEmision = \Carbon\Carbon::now();
        $direccion = DireccionReporte::where('Id_direccion', $solicitud->Id_direccion)->first();

        $folio = explode("-", $solicitud->Folio_servicio);
        $parte1 = strval($folio[0]);
        $parte2 = strval($folio[1]);

        $numOrden = Solicitud::where('Folio_servicio', $parte1 . "-" . $parte2)->first();

        //$cotizacion = Cotizacion::where('Folio_servicio', $folio[0])->first();
        //$cotizacion = Cotizacion::where('Folio_servicio', 'LIKE', "%{$solicitud->Folio_servicio}%")->get();
        $cliente = Clientes::where('Id_cliente', $solicitud->Id_cliente)->first();
        $solicitudPunto = SolicitudPuntos::where('Id_solicitud', $solicitud->Id_solicitud)->first();
        $puntoMuestreo = DB::table('puntos_muestreo')->where('Id_punto', $solicitudPunto->Id_punto)->first();

        //Encuentra el folio secundario para la comparación a través de si el cliente y titulo de consecion es el mismo que el de la solicitud primaria
        $comparacion = DB::table('ViewSolicitud')->where('Folio_servicio', 'LIKE', "%{$numOrden->Folio_servicio}%")->get();
        $data = array();
        $comparacionEncontrada = null;

        foreach ($comparacion as $item) {
            if (($item->Id_cliente == $solicitud->Id_cliente) && ($item->Folio_servicio !== $solicitud->Folio_servicio)) {
                $solicitudComparacionPunto = SolicitudPuntos::where('Id_solicitud', $item->Id_solicitud)->first();
                $puntoMuestreoComparacion = DB::table('puntos_muestreo')->where('Id_punto', $solicitudComparacionPunto->Id_punto)->first();

                //Si ambos titulos de consecion y anexos son los mismos entonces se almacena en la var.comparación encontrada la solicitud correspondiente
                if (($puntoMuestreo->Titulo_consecion == $puntoMuestreoComparacion->Titulo_consecion) && ($puntoMuestreo->Anexo == $puntoMuestreoComparacion->Anexo)) {
                    $comparacionEncontrada = $item;

                    //Obtiene el número de orden para el informe; Ej si la comparación encontrada es 60-1/22-2 estas instrucciones devuelven 60-1/22
                    $folioComparacion = explode("-", $item->Folio_servicio);
                    $parte1C = strval($folio[0]);
                    $parte2C = strval($folio[1]);
                    $folioC = $parte1 . "-" . $parte2;

                    array_push($data, $comparacionEncontrada, $folioC);
                    break;
                }
            }
        }

        //Recupera los resultados de los parámetros de la primera muestra
        $solicitudParametros = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Num_muestra', 1)->get();
        $solicitudParametrosLength = $solicitudParametros->count();
        $limiteMostrar = array();
        $limitesC = array();

        //Recupera los límites de cuantificación de los parámetros        
        foreach ($solicitudParametros as $item) {
            $limiteC = DB::table('parametros')->where('Id_parametro', $item->Id_parametro)->first();

            if ($item->Resultado < $limiteC->Limite) {
                $limC = "< " . $limiteC->Limite;

                array_push($limiteMostrar, true);
                array_push($limitesC, $limC);
            } else {  //Si es mayor el resultado que el límite de cuantificación
                $limC = $item->Resultado;

                array_push($limiteMostrar, false);
                array_push($limitesC, $limC);
            }
        }

        //Recupera la fecha de recepción del primer y segundo folio
        $modelProcesoAnalisis1 = ProcesoAnalisis::where('Id_solicitud', $solicitud->Id_solicitud)->first();
        $modelProcesoAnalisis2 = null;

        //Recupera el gasto LPS del folio 1        
        $gastosModel = GastoMuestra::where('Id_solicitud', $idSol)->get();
        $gastosModelLength = $gastosModel->count();
        $gastoSum1 = 0;

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
        $gastoLPS2 = 0;

        $limiteMostrar2 = array();
        $limites2C = array();
        //Recupera los resultados de los parámetros de la segunda muestra
        if (!is_null($comparacionEncontrada)) {
            $modelProcesoAnalisis2 = ProcesoAnalisis::where('Id_solicitud', $comparacionEncontrada->Id_solicitud)->first();

            $gastosModel2 = GastoMuestra::where('Id_solicitud', $comparacionEncontrada->Id_solicitud)->get();
            $gastosModelLength2 = $gastosModel2->count();
            $gastoSum2 = 0;

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

            $solicitudParametros2 = DB::table('ViewCodigoParametro')->where('Id_solicitud', $comparacionEncontrada->Id_solicitud)->where('Num_muestra', 1)->get();
            $solicitudParametros2Length = $solicitudParametros2->count();

            //Recupera los límites de cuantificación de los parámetros        
            foreach ($solicitudParametros2 as $item) {
                $limite2C = DB::table('parametros')->where('Id_parametro', $item->Id_parametro)->first();

                if ($item->Resultado < $limite2C->Limite) {
                    $lim2C = "< " . $limite2C->Limite;

                    array_push($limiteMostrar2, true);
                    array_push($limites2C, $lim2C);
                } else {  //Si es mayor el resultado que el límite de cuantificación
                    $lim2C = $item->Resultado;

                    array_push($limiteMostrar2, false);
                    array_push($limites2C, $lim2C);
                }
            }
        }

        //BODY;Por añadir validaciones, mismas que se irán implementando cuando haya una tabla en la BD para los informes
        $htmlInforme = view('exports.informes.conComparacion.bodyInformeMensual',  compact('solicitudParametros', 'solicitudParametrosLength', 'limitesC', 'limites2C', 'limiteMostrar', 'limiteMostrar2'));

        //HEADER-FOOTER******************************************************************************************************************                 
        $htmlHeader = view('exports.informes.conComparacion.headerInformeMensual', compact('solicitud', 'direccion', 'cliente', 'puntoMuestreo', 'numOrden', 'norma', 'fechaEmision', 'comparacionEncontrada', 'data', 'modelProcesoAnalisis1', 'modelProcesoAnalisis2', 'gastoLPS1', 'gastoLPS2'));
        $htmlFooter = view('exports.informes.conComparacion.footerInformeMensual', compact('solicitud', 'comparacionEncontrada' /* 'usuario', 'firma' */));

        $mpdf->setHeader("{PAGENO} / {nbpg} <br><br>" . $htmlHeader);
        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
        $mpdf->WriteHTML($htmlInforme);

        $mpdf->CSSselectMedia = 'mpdf';
        $mpdf->Output('Informe de Resultados Con Comparacion.pdf', 'I');
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

        //Establece la marca de agua del documento PDF
        $mpdf->SetWatermarkImage(
            asset('/public/storage/HojaMembretadaHorizontal.png'),
            1,
            array(215, 280),
            array(0, 0),
        );

        $mpdf->showWatermarkImage = true;

        //Recupera el nombre de usuario y firma
        //$usuario = DB::table('users')->where('id', auth()->user()->id)->first();
        //$firma = $usuario->firma;

        //Formatea la fecha; Por adaptar para el informe sin comparacion
        $fechaAnalisis = DB::table('ViewLoteAnalisis')->where('Id_lote', 0)->first();
        if (!is_null($fechaAnalisis)) {
            $fechaConFormato = date("d/m/Y", strtotime($fechaAnalisis->Fecha));
        }

        $solicitud = Solicitud::where('Id_solicitud', $idSol)->first();
        $norma = Norma::where('Id_norma', $solicitud->Id_norma)->first();
        $fechaEmision = \Carbon\Carbon::now();
        $direccion = DireccionReporte::where('Id_direccion', $solicitud->Id_direccion)->first();

        $folio = explode("-", $solicitud->Folio_servicio);
        $parte1 = strval($folio[0]);
        $parte2 = strval($folio[1]);

        $numOrden = Solicitud::where('Folio_servicio', $parte1 . "-" . $parte2)->first();

        //$cotizacion = Cotizacion::where('Folio_servicio', $folio[0])->first();
        //$cotizacion = Cotizacion::where('Folio_servicio', 'LIKE', "%{$solicitud->Folio_servicio}%")->get();
        $cliente = Clientes::where('Id_cliente', $solicitud->Id_cliente)->first();
        $solicitudPunto = SolicitudPuntos::where('Id_solicitud', $solicitud->Id_solicitud)->first();
        $puntoMuestreo = DB::table('puntos_muestreo')->where('Id_punto', $solicitudPunto->Id_punto)->first();

        //Encuentra el folio secundario para la comparación a través de si el cliente y titulo de consecion es el mismo que el de la solicitud primaria
        $comparacion = DB::table('ViewSolicitud')->where('Folio_servicio', 'LIKE', "%{$numOrden->Folio_servicio}%")->get();
        $data = array();
        $comparacionEncontrada = null;

        foreach ($comparacion as $item) {
            if (($item->Id_cliente == $solicitud->Id_cliente) && ($item->Folio_servicio !== $solicitud->Folio_servicio)) {
                $solicitudComparacionPunto = SolicitudPuntos::where('Id_solicitud', $item->Id_solicitud)->first();
                $puntoMuestreoComparacion = DB::table('puntos_muestreo')->where('Id_punto', $solicitudComparacionPunto->Id_punto)->first();

                //Si ambos titulos de consecion y anexos son los mismos entonces se almacena en la var.comparación encontrada la solicitud correspondiente
                if (($puntoMuestreo->Titulo_consecion == $puntoMuestreoComparacion->Titulo_consecion) && ($puntoMuestreo->Anexo == $puntoMuestreoComparacion->Anexo)) {
                    $comparacionEncontrada = $item;

                    //Obtiene el número de orden para el informe; Ej si la comparación encontrada es 60-1/22-2 estas instrucciones devuelven 60-1/22
                    $folioComparacion = explode("-", $item->Folio_servicio);
                    $parte1C = strval($folio[0]);
                    $parte2C = strval($folio[1]);
                    $folioC = $parte1 . "-" . $parte2;

                    array_push($data, $comparacionEncontrada, $folioC);
                    break;
                }
            }
        }

        //Recupera los resultados de los parámetros de la primera muestra
        $solicitudParametros = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Num_muestra', 1)->get();
        $solicitudParametrosLength = $solicitudParametros->count();
        $limiteMostrar = array();
        $limitesC = array();

        //Recupera los límites de cuantificación de los parámetros
        foreach ($solicitudParametros as $item) {
            $limiteC = DB::table('parametros')->where('Id_parametro', $item->Id_parametro)->first();

            if ($item->Resultado < $limiteC->Limite) {
                $limC = "< " . $limiteC->Limite;

                array_push($limiteMostrar, true);
                array_push($limitesC, $limC);
            } else {  //Si es mayor el resultado que el límite de cuantificación
                $limC = $item->Resultado;

                array_push($limiteMostrar, false);
                array_push($limitesC, $limC);
            }
        }

        //Recupera la fecha de recepción del primer y segundo folio
        $modelProcesoAnalisis1 = ProcesoAnalisis::where('Id_solicitud', $solicitud->Id_solicitud)->first();
        $modelProcesoAnalisis2 = null;

        //Recupera el gasto LPS del folio 1        
        $gastosModel = GastoMuestra::where('Id_solicitud', $idSol)->get();
        $gastosModelLength = $gastosModel->count();
        $gastoSum1 = 0;

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
        $gastoLPS2 = 0;

        $limiteMostrar2 = array();
        $limites2C = array();
        //Recupera los resultados de los parámetros de la segunda muestra
        if (!is_null($comparacionEncontrada)) {
            $modelProcesoAnalisis2 = ProcesoAnalisis::where('Id_solicitud', $comparacionEncontrada->Id_solicitud)->first();

            $gastosModel2 = GastoMuestra::where('Id_solicitud', $comparacionEncontrada->Id_solicitud)->get();
            $gastosModelLength2 = $gastosModel2->count();
            $gastoSum2 = 0;

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

            $solicitudParametros2 = DB::table('ViewCodigoParametro')->where('Id_solicitud', $comparacionEncontrada->Id_solicitud)->where('Num_muestra', 1)->get();
            $solicitudParametros2Length = $solicitudParametros2->count();

            //Recupera los límites de cuantificación de los parámetros
            foreach ($solicitudParametros2 as $item) {
                $limite2C = DB::table('parametros')->where('Id_parametro', $item->Id_parametro)->first();

                if ($item->Resultado < $limite2C->Limite) {
                    $lim2C = "< " . $limite2C->Limite;

                    array_push($limiteMostrar2, true);
                    array_push($limites2C, $lim2C);
                } else {  //Si es mayor el resultado que el límite de cuantificación
                    $lim2C = $item->Resultado;

                    array_push($limiteMostrar2, false);
                    array_push($limites2C, $lim2C);
                }
            }
        }

        //BODY;Por añadir validaciones, mismas que se irán implementando cuando haya una tabla en la BD para los informes
        $htmlInforme = view('exports.informes.sinComparacion.bodyInformeMensual',  compact('solicitudParametros', 'solicitudParametrosLength', 'limitesC', 'limites2C', 'limiteMostrar', 'limiteMostrar2'));

        //HEADER-FOOTER******************************************************************************************************************
        $htmlHeader = view('exports.informes.sinComparacion.headerInformeMensual', compact('solicitud', 'direccion', 'cliente', 'puntoMuestreo', 'numOrden', 'norma', 'comparacionEncontrada', 'data', 'modelProcesoAnalisis1', 'modelProcesoAnalisis2', 'fechaEmision', 'gastoLPS1', 'gastoLPS2'));
        $htmlFooter = view('exports.informes.sinComparacion.footerInformeMensual', compact('solicitud'/* 'usuario', 'firma' */));

        $mpdf->setHeader("{PAGENO} / {nbpg} <br><br>" . $htmlHeader);
        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
        $mpdf->WriteHTML($htmlInforme);

        $mpdf->CSSselectMedia = 'mpdf';
        $mpdf->Output('Informe Mensual de Resultados Sin Comparacion.pdf', 'D');
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
            'margin_bottom' => 80,
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

        //Recupera el nombre de usuario y firma
        /* $usuario = DB::table('users')->where('id', auth()->user()->id)->first();
        $firma = $usuario->firma; */

        //Formatea la fecha; Por adaptar para el informe sin comparacion
        $fechaAnalisis = DB::table('ViewLoteAnalisis')->where('Id_lote', 0)->first();
        if (!is_null($fechaAnalisis)) {
            $fechaConFormato = date("d/m/Y", strtotime($fechaAnalisis->Fecha));
        }

        $solicitud = Solicitud::where('Id_solicitud', $idSol)->first();
        $norma = Norma::where('Id_norma', $solicitud->Id_norma)->first();
        $fechaEmision = \Carbon\Carbon::now();
        $direccion = DireccionReporte::where('Id_direccion', $solicitud->Id_direccion)->first();

        $folio = explode("-", $solicitud->Folio_servicio);
        $parte1 = strval($folio[0]);
        $parte2 = strval($folio[1]);

        $numOrden = Solicitud::where('Folio_servicio', $parte1 . "-" . $parte2)->first();

        //$cotizacion = Cotizacion::where('Folio_servicio', $folio[0])->first();
        //$cotizacion = Cotizacion::where('Folio_servicio', 'LIKE', "%{$solicitud->Folio_servicio}%")->get();
        $cliente = Clientes::where('Id_cliente', $solicitud->Id_cliente)->first();
        $solicitudPunto = SolicitudPuntos::where('Id_solicitud', $solicitud->Id_solicitud)->first();
        $puntoMuestreo = DB::table('puntos_muestreo')->where('Id_punto', $solicitudPunto->Id_punto)->first();

        //Encuentra el folio secundario para la comparación a través de si el cliente y titulo de consecion es el mismo que el de la solicitud primaria
        $comparacion = DB::table('ViewSolicitud')->where('Folio_servicio', 'LIKE', "%{$numOrden->Folio_servicio}%")->get();
        $data = array();
        $comparacionEncontrada = null;

        foreach ($comparacion as $item) {
            if (($item->Id_cliente == $solicitud->Id_cliente) && ($item->Folio_servicio !== $solicitud->Folio_servicio)) {
                $solicitudComparacionPunto = SolicitudPuntos::where('Id_solicitud', $item->Id_solicitud)->first();
                $puntoMuestreoComparacion = DB::table('puntos_muestreo')->where('Id_punto', $solicitudComparacionPunto->Id_punto)->first();

                //Si ambos titulos de consecion y anexos son los mismos entonces se almacena en la var.comparación encontrada la solicitud correspondiente
                if (($puntoMuestreo->Titulo_consecion == $puntoMuestreoComparacion->Titulo_consecion) && ($puntoMuestreo->Anexo == $puntoMuestreoComparacion->Anexo)) {
                    $comparacionEncontrada = $item;

                    //Obtiene el número de orden para el informe; Ej si la comparación encontrada es 60-1/22-2 estas instrucciones devuelven 60-1/22
                    $folioComparacion = explode("-", $item->Folio_servicio);
                    $parte1C = strval($folio[0]);
                    $parte2C = strval($folio[1]);
                    $folioC = $parte1 . "-" . $parte2;

                    array_push($data, $comparacionEncontrada, $folioC);
                    break;
                }
            }
        }

        //Recupera los resultados de los parámetros de la primera muestra
        $solicitudParametros = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Num_muestra', 1)->get();
        $solicitudParametrosLength = $solicitudParametros->count();
        $limiteMostrar = array();
        $limitesC = array();

        //Recupera los límites de cuantificación de los parámetros        
        foreach ($solicitudParametros as $item) {
            $limiteC = DB::table('parametros')->where('Id_parametro', $item->Id_parametro)->first();

            if ($item->Resultado < $limiteC->Limite) {
                $limC = "< " . $limiteC->Limite;

                array_push($limiteMostrar, true);
                array_push($limitesC, $limC);
            } else {  //Si es mayor el resultado que el límite de cuantificación
                $limC = $item->Resultado;

                array_push($limiteMostrar, false);
                array_push($limitesC, $limC);
            }
        }

        //Recupera la fecha de recepción del primer y segundo folio
        $modelProcesoAnalisis1 = ProcesoAnalisis::where('Id_solicitud', $solicitud->Id_solicitud)->first();
        $modelProcesoAnalisis2 = null;

        //Recupera el gasto LPS del folio 1        
        $gastosModel = GastoMuestra::where('Id_solicitud', $idSol)->get();
        $gastosModelLength = $gastosModel->count();
        $gastoSum1 = 0;

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
        $gastoLPS2 = 0;

        $limiteMostrar2 = array();
        $limites2C = array();
        //Recupera los resultados de los parámetros de la segunda muestra
        if (!is_null($comparacionEncontrada)) {
            $modelProcesoAnalisis2 = ProcesoAnalisis::where('Id_solicitud', $comparacionEncontrada->Id_solicitud)->first();

            $gastosModel2 = GastoMuestra::where('Id_solicitud', $comparacionEncontrada->Id_solicitud)->get();
            $gastosModelLength2 = $gastosModel2->count();
            $gastoSum2 = 0;

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

            $solicitudParametros2 = DB::table('ViewCodigoParametro')->where('Id_solicitud', $comparacionEncontrada->Id_solicitud)->where('Num_muestra', 1)->get();
            $solicitudParametros2Length = $solicitudParametros2->count();

            //Recupera los límites de cuantificación de los parámetros        
            foreach ($solicitudParametros2 as $item) {
                $limite2C = DB::table('parametros')->where('Id_parametro', $item->Id_parametro)->first();

                if ($item->Resultado < $limite2C->Limite) {
                    $lim2C = "< " . $limite2C->Limite;

                    array_push($limiteMostrar2, true);
                    array_push($limites2C, $lim2C);
                } else {  //Si es mayor el resultado que el límite de cuantificación
                    $lim2C = $item->Resultado;

                    array_push($limiteMostrar2, false);
                    array_push($limites2C, $lim2C);
                }
            }
        }

        //BODY;Por añadir validaciones, mismas que se irán implementando cuando haya una tabla en la BD para los informes
        $htmlInforme = view('exports.informes.conComparacion.bodyInformeMensual',  compact('solicitudParametros', 'solicitudParametrosLength', 'limitesC', 'limites2C', 'limiteMostrar', 'limiteMostrar2'));

        //HEADER-FOOTER******************************************************************************************************************                 
        $htmlHeader = view('exports.informes.conComparacion.headerInformeMensual', compact('solicitud', 'direccion', 'cliente', 'puntoMuestreo', 'numOrden', 'norma', 'fechaEmision', 'comparacionEncontrada', 'data', 'modelProcesoAnalisis1', 'modlProcesoAnalisis2', 'gastoLPS1', 'gastoLPS2'));
        $htmlFooter = view('exports.informes.conComparacion.footerInformeMensual', compact('solicitud', 'comparacionEncontrada' /* 'usuario', 'firma' */));

        $mpdf->setHeader("{PAGENO} / {nbpg} <br><br>" . $htmlHeader);
        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
        $mpdf->WriteHTML($htmlInforme);

        $mpdf->CSSselectMedia = 'mpdf';
        $mpdf->Output('Informe Mensual de Resultados Con Comparacion.pdf', 'D');
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
            'margin_top' => 8,
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

        $gastosModel = GastoMuestra::where('Id_solicitud', $idSol)->get();
        $gastosModelLength = $gastosModel->count();

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

        //Recupera los límites de cuantificación de los parámetros        
        foreach ($paramResultado as $item) {
            $limiteC = DB::table('parametros')->where('Id_parametro', $item->Id_parametro)->first();

            if ($item->Resultado < $limiteC->Limite) {
                $limC = "< " . $limiteC->Limite;

                array_push($limitesC, $limC);
            } else {  //Si es mayor el resultado que el límite de cuantificación
                $limC = $item->Resultado;

                array_push($limitesC, $limC);
            }
        }

        $paquete = DB::table('ViewPlanPaquete')->where('Id_paquete', $model->Id_subnorma)->distinct()->get();
        $paqueteLength = $paquete->count();

        $responsables = array();

        foreach ($paquete as $item) {
            //INSTRUCCIÓN TEMPORAL
            if (!is_null($item)) {
                $responsableArea = AreaLab::where('Area', $item->Area)->first();
                $modelResponsable = DB::table('users')->where('id', $responsableArea->Id_responsable)->first();
                $responsable = $modelResponsable->name;

                array_push($responsables, $responsable);
            }
        }

        $fechaEmision = \Carbon\Carbon::now();
        $tipoMuestra = DB::table('tipo_descargas')->where('Id_tipo', $model->Id_muestreo)->first();
        $norma = Norma::where('Id_norma', $model->Id_norma)->first();

        $mpdf->showWatermarkImage = true;

        $htmlInforme = view('exports.campo.cadenaCustodiaInterna.bodyCadena', compact('model', 'paquete', 'paqueteLength', 'tipoMuestra', 'norma', 'fechaEmision', 'paramResultado', 'paramResultadoLength', 'limitesC', 'limiteGrasas', 'limiteColiformes', 'responsables', 'promedioPonderadoGA', 'mAritmeticaColi', 'gastoPromFinal'));

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
            'margin_top' => 8,
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

        $gastosModel = GastoMuestra::where('Id_solicitud', $idSol)->get();
        $gastosModelLength = $gastosModel->count();

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

        //Recupera los límites de cuantificación de los parámetros        
        foreach ($paramResultado as $item) {
            $limiteC = DB::table('parametros')->where('Id_parametro', $item->Id_parametro)->first();

            if ($item->Resultado < $limiteC->Limite) {
                $limC = "< " . $limiteC->Limite;

                array_push($limitesC, $limC);
            } else {  //Si es mayor el resultado que el límite de cuantificación
                $limC = $item->Resultado;

                array_push($limitesC, $limC);
            }
        }

        $paquete = DB::table('ViewPlanPaquete')->where('Id_paquete', $model->Id_subnorma)->distinct()->get();
        $paqueteLength = $paquete->count();

        $responsables = array();

        foreach ($paquete as $item) {
            //INSTRUCCIÓN TEMPORAL
            if (!is_null($item)) {
                $responsableArea = AreaLab::where('Area', $item->Area)->first();
                $modelResponsable = DB::table('users')->where('id', $responsableArea->Id_responsable)->first();
                $responsable = $modelResponsable->name;

                array_push($responsables, $responsable);
            }
        }

        $fechaEmision = \Carbon\Carbon::now();
        $tipoMuestra = DB::table('tipo_descargas')->where('Id_tipo', $model->Id_muestreo)->first();
        $norma = Norma::where('Id_norma', $model->Id_norma)->first();

        $mpdf->showWatermarkImage = true;

        $htmlInforme = view('exports.campo.cadenaCustodiaInterna.bodyCadena', compact('model', 'paquete', 'paqueteLength', 'tipoMuestra', 'norma', 'fechaEmision', 'paramResultado', 'paramResultadoLength', 'limitesC', 'limiteGrasas', 'limiteColiformes', 'responsables', 'promedioPonderadoGA', 'mAritmeticaColi', 'gastoPromFinal'));

        $mpdf->WriteHTML($htmlInforme);

        $mpdf->CSSselectMedia = 'mpdf';
        $mpdf->Output('Cadena de Custodia Interna.pdf', 'D');
    }
}
