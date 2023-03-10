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
        $id = TempIcp::orderBy('Id','DESC')->first();
        foreach ($rows as $row) {

            $elementoTemp = explode(' ', $row[24]);
            // $idParametro = $this->getIdElemento($elementoTemp[0]);
            $codTemp =  DB::table('ViewCodigoParametro')->where('Parametro','LIKE','%('.$elementoTemp[0].')%')->where('Codigo',$row[9])->get();
            if ($codTemp->count()) {
                LoteDetalleIcp::create([
                    'Id_lote' => $id->Temp,
                    'Id_codigo' => $codTemp[0]->Codigo,
                    'Id_parametro' => $codTemp[0]->Id_parametro,
                    'Parametro' => $elementoTemp[0],
                    'Id_control' => 1,
                    'Cps' => $row[39],
                    'Resultado' => $row[37],
                    'Fecha' => $row[14],
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
                    'Resultado' => $row[37],
                    'Fecha' => $row[14],
                ]);
            }
        }
    }
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
                $id = 206;
                break;
            case 'As':
                # code...
                $id = 208;
                break;
            default:
                # code...
                $id = "";
                break;
        }
        return $id;
    }
}
