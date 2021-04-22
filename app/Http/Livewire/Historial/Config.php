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
    Public $sucursal;

    public function render()
    {
        $sucursal = $this->sucursal;
        return view('livewire.historial.config',compact('sucursal'));
    } 

    public function config()
    {
        $this->sw = 1;
        $this->sucursal = HistorialSucursal::all();
        
        
        
    }
    public function clientes()
    {
        $this->sw = 2;

    }
}
