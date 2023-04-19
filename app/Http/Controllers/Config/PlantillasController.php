<?php

namespace App\Http\Controllers\Config;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Laboratorio\FqController;
use App\Models\PlantillaDirectos;
use App\Models\PlantillaMb;
use App\Models\PlantillaPotable;
use App\Models\PlantillasFq;
use App\Models\PlantillaVolumetria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PlantillasController extends Controller
{
    //
    public function index()
    {
        $idUser = Auth::user()->id;
        return view('config/plantillas/index', compact('idUser'));
    }
    public function bitacoras($tipo)
    {
        switch ($tipo) {
            case 1: //Fq
                $model = PlantillasFq::all();
                break;
            case 2: // Directos
                $model = PlantillaDirectos::all();
                break;
            case 3: // Mb
                $model = PlantillaMb::all();
                break;
            case 4: // Potable
                $model = PlantillaPotable::all();
                break;
            case 5: // Volumetria
                $model = PlantillaVolumetria::all();
                break;
            default:

                break;
        }
        $parametros = DB::table('ViewParametros')->get();
        $data = array(
            'parametros' => $parametros,
            'model' => $model,
            'tipo' => $tipo,
        );
        return view('config/plantillas/bitacoras', $data);
    }
    public function getPlantillas(Request $res)
    {
        switch ($res->tipo) {
            case 1: //Fq
                $model  = DB::table('ViewPlantillasFq')->get();
                break;
            case 2: // Directos
                $model  = DB::table('ViewPlantillasDirectos')->get();
                break;
            case 3: // Mb
                $model  = DB::table('ViewPlantillasMb')->get();
                break;
            case 4: // Potable
                $model  = DB::table('ViewPlantillasPotable')->get();
                break;
            case 5: // Volumetria
                $model  = DB::table('ViewPlantillasVolumetria')->get();
                break;
            default:

                break;
        }

        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function getDetalleBitacora(Request $res)
    {
        switch ($res->tipo) {
            case 1: // Fq
                $model = PlantillasFq::where('Id_plantilla', $res->id)->get();
                break;
            case 2: // Directos
                $model = PlantillaDirectos::where('Id_plantilla', $res->id)->get();
                break;
            case 3: // Mb
                $model = PlantillaMb::where('Id_plantilla', $res->id)->get();
                break;
            case 4: // Potable
                $model = PlantillaPotable::where('Id_plantilla', $res->id)->get();
                break;
            case 5: // Volumetria
                $model = PlantillaVolumetria::where('Id_plantilla', $res->id)->get();
                break;
            default:
                # code...
                break;
        }
        $data = array(
            'tipo' => $res->tipo,
            'model' => $model,
        );
        return response()->json($data);
    }
    public function setPlantilla(Request $res)
    {
        switch ($res->tipo) {
            case 1: // Fq
                $model = PlantillasFq::where('Id_plantilla', $res->id)->get();
                $model[0]->Texto = $res->texto;
                $model[0]->Titulo = $res->titulo;
                $model[0]->Rev = $res->rev;
                $model[0]->save();
                break;
            case 2: // Directos
                $model = PlantillaDirectos::where('Id_plantilla', $res->id)->get();
                $model[0]->Texto = $res->texto;
                $model[0]->Titulo = $res->titulo;
                $model[0]->Rev = $res->rev;
                $model[0]->save();
                break;
            case 3: // Mb
                $model = PlantillaMb::where('Id_plantilla', $res->id)->get();
                $model[0]->Texto = $res->texto;
                $model[0]->Titulo = $res->titulo;
                $model[0]->Rev = $res->rev;
                $model[0]->save();
                break;
            case 4: // Potable
                $model = PlantillaPotable::where('Id_plantilla', $res->id)->get();
                $model[0]->Texto = $res->texto;
                $model[0]->Titulo = $res->titulo;
                $model[0]->Rev = $res->rev;
                $model[0]->save();
                break;
            case 5: // Volumetria
                $model = PlantillaVolumetria::where('Id_plantilla', $res->id)->get();
                $model[0]->Texto = $res->texto;
                $model[0]->Titulo = $res->titulo;
                $model[0]->Rev = $res->rev;
                $model[0]->save();
                break;
            default:
                # code...
                break;
        }
        $data = array(
            'tipo' => $res->tipo,
            'model' => $model,
        );
        return response()->json($data);
    }
    public function setNewPlantilla(Request $res)
    {
        switch ($res->tipo) {
            case 1: // Fq
                $model = PlantillasFq::where('Id_parametro', $res->id)->get();
                if ($model->count()) {
                } else {
                    $model = PlantillasFq::create([
                        'Id_parametro' => $res->id,
                        'Texto' => "Falta registrar procedimiento",
                    ]);
                }
                break;
            case 2: // Directos
                $model = PlantillaDirectos::where('Id_parametro', $res->id)->get();
                if ($model->count()) {
                } else {
                    $model = PlantillaDirectos::create([
                        'Id_parametro' => $res->id,
                        'Texto' => "Falta registrar procedimiento",
                    ]);
                }
                break;
            case 3: // Mb
                $model = PlantillaMb::where('Id_parametro', $res->id)->get();
                if ($model->count()) {
                } else {
                    $model = PlantillaMb::create([
                        'Id_parametro' => $res->id,
                        'Texto' => "Falta registrar procedimiento",
                    ]);
                }
                break;
            case 4: // Potable
                $model = PlantillaPotable::where('Id_parametro', $res->id)->get();
                if ($model->count()) {
                } else {
                    $model = PlantillaPotable::create([
                        'Id_parametro' => $res->id,
                        'Texto' => "Falta registrar procedimiento",
                    ]);
                }
                break;
            case 5: // Volumetria
                $model = PlantillaVolumetria::where('Id_parametro', $res->id)->get();
                if ($model->count()) {
                } else {
                    $model = PlantillaVolumetria::create([
                        'Id_parametro' => $res->id,
                        'Texto' => "Falta registrar procedimiento",
                    ]);
                }
                break;
            default:
                # code...
                break;
        }
        $data = array(
            'tipo' => $res->tipo,
            'model' => $model,
        );
        return response()->json($data);
    }
}
