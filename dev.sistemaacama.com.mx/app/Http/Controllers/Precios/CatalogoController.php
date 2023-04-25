<?php

namespace App\Http\Controllers\Precios;

use App\Http\Controllers\Controller;
use App\Models\Laboratorio;
use App\Models\Norma;
use App\Models\PrecioCatalogo;
use App\Models\Sucursal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CatalogoController extends Controller
{
    //
    public function index()
    {
        // $idUser = Auth::user()->id;
        $norma = Norma::all();
        $lab = Sucursal::all();
        return view('precios.catalogo', compact('norma', 'lab'));
    }
    public function details($idSucursal, $idNorma)
    {
        $idUser = Auth::user()->id;
        return view('precios.catalogo', compact('idSucursal', 'idNorma'));
    }
    public function getParametros()
    {
        $model = DB::table('ViewPrecioCat')->where('deleted_at',NULL)->get();
        $model2 = DB::table('ViewPrecioCat')->where('Revision',($model[0]->Revision-1))->get();
        $data = array(
            'model' => $model,
            'model2' => $model2,
        );
        return response()->json($data);
    }
    public function savePrecioCat(Request $res)
    {
        $model = PrecioCatalogo::find($res->id);
        $model->Precio = $res->precio;
        $model->save();

        $data = array(
            'model' => $model,
        );

        return response()->json($data);
    }
    public function setPrecioAnual(Request $res)
    {
        $model = PrecioCatalogo::all();
        foreach ($model as $item) {
            $desc = 0;
            $desc = ($item->Precio * $res->porcentaje) / 100;
            $precio = $item->Precio + $desc;
            $precioModel = PrecioCatalogo::find($item->Id_precio);

            $mod = $precioModel->replicate();
            $mod->Precio = round($precio);
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
