<?php

namespace App\Http\Controllers\Cotizacion;

use App\Http\Controllers\Controller;
use App\Http\Livewire\AnalisisQ\LimiteParametros001;
use App\Models\ContactoCliente;
use App\Models\DireccionReporte;
use App\Models\Intermediario;
use App\Models\PuntoMuestreoGen;
use App\Models\SucursalCliente;
use App\Models\TipoDescarga;
use App\Models\TipoServicios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SolicitudController extends Controller
{
    // 
    public function index()
    {
        return view('cotizacion.solicitud');
    } 
    public function create()
    {
        $intermediario = DB::table('ViewIntermediarios')->get();
        $cliente = DB::table('ViewGenerales')->get();
        $servicios = TipoServicios::all();
        $descargas = TipoDescarga::all();
        $frecuencia = DB::table('frecuencia001')->get();
        

        return view('cotizacion.createSolicitud',
        compact(
            'intermediario',
            'cliente',
            'servicios',
            'descargas',
            'frecuencia',
        ));
    }
    public function getSucursal(Request $request)
    {
        $contacto = ContactoCliente::where('Id_cliente',$request->cliente)->get();
        $sucursal = SucursalCliente::where('Id_cliente',$request->cliente)->get();
        $data = array(
            'idCliente' => $request->cliente,
            'sucursal' => $sucursal,
            'contacto' => $contacto,
        );
        return response()->json($data);
    }
    public function getDatoIntermediario(Request $request)
    {
        $intermediario = DB::table('ViewIntermediarios')->where('Id_cliente',$request->idCliente)->first();
        $data = array(
            'intermediario' => $intermediario,
        );
        return response()->json($data);
    }
    public function getDireccionReporte(Request $request)
    {
        // $direccion = DB::table('di')
        $direccion = DireccionReporte::where('Id_sucursal',$request->idSucursal)->get();
        $data = array(
            'direccion' => $direccion,
        );
        return response()->json($data);
    }
    public function setContacto(Request $request)
    {
        ContactoCliente::create([
            'Id_cliente' => $request->idCliente,
            'Nombres' => $request->nombre,
            'A_paterno' => $request->paterno,
            'A_materno' => $request->materno,
            'Celular' => $request->celular,
            'Telefono' => $request->telefono,
            'Email' => $request->correo,
        ]);

        $model = ContactoCliente::Where('Id_cliente',$request->idCliente)->get();

        $data = array(
            'model' => $model,
        );

        return response()->json($data);
    }
    public function getDataContacto(Request $request)
    {
        $model = ContactoCliente::where('Id_contacto',$request->idContacto)->first();
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function getPuntoMuestro(Request $request)
    {
        $model = PuntoMuestreoGen::where('Id_sucursal',$request->idSuc)->get();
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
}
  
 