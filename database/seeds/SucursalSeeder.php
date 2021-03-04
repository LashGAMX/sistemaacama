<?php

namespace Database\Seeders;

use App\Models\Sucursal;
use Illuminate\Database\Seeder;

class SucursalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sucursal = new Sucursal();
        $sucursal->Sucursal = 'Matriz';
        $sucursal->Id_user_c = 1;
        $sucursal->Id_user_m = 2;
        $sucursal->save();
    }
}
