<?php

namespace App\Http\Livewire\Clientes;

use App\Models\TituloConsecion;
use Livewire\Component;
use Livewire\WithPagination;

class TableTituloReporte extends Component
{
    use WithPagination; 
    public $idSuc;
    public $idUser;
    public $search = '';
    protected $queryString = ['search' => ['except' => '']]; 
    public $perPage = 30;
    public $show = false;
    public $alert = false;

    public $titulo;
    public $idTitulo;
    public $status = 1;

    protected $rules = [ 
        'titulo' => 'required',
    ];
    protected $messages = [
        'titulo.required' => 'El titulo es un dato requerido',
    ];

    public function render()
    {
        $model = TituloConsecion::withTrashed()->where('Id_sucursal',$this->idSuc)->get();
            
        return view('livewire.clientes.table-titulo-reporte',compact('model'));
    }

    public function create()
    {
        $this->validate();
        TituloConsecion::create([
            'Titulo' => $this->titulo,
            'Id_sucursal' => $this->idSuc,
        ]);

        $this->alert = true;
    }
    public function store()
    {
        $this->validate();
        if($this->status != 1)
        {
            TituloConsecion::withTrashed()->find($this->idTitulo)->restore();
            $model = TituloConsecion::withTrashed()->find($this->idTitulo);
            $model->Titulo = $this->titulo;
            $model->save();
            TituloConsecion::find($this->idTitulo)->delete();
        }else{
            TituloConsecion::withTrashed()->find($this->idTitulo)->restore();
            $model = TituloConsecion::withTrashed()->find($this->idTitulo);
            $model->Titulo = $this->titulo;
            $model->save();
        }
        $this->alert = true;
    }
    public function setData($id,$titulo)
    {
        $this->alert = false;
        $this->resetValidation();
        $this->idTitulo = $id;
        $this->titulo = $titulo;
    }
    
    public function setBtn()
    {
        $this->clean();
        $this->alert = false;
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
        $this->idTitulo = '';
        $this->titulo = '';
    }
    public function resetAlert()
    {
        $this->alert = false;
    }
}
 