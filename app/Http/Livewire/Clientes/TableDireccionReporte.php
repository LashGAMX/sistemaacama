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
    public $perPage = 5;
    public $show = false;
    public $dir;
    public $idDir;

    protected $rules = [ 
        'dir' => 'required',
    ];
    protected $messages = [
        'dir.required' => 'La direccion es un dato requerido',
    ];

    public function render()
    {
        $model = DireccionReporte::where('Id_sucursal',$this->idSuc)->get();
            
        return view('livewire.clientes.table-direccion-reporte',compact('model'));
    }

    public function create()
    {
        $this->validate();
        DireccionReporte::create([
            'Direccion' => $this->dir,
            'Id_sucursal' => $this->idSuc,
        ]);
    }
    public function store()
    {
        $this->validate();
        $model = DireccionReporte::find($this->idDir);
        $model->Direccion = $this->dir;
        $model->save();
    }
    public function setData($id,$dir)
    {
        $this->resetValidation();
        $this->idDir = $id;
        $this->dir = $dir;
    }
    
    public function setBtn()
    {
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
}
