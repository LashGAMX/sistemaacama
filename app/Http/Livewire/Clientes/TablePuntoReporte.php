<?php

namespace App\Http\Livewire\Clientes;

use App\Models\PuntoMuestreoGen;
use Livewire\Component;
use Livewire\WithPagination;

class TablePuntoReporte extends Component
{
    use WithPagination; 
    public $idSuc;
    public $idUser;
    public $search = '';
    protected $queryString = ['search' => ['except' => '']]; 
    public $perPage = 5;
    public $show = false;
    public $alert = false;

    public $punto;
    public $idPunto;

    protected $rules = [ 
        'punto' => 'required',
    ];
    protected $messages = [
        'punto.required' => 'El punto es un dato requerido',
    ];

    public function render()
    {
        $model = PuntoMuestreoGen::where('Id_sucursal',$this->idSuc)->get();
        return view('livewire.clientes.table-punto-reporte',compact('model'));
    }

    public function create()
    {
        $this->validate();
        PuntoMuestreoGen::create([
            'Punto_muestreo' => $this->punto,
            'Id_sucursal' => $this->idSuc,
        ]);
        $this->alert = true;
    }
    public function store()
    {
        $this->validate();
        $model = PuntoMuestreoGen::find($this->idPunto);
        $model->Punto_muestreo = $this->punto;
        $model->save();
        $this->alert = true;
    }
    public function setData($id,$punto)
    {
        $this->resetValidation();
        $this->idPunto = $id;
        $this->punto = $punto;
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
    public function clean()
    {
        $this->idPunto = '';
        $this->punto = ''; 
    }
    public function resetAlert()
    {
        $this->alert = false;
    }
}
