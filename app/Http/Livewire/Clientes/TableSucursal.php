<?php

namespace App\Http\Livewire\Clientes;

use Livewire\Component;
use Livewire\WithPagination;

class TableSucursal extends Component
{

    use WithPagination;
    public $idUser;
    public $search = '';
    protected $queryString = ['search' => ['except' => '']]; 
    public $perPage = 5;
    public $show = false;


    protected $rules = [ 
        'area' => 'required',
    ];
    protected $messages = [
        'area.required' => 'El nombre es un dato requerido',
    ];

    public function render()
    {
        // $model = AreaAnalisis::where('Area_analisis','LIKE',"%{$this->search}%")
        // ->paginate($this->perPage);
        return view('livewire.clientes.table-sucursal');
    }

    public function create()
    {
        $this->validate();
   
        
    }
    public function store()
    {
        $this->validate();

    }
    public function setData($id,$area)
    {
        $this->resetValidation();
        $this->idArea = $id;
        $this->area = $area;
    }
    
    public function setBtn()
    {
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
}
