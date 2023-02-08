<?php

namespace App\Http\Controllers\Cotizacion;

use App\Http\Controllers\Controller;
use App\Http\Livewire\AnalisisQ\LimiteParametros001;
use App\Http\Livewire\AnalisisQ\Normas;
use App\Models\ClienteSiralab;
use App\Models\CodigoParametros;
use App\Models\ContactoCliente;
use App\Models\Cotizacion;
use App\Models\CotizacionMuestreo;
use App\Models\CotizacionParametros;
use App\Models\CotizacionPunto;
use App\Models\DireccionReporte;
use App\Models\Frecuencia001;
use App\Models\Intermediario;
use App\Models\Norma;
use App\Models\NormaParametros;
use App\Models\PhMuestra;
use App\Models\PuntoMuestreoGen;
use App\Models\PuntoMuestreoSir;
use App\Models\SeguimientoAnalisis;
use App\Models\Solicitud;
use App\Models\SolicitudParametro;
use App\Models\SolicitudPuntos; 
use App\Models\SubNorma;
use App\Models\SucursalCliente;
use App\Models\TipoDescarga;
use App\Models\TipoServicios;
use App\Models\TipoMuestraCot;
use App\Models\PromedioCot;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use PDF;
use \Milon\Barcode\DNS1D;
use \Milon\Barcode\DNS2D;

class SolicitudController extends Controller
{
    // 
    public function index()
    {
        // $model = DB::table('ViewSolicitud')->get();
        $model = DB::table('ViewCotizacion')->orderby('Id_cotizacion', 'DESC')->get();
        return view('cotizacion.solicitud', compact('model'));
    }
    public function buscarFecha($inicio, $fin)
    {
        $model = DB::table('ViewCotizacion')->whereBetween('created_at', [$inicio, $fin])->get();
        return view('cotizacion.solicitud', compact('model'));
    }

    public function create($idCot)
    {
        $tipoMuestraCot = TipoMuestraCot::all();
        $promedioCot = PromedioCot::all();
        $servicios = TipoServicios::all();
        $descargas = TipoDescarga::all();
        $frecuencia = DB::table('frecuencia001')->get();
        $model = DB::table('ViewCotizacion')->where('Id_cotizacion', $idCot)->first();
        $intermediario = DB::table('ViewIntermediarios')->get();
        $categorias001 = DB::table('ViewDetalleCuerpos')->get();
        $data = array(
            'model' => $model,
            'tipoMuestraCot' => $tipoMuestraCot,
            'promedioCot' => $promedioCot,
            'servicio' => $servicios,
            'descargas' => $descargas,
            'categorias001' => $categorias001,
            'frecuencia' => $frecuencia,
            'intermediario' => $intermediario,
        );

        return view('cotizacion.createSolicitud', $data);
    }
    public function getDatoIntermediario(Request $res)
    {
        $model = DB::table('ViewIntermediarios')->where('Id_intermediario', $res->id)->first();
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function getDataCotizacion(Request $res)
    {
        $parametro = DB::table('ViewCotParam')->where('Id_cotizacion', $res->id)->get();
        $model = DB::table('ViewCotizacion')->where('Id_cotizacion', $res->id)->first();
        $data = array(
            'model' => $model,
            'parametro' => $parametro
        );
        return response()->json($data);
    }
    public function getClienteRegistrado(Request $res)
    {
        $model = DB::table('ViewGenerales')->get();
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function getSucursalCliente(Request $res)
    {
        $contacto = ContactoCliente::where('Id_cliente', $res->id)->get();
        $sucursal = SucursalCliente::where('Id_cliente', $res->id)->get();
        $data = array(
            'idCliente' => $res->cliente,
            'model' => $sucursal,
            'contacto' => $contacto,
        );
        return response()->json($data);
    }
    public function getDireccionReporte(Request $res)
    {
        $model = DireccionReporte::where('Id_sucursal', $res->id)->get();
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function setContacto(Request $res)
    {
        ContactoCliente::create([
            'Id_cliente' => $res->id,
            'Nombres' => $res->nombre,
            'A_paterno' => $res->paterno,
            'A_materno' => $res->materno,
            'Celular' => $res->celular,
            'Telefono' => $res->telefono,
            'Email' => $res->correo,
        ]);

        $model = ContactoCliente::Where('Id_cliente', $res->idCliente)->get();

        $data = array(
            'model' => $model,
        );

        return response()->json($data);
    }
    public function getDataContacto(Request $res)
    {
        $model = ContactoCliente::where('Id_contacto', $res->id)->first();
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function storeContacto(Request $request)
    {
        $model = ContactoCliente::find($request->idContacto);
        $model->Nombres = $request->nombre;
        $model->A_paterno = $request->paterno;
        $model->A_materno = $request->materno;
        $model->Celular = $request->celular;
        $model->Telefono = $request->telefono;
        $model->Email = $request->correo;
        $model->save();

        $model = ContactoCliente::Where('Id_cliente', $request->idCliente)->get();

        $data = array(
            'model' => $model,
        );

        return response()->json($data);
    }
    public function getPuntoMuestro(Request $request)
    {
        $model = PuntoMuestreoGen::where('Id_sucursal', $request->idSuc)->get();
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function setSolicitud(Request $res)
    {
        $year = date("y");
        $month = date("m");
        $dayYear = date("z") + 1;
        $today = Carbon::now()->format('Y-m-d');
        // $solicitudDay = DB::table('solicitudes')->where('created_at', 'LIKE', "%{$today}%")->count();
        $solicitudDay = DB::table('solicitudes')->whereDate('created_at', $today)->where('Padre', 1)->count();


        $numCot = DB::table('solicitudes')->whereDate('created_at', $today)->where('Id_cliente', $res->clientes)->get();
        $firtsFol = DB::table('solicitudes')->where('created_at', 'LIKE', "%{$today}%")->where('Id_cliente', $res->clientes)->first();
        $cantCot = $numCot->count();

        //var_dump($numCot);
        if ($cantCot > 0) {
            echo "Entro a if <br>";
            $hijo = 1;
            // $folio = $firtsFol->Folio_servicio . '-' . ($cantCot + 1);
            $folio = $dayYear . "-" . ($solicitudDay + 1) . "/" . $year;
        } else {
            echo "Entro a else <br>";
            $folio = $dayYear . "-" . ($solicitudDay + 1) . "/" . $year;
        }
        //var_dump($folio);
        // Convertir cadena a array de datos

        if ($res->id > 0) {
            if ($res->siralab != NULL) {
                $siralab = 1;
            } else {
                $siralab = 0;
            }

            $model = Solicitud::create([
                'Id_cotizacion' => $res->id,
                'Folio_servicio' => $folio,
                'Id_intermediario' => $res->inter,
                'Id_cliente' => $res->clientes,
                'Siralab' => $siralab,
                'Id_sucursal' => $res->sucursal,
                'Id_direccion' => $res->direccionReporte,
                'Id_contacto' => $res->contacto,
                'Atencion' => $res->atencion,
                'Observacion' => $res->observacion,
                'Id_servicio' => $res->tipoServicio,
                'Id_descarga' => $res->tipoDescarga,
                'Id_norma' => $res->norma,
                'Id_subnorma' => $res->subnorma,
                'Fecha_muestreo' => $res->fechaMuestreo,
                'Id_muestreo' => $res->frecuencia,
                'Num_tomas' => $res->numTomas,
                'Id_muestra' => $res->tipoMuestra,
                'Id_promedio' => $res->promedio,
                'Padre' => 1,
                'Hijo' => 0,
            ]);

            // var_dump($model->Id_solicitud);
            $contPuntos = 0;
            DB::table('solicitud_puntos')->where('Id_solicitud', $model->Id_solicitud)->delete();
            for ($i = 0; $i < sizeof($res->puntos); $i++) {
                SolicitudPuntos::create([
                    'Id_solicitud' => $model->Id_solicitud,
                    'Id_muestreo' =>  $res->puntos[$i]
                ]);
                $contPuntos++;
            }

            for ($i = 0; $i < sizeof($res->parametros); $i++) {
                $subnorma = NormaParametros::where('Id_norma', $res->subnorma)->where('Id_parametro', $res->parametros[$i])->get();

                $extra = 0;
                if ($subnorma->count() > 0) {
                    $extra = 0;
                } else {
                    $extra = 1;
                }
                SolicitudParametro::create([
                    'Id_solicitud' => $model->Id_solicitud,
                    'Id_subnorma' => $res->parametros[$i],
                    'Extra' => $extra,
                ]);
            }

            //Actualiza la cotización de estado
            $cotModel = Cotizacion::find($res->id);
            $cotModel->Folio_servicio = $model->Folio_servicio;
            $cotModel->Estado_cotizacion = 2;
            $cotModel->save();


            //todo Inicia seguimiento de analisis
            SeguimientoAnalisis::create([
                'Id_servicio' => $model->Id_solicitud,
                'Obs_solicitud' => '',
            ]);

            if ($contPuntos > 0) {
                for ($i = 0; $i < $contPuntos; $i++) {
                    if ($res->siralab != NULL) {
                        $siralab = 1;
                    } else {
                        $siralab = 0;
                    }
                    $model2 = Solicitud::create([
                        'Id_cotizacion' => $res->id,
                        'Folio_servicio' => $folio . '-' . ($i + 1),
                        'Id_intermediario' => $res->inter,
                        'Id_cliente' => $res->clientes,
                        'Siralab' => $siralab,
                        'Id_sucursal' => $res->sucursal,
                        'Id_direccion' => $res->direccionReporte,
                        'Id_contacto' => $res->contacto,
                        'Atencion' => $res->atencion,
                        'Observacion' => $res->observacion,
                        'Id_servicio' => $res->tipoServicio,
                        'Id_descarga' => $res->tipoDescarga,
                        'Id_norma' => $res->norma,
                        'Id_subnorma' => $res->subnorma,
                        'Fecha_muestreo' => $res->fechaMuestreo,
                        'Id_muestreo' => $res->frecuencia,
                        'Num_tomas' => $res->numTomas,
                        'Id_muestra' => $res->tipoMuestra,
                        'Id_promedio' => $res->promedio,
                        'Padre' => 0,
                        'Hijo' => $model->Id_solicitud
                    ]);
                    SolicitudPuntos::create([
                        'Id_solPadre' => $model->Id_solicitud,
                        'Id_solicitud' => $model2->Id_solicitud,
                        'Id_muestreo' => $res->puntos[$i]
                    ]);


                    for ($i = 0; $i < sizeof($res->parametros); $i++) {
                        $subnorma = NormaParametros::where('Id_norma', $res->subnorma)->where('Id_parametro', $res->parametros[$i])->get();

                        $extra = 0;
                        if ($subnorma->count() > 0) {
                            $extra = 0;
                        } else {
                            $extra = 1;
                        }
                        SolicitudParametro::create([
                            'Id_solicitud' => $model2->Id_solicitud,
                            'Id_subnorma' => $res->parametros[$i],
                            'Extra' => $extra,
                        ]);
                    }
                }
            }
        } else {
        }
 
        $data = array(
            'punto' => $res,
        );
        return response()->json($data);
        // return redirect()->to('admin/cotizacion/solicitud');
    }
    public function setGenFolio(Request $request)
    {

        $sw = false;
        $coliforme = false;
        $ga = false;
        $dbo = false;
        $dqo = false;
        $modelPadre = DB::table('ViewSolicitud')->where('Id_cotizacion', $request->idCot)->where('Padre', 1)->first();
        $model = DB::table('ViewSolicitud')->where('Hijo', $modelPadre->Id_solicitud)->get();


        foreach ($model as $item) {
            $solParam = DB::table('ViewSolicitudParametros')->where('Id_solicitud', $item->Id_solicitud)->where('Id_parametro', 13)->get();
            if ($solParam->count()) {
                $ga = true;
            }
            $solParam = DB::table('ViewSolicitudParametros')->where('Id_solicitud', $item->Id_solicitud)->where('Id_parametro', 12)->get();
            if ($solParam->count()) {
                $coliforme = true;
            }
            $solParam = DB::table('ViewSolicitudParametros')->where('Id_solicitud', $item->Id_solicitud)->where('Id_parametro', 5)->get();
            if ($solParam->count()) {
                $dbo = true;
            }
            $solParam = DB::table('ViewSolicitudParametros')->where('Id_solicitud', $item->Id_solicitud)->where('Id_parametro', 6)->get();
            if ($solParam->count()) {
                $dqo = true;
            }
        }
        $phMuestra = PhMuestra::where('Id_solicitud', $item->Id_solicitud)->where('Activo', 1)->get();

        if ($phMuestra->count()) {
            foreach ($model as $value) {
                # code...
                $sw = false;
                $cont = 0;
                $swCodigo = CodigoParametros::where('Id_solicitud', $value->Id_solicitud)->get();
                $solParam = DB::table('ViewSolicitudParametros')->where('Id_solicitud', $value->Id_solicitud)->get();

                if ($swCodigo->count()) {
                    $sw = true;
                } else {
                    foreach ($solParam as $item) {

                        switch ($item->Id_parametro) {
                            case 13:
                                // G&A
                                for ($i = 0; $i < $phMuestra->count(); $i++) {
                                    if ($phMuestra[$i]->Activo == 1) {
                                        CodigoParametros::create([
                                            'Id_solicitud' => $value->Id_solicitud,
                                            'Id_parametro' => $item->Id_parametro,
                                            'Codigo' => $value->Folio_servicio . "-G-" . ($i + 1) . "",
                                            'Num_muestra' => $i + 1,
                                            'Asignado' => 0,
                                            'Analizo' => 1,
                                        ]);
                                    }
                                }
                                break;
                            case 12:
                                // Coliformes
                                for ($i = 0; $i < $phMuestra->count(); $i++) {
                                    if ($phMuestra[$i]->Activo == 1) {
                                        CodigoParametros::create([
                                            'Id_solicitud' => $value->Id_solicitud,
                                            'Id_parametro' => $item->Id_parametro,
                                            'Codigo' => $value->Folio_servicio . "-C-" . ($i + 1) . "",
                                            'Num_muestra' => $i + 1,
                                            'Asignado' => 0,
                                            'Analizo' => 1,
                                        ]);
                                    }
                                }
                                break;
                            case 5:
                                // DBO
                                for ($i = 0; $i < 3; $i++) {
                                    CodigoParametros::create([
                                        'Id_solicitud' => $value->Id_solicitud,
                                        'Id_parametro' => $item->Id_parametro,
                                        'Codigo' => $value->Folio_servicio . "-D-" . ($i + 1) . "",
                                        'Num_muestra' => $i + 1,
                                        'Asignado' => 0,
                                        'Analizo' => 1,
                                        'Cadena' => 0,
                                    ]);
                                }
                                break;
                            case 6:
                                // DQO
                                CodigoParametros::create([
                                    'Id_solicitud' => $value->Id_solicitud,
                                    'Id_parametro' => $item->Id_parametro,
                                    'Codigo' => $value->Folio_servicio,
                                    'Num_muestra' => 1,
                                    'Asignado' => 0,
                                    'Analizo' => 1,
                                ]);
                                CodigoParametros::create([
                                    'Id_solicitud' => $value->Id_solicitud,
                                    'Id_parametro' => 152,
                                    'Codigo' => $value->Folio_servicio,
                                    'Num_muestra' => 1,
                                    'Asignado' => 0,
                                    'Analizo' => 1,
                                ]);
                                break;
                            default:
                                CodigoParametros::create([
                                    'Id_solicitud' => $value->Id_solicitud,
                                    'Id_parametro' => $item->Id_parametro,
                                    'Codigo' => $value->Folio_servicio,
                                    'Num_muestra' => 1,
                                    'Asignado' => 0,
                                    'Analizo' => 1,
                                ]);
                                break;
                        }
                    }
                }
            }
        } else {
            $sw = false;
        }





        $data = array(
            'dqo' => $dqo,
            'sw' => $sw,
            'ga' => $ga,
            'coliformes' => $coliforme,
            'dbo' => $dbo,
        );

        return response()->json($data);
    }
    public function exportPdfOrden($idOrden)
    {
        $qr = new DNS2D();
        $model = DB::table('ViewSolicitud')->where('Id_cotizacion', $idOrden)->first();
        $cliente = SucursalCliente::where('Id_sucursal', $model->Id_sucursal)->first();
        $direccion = DireccionReporte::where('Id_sucursal', $model->Id_sucursal)->first();
        $parametros = DB::table('ViewSolicitudParametros')->where('Id_solicitud', $model->Id_solicitud)->where('Extra', 0)->orderBy('Parametro', 'ASC')->get();
        $extra = DB::table('ViewSolicitudParametros')->where('Id_solicitud', $model->Id_solicitud)->where('Extra', 1)->orderBy('Parametro', 'ASC')->get();
        $cotizacion = DB::table('ViewCotizacion')->where('Id_cotizacion', $idOrden)->first();
        $frecuenciaMuestreo = Frecuencia001::where('Id_frecuencia', $cotizacion->Frecuencia_muestreo)->first();


        $mpdf = new \Mpdf\Mpdf([ 
            'format' => 'letter',
            'margin_left' => 20,
            'margin_right' => 20,
            'margin_top' => 30,
            'margin_bottom' => 18
        ]);

        $mpdf->SetWatermarkImage(
            asset('/public/storage/MembreteVertical.png'),
            1,
            array(215, 280),
            array(0, 0),
        );

        $data = array(
            'extra' => $extra,
            'model' => $model,
            'parametros' => $parametros,
            'qr' => $qr,
            'cotizacion' => $cotizacion,
            'direccion' => $direccion,
            'frecuenciaMuestreo' => $frecuenciaMuestreo,
            'cliente' => $cliente,
        );

        $html = view('exports.cotizacion.ordenServicio', $data);
        $mpdf->CSSselectMedia = 'mpdf';
        $mpdf->WriteHTML($html);
        $mpdf->Output();
    }

}
