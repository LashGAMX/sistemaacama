<?php

namespace App\Http\Controllers\Clientes;

use App\Http\Controllers\Controller;
use App\Models\Clientes;
use App\Models\SucursalCliente;
use App\Models\ClienteGeneral;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClientesController extends Controller
{
    public $perPage = 500;
    public $idCliente;
    public $Search = '';


    public function getClientesGen(){
        $clienteGen = DB::table('Viewgenerales')->get();
        $data = array(
            'clienteGen' => $clienteGen
        );
        return response()->json($data);
    }

    public function setClientesGen(Request $res){
        $clientesCreado = Clientes::create([
            'Nombres' => $res->nombres,
            'Id_tipo_cliente' => 2,
            'Id_user_c' => Auth::user()->id,
            'Id_user_m' => Auth::user()->id
        ]);

        $clienteGeneralCreado = ClienteGeneral::create([
            'Id_cliente' => $clientesCreado->Id_cliente,
            // 'Nombre' => $res->nombres,
            'Empresa' => $res->nombres,
            'Id_intermediario' => $res->idIntermediario,
            'Id_user_c' => Auth::user()->id,
            'Id_user_m' => Auth::user()->id
        ]);

        if($res->activoCheck == "false"){
            $clientesCreado->delete();
            $clienteGeneralCreado->delete();
        }

        $data = array(
            'clientesCreado' => $clientesCreado,
            'clienteGeneralCreado' => $clienteGeneralCreado
        );

        return response()->json($data);
    }

    public function upClientesGen(Request $res){
        $clientesModificar = Clientes::withTrashed()->find($res->idCliente);
        $clienteGeneralModificar = ClienteGeneral::withTrashed()->where('Id_cliente', $clientesModificar->Id_cliente)->first();

        $clientesModificar->Nombres = $res->nombres;
        $clientesModificar->Id_user_m = Auth::user()->id;
        $clienteGeneralModificar->Empresa = $res->nombres;
        $clienteGeneralModificar->Id_intermediario = $res->idIntermediario;
        $clienteGeneralModificar->Id_user_m = Auth::user()->id;
        $clientesModificar->save();
        $clienteGeneralModificar->save();

        if($res->activoCheckEditar == "false"){
            $clientesModificar->delete();
            $clienteGeneralModificar->delete();
        }
        elseif($clientesModificar->deleted_at != null){ //&& $clienteGeneralModificar->deleted_at != null){
            $clientesModificar->restore();
            $clienteGeneralModificar->restore();
        }

        $data = array(
            'clientesModificar' => $clientesModificar,
            'clienteGeneralModificar' => $clienteGeneralModificar
        );

        return response()->json($data);
    }

    public function clientesGenDetalle($id){
        $clienteGen = DB::table('Viewgenerales')->where('Id_cliente', $id)->first();
        return view('clientes.clientesGenDetalle',compact('clienteGen'));
        
    }

    public function clientesGen(){
        return view('clientes.clientesgen');
    }
  
    public function TablaSucursal()
    {
        $model = SucursalCliente::withTrashed()->where('Id_cliente', $this->idCliente)->orderBy('Id_sucursal', 'desc')->paginate($this->perPage);
        $datos=array(
            'model' =>$model,
            
        );

       return response()->json($datos);
    }
    
    

}
