<?php

namespace App\Http\Controllers\Clientes;

use App\Http\Controllers\Controller;
use App\Models\SucursalCliente;
use App\Models\SucursalContactos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ClienteController extends Controller
{
    
    public function index() 
    {
        return view('clientes.cliente');
    }
    public function show($id)
    {
        $cliente = DB::table('ViewGenerales')->where('Id_cliente',$id)->first();
        return view('clientes.cliente_detalle',compact('cliente'));
    }

    public function details($id,$idSuc)
    {
        $cliente = DB::table('ViewGenerales')->where('Id_cliente',$id)->first();
        $sucursal = SucursalCliente::withTrashed()->where('Id_sucursal',$idSuc)->first();
        // var_dump($sucursal);
        return view('clientes.cliente_detalle',compact('cliente','idSuc','sucursal'));
    }
    public function datosGenerales(Request $request){
         $model = SucursalCliente::find($request->idUser);
         $model->Telefono = $request->telefono;
         $model->Correo = $request->correo;
         $model->Direccion = $request->direccion;
         $model->Atencion = $request->atencion;
         $model->save();

        $data = array(
            'sw' => true,
            'model' => $model
        );
        
        return response()->json($data);
    }
    public function getDatosGenerales(Request $res)
    {
        $model = SucursalContactos::where('Id_sucursal',$res->id)->get();
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
 
    public function setDatosGenerales(Request $res)
    {
        $model = SucursalContactos::create([
            'Id_sucursal' => $res->sucursal,
            'Nombre' => $res->nombre,
            'Departamento' => $res->departamento,
            'Puesto' => $res->puesto,
            'Email' => $res->correo,
            'Celular' => $res->cel,
            'Telefono' => $res->tel,
        ]);

        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }

    public function getContactoGeneral(Request $res)
    {
        $model = SucursalContactos::find($res->id);
        $data = array('model' => $model);
        return response()->json($data);
    }
    public function storeContactoGeneral(Request $res)
    {
        $model = SucursalContactos::find($res->id);
        $model->Id_sucursal = $res->sucursal;
        $model->Nombre = $res->nombre; 
        $model->Departamento = $res->departamento;
        $model->Puesto = $res->puesto;
        $model->Email = $res->correo;
        $model->Celular = $res->cel;
        $model->Telefono = $res->tel;
        $model->save();

        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
} 