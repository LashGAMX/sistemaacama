<?php

namespace App\Http\Controllers\Config;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Laboratorio\FqController;
use App\Models\PlantillaBitacora;
use App\Models\PlantillaDirectos;
use App\Models\PlantillaMb;
use App\Models\PlantillaMetales;
use App\Models\PlantillaPotable;
use App\Models\PlantillasFq;
use App\Models\PlantillaVolumetria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PlantillasController extends Controller
{
    //
    public function index()
    {
        $idUser = Auth::user()->id;
        return view('config/plantillas/index', compact('idUser'));
    }
    public function bitacoras()
    {
        $model = PlantillaBitacora::all();
        $parametros = DB::table('ViewParametros')->get();
        $data = array(
            'parametros' => $parametros,
            'model' => $model,
        );
        return view('config/plantillas/bitacoras', $data);
    }
    public function getPlantillas(Request $res)
    {
        $model  = DB::table('ViewPlantillaBitacoras')->get();
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function getDetalleBitacora(Request $res)
    {
        $model = PlantillaBitacora::where('Id_plantilla',$res->id)->get();
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function setPlantilla(Request $res)
    {
        $model = PlantillaBitacora::where('Id_plantilla', $res->id)->get();
        $model[0]->Texto = $res->texto;
        $model[0]->Titulo = $res->titulo;
        $model[0]->Rev = $res->rev;
        $model[0]->save();
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function setNewPlantilla(Request $res)
    {
        $model = PlantillaBitacora::where('Id_parametro', $res->id)->get();
        if ($model->count()) {
        } else {
            $model = PlantillaBitacora::create([
                'Id_parametro' => $res->id,
                'Titulo' => "Falta registar titulo",
                'Texto' => "Falta registrar procedimiento",
                'Rev' => "Falta ingresar Revisión",
            ]);
        }
        $data = array(
            'tipo' => $res->tipo,
            'model' => $model,
        );
        return response()->json($data);
    }
    public function pdfBitacora($id)
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

        $plantilla = PlantillaBitacora::where('Id_parametro', $id)->get();
        $procedimiento = explode("NUEVASECCION", $plantilla[0]->Texto);

        $data = array(
            'plantilla' => $plantilla,
            'procedimiento' => $procedimiento,
        );

        $htmlFooter = view('exports.config.plantillas.bitacoraFooter', $data);
        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
        $htmlHeader = view('exports.config.plantillas..bitacoraHeader', $data);
        $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
        $htmlCaptura = view('exports.config.plantillas.bitacoraBody', $data);
        $mpdf->CSSselectMedia = 'mpdf';
        $mpdf->WriteHTML($htmlCaptura);
        $mpdf->Output();
    }

    
}
