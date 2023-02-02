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

        return view('cotizacion.createSolicitud',$data);
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
}
