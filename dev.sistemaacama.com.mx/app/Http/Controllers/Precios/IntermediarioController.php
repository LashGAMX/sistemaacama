<?php

namespace App\Http\Controllers\Precios;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;

class IntermediarioController extends Controller
{
    
    public function index()
    {
        return view('precios.intermediario');
    }
    public function details($idCliente)
    {
        $model = DB::table('ViewDetalleInter')->where('Id_cliente',$idCliente)->first();
        return view('precios.precioIntermediario',compact('idCliente','model')); 
    }
}
 