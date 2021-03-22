<?php

namespace App\Http\Livewire\AnalisisQ;

use App\Models\Norma;
use Livewire\Component;

class ListaNorma extends Component
{
    public $idNorma = 0;
    public $norma;

    public function render() 
    {
        $this->norma = $this->idNorma;   
        $model = Norma::all();
        return view('livewire.analisis-q.lista-norma',compact('model'));
    }
    public function show()
    {
        return redirect()->to('admin/analisisQ/limites/'.$this->norma);
    }
}
