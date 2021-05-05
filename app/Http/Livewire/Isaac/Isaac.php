<?php

namespace App\Http\Livewire\Isaac;

use Livewire\Component;

class Isaac extends Component
{

    public $formula;

    public function render()
    {
        return view('livewire.isaac.isaac');
    }

    public function agregar()
    {
        echo $this->formula;
    }
}
