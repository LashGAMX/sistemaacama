<?php

namespace App\Http\Controllers\Cotizacion;

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
use App\Models\NormaParametros;
use App\Models\SeguimientoAnalsis;
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
        $model = DB::table('ViewCotizacion')->orderBy('Id_cotizacion', 'DESC')->get();
        return view('cotizacion.cotizacion', compact('model'));
    }
    public function buscarFecha($inicio, $fin)
    {
        $model = DB::table('ViewCotizacion')->whereBetween('created_at', [$inicio, $fin])->get();
        return view('cotizacion.cotizacion', compact('model'));
    }
    public function clienteSucursal(Request $request){
        $datosCliente = SucursalCliente::where('Id_cliente', $request->idCliente)->get();

        $data = array(
            'model' => $datosCliente,
            'id' => $request->idCliente
        );
        return response()->json($data);
    }
    public function DatosClienteSucursal(Request $request){ 
        $info = SucursalCliente::where('Id_sucursal', $request->idSucursal)->first();
        $nombre = $info->Empresa;

        $data = array(
            'info' => $info,
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

        

        $data = array(
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


        $model = DB::table('ViewCotizacion')->where('Id_cotizacion', $id)->first();
        $cotizacionParametros = CotizacionParametros::where('Id_cotizacion', $id)->get();
        $cotizacionPuntos = CotizacionPunto::where('Id_cotizacion', $id)->get();
        $cotizacionMuestreo = DB::table('cotizacion_muestreos')->where('Id_cotizacion', $id)->first();

        $data = array(
            'intermediarios' => $intermediarios,
            'generales' => $generales,
            'subNormas' => $subNormas,
            'servicios' => $servicios,
            'descargas' => $descargas,
            'frecuencia' => $frecuencia,
            'metodoPago' => $metodoPago,
            'model' => $model,
            'estados' => $estados,
            'cotizacionParametros' => $cotizacionParametros,
            'cotizacionPuntos' => $cotizacionPuntos,
            'muestreo' => $cotizacionMuestreo,
            'idCotizacion' => $id,
            'sw' => 1,
        );
        return view('cotizacion.create', $data);
    }
    public function getParametroCot(Request $request)
    {
        $model = DB::table('ViewCotParam ')->where('Id_cotizacion', $request->idCot)->get();
        return response()->json(compact('model'));
    }
    public function getCotizacionId(Request $request)
    {
        $model = DB::table('ViewCotizacion')->where('Id_cotizacion', $request->idCotizacion)->first();
        return response()->json(compact('model'));
    }
    public function getCliente()
    {
        $id = $_POST['cliente'];
        $model = DB::table('ViewGenerales')->where('Id_cliente', $id)->first();
        return response()->json($model);
    }
    public function getSubNorma()
    {
        if(isset($_POST['idCotizacion'])){            
            $id = $_POST['norma'];
            $idCot = $_POST['idCotizacion'];                                    

            $cotizacion = DB::table('ViewCotizacion')->where('Id_cotizacion', $idCot)->first();
            $model = DB::table('ViewPrecioPaq')->where('Id_subnorma', $cotizacion->Id_subnorma)->get();
            
            $data = array(
                'model' => $model,
            );
            return response()->json($data);
        }else{
            $id = $_POST['norma'];
            $model = DB::table('ViewPrecioPaq')->where('Id_norma', $id)->get();
            $data = array(
                'model' => $model,
            );
            return response()->json($data);
        }
    }
    public function getSubNormaId()
    {
        $id = $_POST['idSub'];
        $model = DB::table('sub_normas')->where('Id_subnorma', $id)->first();
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function getLocalidad()
    {
        $id = $_POST['idLocalidad'];
        $model = DB::table('localidades')->where('Id_estado', $id)->get();
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function getNorma()
    {
        $diDescarga = $_POST['idDescarga'];
        $model = Norma::where('Id_descarga', $diDescarga)->get();
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function cantidadGasolina(Request $request)
    {
        $model = DB::table('costo_muestreo')->first();
        $kmTotal = ($request->km + $request->kmExtra);
        $descaste = $kmTotal * $model->Desgaste_km;
        $gasolinaTeorico = ($kmTotal / $model->Rendimiento);
        $data = array(
            'total' => $gasolinaTeorico,
        );
        return response()->json($data);
    }
    public function precioMuestreo(Request $request)
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
    public function getDatos2()
    {
        $idIntermediario = $_POST['intermediario'];
        $idSub = $_POST['idSub'];
        $idParametros = $_POST['idParametros'];
        $idServicio = $_POST['idServicio'];
        $idDescarga = $_POST['idDescarga'];

        $parametroExtra = array();

        $intermediarios = DB::table('ViewIntermediarios')->where('Id_cliente', $idIntermediario)->first();
        $subnorma = DB::table('sub_normas')->where('Id_subnorma', $idSub)->first();
        $servicio = DB::table('tipo_servicios')->where('Id_tipo', $idServicio)->first();
        $descarga = DB::table('tipo_descargas')->where('Id_tipo', $idDescarga)->first();

        $contExtra = 0;
        for ($i = 0; $i < sizeof($idParametros); $i++) {
            $parPre = DB::table('norma_parametros')->where('Id_norma', $idSub)->where('Id_parametro', $idParametros[$i])->get();
            if ($parPre->count()) {
            } else {
                $parametroExtra[$contExtra] = $idParametros[$i];
                $contExtra++;
            }
        }

        $precioTotal = 0;

        # Obtiene el precio del paquete
        $precioModel = DB::table('ViewPrecioPaqInter')->where('Id_intermediario', $idIntermediario)->where('Id_catalogo', $idSub)->first();
        if ($precioModel != NULL) {
            $precioTotal = $precioTotal + $precioModel->Precio;
        } else {
            $precioModel = DB::table('ViewPrecioPaq')->where('Id_paquete', $idSub)->first();
            $precioTotal = $precioTotal +  $precioModel->Precio;
        }
        # Obtener el precio por parametro extra

        if (sizeof($parametroExtra) > 0) {
            for ($i = 0; $i < sizeof($parametroExtra); $i++) {
                # code...
                $precioModel = DB::table('ViewPrecioCatInter')->where('Id_intermediario', $idIntermediario)->where('Id_catalogo', $parametroExtra[$i])->first();
                if ($precioModel != null) {
                    $precioTotal += $precioModel->Precio;
                } else {
                    $precioModel = DB::table('ViewPrecioCat')->where('Id_parametro', $parametroExtra[$i])->first();
                    $precioTotal += $precioModel->Precio;
                }
            }
        }


        $data = array(
            'intermediarios' => $intermediarios,
            'subnorma' => $subnorma,
            'idParametros' => $idParametros,
            'precioTotal' => $precioTotal,
            'servicio' => $servicio,
            'descarga' => $descarga
        );
        return response()->json($data);
    }
    public function getTomas()
    {
        $idFrecuencia = $_POST['idFrecuencia'];
        $model = DB::table('frecuencia001')->where('Id_frecuencia', $idFrecuencia)->first();
        return response()->json($model);
    }
    public function setCotizacion(Request $request)
    {

        $year = date("y");
        $month = date("m");
        $dayYear = date("z") + 1;
        $today = Carbon::now()->format('Y-m-d');
        $cotizacionDay = DB::table('cotizacion')->where('created_at', 'LIKE', "%{$today}%")->count();

        $numCot = DB::table('cotizacion')->where('created_at', 'LIKE', "%{$today}%")->where('Id_cliente', $request->clientes)->get();
        $firtsFol = DB::table('cotizacion')->where('created_at', 'LIKE', "%{$today}%")->where('Id_cliente', $request->clientes)->first();
        $cantCot = $numCot->count();
        if ($cantCot > 0) {

            $folio = $firtsFol->Folio . '-' . ($cantCot + 1);
        } else {
            $folio = $dayYear . "-" . ($cotizacionDay + 1) . "/" . $year;
        }


        //  var_dump($request->precioAnalisis);

        $cotizacion = Cotizacion::create([
            'Id_intermedio' => $request->intermediario,
            'Id_cliente' => $request->clientes,
            'Nombre' => $request->nombreCliente,
            'Direccion' => $request->direccion,
            'Atencion' => $request->atencion,
            'Telefono' => $request->telefono,
            'Correo' => $request->correo,
            'Tipo_servicio' => $request->tipoServicio,
            'Tipo_descarga' => $request->tipoDescarga,
            'Id_norma' => $request->norma,
            'Id_subnorma' => $request->subnorma,
            'Fecha_muestreo' => $request->fecha,
            'Frecuencia_muestreo' => $request->frecuencia,
            'Tomas' => $request->tomas,
            'Tipo_muestra' => $request->tipoMuestra,
            'Promedio' => $request->promedio,
            'Numero_puntos' => $request->contPunto,
            'Tipo_reporte' => $request->tipoReporte,
            'Tiempo_entrega' => $request->tiempoEntrega,
            'Observacion_interna' => $request->observacionInterna,
            'Observacion_cotizacion' => $request->observacionCotizacion,
            'Folio' => $folio,
            'Metodo_pago' => $request->metodoPago,
            'Precio_analisis' => $request->precioAnalisis,
            'Descuento' => $request->descuento,
            'Precio_muestreo' => $request->precioMuestra,
            'Sub_total' => $request->subTotal,
            'Costo_total' => $request->precioTotal,
            'Estado_cotizacion' => 1,
            'Creado_por' => Auth::user()->id,
            'Actualizado_por' => Auth::user()->id,
        ]);

        CotizacionMuestreo::create([
            'Id_cotizacion' => $cotizacion->Id_cotizacion,
            'Dias_hospedaje' => $request->diasHospedaje,
            'Hospedaje' => $request->hospedaje,
            'Dias_muestreo' => $request->diasMuestreo,
            'Num_muestreo' => $request->numeroMuestreo,
            'Caseta' => $request->caseta,
            'Km' => $request->km,
            'Km_extra' => $request->kmExtra,
            'Gasolina_teorico' => $request->gasolinaTeorico,
            'Cantidad_gasolina' => $request->cantidadGasolina,
            'Paqueteria' => $request->paqueteria,
            'Adicional' => $request->gastosExtras,
            'Num_servicio' => $request->numeroServicio,
            'Num_muestreador' => $request->numMuestreador,
            'Estado' => $request->estado,
            'Localidad' => $request->localidad,
            'Total' => $request->totalMuestreo
        ]);

        $parametro = $request->parametrosCotizacion;
        $parametro = explode(',', $parametro);


        foreach ($parametro as $item) {
            $subnorma = NormaParametros::where('Id_norma', $request->subnorma)->where('Id_parametro', $item)->get();

            $extra = 0;
            if ($subnorma->count() > 0) {
                $extra = 0;
            } else {
                $extra = 1;
            }

            CotizacionParametros::create([
                'Id_cotizacion' => $cotizacion->Id_cotizacion,
                'Id_subnorma' => $item,
                'Extra' => $extra,
            ]);
            echo $item;
        }

        $puntoMuestreo = $request->puntosCotizacion;
        $puntoMuestreo = explode(',', $puntoMuestreo);
        
        foreach ($puntoMuestreo as $item) {
            CotizacionPunto::create([
                'Id_cotizacion' => $cotizacion->Id_cotizacion,
                'Descripcion' => $item,
            ]);
        }


        return redirect()->to('admin/cotizacion');
    }
    public function fecha()
    {
        // $year = date("y");
        // $month = date("m");
        // $dayYear = date("z") + 1;
        // $today = Carbon::now()->format('Y-m-d');
        // $cotizacionDay = DB::table('cotizacion')->where('created_at','LIKE',"%{$today}%")->count();
        // $folio = $dayYear . "-" . ($cotizacionDay + 1) . "/" . $year;
        // var_dump($folio);
        // $firtsFol = DB::table('cotizacion')->where('created_at','LIKE',"%{$today}%")->where('Id_cliente',$request->clientes)->first();
        // var_dump($);
    } //

    /**
     * Actualizar Cotización
     */
    public function updateCotizacion(Request $request)
    {
        $id = $request->idCotizacion;

        // var_dump($id);

        Cotizacion::where('Id_cotizacion', $id)
            ->update([
                'Id_intermedio' => $request->intermediario,
                'Id_cliente' => $request->clientes,
                'Nombre' => $request->nombreCliente,
                'Direccion' => $request->direccion,
                'Atencion' => $request->atencion,
                'Telefono' => $request->telefono,
                'Correo' => $request->correo,
                'Correo' => $request->correo,
                'Tipo_servicio' => $request->tipoServicio,
                'Tipo_descarga' => $request->tipoDescarga,
                'Id_norma' => $request->norma,
                'Id_subnorma' => $request->subnorma,
                'Fecha_muestreo' => $request->fecha,
                'Frecuencia_muestreo' => $request->frecuencia,
                'Tomas' => $request->tomas,
                'Tipo_muestra' => $request->tipoMuestra,
                'Promedio' => $request->promedio,
                'Numero_puntos' => $request->promedio,
                'Tipo_reporte' => $request->tipoReporte,
                'Tiempo_entrega' => $request->tiempoEntrega,
                'Observacion_interna' => $request->observacionInterna,
                'Observacion_cotizacion' => $request->observacionCotizacion,
                'Metodo_pago' => $request->metodoPago,
                'Precio_analisis' => $request->precioAnalisis,
                'Descuento' => $request->descuento,
                'Precio_muestreo' => $request->precioMuestra,
                'Sub_total' => $request->subTotal,
                'Costo_total' => $request->precioTotal,
                'Estado_cotizacion' => 1,
                'Actualizado_por' => Auth::user()->id,
            ]);

        CotizacionMuestreo::where('Id_cotizacion', $id)
            ->update([
                'Dias_hospedaje' => $request->diasHospedaje,
                'Hospedaje' => $request->hospedaje,
                'Dias_muestreo' => $request->diasMuestreo,
                'Num_muestreo' => $request->numeroMuestreo,
                'Caseta' => $request->caseta,
                'Km' => $request->km,
                'Km_extra' => $request->kmExtra,
                'Gasolina_teorico' => $request->gasolinaTeorico,
                'Cantidad_gasolina' => $request->cantidadGasolina,
                'Paqueteria' => $request->paqueteria,
                'Adicional' => $request->gastosExtras,
                'Num_servicio' => $request->numeroServicio,
                'Num_muestreador' => $request->numMuestreador,
                'Estado' => $request->estado,
                'Localidad' => $request->localidad,
                'Total' => $request->totalMuestreo
            ]);


        $cotParam = DB::table('cotizacion_parametros')->where('Id_cotizacion', $id)->get();

        //Elimina cada parámetro de la cotización existente
        foreach($cotParam as $item){
            $item->delete();
        }

        // $cotPunto = DB::table('cotizacion_puntos')->where('Id_cotizacion', $id)->get();
        $cotPuntos =  DB::table('cotizacion_puntos')->where('Id_cotizacion', $id)->delete();

        //Elimina cada punto de la cotizacion existente
        // foreach($cotPunto as $item){
        //     $item->delete();
        // }  

        $parametro = $request->parametrosCotizacion;
        $parametro = explode(',', $parametro);
 

        foreach ($parametro as $item) {
            $subnorma = NormaParametros::where('Id_norma', $request->subnorma)->where('Id_parametro', $item)->get();

            $extra = 0;
            if ($subnorma->count() > 0) {
                $extra = 0;
            } else {
                $extra = 1;
            }

            /* CotizacionParametros::where('Id_cotizacion', $id)->update([
                'Id_cotizacion' => $id,
                'Id_subnorma' => $item,
                'Extra' => $extra
            ]); */

            CotizacionParametros::create([
                'Id_cotizacion' => $id,
                'Id_subnorma' => $item,
                'Extra' => $extra,
            ]);
            // echo $item;
        }

        $puntoMuestreo = $request->puntosCotizacion;
        $puntoMuestreo = explode(',', $puntoMuestreo);
        foreach ($puntoMuestreo as $item) {
            CotizacionPunto::create([
                'Id_cotizacion' => $id,
                'Descripcion' => $item,
            ]);
        }


        return redirect('admin/cotizacion');
    }

    public function duplicar($idCot){
        
        //Duplica la Cotización
        $cotOriginal = Cotizacion::where('Id_cotizacion', $idCot)->first();
        $cotReplicada = $cotOriginal->replicate();

        $year = date("y");
        $month = date("m");
        $dayYear = date("z") + 1;
        $today = Carbon::now()->format('Y-m-d');
        $cotizacionDay = DB::table('cotizacion')->where('created_at', 'LIKE', "%{$today}%")->count();

        $numCot = DB::table('cotizacion')->where('created_at', 'LIKE', "%{$today}%")->where('Id_cliente', $cotOriginal->Id_cliente)->get();
        $firtsFol = DB::table('cotizacion')->where('created_at', 'LIKE', "%{$today}%")->where('Id_cliente', $cotOriginal->Id_cliente)->first();
        $cantCot = $numCot->count();
        if ($cantCot > 0) {

            $folio = $firtsFol->Folio . '-' . ($cantCot + 1);
        } else {
            $folio = $dayYear . "-" . ($cotizacionDay + 1) . "/" . $year;
        }

        //$cotReplicada->Id_cotizacion = $idCot;
        $cotReplicada->Folio_servicio = NULL;
        $cotReplicada->Folio = $folio;
        $cotReplicada->Creado_por = Auth::user()->id;
        $cotReplicada->Actualizado_por = Auth::user()->id;
        $cotReplicada->created_at = Carbon::now();
        $cotReplicada->updated_at = Carbon::now();
        
        $cotReplicada->save();

        $cotMuestreoOriginal = CotizacionMuestreo::where('Id_cotizacion', $idCot)->first();
        $cotMuestreoDuplicada = $cotMuestreoOriginal->replicate();
        $cotMuestreoDuplicada->Id_cotizacion = $cotReplicada->Id_cotizacion;
        $cotMuestreoDuplicada->Id_user_c = Auth::user()->id;
        $cotMuestreoDuplicada->Id_user_m = Auth::user()->id;
        $cotMuestreoDuplicada->created_at = Carbon::now();
        $cotMuestreoDuplicada->updated_at = Carbon::now();
        $cotMuestreoDuplicada->save();

        $cotParamOriginal = CotizacionParametros::where('Id_cotizacion', $idCot)->get();

        foreach($cotParamOriginal as $item){
            $cotParamDuplicada = $item->replicate();
            $cotParamDuplicada->Id_cotizacion = $cotReplicada->Id_cotizacion;
            $cotParamDuplicada->save();
        }

        $cotPuntoOriginal = CotizacionPunto::where('Id_cotizacion', $idCot)->get();

        foreach($cotPuntoOriginal as $item){
            $cotPuntoDuplicada = $item->replicate();
            $cotPuntoDuplicada->Id_cotizacion = $cotReplicada->Id_cotizacion;
            $cotPuntoDuplicada->save();
        }        

        echo "<script>alert('Cotización duplicada exitosamente!');</script>";
        return redirect()->to('admin/cotizacion/solicitud');
    }

    public function exportPdfOrden($idCot)
    {
        
        //Recupera los parámetros de la cotización
        $parametros = DB::table('ViewCotParam')->where('Id_cotizacion', $idCot)->where('Extra', 0)->orderBy('Parametro', 'ASC')->get();

        //Recupera los parámetros extra de la cotización
        $parametrosExtra = DB::table('ViewCotParam')->where('Id_cotizacion', $idCot)->where('Extra', 1)->orderBy('Parametro', 'ASC')->get();
        
        $model = DB::table('ViewCotizacion')->where('Id_cotizacion', $idCot)->first();

        // $mpdf = new \Mpdf\Mpdf([
        //     'format' => 'letter',
        //     'margin_left' => 0, 
        //     'margin_right' => 0,
        //     'margin_top' => 0,
        //     'margin_bottom' => 0,
        //     'margin_header' => 0,
        //     'margin_footer' => 0,  
        // ]); 
        $mpdf = new \Mpdf\Mpdf([
            'format' => 'letter',
            'margin_left' => 5,
            'margin_right' => 5,
            'margin_top' => 20, 
            'margin_bottom' => 25
        ]);
        // var_dump(storage_path('public/HojaMembretada.png'));
        // $mpdf->SetWatermarkImage();https://dev.sistemaacama.com.mx//storage/HojaMembretada.png
        $mpdf->SetWatermarkImage(
            asset('/public/storage/MembreteVertical.png'),
            1,
            array(215, 280),
            array(0, 0), 
        );
      
        $mpdf->showWatermarkImage = true;
        $html = view('exports.cotizacion.cotizacion', compact('model','parametros', 'parametrosExtra'));
        $mpdf->CSSselectMedia = 'mpdf';

        $htmlFooter = view('exports.cotizacion.footerCotizacion');
        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
        
        $mpdf->WriteHTML($html);
        $mpdf->Output();        
    }
 
}
