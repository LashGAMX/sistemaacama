<?php

namespace App\Http\Controllers\Alimentos;

use App\Http\Controllers\Controller;
use App\Http\Livewire\AnalisisQ\Parametros;
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
use App\Models\ParametrosMatriz;
use Mpdf\Tag\Select;
use App\Models\TipoServicios;
use App\Models\RecepcionAlimentos;
use App\Models\MatrizParametro;
use App\Models\Users;
use Carbon\Carbon;
use Mpdf\Mpdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Psy\Command\WhereamiCommand;

class AlimentosController extends Controller
{
   
    public function cotizacion()
    {
        return view('alimentos.cotizacion');
    }
    public function BitacoraRecep()
    {
        return view('alimentos.BiatcoraAlimentos');
    }
     public function HistorialRecepAli()
    {
        return view('alimentos.HistorialRecepAlimento');
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
        return view('alimentos.informes');
    }
    public function InformeData(Request $request)
    {
        $query = ProcesoAnalisisA::select('proceso_analisisa.Id_solicitud','proceso_analisisa.Folio','proceso_analisisa.Empresa','solicitudes_alimentos.Norma','solicitudes_alimentos.Servicio')->leftJoin('solicitudes_alimentos', 'proceso_analisisa.Id_solicitud', '=', 'solicitudes_alimentos.Id_solicitud');
          // Filtros
          if ($request->has('Id_solicitud')) {
              $query->where('proceso_analisisa.Id_solicitud', 'like', '%' . $request->Id_solicitud . '%');
          }
          if ($request->has('Folio')) {
              $query->where('proceso_analisisa.Folio', 'like', '%' . $request->Folio . '%');
          }
          if ($request->has('Empresa')) {
              $query->where('proceso_analisisa.Empresa', 'like', '%' . $request->Empresa . '%');
          }
          if ($request->has('Norma')) {
              $query->where('solicitudes_alimentos.Norma', 'like', '%' . $request->Norma . '%');
          }
          if ($request->has('Servicio')) {
              $query->where('solicitudes_alimentos.Servicio', 'like', '%' . $request->Servicio . '%');
          }
          
          // Orden y límite
          if (!$request->has('Id_solicitud') && !$request->has('Folio') && !$request->has('Empresa') && !$request->has('Norma') && !$request->has('Servicio')) {
              $query->orderBy('proceso_analisisa.Id_procAnalisis', 'desc')->take(1000);
          } else {
              $query->orderBy('proceso_analisisa.Id_procAnalisis', 'desc'); 
          }
          
          $model = $query->get();
          return response()->json($model);
    
        return response()->json($model);
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
        $clientes = DB::table('ViewClienteGeneral')->where('stdCliente', NULL)->get();
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
        $clientes = DB::table('ViewClienteGeneral')->where('stdCliente', NULL)->get();
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
        $proceso= ProcesoAnalisisA::where('Id_solicitud',$id)->exists();
        $normas = Norma::where('Id_tipo', 2)->get();
        $clientes = DB::table('ViewClienteGeneral')->where('stdCliente', NULL)->get();
        $servicios = TipoServicios::all();
        // $muestras = SolicitudMuestraA::where('Id_solicitud', $id)->get();
        $servicios = TipoServicios::all();
        $parametros = DB::table('viewparametros')->get();
        //    dd($proceso);
        $data = array(
            'proceso' => $proceso,
            'parametros' => $parametros,
            'model' => $model,
            // 'muestras' => $muestras,
            'servicios' => $servicios,
            'normas' => $normas,
            'clientes' => $clientes
        );
        return view('alimentos.editarOrden', $data);
    }
    public function editOrden2($id)
    {
        $model = SolicitudesAlimentos::find($id);
        // $normas = Norma::all();
        $normas = Norma::where('Id_tipo', 2)->get();
        $clientes = DB::table('ViewClienteGeneral')->where('stdCliente', NULL)->get();
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
        return view('alimentos.editarOrdenCI', $data);
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
            ->select('Id_parametro', 'Id_muestra', 'Id_matrizar')
            ->get()
            ->groupBy('Id_muestra');

        if (!$solicitud || $parametros->isEmpty()) {
            return response()->json(['error' => 'Datos no encontrados'], 404);
        }


        $contadorMuestra = 1;


        foreach ($parametros as $idMuestra => $parametrosDeMuestra) {
            foreach ($parametrosDeMuestra as $parametro) {
                CodigoParametroA::create([
                    'Id_solicitud' => $res->idsol,
                    'Id_parametro' => $parametro->Id_parametro,
                    'Id_matrizar' => $parametro->Id_matrizar,
                    'Codigo' => $solicitud->Folio . '-' . $contadorMuestra,
                    'Num_muestra' => $idMuestra,
                    'Asignado' => 0,
                    'Analizo' => 0,
                    'Reporte' => 1,
                    'Cadena' => 1,
                    'Mensual' => 1,
                    'Cancelado' => 0,
                ]);
            }
            $contadorMuestra++; // Aumenta para el siguiente código
        }



        // Respuesta si funciona
        return response()->json([
            'message' => 'Códigos creados correctamente',
            'Folio' => $solicitud->Folio,
            'ParametrosCount' => $parametros->count(),
        ]);
    }
    public function setSolicitud(Request $res)
        {
            $clientes  = DB::table('ViewClienteGeneral')->where('Id_cliente', $res->cliente)->first();
            $direccion = DireccionReporte::where('Id_direccion', $res->direccion)->first();
            $sucursal  = SucursalCliente::find($res->sucursal);
            $servicios = TipoServicios::find($res->servicio);
            $norma     = Norma::find($res->norma);
        
            $msg = "";
        
            // Datos base sin 'Creado_por' ni 'Actualizado_por'
            $datos = [
                'Num_muestras'   => $res->numTomas,
                'Id_cliente'     => $res->cliente,
                'Cliente'        => $clientes->Empresa,
                'Id_sucursal'    => $res->sucursal,
                'Sucursal'       => $sucursal->Empresa,
                'Fecha_muestreo' => $res->fechaMuestreo,
                'Id_direccion'   => $res->direccion,
                'Direccion'      => $direccion->Direccion,
                'Atencion'       => $res->atencion,
                'Id_contacto'    => $res->contacto,
                'Id_servicio'    => $res->servicio,
                'Servicio'       => $servicios->Servicio,
                'Id_norma'       => $res->norma,
                'Norma'          => $norma->Clave_norma,
                'Id_subnorma'    => $res->subnorma,
                'Observacion'    => $res->observacion,
                'Folio'          => $res->folio,
                'Estatus'        => 1,
            ];
        
            if (!empty($res->idSol)) {
                $model = SolicitudesAlimentos::find($res->idSol);
                $datos['Actualizado_por'] = $res->id;
                $model->fill($datos)->save();
                $msg = 'Solicitud actualizada';
            } else {
                $datos['Creado_por'] = $res->id;
        
                $model = SolicitudesAlimentos::create($datos);
                $msg   = 'Solicitud creada';
            }
        
            return response()->json([
                'model' => $model,
                'msg'   => $msg,
            ]);
        }
    
     
        // public function getOrden(Request $res)
        // {
    
        //     if ($res->eliminadas == 0) {
        //         $solicitudes = SolicitudesAlimentos::with('usuario:id,name','usuario2:id,name')->orderBy('created_at', 'desc')->get();
        //     } elseif ($res->eliminadas == 1) {
        //         $solicitudes = SolicitudesAlimentos::with('usuario:id,name','usuario2:id,name')->where('Cancelado', 1)->orderBy('created_at', 'desc')->get();
        //     } else {
        //         $solicitudes = SolicitudesAlimentos::with('usuario:id,name','usuario2:id,name')->orderBy('created_at', 'desc')->get();
        //     }
        //     return response()->json(['status' => 'success','data' => $solicitudes,
        //     ]);
        // }
    public function getOrden(Request $request)
    {    
      $modoCarga = $request->input('modoCarga', 'inicial'); // por defecto 'inicial'
      $eliminadas = $request->input('eliminadas', 0);
      $query = SolicitudesAlimentos::with('usuario:id,name', 'usuario2:id,name');
      if ($eliminadas == 1) {
          $query->where('Cancelado', 1);
      }
      $query->orderBy('created_at', 'desc');
      if ($modoCarga === 'inicial') {
          $solicitudes = $query->limit(1000)->get();
      } else {
          $solicitudes = $query->get();
      }  
      return response()->json([
          'status' => 'success',
          'data' => $solicitudes,
      ]);
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
            SolicitudMuestraA::create([
                'Id_solicitud' => $res->idSol,
            ]);

            $msg = "Se creó 1 Punto de Muestreo.";
        } catch (\Throwable $th) {
            $msg = "Error: " . $th->getMessage();
        }

        return response()->json([
            'msg' => $msg,
        ]);
    }
    public function setMuestraSol2(Request $res)
    {
        $msg = "";
        $idSol = $res->idSol;

        try {
            if (!$idSol) {
                $nuevaSolicitud = SolicitudesAlimentos::create([
                    'Estatus' => 1
                ]);
                $idSol = $nuevaSolicitud->Id_solicitud;
            }


            $numeroTomas = (int) $res->numTomas;
            for ($i = 0; $i < $numeroTomas; $i++) {
                SolicitudMuestraA::create([
                    'Id_solicitud' => $idSol,
                ]);
            }

            $msg = "Se crearon {$numeroTomas} Puntos de Muestreo.";
        } catch (\Throwable $th) {
            $msg = "Error: " . $th->getMessage();
        }

        return response()->json([
            'msg' => $msg,
            'idSol' => $idSol,
        ]);
    }
    public function getMuestraSol(Request $res)
    {

        $model = SolicitudMuestraA::where('Id_solicitud', $res->idSol)->get();
        $proceso= ProcesoAnalisisA::where('Id_solicitud',$res->idSol)->exists();

        // $parametros = DB::table('ViewParametros')->where('Id_area', 3)->get();
        $parametros = ParametrosMatriz::select('Id', 'Id_parametro', 'Id_matriz_parametro', 'Id_unidad', 'Limite')->with(['matriz:Id_matriz_parametro,Matriz', 'parametro:Id_parametro,Parametro', 'unidad:Id_unidad,Unidad'])->get()
            ->map(function ($item) {
                return [
                    'Id' => $item->Id,
                    'Id_parametro' => $item->parametro->Id_parametro ?? null,
                    'Parametro' => $item->parametro->Parametro ?? null,
                    'Matriz' => $item->matriz->Matriz ?? null,
                    'Unidad' => $item->unidad->Unidad ?? null,
                    'Limite' => $item->Limite,
                ];
            });

        $normas = Norma::where('Id_tipo', 2)->get();
        $solParam = array();

        foreach ($model as $item) {
            $temp = SolicitudParametrosA::where('Id_muestra', $item->Id_muestra)->get();
            array_push($solParam, $temp);
        }

        $data = array(
            'solParam' => $solParam,
            'proceso' => $proceso,
            'parametros' => $parametros,
            'model' => $model,
            'normas' => $normas,
        );
        return response()->json($data);
    }
    public function exportPdfOrden($id)
    {
        $solicitud = SolicitudesAlimentos::with(['contacto', 'servicio', 'usuario:id,name,firma'])->where('Id_solicitud', $id)->first();

        $muestras = SolicitudMuestraA::where('Id_solicitud', $id)->get();
        $parametros = SolicitudParametrosA::where('Id_solicitud', $id)->with('par')->get();
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
            'Idsolicitud' => $solicitud->Id_solicitud ? $solicitud->Id_solicitud : 'Sin Id',
            'folio' => $solicitud->Folio ? $solicitud->Folio : 'Sin Folio',
            'cliente' => $solicitud->Cliente ? $solicitud->Cliente : 'No existe un nombre del cliente',
            'direccion' => $solicitud->Direccion ? $solicitud->Direccion : 'No existe una dirección',
            'contacto' => $solicitud->contacto ? $solicitud->contacto->Nombre : 'Sin contacto',
            'numero' => $solicitud->contacto ? $solicitud->contacto->Telefono : 'Sin teléfono',
            'email' => $solicitud->contacto ? $solicitud->contacto->Email : 'No hay Email',
            'observacion' => $solicitud->Observacion ? $solicitud->Observacion : 'Sin observaciones',
            'servicio' => $solicitud->servicio ? $solicitud->servicio->Servicio : 'No existe ese servicio',
            'fecha' => $solicitud->Fecha_muestreo ? $solicitud->Fecha_muestreo : 'No hay fecha de muestreo',
            'hora' => $solicitud->Hora_muestreo ? $solicitud->Hora_muestreo : 'No hay hora de muestreo',
            'solicitud' => $solicitud,
            'muestras' => $muestras,
            'parametro' => $parametros,
            'creado_por' => $solicitud->usuario ? $solicitud->usuario->name : 'Usuario no disponible',
            'Firma' => $solicitud->usuario ? $solicitud->usuario->firma : 'no existe firma'

        ];

       $html = view('exports.alimentos.Orden.OrdenServicio', $data)->render();
       $footer = view('exports.alimentos.Orden.FooterOrdenServicio', $data)->render();
       
       $mpdf->CSSselectMedia = 'mpdf';
       
       $mpdf->SetHTMLFooter($footer);
       
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
        $contacto = DB::table('sucursal_contactos')->where('Id_contacto', $res->id)->get();
        return response()->json($contacto);
    }
    public  function getDatos(Request $res)
    {
        $contacto = DB::table('sucursal_contactos')->where('Id_contacto', $res->contacto_id)->select('Id_contacto', 'Nombre', 'Departamento', 'Puesto', 'Email', 'Celular', 'Telefono')->get();
        return response()->json($contacto);
    }
    public function getservicios()
    {
        $servicio = DB::table('tipo_servicios')->select('Id_tipo', 'Servicio')->orderBy('Id_tipo', 'asc')->get();
        return response()->json($servicio);
    }
    public function getNormas()
    {
        $norma = DB::table('normas')->where('Id_tipo', '=', 2)->select('Id_norma', 'Norma')->orderBy('Id_norma', 'asc')->get();
        return response()->json($norma);
    }
    public function getSubNorma(Request $request)
    {
        $id = $request->id;
        $subnorma = DB::table('sub_normas')->where('Id_norma', $id)->select('Id_subnorma', 'Clave')->get();
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
        $informe = DB::table('solicitudes_alimentos')->select('Id_solicitud', 'Folio', 'Cliente', 'Norma', 'Servicio')->orderBy('Id_solicitud', 'asc')->get();
        return response()->json($informe);
    }
    public function  ImprimirInforme($id)
    {
        $solicitud = SolicitudesAlimentos::with(['contacto', 'servicio'])->where('Id_solicitud', $id)->first();

        $mpdf = new Mpdf([
            'format' => 'letter',
            'margin_left' => 5,
            'margin_right' => 5,
            'margin_top' => 30,
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
        $proceso = ProcesoAnalisisA::where('Id_solicitud',  $muestra->Id_solicitud)->first();
        // $codigo = DB::table('viewcodigoparametrosali')->where('Id_solicitud', $muestra->Id_solicitud)->where('Num_muestra', $id)->get();
        $codigo = CodigoParametroA::where('Id_solicitud', $muestra->Id_solicitud)->where('Num_muestra', $id)
            ->with(['parametrosMatriz', 'parametro', 'parametro.simbologia', 'usuario'])->get();
        //    dd($codigo->parametro->mertodo->Clave_metodo);
        $repali = RecepcionAlimentos::where('Id_muestra', $id)->first();
      
        

        // var_dump($repali);
        $norma = Norma::where('Id_norma', $muestra->Id_norma)->first();
        // dd($codigo);
        $mpdf = new \Mpdf\Mpdf([
            'orientation' => 'P',
            'format' => 'letter',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 30,
            'margin_bottom' => 15,
            'defaultheaderfontstyle' => ['normal'],
            'defaultheaderline' => '0'
        ]);

        // $mpdf->SetWatermarkImage(
        //     asset('/public/storage/MembreteVertical.png'),
        //     1,
        //     array(215, 280),
        //     array(0, 0),
        // );

        // $mpdf->showWatermarkImage = true;

        $data = array(
            'repali' => $repali,
            'norma' => $norma,
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
    // public function setSaveMuestra(Request $res)
    // {
    //     $msg = "Datos guardados";
    //     $muestra = SolicitudMuestraA::where('Id_solicitud', $res->id)->first();
    //     $muestra->Muestra = $res->muestra;
    //     $muestra->Id_norma = $res->norma;
    //     $muestra->save();

    //     DB::table('solicitud_parametrosa')->where('Id_solicitud', $res->id)->delete();
    //     foreach ($res->parametros as $item) {
    //         $model = SolicitudParametrosA::create([
    //             'Id_muestra' => $res->id,
    //             'Id_solicitud' => $res->idSol,
    //             'Id_parametro' => $item,
    //         ]);
    //     }
    //     $data = array(
    //         'msg' => $msg,
    //     );
    //     return response()->json($data);
    // }

    // Metodo Opcional Para evitar eliminar y crear solo actualiza y crea datos Faltantes en SolicitudMuestraA Netzair 

    public function setSaveMuestra(Request $res)
    {


        $msg = "Datos guardados";
        $muestra = SolicitudMuestraA::where('Id_muestra', $res->id)->first();
        $muestra->Id_solicitud = $res->idSol;
        $muestra->Muestra = $res->muestra;
        $muestra->Tem_muestra = $res->tempMuetra;
        $muestra->Tem_recepcion = $res->temprecep;
        $muestra->Observacion = $res->Obs;
        $muestra->Unidad = $res->unidad;
        $muestra->Cantidad = $res->cant;
        $muestra->Motivo = $res->motivo;
        $muestra->Cumple = $res->cumple;



        $muestra->Id_norma = $res->norma;

        $muestra->save();


        if (is_array($res->parametros)) {

            $parametrosActuales = SolicitudParametrosA::where('Id_solicitud', $res->idSol)
                ->where('Id_muestra', $res->id)
                ->get();


            //Armar arreglo de claves compuestas para comparar (revisa los parametros para que no se repita el mismo )
            $clavesNuevas = collect($res->parametros)->map(function ($p) {
                return $p['Id_parametro'] . '-' . $p['Id_matrizar'];
            });



            //  Elimina los que ya no están en la nueva selección
            foreach ($parametrosActuales as $actual) {
                $clave = $actual->Id_parametro . '-' . $actual->Id_matrizar;
                if (!$clavesNuevas->contains($clave)) {
                    $actual->forceDelete(); // elimina de verdad en caso de que no lo requiera solo usar delete

                }
            }

            // Crea o actualiza los nuevos parametros editados por el usuario
            foreach ($res->parametros as $parametro) {
                SolicitudParametrosA::updateOrCreate(
                    [
                        'Id_solicitud' => $res->idSol,
                        'Id_muestra' => $muestra->Id_muestra,
                        'Id_parametro' => $parametro['Id_parametro'],
                        'Id_matrizar' => $parametro['Id_matrizar'],
                    ],
                    [] // puedes incluir otros campos si quieres
                );
            }
        } else {
            return response()->json(['msg' => 'Los parámetros no tienen un formato válido'], 400);
        }

        $data = array(
            'msg' => $msg,
        );
        return response()->json($data);
    }
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
      if (!$res->has('fecha') || empty($res->fecha)) {
          return response()->json(['msg' => 'Fecha no proporcionada', 'folio' => null]);
      }
      //1) verifica que la fecha 
      $temp = strtotime($res->fecha);
      if ($temp === false) {
          return response()->json(['msg' => 'Fecha inválida', 'folio' => null]);
      }
  
      $year = date("y", $temp);
      $dayYear = date("z", $temp) + 1;
       //2) verifica que no exista en solicitudes 
      $solicitud = null;
      if (!empty($res->id)) {
          $solicitud = SolicitudesAlimentos::where('Id_solicitud', $res->id)->first();
      }
  
      // 3) Si no existe o ID es null  crear nuevo registro
      if (!$solicitud) {
          $solicitud = new SolicitudesAlimentos();
          $solicitud->Fecha_muestreo = $res->fecha;
          $solicitud->Creado_por = Auth::user()->id;
          $solicitud->Estatus = 1;
          $solicitud->save(); 
      }
  
      // 4) Contar solicitudes en esa fecha con folio
      $solDay = SolicitudesAlimentos::where('Fecha_muestreo', $res->fecha)
          ->whereNotNull('Folio')
          ->count();
  
      // 5) Construcción del folio
      $folio = $dayYear . "A-" . ($solDay + 100) . "/" . $year;
  
      // 6) Verificar duplicados
      while (SolicitudesAlimentos::where('Folio', $folio)->exists()) {
          $solDay++;
          $folio = $dayYear . "A-" . ($solDay + 100) . "/" . $year;
      }
  
      // 7) Guardar folio (siempre se sustituye)
      $solicitud->Folio = $folio;
      $solicitud->save();
  
      return response()->json([
          'msg' => "Folio asignado o actualizado correctamente",
          'folio' => $folio,
          'id' => $solicitud->Id_solicitud
      ]);
  }
    public function buscarFolio(Request $res)
    {
        $Folio = $res->folio;
        $folio = SolicitudesAlimentos::where('Folio', $Folio)->select('Id_solicitud', 'Folio', 'Cliente', 'Sucursal')->first();
        if (!$folio) {
            return response()->json([
                'folio' => null,
                'muestra' => [],
                'codigos' => [],
                'proceso' => [],
            ]);
        }
        $proceso = ProcesoAnalisisA::where('Folio', $Folio)->select('Hora_recepcion', 'Hora_entrada', 'Ingreso', 'Id_recibio', 'Recibio', 'Fecha_muestreo')->first();
        $muestra = SolicitudMuestraA::where('Id_solicitud', $folio->Id_solicitud)->select('Id_muestra', 'Muestra', 'Tem_muestra', 'Tem_recepcion', 'Observacion',  'Unidad', 'Cantidad', 'Fecha_muestreo','Motivo','Cumple','Calculo','Cancelado')->get();
        //  dd($muestra);

        // $codigos = CodigoParametroA::where('Id_solicitud',$folio->Id_solicitud)->with('parametro')->get();
        $codigos = DB::table('codigo_parametroa')
        ->join('parametros', 'codigo_parametroa.Id_parametro', '=', 'parametros.Id_parametro')
        ->where('codigo_parametroa.Id_solicitud', $folio->Id_solicitud)
        ->select('codigo_parametroa.Codigo','codigo_parametroa.Cancelado', 'parametros.Parametro','parametros.Id_parametro')->get();

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
                // 'Fecha_muestreo' => $res->fechaMuestreo,
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
    public function getLote(Request $res)
    {
        $aux = array();
        if ($res->folio != "") {
            $temp = DB::table('codigo_parametroa')->where('Codigo', 'LIKE', '%' . $res->folio . '%')->where('Id_parametro', $res->id)->first();
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
    
        $folio = [];
        $normaArray = [];
        $punto = [];
        $fecha = [];
        $idCodigo = [];
        $historial = [];
        $fechahoy = [];
        $diasanalisisArray = [];
    
        // Buscar el lote
        $lote = DB::table('ViewLoteAnalisis')->where('Id_lote', $res->idLote)->first();
    
        if ($res->fecha == "") {
    
            // Códigos sin asignar
            $model = DB::table('codigo_parametroa')
                ->where('Asignado', '!=', 1)
                ->where('Id_parametro', $lote->Id_tecnica)
                ->where('Cancelado', '!=', 1)
                ->get();
    
            // IDs de solicitudes
            $solicitudesIds = $model->pluck('Id_solicitud')->unique();
    
            // Procesos válidos
            
            $procesos = ProcesoAnalisisA::whereIn('Id_solicitud', $solicitudesIds)
                ->get()
                ->groupBy('Id_solicitud');
    
            // Filtramos solo las solicitudes que tienen proceso
            $solicitudesValidas = $procesos->keys();
    
            // Puntos de muestreo válidos
            $puntos = SolicitudMuestraA::whereIn('Id_solicitud', $solicitudesValidas)
                ->get()
                ->groupBy('Id_solicitud');
    
            // Días de análisis válidos
            $diasanalisis = DB::table('viewcodigopendientesali')
                ->select('Id_solicitud', 'Dias_analisis')
                ->whereIn('Id_solicitud', $solicitudesValidas)
                ->get()
                ->groupBy('Id_solicitud');
    
            // Normas válidas
            $normasIds = $puntos->flatten()->pluck('Id_norma')->unique();
            $normas = Norma::whereIn('Id_norma', $normasIds)->pluck('Clave_norma', 'Id_norma');
    
            // Recorrer códigos y armar arrays
            foreach ($model as $item) {
                $idSolicitud = $item->Id_solicitud;
                $idMuestra = $item->Num_muestra ?? null;
    
                // Solo continuar si la solicitud es válida
                if (!$solicitudesValidas->contains($idSolicitud)) {
                    continue;
                }
    
                // Punto de muestreo
                $puntoModel = isset($puntos[$idSolicitud])
                    ? $puntos[$idSolicitud]->firstWhere('Id_muestra', $idMuestra)
                    : null;
    
                // Norma
                $norma = $puntoModel ? $normas->get($puntoModel->Id_norma) : null;
    
                // Proceso
                $proceso = $procesos[$idSolicitud]->first() ?? null;
    
                //  Filtro extra: solo pasara los registros completos
                if (!$proceso || !$item->Codigo || !$puntoModel) {
                    continue;
                }
    
                $idCodigo[] = $item->Id_codigo;
                $folio[] = $item->Codigo;
                $punto[] = $puntoModel->Muestra ?? '';
                $normaArray[] = $norma ?? '';
                $fecha[] = $proceso->Hora_recepcion ?? '';
                $historial[] = $item->Historial ?? null;
                $fechahoy[] = $hoy;
    
                // Días de análisis
                $diasanalisisArray[] = isset($diasanalisis[$idSolicitud])
                    ? $diasanalisis[$idSolicitud]->first()->Dias_analisis
                    : 0;
            }
        }
    
        // Respuesta final
        $data = [
            'procesos' => $procesos,
            'idCodigo' => $idCodigo,
            'model' => $model ?? [],
            'folio' => $folio,
            'norma' => $normaArray,
            'fecha' => $fecha,
            'punto' => $punto,
            'lote' => $lote,
            'historial' => $historial,
            'fechahoy' => $fechahoy,
            'diasanalisis' => $diasanalisisArray,
        ];
    
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
                    $model = LoteDetalleDirectosA::where('Id_lote', $res->idLote)->with("proceso")->with("codigo")->get();
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
                        $model->Analizo=Auth::user()->id;
                        if (strval($model->Resultado) != null) {
                            $sw = true;
                            $model->save();
                        }
                        if ($item->Id_control == 1) {
                            $modelCod = CodigoParametroA::find($model->Id_codigo);
                            $modelCod->Resultado = $model->Resultado;
                            $modelCod->Resultado2 = $model->Resultado;
                            $modelCod->Liberado=1;
                            $modelCod->Analizo = Auth::user()->id;
                            $modelCod->save();
                        }
                    }
                    $model = LoteDetalleDirectosA::where('Id_lote', $res->idLote)->where('Liberado', 1)->get();
                                        $model2 = LoteDetalleDirectosA::where('Id_lote', $res->idLote)->get();

                    break;
            }
        }
        $loteModel = LoteAnalisis::find($res->idLote);
        $loteModel->Liberado = $model->count();
        $loteModel->Asignado=$model2->count();
        $loteModel->save();
        $data = array(
            'model' => $model,
            'sw' => $sw,
        );
        return response()->json($data);
    }
    public function setLiberar(Request $res)
    {

        $lote = LoteAnalisis::where('Id_lote', $res->idLote)->first();

        $aux = Parametro::where('Id_parametro', $lote->Id_tecnica)->first();

        // $idLibero = $aux->Usuario_default != 0 ? $aux->Usuario_default : Auth::user()->id;

        $sw = false;

        $model = LoteDetalleDirectosA::find($res->idMuestra);
        $model->Liberado = 1;
        $model->Analizo=Auth::user()->id;

        if (!is_null($model->Resultado)) {
            $sw = true;
            $model->save();
        }

        if ($model->Id_control == 1) {
            $modelCod = CodigoParametroA::find($model->Id_codigo);
            $modelCod->Resultado = $model->Resultado;
            $modelCod->Resultado2 = $model->Resultado;
            $modelCod->Analizo =  Auth::user()->id;
            $modelCod->Liberado = 1;
            $modelCod->save();
        }

        $liberatedCount = LoteDetalleDirectosA::where('Id_lote', $res->idLote)->where('Liberado', 1)->count();
        $lote->Liberado = $liberatedCount;
        $lote->save();

        $loteanalisis = LoteAnalisis::where('Id_lote', $res->idLote)->get();
        $loteanalisis->Liberado = $liberatedCount;
        return response()->json([
            'model' => $model,
            'sw' => $sw,
        ]);
    }
    public function supervicion()
    {
           return view('alimentos.cadena');
    }
    public function setDataCadena(Request $request)
    {
        // 1  Construcción del query base  
        $query = DB::table('solicitudes_alimentos as sol')
            ->join('proceso_analisisa as pro', 'pro.Id_solicitud', '=', 'sol.Id_solicitud')
            ->select(
                'sol.Id_solicitud',
                'sol.Folio',
                'pro.Fecha_muestreo',
                'pro.Hora_recepcion',
                'sol.Cliente',
                'sol.Norma',
                'pro.Recibio',
                'sol.created_at',
                'sol.updated_at'
            );
    
        // 2 Total de registros antes de filtro
        $recordsTotal = $query->count();
    
        // 3 Búsqueda por columna
        foreach ($request->input('columns') as $index => $column) {
         $searchValue = $column['search']['value'] ?? null;
         if ($searchValue) {
             switch ($index) {
                 case 0:
                     $query->where('sol.Id_solicitud', 'like', "%{$searchValue}%");
                     break;
                 case 1:
                     $query->where('sol.Folio', 'like', "%{$searchValue}%");
                     break;
                 case 2:
                     $query->where('pro.Fecha_muestreo', 'like', "%{$searchValue}%");
                     break;
                 case 3:
                     $query->where('pro.Hora_recepcion', 'like', "%{$searchValue}%");
                     break;
                 case 4:
                     $query->where('sol.Cliente', 'like', "%{$searchValue}%");
                     break;
                 case 5:
                     $query->where('sol.Norma', 'like', "%{$searchValue}%");
                     break;
                 case 6:
                     $query->where('pro.Recibio', 'like', "%{$searchValue}%");
                     break;
                 case 7:
                     $query->where('sol.created_at', 'like', "%{$searchValue}%");
                     break;
                 case 8:
                     $query->where('sol.updated_at', 'like', "%{$searchValue}%");
                     break;
             }
         }
     }
     
         
        $recordsFiltered = $query->count();
    
        // 4 Ordenación dinámica
        $columns = [
            'sol.Id_solicitud',
            'sol.Folio',
            'pro.Fecha_muestreo',
            'pro.Hora_recepcion',
            'sol.Cliente',
            'sol.Norma',
            'pro.Recibio',
            'sol.created_at',
            'sol.updated_at'
        ];
    
        if ($order = $request->input('order.0')) {
            $colIndex = $order['column'];
            $dir = $order['dir'] ?? 'desc';
            if(isset($columns[$colIndex])) {
                $query->orderBy($columns[$colIndex], $dir);
            }
        } else {
            $query->orderBy('sol.created_at', 'desc'); 
        }
    
        // 5 Paginación
        $start = $request->input('start', 0);
        $length = $request->input('length', 1000); 
        $data = $query->offset($start)->limit($length)->get();
    
        // 6 Respuesta JSON
        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data
        ]);
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

            $idSolPunto = SolicitudMuestraA::where('Id_muestra', $res->idmuestra)->first();
            // dd($idSolPunto);

            $model = DB::table('viewcodigoparametrosali')
                ->where('Num_muestra', $res->idmuestra)
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
        $codigoModel = DB::table('viewcodigoparametrosalimentos')->where('Id_codigo', $res->idCodigo)->where('Id_parametro',$res->Id_parametro)->first();
        $paraModel = DB::table('ViewParametros')->where('Id_parametro', $res->Id_parametro)->first();
        switch ($paraModel->Id_parametro) {
            default:
                $model = DB::table('ViewLoteDetalleDirectosA')->where('Id_codigo', $res->idCodigo)
                    ->where('Id_control', 1)
                    ->where('Id_parametro', $res->Id_parametro)->get();
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

        // 4. Actualizar lote
        $firstItem = $model->first();
        $asignado = LoteDetalleDirectosA::where('Id_codigo', $res->idCodigo)->count();
        $liberado = LoteDetalleDirectosA::where('Id_codigo', $res->idCodigo)->where('Liberado', 1)->count();

        $codigo = CodigoParametroA::where('Id_codigo', $res->idCodigo)->first();
        $codigo->Liberado = 0;
        $codigo->Resultado2 = "";
        $codigo->Resultado = "";
        $codigo->save();

        if ($firstItem) {
            $lote = LoteAnalisis::where('Id_lote', $firstItem->Id_lote)->first();
            if ($lote) {
                $lote->Liberado = $liberado;
                $lote->Asignado = $asignado;
                $lote->save();
            }
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
        $request->validate([
            'Fini' => 'required|date',
            'Ffin' => 'required|date|after_or_equal:Fini',
        ]);

        $detalleSubfolios = [];

        $procesos = ProcesoAnalisisA::whereBetween('Hora_recepcion', [$request->Fini, $request->Ffin])
            ->with('user')
            ->get();

        foreach ($procesos as $proceso) {
            $idSolicitud = $proceso->Id_solicitud;
            $folio = $proceso->Folio;

            $muestras = SolicitudMuestraA::where('Id_solicitud', $idSolicitud)->get();
            $solicitud = SolicitudesAlimentos::where('Id_solicitud', $idSolicitud)->first();

            foreach ($muestras as $index => $muestra) {
                $parametros = SolicitudParametrosA::where('Id_muestra', $muestra->Id_muestra)
                    ->with('par')
                    ->get();

                $listaParametros = $parametros->map(function ($item) {
                    return $item->par->Parametro ?? '';
                })->filter()->values()->all();

                $detalleSubfolios[] = [
                    'subfolio'       => $folio . '-' . ($index + 1),
                    'muestra'        => $muestra->Muestra,
                    'tem_muestra'    => $muestra->Tem_muestra,
                    'tem_recepcion'  => $muestra->Tem_recepcion,
                    'observacion'    => $muestra->Observacion ?? '',
                    'unidad'         => $muestra->Unidad ?? '',
                    'cantidad'       => $muestra->Cantidad ?? '',
                    'calculo'        => $muestra->Calculo ?? 'N/A',
                    'cliente'        => $solicitud->Cliente ?? '',
                    'direccion'      => $solicitud->Direccion ?? '',
                    'atencion'       => $solicitud->Atencion ?? '',
                    'norma'          => $solicitud->Norma ?? '',
                    'hora_recepcion' => $proceso->Hora_recepcion ?? '',
                    'hora_entrada'   => $proceso->Hora_entrada ?? '',
                    'fechamuestreo'  => $muestra->Fecha_muestreo ?? '',
                    'recibio'        => $proceso->Recibio ?? '',
                    'motivo'         => $muestra->Motivo ?? '',
                    'cumple'         => $muestra->Cumple ?? '',
                    'parametros'     => $listaParametros,

                ];
            }
        }
        return response()->json($detalleSubfolios);
    }
    public function getbitacorasAlimentos(Request $request)
    {
        $request->validate([
            'Fini' => 'required|date',
            'Ffin' => 'required|date|after_or_equal:Fini',
        ]);

        $detalleSubfolios = [];

        $recepcion = RecepcionAlimentos::whereBetween('Hora_recepcion', [$request->Fini, $request->Ffin])->get();


        return response()->json($recepcion);
    }
    public function UpdateMuestra(Request $res)
    {
        $muestra = SolicitudMuestraA::where('Id_muestra', $res->Id_muestra)->first();

        if (!$muestra) {
            return response()->json(['error' => 'Muestra no encontrada'], 404);
        }

        $muestra->update([
            'Id_solicitud' => $res->idSol,
            'Fecha_muestreo' => $res->fechamuestreo,
            'Muestra' => $res->muestra,
            'Tem_muestra' => $res->tem_muestra,
            'Tem_recepcion' => $res->tem_recepcion,
            'Observacion' => $res->observacion,
            'Unidad' => $res->Num_unidad,
            'Cantidad' => $res->cantidad,
            'Calculo' => $res->Calculo,
            'Motivo' => $res->motivo,
            'Cumple' => $res->cumple,
        ]);

        // Responder con un mensaje de éxito
        return response()->json(['success' => 'Muestra  Actualizada Correctamente']);
    }
    public function getRecepcionAli()
    {
        $datos = RecepcionAlimentos::where('Estatus', '!=', 1)->orderBy('created_at', 'desc')->where('Cancelado', '!=', 1)->get();
        return response()->json($datos);
    }
    public function getRecepcion(Request $res)
    {
        // Obtener los datos de la base de datos
        $REPALI = RecepcionAlimentos::where('Id_rep', $res->idRep)->first();
        // Retornar los datos como respuesta JSON
        return response()->json($REPALI);
    }
    public function setDetalleMuestra(Request $res)
    {
        $lote = LoteAnalisis::where('Id_lote', $res->idLote)->get();
        switch ($lote[0]->Id_area) {

            case 3: // Default Directos
            case 12:
            case 19:
            case 6:
                $model = LoteDetalleDirectosA::where('Id_detalle', $res->idMuestra)->first();
                $model->Resultado  = $res->resultado;
                $model->save();
                break;
        

            default:
                $model = LoteDetalleDirectosA::where('Id_detalle', $res->idMuestra)->first();
                $model->Resultado = $res->observacion;
                $model->save();
                break;
        }
        $codigo = CodigoParametroA::where('Id_codigo', $model->Id_codigo)->first();
        $codigo->Resultado = $res->resultado;
        $codigo->save();
        $data = array(
            'model' => $model,
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
                }
                break;
            case 3: // Default Directos
                $model = LoteDetalleDirectosA::where('Id_detalle', $res->idMuestra)->first();
                $model->Observacion = $res->observacion;
                $model->save();
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
    public function reasignarMuestra2(Request $res)
    {
        $msg = "";
        $detalle = DB::table('codigo_parametroa')->where('Id_codigo', $res->idCodigo)->where('Id_solicitud',$res->idSol)->first();
       

        if (!$detalle) {
            return response()->json(['msg' => 'Detalle no encontrado'], 404);
        }
        
        try {
            $asignado = 0;
            $liberado = 0;

            DB::table('lote_detalle_directosa')->where('Id_analisis', $detalle->Id_solicitud)->where('Id_parametro', $detalle->Id_parametro)->where('Id_codigo', $detalle->Id_codigo)->delete();
            $asignado = LoteDetalleDirectosA::where('Id_lote', $detalle->Id_lote)->count();
            $liberado = LoteDetalleDirectosA::where('Id_lote', $detalle->Id_lote)->where('Liberado', 1)->count();
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
    public function IngresarRecepcion(Request $res)
    {
        
        $model = RecepcionAlimentos::where('Id_rep', $res->idrep)->first();

        $model->Fecha = $res->Fecha;
        $model->Fecha_inicio = $res->Fechainicio;
        $model->AnalistaRes = $res->Nombre;
        $model->AnalistaRecep = $res->Nombre2;
        $model->Resguardo = $res->resRecep;
        $model->Fecha_resguardo = $res->Fecha2;
        $model->Resguardo2 = $res->resRecep2;
        $model->Fecha_desecho = $res->Fecha3;
        $model->Analista_desecho = $res->analistadesecho;
        $model->Lugar_desecho = $res->Lugardedesecho;
        $model->Fecha_R_Alimento = $res->fechareal;
        $model->Fecha_R_Alimento = $res->fechareal;
        $model->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Recepción del Folio Registrada'
        ]);
    }
    public function setEmision(Request $res)
    {
        try {
            $model = ProcesoAnalisisA::where('Id_solicitud', $res->idSol)->first();
            $model->Periodo_analisis = $res->fecha;
            $model->save();

            $solModel = SolicitudesAlimentos::where('Id_solicitud', $res->idSol)->get();
            foreach ($solModel as $item) {
                $itemModel = ProcesoAnalisisA::where('Id_solicitud', $item->Id_solicitud)->first();
                $itemModel->Periodo_analisis = $res->fecha;
                $itemModel->save();
            }

            return response()->json(['msg' => 'Fecha modificada correctamente']);
        } catch (\Throwable $th) {
            return response()->json(['msg' => 'Error: ' . $th->getMessage()], 500);
        }
    }
    public function getDetalleMuestra(Request $res)
    {
        $model = array();
        $model2 = array();
        $curva = array();
        $blanco = array();
        $valoracion = array();
        $convinaciones = array();
        $d1 = array();
        $d2 = array();
        $dif1 = "Sin datos";
        $dif2 = "Sin datos";
        $nom1 = 'sin nombre';
        $nom2 = 'sin nombre';
        $phCampo = "";
        $masa = array();
        $conductividad = "";

        $lote = LoteAnalisis::where('Id_lote', $res->idLote)->get();
        if ($lote->count()) {
            switch ($lote[0]->Id_area) {

                case 7: //Campo
                case 19: //Directo
                case 3:
                    switch ($lote[0]->Id_tecnica) {
                        case 130:
                            break;
                        default:
                            $model = DB::table("viewlotedetalledirectosa")->where('Id_detalle', $res->id)->first();
                            break;
                    }
                    break;
                default:
                    $model = array();
                    break;
            }
        }

        $data = array(
            'd1' => $d1,
            'd2' => $d2,
            'model' => $model,
            'convinaciones' => $convinaciones,
            'model2' => $model2,
            'valoracion' => $valoracion,
            'curva' => $curva,
            'lote' => $lote,
            'blanco' => $blanco,
            'nom1' => $nom1,
            'nom2' => $nom2,
            'dif1' => $dif1,
            'dif2' => $dif2,
            'phCampo' => $phCampo,
            'masa' => $masa,
            'conductividad' => $conductividad,
        );
        return response()->json($data);
    }
    public function BitacoraPdf($Fini, $Ffin)
    {
        $model = RecepcionAlimentos::whereBetween('Hora_recepcion', [$Fini, $Ffin])->get();
        $mpdf = new \Mpdf\Mpdf([
            'orientation' => 'L',
            'format' => 'Legal',
            'margin_left' => 2,
            'margin_right' => 2,
            'margin_top' => 20,
            'margin_bottom' => 15,
            'defaultheaderfontstyle' => ['normal'],
            'defaultheaderline' => '0'
        ]);
        $mpdf->SetWatermarkImage(
            asset('/public/storage/HojaMembretadaHorizontal.png'),
            1,
            array(215, 280),
            array(0, 0),
        );
        $mpdf->showWatermarkImage = true;
        $htmlHeader = view('exports.alimentos.BitacoraAli.headerBitacora', ['model' => $model])->render();
        $htmlBitacora = view('exports.alimentos.BitacoraAli.bodyBitacora', ['model' => $model])->render();
        $htmlFooter = view('exports.alimentos.BitacoraAli.footerBitacora', ['model' => $model])->render();
        $mpdf->SetHTMLHeader('<div style="text-align: right; font-size: 10pt;">{PAGENO} / {nbpg}</div>' . $htmlHeader, 'O', true);
        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
        $mpdf->WriteHTML($htmlBitacora);
        $mpdf->Output('Bitacora de Recepcion de Alimentos.pdf', 'I');
    }
    public function parametros(Request $request)
    {
        $parametros = SolicitudParametrosA::where('Id_muestra', $request->idRep)
            ->with([
                'par:Id_parametro,Parametro',
                'parMat' => fn($q) => $q->select('Id', 'Limite', 'Id_unidad', 'Id_matriz_parametro')
                    ->with([
                        'unidad:Id_unidad,Unidad',
                        'matriz:Id_matriz_parametro,Matriz'
                    ]),
            ])->get();
        $resultado = $parametros->map(fn($p) => [
            'Id_parametro' =>  $p->par->Id_parametro,
            'parametro' => $p->par->Parametro,
            'limite'    => $p->parMat->Limite,
            'unidad'    => $p->parMat->unidad->Unidad,
            'matriz'    => $p->parMat->matriz->Matriz,
        ]);


        return response()->json($resultado);
    }
    public function getPendientes(Request $res)
    {
        $fechaHoy = Carbon::now()->toDateString();
        $model = array();
        $temp = array();
        // $codigo = DB::table('ViewCodigoPendientes')->where('Asignado', 0)->where('Cancelado','!=',1)->where('Liberado',0)->whereYear('Hora_recepcion', now()->year)->get();
        // $codigo = DB::table('viewcodigopendientesAli')->where('Asignado', 0)->where('Cancelado',  0)->where('Liberado', 0)->whereYear('created_at', 2025)->get();
       $codigo = DB::table('viewcodigopendientesAli')
    ->select('*')
    ->where('Asignado', 0)
    ->where('Cancelado', 0)
    ->where('Liberado', 0)
    ->whereYear('created_at', 2025)
    ->orderByRaw("SUBSTRING_INDEX(Codigo, '-', (LENGTH(Codigo) - LENGTH(REPLACE(Codigo, '-', '')))) ASC")
    ->orderByRaw("CAST(SUBSTRING_INDEX(Codigo, '-', -1) AS UNSIGNED) ASC")
    ->orderBy('Hora_recepcion', 'asc')
    ->get();


        

        $param = DB::table('ViewParametroUsuarios')->where('Id_user', Auth::user()->id)->get();

        foreach ($codigo as $item) {
            $temp = array();
            foreach ($param as $item2) {
            if ($item->Id_parametro == $item2->Id_parametro) {
                // Buscar el nombre de la matriz en la tabla matriz_parametros
                array_push($temp, $item->Codigo);
                array_push($temp, "(" . $item->Id_parametro . ") " . $item->Parametro);
                array_push($temp, $item->Matriz ? $item->Matriz : 'Sin Matriz(Revisar con alimento)');
                array_push($temp, $item->Hora_recepcion);
                array_push($temp, $item->Empresa);
                array_push($temp, $fechaHoy);
                array_push($temp, $item->Dias_analisis);

                array_push($model, $temp);

                break;
            }
            }
        }

        $data = array(
            'model' => $model,

        );
        return response()->json($data);
    }
    public function guardarCalculo(Request $res)
    {

        $validated = $res->validate([
            'id_muestra' => 'required|integer|exists:solicitud_muestrasa,Id_muestra',
            'texto'      => 'required|string',
        ]);

        $muestra = SolicitudMuestraA::find($validated['id_muestra']);
        $muestra->Calculo = $validated['texto'];
        $muestra->save();

        return response()->json([
            'message' => 'Cálculo guardado correctamente',
            'id_muestra' => $muestra->Id_muestra,
            'calculo' => $muestra->Calculo,
        ]);
    }
    public function setIncumplimiento(Request $res)
    {

        $model = SolicitudMuestraA::where('Id_solicitud', $res->id)->get();
        $num = explode(",", $res->nMuestra);
        $msg = "";
        if (count($num) > 1) {
            foreach ($num as $indice => $valor) {
                $model[$valor - 1]->Motivo = $res->motivoInc;
                $model[$valor - 1]->Cumple = 0;
                $model[$valor - 1]->save();
            }
            $msg = "Modificacion correcta";
        } else {
            $num = explode("-", $res->nMuestra);
            if (count($num) > 1) {
                for ($i = $num[0]; $i <= $num[1]; $i++) {
                    $model[$i - 1]->Motivo = $res->motivoInc;
                    $model[$i - 1]->Cumple = 0;
                    $model[$i - 1]->save();
                    $msg = "Modificacion correcta";
                }
            } else {
                $model[$res->nMuestra - 1]->Motivo = $res->motivoInc;
                $model[$res->nMuestra - 1]->Cumple = 0;
                $model[$res->nMuestra - 1]->save();
                $msg = "Modificacion correcta";
            }
        }

        $model = SolicitudMuestraA::where('Id_solicitud', $res->id)->get();
        $data = array(
            'res' => $res->nMuestra,
            'model' => $model,
            'msg' => $msg,
        );
        return response()->json($data);
    }
    public function LiberarReg(Request $res)
    {
       $model = RecepcionAlimentos::where('Id_rep', $res->idrep)->first();
    
       if ($model) {
           $model->Estatus = 1;
           $model->save();
    
          return response()->json([
               'status' => 'success',
               'message' => "Registro de Bitácora liberado {$model->Folio}"
           ]);
    
       } else {
           return response()->json([
               'status' => 'error',
               'message' => 'Registro no encontrado'
           ]);
       }
     }

     public function EntregaMuestra(Request $res)
{
    $model = RecepcionAlimentos::where('Id_rep', $res->idrep)->first();

    if ($model) {
        // Actualizamos el campo Entrega con el valor recibido
        $model->Entrega = $res->entrega;
        $model->save();

        // Determinamos el mensaje según el valor recibido
        $mensaje = $res->entrega == 1
            ? "Muestra {$model->Folio} Entregada"
            : "Muestra {$model->Folio} No Entregada";

        return response()->json([
            'status' => 'success',
            'message' => $mensaje
        ]);
    } else {
        return response()->json([
            'status' => 'error',
            'message' => 'Registro no encontrado'
        ]);
    }
}

     public function cancelarOrden(Request $res)
    {   
        $userId = auth()->id();                
    
     try {
    // Cancelar solicitud principal
    $sol = SolicitudesAlimentos::where('Id_solicitud', $res->id)->first();
    if ($sol) {
        
        $sol->Observacion = trim(($sol->Observacion ?? '')) . " -----------------------// " . trim($res->motivo);
        $sol->Actualizado_por = $userId;
        $sol->Cancelado = 1;
        $sol->save();
    }

    // Cancelar códigos de parámetros (todos)
    CodigoParametroA::where('Id_solicitud', $res->id)
        ->update(['Cancelado' => 1]);

    // Cancelar procesos de análisis (todos)
    ProcesoAnalisisA::where('Id_solicitud', $res->id)
        ->update(['Cancelado' => 1]);

    // Cancelar muestras (todas)
    $muestras = SolicitudMuestraA::where('Id_solicitud', $res->id)->get();
    foreach ($muestras as $muestra) {
        $muestra->Cancelado = 1;
        $leyenda = ' - Muestra Cancelada (No asignar A Lote)';
        $muestra->Muestra = trim(($muestra->Muestra ?? '')) . $leyenda;
        $muestra->save();
    }

    // Cancelar recepciones (todas)
    RecepcionAlimentos::where('Id_sol', $res->id)
        ->update(['Cancelado' => 1]);

    return response()->json(['success' => true]);
} catch (\Throwable $e) {
    // Puedes agregar el mensaje de error si estás en entorno de desarrollo
    return response()->json([
        'success' => false,
        'message' => 'Ocurrió un error inesperado.',
        'error' => $e->getMessage(), // <-- puedes quitar esto en producción
    ], 500);
}

    }
    public function DuplicarSolAlimentos(Request $res){
       
        $sol = SolicitudesAlimentos::where('Id_solicitud', $res->id)->firstOrFail();
        $newSol = $sol->replicate();
        $newSol->Cancelado = 0;
        $newSol->Creado_por = Auth::user()->id;
        $newSol->Actualizado_por = Auth::user()->id;
        $newSol->Folio= null;
        $newSol->save();
        
        // Duplicar las muestras primero
        $muestras = SolicitudMuestraA::where('Id_solicitud', $res->id)->get();
        $muestraMap = []; // [oldId => newId]
        
        foreach ($muestras as $muestra) {
            $oldId = $muestra->Id_muestra;
            $newMuestra = $muestra->replicate();
            $newMuestra->Cancelado = 0;
            $newMuestra->Id_solicitud = $newSol->Id_solicitud;
            $newMuestra->save();
        
            // Guardamos el mapeo del ID antiguo al nuevo
            $muestraMap[$oldId] = $newMuestra->Id_muestra;
        }
        
        // Ahora duplicar los parámetros asociados a esas muestras
        $solParList = SolicitudParametrosA::where('Id_solicitud', $res->id)->get();
        
        foreach ($solParList as $solPar) {
            $newPar = $solPar->replicate();
            $oldIdMuestra = $solPar->Id_muestra;
        
            if (isset($muestraMap[$oldIdMuestra])) {
                $newPar->Id_muestra = $muestraMap[$oldIdMuestra];
                $newPar->Id_solicitud = $newSol->Id_solicitud;
                $newPar->save();
            }
        }
        return response()->json(['success' => true, 'new_id' => $newSol->Id_solicitud]);
    }
    public function setFechaPA2(Request $res)
    {
        $temp = CodigoParametroA::where('Id_codigo', $res->idCod)->select('Id_solicitud')->first();
    
        if (!$temp) {
            return response()->json(['error' => 'Código no encontrado'], 404);
        }
    
        $proceso = ProcesoAnalisisA::where('Id_solicitud', $temp->Id_solicitud)->first();
    
        if (!$proceso) {
            return response()->json(['error' => 'Proceso no encontrado'], 404);
        }
    
        // Usar Carbon para parsear la fecha del input (YYYY-MM-DD)
        $proceso->Periodo_analisis =  $res->fecha;
        $proceso->save();
        
        $data = array(
            'proceso' => $proceso,
            'temp' => $temp,
            'fecha' => $res->fecha,
            );
    
        return response()->json($data);
    }
    public function capturaAlimentos(){
        $matriz = MatrizParametro::all();
        $data = array(
            'matriz' => $matriz,
        );
        return view('alimentos.capturaAlimentos',$data);
    }

}
