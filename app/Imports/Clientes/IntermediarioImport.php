<?php

namespace App\Imports\Clientes;

use App\Models\Cliente;
use Maatwebsite\Excel\Concerns\ToModel;

class IntermediarioImport implements ToModel 
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        var_dump($row);
    }
}
