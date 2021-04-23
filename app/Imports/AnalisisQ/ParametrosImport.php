<?php

namespace App\Imports\AnalisisQ;

use App\Models\Parametro;
use App\Models\Unidad;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ParametrosImport implements ToCollection 
{
    public function collection(Collection $rows)
    {
        // var_dump($rows);
        $normas = DB::table('normas')->get();
        $unidades = DB::table('unidades')->get();
        
        $norma = 0;
        $unidad = 0;

        foreach ($rows as $row) {

            foreach ($normas as $item) {
                if ($item->Clave_norma == $row[4]) {
                    $norma = $item->Id_norma;
                }
            }
            foreach($unidades as $item)
            {
                if($item->Unidad == $row[2])
                {
                    $unidad = $item->Id_unidad;
                }
            }

            DB::table('parametros')->insert([
                'Id_laboratorio' =>2,
                'Id_tipo_formula' => 1,
                'Id_rama' => 1,
                'Parametro' => $row[1],
                'Id_unidad' => $unidad,
                'Id_metodo' => 1,
                'Id_norma' => $norma,
                'Limite' => $row[5],
                'Id_procedimiento' => 1,
                'Id_matriz' => 1,
                'Id_simbologia' => 1,
                'Id_user_c' => Auth::user()->id,
                'Id_user_m' => Auth::user()->id,
            ]);

        }
    }
}
