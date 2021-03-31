<?php

namespace App\Http\Livewire\Precios;

use Livewire\Component;

class PaqueteIntermediario extends Component
{
    public $search = '';
    protected $queryString = ['search' => ['except' => '']];
    public $sw = false;
    public $perPage = 30;
    public $alert = false;
    public $error = false;
    public $idCliente;
    public $idLab;
    public $descNivel; 
    public $calcular;
    
    public function render()  
    {
        return view('livewire.precios.paquete-intermediario');
    }
}
