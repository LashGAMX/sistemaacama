<?php

namespace App\Http\Livewire\Isaac;

use Composer\DependencyResolver\Request;
use Livewire\Component;

class Isaac extends Component
{
    public $array = array();
    public $formula = "";
    public $cont = 0;
    public $string;

    public function render()
    {
        return view('livewire.isaac.isaac');
    }
     public function agregar()
     {
        $this->array[$this->cont] = $this->formula;
        $this->cont++;
        $this->formula = "";
        $this->string  = implode(" ",$this->array);
     }

     public function terminar(Request $request)
     {
         $request->formula;
         
 
     }
}
