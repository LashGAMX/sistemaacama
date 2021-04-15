<?php

namespace App\Http\Livewire\Pruebas;

use Livewire\Component;

class Prueba extends Component
{
    public $name = 'luis';
    public $count = 0;

    protected $rules = [
        'name' => 'required',
    ];

    protected $messages  = [
        'name.required' => 'Este dato es obligatorio 2'
    ];
    
    public function render()
    {
        return view('livewire.pruebas.prueba');
    }
    public function create()
    {
        $this->validate();
    }
    public function increment()
    {
        $this->count++;
    }
}
 