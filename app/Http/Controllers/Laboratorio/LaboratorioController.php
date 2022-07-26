<?php

namespace App\Http\Controllers\Laboratorio;

use App\Http\Controllers\Controller;
use App\Models\AreaAnalisis;
use App\Models\Constante;
use App\Models\LoteAnalisis;
use App\Models\LoteDetalle; 
use App\Models\ObservacionMuestra;
use App\Models\ProcesoAnalisis;
use Illuminate\Http\Request;
use App\Models\Parametro;
use App\Models\Reportes;
use App\Models\SolicitudParametro;
use App\Models\TipoFormula;
use App\Models\CurvaConstantes;
use App\Models\estandares;
use App\Models\TecnicaLoteMetales;
use App\Models\BlancoCurvaMetales;
use App\Models\CurvaCalibracionMet;
use App\Models\VerificacionMetales;
use App\Models\EstandarVerificacionMet;
use App\Models\GeneradorHidrurosMet;
use App\Models\Tecnica;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LaboratorioController extends Controller
{
    function index()
    {
        return view('laboratorio.laboratorio');
    }

    public function exportPdfCaptura($idLote)
    {
        //Opciones del documento PDF
        $mpdf = new \Mpdf\Mpdf([
            'orientation' => 'L',
            'format' => 'letter',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 48,
            'margin_bottom' => 40,
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
        
        $id_lote = $idLote;
        $semaforo = true;
        $tecnicaUsada = null;

        //$curvaCalibracion = DB::table('curva_calibracion_met')->where('Id_lote', $id_lote)->first();
        //$generadorHidruros = DB::table('generador_hidruros_met')->where('Id_lote', $id_lote)->first();
        
        $formula = DB::table('ViewLoteDetalle')->where('Id_lote', $id_lote)->first();
        $dataLote = LoteAnalisis::find($id_lote);
        if(!is_null($formula)){
            //Recupera el tipo de fórmula del parámetro
            $paramDetected = Parametro::where('Id_parametro', $formula->Id_parametro)->first();
            $tipoFormula = TipoFormula::where('Id_tipo_formula', $paramDetected->Id_tipo_formula)->first();
            $loteAnalisis = LoteAnalisis::where('Id_lote', $id_lote)->first();
            $tecnicaUsada = Tecnica::where('Id_tecnica', $loteAnalisis->Id_tecnica)->first();

            $formulaSelected = $formula->Parametro;
            $formulaSelectedComp = $formula->Parametro. " (".$tipoFormula->Tipo_formula.")";
        }else{
            $formula = DB::table('ViewLoteDetalle')->where('Id_lote', 0)->first();
            $formulaSelected = $formula->Parametro;
            $formulaSelectedComp = $formula->Parametro. " (".$formula->Area_analisis.")";
            $mpdf->SetJS('print("No se han llenado todos los datos del reporte. Verifica que todos los datos estén ingresados.");');
        }

        //Recupera el nombre de usuario y firma
        $usuario = DB::table('users')->where('id', auth()->user()->id)->first();
        $firma = $usuario->firma;

        //Formatea la fecha
        $fechaAnalisis = DB::table('ViewLoteAnalisis')->where('Id_lote', $id_lote)->first();
        if(!is_null($fechaAnalisis)){
            $fechaConFormato = date("d/m/Y", strtotime($fechaAnalisis->Fecha));
            $hora = date("H:i", strtotime($fechaAnalisis->created_at));
        }else{
            $fechaAnalisis = DB::table('ViewLoteAnalisis')->where('Id_lote', 0)->first();
            $fechaConFormato = date("d/m/Y", strtotime($fechaAnalisis->Fecha));
            $hora = date("H:i", strtotime($fechaAnalisis->created_at));
            $mpdf->SetJS('print("No se han llenado todos los datos del reporte. Verifica que todos los datos estén ingresados.");');
        }

        $datos = DB::table('ViewLoteDetalle')->where('Id_lote', $id_lote)->orderBy('Id_control', 'DESC')->get();
        if(!is_null($datos)){
            //Recupera el parámetro que se está utilizando
            $parametro = DB::table('ViewLoteDetalle')->where('Id_lote', $id_lote)->first();

            if (!is_null($parametro)) {                
                $limiteC = DB::table('parametros')->where('Id_parametro', $parametro->Id_parametro)->first();
            }

            //Falta campo de resultado
            $limites = array();
                    foreach ($datos as $item) {
                        if ($item->Vol_disolucion < $limiteC->Limite) {  //Tira error debido a que no existe aún en la tabla el campo Resultado
                            $limC = "< " . $limiteC->Limite;

                            array_push($limites, $limC);
                        } else {  //Si es mayor el resultado que el límite de cuantificación
                            $limC = round($item->Vol_disolucion, 3);

                            array_push($limites, $limC);
                        }
                    }

            $datosLength = sizeof($datos);
        }else{
            $datos = DB::table('ViewLoteDetalle')->where('Id_lote', 0)->get();
            $datosLength = sizeof($datos);
            echo '<script> alert("Valores predeterminados para las ABS. Rellena este campo.") </script>';
        }

        $loteModel = array();
        $loteModelPh = array();
        
        foreach($datos as $item){
            $loteModelObs = DB::table('observacion_muestra')->where('Id_analisis', $item->Id_analisis)->first();

            array_push(
                $loteModel,
                $loteModelObs->Observaciones
            );

            array_push(
                $loteModelPh,
                $loteModelObs->Ph
            );
        }        


        $html = view('exports.laboratorio.captura', compact('datos', 'datosLength', 'loteModel', 'loteModelPh', 'limites', 'tecnicaUsada'));
        
        //Hace referencia a la vista capturaHeader y posteriormente le envía el valor de la var.formulaSelected
        $htmlHeader = view('exports.laboratorio.capturaHeader', compact('formulaSelected', 'formulaSelectedComp', 'tecnicaUsada', 'fechaConFormato', 'hora'));
        //Establece el encabezado del documento PDF
        $mpdf->setHeader("{PAGENO}<br><br>" . $htmlHeader);

        //Hace referencia a la vista capturaPie
        $htmlFooter = view('exports.laboratorio.capturaPie', compact('usuario', 'firma', 'tecnicaUsada')); 
        //Establece el pie de página del PDF                
        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
        //*************************************************Segundo juego de documentos PDF***************************************************
        $mpdf->AddPage('', '', '', '', '', '', '', 40, 35, 6.5, '', '', '', '', '', -1, -1, -1, -1);

        $semaforoHoja1 = true;

        //Recupera (PRUEBA) el texto dinámico Procedimientos de la tabla reportes
        $textoProcedimiento = Reportes::where('Id_lote', $id_lote)->first();
        if(!is_null($textoProcedimiento)){
            //Hoja1
            $htmlCurva = view('exports.laboratorio.curvaBody', compact('textoProcedimiento'));
        }else{
            $textoProcedimiento = Reportes::where('Id_lote', 0)->first();
            $htmlCurva = view('exports.laboratorio.curvaBody', compact('textoProcedimiento'));

            $mpdf->SetJS('print("Valores predeterminados para el reporte. Rellena este campo.");');

            //echo '<script type= alert("Valores predeterminados para el reporte. Rellena este campo."); </script>';
        }

        //if(!is_null($formula) && !is_null($fechaAnalisis)){
            $htmlCurvaHeader = view('exports.laboratorio.curvaHeader', compact('formulaSelected', 'formulaSelectedComp', 'tecnicaUsada', 'fechaConFormato', 'hora'));
            $mpdf->SetHTMLHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlCurvaHeader, 'O', 'E');
        //}
        
        $htmlCurvaFooter = view('exports.laboratorio.curvaFooter', compact('usuario', 'tecnicaUsada'));        
        $mpdf->SetHTMLFooter($htmlCurvaFooter, 'O', 'E');
        
        /* if(is_null($textoProcedimiento) || is_null($formula) || is_null($fechaAnalisis)){
            $semaforoHoja1 = false;
        } */

        //if($semaforoHoja1 === true){ 
            //$mpdf->WriteHTML($htmlCurva);
        //}

        //Hoja2
        $semaforoHoja2 = true;
        //$mpdf->AddPage('', '', '', '', '', '', '', 36, '', '', '', '', '', '', '', '', '', '', '');
        
        //if(!is_null($formula)){
            $limiteCuantificacion = DB::table('parametros')->where('Parametro', $formulaSelected)->first();
        //}

    //     $model = estandares::whereDate('Fecha_inicio', '<=', $today)->whereDate('Fecha_fin', '>=', $today)
    //     ->where('Id_area', $request->area)
    //     ->where('Id_parametro', $request->parametro)->get();

    // $concent = ConcentracionParametro::where('Id_parametro', $request->parametro)->get();
    // $bmr = CurvaConstantes::whereDate('Fecha_inicio', '<=', $today)
    //     ->whereDate('Fecha_fin', '>=', $today)
    //     ->where('Id_area', $request->area)
    //     ->where('Id_parametro', $request->parametro)->first();
        $fecha = new Carbon($dataLote->Fecha);
        $today = $fecha->toDateString();
                
        $estandares = estandares::where('Id_parametro', $formula->Id_parametro)->whereDate('Fecha_inicio','>=',$today)->whereDate('Fecha_fin','<=',$today)->get();
        $topeEstandar = 0;

        if(is_null($estandares)){
            $estandares = estandares::where('Id_lote', 0)->get();
            echo '<script> alert("Valores predeterminados para los estándares. Rellena estos datos.") </script>';
        }else{
            foreach($estandares as $item){
                if($item->STD == 'STD4'){
                    $topeEstandar = 4;
                }else if($item->STD == 'STD5'){
                    $topeEstandar = 5;
                }
            }
        }

        $bmr = CurvaConstantes::where('Id_parametro', $formula->Id_parametro)->whereDate('Fecha_inicio','>=',$today)->whereDate('Fecha_fin','<=',$today)->first();
        if(is_null($bmr)){
            $bmr = CurvaConstantes::where('Id_lote', 0)->first();
            echo '<script> alert("Valores predeterminados para las curvas. Rellena estos datos.") </script>';
        }
        
        $tecnicaMetales = TecnicaLoteMetales::where('Id_lote', $id_lote)->first();
        if(!is_null($tecnicaMetales)){
            //Recupera la fecha de preparación y le da un formato d/m/Y        
            $fechaPreparacion = date("d/m/Y", strtotime($tecnicaMetales->Fecha_preparacion));

            //Instancia Carbon
            $fechaHora = Carbon::parse($tecnicaMetales->Fecha_hora_dig);

            //Separa de la hora la fecha y aplica un formato DD/MM/AAAA
            $soloFecha = $fechaHora->toDateString();
            $soloFechaFormateada = date("d/m/Y", strtotime($soloFecha));

            //Separa la hora de la fecha dando un formato de HH:mm:ss
            $soloHoraFormateada = $fechaHora->format('H:i');
            //$soloHoraFormateada = $fechaHora->toTimeString();
        }else{
            $tecnicaMetales = TecnicaLoteMetales::where('Id_lote', 0)->first();            
            $fechaPreparacion = date("d/m/Y", strtotime($tecnicaMetales->Fecha_preparacion));            
            $fechaHora = Carbon::parse($tecnicaMetales->Fecha_hora_dig);
            $soloFecha = $fechaHora->toDateString();
            $soloFechaFormateada = date("d/m/Y", strtotime($soloFecha));            
            $soloHoraFormateada = $fechaHora->format('H:i');
            //$soloHoraFormateada = $fechaHora->toTimeString();

            echo '<script> alert("Valores predeterminados en la sección Flama/Generador de hidruros/Horno de grafito/Alimentos. Rellena estos datos.") </script>';
        }

        //Recupera los datos de las tablas filtrándolas por Id del lote
        $blancoMetales = BlancoCurvaMetales::where('Id_lote', $id_lote)->first();
        if(is_null($blancoMetales)){
            $blancoMetales = BlancoCurvaMetales::where('Id_lote', 0)->first();
            echo '<script> alert("Valores predeterminados en la sección Blanco de curva. Rellena estos datos.") </script>';
        }

        $estandarMetales = EstandarVerificacionMet::where('Id_lote', $id_lote)->first();
        if(is_null($estandarMetales)){
            $estandarMetales = EstandarVerificacionMet::where('Id_lote', 0)->first();
            echo '<script> alert("Valores predeterminados para la sección Estándar de verificación del instrumento. Rellena estos datos.") </script>';
        }

        $verificacionMetales = VerificacionMetales::where('Id_lote', $id_lote)->first();
        if(is_null($verificacionMetales)){
            $verificacionMetales = VerificacionMetales::where('Id_lote', 0)->first();
            echo '<script> alert("Valores predeterminados para la sección Verificación del espectrofotómetro. Rellena estos datos.") </script>';
        }


            $htmlCurva2 = view('exports.laboratorio.curvaBody', compact('textoProcedimiento', 'estandares', 'topeEstandar', 'limiteCuantificacion', 'bmr', 
            'tecnicaMetales', 'blancoMetales', 'estandarMetales', 'verificacionMetales', 'fechaConFormato', 'soloFechaFormateada', 
            'soloHoraFormateada', 'fechaPreparacion','sw', 'hora', 'tecnicaUsada'));
            $mpdf->WriteHTML($htmlCurva2);


        
        //}

        //Hoja 3
        $mpdf->AddPage('', '', '', '', '', '', '', 40, 45, '', '', '', '', '', '', '', '', '', '');
        $mpdf->WriteHTML($html);
        
        $mpdf->Output();
    }
}
