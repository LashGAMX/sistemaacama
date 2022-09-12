<?php

namespace App\Http\Controllers\Precios;

use App\Http\Controllers\Controller;
use App\Models\Norma;
use App\Models\PrecioPaquete;
use App\Models\SubNorma;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaqueteController extends Controller
{
    //
    public function index()
    {
        $idUser = Auth::user()->id;
        $norma = Norma::all();
        return view('precios.paquete', compact('norma','idUser'));
    }
    public function getPaquetes(Request $res)
    {
        if($res->idNorma == 0)
        {
            $model = DB::table('ViewPrecioPaquete')->get();
        }else{
            $model = DB::table('ViewPrecioPaquete')->where('Id_norma',$res->idNorma)->get();
        }
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function savePrecioPaq(Request $res)
    {
        $model = PrecioPaquete::find($res->id);
        $model->Precio1 = $res->precio1;
        $model->Precio2 = $res->precio2;
        $model->Precio3 = $res->precio3;
        $model->Precio4 = $res->precio4;
        $model->Precio5 = $res->precio5;
        $model->Precio6 = $res->precio6;
        $model->save();

        $data = array(
            'model' => $model,
        );

        return response()->json($data);
    }
}
