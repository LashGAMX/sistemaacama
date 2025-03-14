<?php

namespace App\Http\Controllers\Alimentos;

use App\Http\Controllers\Controller;
use App\Models\ContactoCliente;
use App\Models\Cotizacion;
use App\Models\DireccionReporte;
use App\Models\Norma;
use App\Models\SolicitudesAlimentos;
use App\Models\SubNorma;
use App\Models\SucursalCliente;
use App\Models\SucursalContactos;
use App\Models\ClienteGeneral;
use App\Models\CotizacionAlimentos;
use App\Models\SolicitudMuestraA;
use App\Models\SolicitudParametrosA;
use App\Models\CodigoParametroA;
use App\Models\CodigoParametros;
use App\Models\ControlCalidad;
use App\Models\LoteAnalisis;
use App\Models\LoteDetalleDirectos;
use App\Models\Parametro;
use App\Models\ProcesoAnalisisA;
use App\Models\LoteDetalle;
use App\Models\LoteDetalleDureza;
use App\Models\LoteDetalleEspectro;
use App\Models\LoteDetalleHH;
use App\Models\LoteDetalleSolidos;
use App\Models\LoteDetalleDqo;
use App\Models\LoteDetalleNitrogeno;
use App\Models\LoteDetalleCloro;
use App\Models\LoteDetalleGA;
use App\Models\LoteDetalleColiformes;
use App\Models\LoteDetalleAlcalinidad;
use App\Models\LoteDetalleDbo;
use App\Models\LoteDetalleDirectosA;
use App\Models\LoteDetalleColor;
use App\Models\LoteDetalleVidrio;
use App\Models\LoteDetalleEnterococos;
use App\Models\LoteDetalleDboIno;
use App\Models\LoteDetalleEcoli;
use App\Models\ProcesoAnalisis;
use App\Models\MatrazGA;
use App\Models\CrisolesGA;
use App\Models\Capsulas;
use App\Models\LoteDetallePotable;

use App\Models\TipoServicios;
use App\Models\RecepcionAlimentos;
use App\Models\Users;
use Carbon\Carbon;
use Mpdf\Mpdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Psy\Command\WhereamiCommand;

class AlimentosController extends Controller
{
    //Vistas 
    public function cotizacion()
    {
        return view('alimentos.cotizacion');
    }
    public function GetBitacora()
    {
        // Retornar la vista con el ID
        return view('alimentos.bitacoraRecepcion');
    }
    public function  RecepcionAli()
    {
        // Retornar la vista con el ID
        return view('alimentos.replabalimentos');
    }
    public function CampoAlimentos()
    {
        return view('alimentos.campo');
    }
    public function informe()
    {
        // $model = DB::table('ViewProcesoAnalisis')->where('Cancelado',0)->where('Padre',1)->orderBy("Id_procAnalisis","desc")->get();
        $model = ProcesoAnalisisA::orderBy("Id_procAnalisis", "desc")->get();
        return view('alimentos.informes', compact('model'));
    }
    public function getPuntoMuestro(Request $res)
    {
        $solModel = SolicitudesAlimentos::where('Id_solicitud', $res->id)->first();
        $model = SolicitudMuestraA::where('Id_solicitud', $res->id)->get();
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function createcotizacion()
    {
        return view('alimentos.createCotizacion');
    }
    public function captura()
    {
        $parametro = DB::table('ViewParametroUsuarios')->where('Id_user', Auth::user()->id)->get();
        $control = ControlCalidad::all();
        $data = array(
            'control' => $control,
            'model' => $parametro,
        );
        return view('alimentos.captura', $data);
    }
    public function ordenServicio()
    {
        return view('alimentos.ordenServicio');
    }
    public function recepcionMuestras()
    {
        return view('alimentos.recepcionMuestras');
    }
    public function createOrden()
    {
        // $normas = Norma::where('Id_tipo', 2)->get();
        $normas = Norma::where('Id_tipo', 2)->get();
        $clientes = DB::table('ViewClienteGeneral')->where('Id_intermediario', 21)->where('stdCliente', NULL)->get();
        $servicios = TipoServicios::all();
        $parametros = DB::table('viewparametros')->get();
        $data = array(
            'parametros' => $parametros,
            'servicios' => $servicios,
            'normas' => $normas,
            'clientes' => $clientes
        );
        return view('alimentos.createOrden', $data);
    }
    public function createOrdenIngreso()
    {
        // $normas = Norma::where('Id_tipo', 2)->get();
        $normas = Norma::all();
        $clientes = DB::table('ViewClienteGeneral')->where('Id_intermediario', 21)->where('stdCliente', NULL)->get();
        $servicios = TipoServicios::all();
        $parametros = DB::table('viewparametros')->get();
        $data = array(
            'parametros' => $parametros,
            'servicios' => $servicios,
            'normas' => $normas,
            'clientes' => $clientes
        );
        return view('alimentos.createOrdenIngreso', $data);
    }
    public function editOrden($id)
    {
        $model = SolicitudesAlimentos::find($id);
        // $normas = Norma::all();
        $normas = Norma::where('Id_tipo', 2)->get();
        $clientes = DB::table('ViewClienteGeneral')->where('Id_intermediario', 21)->where('stdCliente', NULL)->get();
        $servicios = TipoServicios::all();
        // $muestras = SolicitudMuestraA::where('Id_solicitud', $id)->get();
        $servicios = TipoServicios::all();
        $parametros = DB::table('viewparametros')->get();

        $data = array(
            'parametros' => $parametros,
            'model' => $model,
            // 'muestras' => $muestras,
            'servicios' => $servicios,
            'normas' => $normas,
            'clientes' => $clientes
        );
        return view('alimentos.editarOrden', $data);
    }
    public function getSucursal(Request $res)
    {

        $model = SucursalCliente::where('Id_cliente', $res->id)->get();

        $data = array(
            'model' => $model,
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
    public function getContactoSucursal(Request $res)
    {
        $model = SucursalContactos::where('Id_sucursal', $res->id)->get();

        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function getSubNormas(Request $res)
    {
        $model = SubNorma::where('Id_norma', $res->id)->get();

        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    function setSolicitud(Request $res)
    {
        $clientes = DB::table('ViewClienteGeneral')->where('Id_cliente', $res->cliente)->first();
        $direccion = DireccionReporte::where('Id_direccion', $res->direccion)->first();
        $sucursal = SucursalCliente::find($res->sucursal);
        $servicios = TipoServicios::find($res->servicio);
        $norma = Norma::find($res->norma);
        $subNorma = SubNorma::find($res->subnorma);

        $msg = "";

        if ($res->idSol != "") {
            try {

                $model = SolicitudesAlimentos::find($res->idSol);
                $model->Num_muestras = $res->numTomas;
                $model->Id_cliente = $res->cliente;
                $model->Cliente = $clientes->Empresa;
                $model->Id_sucursal = $res->sucursal;
                $model->Sucursal = $sucursal->Empresa;
                $model->Fecha_muestreo = $res->fechaMuestreo;
                $model->Id_direccion = $res->direccion;
                $model->Direccion = $direccion->Direccion;
                $model->Atencion = $res->atencion;
                $model->Id_contacto = $res->contacto;
                $model->Id_servicio = $res->servicio;
                $model->Servicio = $servicios->Servicio;
                $model->Id_norma = $res->norma;
                $model->Norma = $norma->Clave_norma;
                $model->Id_subnorma = $res->subnorma;
                $model->Sub_norma = $subNorma->Clave;
                $model->Observacion = $res->observacion;
                $model->Estatus = 2;
                $model->save();

                $msg = "Solicitud editada correctamente";

                $data = array(
                    'model' => $model,
                    'msg' => $msg,
                );

                return response()->json($data);
            } catch (\Exception $e) {
                return response()->json(['error' => 'Ocurrió un error durante la solicitud: ' . $e->getMessage()], 500);
            }
        } else {
            try {
                $model = SolicitudesAlimentos::create([
                    'Num_muestras' => $res->numTomas,
                    'Id_cliente' => $res->cliente,
                    'Cliente' => $clientes->Empresa,
                    'Id_sucursal' => $res->sucursal,
                    'Sucursal' => $sucursal->Empresa,
                    'Fecha_muestreo' => $res->fechaMuestreo,
                    'Id_direccion' => $res->direccion,
                    'Direccion' => $direccion->Direccion,
                    'Atencion' => $res->atencion,
                    'Id_contacto' => $res->contacto,
                    'Id_servicio' => $res->servicio,
                    'Servicio' => $servicios->Servicio,
                    'Id_norma' => $res->norma,
                    'Norma' => $norma->Clave_norma,
                    'Id_subnorma' => $res->subnorma,
                    'Sub_norma' => $subNorma->Clave,
                    'Observacion' => $res->observacion,
                    'Estatus' => 1,
                ]);

                $msg = "Solicitud creada";

                $data = array(
                    'model' => $model,
                    'msg' => $msg,
                );

                return response()->json($data);
            } catch (\Exception $e) {
                return response()->json(['error' => 'Ocurrió un error durante la solicitud: ' . $e->getMessage()], 500);
            }
        }
    }
    public function getOrden()
    {
        $solicitudes = SolicitudesAlimentos::all();
        $data = array(
            'status' => 'success',
            'data' => $solicitudes,
        );
        return response()->json($data);
    }
    public function getcotizacionAli()
    {
        $cotizaciones = CotizacionAlimentos::select(
            'Id_cotizacion',
            'Folio_servicio',
            'Folio',
            'Fecha_cotizacion',
            'Nombre',
            'Id_norma',
            'Tipo_descarga',
            'Estado_cotizacion',
            'Creado_por',
            'created_at',
            'Actualizado_por',
            'updated_at'
        )->get();

        return response()->json(['data' => $cotizaciones]);
    }
    public function getClientesIntermediarios(Request $res)
    {
        $model = ClienteGeneral::where('Id_intermediario', $res->id)->get();

        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function getDataSolicitud(Request $res)
    {
        $msg = "";
        $model = SolicitudesAlimentos::where('Id_solicitud', $res->id)->first();
        $msg = "Muestra creada correctamente";
        $data = array(
            'msg' => $msg,
            'model' => $model,
        );
        return response()->json($data);
    }
    public function setMuestraSol(Request $res)
    {
        $msg = "";

        try {
            $model = SolicitudMuestraA::create([
                'Id_solicitud' => $res->idSol,
            ]);
            $msg = "Punto creado correctamente";
        } catch (\Throwable $th) {
            $msg = $th;
        }

        $data = array(
            'msg' => $msg,
        );
        return response()->json($data);
    }
    public function getMuestraSol(Request $res)
    {
        $model = SolicitudMuestraA::where('Id_solicitud', $res->idSol)->get();
        $parametros = DB::table('ViewParametros')->get();
        $normas = Norma::where('Id_tipo', 2)->get();


        $solParam = array();

        foreach ($model as $item) {
            $temp = SolicitudParametrosA::where('Id_muestra', $item->Id_muestra)->get();
            array_push($solParam, $temp);
        }

        $data = array(
            'solParam' => $solParam,
            'parametros' => $parametros,
            'model' => $model,
            'normas' => $normas,
        );
        return response()->json($data);
    }
    public function exportPdfOrden($id)
    {
        $solicitud = SolicitudesAlimentos::with(['contacto', 'servicio'])->where('Id_solicitud', $id)->first();
        $muestras = SolicitudMuestraA::where('Id_solicitud', $id)->get();
        $mpdf = new Mpdf([
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

        $mpdf->showWatermarkImage = true;

        $mpdf->SetTitle('Orden de Servicio - Folio ' . ($solicitud->Folio ? $solicitud->Folio : 'Sin Folio'));

        $data = [
            'folio' => $solicitud->Folio ? $solicitud->Folio : 'Sin Folio',
            'cliente' => $solicitud->Cliente ? $solicitud->Cliente : 'No existe un nombre del cliente',
            'direccion' => $solicitud->Direccion ? $solicitud->Direccion : 'No existe una dirección',
            'contacto' => $solicitud->contacto ? $solicitud->contacto->Nombre : 'Sin contacto',
            'numero' => $solicitud->contacto ? $solicitud->contacto->Celular : 'Sin teléfono',
            'email' => $solicitud->contacto ? $solicitud->contacto->Email : 'No hay Email',
            'observacion' => $solicitud->Observacion ? $solicitud->Observacion : 'Sin observaciones',
            'servicio' => $solicitud->servicio ? $solicitud->servicio->Servicio : 'No existe ese servicio',
            'fecha' => $solicitud->Fecha_muestreo ? $solicitud->Fecha_muestreo : 'No hay fecha de muestreo',
            'hora' => $solicitud->Hora_muestreo ? $solicitud->Hora_muestreo : 'No hay hora de muestreo',
            'solicitud' => $solicitud,
            'muestras' => $muestras,
        ];

        $html = view('exports.alimentos.OrdenServicio', $data)->render();

        $mpdf->CSSselectMedia = 'mpdf';

        $mpdf->WriteHTML($html);

        $nombreArchivo = $solicitud->Folio ? 'Orden de Servicio_' . $solicitud->Folio . '.pdf' : 'Orden_de_Servicio_Sin_Folio.pdf';

        $mpdf->Output($nombreArchivo, 'I');
    }
    public function getClienteGen()
    {
        $clientesGen = DB::table('viewclientegeneral')
            ->select('Id_cliente', 'Empresa')
            ->orderBy('Id_cliente', 'asc')
            ->get();

        return response()->json($clientesGen);
    }
    public function setSucursal(Request $request)
    {
        $sucursal = DB::table('sucursales_cliente')
            ->where('Id_cliente', $request->id)
            ->select('Id_sucursal', 'Empresa')
            ->orderBy('Id_sucursal', 'asc')
            ->get();

        return response()->json($sucursal);
    }
    public function getDirecciones(Request $request)
    {
        $id = $request->id;
        $dir = DB::table('sucursales_cliente')
            ->where('Id_sucursal', $id)
            ->select('Direccion')
            ->get();

        return response()->json($dir);
    }
    public function getDataContacto(Request $res)
    {

        $contacto = DB::table('sucursal_contactos')
            ->where('Id_contacto', $res->id)->get();

        return response()->json($contacto);
    }
    public  function getDatos(Request $res)
    {
        $contacto = DB::table('sucursal_contactos')
            ->where('Id_contacto', $res->contacto_id)
            ->select('Id_contacto', 'Nombre', 'Departamento', 'Puesto', 'Email', 'Celular', 'Telefono')
            ->get();

        return response()->json($contacto);
    }
    public function getservicios()
    {
        $servicio = DB::table('tipo_servicios')
            ->select('Id_tipo', 'Servicio')
            ->orderBy('Id_tipo', 'asc')
            ->get();

        return response()->json($servicio);
    }
    public function getNormas()
    {
        $norma = DB::table('normas')->where('Id_tipo', '=', 2)
            ->select('Id_norma', 'Norma')
            ->orderBy('Id_norma', 'asc')
            ->get();

        return response()->json($norma);
    }
    public function getSubNorma(Request $request)
    {
        $id = $request->id;

        $subnorma = DB::table('sub_normas')
            ->where('Id_norma', $id)
            ->select('Id_subnorma', 'Clave')
            ->get();

        return response()->json($subnorma);
    }
    public function setContacto(Request $request)
    {
        $request->validate([
            'idSucursal' => 'required|integer',
            'nombre' => 'required|string|max:255',
            'departamento' => 'nullable|string|max:255',
            'puesto' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'telefono' => 'nullable|string|max:20',
            'celular' => 'nullable|string|max:20',
        ]);

        $contactoId = DB::table('sucursal_contactos')->insertGetId([
            'Id_sucursal' => $request->idSucursal,
            'Nombre' => $request->nombre,
            'Departamento' => $request->departamento,
            'Puesto' => $request->puesto,
            'Email' => $request->email,
            'Telefono' => $request->telefono,
            'Celular' => $request->celular,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Devolver una respuesta JSON
        return response()->json([
            'success' => true,
            'message' => 'Contacto creado con éxito.',
            'contacto_id' => $contactoId,
        ]);
    }
    public function editContacto(Request $request)
    {
        $request->validate([
            'idContacto' => 'required|integer',
            'nombre' => 'required|string|max:255',
            'departamento' => 'nullable|string|max:255',
            'puesto' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'telefono' => 'nullable|string|max:20',
            'celular' => 'nullable|string|max:20',
        ]);

        $updated = DB::table('sucursal_contactos')
            ->where('Id_contacto', $request->idContacto)
            ->update([
                'Nombre' => $request->nombre,
                'Departamento' => $request->departamento,
                'Puesto' => $request->puesto,
                'Email' => $request->email,
                'Telefono' => $request->telefono,
                'Celular' => $request->celular,
                'updated_at' => now(),
            ]);

        if ($updated) {
            return response()->json([
                'success' => true,
                'message' => 'Contacto actualizado con éxito.'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No se pudo actualizar el contacto. Verifica que el ID sea correcto.'
            ]);
        }
    }
    public function getinformes()
    {
        $informe = DB::table('solicitudes_alimentos')
            ->select('Id_solicitud', 'Folio', 'Cliente', 'Norma', 'Servicio')
            ->orderBy('Id_solicitud', 'asc')
            ->get();

        return response()->json($informe);
    }
    public function  ImprimirInforme($id)
    {
        $solicitud = SolicitudesAlimentos::with(['contacto', 'servicio'])->where('Id_solicitud', $id)->first();

        $mpdf = new Mpdf([
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

        $mpdf->showWatermarkImage = true;

        $mpdf->SetTitle('Orden de Servicio - Folio ' . ($solicitud->Folio ? $solicitud->Folio : 'Sin Folio'));

        $data = [
            'folio' => $solicitud->Folio ? $solicitud->Folio : 'Sin Folio',
            'cliente' => $solicitud->Cliente ? $solicitud->Cliente : 'No existe un nombre del cliente',
            'direccion' => $solicitud->Direccion ? $solicitud->Direccion : 'No existe una dirección',
            'contacto' => $solicitud->contacto ? $solicitud->contacto->Nombre : 'Sin contacto',
            'numero' => $solicitud->contacto ? $solicitud->contacto->Celular : 'Sin teléfono',
            'email' => $solicitud->contacto ? $solicitud->contacto->Email : 'No hay Email',
            'observacion' => $solicitud->Observacion ? $solicitud->Observacion : 'Sin observaciones',
            'servicio' => $solicitud->servicio ? $solicitud->servicio->Servicio : 'No existe ese servicio',
            'fecha' => $solicitud->Fecha_muestreo ? $solicitud->Fecha_muestreo : 'No hay fecha de muestreo',
            'hora' => $solicitud->Hora_muestreo ? $solicitud->Hora_muestreo : 'No hay hora de muestreo',
        ];

        $html = view('exports.alimentos.InformeDiario', $data)->render();

        $mpdf->CSSselectMedia = 'mpdf';

        $mpdf->WriteHTML($html);

        $nombreArchivo = $solicitud->Folio ? 'Orden de Servicio_' . $solicitud->Folio . '.pdf' : 'Orden_de_Servicio_Sin_Folio.pdf';

        $mpdf->Output($nombreArchivo, 'I');
    }
    public function exportPdfHojaCampo()
    {
        // $solicitud = SolicitudesAlimentos::with(['contacto', 'servicio'])->where('Id_solicitud',)->first();

        $mpdf = new Mpdf([
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

        $mpdf->showWatermarkImage = true;

        // $mpdf->SetTitle('Orden de Servicio - Folio ' . ($solicitud->Folio ? $solicitud->Folio : 'Sin Folio'));

        // $data = [
        //     'folio' => $solicitud->Folio ? $solicitud->Folio : 'Sin Folio',
        //     'cliente' => $solicitud->Cliente ? $solicitud->Cliente : 'No existe un nombre del cliente',
        //     'direccion' => $solicitud->Direccion ? $solicitud->Direccion : 'No existe una dirección',
        //     'contacto' => $solicitud->contacto ? $solicitud->contacto->Nombre : 'Sin contacto',
        //     'numero' => $solicitud->contacto ? $solicitud->contacto->Celular : 'Sin teléfono',
        //     'email' => $solicitud->contacto ? $solicitud->contacto->Email : 'No hay Email',
        //     'observacion' => $solicitud->Observacion ? $solicitud->Observacion : 'Sin observaciones',
        //     'servicio' => $solicitud->servicio ? $solicitud->servicio->Servicio : 'No existe ese servicio',
        //     'fecha' => $solicitud->Fecha_muestreo ? $solicitud->Fecha_muestreo : 'No hay fecha de muestreo',
        //     'hora' => $solicitud->Hora_muestreo ? $solicitud->Hora_muestreo : 'No hay hora de muestreo',
        // ];

        $html = view('exports.alimentos.HojaCampo')->render();

        $mpdf->CSSselectMedia = 'mpdf';

        $mpdf->WriteHTML($html);

        $nombreArchivo  = 'HojaCampo.pdf';

        $mpdf->Output($nombreArchivo, 'I');
    }
    public function exportPdfInforme($id)
    {
        $muestra = SolicitudMuestraA::where('Id_muestra', $id)->first();
        $solicitud = SolicitudesAlimentos::where('Id_solicitud', $muestra->Id_solicitud)->first();
        $proceso = ProcesoAnalisisA::where('Id_solicitud', $id)->first();
        $codigo = DB::table('viewcodigoparametrosalimentos')->where('Id_solicitud', $id)->get();

        $mpdf = new \Mpdf\Mpdf([
            'orientation' => 'P',
            'format' => 'letter',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 30,
            'margin_bottom' => 32,
            'defaultheaderfontstyle' => ['normal'],
            'defaultheaderline' => '0'
        ]);

        $mpdf->SetWatermarkImage(
            asset('/public/storage/MembreteVertical.png'),
            1,
            array(215, 280),
            array(0, 0),
        );

        $mpdf->showWatermarkImage = true;

        $data = array(
            'codigo' => $codigo,
            'muestra' => $muestra,
            'solicitud' => $solicitud,
            'proceso' => $proceso,
        );

        $htmlInforme = view('exports.alimentos.informe.bodyInforme', $data);
        $htmlHeader = view('exports.alimentos.informe.headerInforme', $data);
        $htmlFooter = view('exports.alimentos.informe.footerInforme', $data);
        $mpdf->setHeader("{PAGENO} / {nbpg} <br><br>" . $htmlHeader);
        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
        //Para la proteccion con contraseña, es el segundo parametro de la función SetProtection, el tercer parametro es una contraseña de propietario para permitir más acciones
        //En el caso del ultimo parámetro es la longitud del cifrado
        $mpdf->WriteHTML($htmlInforme);
        $mpdf->CSSselectMedia = 'mpdf';
        $mpdf->Output('Informe de resultados sin comparacion.pdf', 'I');
    }
    public function setSaveMuestra(Request $res)
    {
        $msg = "Datos guardados";
        $muestra = SolicitudMuestraA::where('Id_solicitud', $res->id)->first();
        $muestra->Muestra = $res->muestra;
        $muestra->Id_norma = $res->norma;
        $muestra->save();

        DB::table('solicitud_parametrosa')->where('Id_solicitud', $res->id)->delete();
        foreach ($res->parametros as $item) {
            $model = SolicitudParametrosA::create([
                'Id_muestra' => $res->id,
                'Id_solicitud' => $res->idSol,
                'Id_parametro' => $item,
            ]);
        }
        $data = array(
            'msg' => $msg,
        );
        return response()->json($data);
    }

    // Metodo Opcional Para evitar eliminar y crear solo actualiza y crea datos Faltantes en SolicitudMuestraA Netzair 
    // public function setSaveMuestra(Request $res)
    // {
    //     $msg = "Datos guardados";
    //     $muestra = SolicitudMuestraA::where('Id_solicitud', $res->id)->first();
    //     $muestra->Muestra = $res->muestra;
    //     // $muestra->Id_norma = $res->normas;

    //     $muestra->save();

    //     if (is_array($res->parametros)) {
    //         foreach ($res->parametros as $parametro) {
    //             SolicitudParametrosA::updateOrCreate(
    //                 [
    //                     'Id_muestra' => $muestra->Id_muestra,
    //                     'Id_parametro' => $parametro,
    //                 ],
    //                 [
    //                     'Id_solicitud' => $res->idSol,
    //                 ]
    //             );
    //         }
    //     } else {
    //         return response()->json(['msg' => 'Los parámetros no tienen un formato válido'], 400);
    //     }
    //     $data = array(
    //         'msg' => $msg,
    //     );
    //     return response()->json($data);
    // }

    public function DeleteMuestra(Request $res)
    {

        // Comprobar si el ID existe en las tablas
        $existsInParametros = DB::table('solicitud_parametrosa')->where('Id_muestra', $res->id)->exists();
        $existsInMuestrasa = DB::table('solicitud_muestrasa')->where('Id_muestra', $res->id)->exists();


        // Eliminar registros
        DB::table('solicitud_parametrosa')->where('Id_muestra', $res->id)->delete();
        DB::table('solicitud_muestrasa')->where('Id_muestra', $res->id)->delete();

        return response()->json(['msg' => 'Datos Eliminados']);
    }
    public function setGenFolioSol(Request $res)
    {
        // Verificar si la fecha está presente y es válida
        if (!$res->has('fecha') || empty($res->fecha)) {
            return response()->json(['msg' => 'Fecha no proporcionada', 'folio' => null]);
        }

        $temp = strtotime($res->fecha);

        if ($temp === false) {
            return response()->json(['msg' => 'Fecha inválida', 'folio' => null]);
        }

        $year = date("y", $temp);
        $dayYear = date("z", $temp) + 1;

        // Verificar si ya existe un folio para la cotización
        $cotizacion = SolicitudesAlimentos::find($res->id);
        if ($cotizacion && $cotizacion->Folio_servicio) {
            return response()->json([
                'msg' => 'Ya tiene un folio',
                'folio' => $cotizacion->Folio_servicio
            ]);
        }

        $solDay = SolicitudesAlimentos::where('Fecha_muestreo', $res->fecha)
            ->where('Folio', '!=', '')
            ->count();

        // Construcción del folio
        $folio = $dayYear  . "-" . ($solDay + 1) . "/" . $year . "-A";

        if (empty($folio)) {
            return response()->json(['msg' => 'No se pudo generar el folio', 'folio' => null]);
        }

        if ($cotizacion) {
            $cotizacion->Folio_servicio = $folio;
            $cotizacion->save();
        } else {
            return response()->json(['msg' => 'Cotización no encontrada', 'folio' => null]);
        }

        $cotTemp = SolicitudesAlimentos::where('Id_cotizacion', $res->id)->get();
        if ($cotTemp->count()) {
            $solicitud = SolicitudesAlimentos::find($cotTemp[0]->Id_solicitud);
            if ($solicitud) {
                $solicitud->Folio = $folio;
                $solicitud->save();
            } else {
                return response()->json(['msg' => 'Solicitud no encontrada', 'folio' => null]);
            }
        } else {
            return response()->json(['msg' => 'Solicitud no encontrada en las cotizaciones', 'folio' => null]);
        }

        // Respuesta exitosa con el folio generado
        return response()->json([
            'msg' => "Folio creado correctamente",
            'folio' => $folio,
        ]);
    }
    public function buscarFolio(Request $res)
    {
        $Folio = $res->folio;


        $folio = SolicitudesAlimentos::where('Folio', $Folio)->select('Id_solicitud', 'Folio', 'Cliente', 'Sucursal')
            ->first();
        if (!$folio) {
            return response()->json([
                'folio' => null,
                'muestra' => [],
                'codigos' => [],
                'proceso' => [],
            ]);
        }
        $proceso = ProcesoAnalisisA::where('Folio', $Folio)->select('Hora_recepcion', 'Hora_entrada', 'Ingreso', 'Id_recibio', 'Recibio')->first();

        $muestra = SolicitudMuestraA::where('Id_solicitud', $folio->Id_solicitud)->select('Id_muestra', 'Muestra', 'Tem_muestra', 'Tem_recepcion', 'Observacion')
            ->get();


        $codigos = DB::table('codigo_parametroa')
            ->join('parametros', 'codigo_parametroa.Id_parametro', '=', 'parametros.Id_parametro')
            ->where('codigo_parametroa.Id_solicitud', $folio->Id_solicitud)
            ->select('codigo_parametroa.Codigo', 'parametros.Parametro')
            ->get();

        return response()->json([
            'folio' => $folio,
            'muestra' => $muestra,
            'codigos' => $codigos,
            'proceso' => $proceso,
        ]);
    }
    public function RepAlimentos(Request $res)
    {
        $reg = RecepcionAlimentos::where('Id_rep', $res->idrep)->first();


        if ($reg) {
            // Actualizar los campos
            $reg->Fecha = $res->Fecha;
            $reg->Nombre = $res->Nombre;
            $reg->Id_user = $res->idUsuario;
            $reg->Fecha_resguardo = $res->Fecha2;
            $reg->Resguardo = $res->resRecep;
            $reg->Resguardo2 = $res->resRecep2;
            $reg->Fecha_desecho = $res->Fecha3;
            $reg->Lugar_desecho = $res->Lugardedesecho;
            $reg->Analista_desecho = $res->analistadesecho;
            $reg->save();
            return response()->json(['message' => 'Datos actualizados correctamente.']);
        } else {
            // Si no se encuentra el registro, retornar un error
            return response()->json(['message' => 'No se encontró el registro con ese idrep.'], 404);
        }
    }
    public function ingresar(Request $res)
    {

        $recibio = "";
        if ($res->idRecibe != 0) {
            $temp = Users::where('id', $res->idRecibe)->first();
            $recibio = $temp ? $temp->name : '';
        }

        $registroExistente = ProcesoAnalisisA::where('Id_solicitud', $res->idsol)->first();

        // Si el registro existe, verificamos si han pasado más de 10 minutos desde su creación
        if ($registroExistente && $registroExistente->created_at->diffInMinutes(now()) > 10) {
            return response()->json([
                'message' => 'No puedes actualizar esta solicitud. Han pasado más de 10 minutos desde su creación.',
            ], 403);
        }

        // Si han pasado menos de 10 minutos o no existe el registro, procedemos con la creación o actualización
        $registro = ProcesoAnalisisA::updateOrCreate(
            [
                'Id_solicitud' => $res->idsol,
            ],
            [
                'Folio' => $res->folio,
                'Cliente' => $res->cliente,
                'Empresa' => $res->empresa,
                'Hora_recepcion' => $res->hora_recepcion,
                'Hora_entrada' => $res->hora_entrada,
                'Id_recibio' => $res->idRecibe,
                'Recibio' => $recibio ?: $res->nombreRecibe,
                'Ingreso' => 1,
                'Impresion_informe' => 1,
            ]
        );

        return response()->json([
            'message' => $registro->wasRecentlyCreated
                ? 'La muestra con folio ' . $res->folio . ' se registró correctamente.'
                : 'La muestra con folio ' . $res->folio . ' se actualizó correctamente.',
        ]);
    }
    public function CodigoAlimentos(Request $res)
    {
        // Verifica si ya existen códigos generados para esta solicitud
        $codigoExistente = CodigoParametroA::where('Id_solicitud', $res->idsol)->exists();

        if ($codigoExistente) {
            return response()->json([
                'message' => 'Este Folio ya tiene códigos generados',
            ], 400);
        }

        $solicitud = SolicitudesAlimentos::where('Id_solicitud', $res->idsol)
            ->select('Folio', 'Cliente', 'Sucursal', 'Fecha_muestreo', 'Num_muestras')
            ->first();

        $parametros = SolicitudParametrosA::where('Id_solicitud', $res->idsol)
            ->select('Id_parametro')
            ->get();

        if (!$solicitud || $parametros->isEmpty()) {
            return response()->json(['error' => 'Datos no encontrados'], 404);
        }

        // Crea los registros en el modelo CodigoParametroA
        foreach ($parametros as $parametro) {
            for ($i = 1; $i <= $solicitud->Num_muestras; $i++) {
                CodigoParametroA::create([
                    'Id_solicitud' => $res->idsol,
                    'Id_parametro' => $parametro->Id_parametro,
                    'Codigo' => $solicitud->Folio . '-' . $i,
                    'Num_muestra' => $i,
                    'Asignado' => 0,
                    'Analizo' => 0,
                    'Reporte' => 1,
                    'Cadena' => 1,
                    'Mensual' => 1,
                    'Cancelado' => 0,
                ]);
            }
        }

        // Respuesta si funciona
        return response()->json([
            'message' => 'Códigos creados correctamente',
            'Folio' => $solicitud->Folio,
            'ParametrosCount' => $parametros->count(),
        ]);
    }
    public function getLote(Request $res)
    {
        $aux = array();
        if ($res->folio != "") {
            $temp = DB::table('codigo_parametro')->where('Codigo', 'LIKE', '%' . $res->folio . '%')->where('Id_parametro', $res->id)->first();
            $model = DB::table('ViewLoteAnalisis')->where('Id_lote', $temp->Id_lote)->get();
        } else {
            $model = DB::table('ViewLoteAnalisis')->where('Id_tecnica', $res->id)->where('Fecha', $res->fecha)->get();
        }

        $data = array(
            'aux' => $aux,
            'model' => $model,
        );
        return response()->json($data);
    }
    public function getMuestraSinAsignar(Request $res)
    {
        $hoy = Carbon::now()->toDateString();

        $folio = array();
        $norma = array();
        $punto = array();
        $fecha = array();
        $idCodigo = array();
        $historial = array();
        $fechahoy = array();
        $diasanalisis = array();
        // $lote = DB::table('ViewLoteAnalisis')->where('Id_lote', $res->idLote)->whereYear('created_at', now()->year)->first();
        $lote = DB::table('ViewLoteAnalisis')->where('Id_lote', $res->idLote)->first();
        if ($res->fecha != "") {
        } else {


            $model = DB::table('codigo_parametroa')
                ->where('Asignado', '!=', 1)
                ->where('Id_parametro', $lote->Id_tecnica)
                ->where('Cancelado', '!=', 1)
                ->get();

            for ($i = 0; $i < $model->count(); $i++) {
                $puntoModel = SolicitudMuestraA::where('Id_muestra', $model[$i]->Id_solicitud)->first();
                $proceso = ProcesoAnalisisA::where('Id_solicitud', $model[$i]->Id_solicitud)->get();
                if ($proceso->count()) {
                    array_push($idCodigo, $model[$i]->Id_codigo);
                    array_push($folio, $model[$i]->Codigo);
                    array_push($punto, @$puntoModel->Punto);
                    array_push($fecha, $proceso[0]->Hora_recepcion);
                    array_push($historial, @$model[$i]->Historial);
                    array_push($fechahoy, $hoy);
                    array_push($diasanalisis, @$model[$i]->Dias_analisis);
                }
            }
        }

        $data = array(
            'idCodigo' => $idCodigo,
            'model' => $model,
            'folio' => $folio,
            'norma' => $norma,
            'fecha' => $fecha,
            'punto' => $punto,
            'lote' => $lote,
            'historial' => $historial,
            'fechahoy' => $fechahoy,
            'diasanalisis' => $diasanalisis,
        );
        return response()->json($data);
    }
    public function setMuestraLote(Request $res)
    {

        $lote = LoteAnalisis::where('Id_lote', $res->idLote)->first();

        for ($i = 0; $i < sizeof($res->codigos); $i++) {
            $model = CodigoParametroA::where('Id_codigo', $res->codigos[$i])->first();
            $model->Id_lote = $res->idLote;
            $model->Asignado = 1;
            $model->save();
            switch ($lote->Id_area) {

                default:
                    $temp = LoteDetalleDirectosA::create([
                        'Id_lote' => $res->idLote,
                        'Id_analisis' => $model->Id_solicitud,
                        'Id_codigo' => $model->Id_codigo,
                        'Id_parametro' => $model->Id_parametro,
                        'Id_control' => 1,
                        'Analizo' => 1,
                        'Liberado' => 0,
                    ]);
                    $tempModel = LoteDetalleDirectosA::where('Id_lote', $res->idLote)->get();
                    break;
            }
            $lote->Asignado = $tempModel->count();
            $lote->save();
        }
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function getCapturaLote(Request $res)
    {

        $lote = LoteAnalisis::where('Id_lote', $res->idLote)->get();
        $aux = array();
        $indice = array();
        $valores = array();
        $img = array();
        if ($lote->count()) {
            switch ($lote[0]->Id_area) {

                default:
                    $model = DB::table('viewlotedetalledirectosa')->where('Id_lote', $res->idLote)->get();
                    break;
            }
        } else {
            $model = array();
        }

        $data = array(
            'aux' => $aux,
            'indice' => $indice,
            'model' => $model,
            'lote' => $lote,
        );
        return response()->json($data);
    }
    public function setLiberarTodo(Request $res)
    {
        $sw = false;
        $lote = LoteAnalisis::where('Id_lote', $res->idLote)->get();
        $aux = Parametro::where('Id_parametro', $lote[0]->Id_tecnica)->first();
        $idLibero = Auth::user()->id;
        if ($aux->Usuario_default != 0) {
            $idLibero = $aux->Usuario_default;
        }
        if ($lote->count()) {
            switch ($lote[0]->Id_area) {
                default:
                    $muestras = LoteDetalleDirectosA::where('Id_lote', $res->idLote)->where('Liberado', 0)->get();
                    foreach ($muestras as $item) {
                        $model = LoteDetalleDirectosA::find($item->Id_detalle);
                        $model->Liberado = 1;
                        if (strval($model->Resultado) != null) {
                            $sw = true;
                            $model->save();
                        }
                        if ($item->Id_control == 1) {
                            $modelCod = CodigoParametroA::find($model->Id_codigo);
                            $modelCod->Resultado = $model->Resultado;
                            $modelCod->Resultado2 = $model->Resultado;
                            $modelCod->Analizo = Auth::user()->id;
                            $modelCod->save();
                        }
                    }

                    $model = LoteDetalleDirectosA::where('Id_lote', $res->idLote)->where('Liberado', 1)->get();
                    break;
            }
        }

        $loteModel = LoteAnalisis::find($res->idLote);
        $loteModel->Liberado = $model->count();
        $loteModel->save();


        $data = array(
            'model' => $model,
            'sw' => $sw,
        );
        return response()->json($data);
    }
    public function setLiberar(Request $res)
    {
        $lote = LoteAnalisis::where('Id_lote', $res->idLote)->get();
        $aux = Parametro::where('Id_parametro', $lote[0]->Id_tecnica)->first();
        $idLibero = Auth::user()->id;
        if ($aux->Usuario_default != 0) {
            $idLibero = $aux->Usuario_default;
        }
        switch ($lote[0]->Id_area) {
            default:
                $model = LoteDetalleDirectosA::find($res->idMuestra);
                $model->Liberado = 1;
                if (strval($model->Resultado) != null) {
                    $sw = true;
                    $model->save();
                }

                if ($model->Id_control == 1) {
                    $modelCod = CodigoParametroA::find($model->Id_codigo);
                    $modelCod->Resultado = $model->Resultado;
                    $modelCod->Resultado2 = $model->Resultado;
                    $modelCod->Analizo = $idLibero;
                    $modelCod->save();
                }

                $model = LoteDetalleDirectosA::where('Id_lote', $res->idLote)->where('Liberado', 1)->get();
                break;
        }

        $loteModel = LoteAnalisis::find($res->idLote);
        $loteModel->Liberado = $model->count();
        $loteModel->save();

        $data = array(
            'model' => $model,
            'sw' => $sw,
            // 'muestra' => $res->idMuestra
        );
        return response()->json($data);
    }
    public function supervicion()
    {
        $model = DB::table('solicitudes_alimentos as sol')
            // ->join('intermediarios as inter', 'inter.Id_intermediario', '=', 'sol.Id_intermediario')
            ->join('clientes as cli', 'sol.Id_cliente', '=', 'cli.Id_cliente')
            ->join('sucursales_cliente as suc', 'suc.Id_sucursal', '=', 'sol.Id_sucursal')
            ->join('tipo_servicios as ser', 'sol.Id_servicio', '=', 'ser.Id_tipo')
            // ->join('tipo_descargas as des', 'des.Id_tipo', '=', 'sol.Id_descarga')
            ->join('normas as nor', 'nor.Id_norma', '=', 'sol.Id_norma')
            ->join('sub_normas as sub', 'sub.Id_subnorma', '=', 'sol.Id_subnorma')
            ->join('proceso_analisisa as pro', 'pro.Id_solicitud', '=', 'sol.Id_solicitud')
            ->join('users as creador', 'creador.id', '=', 'pro.Id_user_c')
            ->select(
                'sol.Id_solicitud',
                'sol.Folio',
                'sol.Fecha_muestreo',
                'pro.Hora_recepcion as Fecha_recepcion',
                'suc.Empresa as Empresa_suc',
                // 'sol.Id_intermediario',
                'sub.Norma as Nor_sub',
                'suc.Estado',
                // 'sol.Padre',
                'sol.created_at',
                'sol.updated_at',
                'creador.name as Id_user_c'
            )
            ->orderBy('sol.Id_solicitud', 'desc')
            ->get();

        return view('alimentos.cadena', compact('model'));
    }
    public function detalleCadena($id)
    {
        $swSir = false;
        $model = DB::table('solicitudes_alimentos')->where('Id_solicitud', $id)->first();
        $intermediario = DB::table('ViewIntermediarios')->where('Id_cliente', $model->Id_cliente)->first();
        $proceso = ProcesoAnalisisA::where('Id_solicitud', $id)->first();
        $puntos = SolicitudMuestraA::where('Id_solicitud', $id)->get();

        $data = array(
            'model' => $model,
            'intermediario' => $intermediario,
            'proceso' => $proceso,
            'puntos' => $puntos,
        );

        return view('alimentos.detalleCadena', $data);
    }
    public function getParametroCadena(Request $res)
    {
        $porcentaje = array();
        try {

            $idSolPunto = SolicitudMuestraA::where('Id_solicitud', $res->idPunto)->first();
            $model = DB::table('viewcodigoparametrosalimentos')
                ->where('Id_solicitud', $res->idPunto)
                ->where('Num_muestra', 1)
                ->get();

            $models = DB::table('solicitudes_alimentos')
                ->where('Id_solicitud', $idSolPunto->Id_solicitud)
                ->whereNull('deleted_at')
                ->first();

            // switch ($models->Id_norma) {

            //     default:
            //         foreach ($model as $fila) {
            //             $fila->Limite = 'N/A';
            //         }
            //         break;
            // }

            $tempData = '';
            // foreach ($model as $item) {
            //     switch ($item->Id_parametro) { 
            //         default:
            //             $tempData = "100%";
            //             break;
            //     }
            //     array_push($porcentaje, $tempData);
            // }

            $data = [
                'model' => $model,
                // 'porcentaje' => $porcentaje,
            ];

            return response()->json($data);
        } catch (\Exception $e) {

            return response()->json(['error' => 'No carga los datos'], 500);
        }
    }
    public function getDetalleAnalisis(Request $res)
    {
        $aux = 0;
        $model = array();
        $codigoModel = DB::table('viewcodigoparametrosalimentos')->where('Id_codigo', $res->idCodigo)->first();
        $paraModel = DB::table('ViewParametros')->where('Id_parametro', $codigoModel->Id_parametro)->first();
        switch ($paraModel->Id_parametro) {
            default:
                $model = DB::table('ViewLoteDetalleDirectosA')->where('Id_analisis', $codigoModel->Id_solicitud)
                    ->where('Id_control', 1)
                    ->where('Id_parametro', $codigoModel->Id_parametro)->get();
                break;
        }
        $data = array(
            'aux' => $aux,
            'paraModel' => $paraModel,
            'codigoModel' => $codigoModel,
            'model' => $model,
        );
        return response()->json($data);
    }
    public function regresarMuestra(Request $res)
    {
        $codigoParametro = DB::table('viewcodigoparametrosalimentos')->where('Id_codigo', $res->idCodigo)->first();
        $model = LoteDetalleDirectosA::where('Id_codigo', $res->idCodigo)->get();
        foreach ($model as $item) {
            $item->Liberado = 0;
            $item->save();
        }

        $data = array(
            'idSol' => $res->idSol,
            'idCodigo' => $res->idCodigo,
            'model' => $model,

        );

        return response()->json($data);
    }
    public function desactivarMuestra(Request $res)
    {
        $codigoParametro = DB::table('viewcodigoparametrosalimentos')->where('Id_codigo', $res->idCodigo)->first();
        $model = CodigoParametroA::where('Id_codigo', $res->idCodigo)->first();
        $model->Cadena = 0;
        $model->Reporte = 0;
        $model->Mensual = 0;
        $model->save();

        $data = array(
            "model" => $model,

        );
        return response()->json($data);
    }
    public function getbitacoras(Request $request)
    {
        // Validar que Fini y Ffin existan
        $request->validate([
            'Fini' => 'required|date',
            'Ffin' => 'required|date|after_or_equal:Fini',
        ]);

        // Inicializar el array para los IDs
        $idsol = [];


        $Proceso = ProcesoAnalisisA::whereBetween('Hora_recepcion', [$request->Fini, $request->Ffin])->with('user')->get();
        // Obtener registros dentro del rango de fechas
        // $Proceso = DB::table('Proceso_analisisa')->whereBetween('Hora_recepcion', [$request->Fini, $request->Ffin])->get();
        // Extraer los "Id_solicitud" y almacenarlos en $idsol
        $idsol = $Proceso->pluck('Id_solicitud')->toArray();
        $Solicitud = SolicitudesAlimentos::where('Id_solicitud', $idsol)->with('dir')->get();
        // $Solicitud=DB::table('solicitudes_alimentos')->where('Id_solicitud',$idsol)->get();
        // $Muestra= DB::table('solicitud_muestrasa')->where('Id_solicitud',$idsol)->get();
        $Muestra = SolicitudMuestraA::where('Id_solicitud', $idsol)->get();
        $Parametro = SolicitudParametrosA::whereIn('Id_solicitud', $idsol)->with('par')->get();


        return response()->json([
            'success' => true,
            'Proceso' => $Proceso,
            'Solicitud' => $Solicitud,
            'Muestra' => $Muestra,
            'Parametro' => $Parametro,
        ]);
    }
    public function UpdateMuestra(Request $res)
    {
        $muestra = SolicitudMuestraA::where('Id_muestra', $res->Id_muestra)->first();

        if (!$muestra) {
            return response()->json(['error' => 'Muestra no encontrada'], 404);
        }

        $muestra->update([
            'Muestra' => $res->muestra,
            'Tem_muestra' => $res->tem_muestra,
            'Tem_recepcion' => $res->tem_recepcion,
            'Observacion' => $res->observacion

        ]);

        // Responder con un mensaje de éxito
        return response()->json(['success' => 'Muestra  Actualizada Correctamente']);
    }
    public function getRecepcionAli()
    {
        // $datos = RecepcionAlimentos::select('Id_rep', 'Folio', 'Fecha', 'Nombre', 'Hora_recepcion', 'Recibio','Id_user')->get();
        $datos = RecepcionAlimentos::all();

        return response()->json($datos);
    }
    public function getRecepcion(Request $res)
    {
        // Obtener los datos de la base de datos
        $REPALI = RecepcionAlimentos::where('Id_rep', $res->idRep)->select('Fecha', 'Nombre', 'Folio', 'Id_rep', 'Fecha_resguardo', 'Resguardo', 'Resguardo2', 'Fecha_desecho', 'Analista_desecho', 'Lugar_desecho', 'Id_user')->first();

        // Retornar los datos como respuesta JSON
        return response()->json($REPALI);
    }
    public function setDetalleMuestra(Request $res)
    {
        $r2 = 0;
        $std = true;
        $tipo = 0;
        $resultado = 0;
        $aux = array();
        $model = array();
        $lote = LoteAnalisis::where('Id_lote', $res->idLote)->get();
        if ($lote->count()) {
            switch ($lote[0]->Id_area) {
                case 16: // Espectrofotometria
                case 5: // Fisicoquimicos
                    switch ($lote[0]->Id_tecnica) {
                        case 152: // COT
                            $model = LoteDetalleEspectro::where('Id_detalle', $res->idMuestra)->first();
                            $dilucion = 40 / $res->E;
                            $promedio = ($res->X + $res->Y + $res->Z) / 3;
                            switch ($model->Id_control) {
                                case 14: //estandar de verificación
                                    $resultado = ((($promedio - $res->CB) / $res->CM) * $dilucion);
                                    break;
                                case 5: // blanco
                                    $resultado = ($res->X + $res->Y + $res->Z) / 3;
                                    break;
                                default:
                                    $resultado = ((($promedio - $res->CA) / $res->CM) * $dilucion);
                                    break;
                            }
                            $model = LoteDetalleEspectro::find($res->idMuestra);
                            $model->Resultado = $resultado;
                            $model->Abs1 = $res->X;
                            $model->Abs2 = $res->Y;
                            $model->Abs3 = $res->Z;
                            $model->B = $res->CB;
                            $model->M = $res->CM;
                            $model->R = $res->CR;
                            $model->Promedio = number_format($promedio, 3, '.', '');
                            $model->Vol_dilucion = $dilucion;
                            $model->Vol_muestra = $res->E;
                            $model->Blanco = $res->CA;
                            $model->Analizo = Auth::user()->id;
                            $model->save();
                            break;
                        case 69:
                            # Cromo Hexavalente
                            $d =  $res->CM;
                            $x = ($res->X + $res->Y + $res->Z) / 3;
                            $r1 = ($x - $res->CB) / $d;
                            $r2 = 100 / $res->E;
                            $resultado = $r1 * $r2;

                            $model = LoteDetalleEspectro::find($res->idMuestra);
                            $model->Resultado = $resultado;
                            $model->Abs1 = $res->X;
                            $model->Abs2 = $res->Y;
                            $model->Abs3 = $res->Z;
                            $model->B = $res->CB;
                            $model->M = $res->CM;
                            $model->R = $res->CR;
                            $model->Ph_ini = $res->phIni;
                            $model->Ph_fin = $res->phFin;
                            $model->Promedio = $x;
                            //$model->Vol_dilucion = round($d,3);
                            $model->Vol_dilucion = $r2;
                            $model->Vol_muestra = $res->E;
                            $model->Blanco = $res->CA;
                            $model->Analizo = Auth::user()->id;
                            $model->save();
                            break;
                        case 19:
                        case 99:
                        case 118:
                            # Cianuros
                            $d = 500 * $res->E;
                            $x = ($res->X + $res->Y + $res->Z) / 3;
                            $r1 = ($x - $res->CB) / $res->CM;
                            $resultado = ($r1 * 12500) / $d;

                            $model = LoteDetalleEspectro::find($res->idMuestra);
                            $model->Resultado = round($resultado, 3);
                            $model->Abs1 = $res->X;
                            $model->Abs2 = $res->Y;
                            $model->Abs3 = $res->Z;
                            $model->B = $res->CB;
                            $model->M = $res->CM;
                            $model->R = $res->CR;
                            $model->Nitratos = $res->nitratos;
                            $model->Nitritos = $res->nitritos;
                            $model->Sulfuros = $res->sulfuros;
                            $model->Promedio = $x;
                            $model->Vol_dilucion = $d;
                            $model->Vol_muestra = $res->E;
                            $model->Blanco = $res->CA;
                            $model->Analizo = Auth::user()->id;
                            $model->save();
                            break;
                        case 114:
                        case 96:
                        case 124:
                            # Sustancias activas al Azul de Metileno
                            $x = ($res->X + $res->Y + $res->Z) / 3;
                            $r1 = (round($x, 3) - $res->CB) / $res->CM;
                            $r2 = 1000 / $res->E;
                            $resultado = $r1 * $r2;
                            $d = $r2;

                            $model = LoteDetalleEspectro::find($res->idMuestra);
                            $model->Resultado = $resultado;
                            $model->Abs1 = $res->X;
                            $model->Abs2 = $res->Y;
                            $model->Abs3 = $res->Z;
                            $model->B = $res->CB;
                            $model->M = $res->CM;
                            $model->R = $res->CR;
                            $model->Promedio = $x;
                            $model->Vol_dilucion = $d;
                            $model->Vol_muestra = $res->E;
                            $model->Blanco = $res->CA;
                            $model->Analizo = Auth::user()->id;
                            $model->save();
                            break;
                        case 15:
                        case 38: //ORTOFOSFATO
                            # Fosforo-Total 
                            $d = 100 / $res->E;
                            $x = round((($res->X + $res->Y + $res->Z) / 3), 3);
                            $resultado = (($x - $res->CB) / $res->CM) * $d;

                            $model = LoteDetalleEspectro::find($res->idMuestra);
                            $model->Resultado = round($resultado, 3);
                            $model->Abs1 = $res->X;
                            $model->Abs2 = $res->Y;
                            $model->Abs3 = $res->Z;
                            $model->B = $res->CB;
                            $model->M = $res->CM;
                            $model->R = $res->CR;
                            $model->Promedio = $x;
                            $model->Vol_dilucion = $d;
                            $model->Vol_muestra = $res->E;
                            $model->Blanco = $res->CA;
                            $model->Analizo = Auth::user()->id;
                            $model->save();
                            break;
                        case 117:
                        case 222:
                            # Boro (B) 
                            $x = ($res->X + $res->Y + $res->Z) / 3;
                            $d = 1 / $res->E;
                            $resultado = ((round($x, 3) - $res->CB) / $res->CM) * round($d, 3);

                            $model = LoteDetalleEspectro::find($res->idMuestra);
                            $model->Resultado = round($resultado, 3);
                            $model->Abs1 = $res->X;
                            $model->Abs2 = $res->Y;
                            $model->Abs3 = $res->Z;
                            $model->B = $res->CB;
                            $model->M = $res->CM;
                            $model->R = $res->CR;
                            $model->Promedio = $x;
                            $model->Vol_dilucion = $d;
                            $model->Vol_muestra = $res->E;
                            $model->Blanco = $res->CA;
                            $model->Analizo = Auth::user()->id;
                            $model->save();
                            break;
                        case 7:
                        case 122:
                            # N-Nitratos
                            $d = 10 / $res->E;
                            $x = ($res->X + $res->Y + $res->Z) / 3;
                            $resultado = ((round($x, 3) - $res->CB) / $res->CM) * $d;

                            $model = LoteDetalleEspectro::find($res->idMuestra);
                            $model->Resultado = $resultado;
                            $model->Abs1 = $res->X;
                            $model->Abs2 = $res->Y;
                            $model->Abs3 = $res->Z;
                            $model->B = $res->CB;
                            $model->M = $res->CM;
                            $model->R = $res->CR;
                            $model->Promedio = $x;
                            $model->Vol_dilucion = $d;
                            $model->Vol_muestra = $res->E;
                            $model->Blanco = $res->CA;
                            $model->Analizo = Auth::user()->id;
                            $model->save();
                            break;
                        case 8:
                        case 107:
                            # N-nitritos
                            $d = 50 / $res->E;
                            $x = ($res->X + $res->Y + $res->Z) / 3;
                            $resultado = ((($x - $res->CB) / $res->CM) * $d);

                            $model = LoteDetalleEspectro::find($res->idMuestra);
                            $model->Resultado = $resultado;
                            $model->Abs1 = $res->X;
                            $model->Abs2 = $res->Y;
                            $model->Abs3 = $res->Z;
                            $model->B = $res->CB;
                            $model->M = $res->CM;
                            $model->R = $res->CR;
                            $model->Promedio = $x;
                            $model->Vol_dilucion = $d;
                            $model->Vol_muestra = $res->E;
                            $model->Blanco = $res->CA;
                            $model->Analizo = Auth::user()->id;
                            $model->save();
                            break;
                        case 106:
                            # N-nitratos (potable)
                            $d = 10 / $res->E;
                            $x = ($res->X + $res->Y + $res->Z) / 3;
                            $xround = round($x, 3);
                            $resultado = ((($xround - $res->CB) / $res->CM) * $d);

                            $model = LoteDetalleEspectro::find($res->idMuestra);
                            $model->Resultado = $resultado;
                            $model->Abs1 = $res->X;
                            $model->Abs2 = $res->Y;
                            $model->Abs3 = $res->Z;
                            $model->B = $res->CB;
                            $model->M = $res->CM;
                            $model->R = $res->CR;
                            $model->Promedio = $x;
                            $model->Vol_dilucion = $d;
                            $model->Vol_muestra = $res->E;
                            $model->Blanco = $res->CA;
                            $model->Analizo = Auth::user()->id;
                            $model->save();
                            break;
                        case 103: //Dureza
                            $x = $res->A - $res->B;
                            $d = ($x * $res->RE) * 1000;
                            $resultado = $d / $res->D;

                            $model = LoteDetalleEspectro::find($res->idMuestra);
                            $model->Resultado = round($resultado, 2);
                            $model->Abs1 = $res->X;
                            $model->Abs2 = $res->Y;
                            $model->Abs3 = $res->Z;
                            $model->B = $res->CB;
                            $model->M = $res->CM;
                            $model->R = $res->CR;
                            $model->Promedio = $x;
                            $model->Vol_dilucion = $d;
                            $model->Vol_muestra = $res->E;
                            $model->Blanco = $res->CA;
                            $model->Analizo = Auth::user()->id;
                            $model->save();
                            break;
                        case 105: //Fluoruros (potable)
                        case 121:
                            $x = ($res->X + $res->Y + $res->Z) / 3;
                            $d =  50 / $res->E;
                            $xround = round($x, 3);
                            $resultado = (($xround - $res->CB) / $res->CM) * round($d, 3);

                            $model = LoteDetalleEspectro::find($res->idMuestra);
                            $model->Resultado = round($resultado, 3);
                            $model->Abs1 = $res->X;
                            $model->Abs2 = $res->Y;
                            $model->Abs3 = $res->Z;
                            $model->B = $res->CB;
                            $model->M = $res->CM;
                            $model->R = $res->CR;
                            $model->Promedio = $x;
                            $model->Vol_dilucion = $d;
                            $model->Vol_muestra = $res->E;
                            $model->Blanco = $res->CA;
                            $model->Analizo = Auth::user()->id;
                            $model->save();
                            break;

                        case 113:
                            // Sulfatos Residual
                            $x = ($res->X + $res->Y + $res->Z + $res->ABS4 + $res->ABS5 + $res->ABS6 + $res->ABS7 + $res->ABS8) / 8;
                            $d =   100  / $res->E;
                            $res1 = round($x, 3) - ($res->CB);
                            $res2 = $res1 / $res->CM;
                            $resultado = round($res2, 4) * round($d, 3);

                            $model = LoteDetalleEspectro::find($res->idMuestra);
                            $model->Resultado = round($resultado, 3);
                            $model->Abs1 = $res->X;
                            $model->Abs2 = $res->Y;
                            $model->Abs3 = $res->Z;
                            $model->Abs4 = $res->ABS4;
                            $model->Abs5 = $res->ABS5;
                            $model->Abs6 = $res->ABS6;
                            $model->Abs7 = $res->ABS7;
                            $model->Abs8 = $res->ABS8;
                            $model->B = $res->CB;
                            $model->M = $res->CM;
                            $model->R = $res->CR;
                            $model->Promedio = $x;
                            $model->Vol_dilucion = $d;
                            $model->Vol_muestra = $res->E;
                            $model->Blanco = $res->CA;
                            $model->Analizo = Auth::user()->id;
                            $model->save();
                            break;
                        case 95:
                            // Sulfatos Potable 
                            $val1 = $res->X2 - $res->X;
                            $val2 = $res->Y2 - $res->Y;
                            $val3 = $res->Z2 - $res->Z;
                            $prom1 = ($res->X + $res->Y + $res->Z) / 3;
                            $prom2 = ($res->X2 + $res->Y2 + $res->Z2) / 3;
                            $x = ($val1 + $val2 + $val3) / 3;
                            $d =   100  / $res->E;
                            $res1 = round($x, 3) - ($res->CB);
                            $res2 = $res1 / $res->CM;
                            $resultado = round($res2, 3) * round($d, 3);

                            $model = LoteDetalleEspectro::find($res->idMuestra);
                            $model->Resultado = round($resultado, 4);
                            $model->Abs1 = $res->X;
                            $model->Abs2 = $res->Y;
                            $model->Abs3 = $res->Z;
                            $model->Abs4 = $res->X2;
                            $model->Abs5 = $res->Y2;
                            $model->Abs6 = $res->Z2;
                            $model->Abs7 = round($prom1, 3);
                            $model->Abs8 = round($prom2, 3);
                            $model->B = $res->CB;
                            $model->M = $res->CM;
                            $model->R = $res->CR;
                            $model->Promedio = round($x, 3);
                            $model->Vol_dilucion = $d;
                            $model->Vol_muestra = $res->E;
                            $model->Blanco = $res->CA;
                            $model->Analizo = Auth::user()->id;
                            $model->save();
                            break;
                        case 79:
                            $promedio = ($res->X + $res->Y + $res->Z) / 3;
                            $dilucion =  500 / $res->E;
                            $resultado = (($promedio - $res->CB) / $res->CM) * $dilucion;

                            $model = LoteDetalleEspectro::find($res->idMuestra);
                            $model->Resultado = round($resultado, 3);
                            $model->Abs1 = $res->X;
                            $model->Abs2 = $res->Y;
                            $model->Abs3 = $res->Z;
                            $model->B = $res->CB;
                            $model->M = $res->CM;
                            $model->R = $res->CR;
                            $model->Promedio = $promedio;
                            $model->Vol_dilucion = $dilucion;
                            $model->Vol_muestra = $res->E;
                            $model->Blanco = $res->CA;
                            $model->Analizo = Auth::user()->id;
                            $model->save();
                            break;
                        case 87:
                            $promedio = round(($res->X + $res->Y + $res->Z) / 3, 3);
                            $dilucion =  50 / $res->E;
                            $resultado = (($promedio - $res->CB) / $res->CM) * $dilucion;

                            $model = LoteDetalleEspectro::find($res->idMuestra);
                            $model->Resultado = round($resultado, 3);
                            $model->Abs1 = $res->X;
                            $model->Abs2 = $res->Y;
                            $model->Abs3 = $res->Z;
                            $model->B = $res->CB;
                            $model->M = $res->CM;
                            $model->R = $res->CR;
                            $model->Promedio = $promedio;
                            $model->Vol_dilucion = $dilucion;
                            $model->Vol_muestra = $res->E;
                            $model->Blanco = $res->CA;
                            $model->Analizo = Auth::user()->id;
                            $model->save();
                            break;
                        case 80: //floruros
                            $promedio = round(($res->X + $res->Y + $res->Z) / 3, 3);
                            $dilucion =  50 / $res->E;
                            $resultado = (($promedio - $res->CB) / $res->CM) * $dilucion;

                            $model = LoteDetalleEspectro::find($res->idMuestra);
                            $model->Resultado = round($resultado, 3);
                            $model->Abs1 = $res->X;
                            $model->Abs2 = $res->Y;
                            $model->Abs3 = $res->Z;
                            $model->B = $res->CB;
                            $model->M = $res->CM;
                            $model->R = $res->CR;
                            $model->Promedio = $promedio;
                            $model->Vol_dilucion = $dilucion;
                            $model->Vol_muestra = $res->E;
                            $model->Blanco = $res->CA;
                            $model->Analizo = Auth::user()->id;
                            $model->save();
                            break;

                        default:
                            $promedio = ($res->X + $res->Y + $res->Z) / 3;
                            $dilucion =  50 / $res->E;
                            $resultado = (($promedio - $res->CB) / $res->CM) * $dilucion;

                            $model = LoteDetalleEspectro::find($res->idMuestra);
                            $model->Resultado = round($resultado, 3);
                            $model->Abs1 = $res->X;
                            $model->Abs2 = $res->Y;
                            $model->Abs3 = $res->Z;
                            $model->B = $res->CB;
                            $model->M = $res->CM;
                            $model->R = $res->CR;
                            $model->Promedio = $promedio;
                            $model->Vol_dilucion = $dilucion;
                            $model->Vol_muestra = $res->E;
                            $model->Blanco = $res->CA;
                            $model->Analizo = Auth::user()->id;
                            $model->save();
                            break;
                    }
                    break;
                case 13: //G&A
                    if ($res->R != '') {
                        $matraz = MatrazGA::where('Estado', 0)->get();
                        $aux = $matraz;
                        if ($matraz->count()) {
                            regresar:
                            $mat = rand(0, $matraz->count());
                            $valMatraz = LoteDetalleGA::where('Id_matraz', $matraz[$mat]->Id_matraz)->where('Id_lote', $res->idLote)->get();
                            if ($valMatraz->count()) {
                                goto regresar;
                            } else {

                                $matraz[$mat]->Estado = 1;
                                $matraz[$mat]->save();
                            }
                        } else {
                            $std = false;
                        }

                        //$m3 = mt_rand($matraz->Min, $matraz->Max);
                        $dif = ($matraz[$mat]->Max - $matraz[$mat]->Min);
                        $ran = (round($dif, 4)) / 10;
                        $m3 = $matraz[$mat]->Max - $ran;

                        $mf = ((($res->R / $res->E) * $res->I) + $m3);


                        $numeroAleatorio = rand(1, 2); // Genera un número entre 1 y 5
                        $valRandom = number_format($numeroAleatorio / 10000, 4); // Divide por 1000 para obtener el rango deseado
                        $m2 = ($m3 + $valRandom);

                        $numeroAleatorio = rand(1, 3); // Genera un número entre 1 y 5
                        $valRandom = number_format($numeroAleatorio / 10000, 4); // Divide por 1000 para obtener el rango deseado
                        $m1 = ($m2 + $valRandom);


                        $auxMf = ((round($mf, 4) - $m3) / $res->I) * $res->E;
                        $resultado = $auxMf - $res->G;

                        $model = LoteDetalleGA::find($res->idMuestra);
                        $model->Id_matraz = $matraz[$mat]->Id_matraz;
                        $model->Matraz = $matraz[$mat]->Num_serie;
                        $model->M_final = round($mf, 4);
                        $model->M_inicial1 = $m1;
                        $model->M_inicial2 = $m2;
                        $model->M_inicial3 = $m3;
                        $model->Ph = $res->L;
                        $model->Blanco = $res->G;
                        $model->F_conversion = $res->E;
                        $model->Vol_muestra = $res->I;
                        $model->Resultado = round($resultado, 2);
                        $model->Analizo = Auth::user()->id;
                        $model->save();
                    } else {
                        $res1 = $res->H - $res->C;
                        $res2 = $res1 / $res->I;
                        $res3 = $res2 * $res->E;
                        $resultado = $res3 - $res->G;

                        $matraz = MatrazGA::where('Num_serie', $res->P)->first();

                        $model = LoteDetalleGA::find($res->idMuestra);
                        $model->M_final = $res->H;
                        $model->Id_matraz = $matraz->Id_matraz;
                        $model->Matraz = $matraz->Num_serie;
                        $model->M_inicial1 = $res->J;
                        $model->M_inicial2 = $res->K;
                        $model->M_inicial3 = $res->C;
                        $model->Ph = $res->L;
                        $model->Blanco = $res->G;
                        $model->F_conversion = $res->E;
                        $model->Vol_muestra = $res->I;
                        $model->Resultado = $resultado;
                        $model->Analizo = Auth::user()->id;
                        $model->save();
                    }
                    break;
                case 15: // Solidos
                    switch ($lote[0]->Id_tecnica) {
                        case 3: // Directos
                            $model = LoteDetalleSolidos::find($res->idMuestra);
                            $model->Resultado = $res->resultado;
                            $model->Inmhoff = $res->inmhoff;
                            $model->Temp_muestraLlegada = $res->temperaturaLlegada;
                            $model->Temp_muestraAnalizada = $res->temperaturaAnalizada;
                            $model->Observacion = $res->obs;
                            $model->save();
                            $resultado = $res->resultado;
                            break;
                        case 47: // Por diferencia
                        case 88:
                        case 44:
                        case 45:
                        case 43:
                            $model = LoteDetalleSolidos::find($res->idMuestra);
                            $model->Resultado = $res->resultado;
                            $model->Masa1 = $res->val1;
                            $model->Masa2 = $res->val2;
                            $model->Observacion = $res->obs;
                            $model->Analizo = Auth::user()->id;
                            $model->save();
                            $resultado = $res->resultado;
                            break;
                        case 4:
                            if ($res->R != "") {

                                $modelCrisol = CrisolesGA::where('Estado', 0)->get();
                                $aux = $modelCrisol;

                                if ($modelCrisol->count()) {
                                    $mat = rand(0, $modelCrisol->count());
                                    $valCrisol = LoteDetalleSolidos::where('Id_crisol', $modelCrisol[$mat]->Id_matraz)->where('Id_lote', $res->idLote)->get();
                                    if ($valCrisol->count()) {
                                        goto regresar;
                                    } else {

                                        $modelCrisol[$mat]->Estado = 1;
                                        $modelCrisol[$mat]->save();
                                    }
                                } else {
                                    $std = false;
                                }

                                $mf = ((($res->R / $res->factor) * $res->volumen) + round($modelCrisol[$mat]->Peso, 4));
                                $auxMf =  (((round($mf, 4) - round($modelCrisol[$mat]->Peso, 4)) / $res->volumen) * $res->factor);
                                $resultado = $auxMf;

                                $numeroAleatorio = rand(0, 3); // Genera un número entre 1 y 5
                                $valRandomPm1 = number_format($numeroAleatorio / 10000, 4); // Divide por 1000 para obtener el rango deseado
                                $numeroAleatorio = rand(0, 3); // Genera un número entre 1 y 5
                                $valRandomPm2 = number_format($numeroAleatorio / 10000, 4); // Divide por 1000 para obtener el rango deseado
                                $numeroAleatorio = rand(0, 3); // Genera un número entre 1 y 5
                                $valRandomPc1 = number_format($numeroAleatorio / 10000, 4); // Divide por 1000 para obtener el rango deseado
                                $numeroAleatorio = rand(0, 3); // Genera un número entre 1 y 5
                                $valRandomPc2 = number_format($numeroAleatorio / 10000, 4); // Divide por 1000 para obtener el rango deseado

                                $model = LoteDetalleSolidos::find($res->idMuestra);
                                $model->Id_crisol = $modelCrisol[$mat]->Id_crisol;
                                $model->Crisol = $modelCrisol[$mat]->Num_serie;
                                $model->Masa1 = round($modelCrisol[$mat]->Peso, 4);
                                $model->Masa2 = round($mf, 4);
                                $model->Peso_muestra1 = round(($modelCrisol[$mat]->Peso + $valRandomPm1), 4);
                                $model->Peso_muestra2 = round(($modelCrisol[$mat]->Peso + $valRandomPm2), 4);
                                $model->Peso_constante1 = round(($mf + $valRandomPc1), 4);
                                $model->Peso_constante2 = round(($mf + $valRandomPc2), 4);
                                $model->Vol_muestra = $res->volumen;
                                $model->Factor_conversion = $res->factor;
                                $model->Resultado = $resultado;
                                $model->Analizo = Auth::user()->id;
                                $model->Observacion = $res->obs;
                                $model->save();
                            } else { //operacion larga
                                $res1 = $res->masa2 - $res->masa1;
                                $res2 = $res1 / $res->volumen;
                                $resultado = $res2 * $res->factor;

                                $model = LoteDetalleSolidos::find($res->idMuestra);
                                $model->Crisol = $res->crisol;
                                $model->Masa1 = $res->masa1;
                                $model->Masa2 = $res->masa2;
                                $model->Peso_muestra1 = $res->pesoC1;
                                $model->Peso_muestra2 = $res->pesoC2;
                                $model->Peso_constante1 = $res->pesoConMuestra1;
                                $model->Peso_constante2 = $res->pesoConMuestra2;
                                $model->Vol_muestra = $res->volumen;
                                $model->Factor_conversion = $res->factor;
                                $model->Resultado = $resultado;
                                $model->Observacion = $res->obs;
                                $model->Analizo = Auth::user()->id;
                                $model->save();
                            }
                            break;
                        case 90:
                            if ($res->R != "") {

                                $modelCrisol = Capsulas::where('Estado', 0)->get();
                                $aux = $modelCrisol;

                                if ($modelCrisol->count()) {
                                    $mat = rand(0, $modelCrisol->count());
                                    $valCrisol = LoteDetalleSolidos::where('Id_crisol', $modelCrisol[$mat]->Id_capsula)->where('Id_lote', $res->idLote)->get();
                                    if ($valCrisol->count()) {
                                        goto regresar;
                                    } else {

                                        $modelCrisol[$mat]->Estado = 1;
                                        $modelCrisol[$mat]->save();
                                    }
                                } else {
                                    $std = false;
                                }

                                $mf = ((($res->R / $res->factor) * $res->volumen) + round($modelCrisol[$mat]->Peso, 4));
                                $auxMf =  (((round($mf, 4) - round($modelCrisol[$mat]->Peso, 4)) / $res->volumen) * $res->factor);
                                $resultado = $auxMf;

                                $numeroAleatorio = rand(0, 3); // Genera un número entre 1 y 5
                                $valRandomPm1 = number_format($numeroAleatorio / 10000, 4); // Divide por 1000 para obtener el rango deseado
                                $numeroAleatorio = rand(0, 3); // Genera un número entre 1 y 5
                                $valRandomPm2 = number_format($numeroAleatorio / 10000, 4); // Divide por 1000 para obtener el rango deseado
                                $numeroAleatorio = rand(0, 3); // Genera un número entre 1 y 5
                                $valRandomPc1 = number_format($numeroAleatorio / 10000, 4); // Divide por 1000 para obtener el rango deseado
                                $numeroAleatorio = rand(0, 3); // Genera un número entre 1 y 5
                                $valRandomPc2 = number_format($numeroAleatorio / 10000, 4); // Divide por 1000 para obtener el rango deseado

                                $model = LoteDetalleSolidos::find($res->idMuestra);
                                $model->Id_crisol = $modelCrisol[$mat]->Id_crisol;
                                $model->Crisol = $modelCrisol[$mat]->Num_serie;
                                $model->Masa1 = round($modelCrisol[$mat]->Peso, 4);
                                $model->Masa2 = round($mf, 4);
                                $model->Peso_muestra1 = round(($modelCrisol[$mat]->Peso + $valRandomPm1), 4);
                                $model->Peso_muestra2 = round(($modelCrisol[$mat]->Peso + $valRandomPm2), 4);
                                $model->Peso_constante1 = round(($mf + $valRandomPc1), 4);
                                $model->Peso_constante2 = round(($mf + $valRandomPc2), 4);
                                $model->Vol_muestra = $res->volumen;
                                $model->Factor_conversion = $res->factor;
                                $model->Resultado = $resultado;
                                $model->Analizo = Auth::user()->id;
                                $model->Observacion = $res->obs;
                                $model->save();
                            } else { //operacion larga
                                $res1 = $res->masa2 - $res->masa1;
                                $res2 = $res1 / $res->volumen;
                                $resultado = $res2 * $res->factor;

                                $model = LoteDetalleSolidos::find($res->idMuestra);
                                $model->Crisol = $res->crisol;
                                $model->Masa1 = $res->masa1;
                                $model->Masa2 = $res->masa2;
                                $model->Peso_muestra1 = $res->pesoC1;
                                $model->Peso_muestra2 = $res->pesoC2;
                                $model->Peso_constante1 = $res->pesoConMuestra1;
                                $model->Peso_constante2 = $res->pesoConMuestra2;
                                $model->Vol_muestra = $res->volumen;
                                $model->Factor_conversion = $res->factor;
                                $model->Resultado = $resultado;
                                $model->Analizo = Auth::user()->id;
                                $model->Observacion = $res->obs;
                                $model->save();
                            }
                            break;
                        case 46:
                            if ($res->R != "") {

                                $modelCrisol = CrisolesGA::where('Estado', 0)->get();
                                $aux = $modelCrisol;

                                if ($modelCrisol->count()) {
                                    $mat = rand(0, $modelCrisol->count());
                                    $valCrisol = LoteDetalleSolidos::where('Id_crisol', $modelCrisol[$mat]->Id_matraz)->where('Id_lote', $res->idLote)->get();
                                    if ($valCrisol->count()) {
                                        goto regresar;
                                    } else {

                                        $modelCrisol[$mat]->Estado = 1;
                                        $modelCrisol[$mat]->save();
                                    }
                                } else {
                                    $std = false;
                                }

                                $mf = ((($res->R / $res->factor) * $res->volumen) + round($modelCrisol[$mat]->Peso, 4));
                                $auxMf =  (((round($mf, 4) - round($modelCrisol[$mat]->Peso, 4)) / $res->volumen) * $res->factor);
                                $resultado = $auxMf;

                                $numeroAleatorio = rand(0, 3); // Genera un número entre 1 y 5
                                $valRandomPm1 = number_format($numeroAleatorio / 10000, 4); // Divide por 1000 para obtener el rango deseado
                                $numeroAleatorio = rand(0, 3); // Genera un número entre 1 y 5
                                $valRandomPm2 = number_format($numeroAleatorio / 10000, 4); // Divide por 1000 para obtener el rango deseado
                                $numeroAleatorio = rand(0, 3); // Genera un número entre 1 y 5
                                $valRandomPc1 = number_format($numeroAleatorio / 10000, 4); // Divide por 1000 para obtener el rango deseado
                                $numeroAleatorio = rand(0, 3); // Genera un número entre 1 y 5
                                $valRandomPc2 = number_format($numeroAleatorio / 10000, 4); // Divide por 1000 para obtener el rango deseado

                                $model = LoteDetalleSolidos::find($res->idMuestra);
                                $model->Id_crisol = $modelCrisol[$mat]->Id_crisol;
                                $model->Crisol = $modelCrisol[$mat]->Num_serie;
                                $model->Masa1 = round($modelCrisol[$mat]->Peso, 4);
                                $model->Masa2 = round($mf, 4);
                                $model->Peso_muestra1 = round(($modelCrisol[$mat]->Peso + $valRandomPm1), 4);
                                $model->Peso_muestra2 = round(($modelCrisol[$mat]->Peso + $valRandomPm2), 4);
                                $model->Peso_constante1 = round(($mf + $valRandomPc1), 4);
                                $model->Peso_constante2 = round(($mf + $valRandomPc2), 4);
                                $model->Vol_muestra = $res->volumen;
                                $model->Factor_conversion = $res->factor;
                                $model->Resultado = $resultado;
                                $model->Analizo = Auth::user()->id;
                                $model->Observacion = $res->obs;
                                $model->save();
                            } else { //operacion larga
                                $res1 = $res->masa2 - $res->masa1;
                                $res2 = $res1 / $res->volumen;
                                $resultado = $res2 * $res->factor;

                                $model = LoteDetalleSolidos::find($res->idMuestra);
                                $model->Crisol = $res->crisol;
                                $model->Masa1 = $res->masa1;
                                $model->Masa2 = $res->masa2;
                                $model->Peso_muestra1 = $res->pesoC1;
                                $model->Peso_muestra2 = $res->pesoC2;
                                $model->Peso_constante1 = $res->pesoConMuestra1;
                                $model->Peso_constante2 = $res->pesoConMuestra2;
                                $model->Vol_muestra = $res->volumen;
                                $model->Factor_conversion = $res->factor;
                                $model->Resultado = $resultado;
                                $model->Analizo = Auth::user()->id;
                                $model->Observacion = $res->obs;
                                $model->save();
                            }
                            break;
                        case 48:

                            $res1 = $res->masa2 - $res->masa1;
                            $res2 = $res1 / $res->volumen;
                            $resultado = $res2 * $res->factor;

                            $model = LoteDetalleSolidos::find($res->idMuestra);
                            $model->Crisol = $res->crisol;
                            $model->Masa1 = $res->masa1;
                            $model->Masa2 = $res->masa2;
                            $model->Peso_muestra1 = $res->pesoC1;
                            $model->Peso_muestra2 = $res->pesoC2;
                            $model->Peso_constante1 = $res->pesoConMuestra1;
                            $model->Peso_constante2 = $res->pesoConMuestra2;
                            $model->Vol_muestra = $res->volumen;
                            $model->Factor_conversion = $res->factor;
                            $model->Resultado = $resultado;
                            $model->Analizo = Auth::user()->id;
                            $model->Observacion = $res->obs;
                            $model->save();
                            break;

                        default: // Default
                            if ($res->R != "") {

                                $modelCrisol = CrisolesGA::where('Estado', 0)->get();
                                $aux = $modelCrisol;

                                if ($modelCrisol->count()) {
                                    $mat = rand(0, $modelCrisol->count());
                                    $valCrisol = LoteDetalleSolidos::where('Id_crisol', $modelCrisol[$mat]->Id_matraz)->where('Id_lote', $res->idLote)->get();
                                    if ($valCrisol->count()) {
                                        goto regresar;
                                    } else {

                                        $modelCrisol[$mat]->Estado = 1;
                                        $modelCrisol[$mat]->save();
                                    }
                                } else {
                                    $std = false;
                                }

                                $mf = ((($res->R / $res->factor) * $res->volumen) + round($modelCrisol[$mat]->Peso, 4));
                                $auxMf =  (((round($mf, 4) - round($modelCrisol[$mat]->Peso, 4)) / $res->volumen) * $res->factor);
                                $resultado = $auxMf;

                                $numeroAleatorio = rand(0, 3); // Genera un número entre 1 y 5
                                $valRandomPm1 = number_format($numeroAleatorio / 10000, 4); // Divide por 1000 para obtener el rango deseado
                                $numeroAleatorio = rand(0, 3); // Genera un número entre 1 y 5
                                $valRandomPm2 = number_format($numeroAleatorio / 10000, 4); // Divide por 1000 para obtener el rango deseado
                                $numeroAleatorio = rand(0, 3); // Genera un número entre 1 y 5
                                $valRandomPc1 = number_format($numeroAleatorio / 10000, 4); // Divide por 1000 para obtener el rango deseado
                                $numeroAleatorio = rand(0, 3); // Genera un número entre 1 y 5
                                $valRandomPc2 = number_format($numeroAleatorio / 10000, 4); // Divide por 1000 para obtener el rango deseado

                                $model = LoteDetalleSolidos::find($res->idMuestra);
                                $model->Id_crisol = $modelCrisol[$mat]->Id_crisol;
                                $model->Crisol = $modelCrisol[$mat]->Num_serie;
                                $model->Masa1 = round($modelCrisol[$mat]->Peso, 4);
                                $model->Masa2 = round($mf, 4);
                                $model->Peso_muestra1 = round(($modelCrisol[$mat]->Peso + $valRandomPm1), 4);
                                $model->Peso_muestra2 = round(($modelCrisol[$mat]->Peso + $valRandomPm2), 4);
                                $model->Peso_constante1 = round(($mf + $valRandomPc1), 4);
                                $model->Peso_constante2 = round(($mf + $valRandomPc2), 4);
                                $model->Vol_muestra = $res->volumen;
                                $model->Factor_conversion = $res->factor;
                                $model->Resultado = $resultado;
                                $model->Analizo = Auth::user()->id;
                                $model->Observacion = $res->obs;
                                $model->save();
                            } else { //operacion larga
                                $res1 = $res->masa2 - $res->masa1;
                                $res2 = $res1 / $res->volumen;
                                $resultado = $res2 * $res->factor;

                                $model = LoteDetalleSolidos::find($res->idMuestra);
                                $model->Crisol = $res->crisol;
                                $model->Masa1 = $res->masa1;
                                $model->Masa2 = $res->masa2;
                                $model->Peso_muestra1 = $res->pesoC1;
                                $model->Peso_muestra2 = $res->pesoC2;
                                $model->Peso_constante1 = $res->pesoConMuestra1;
                                $model->Peso_constante2 = $res->pesoConMuestra2;
                                $model->Vol_muestra = $res->volumen;
                                $model->Factor_conversion = $res->factor;
                                $model->Resultado = $resultado;
                                $model->Analizo = Auth::user()->id;
                                $model->Observacion = $res->obs;
                                $model->save();
                            }

                            break;
                    }
                    break;
                case 14: // volumetria
                    switch ($lote[0]->Id_tecnica) {
                        case 6: // Dqo
                        case 161:
                            $x = 0;
                            $d = 0;
                            if ($res->sw == 2) {
                                $res1 = ($res->CA - $res->B);
                                $res2 = ($res1 * $res->C);
                                $res3 = ($res2 * $res->D);
                                $resultado = ($res3 / $res->E);

                                $model = LoteDetalleDqo::find($res->idMuestra);
                                $model->Titulo_muestra = $res->B;
                                $model->Molaridad = $res->C;
                                $model->Titulo_blanco = $res->CA;
                                $model->Equivalencia = $res->D;
                                $model->Vol_muestra = $res->E;
                                $model->Resultado = $resultado;
                                $model->Tecnica = $res->radio;
                                $model->Analizo = Auth::user()->id;
                                $model->save();
                            } else {
                                $d = 2 / $res->E;
                                $x = ($res->X + $res->Y + $res->Z) / 3;
                                $resultado = ((($x - $res->CB) / $res->CM) * $d);

                                $model = LoteDetalleDqo::find($res->idMuestra);
                                $model->Vol_muestra = $res->Vol_muestra;
                                $model->Abs_prom = $res->ABS;
                                $model->Blanco = $res->CA;
                                $model->Factor_dilucion = $res->D;
                                $model->Vol_muestra = $res->Vol_muestra;
                                $model->Abs1 = $res;
                                $model->Abs2 = $res->Y;
                                $model->Abs3 = $res->Z;
                                $model->Resultado = $res->resultado;
                                $model->Tecnica = $res->radio;
                                $model->Analizo = Auth::user()->id;
                                $model->save();
                            }

                            break;
                        case 33: // Cloro
                        case 218:
                        case 119:
                            $res1 = $res->A - $res->B;
                            $res2 = $res1 * $res->C;
                            $res3 = $res2 * $res->D;
                            $resultado = $res3 / $res->E;

                            $model = LoteDetalleCloro::find($res->idMuestra);
                            $model->Vol_muestra = $res->A;
                            $model->Ml_muestra = $res->E;
                            $model->Vol_blanco = $res->B;
                            $model->Normalidad = $res->C;
                            $model->Ph_inicial = $res->G;
                            $model->Ph_final = $res->H;
                            $model->Factor_conversion = $res->D;
                            $model->Resultado = round($resultado, 2);
                            $model->analizo = Auth::user()->id;
                            $model->save();
                            break;
                        case 64:
                            $res1 = $res->A - $res->B;
                            $res2 = $res1 * $res->C;
                            $res3 = $res2 * $res->D;
                            $resultado = $res3 / $res->E;

                            $model = LoteDetalleCloro::find($res->idMuestra);
                            $model->Vol_muestra = $res->A;
                            $model->Ml_muestra = $res->E;
                            $model->Vol_blanco = $res->B;
                            $model->Normalidad = $res->C;
                            $model->Ph_inicial = $res->G;
                            $model->Ph_final = $res->H;
                            $model->Factor_conversion = $res->D;
                            $model->Resultado = $resultado;
                            $model->analizo = Auth::user()->id;
                            $model->save();
                            break;
                        case 9: // Nitrogeno
                        case 10:
                        case 11: //Nitrogeno total
                        case 287:
                        case 83:
                            $res1 = $res->A - $res->B;
                            $res2 = $res1 * $res->C;
                            $res3 = $res2 * $res->D;
                            $res4 = $res3 * $res->E;
                            $resultado = $res4 / $res->G;

                            $model = LoteDetalleNitrogeno::find($res->idMuestra);
                            $model->Titulado_muestra = $res->A;
                            $model->Titulado_blanco = $res->B;
                            $model->Molaridad = $res->C;
                            $model->Factor_equivalencia = $res->D;
                            $model->Factor_conversion = $res->E;
                            $model->Vol_muestra = $res->G;
                            $model->Resultado = $resultado;
                            $model->analizo = Auth::user()->id;
                            $model->save();
                            break;
                        case 108:
                            $a = $res->A * $res->B;
                            $d = 100 + $res->D;
                            $c = 100 + $res->C;

                            $resultado = $a * ($d / $c);

                            $model = LoteDetalleNitrogeno::find($res->idMuestra);
                            $model->Titulado_muestra = $res->A; //Facor de dilución
                            $model->Titulado_blanco = $res->B; //Concentracion de NH3 en mg/L
                            $model->Molaridad = $res->C; //Volumen Añadido al std
                            $model->Factor_equivalencia = $res->D; //Volumen añadido a la muestra
                            $model->Vol_muestra = $res->V; //Volumen de la muestra en mL 
                            $model->Resultado = $resultado; //Resultado
                            $model->Observacion = $res->O; //observacion
                            $model->analizo = Auth::user()->id;
                            $model->save();
                            break;
                        case 28:
                        case 29:
                            $resultado = (($res->A * $res->B) * $res->C) / $res->D;

                            $model = LoteDetalleAlcalinidad::find($res->idMuestra);
                            $model->Titulados = $res->A;
                            $model->Ph_muestra = $res->E;
                            $model->Vol_muestra = $res->D;
                            $model->Normalidad = $res->B;
                            $model->Factor_conversion = $res->C;
                            $model->Resultado = number_format($resultado, 2);
                            $model->analizo = Auth::user()->id;
                            $model->save();
                            break;
                        case 27:
                            $resultado = (($res->A * $res->B) * $res->C) / $res->D;

                            $model = LoteDetalleAlcalinidad::find($res->idMuestra);
                            $model->Titulados = $res->A;
                            $model->Ph_muestra = $res->E;
                            $model->Vol_muestra = $res->D;
                            $model->Normalidad = $res->B;
                            $model->Factor_conversion = $res->C;
                            $model->Resultado = number_format($resultado, 2);
                            $model->analizo = Auth::user()->id;
                            $model->save();
                            break;
                        case 30:
                            $resultado = $res->A + $res->B;

                            $model = LoteDetalleAlcalinidad::find($res->idMuestra);
                            $model->Titulados = $res->A;
                            $model->Normalidad = $res->B;
                            $model->Resultado = number_format($resultado, 2, '.', '');
                            $model->analizo = Auth::user()->id;
                            $model->save();
                            break;
                        default:
                            # code...
                            break;
                    }
                    break;
                case 8:
                    switch ($lote[0]->Id_tecnica) {
                        case 77:
                        case 251:

                            $resultado = round((($res->edta1 * $res->conversion1 * $res->real1) / $res->vol1), 4);
                            $model = LoteDetalleDureza::find($res->idMuestra);
                            $model->EdtaVal1 = $res->edta1;
                            $model->Ph_muestraVal1 = $res->ph1;
                            $model->Vol_muestraVal1 = $res->vol1;
                            $model->Factor_realVal1 = $res->real1;
                            $model->Factor_conversionVal1 = $res->conversion1;
                            $model->ResultadoVal1 = number_format($resultado, 2, '.', '');
                            $model->Resultado = number_format($resultado, 2, '.', '');
                            $model->save();
                            break;
                        case 103:
                            $resultado1 = round((round(($res->edta1 * $res->conversion1 * $res->real1), 4) / $res->vol1), 4);
                            $resultado2 = round((round(($res->edta2 * $res->conversion2 * $res->real2), 4) / $res->vol2), 4);
                            $resultado3 = round((round(($res->edta3 * $res->conversion3 * $res->real3), 4) / $res->vol3), 4);
                            $promEdta = ($res->edta1 + $res->edta2 + $res->edta3) / 3;
                            $resultado = round((round($promEdta, 2, PHP_ROUND_HALF_UP) * $res->real1 * $res->conversion1)  / $res->vol1, 4);

                            $model = LoteDetalleDureza::find($res->idMuestra);
                            $model->EdtaVal1 = $res->edta1;
                            $model->Ph_muestraVal1 = $res->ph1;
                            $model->Vol_muestraVal1 = $res->vol1;
                            $model->Factor_realVal1 = $res->real1;
                            $model->Factor_conversionVal1 = $res->conversion1;
                            $model->ResultadoVal1 = $resultado1;

                            $model->EdtaVal2 = $res->edta2;
                            $model->Ph_muestraVal2 = $res->ph2;
                            $model->Vol_muestraVal2 = $res->vol2;
                            $model->Factor_realVal2 = $res->real2;
                            $model->Factor_conversionVal2 = $res->conversion2;
                            $model->ResultadoVal2 = $resultado2;

                            $model->EdtaVal3 = $res->edta3;
                            $model->Ph_muestraVal3 = $res->ph3;
                            $model->Vol_muestraVal3 = $res->vol3;
                            $model->Factor_realVal3 = $res->real3;
                            $model->Factor_conversionVal3 = $res->conversion3;
                            $model->ResultadoVal3 = $resultado3;


                            $model->Resultado = $resultado;
                            $model->save();
                            break;
                        case 252:
                            $resultado = $res->durezaT - $res->durezaC;
                            $model = LoteDetalleDureza::find($res->idMuestra);
                            $model->Lectura1 = $res->durezaT;
                            $model->Lectura2 = $res->durezaC;
                            $model->Resultado = number_format($resultado, 2, '.', '');
                            $model->save();
                            break;
                        default:
                            # code...
                            break;
                    }
                    break;
                case 6: //Mb
                case 12:
                case 3:
                    switch ($lote[0]->Id_tecnica) {
                        case 5:
                        case 71:
                            $temp = LoteDetalleDbo::where('Id_detalle', $res->idMuestra)->first();
                            switch ($temp->Id_control) {
                                case 5:
                                    $resultado = $res->OIB - $res->OFB;
                                    $d = 300 / $res->VB;
                                    $resultado = round($resultado / $d, 2);
                                    $model = LoteDetalleDbo::find($res->idMuestra);
                                    $model->Botella_final = $res->H;
                                    $model->Botella_od = $res->G;
                                    $model->Odf = $res->OFB;
                                    $model->Odi = $res->OIB;
                                    $model->Ph_final = $res->J;
                                    $model->Ph_inicial = $res->I;
                                    $model->Vol_muestra = $res->VB;
                                    $model->Dilucion = $res->E;
                                    $model->Vol_botella = $res->C;
                                    $model->Resultado = $resultado;
                                    $model->Analizo = Auth::user()->id;
                                    $model->Sugerido = $res->S;
                                    $model->save();
                                    $tipo = 2;
                                    break;
                                default:
                                    if ($res->tipo == 1) {
                                        if ($res->D <= 0.1) {
                                            $E = $res->D / $res->C;
                                            $resultadoTemp = ($res->A - $res->B) / $E;
                                        } else {
                                            $E = $res->D / $res->C;
                                            $resultadoTemp = ($res->A - $res->B) / round($E, 3);
                                        }
                                        $resultado = round($resultadoTemp, 2);

                                        $model = LoteDetalleDbo::find($res->idMuestra);
                                        $model->Botella_final = $res->H;
                                        $model->Botella_od = $res->G;
                                        $model->Odf = $res->B;
                                        $model->Odi = $res->A;
                                        $model->Ph_final = $res->J;
                                        $model->Ph_inicial = $res->I;
                                        $model->Vol_muestra = $res->D;
                                        $model->Dilucion = $res->E;
                                        $model->Vol_botella = $res->C;
                                        $model->Resultado = $resultado;
                                        $model->Analizo = Auth::user()->id;
                                        $model->Sugerido = $res->S;
                                        $model->save();
                                        $tipo = 1;
                                    } else {
                                        $resultado = ($res->OI - $res->OF);
                                        $model = LoteDetalleDbo::find($res->idMuestra);
                                        $model->Odf = $res->OF;
                                        $model->Odi = $res->OI;
                                        $model->Vol_muestra = $res->V;
                                        $model->Dilucion = $res->E;
                                        $model->Resultado = $resultado;
                                        $model->Analizo = Auth::user()->id;
                                        $model->Sugerido = $res->S;
                                        $model->save();
                                        $tipo = 2;
                                    }
                                    break;
                            }


                            break;
                        default:
                            $model = LoteDetalleDirectosA::find($res->idMuestra);
                            $model->Resultado = $res->resultado;
                            $model->save();
                            break;
                    }
                    break;
                case 7: //Muestreo
                case 19: //Directos
                    switch ($lote[0]->Id_tecnica) {
                        case 14:
                        case 110:
                            $resultado = "";
                            $promedio = ($res->l1 + $res->l2 + $res->l3) / 3;
                            $resultado = round($promedio, 1);
                            $model = LoteDetalleDirectosA::find($res->idMuestra);
                            $model->Resultado = $resultado;
                            $model->Lectura1 = $res->l1;
                            $model->Lectura2 = $res->l2;
                            $model->Lectura3 = $res->l3;
                            $model->Temperatura = $res->temp;
                            $model->Promedio = $res->promedio;
                            $model->save();

                            break;
                        case 67:
                        case 68:
                            $resultado = "";
                            $promedio = ($res->l1 + $res->l2 + $res->l3) / 3;
                            $resultado = round($promedio, 0);
                            $model = LoteDetalleDirectosA::find($res->idMuestra);
                            $model->Resultado = $resultado;
                            $model->Lectura1 = $res->l1;
                            $model->Lectura2 = $res->l2;
                            $model->Lectura3 = $res->l3;
                            $model->Temperatura = $res->temp;
                            $model->Promedio = $res->promedio;
                            $model->save();

                            break;
                        case 119:
                        case 218:
                            $resultado = 0;
                            $dilusion = $res->dilucion;
                            $fd = 10 / $res->volumen;
                            $promedio = ($res->l1 + $res->l2 + $res->l3) / 3;
                            $resultado = round($promedio * $fd, 2);
                            $model = LoteDetalleDirectosA::find($res->idMuestra);
                            $model->Factor_dilucion = $fd;
                            $model->Resultado = $resultado;
                            $model->Vol_muestra = $res->volumen;
                            $model->Lectura1 = $res->l1;
                            $model->Lectura2 = $res->l2;
                            $model->Lectura3 = $res->l3;
                            $model->Promedio = $promedio;
                            $model->save();
                            break;
                        case 98:
                            $resultado = 0;
                            $fd =  15 / $res->volumen;
                            $promedio = (($res->l1 + $res->l2 + $res->l3) / 3) * $fd;
                            $resultado = round($promedio, 2);
                            $model = LoteDetalleDirectosA::find($res->idMuestra);
                            $model->Resultado = $resultado;
                            $model->Factor_dilucion = $fd;
                            $model->Vol_muestra = $res->volumen;
                            $model->Lectura1 = $res->l1;
                            $model->Lectura2 = $res->l2;
                            $model->Lectura3 = $res->l3;
                            $model->Promedio = $promedio;
                            $model->save();
                            break;
                        case 89:
                        case 115:
                            $resultado = 0;
                            $fd =  15 / $res->volumen;
                            $promedio = (($res->l1 + $res->l2 + $res->l3) / 3);
                            $resTemp = $promedio * $fd;
                            $resultado = round($resTemp, 2);
                            $model = LoteDetalleDirectosA::find($res->idMuestra);
                            $model->Resultado = $resultado;
                            $model->Factor_dilucion = $fd;
                            $model->Vol_muestra = $res->volumen;
                            $model->Lectura1 = $res->l1;
                            $model->Lectura2 = $res->l2;
                            $model->Lectura3 = $res->l3;
                            $model->Promedio = $promedio;
                            $model->save();

                            break;
                        case 97:
                        case 33:
                            $resultado = "";

                            $promedio = ($res->l1 + $res->l2 + $res->l3) / 3;
                            $resultado = round($promedio, 3);

                            $model = LoteDetalleDirectosA::find($res->idMuestra);
                            $model->Resultado = $resultado;
                            $model->Lectura1 = $res->l1;
                            $model->Lectura2 = $res->l2;
                            $model->Lectura3 = $res->l3;
                            $model->Promedio = $res->promedio;
                            $model->save();
                            break;
                        case 66:
                        case 65:
                        case 120:
                        case 372:
                        case 365:
                        case 370:
                            $resultado = 0;
                            //$factor = 0;
                            $dilusion = 50 / $res->volumen;
                            $promedio = ($res->aparente + $res->verdadero) * $res->dilusion;

                            $resultado = $promedio + $res->factor;

                            $model = LoteDetalleDirectosA::find($res->idMuestra);
                            $model->Resultado = $resultado;
                            $model->Color_a = $res->aparente;
                            $model->Color_v = $res->verdadero;
                            $model->Factor_dilucion = $dilusion;
                            $model->Vol_muestra = $res->volumen;
                            $model->Ph = $res->ph;
                            $model->Factor_correcion = $res->factor;
                            $model->save();
                            break;
                        case 130:
                            $resultado = ($res->resultado / 50) * 20;
                            $model = LoteDetalleDirectosA::find($res->idMuestra);
                            $model->Lectura1 = $res->resultado;
                            $model->Resultado = $resultado;
                            $model->save();
                            break;
                        case 261:
                            $resultado = ($res->resultado / 50) * 12;
                            $model = LoteDetalleDirectosA::find($res->idMuestra);
                            $model->Lectura1 = $res->resultado;
                            $model->Resultado = $resultado;
                            $model->save();
                            break;
                        case 102:
                            $absProm1 = ($res->abs11 + $res->abs12 + $res->abs13) / 3;
                            $absProm2 = ($res->abs21 + $res->abs22 + $res->abs23) / 3;
                            $absProm3 = ($res->abs31 + $res->abs32 + $res->abs33) / 3;

                            $re1 = (($absProm1 / 10) * 1000) * $res->fd1;
                            $re2 = (($absProm2 / 10) * 1000) * $res->fd1;
                            $re3 = (($absProm3 / 10) * 1000) * $res->fd1;

                            $res1 = number_format($re1, 2, '.', '');
                            $res2 = number_format($re2, 2, '.', '');
                            $res3 = number_format($re3, 2, '.', '');


                            $model = LoteDetalleColor::find($res->idMuestra);
                            $model->Vol_muestra = $res->volColor;
                            $model->Ph_muestra = $res->ph;
                            $model->Fd1 = $res->fd1;
                            $model->Fd2 = $res->fd2;
                            $model->Fd3 = $res->fd3;
                            $model->Longitud1 = $res->longitud1;
                            $model->Longitud2 = $res->longitud2;
                            $model->Longitud3 = $res->longitud3;
                            //ABS 436
                            $model->Abs1_436 = $res->abs11;
                            $model->Abs2_436 = $res->abs12;
                            $model->Abs3_436 = $res->abs13;

                            //ABS 525
                            $model->Abs1_525 = $res->abs21;
                            $model->Abs2_525 = $res->abs22;
                            $model->Abs3_525 = $res->abs23;
                            //ABS 620
                            $model->Abs1_620 = $res->abs31;
                            $model->Abs2_620 = $res->abs32;
                            $model->Abs3_620 = $res->abs33;

                            $model->Abs_promedio1 = number_format($absProm1, 3);
                            $model->Abs_promedio2 = number_format($absProm2, 3);
                            $model->Abs_promedio3 = number_format($absProm3, 3);


                            // $model->Observacion1 = $res->ph; 
                            // $model->Observacion2 = $res->ph; 
                            // $model->Observacion3 = $res->ph; 
                            // $model->Resultado1 = number_format($res1, 2);
                            // $model->Resultado2 = number_format($res2, 2);
                            // $model->Resultado3 = number_format($res3, 2);

                            $model->Resultado1 = $res1;
                            $model->Resultado2 = $res2;
                            $model->Resultado3 = $res3;
                            $model->save();
                            break;
                        case 173:
                            $model = LoteDetalleVidrio::find($res->idMuestra);
                            $model->Vidrio1 = $res->vidrio1;
                            $model->Vidrio2 = $res->vidrio2;
                            $model->Vidrio3 = $res->vidrio3;
                            $model->Vidrio4 = $res->vidrio4;
                            $model->Vidrio5 = $res->vidrio5;
                            $model->Vidrio6 = $res->vidrio6;
                            $model->save();

                            $model2 = CodigoParametros::find($model->Id_codigo);
                            $model2->Resultado = $res->vidrio1;
                            $model2->Resultado2 = $res->vidrio2;
                            $model2->Resultado_aux = $res->vidrio3;
                            $model2->Resultado_aux2 = $res->vidrio4;
                            $model2->Resultado_aux3 = $res->vidrio5;
                            $model2->Resultado_aux4 = $res->vidrio6;
                            $model2->save();

                            // Asegúrate de retornar los datos de model2 en formato JSON
                            // return response()->json([
                            //     'success' => true,
                            //     'model2' => $model2,
                            // ]);
                            break; // El break debe estar dentro del case

                        default: // Default Directos
                            $resultado = $res->resultado;
                            $model = LoteDetalleDirectosA::find($res->idMuestra);
                            $model->Resultado = $res->resultado;
                            $model->save();
                            break;
                    }
                    break;
                default:
                    $model = array();
                    break;
            }
            if ($model->Id_control == 1) {
                $codigoParametro = CodigoParametros::find($model->Id_codigo);
                $codigoParametro->Resultado = @$resultado;
                $codigoParametro->save();
            }
        }
        $data = array(
            'tipo' => $tipo,
            'resultado' => $resultado,
            'aux' => $aux,
            'model' => $model,
            'std' => $std,
            'r2' => $r2,
        );

        return response()->json($data);
    }
    public function setObservacion(Request $res)
    {
        $lote = LoteAnalisis::where('Id_lote', $res->idLote)->get();
        switch ($lote[0]->Id_area) {
            case 16: // Espectrofotometria 
            case 5: // Fisicoquimicos
                switch ($lote[0]->Id_tecnica) {
                    case 0:
                        break;
                    default:
                        $model = LoteDetalleEspectro::where('Id_detalle', $res->idMuestra)->first();
                        $model->Observacion = $res->observacion;
                        $model->save();
                        break;
                }
                break;
            case 13: //G&A
                $model = LoteDetalleGA::where('Id_detalle', $res->idMuestra)->first();
                $model->Observacion = $res->observacion;
                $model->save();
                break;
            case 15: //Solidos
                $model = LoteDetalleSolidos::where('Id_detalle', $res->idMuestra)->first();
                $model->Observacion = $res->observacion;
                $model->save();
                break;
            case 14: //Volumetria
                switch ($lote[0]->Id_tecnica) {
                    case 218: // Cloro
                    case 33:
                    case 64:
                        $model = LoteDetalleCloro::where('Id_detalle', $res->idMuestra)->first();
                        $model->Observacion = $res->observacion;
                        $model->save();
                        break;
                    case 6: // Dqo
                    case 161:
                        $model = LoteDetalleDqo::where('Id_detalle', $res->idMuestra)->first();
                        $model->Observacion = $res->observacion;
                        $model->save();
                        break;
                    case 9: // Nitrogeno
                    case 287:
                    case 10:
                    case 11:
                    case 108: // Nitrogeno Amon
                        $model = LoteDetalleNitrogeno::where('Id_detalle', $res->idMuestra)->first();
                        $model->Observacion = $res->observacion;
                        $model->save();
                        break;
                    case 28: //Alcalinidad
                    case 29:
                    case 30:
                    case 27:
                        $model = LoteDetalleAlcalinidad::where('Id_detalle', $res->idMuestra)->first();
                        $model->Observacion = $res->observacion;
                        $model->save();
                        break;
                    default: // Default Directos
                        $model = LoteDetalleDirectosA::where('Id_detalle', $res->idMuestra)->first();
                        $model->Observacion = $res->observacion;
                        $model->save();
                        break;
                }
                break;
            case 7: //Campo
            case 19: //Directos
                switch ($lote[0]->Id_tecnica) {
                    case 102:
                        $model = LoteDetalleColor::where('Id_detalle', $res->idMuestra)->first();
                        $model->Observacion1 = $res->observacion;
                        $model->Observacion2 = $res->observacion2;
                        $model->Observacion3 = $res->observacion3;
                        $model->save();
                        break;
                    case 173:
                        $model = LoteDetalleVidrio::where('Id_detalle', $res->idMuestra)->first();
                        $model->Observacion = $res->observacion;
                        $model->save();
                        break;
                    default:
                        $model = LoteDetalleDirectosA::where('Id_detalle', $res->idMuestra)->first();
                        $model->Observacion = $res->observacion;
                        $model->save();
                        break;
                }
                break;
            case 6: //Mb
            case 12:
                switch ($lote[0]->Id_tecnica) {
                    case 135: // Coliformes fecales
                    case 132:
                    case 133:
                    case 12:
                    case 134: // E COLI
                    case 35:
                    case 51: // Coliformes totales
                    case 137:
                        $model = LoteDetalleColiformes::where('Id_detalle', $res->idMuestra)->first();
                        $model->Observacion = $res->observacion;
                        $model->save();
                        break;
                    case 253: //todo  ENTEROCOCO FECAL
                        $model = LoteDetalleEnterococos::where('Id_detalle', $res->idMuestra)->first();
                        $model->Observacion = $res->observacion;
                        $model->save();
                        break;
                    case 5: //todo DEMANDA BIOQUIMICA DE OXIGENO (DBO5) 
                    case 71:
                        $model = LoteDetalleDbo::where('Id_detalle', $res->idMuestra)->first();
                        $model->Observacion = $res->observacion;
                        $model->save();
                        break;
                    case 70:
                        $model = LoteDetalleDboIno::where('Id_detalle', $res->idMuestra)->first();
                        $model->Observacion = $res->observacion;
                        $model->save();
                        break;
                    case 16: //todo Huevos de Helminto 
                        $model = LoteDetalleHH::where('Id_detalle', $res->idMuestra)->first();
                        $model->Observacion = $res->observacion;
                        $model->save();
                        break;
                    case 78:
                        $model = LoteDetalleEcoli::where('Id_detalle', $res->idMuestra)->first();
                        $model->Observacion = $res->observacion;
                        $model->save();
                        break;
                    default:
                        $model = LoteDetalleDirectosA::where('Id_detalle', $res->idMuestra)->first();
                        $model->Observacion = $res->observacion;
                        $model->save();
                        break;
                }
                break;
            case 8: //Potable
                switch ($lote[0]->Id_tecnica) {
                    case 77: //Dureza
                    case 103:
                    case 251:
                    case 252:
                        $model = LoteDetalleDureza::where('Id_detalle', $res->idMuestra)->first();
                        $model->Observacion = $res->observacion;
                        $model->save();
                        break;
                    default:
                        $model = LoteDetallePotable::where('Id_detalle', $res->idMuestra)->first();
                        $model->Observacion = $res->observacion;
                        $model->save();
                        break;
                }
                break;
            default:
                $model = LoteDetalleDirectosA::where('Id_detalle', $res->idMuestra)->first();
                $model->Observacion = $res->observacion;
                $model->save();
                break;
        }
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function setControlCalidad(Request $res)
    {
        $lote = LoteAnalisis::where('Id_lote', $res->idLote)->get();
        if ($lote->count()) {
            switch ($lote[0]->Id_area) {
                case 16: // Espectrofotometria
                case 5: // Fisicoquimicos
                    switch ($lote[0]->Id_tecnica) {
                        case 0:

                            break;
                        case 69:
                        case 152:
                        case 87:
                            $muestra = LoteDetalleEspectro::where('Id_detalle', $res->idMuestra)->first();
                            $model = $muestra->replicate();
                            $model->Id_control = $res->idControl;
                            $model->Resultado = "";
                            $model->Liberado = 0;
                            $model->save();
                            $model = LoteDetalleEspectro::where('Id_lote', $res->idLote)->get();
                            break;
                        default:
                            $muestra = LoteDetalleEspectro::where('Id_detalle', $res->idMuestra)->first();
                            $temp = LoteDetalleEspectro::where('Id_lote', $muestra->Id_lote)->where('Id_control', $res->idControl)->get();
                            switch ($res->idControl) {
                                case 28:
                                    $model = $muestra->replicate();
                                    $model->Id_control = $res->idControl;
                                    $model->Resultado = NULL;
                                    $model->Liberado = 0;
                                    $model->save();
                                    break;
                                default:
                                    if ($temp->count()) {
                                    } else {
                                        $model = $muestra->replicate();
                                        $model->Id_control = $res->idControl;
                                        $model->Resultado =  NULL;
                                        $model->Liberado = 0;
                                        $model->save();
                                    }
                                    break;
                            }

                            $model = LoteDetalleEspectro::where('Id_lote', $res->idLote)->get();
                            break;
                    }
                    break;
                case 13: //G&A
                    $muestra = LoteDetalleGA::where('Id_detalle', $res->idMuestra)->first();
                    $model = $muestra->replicate();
                    $model->Id_control = $res->idControl;
                    $model->Resultado = NULL;
                    $model->Liberado = 0;
                    $model->save();

                    $model = LoteDetalleGA::where('Id_lote', $res->idLote)->get();
                    break;
                case 15: //Solidos
                    $muestra = LoteDetalleSolidos::where('Id_detalle', $res->idMuestra)->first();
                    $model = $muestra->replicate();
                    $model->Id_control = $res->idControl;
                    $model->Resultado = NULL;
                    $model->Liberado = 0;
                    $model->save();

                    $model = LoteDetalleSolidos::where('Id_lote', $res->idLote)->get();
                    break;
                case 14: //Volumetria
                    switch ($lote[0]->Id_tecnica) {
                        case 6: // Dqo
                        case 161:
                            $muestra = LoteDetalleDqo::where('Id_detalle', $res->idMuestra)->first();
                            $temp = LoteDetalleDqo::where('Id_lote', $muestra->Id_lote)->where('Id_control', $res->idControl)->get();
                            switch ($res->idControl) {
                                case 28:
                                    $model = $muestra->replicate();
                                    $model->Id_control = $res->idControl;
                                    $model->Resultado = NULL;
                                    $model->Liberado = 0;
                                    $model->save();
                                    break;
                                default:
                                    if ($temp->count()) {
                                    } else {
                                        $model = $muestra->replicate();
                                        $model->Id_control = $res->idControl;
                                        $model->Resultado = NULL;
                                        $model->Liberado = 0;
                                        $model->save();
                                    }
                                    break;
                            }
                            $model = LoteDetalleDqo::where('Id_lote', $res->idLote)->get();
                            break;
                        case 33: // Cloro
                        case 218:
                        case 64:
                            $muestra = LoteDetalleCloro::where('Id_detalle', $res->idMuestra)->first();
                            $model = $muestra->replicate();
                            $model->Id_control = $res->idControl;
                            $model->Resultado = NULL;
                            $model->Liberado = 0;
                            $model->save();

                            $model = LoteDetalleCloro::where('Id_lote', $res->idLote)->get();
                            break;
                        case 9: // Nitrogeno
                        case 10:
                        case 11:
                        case 287:
                        case 83:
                        case 108:
                            $muestra = LoteDetalleNitrogeno::where('Id_detalle', $res->idMuestra)->first();
                            $model = $muestra->replicate();
                            $model->Id_control = $res->idControl;
                            $model->Resultado = NULL;
                            $model->Liberado = 0;
                            $model->save();

                            $model = LoteDetalleNitrogeno::where('Id_lote', $res->idLote)->get();
                            break;
                        case 28:
                        case 29:
                        case 30:
                        case 27:
                            $muestra = LoteDetalleAlcalinidad::where('Id_detalle', $res->idMuestra)->first();
                            $model = $muestra->replicate();
                            $model->Id_control = $res->idControl;
                            $model->Resultado = NULL;
                            $model->Liberado = 0;
                            $model->save();

                            $model = LoteDetalleAlcalinidad::where('Id_lote', $res->idLote)->get();
                            break;
                        default:

                            $muestra = LoteDetalleDirectosA::where('Id_detalle', $res->idMuestra)->first();
                            $model = $muestra->replicate();
                            $model->Id_control = $res->idControl;
                            $model->Resultado = NULL;
                            $model->Liberado = 0;
                            $model->save();

                            $model = LoteDetalleDirectosA::where('Id_lote', $res->idLote)->get();
                            break;
                    }
                    break;
                case 7: //Campo
                case 19: // Directos
                    switch ($lote[0]->Id_tecnica) {
                        case 102:
                            $muestra = LoteDetalleColor::where('Id_detalle', $res->idMuestra)->first();
                            $model = $muestra->replicate();
                            $model->Id_control = $res->idControl;
                            $model->Resultado1 = NULL;
                            $model->Resultado2 = NULL;
                            $model->Resultado3 = NULL;
                            $model->Liberado = 0;
                            $model->save();
                            $model = LoteDetalleColor::where('Id_lote', $res->idLote)->get();
                            break;
                        default:
                            $muestra = LoteDetalleDirectosA::where('Id_detalle', $res->idMuestra)->first();
                            $model = $muestra->replicate();
                            $model->Id_control = $res->idControl;
                            $model->Resultado = NULL;
                            $model->Liberado = 0;
                            $model->save();
                            $model = LoteDetalleDirectosA::where('Id_lote', $res->idLote)->get();
                            break;
                    }

                    break;


                case 6: //Mb
                case 12: //Mb Alimentos
                    switch ($lote[0]->Id_tecnica) {
                        case 135: // Coliformes fecales
                        case 132:
                        case 133:
                        case 12:
                        case 134: // E COLI
                        case 35:
                        case 51: // Coliformes totales
                        case 137:
                            $muestra = LoteDetalleColiformes::where('Id_detalle', $res->idMuestra)->first();
                            $model = $muestra->replicate();
                            $model->Id_control = $res->idControl;
                            $model->Resultado = NULL;
                            $model->Liberado = 0;
                            $model->save();

                            $model = LoteDetalleColiformes::where('Id_lote', $res->idLote)->get();
                            break;
                        case 253: //todo  ENTEROCOCO FECAL
                            $muestra = LoteDetalleEnterococos::where('Id_detalle', $res->idMuestra)->first();
                            $model = $muestra->replicate();
                            $model->Id_control = $res->idControl;
                            $model->Resultado = NULL;
                            $model->Liberado = 0;
                            $model->save();

                            $model = LoteDetalleEnterococos::where('Id_lote', $res->idLote)->get();
                            break;
                        case 5: //todo DEMANDA BIOQUIMICA DE OXIGENO (DBO5) 
                        case 71:
                            $muestra = LoteDetalleDbo::where('Id_detalle', $res->idMuestra)->first();
                            $model = $muestra->replicate();
                            $model->Id_control = $res->idControl;
                            $model->Resultado = NULL;
                            $model->Liberado = 0;
                            $model->save();

                            $model = LoteDetalleDbo::where('Id_lote', $res->idLote)->get();
                            break;
                        case 70:
                            $muestra = LoteDetalleDboIno::where('Id_detalle', $res->idMuestra)->first();
                            $model = $muestra->replicate();
                            $model->Id_control = $res->idControl;
                            $model->Resultado = NULL;
                            $model->Liberado = 0;
                            $model->save();

                            $model = LoteDetalleDboIno::where('Id_lote', $res->idLote)->get();
                            break;
                        case 16: //todo Huevos de Helminto 
                            $muestra = LoteDetalleHH::where('Id_detalle', $res->idMuestra)->first();
                            $model = $muestra->replicate();
                            $model->Id_control = $res->idControl;
                            $model->Resultado = NULL;
                            $model->Liberado = 0;
                            $model->save();

                            $model = LoteDetalleHH::where('Id_lote', $res->idLote)->get();
                            break;
                        case 78:
                            $muestra = LoteDetalleEcoli::where('Id_detalle', $res->idMuestra)->first();
                            $model = $muestra->replicate();
                            $model->Id_control = $res->idControl;
                            $model->Resultado = NULL;
                            $model->Liberado = 0;
                            $model->save();

                            $model = LoteDetalleEcoli::where('Id_lote', $res->idLote)->get();
                            break;
                        default:
                            $model = array();
                            break;
                    }
                    break;
                case 8: //Potable
                    switch ($lote[0]->Id_tecnica) {
                        case 77: //Dureza
                        case 103:
                        case 251:
                        case 252:
                            $muestra = LoteDetalleDureza::where('Id_detalle', $res->idMuestra)->first();
                            $model = $muestra->replicate();
                            $model->Id_control = $res->idControl;
                            $model->Resultado = NULL;
                            $model->Liberado = 0;
                            $model->save();

                            $model = LoteDetalleDureza::where('Id_lote', $res->idLote)->get();
                            break;
                        default:
                            $muestra = LoteDetallePotable::where('Id_detalle', $res->idMuestra)->first();
                            $model = $muestra->replicate();
                            $model->Id_control = $res->idControl;
                            $model->Resultado = NULL;
                            $model->Liberado = 0;
                            $model->save();

                            $model = LoteDetallePotable::where('Id_lote', $res->idLote)->get();

                            break;
                    }
                    break;
                default:
                    $muestra = LoteDetalleDirectosA::where('Id_detalle', $res->idMuestra)->first();
                    $model = $muestra->replicate();
                    $model->Id_control = $res->idControl;
                    $model->Resultado = NULL;
                    $model->Liberado = 0;
                    $model->save();

                    $model = LoteDetalleDirectosA::where('Id_lote', $res->idLote)->get();
                    break;
            }
        }

        $lote = LoteAnalisis::find($res->idLote);
        $lote->Asignado = $model->count();
        $lote->save();

        $data = array(
            'lote' => $lote,
            'model' => $model,
        );
        return response()->json($data);
    }
    // public function reasignarMuestra(Request $res)
    // {
    //     $msg = "";
    //     $detalle = DB::table('viewcodigoinformeAlimentos')->where('Id_codigo', $res->idCodigo)->first();
    //     $asignado = 0;
    //     $liberado = 0;
    //     try {
    //         switch ($detalle->Id_area) {
    //             case 16: // Espectrofotometria
    //             case 5: // Fisicoquimicos
    //                 $model = DB::table('lote_detalle_espectro')->where('Id_analisis', $detalle->Id_solicitud)->where('Id_parametro', $detalle->Id_parametro)->delete();
    //                 $asignado = LoteDetalleEspectro::where('Id_lote', $detalle->Id_lote)->get()->count();
    //                 $liberado = LoteDetalleEspectro::where('Id_lote', $detalle->Id_lote)->where('Liberado', 1)->get()->count();
    //                 break;
    //             case 13: // G&A
    //                 $model = DB::table('lote_detalle_ga')->where('Id_analisis', $detalle->Id_solicitud)->where('Id_parametro', $detalle->Id_parametro)->delete();
    //                 $asignado = LoteDetalleGA::where('Id_lote', $detalle->Id_lote)->get()->count();
    //                 $liberado = LoteDetalleGA::where('Id_lote', $detalle->Id_lote)->where('Liberado', 1)->get()->count();
    //                 break;
    //             case 15: //Solidos
    //                 $model = DB::table('lote_detalle_solidos')->where('Id_analisis', $detalle->Id_solicitud)->where('Id_parametro', $detalle->Id_parametro)->delete();
    //                 $asignado = LoteDetalleSolidos::where('Id_lote', $detalle->Id_lote)->get()->count();
    //                 $liberado = LoteDetalleSolidos::where('Id_lote', $detalle->Id_lote)->where('Liberado', 1)->get()->count();
    //                 break;
    //             case 14: //volumetria
    //                 switch ($detalle->Id_parametro) {
    //                     case 6:
    //                     case 161:
    //                         $model = DB::table('lote_detalle_dqo')->where('Id_analisis', $detalle->Id_solicitud)->where('Id_parametro', $detalle->Id_parametro)->delete();
    //                         $asignado = LoteDetalleDqo::where('Id_lote', $detalle->Id_lote)->get()->count();
    //                         $liberado = LoteDetalleDqo::where('Id_lote', $detalle->Id_lote)->where('Liberado', 1)->get()->count();
    //                         break;
    //                     case 33:
    //                     case 218:
    //                     case 119:
    //                     case 64:
    //                         $model = DB::table('lote_detalle_cloro')->where('Id_analisis', $detalle->Id_solicitud)->where('Id_parametro', $detalle->Id_parametro)->delete();
    //                         $asignado = LoteDetalleCloro::where('Id_lote', $detalle->Id_lote)->get()->count();
    //                         $liberado = LoteDetalleCloro::where('Id_lote', $detalle->Id_lote)->where('Liberado', 1)->get()->count();
    //                         break;
    //                     case 9:
    //                     case 10:
    //                     case 11:
    //                     case 287:
    //                     case 83:
    //                     case 108:
    //                         $model = DB::table('lote_detalle_nitrogeno')->where('Id_analisis', $detalle->Id_solicitud)->where('Id_parametro', $detalle->Id_parametro)->delete();
    //                         $asignado = LoteDetalleNitrogeno::where('Id_lote', $detalle->Id_lote)->get()->count();
    //                         $liberado = LoteDetalleNitrogeno::where('Id_lote', $detalle->Id_lote)->where('Liberado', 1)->get()->count();
    //                         break;
    //                     case 28:
    //                     case 29:
    //                     case 30:
    //                     case 27:
    //                         $model = DB::table('lote_detalle_alcalinidad')->where('Id_analisis', $detalle->Id_solicitud)->where('Id_parametro', $detalle->Id_parametro)->delete();
    //                         $asignado = LoteDetalleAlcalinidad::where('Id_lote', $detalle->Id_lote)->get()->count();
    //                         $liberado = LoteDetalleAlcalinidad::where('Id_lote', $detalle->Id_lote)->where('Liberado', 1)->get()->count();
    //                         break;
    //                     default:
    //                         $model = DB::table('lote_detalle_directosa')->where('Id_analisis', $detalle->Id_solicitud)->where('Id_parametro', $detalle->Id_parametro)->delete();
    //                         $asignado = LoteDetalleDirectosA::where('Id_lote', $detalle->Id_lote)->get()->count();
    //                         $liberado = LoteDetalleDirectosA::where('Id_lote', $detalle->Id_lote)->where('Liberado', 1)->get()->count();
    //                         break;
    //                 }
    //                 break;
    //             case 7: //Campo
    //             case 19: // Directos
    //                 switch ($detalle->Id_parametro) {
    //                     case 102:
    //                         $model = DB::table('lote_detalle_color')->where('Id_analisis', $detalle->Id_solicitud)->where('Id_parametro', $detalle->Id_parametro)->delete();
    //                         $asignado = LoteDetalleColor::where('Id_lote', $detalle->Id_lote)->get()->count();
    //                         $liberado = LoteDetalleColor::where('Id_lote', $detalle->Id_lote)->where('Liberado', 1)->get()->count();
    //                         break;
    //                     default:
    //                         $model = DB::table('lote_detalle_directosa')->where('Id_analisis', $detalle->Id_solicitud)->where('Id_parametro', $detalle->Id_parametro)->delete();
    //                         $asignado = LoteDetalleDirectosA::where('Id_lote', $detalle->Id_lote)->get()->count();
    //                         $liberado = LoteDetalleDirectosA::where('Id_lote', $detalle->Id_lote)->where('Liberado', 1)->get()->count();
    //                         break;
    //                 }
    //                 break;
    //             case 8: //Potable
    //                 switch ($detalle->Id_parametro) {
    //                     case 77: //Dureza
    //                     case 103:
    //                     case 251:
    //                     case 252:
    //                         $model = DB::table('lote_detalle_dureza')->where('Id_analisis', $detalle->Id_solicitud)->where('Id_parametro', $detalle->Id_parametro)->delete();
    //                         $asignado = LoteDetalleDureza::where('Id_lote', $detalle->Id_lote)->get()->count();
    //                         $liberado = LoteDetalleDureza::where('Id_lote', $detalle->Id_lote)->where('Liberado', 1)->get()->count();
    //                         break;
    //                     default:
    //                         $model = DB::table('lote_detalle_potable')->where('Id_analisis', $detalle->Id_solicitud)->where('Id_parametro', $detalle->Id_parametro)->delete();
    //                         $asignado = LoteDetallePotable::where('Id_lote', $detalle->Id_lote)->get()->count();
    //                         $liberado = LoteDetallePotable::where('Id_lote', $detalle->Id_lote)->where('Liberado', 1)->get()->count();
    //                         break;
    //                 }
    //                 break;
    //             case 6: // Mb
    //             case 12:
    //             case 3:
    //                 switch ($detalle->Id_parametro) {
    //                     case 135: // Coliformes fecales
    //                     case 132:
    //                     case 133:
    //                     case 12:
    //                     case 134: // termotolerantes
    //                     case 35:
    //                     case 51: // Coliformes totales
    //                     case 137:
    //                     case 350:
    //                         $model = DB::table('lote_detalle_coliformes')->where('Id_analisis', $detalle->Id_solicitud)->where('Id_parametro', $detalle->Id_parametro)->delete();
    //                         $asignado = LoteDetalleColiformes::where('Id_lote', $detalle->Id_lote)->get()->count();
    //                         $liberado = LoteDetalleColiformes::where('Id_lote', $detalle->Id_lote)->where('Liberado', 1)->get()->count();
    //                         break;
    //                     case 253: //todo  ENTEROCOCO FECAL
    //                         $model = DB::table('lote_detalle_enterococos')->where('Id_analisis', $detalle->Id_solicitud)->where('Id_parametro', $detalle->Id_parametro)->delete();
    //                         $asignado = LoteDetalleEnterococos::where('Id_lote', $detalle->Id_lote)->get()->count();
    //                         $liberado = LoteDetalleEnterococos::where('Id_lote', $detalle->Id_lote)->where('Liberado', 1)->get()->count();
    //                         break;
    //                     case 5: //todo DEMANDA BIOQUIMICA DE OXIGENO (DBO5) 
    //                     case 71:
    //                         $model = DB::table('lote_detalle_dbo')->where('Id_analisis', $detalle->Id_solicitud)->where('Id_parametro', $detalle->Id_parametro)->delete();
    //                         $asignado = LoteDetalleDbo::where('Id_lote', $detalle->Id_lote)->get()->count();
    //                         $liberado = LoteDetalleDbo::where('Id_lote', $detalle->Id_lote)->where('Liberado', 1)->get()->count();
    //                         break;
    //                     case 70:
    //                         $model = DB::table('lote_detalle_dboino')->where('Id_analisis', $detalle->Id_solicitud)->where('Id_parametro', $detalle->Id_parametro)->delete();
    //                         $asignado = LoteDetalleDboIno::where('Id_lote', $detalle->Id_lote)->get()->count();
    //                         $liberado = LoteDetalleDboIno::where('Id_lote', $detalle->Id_lote)->where('Liberado', 1)->get()->count();
    //                         break;
    //                     case 16: //todo Huevos de Helminto 
    //                         $model = DB::table('lote_detalle_hh')->where('Id_analisis', $detalle->Id_solicitud)->where('Id_parametro', $detalle->Id_parametro)->delete();
    //                         $asignado = LoteDetalleHH::where('Id_lote', $detalle->Id_lote)->get()->count();
    //                         $liberado = LoteDetalleHH::where('Id_lote', $detalle->Id_lote)->where('Liberado', 1)->get()->count();
    //                         break;
    //                     case 78:
    //                         $model = DB::table('lote_detalle_ecoli')->where('Id_analisis', $detalle->Id_solicitud)->where('Id_parametro', $detalle->Id_parametro)->delete();
    //                         $asignado = LoteDetalleEcoli::where('Id_lote', $detalle->Id_lote)->get()->count();
    //                         $liberado = LoteDetalleEcoli::where('Id_lote', $detalle->Id_lote)->where('Liberado', 1)->get()->count();
    //                         break;
    //                     default:
    //                         $model = DB::table('lote_detalle_directosa')->where('Id_analisis', $detalle->Id_solicitud)->where('Id_parametro', $detalle->Id_parametro)->delete();
    //                         $asignado = LoteDetalleDirectosA::where('Id_lote', $detalle->Id_lote)->get()->count();
    //                         $liberado = LoteDetalleDirectosA::where('Id_lote', $detalle->Id_lote)->where('Liberado', 1)->get()->count();
    //                         break;
    //                 }
    //                 break;
    //             default:
    //                 $model = DB::table('lote_detalle_directosa')->where('Id_analisis', $detalle->Id_solicitud)->where('Id_parametro', $detalle->Id_parametro)->delete();
    //                 $asignado = LoteDetalleDirectosA::where('Id_lote', $detalle->Id_lote)->get()->count();
    //                 $liberado = LoteDetalleDirectosA::where('Id_lote', $detalle->Id_lote)->where('Liberado', 1)->get()->count();
    //                 break;
    //         }
    //         $lote = LoteAnalisis::where('Id_lote', $detalle->Id_lote)->first();
    //         $lote->Asignado = $asignado->count();
    //         $lote->Liberado = $liberado->count();
    //         $lote->save();

    //         DB::table('codigo_parametroa')
    //             ->where('Id_codigo', $res->idCodigo)
    //             ->update(
    //                 [
    //                     'Asignado' => 0,
    //                     'Resultado' => "",
    //                     'Resultado2' => "",
    //                     'Id_lote'=> "",
    //                 ]
    //             );
    //         $msg = "Muestra regresada correctamente";
    //     } catch (\Throwable $th) {
    //         //throw $th;
    //         $msg = $th;
    //     }
    //     $data = array(
    //         'msg' => $msg,

    //     );
    //     return response()->json($data);
    // }
    
    public function reasignarMuestra(Request $res)
    {
        $msg = "";
        $detalle = DB::table('viewcodigoinformeAlimentos')->where('Id_codigo', $res->idCodigo)->first();

        if (!$detalle) {
            return response()->json(['msg' => 'Detalle no encontrado'], 404);
        }

        try {
            $asignado = 0;
            $liberado = 0;

            switch ($detalle->Id_area) {
                case 16: // Espectrofotometria
                case 5: // Fisicoquimicos
                    $model = DB::table('lote_detalle_espectro')->where('Id_analisis', $detalle->Id_solicitud)->where('Id_parametro', $detalle->Id_parametro)->delete();
                    $asignado = LoteDetalleEspectro::where('Id_lote', $detalle->Id_lote)->get()->count();
                    $liberado = LoteDetalleEspectro::where('Id_lote', $detalle->Id_lote)->where('Liberado', 1)->get()->count();
                    break;
                case 13: // G&A
                    $model = DB::table('lote_detalle_ga')->where('Id_analisis', $detalle->Id_solicitud)->where('Id_parametro', $detalle->Id_parametro)->delete();
                    $asignado = LoteDetalleGA::where('Id_lote', $detalle->Id_lote)->get()->count();
                    $liberado = LoteDetalleGA::where('Id_lote', $detalle->Id_lote)->where('Liberado', 1)->get()->count();
                    break;
                case 15: //Solidos
                    $model = DB::table('lote_detalle_solidos')->where('Id_analisis', $detalle->Id_solicitud)->where('Id_parametro', $detalle->Id_parametro)->delete();
                    $asignado = LoteDetalleSolidos::where('Id_lote', $detalle->Id_lote)->get()->count();
                    $liberado = LoteDetalleSolidos::where('Id_lote', $detalle->Id_lote)->where('Liberado', 1)->get()->count();
                    break;
                case 14: //volumetria
                    switch ($detalle->Id_parametro) {
                        case 6:
                        case 161:
                            $model = DB::table('lote_detalle_dqo')->where('Id_analisis', $detalle->Id_solicitud)->where('Id_parametro', $detalle->Id_parametro)->delete();
                            $asignado = LoteDetalleDqo::where('Id_lote', $detalle->Id_lote)->get()->count();
                            $liberado = LoteDetalleDqo::where('Id_lote', $detalle->Id_lote)->where('Liberado', 1)->get()->count();
                            break;
                        case 33:
                        case 218:
                        case 119:
                        case 64:
                            $model = DB::table('lote_detalle_cloro')->where('Id_analisis', $detalle->Id_solicitud)->where('Id_parametro', $detalle->Id_parametro)->delete();
                            $asignado = LoteDetalleCloro::where('Id_lote', $detalle->Id_lote)->get()->count();
                            $liberado = LoteDetalleCloro::where('Id_lote', $detalle->Id_lote)->where('Liberado', 1)->get()->count();
                            break;
                        case 9:
                        case 10:
                        case 11:
                        case 287:
                        case 83:
                        case 108:
                            $model = DB::table('lote_detalle_nitrogeno')->where('Id_analisis', $detalle->Id_solicitud)->where('Id_parametro', $detalle->Id_parametro)->delete();
                            $asignado = LoteDetalleNitrogeno::where('Id_lote', $detalle->Id_lote)->get()->count();
                            $liberado = LoteDetalleNitrogeno::where('Id_lote', $detalle->Id_lote)->where('Liberado', 1)->get()->count();
                            break;
                        case 28:
                        case 29:
                        case 30:
                        case 27:
                            $model = DB::table('lote_detalle_alcalinidad')->where('Id_analisis', $detalle->Id_solicitud)->where('Id_parametro', $detalle->Id_parametro)->delete();
                            $asignado = LoteDetalleAlcalinidad::where('Id_lote', $detalle->Id_lote)->get()->count();
                            $liberado = LoteDetalleAlcalinidad::where('Id_lote', $detalle->Id_lote)->where('Liberado', 1)->get()->count();
                            break;
                        default:
                            $model = DB::table('lote_detalle_directosa')->where('Id_analisis', $detalle->Id_solicitud)->where('Id_parametro', $detalle->Id_parametro)->delete();
                            $asignado = LoteDetalleDirectosA::where('Id_lote', $detalle->Id_lote)->get()->count();
                            $liberado = LoteDetalleDirectosA::where('Id_lote', $detalle->Id_lote)->where('Liberado', 1)->get()->count();
                            break;
                    }
                    break;
                case 7: // Campo
                case 19: // Directos
                    switch ($detalle->Id_parametro) {
                        case 102:
                            DB::table('lote_detalle_color')
                                ->where('Id_analisis', $detalle->Id_solicitud)
                                ->where('Id_parametro', $detalle->Id_parametro)
                                ->delete();

                            $asignado = LoteDetalleColor::where('Id_lote', $detalle->Id_lote)->count();
                            $liberado = LoteDetalleColor::where('Id_lote', $detalle->Id_lote)
                                ->where('Liberado', 1)
                                ->count();
                            break;

                        default:
                            DB::table('lote_detalle_directosa')
                                ->where('Id_analisis', $detalle->Id_solicitud)
                                ->where('Id_parametro', $detalle->Id_parametro)
                                ->delete();

                            $asignado = LoteDetalleDirectosA::where('Id_lote', $detalle->Id_lote)->count();
                            $liberado = LoteDetalleDirectosA::where('Id_lote', $detalle->Id_lote)
                                ->where('Liberado', 1)
                                ->count();
                            break;
                    }
                    break;
                case 8: //Potable
                    switch ($detalle->Id_parametro) {
                        case 77: //Dureza
                        case 103:
                        case 251:
                        case 252:
                            $model = DB::table('lote_detalle_dureza')->where('Id_analisis', $detalle->Id_solicitud)->where('Id_parametro', $detalle->Id_parametro)->delete();
                            $asignado = LoteDetalleDureza::where('Id_lote', $detalle->Id_lote)->get()->count();
                            $liberado = LoteDetalleDureza::where('Id_lote', $detalle->Id_lote)->where('Liberado', 1)->get()->count();
                            break;
                        default:
                            $model = DB::table('lote_detalle_potable')->where('Id_analisis', $detalle->Id_solicitud)->where('Id_parametro', $detalle->Id_parametro)->delete();
                            $asignado = LoteDetallePotable::where('Id_lote', $detalle->Id_lote)->get()->count();
                            $liberado = LoteDetallePotable::where('Id_lote', $detalle->Id_lote)->where('Liberado', 1)->get()->count();
                            break;
                    }
                    break;
                case 6: // Mb
                case 12:
                case 3:
                    switch ($detalle->Id_parametro) {
                        case 135: // Coliformes fecales
                        case 132:
                        case 133:
                        case 12:
                        case 134: // termotolerantes
                        case 35:
                        case 51: // Coliformes totales
                        case 137:
                        case 350:
                            $model = DB::table('lote_detalle_coliformes')->where('Id_analisis', $detalle->Id_solicitud)->where('Id_parametro', $detalle->Id_parametro)->delete();
                            $asignado = LoteDetalleColiformes::where('Id_lote', $detalle->Id_lote)->get()->count();
                            $liberado = LoteDetalleColiformes::where('Id_lote', $detalle->Id_lote)->where('Liberado', 1)->get()->count();
                            break;
                        case 253: //todo  ENTEROCOCO FECAL
                            $model = DB::table('lote_detalle_enterococos')->where('Id_analisis', $detalle->Id_solicitud)->where('Id_parametro', $detalle->Id_parametro)->delete();
                            $asignado = LoteDetalleEnterococos::where('Id_lote', $detalle->Id_lote)->get()->count();
                            $liberado = LoteDetalleEnterococos::where('Id_lote', $detalle->Id_lote)->where('Liberado', 1)->get()->count();
                            break;
                        case 5: //todo DEMANDA BIOQUIMICA DE OXIGENO (DBO5) 
                        case 71:
                            $model = DB::table('lote_detalle_dbo')->where('Id_analisis', $detalle->Id_solicitud)->where('Id_parametro', $detalle->Id_parametro)->delete();
                            $asignado = LoteDetalleDbo::where('Id_lote', $detalle->Id_lote)->get()->count();
                            $liberado = LoteDetalleDbo::where('Id_lote', $detalle->Id_lote)->where('Liberado', 1)->get()->count();
                            break;
                        case 70:
                            $model = DB::table('lote_detalle_dboino')->where('Id_analisis', $detalle->Id_solicitud)->where('Id_parametro', $detalle->Id_parametro)->delete();
                            $asignado = LoteDetalleDboIno::where('Id_lote', $detalle->Id_lote)->get()->count();
                            $liberado = LoteDetalleDboIno::where('Id_lote', $detalle->Id_lote)->where('Liberado', 1)->get()->count();
                            break;
                        case 16: //todo Huevos de Helminto 
                            $model = DB::table('lote_detalle_hh')->where('Id_analisis', $detalle->Id_solicitud)->where('Id_parametro', $detalle->Id_parametro)->delete();
                            $asignado = LoteDetalleHH::where('Id_lote', $detalle->Id_lote)->get()->count();
                            $liberado = LoteDetalleHH::where('Id_lote', $detalle->Id_lote)->where('Liberado', 1)->get()->count();
                            break;
                        case 78:
                            $model = DB::table('lote_detalle_ecoli')->where('Id_analisis', $detalle->Id_solicitud)->where('Id_parametro', $detalle->Id_parametro)->delete();
                            $asignado = LoteDetalleEcoli::where('Id_lote', $detalle->Id_lote)->get()->count();
                            $liberado = LoteDetalleEcoli::where('Id_lote', $detalle->Id_lote)->where('Liberado', 1)->get()->count();
                            break;
                        default:
                            $model = DB::table('lote_detalle_directosa')->where('Id_analisis', $detalle->Id_solicitud)->where('Id_parametro', $detalle->Id_parametro)->delete();
                            $asignado = LoteDetalleDirectosA::where('Id_lote', $detalle->Id_lote)->get()->count();
                            $liberado = LoteDetalleDirectosA::where('Id_lote', $detalle->Id_lote)->where('Liberado', 1)->get()->count();
                            break;
                    }
                    break;

                default:
                    DB::table('lote_detalle_directosa')
                        ->where('Id_analisis', $detalle->Id_solicitud)
                        ->where('Id_parametro', $detalle->Id_parametro)
                        ->delete();

                    $asignado = LoteDetalleDirectosA::where('Id_lote', $detalle->Id_lote)->count();
                    $liberado = LoteDetalleDirectosA::where('Id_lote', $detalle->Id_lote)
                        ->where('Liberado', 1)
                        ->count();
                    break;
            }

            $lote = LoteAnalisis::where('Id_lote', $detalle->Id_lote)->first();

            if ($lote) {
                $lote->Asignado = $asignado;
                $lote->Liberado = $liberado;
                $lote->save();
            }

            DB::table('codigo_parametroa')
                ->where('Id_codigo', $res->idCodigo)
                ->update([
                    'Asignado' => 0,
                    'Resultado' => null,
                    'Resultado2' => null,
                    'Id_lote' => null,
                ]);

            $msg = "Muestra regresada correctamente";
        } catch (\Throwable $th) {
            $msg = $th->getMessage();
        }

        return response()->json(['msg' => $msg]);
    }
}
