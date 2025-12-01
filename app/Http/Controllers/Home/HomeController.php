<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\CodigoParametros;
use App\Models\LoteAnalisis;
use App\Models\LoteDetalleDbo;
use App\Models\LoteDetalleNitrogeno;
use App\Models\Parametro;
use App\Models\ParametroNorma;
use App\Models\Sucursal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    //
    public function index($name)
    {
        
    }
    public function getRespaldoObservacion()
    {
        echo "Respaldo observacion";
        $model = LoteDetalleNitrogeno::where('Id_parametro',108)->where('Observacion','LIKE','%CANCELACION MASIVA%')->get();
        echo "Total: ".$model->count()."<br>";
        foreach ($model as $item) {
            echo "Id_lote: ".$item->Id_lote." - OBS: ".$item->Observacion.'<br>';
            $temp = DB::connection('mysqlrespaldo')->table('lote_detalle_nitrogeno')->where('Id_detalle',$item->Id_detalle)->first();
            
            $item->Observacion = $temp->Observacion;
            $item->save();
        }
    }
    public function getRegresarMuestraParametro()
    {
        $model = LoteDetalleNitrogeno::where('Id_lote',31434)->where('Liberado',0)->get();
        foreach ($model as $item) {
            $temp = CodigoParametros::where('Id_codigo',$item->Id_codigo)->first();
            $temp->Asignado = 0;
            $temp->save();

            LoteDetalleNitrogeno::withTrashed()->where('Id_detalle',$item->Id_detalle)->forceDelete();
        }

        $lot = LoteAnalisis::where('Id_lote',31434)->first();
        $lot->Asignado = 11;
        $lot->Liberado = 11;
        $lot->save();
    }
    public function create(Request $request)
    {
        echo "Nombre: ".$request->name;
        echo "Apellido: ".$_POST['last'];

    }
    public function jsonParametros()
    {
        echo "JsonParametros";
        $file = file_get_contents(asset('public/assets/json/parametros.json'));//Obtener archivo json
        $json = json_decode($file,true);//Leer json
        
        echo "Tamaño: ". sizeof($json['parametros']);
        // var_dump($json["parametros"][2]["Parametro"]);
        for ($i=0; $i < sizeof($json["parametros"]); $i++) { 
            $parametro = Parametro::where('Parametro',$json["parametros"][$i]["Parametro"])
                ->where('Id_tipo_formula',$json["parametros"][$i]["Id_tipo_formula"])
                ->where('Id_metodo',$json["parametros"][$i]["Id_metodo"])
                ->where('Id_matriz',$json["parametros"][$i]["Id_matriz"])
                ->where('Id_laboratorio',$json["parametros"][$i]["Id_laboratorio"])
                ->get();
             
            if($parametro->count())
            {
                $norma = ParametroNorma::create([
                    'Id_norma' => $json["parametros"][$i]["Id_norma"],
                    'Id_parametro' => $parametro[0]->Id_parametro,
                ]);
            }else{
                $model = Parametro::create([
                    'Id_laboratorio' => $json["parametros"][$i]["Id_laboratorio"],
                    'Id_tipo_formula' => $json["parametros"][$i]["Id_tipo_formula"],
                    'Id_area' => $json["parametros"][$i]["Id_area"],
                    'Id_rama' => $json["parametros"][$i]["Id_rama"],
                    'Parametro' => $json["parametros"][$i]["Parametro"],
                    'Id_unidad' => $json["parametros"][$i]["Id_unidad"],
                    'Id_metodo' => $json["parametros"][$i]["Id_metodo"],
                    'Limite' => $json["parametros"][$i]["Limite"],
                    'Id_procedimiento' => $json["parametros"][$i]["Id_procedimiento"],
                    'Id_matriz' => $json["parametros"][$i]["Id_matriz"],
                    'Id_simbologia' => $json["parametros"][$i]["Id_simbologia"],
                    'F_inicio_vigencia' => $json["parametros"][$i]["F_inicio_vigencia"],
                    'F_fin_vigencia' => $json["parametros"][$i]["F_fin_vigencia"],
                    'Envase' => $json["parametros"][$i]["Envase"],
                    'Id_user_c' => $json["parametros"][$i]["Id_user_c"],
                    'Id_user_m' => $json["parametros"][$i]["Id_user_m"],
                ]);
                $norma = ParametroNorma::create([
                    'Id_norma' => $json["parametros"][$i]["Id_norma"],
                    'Id_parametro' => $model->Id_parametro,
                ]);
            }
        }
    }

    public function ordenJson()
    {
     return view('home.ordenJson');
    }

    public function login()
    {
        return view('voyager::login');
    }
    
}
