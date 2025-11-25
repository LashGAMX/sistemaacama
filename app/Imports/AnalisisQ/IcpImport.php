<?php

namespace App\Imports\AnalisisQ;

use App\Models\CodigoParametros;
use App\Models\LoteDetalleIcp;
use App\Models\Parametro;
use App\Models\TempIcp;
use App\Models\Unidad;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IcpImport implements ToCollection
{
    public function collection(Collection $rows) 
    {
                set_time_limit(0);
        ini_set('memory_limit', '4000M');


        $id = TempIcp::orderBy('Id','DESC')->first();
        $model = DB::table('lote_detalle_icp')->where('Id_lote',$id->Temp)->delete();
        foreach ($rows as $row) {

            $elementoTemp = explode(' ', $row[24]);
            // $idParametro = $this->getIdElemento($elementoTemp[0]);
            $codTemp =  DB::table('viewcodigorecepcion')->where('Parametro','LIKE','%('.$elementoTemp[0].')%')->where('Codigo',$row[9])->get();
            if ($codTemp->count()) {
                LoteDetalleIcp::create([
                    'Id_lote' => $id->Temp,
                    'Id_codigo' => $codTemp[0]->Codigo,     
                    'Id_parametro' => $codTemp[0]->Id_parametro,
                    'Parametro' => $elementoTemp[0],
                    'Id_control' => 1,
                    'Cps' => $row[39],
                    'Dilucion' => $row[35],
                    'Resultado' => $row[37], // AL
                    'Fecha' => $row[14],
                    'Analizo' => Auth::user()->id,
                ]);
                $codigo = CodigoParametros::find($codTemp[0]->Id_codigo);
                $codigo->Asignado = 1;


                
                $codigo->save();
            }else{
                LoteDetalleIcp::create([
                    'Id_lote' => $id->Temp,
                    'Id_codigo' => $row[9],
                    'Parametro' => $elementoTemp[0],
                    'Cps' => $row[39],
                    'Dilucion' => $row[35],
                    'Resultado' => $row[37],
                    'Fecha' => $row[14],
                    'Analizo' => Auth::user()->id,
                ]);
            }
        }
    }
        public function chunkSize(): int
    {
        return 500; // Ajusta según el rendimiento del servidor
    }

    // public function collection(Collection $rows) 
    // {
    //     $id = TempIcp::orderBy('Id','DESC')->first();
    //     $aux = 0;
    //     $aux2 = 1;
    //     foreach ($rows as $row) {
    //         if ($row[$aux] != '') {
    //             break;
    //         }
    //         $aux++;
    //     }
    //     foreach ($rows as $row) {
    //         $elementoTemp = explode(' ', $row[$aux + 17]);
    //         $codTemp =  DB::table('ViewCodigoParametro')->where('Parametro','LIKE','%('.$elementoTemp[0].')%')->where('Codigo',$row[$aux + 2])->get();
    //         if ($codTemp->count()) {
    //             LoteDetalleIcp::create([
    //                 'Id_lote' => $id->Temp,
    //                 'Id_codigo' => $codTemp[0]->Codigo,     
    //                 'Id_parametro' => $codTemp[0]->Id_parametro,
    //                 'Parametro' => $elementoTemp[0],
    //                 'Id_control' => 1,
    //                 'Cps' => $row[$aux + 34],
    //                 'Dilucion' => $row[$aux + 30],
    //                 'Resultado' => $row[$aux + 30], // AL
    //                 'Fecha' => $row[$aux + 11],
    //                 'Analizo' => Auth::user()->id,
    //             ]);
    //             $codigo = CodigoParametros::find($codTemp[0]->Id_codigo);
    //             $codigo->Asignado = 1;
    //             $codigo->save();
    //         }else{
    //             LoteDetalleIcp::create([
    //                 'Id_lote' => $id->Temp,
    //                 'Id_codigo' => $row[$aux + 2],
    //                 'Parametro' => $elementoTemp[0],
    //                 'Cps' => $row[$aux + 34],
    //                 'Dilucion' => $row[$aux + 30],
    //                 'Resultado' => $row[$aux + 30], // AL
    //                 'Fecha' => $row[$aux + 11],
    //                 'Analizo' => Auth::user()->id,
    //             ]);
    //         }
    //     }
    // }
    function getIdElemento($elemento)
    {
        switch ($elemento) {
            case 'Cu':
                # code...
                $id = 211;
                break;
            case 'Cr':
                # code...
                $id = 212;
                break;
            case 'Ni':
                # code...
                $id = 300;
                break;
            case 'Cd':
                # code...
                $id = 210;
                break;
            case 'Pb':
                # code...
                $id = 216;
                break;
            case 'Zn':
                # code...
                $id = 227;
                break;
            case 'As':
                # code...
                $id = 208;
                break;
            case 'Fe':
                break;
            default:
                # code...
                $id = "";
                break;
        }
        return $id;
    }
}
