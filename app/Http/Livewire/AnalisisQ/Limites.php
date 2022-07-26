<?php

namespace App\Http\Livewire\AnalisisQ;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Limites extends Component
{
    public $idNorma;
    public $search = '';
    protected $queryString = ['search' => ['except' => '']];
    public function render()
    {
        $model = DB::table('ViewNormaParametro')
        ->where('Id_norma',$this->idNorma)
        ->where('Parametro','LIKE',"%{$this->search}%")
        ->get();
        return view('livewire.analisis-q.limites',compact('model'));
       
    }
    public function details($idParametro)
    {
        return redirect()->to('admin/analisisQ/limites/'.$this->idNorma.'/'.$idParametro);
    }
}
