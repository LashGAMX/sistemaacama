<?php

namespace App\Http\Controllers\Informes;

use App\Http\Controllers\Controller;
use App\Models\Clientes;
use App\Models\Cotizacion;
use App\Models\CotizacionPunto;
use App\Models\DireccionReporte;
use App\Models\Norma;
use App\Models\Parametro;
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
        return view('informes.informes',compact('tipoReporte','model'));
    }
    public function getPuntoMuestro(Request $request)
    {
        $model = DB::table('ViewPuntoMuestreoGen')->where('Id_solicitud',$request->id)->get();
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function getSolParametro(Request $request)
    {
        $model = DB::table('ViewSolicitudParametros')->where('Id_solicitud',$request->id)->get();
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }

    public function pdfSinComparacion($idSol){
        //Opciones del documento PDF
        $mpdf = new \Mpdf\Mpdf([
            'orientation' => 'P',
            'format' => 'letter',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 76,
            'margin_bottom' => 114,
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
        $usuario = DB::table('users')->where('id', auth()->user()->id)->first();
        $firma = $usuario->firma;

        //Formatea la fecha; Por adaptar para el informe sin comparacion
        $fechaAnalisis = DB::table('ViewLoteAnalisis')->where('Id_lote', 0)->first();
        if (!is_null($fechaAnalisis)) {
            $fechaConFormato = date("d/m/Y", strtotime($fechaAnalisis->Fecha));
        }

        $solicitud = Solicitud::where('Id_solicitud', $idSol)->first();
        $direccion = DireccionReporte::where('Id_direccion', $solicitud->Id_direccion)->first();
        $folio = explode("-", $solicitud->Folio_servicio);
        $parte1 = strval($folio[0]);
        $parte2 = strval($folio[1]);    

        $numOrden = Solicitud::where('Folio_servicio', $parte1."-".$parte2)->first();
        //$cotizacion = Cotizacion::where('Folio_servicio', $folio[0])->first();
        //$cotizacion = Cotizacion::where('Folio_servicio', 'LIKE', "%{$solicitud->Folio_servicio}%")->get();
        $cliente = Clientes::where('Id_cliente', $solicitud->Id_cliente)->first();
        $solicitudPunto = SolicitudPuntos::where('Id_solicitud', $solicitud->Id_solicitud)->first();
        $puntoMuestreo = DB::table('puntos_muestreo')->where('Id_punto', $solicitudPunto->Id_punto)->first();

        $solicitudParametros = DB::table('ViewSolicitudParametros')->where('Id_solicitud', $solicitud->Id_solicitud)->get();

        /* $resultados = array();
        foreach($solicitudParametros as $item){
            if($item->Parametro == 'Grasas y Aceites ++'){
                $parametro = DB::table('ViewLoteDetalleGA')->where('Folio_servicio', $item->Folio_servicio)->where('Id_control', 1)->first();
                
                array_push($resultados, $parametro->Resultado);
            }if($item->Parametro == 'HUEVOS DE HELMINTO' || $item->Parametro == 'Huevos de Helminto'){
                $parametro = DB::table('ViewLoteDetalleHH')->where('Folio_servicio', $item->Folio_servicio)->where('Id_control', 1)->first();

                array_push($resultados, $parametro->Resultado);
            }if($item->Parametro == 'CLORO RESIDUAL LIBRE'){
                $parametro = DB::table('ViewLoteDetalleCloro')->where('Folio_servicio', $item->Folio_servicio)->where('Id_control', 1)->first();

                array_push($resultados, $parametro->Resultado);
            }if($item->Parametro == 'Coliformes Fecales +' || $item->Parametro == 'COLIFORMES FECALES'){
                $parametro = DB::table('ViewLoteDetalleColiformes')->where('Folio_servicio', $item->Folio_servicio)->where('Id_control', 1)->first();

                array_push($resultados, $parametro->Resultado);
            }if($item->Parametro == 'DEMANDA BIOQUIMICA DE OXIGENO (DBO5)'){
                $parametro = DB::table('ViewLoteDetalleDbo')->where('Folio_servicio', $item->Folio_servicio)->where('Id_control', 1)->first();

                array_push($resultados, $parametro->Resultado);
            }if($item->Parametro == 'Demanda Química de Oxigeno  (DQO))'){
                $parametro = DB::table('ViewLoteDetalleDqo')->where('Folio_servicio', $item->Folio_servicio)->where('Id_control', 1)->first();

                array_push($resultados, $parametro->Resultado);
            }if($item->Parametro == 'Nitrógeno Total *'){
                $parametro = DB::table('ViewLoteDetalleNitrogeno')->where('Folio_servicio', $item->Folio_servicio)->where('Id_control', 1)->first();

                array_push($resultados, $parametro->Resultado);
            }if($item->Parametro == 'Cromo Total' || $item->Parametro == 'Cadmio (Cd)' || $item->Parametro == 'Plomo (Pb)' || $item->Parametro == 'Zinc (Zn)' || $item->Parametro == 'Cobre (Cu)'){

            }if($item->Area_analisis == 'Espectrofotometria'){
                $parametro = DB::table('ViewLoteDetalleEspectro')->where('Parametro', $item->Parametro)->where('Folio_servicio', $item->Folio_servicio)->where('Id_control', 1)->first();

                array_push($resultados, $parametro->Resultado);
            }if($item->Area_analisis == 'Solidos'){
                $parametro = DB::table('ViewLoteDetalleSolidos')->where('Parametro', $item->Parametro)->where('Folio_servicio', $item->Folio_servicio)->where('Id_control', 1)->first();

                array_push($resultados, $parametro->Resultado);
            }else{
                array_push($resultados, 0);
            }
        } */

        $solicitudParametrosLength = $solicitudParametros->count();

        //BODY;Por añadir validaciones, mismas que se irán implementando cuando haya una tabla en la BD para los informes
        $htmlInforme = view('exports.informes.sinComparacion.bodyInforme',  compact('solicitudParametros', 'solicitudParametrosLength'));

        //HEADER-FOOTER******************************************************************************************************************                 
        $htmlHeader = view('exports.informes.sinComparacion.headerInforme', compact('solicitud', 'direccion', 'cliente', 'puntoMuestreo', 'numOrden'));
        $htmlFooter = view('exports.informes.sinComparacion.footerInforme', compact('solicitud'/* 'usuario', 'firma' */));

        $mpdf->setHeader("{PAGENO} / {nbpg} <br><br>" . $htmlHeader);
        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
        $mpdf->WriteHTML($htmlInforme);

        $mpdf->CSSselectMedia = 'mpdf';
        $mpdf->Output();
        
        /* echo sizeof($solicitudParametros);
        echo sizeof($resultados);
        echo $solicitudParametrosLength; */
    }

    public function pdfComparacion($idSol){
        //Opciones del documento PDF
        $mpdf = new \Mpdf\Mpdf([
            'orientation' => 'L',
            'format' => 'letter',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 73,
            'margin_bottom' => 66,
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
        $usuario = DB::table('users')->where('id', auth()->user()->id)->first();
        $firma = $usuario->firma;

        //Formatea la fecha; Por adaptar para el informe sin comparacion
        $fechaAnalisis = DB::table('ViewLoteAnalisis')->where('Id_lote', 0)->first();
        if (!is_null($fechaAnalisis)) {
            $fechaConFormato = date("d/m/Y", strtotime($fechaAnalisis->Fecha));
        }

        $solicitud = Solicitud::where('Id_solicitud', $idSol)->first();
        $norma = Norma::where('Id_norma', $solicitud->Id_norma)->first();
        $direccion = DireccionReporte::where('Id_direccion', $solicitud->Id_direccion)->first();
        $folio = explode("-", $solicitud->Folio_servicio);
        $parte1 = strval($folio[0]);
        $parte2 = strval($folio[1]);    

        $numOrden = Solicitud::where('Folio_servicio', $parte1."-".$parte2)->first();
        //$cotizacion = Cotizacion::where('Folio_servicio', $folio[0])->first();
        //$cotizacion = Cotizacion::where('Folio_servicio', 'LIKE', "%{$solicitud->Folio_servicio}%")->get();
        $cliente = Clientes::where('Id_cliente', $solicitud->Id_cliente)->first();
        $solicitudPunto = SolicitudPuntos::where('Id_solicitud', $solicitud->Id_solicitud)->first();
        $puntoMuestreo = DB::table('puntos_muestreo')->where('Id_punto', $solicitudPunto->Id_punto)->first();

        $solicitudParametros = DB::table('ViewSolicitudParametros')->where('Id_solicitud', $solicitud->Id_solicitud)->get();

        /* $resultados = array();
        foreach($solicitudParametros as $item){
            if($item->Parametro == 'Grasas y Aceites ++'){
                $parametro = DB::table('ViewLoteDetalleGA')->where('Folio_servicio', $item->Folio_servicio)->where('Id_control', 1)->first();
                
                array_push($resultados, $parametro->Resultado);
            }if($item->Parametro == 'HUEVOS DE HELMINTO' || $item->Parametro == 'Huevos de Helminto'){
                $parametro = DB::table('ViewLoteDetalleHH')->where('Folio_servicio', $item->Folio_servicio)->where('Id_control', 1)->first();

                array_push($resultados, $parametro->Resultado);
            }if($item->Parametro == 'CLORO RESIDUAL LIBRE'){
                $parametro = DB::table('ViewLoteDetalleCloro')->where('Folio_servicio', $item->Folio_servicio)->where('Id_control', 1)->first();

                array_push($resultados, $parametro->Resultado);
            }if($item->Parametro == 'Coliformes Fecales +' || $item->Parametro == 'COLIFORMES FECALES'){
                $parametro = DB::table('ViewLoteDetalleColiformes')->where('Folio_servicio', $item->Folio_servicio)->where('Id_control', 1)->first();

                array_push($resultados, $parametro->Resultado);
            }if($item->Parametro == 'DEMANDA BIOQUIMICA DE OXIGENO (DBO5)'){
                $parametro = DB::table('ViewLoteDetalleDbo')->where('Folio_servicio', $item->Folio_servicio)->where('Id_control', 1)->first();

                array_push($resultados, $parametro->Resultado);
            }if($item->Parametro == 'Demanda Química de Oxigeno  (DQO))'){
                $parametro = DB::table('ViewLoteDetalleDqo')->where('Folio_servicio', $item->Folio_servicio)->where('Id_control', 1)->first();

                array_push($resultados, $parametro->Resultado);
            }if($item->Parametro == 'Nitrógeno Total *'){
                $parametro = DB::table('ViewLoteDetalleNitrogeno')->where('Folio_servicio', $item->Folio_servicio)->where('Id_control', 1)->first();

                array_push($resultados, $parametro->Resultado);
            }if($item->Parametro == 'Cromo Total' || $item->Parametro == 'Cadmio (Cd)' || $item->Parametro == 'Plomo (Pb)' || $item->Parametro == 'Zinc (Zn)' || $item->Parametro == 'Cobre (Cu)'){

            }if($item->Area_analisis == 'Espectrofotometria'){
                $parametro = DB::table('ViewLoteDetalleEspectro')->where('Parametro', $item->Parametro)->where('Folio_servicio', $item->Folio_servicio)->where('Id_control', 1)->first();

                array_push($resultados, $parametro->Resultado);
            }if($item->Area_analisis == 'Solidos'){
                $parametro = DB::table('ViewLoteDetalleSolidos')->where('Parametro', $item->Parametro)->where('Folio_servicio', $item->Folio_servicio)->where('Id_control', 1)->first();

                array_push($resultados, $parametro->Resultado);
            }else{
                array_push($resultados, 0);
            }
        } */

        $solicitudParametrosLength = $solicitudParametros->count();

        //BODY;Por añadir validaciones, mismas que se irán implementando cuando haya una tabla en la BD para los informes
        $htmlInforme = view('exports.informes.conComparacion.bodyInforme',  compact('solicitudParametros', 'solicitudParametrosLength'));

        //HEADER-FOOTER******************************************************************************************************************                 
        $htmlHeader = view('exports.informes.conComparacion.headerInforme', compact('solicitud', 'direccion', 'cliente', 'puntoMuestreo', 'numOrden', 'norma'));
        $htmlFooter = view('exports.informes.conComparacion.footerInforme', compact('solicitud'/* 'usuario', 'firma' */));

        $mpdf->setHeader("{PAGENO} / {nbpg} <br><br>" . $htmlHeader);
        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
        $mpdf->WriteHTML($htmlInforme);

        $mpdf->CSSselectMedia = 'mpdf';
        $mpdf->Output();
        
        /* echo sizeof($solicitudParametros);
        echo sizeof($resultados);
        echo $solicitudParametrosLength; */
    }
}
