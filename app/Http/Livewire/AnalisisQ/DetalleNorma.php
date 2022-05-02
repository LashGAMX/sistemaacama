<?php

namespace App\Http\Livewire\AnalisisQ;

use App\Models\SubNorma;
use Livewire\Component;

class DetalleNorma extends Component
{
    public $search = '';
    protected $queryString = ['search' => ['except' => '']]; 
    public $stdNorma;
    public $name;
    public $idNorma;

    public $idSub;
    public $sub;
    public $clave;
    public $status;

    public $sw;
    public $alert = false;

    protected $rules = [
        'sub' => 'required',
        'clave' => 'required',
    ];

    protected $messages = [
        'sub.required' => 'El nombre es un dato requerido',
        'clave.required' => 'La clave es un dato requerido',
    ];

    public function render()
    {
        $model = SubNorma::withTrashed()->where('Id_norma',$this->idNorma)->orderBy('Id_subnorma','ASC')->get();
        return view('livewire.analisis-q.detalle-norma',compact('model'));
    }
    public function create()
    {
        $this->validate();
        $model = SubNorma::create([
            'Id_norma' => $this->idNorma,
            'Norma' => $this->sub,
            'Clave' => $this->clave,
        ]);
        if($this->status != 1) 
        {
            SubNorma::find($model->Id_subnorma)->delete();   
        }
        $this->alert = true;
    }
    public function store()
    {
        $this->validate();
        SubNorma::withTrashed()->find($this->idSub)->restore();
        $model = SubNorma::find($this->idSub);
        $model->Norma = $this->sub;
        $model->Clave = $this->clave;
        if($this->status != 1) 
        {
            SubNorma::find($this->idSub)->delete();   
        }
        $this->alert = true;
        $model->save();
    }
    public function setData($idSub,$sub,$clave,$status)
    {
        $this->sw = true;
        $this->alert = false;
        $this->resetValidation();
        $this->idSub = $idSub;
        $this->sub = $sub;
        $this->clave = $clave;
        if($status != null)
        { 
            $this->status = 0;
        }else{
            $this->status = 1;
        }
    }
    public function showDetils($idSub)
    {
        return redirect()->to('admin/analisisQ/detalle_normas/'.$this->idNorma.'/'.$idSub);
    }
    public function btnCreate()
    {
        $this->clean();
        $this->alert = false;
        $this->resetValidation();
        $this->status = 1;
        if($this->sw != false)
        {
            $this->sw = true;
        }
    }
    public function setName()
    {
        if($this->stdNorma == 1)
        {
            $this->sub = $this->name;
        }else{
            $this->sub = '';
        }
    }
    public function clean()
    {
        $this->idSub = '';
        $this->sub = '';
        $this->clave = '';
        $this->status = '';
    }
}
