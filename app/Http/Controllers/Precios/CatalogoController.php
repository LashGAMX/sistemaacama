<?php

namespace App\Http\Controllers\Precios;

use App\Http\Controllers\Controller;
use App\Models\Laboratorio;
use App\Models\Norma;
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
        return view('precios.catalogo',compact('norma','lab'));
    }
    public function details($idSucursal, $idNorma)
    {
        $idUser = Auth::user()->id;
        return view('precios.catalogo',compact('idSucursal', 'idNorma')); 
    }
    public function getParametros()
    {
        $model = DB::table('ViewPrecioCat')->get();
        $data = array(
            'model' => $model,
         );
        return response()->json($data);
    }
}
