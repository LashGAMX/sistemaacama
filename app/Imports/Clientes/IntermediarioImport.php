<?php

namespace App\Imports\Clientes;

use App\Models\Cliente;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class IntermediarioImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        $cont = 0;
        foreach ($rows as $row) 
        {
            echo 'Us:'.$cont.':'.$row[0];
            echo 'PA:'.$cont.':'.$row[1];
            echo 'PA:'.$cont.':'.$row[2]; 
            echo '<br>';
        }
    }
}
