<?php

namespace App\Http\Controllers\AnalisisQ;

use App\Http\Controllers\Controller;
use App\Http\Livewire\AnalisisQ\NormaParametros;
use App\Models\Norma;
use App\Models\Parametro;
use App\Models\SubNorma;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PHPUnit\Util\Json;

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
    public function createNormaParametro()
    {
        @$parametros = $_POST['parametros'];
        $idSub = $_POST['idSub'];

        DB::table('norma_parametros')->where('Id_norma',$idSub)->delete();
        if(@$parametros != null)
        {
            foreach($parametros as $pa)
            {
                DB::table('norma_parametros')->insert([
                    'Id_norma' => $idSub,
                    'Id_parametro' => $pa
                ]);
            }      
        }

        $data = array(
            'sw' => true,
        );
        return response()->json($data);
    }
    public function getParametro()
    {
        $idSub = $_POST['idSub'];
        $model = DB::table('ViewNormaParametro')
        ->where('Id_norma',$idSub)
        ->get();
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function getParametroNorma()
    {
        $idSubNorma = $_POST['idSub'];
        $idNorma = $_POST['idNorma'];
        $normaModel = DB::table('ViewNormaParametro')->where('Id_norma',$idSubNorma)->get();
        $parametroModel = DB::table('ViewParametros')->where('Id_norma',$idNorma)->get();
        $data = array('sqlNorma' => $normaModel,'sqlParametro' => $parametroModel);
        return response()->json($data);
    }
}
