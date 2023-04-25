<?php

namespace App\Http\Livewire\Historial;

use App\Models\HistorialSucursal;
use App\Models\Sucursal;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Config extends Component
{
    public $sw = 0;
    public $subModulo;
    public $categoria;
    public $search = '';

    public $idHist;
    public $idSuc;
    public $sucursal;
    public $unidad;
    // public $res;
    //public $model;

    public function render()
    {   
        return view('livewire.historial.config');
    } 

    public function config()
    {
        $this->sw = 1;
       

    }

    public function getConfig()
    {
    
    }
    public function clientes()
    {
        $this->sw = 2;

    }
}
