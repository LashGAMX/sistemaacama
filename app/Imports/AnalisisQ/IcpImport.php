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

            LoteDetalleIcp::create([
                'Id_lote' => 69,
                'Cps' => $row[36],
                'Resultado' => $row[32],
                'Fecha' => $row[11],
            ]); 

        }
    }
}
