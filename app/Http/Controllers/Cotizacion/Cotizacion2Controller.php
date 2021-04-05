<?php

namespace App\Http\Controllers\Cotizacion;

use App\Http\Controllers\Controller;
use App\Http\Livewire\Cotizacion\Cotizacion;
use App\Models\Clientes;
use App\Models\DetallesTipoCuerpo;
use App\Models\IntermediariosView;
use App\Models\Norma;
use App\Models\SubNorma;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Cotizacion2Controller extends Controller
{
    //
    public function index()
    {
        //Vista Cotización
        $model = Cotizacion::All();
        $intermediarios = IntermediariosView::All();
        $cliente = Clientes::All();
        $norma = Norma::All();
        $clasificacion = DetallesTipoCuerpo::All();
        $subNormas = SubNorma::All();
        return view('cotizacion.cotizacion', compact('model', 'intermediarios', 'cliente', 'norma', 'subNormas'));
    }
    public function create()
    {
        $intermediarios = DB::table('ViewIntermediarios')->where('deleted_at',null)->get();
        $generales = DB::table('ViewGenerales')->where('deleted_at',null)->get();
        $normas = Norma::all();
        $subNormas = SubNorma::all();

        $data = array(
            'intermediarios' => $intermediarios,
            'generales' => $generales,
            'normas' => $normas,
            'subNormas' => $subNormas,
        );
        return view('cotizacion.create',$data);
    }
    public function getCliente() 
    { 
        $id = $_POST['cliente'];
        $model = DB::table('ViewGenerales')->where('Id_cliente',$id)->first();
        return response()->json($model);
    }
    public function getSubNorma()
    {
        $id = $_POST['norma'];
        $model = DB::table('sub_normas')->where('Id_norma',$id)->get();
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
}
