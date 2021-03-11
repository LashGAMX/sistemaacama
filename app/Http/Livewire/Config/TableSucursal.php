<?php

namespace App\Http\Livewire\Config;

use App\Models\Sucursal;
use Livewire\Component; 

class TableSucursal extends Component
{

    public function render() 
    { 
        $sucursal = Sucursal::all();
        return view('livewire.config.table-sucursal',compact('sucursal'));
    } 
}
  