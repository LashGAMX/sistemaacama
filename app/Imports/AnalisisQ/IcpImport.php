<?php

namespace App\Imports\AnalisisQ;

use App\Models\LoteDetalleIcp;
use App\Models\Parametro;
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

        foreach ($rows as $row) {

            $folioTemp = explode('MA', $row[6]);
            $elementoTemp = explode(' ', $row[21]);
            $idParametro = $this->getIdElemento($elementoTemp[0]);
        

            LoteDetalleIcp::create([
                'Id_lote' => 275,
                'Id_codigo' => $folioTemp[0],
                'Id_parametro' => $idParametro,
                'Cps' => $row[36],
                'Resultado' => $row[32],
                'Fecha' => $row[11],
            ]);
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
