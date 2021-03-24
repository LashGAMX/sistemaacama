<?php

namespace App\Http\Controllers\AnalisisQ;

use App\Http\Controllers\Controller;
use App\Models\Norma;
use App\Models\Parametro;
use App\Models\SubNorma;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NormaController extends Controller
{
    public function index()
    {
        return view('analisisQ.norma'); 
    }
    public function show($id) 
    {
        $norma = Norma::find($id);
        return view('analisisQ.detalle_normas',compact('id','norma'));
    }
    public function details($id,$idSub) 
    {
        $norma = Norma::find($id);
        $subnorma = SubNorma::find($idSub);
        $model = DB::table('ViewNormaParametro')->where('Id_norma',$idSub)->get();
        $parametros = Parametro::all();
        return view('analisisQ.detalle_normas',compact('id','norma','idSub','subnorma','model','parametros'));
    }
    public function create(Request $request)
    {
        var_dump($request->parametros);
    }
    public function getParametro()
    {
        $data = array(
            'model' => DB::table('ViewParametros')->get(),
        );
        return response()->json($data);
    }
}
  