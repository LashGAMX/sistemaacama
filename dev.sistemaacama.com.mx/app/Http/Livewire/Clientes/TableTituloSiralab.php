<?php

namespace App\Http\Livewire\Clientes;

use App\Models\TituloConsecionSir;
use Livewire\Component;
use Livewire\WithPagination;

class TableTituloSiralab extends Component
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
        $model = TituloConsecionSir::withTrashed()->where('Id_sucursal',$this->idSuc)->get();
            
        return view('livewire.clientes.table-titulo-siralab',compact('model'));
    }

    public function create()
    {
        $this->validate();
        TituloConsecionSir::create([
            'Titulo' => $this->titulo,
            'Id_sucursal' => $this->idSuc,
        ]);
        $this->alert = true;
    }
    public function store()
    {
        $this->validate();
        TituloConsecionSir::withTrashed()->find($this->idTitulo)->restore();
        $model = TituloConsecionSir::withTrashed()->find($this->idTitulo);
        $model->Titulo = $this->titulo;
        $model->save();
        if($this->status != 1)
        {
            TituloConsecionSir::find($this->idTitulo)->delete();
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
