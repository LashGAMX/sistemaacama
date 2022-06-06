<?php

namespace App\Http\Livewire\AnalisisQ;

use App\Models\Norma;
use Livewire\Component;

class ListaNorma extends Component
{
    public $idNorma;
    public $norma;

    public function render() 
    {
        $norma = $this->idNorma;
        $idNorma = $this->idNorma;
        $model = Norma::all();
        return view('livewire.analisis-q.lista-norma',compact('model','norma','idNorma')); 
    }
    public function show()
    {
        return redirect()->to('admin/analisisQ/limites/'.$this->idNorma);
    }
}
