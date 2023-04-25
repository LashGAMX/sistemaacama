<?php

namespace App\Imports\Clientes;

use App\Models\Cliente;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class IntermediarioImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        for ($i=1; $i < sizeof($rows) -1 ; $i++) { 
            $id = DB::table('clientes')->insertGetId([
                'Nombres' => $rows[$i][0], 
                'RFC' => $rows[$i][1],
                'Id_user_c' => Auth::user()->id,
                'Id_user_m' => Auth::user()->id,
            ]);
            DB::table('intermediarios')->insert([
                'Id_cliente' => $id,
                'Laboratorio' => 2,
                'Correo' => $rows[$i][5],
                'Direccion' => $rows[$i][2],
                'Tel_oficina' => $rows[$i][3],
                'Celular1' => $rows[$i][4],
            ]);
        }
    }
}
