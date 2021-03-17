<?php

namespace App\Http\Controllers\Clientes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClienteController extends Controller
{
    //
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
        return view('clientes.cliente_detalle',compact('cliente','idSuc'));
    }
}
 