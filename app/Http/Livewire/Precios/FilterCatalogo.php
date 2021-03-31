<?php

namespace App\Http\Livewire\Precios;

use App\Models\Sucursal;
use Livewire\Component;

class FilterCatalogo extends Component
{ 
    public $idSucursal;
    public $sucursal;
    public function render()
    {
        $model = Sucursal::all();
        return view('livewire.precios.filter-catalogo',compact('model'));
    }
    public function show()
    {
        return redirect()->to('admin/precios/catalogo/'.$this->idSucursal);
    }
}
