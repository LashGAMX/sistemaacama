<?php

namespace App\Http\Livewire\Precios;

use App\Http\Livewire\AnalisisQ\Normas;
use App\Models\Norma;
use App\Models\Sucursal;
use Livewire\Component;

class FilterCatalogo extends Component
{ 
    public $idSucursal;
    public $idNorma;
    public $sucursal;
    public function render()
    {
        $model = Sucursal::all();
        $normas = Norma::all();
        return view('livewire.precios.filter-catalogo',compact('model', 'normas'));
    }
    public function show()
    {
        return redirect()->to('admin/precios/catalogo/'.$this->idSucursal.'/'.$this->idNorma);
    }
}
