<?php

namespace App\Http\Controllers;

use App\Http\Livewire\Pruebas\Prueba;
use App\Models\PruebaPatrullajeEvidencia;
use App\Models\PruebaPatrullajeGeneral;
use App\Models\PruebaPatrullajeMapa;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PruebaController extends Controller
{
    public function prueba()
    {
        return view('prueba');
    }
    public function guardar(Request $request){
       $jsonPatrullajeGeneral = json_decode($request->patrullajeGeneral,true);
       $jsonPatrullajeMapa = json_decode($request->patrullajeMapa,true);
       $jsonPatrullajeEvidencia = json_decode($request->patrullajeEvidencia,true);

       //$evidenciaExplode = explode()

        $comprobarGenerales = PruebaPatrullajeGeneral::where("Folio",$request->folio)->get();
        if($comprobarGenerales->count()){
            $patrullajeGeneral =  PruebaPatrullajeGeneral::Where("Id_general",$comprobarGenerales[0]->Id_general)->first();
            $patrullajeGeneral->Fecha = $jsonPatrullajeGeneral[0]["Fecha"];
            $patrullajeGeneral->Calle = $jsonPatrullajeGeneral[0]["Calle"];
            $patrullajeGeneral->Colonia = $jsonPatrullajeGeneral[0]["Colonia"];
            $patrullajeGeneral->NumExt = $jsonPatrullajeGeneral[0]["NumExt"];
            $patrullajeGeneral->NumInt = $jsonPatrullajeGeneral[0]["NumInt"];
            $patrullajeGeneral->Tipo = $jsonPatrullajeGeneral[0]["Tipo"];
            $patrullajeGeneral->Zona = $jsonPatrullajeGeneral[0]["Zona"];
            $patrullajeGeneral->Descripcion = $jsonPatrullajeGeneral[0]["Descripcion"];
            $patrullajeGeneral->save();
        } else {
            $patrullajeGeneral =  PruebaPatrullajeGeneral::create([
                'Folio' => $jsonPatrullajeGeneral[0]["Folio"],
                'Fecha' => $jsonPatrullajeGeneral[0]["Fecha"],
                'Calle' => $jsonPatrullajeGeneral[0]["Calle"],
                'Colonia' => $jsonPatrullajeGeneral[0]["Colonia"],
                'NumExt' => $jsonPatrullajeGeneral[0]["NumExt"],
                'NumInt' => $jsonPatrullajeGeneral[0]["NumInt"],
                'Tipo' => $jsonPatrullajeGeneral[0]["Tipo"],
                'Zona' => $jsonPatrullajeGeneral[0]["Zona"],
                'Descripcion' => $jsonPatrullajeGeneral[0]["Descripcion"],
            ]);
       }
        // MAPA

        $comprobarMapa = PruebaPatrullajeMapa::where('Folio', $request->folio)->get();

        if($comprobarMapa->count()){
            $patrullajeMapa = PruebaPatrullajeMapa::where("Id_mapa", $comprobarMapa[0]->Id_mapa)->first();
            $patrullajeMapa->Latitud = $jsonPatrullajeMapa[0]["Latitud"];
            $patrullajeMapa->Longitud = $jsonPatrullajeMapa[0]["Longitud"];
            $patrullajeMapa->save();
            
         } else {
            $patrullajeMapa = PruebaPatrullajeMapa::create([
                'Folio' => $jsonPatrullajeMapa[0]["Folio"],
                'Latitud' => $jsonPatrullajeMapa[0]["Latitud"],
                'Longitud' => $jsonPatrullajeMapa[0]["Longitud"],
            ]);
        }

        //Evidencia

        // $compobarEvidencia = PruebaPatrullajeEvidencia::where('Folio', $request->folio)->get();
        // if($compobarEvidencia->count()){
        //     $patrullajeEvidencia = PruebaPatrullajeEvidencia::where("Id_evidencia", $compobarEvidencia[0]->Id_evidencia)->first();
        //     $patrullajeEvidencia->Folio = $jsonPatrullajeEvidencia[0]["Folio"];
        //     $patrullajeEvidencia->Imagen1 = $jsonPatrullajeEvidencia[0]["Evidencia"];
        //     $patrullajeEvidencia->Captura = $jsonPatrullajeEvidencia[0]["Captura"];
        //     $patrullajeEvidencia->save();
        // } else {
        //     $patrullajeEvidencia = PruebaPatrullajeEvidencia::create([
        //         'Folio' => $jsonPatrullajeEvidencia[0]["Folio"],
        //         'Imagen1' => $jsonPatrullajeEvidencia[0]["Evidencia"],
        //         'Captura' => $jsonPatrullajeEvidencia[0]["Captura"],
        //     ]);
        // }


        $data = array(
            'response' => true,
           // 'folio' => $request->folio,
            'evidencia' => $request->patrullajeEvidencia
        
        );
        return response()->json($data);
    }
}
