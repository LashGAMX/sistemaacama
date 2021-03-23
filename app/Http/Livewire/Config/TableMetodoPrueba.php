<?php

namespace App\Http\Livewire\Config;

use App\Models\MetodoPrueba;
use Livewire\Component;
use Livewire\WithPagination;

class TableMetodoPrueba extends Component
{
    use WithPagination;
    public $idUser;
    public $search = '';
    protected $queryString = ['search' => ['except' => '']]; 
    public $perPage = 50;
    public $show = false;
    public $alert = false;

    public $metodo;
    public $clave;
    public $idMetodo;

    protected $rules = [ 
        'metodo' => 'required',
        'clave' => 'required',
    ];
    protected $messages = [
        'metodo.required' => 'El nombre es un dato requerido',
        'clave.required' => 'El nombre es un dato requerido',
    ];

    public function render()
    {
        $model = MetodoPrueba::where('Metodo_prueba','LIKE',"%{$this->search}%")
        ->orWhere('Clave_metodo','LIKE',"%{$this->search}%")
        ->paginate($this->perPage);
        return view('livewire.config.table-metodo-prueba',compact('model'));
    }

    public function create()
    {
        $this->validate();
        MetodoPrueba::create([
            'Metodo_prueba' => $this->metodo,
            'Clave_metodo' => $this->clave,
        ]);
        $this->alert = true;
    }
    public function store()
    {
        $this->validate();
        $model = MetodoPrueba::find($this->idMetodo);
        $model->Metodo_prueba = $this->metodo;
        $model->Clave_metodo = $this->clave;
        $model->save();
        $this->alert = true;
    }
    public function setData($id,$metodo,$clave)
    {
        $this->resetValidation();
        $this->idMetodo = $id;
        $this->metodo = $metodo;
        $this->clave = $clave;
        $this->alert = false;
    }
    
    public function setBtn()
    {
        $this->alert = false;
        $this->clean();
        if($this->show == false)
        {
            $this->resetValidation();
            $this->show = true;
        }
    }
    public function deleteBtn()
    {
        if($this->show == true)
        {
            $this->show = false;
        }
    }
    public function resetAlert()
    {
        $this->alert = false;
    }
    public function clean()
    {
        $this->idTipo = '';
        $this->tipo = '';
    }
}
