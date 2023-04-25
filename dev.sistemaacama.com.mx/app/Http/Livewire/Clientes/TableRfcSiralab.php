<?php

namespace App\Http\Livewire\Clientes;

use App\Models\RfcSiralab;
use Livewire\Component;
use Livewire\WithPagination;

class TableRfcSiralab extends Component
{
    use WithPagination; 
    public $idSuc;
    public $idUser;
    public $search = '';
    protected $queryString = ['search' => ['except' => '']]; 
    public $perPage = 5;
    public $show = false;
    public $alert = false; 

    public $rfc;
    public $idRfc;
    public $status = 1;

    protected $rules = [ 
        'rfc' => 'required|max:13|min:12l',
    ];
    protected $messages = [
        'rfc.required' => 'El RFC es un dato requerido',
        // 'rfc.unique' => 'Este RFC ya se encuentra registrado',
    ];

    public function render()
    {
        // $model = AreaAnalisis::where('Area_analisis','LIKE',"%{$this->search}%")
        // ->paginate($this->perPage);
        
        $model = RfcSiralab::withTrashed()->where('Id_sucursal',$this->idSuc)->get();
        return view('livewire.clientes.table-rfc-siralab',compact('model'));
    }

    public function create()
    {
        $this->validate();
        RfcSiralab::create([
            'Id_sucursal' => $this->idSuc,
            'RFC' => $this->rfc,
        ]);
        $this->alert = true;
    }
    public function store()
    {
        $this->validate([
            'rfc' => 'required|max:13|min:12',
        ]);

        RfcSiralab::withTrashed()->find($this->idRfc)->restore();
        $model = RfcSiralab::withTrashed()->find($this->idRfc);
        $model->RFC = $this->rfc;
        $model->save();

        if($this->status != 1)
        {
            RfcSiralab::find($this->idRfc)->delete();
        }
        $this->alert = true;
    }
    public function setData($id,$rfc)
    {
        $this->alert = false;
        $this->resetValidation();
        $this->idRfc = $id;
        $this->rfc = $rfc;
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
        $this->idRfc = '';
        $this->rfc = '';
        $this->status = 1;
    }
    public function resetAlert()
    {
        $this->alert = false;
    }
}
