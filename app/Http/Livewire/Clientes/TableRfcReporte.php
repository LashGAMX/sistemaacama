<?php

namespace App\Http\Livewire\Clientes;

use App\Models\RfcSucursal;
use Livewire\Component;
use Livewire\WithPagination;

class TableRfcReporte extends Component
{
    use WithPagination; 
    public $idSuc;
    public $idUser;
    public $search = '';
    protected $queryString = ['search' => ['except' => '']]; 
    public $perPage = 10;
    public $show = false;
    public $alert = false;

    public $rfc;
    public $idRfc;
    public $status = 1;
 
    protected $rules = [ 
        'rfc' => 'required|max:13|min:12|unique:rfc_sucursal',
    ];
    protected $messages = [
        'rfc.required' => 'El RFC es un dato requerido',
    ]; 

    public function render()
    {
        $model = RfcSucursal::withTrashed()
        ->where('Id_sucursal',$this->idSuc)
        ->where('RFC','LIKE',"%{$this->search}%")
        ->paginate($this->perPage);
        return view('livewire.clientes.table-rfc-reporte',compact('model'));
    }

    public function create()
    {
        $this->validate();
        RfcSucursal::create([
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

        if($this->status != 1)
        {
            RfcSucursal::withTrashed()->find($this->idRfc)->restore();
            $model = RfcSucursal::withTrashed()->find($this->idRfc);
            $model->RFC = $this->rfc;
            $model->save();
            RfcSucursal::find($this->idRfc)->delete();
        }
        else
        {
            RfcSucursal::withTrashed()->find($this->idRfc)->restore();
            $model = RfcSucursal::withTrashed()->find($this->idRfc);
            $model->RFC = $this->rfc;
            $model->save();
        }

        $this->alert = true;
    }
    public function setData($id,$rfc,$status)
    {
        $this->alert = false;
        $this->resetValidation();
        $this->idRfc = $id;
        $this->rfc = $rfc;
        if($status != null)
        {
            $this->status = 0;
        }else{
            $this->status = 1;
        }
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
