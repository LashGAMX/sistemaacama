<?php

namespace App\Imports\AnalisisQ;

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
        var_dump($rows)
    }
}
