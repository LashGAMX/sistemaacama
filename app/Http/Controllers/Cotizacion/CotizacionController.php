<?php

namespace App\Http\Controllers\Cotizacion;

use App\Http\Controllers\AnalisisQ\NormaControler;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\ClienteGeneral;
use App\Models\SucursalCliente;
use Illuminate\Http\Request;

use App\Models\Cotizacion;
use App\Models\CotizacionMuestreo;
use App\Models\Norma;
use App\Models\SubNorma;
use App\Models\CotizacionParametros;
use App\Models\CotizacionPunto;
use App\Models\DireccionReporte;
use App\Models\Frecuencia001;
use App\Models\NormaParametros;
use App\Models\ParametroNorma;
use App\Models\PrecioCatalogo;
use App\Models\PrecioPaquete;
use App\Models\SeguimientoAnalsis;
use App\Models\TipoMuestraCot;
use App\Models\PromedioCot;
use App\Models\Sucursal;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

use PDF;
use Mpdf\Mpdf;


class CotizacionController extends Controller
{
    //
    public function index()
    {
        //Vista Cotización
        $model = DB::table('ViewCotizacion')->orderBy('created_at', 'DESC')->get();
        return view('cotizacion.cotizacion', compact('model'));
    }
    public function getClientesIntermediarios(Request $res){
        $model = ClienteGeneral::where('Id_intermediario',$res->id)->get();

        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function getDataCliente(Request $res){
        $model = SucursalCliente::where('Id_sucursal', $res->id)->first();
        $direccion = DireccionReporte::where('Id_sucursal',$model->Id_sucursal)->get();

        $data = array(
            'model' => $model,
            'direccion' => $direccion,
        ); 

        return response()->json($data);
    }
    public function getSucursal(Request $res){ 
        $model = SucursalCliente::where('Id_cliente', $res->id)->get();

        $data = array(
            'model' => $model,
        ); 

        return response()->json($data);
    }
    public function getNormas(Request $res)
    {
        $model = Norma::where('Id_descarga',$res->id)->get();
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function getSubNormas(Request $res)
    {
        $model = DB::table("ViewPrecioPaq")->where('Id_norma',$res->id)->get();
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function getParametrosNorma(Request $res)
    {
        $model = DB::table('ViewNormaParametro')->where('Id_norma',$res->id)->get();
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function getParametrosSelected(Request $res)
    {
        $model = DB::table('ViewNormaParametro')->where('Id_norma',$res->subnorma)->get();
        $parametros = DB::table('ViewParametros')->get();
        $data = array(
            'parametros' => $parametros,
            'model' => $model,
        );
        return response()->json($data);
    }
    public function getFrecuenciaMuestreo(Request $res)
    {
        $model = Frecuencia001::find($res->id);
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function getDatosCotizacion(Request $res)
    {
        $precioModel = DB::table('ViewPrecioPaquete')->where('Id_paquete', $res->subnorma)->first();
        $precioAnalisis = 0;
        switch ($res->frecuencia) {
            case 1:
                $precioAnalisis =  $precioModel->Precio2;
                break;
            case 2:
                $precioAnalisis =  $precioModel->Precio3;
                break;
            case 3:
                $precioAnalisis =  $precioModel->Precio4;
                break;
            case 4:
                $precioAnalisis =  $precioModel->Precio5;
                break;
            case 5:
                $precioAnalisis =  $precioModel->Precio6;
                break;
            case 6:
                $precioAnalisis =  $precioModel->Precio1;
                break;
            default:
                $precioAnalisis =  0;
                break;
        }
        $data = array(
            'precio' => $precioAnalisis,
        );
        return response()->json($data);
    }
    public function setCotizacion(Request $res){
        $year = date("y");
        $dayYear = date("z") + 1;
        $today = Carbon::now()->format('Y-m-d');

        $cotizacionDay = DB::table('cotizacion')->whereDate('created_at', $today)->where('Hijo', 0)->count();
        $numCot = DB::table('cotizacion')->whereDate('created_at', $today)->where('Id_sucursal', $res->clienteSucursal)->get();
        $hijo = 0;
        if ($numCot->count()) {
            $hijo = 1;
            $firtsFol = DB::table('cotizacion')->where('created_at', 'LIKE', "%{$today}%")->where('Id_sucursal', $res->clienteSucursal)->first();    
            $folio = $firtsFol->Folio . '-' . ($numCot->count() + 1);
        } else {
            $folio = $dayYear . "-" . ($cotizacionDay + 1) . "/" . $year;
        }
        
        
        $cotizacion = Cotizacion::create([
            'Id_intermedio' => $res->intermediario,
            'Id_cliente' => $res->cliente,
            'Id_sucursal' => $res->clienteSucursal,
            'Nombre' => $res->nomCli,
            'Direccion' => $res->dirCli,
            'Atencion' => $res->atencion,
            'Telefono' => $res->telCli,
            'Correo' => $res->correoCli,
            'Tipo_servicio' => $res->tipoServicio,
            'Tipo_descarga' => $res->tipoDescarga,
            'Id_norma' => $res->norma,
            'Id_subnorma' => $res->subnorma,
            'Fecha_muestreo' => $res->fecha,
            'Frecuencia_muestreo' => $res->frecuencia,
            'Tomas' => $res->tomas,
            'Tipo_muestra' => $res->tipoMuestra,
            'Promedio' => $res->promedio,
            'Tipo_reporte' => $res->tipoReporte,
            'Numero_puntos' => sizeof($res->puntos),
            'Estado_cotizacion' => 1,
            'Folio' => $folio,
            'Creado_por' => Auth::user()->id,
            'Actualizado_por' => Auth::user()->id,
            'Hijo' => $hijo,
        ]);
        
    
       for ($i=0; $i < sizeof($res->parametros); $i++) { 
        $subnorma = NormaParametros::where('Id_norma', $res->subnorma)->where('Id_parametro', $res->parametros[$i])->get();

            $extra = 0;
            if ($subnorma->count() > 0) {
                $extra = 0;
            } else {
                $extra = 1;
            }

            CotizacionParametros::create([
                'Id_cotizacion' => $cotizacion->Id_cotizacion,
                'Id_subnorma' => $res->parametros[$i],
                'Extra' => $extra,
            ]);
       }
       for ($i=0; $i < sizeof($res->puntos); $i++) { 
        CotizacionPunto::create([
            'Id_cotizacion' => $cotizacion->Id_cotizacion,
            'Descripcion' => $res->puntos[$i],
        ]);
       }

        
        $data = array(
            'model' => $cotizacion,
        );
        return response()->json($data);
    }
    public function setPrecioMuestreo(Request $request)
    {
        $model = DB::table('costo_muestreo')->first();
        $kmTotal = ($request->km + $request->kmExtra);
        $desgaste = $kmTotal * $model->Desgaste_km;
        $gasolinaTeorico = ($kmTotal / $model->Rendimiento);
        $costoViaticos = (($request->hospedaje * $request->diasHospedaje) + ($request->caseta) + ($model->Comida * $request->diasMuestreo * $request->numMuestreador) + ($model->Gasolina * $request->cantidadGasolina));
        $costoSuma = ($costoViaticos + ($model->Pago_muestreador * $request->diasMuestreo * $request->numMuestreador) + $desgaste + $model->Insumo);
        $costoTotal = ($costoSuma * (1 + $model->Ganancia));
        $costoTotal += ($request->paqueteria + $request->gastosExtras);
        $data = array(
            'descaste' => $desgaste,
            'viaticos' => $costoViaticos,
            'costoSuma' => $costoSuma,
            'total' => $costoTotal,
            'model' => $model,
        );
        return response()->json($data);
    }
    public function create()
    {
        $intermediarios = DB::table('ViewIntermediarios')->where('deleted_at', null)->get();
        $generales = DB::table('ViewGenerales')->where('deleted_at', null)->get();
        $frecuencia = DB::table('frecuencia001')->get();
        $subNormas = SubNorma::all();
        $servicios = DB::table('tipo_servicios')->get();
        $descargas = DB::table('tipo_descargas')->get();
        $metodoPago = DB::table('metodo_pago')->get(); 
        $estados = DB::table('estados')->get();
        $categorias001 = DB::table('ViewDetalleCuerpos')->get();
        $tipoMuestraCot = TipoMuestraCot::all();
        $promedioCot = PromedioCot::all();



        $data = array(
            'categorias001' => $categorias001,
            'tipoMuestraCot' => $tipoMuestraCot,
            'promedioCot' => $promedioCot,
            'intermediarios' => $intermediarios,
            'generales' => $generales,
            'subNormas' => $subNormas,
            'servicios' => $servicios,
            'descargas' => $descargas,
            'frecuencia' => $frecuencia,
            'estados' => $estados,
            'metodoPago' => $metodoPago,
            'version' => $this->version,
        );
        return view('cotizacion.create', $data);
    }
    public function update($id)
    {
        $intermediarios = DB::table('ViewIntermediarios')->where('deleted_at', null)->get();
        $generales = DB::table('ViewGenerales')->where('deleted_at', null)->get();
        $frecuencia = DB::table('frecuencia001')->get();
        $subNormas = SubNorma::all();
        $servicios = DB::table('tipo_servicios')->get();
        $descargas = DB::table('tipo_descargas')->get();
        $metodoPago = DB::table('metodo_pago')->get();
        $estados = DB::table('estados')->get(); 
        $categorias001 = DB::table('ViewDetalleCuerpos')->get();
        $tipoMuestraCot = TipoMuestraCot::all();
        $promedioCot = PromedioCot::all();
        $model = DB::table('ViewCotizacion')->where('Id_cotizacion',$id)->first();


        $data = array(
            'model' => $model,
            'categorias001' => $categorias001,
            'tipoMuestraCot' => $tipoMuestraCot,
            'promedioCot' => $promedioCot,
            'intermediarios' => $intermediarios,
            'generales' => $generales,
            'subNormas' => $subNormas,
            'servicios' => $servicios,
            'descargas' => $descargas,
            'frecuencia' => $frecuencia,
            'estados' => $estados,
            'metodoPago' => $metodoPago,
            
        ); 
        return view('cotizacion.create', $data);
    }
    public function getDataUpdate(Request $res)
    {
        $model = DB::table('ViewCotizacion')->where('Id_cotizacion',$res->id)->first();
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function comprobarEdicion(Request $res)
    {
        $sw = false;
        $model = Cotizacion::find($res->id);
        if ($model->Folio_servicio != NULL) {
            $sw = true;
        } else {
            $sw = false;
        }

        $data = array('sw' => $sw);
        return response()->json($data);
    }
    public function exportPdfOrden($idCot)
    {

        //Recupera los parámetros de la cotización
        $parametros = DB::table('ViewCotParam')->where('Id_cotizacion', $idCot)->where('Extra', 0)->orderBy('Parametro', 'ASC')->get();

        //Recupera los parámetros extra de la cotización
        $parametrosExtra = DB::table('ViewCotParam')->where('Id_cotizacion', $idCot)->where('Extra', 1)->orderBy('Parametro', 'ASC')->get();
        $sumaParamEspecial = 0;

        foreach ($parametrosExtra as $item) {
            $precioEspecial = PrecioCatalogo::where('Id_parametro', $item->Id_subnorma)->first();
            $sumaParamEspecial += $precioEspecial->Precio;
        }

        $model = DB::table('ViewCotizacion')->where('Id_cotizacion', $idCot)->first();
        $norma = Norma::where('Id_norma', $model->Id_norma)->first();
        $puntos = CotizacionPunto::where('Id_cotizacion', $model->Id_cotizacion)->get();

        $analisisDesc = $model->Precio_analisis - (($model->Precio_analisis * $model->Descuento) / 100);

        if ($parametrosExtra->count() > 0) {
            $subTotal = $analisisDesc + $sumaParamEspecial + $model->Precio_muestreo;
        } else {
            $subTotal = $analisisDesc + $sumaParamEspecial + $model->Precio_muestreo;
        }


        $mpdf = new \Mpdf\Mpdf([
            'format' => 'letter',
            'margin_left' => 5,
            'margin_right' => 5,
            'margin_top' => 20,
            'margin_bottom' => 25
        ]);
        $mpdf->SetWatermarkImage(
            asset('/public/storage/MembreteVertical.png'),
            1,
            array(215, 280),
            array(0, 0),
        );

        $firma = User::find(24); // Firma maribel
        $mpdf->showWatermarkImage = true;
        $html = view('exports.cotizacion.cotizacion', compact('model', 'parametros', 'parametrosExtra', 'norma', 'puntos', 'sumaParamEspecial', 'analisisDesc', 'subTotal', 'firma'));
        $mpdf->CSSselectMedia = 'mpdf';

        $htmlFooter = view('exports.cotizacion.footerCotizacion');
        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');

        $mpdf->WriteHTML($html);
        $mpdf->Output();
    }

}
