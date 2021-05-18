<?php

namespace App\Http\Livewire\Clientes;

use App\Models\DireccionReporte;
use Livewire\Component;
use Livewire\WithPagination;

class TableDireccionReporte extends Component
{

    use WithPagination; 
    public $idSuc;
    public $idUser;
    public $search = '';
    protected $queryString = ['search' => ['except' => '']]; 
    public $perPage = 30;
    public $show = false;
    public $alert = false;

    public $dir;
    public $idDir;
    public $status = 1;

    protected $rules = [ 
        'dir' => 'required',
    ];
    protected $messages = [
        'dir.required' => 'La direccion es un dato requerido',
    ];

    public function render()
    {
        $model = DireccionReporte::withTrashed()->where('Id_sucursal',$this->idSuc)
        ->where('Direccion','LIKE',"%{$this->search}%")
        ->get();
        return view('livewire.clientes.table-direccion-reporte',compact('model'));
    }

    public function create()
    {
        $this->validate();
        DireccionReporte::create([
            'Direccion' => $this->dir,
            'Id_sucursal' => $this->idSuc,
        ]);
        $this->alert = true;
    }
    public function store()
    {
        $this->validate();
        if($this->status != 1)
        {
            DireccionReporte::withTrashed()->find($this->idDir)->restore();
            $model = DireccionReporte::withTrashed()->find($this->idDir);
            $model->Direccion = $this->dir;
            $model->save();
            DireccionReporte::find($this->idDir)->delete();
        }else{
            DireccionReporte::withTrashed()->find($this->idDir)->restore();
            $model = DireccionReporte::withTrashed()->find($this->idDir);
            $model->Direccion = $this->dir;
            $model->save();
        }

        $this->alert = true;
    }
    public function setData($id,$dir)
    {
        $this->alert = false;
        $this->resetValidation();
        $this->idDir = $id;
        $this->dir = $dir;
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
    public function clean()
    {
        $this->idDir = '';
        $this->dir = '';
    }

    public function resetAlert()
    {
        $this->alert = false;
    }
}
