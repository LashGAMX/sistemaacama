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
    public $perPage = 5;
    public $show = false;
    public $rfc;
    public $idRfc;

    protected $rules = [ 
        'rfc' => 'required|max:13|min:12|unique:rfc_sucursal',
    ];
    protected $messages = [
        'rfc.required' => 'El RFC es un dato requerido',
        'rfc.unique' => 'Este RFC ya se encuentra registrado',
    ];

    public function render()
    {
        // $model = AreaAnalisis::where('Area_analisis','LIKE',"%{$this->search}%")
        // ->paginate($this->perPage);
        $model = RfcSucursal::where('Id_sucursal',$this->idSuc)->get();
        return view('livewire.clientes.table-rfc-reporte',compact('model'));
    }

    public function create()
    {
        $this->validate();
        RfcSucursal::create([
            'Id_sucursal' => $this->idSuc,
            'RFC' => $this->rfc,
        ]);
        
    }
    public function store()
    {
        $this->validate([
            'rfc' => 'required|max:13|min:12',
        ]);
        $model = RfcSucursal::find($this->idRfc);
        $model->RFC = $this->rfc;
        $model->save();

    }
    public function setData($id,$rfc)
    {
        $this->resetValidation();
        $this->idRfc = $id;
        $this->rfc = $rfc;
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
    public function clean()
    {
        $this->idRfc = '';
        $this->rfc = '';
    }
}
