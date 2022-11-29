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
        $paquete = SubNorma::all();
        return view('precios.paquete', compact('norma','idUser','paquete'));
    }
    public function getPaquetes(Request $res)
    {
        if($res->idNorma == 0)
        {
            $model = DB::table('ViewPrecioPaquete')->where('deleted_at',NULL)->get();
        }else{
            $model = DB::table('ViewPrecioPaquete')->where('Id_norma',$res->idNorma)->where('deleted_at',NULL)->get();
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
    public function setPrecioPaquete(Request $res)
    {
        $model = PrecioPaquete::where('Id_paquete')->get();
        if($model->count())
        { 
            $sw = false;
        }else{
            $model = PrecioPaquete::create([
                'Id_paquete' => $res->id,
            ]);
            $sw = true;
        }
        $data = array(
            'sw' => $sw,
            'model' => $model,
        );
        return response()->json($data);
    }
    public function setPrecioAnual(Request $res)
    {
        $model = PrecioPaquete::all();
        foreach ($model as $item) {
            
            $temp1 = ($item->Precio1 * $res->porcentaje) / 100;
            $precio1 = $item->Precio1 + $temp1;
            
            $temp2 = ($item->Precio2 * $res->porcentaje) / 100;
            $precio2 = $item->Precio2 + $temp2;

            $temp3 = ($item->Precio3 * $res->porcentaje) / 100;
            $precio3 = $item->Precio3 + $temp3;

            $temp4 = ($item->Precio4 * $res->porcentaje) / 100;
            $precio4 = $item->Precio4 + $temp4;

            $temp5 = ($item->Precio5 * $res->porcentaje) / 100;
            $precio5 = $item->Precio5 + $temp5;

            $temp6 = ($item->Precio6 * $res->porcentaje) / 100;
            $precio6 = $item->Precio6 + $temp6;

            $precioModel = PrecioPaquete::find($item->Id_precio);

            $mod = $precioModel->replicate();
            $mod->Precio1 = round($precio1);
            $mod->Precio2 = round($precio2);
            $mod->Precio3 = round($precio3);
            $mod->Precio4 = round($precio4);
            $mod->Precio5 = round($precio5);
            $mod->Precio6 = round($precio6);
            $mod->Revision = $precioModel->Revision + 1;
            $mod->Id_user_c = Auth::user()->id;
            $mod->Id_user_m = Auth::user()->id;
            $mod->save();

            $precioModel->delete();
        }

        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
}
