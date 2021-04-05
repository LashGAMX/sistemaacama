<?php

namespace App\Http\Livewire\Precios;

use App\Models\DetalleIntermediario;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Intermediario extends Component 
{
     //
     use WithPagination;
    
     public $search = '';
     protected $queryString = ['search' => ['except' => '']];
     public $sw = false;
     public $perPage = 30;
     public $alert = false;
     public $error = false;

    public $idDetalle;
    public $intermediario;
    public $nivel;
    public $status = 1;

    protected $rules = [
        'intermediario' => 'required',
        'nivel' => 'required',
    ];

    public function render()
    {
        $intermediarios = DB::table('ViewIntermediarios')->where('deleted_at',NULL)->get();
        $niveles = DB::table('nivel_clientes')->get();
        $model = DB::table('ViewDetalleInter')->get();
        return view('livewire.precios.intermediario',compact('model','intermediarios','niveles'));
    }
    public function create()
    {
        $this->validate();
        $cliente = DetalleIntermediario::where('Id_intermediario',$this->intermediario)->get();
        if($cliente->count())
        {
            $this->error = true;   
        }else{
            $model = DetalleIntermediario::create([
                'Id_intermediario' => $this->intermediario,
                'Id_nivel' => $this->nivel,
            ]);
            if ($this->status != 1) {
                DetalleIntermediario::find($model->Id_detalle)->delete();
            }
            $this->error = false;
        }
        $this->alert = true;
    }
    public function store()
    {
        DetalleIntermediario::withTrashed()->find($this->idDetalle)->restore();
        $model = DetalleIntermediario::find($this->idDetalle);
        $model->Id_nivel = $this->nivel;
        $model->save();

        if ($this->status != 1) {
            DetalleIntermediario::find($this->idDetalle)->delete();
        }
        $this->error = false;
        $this->alert = true;
    }
    public function setData($idDetalle,$intermediario,$nivel,$status)
    {
        $this->sw = true;
        $this->error = false;
        $this->resetValidation();
        $this->idDetalle = $idDetalle;
        $this->intermediario = $intermediario;
        $this->nivel = $nivel;
        $this->status = $status;
        if($status != null)
        {
            $this->status = 0;
        }else{
            $this->status = 1;
        }
        $this->resetAlert();
    }
    public function showDetils($id)
    {
        return redirect()->to('/admin/precios/intermediario/details/'.$id);
    }
    public function btnCreate()
    {
        $this->sw = false;
        $this->clean();
        $this->resetValidation();
        $this->resetAlert();
        $this->alert = false;
        $this->error = false;
    }
    public function clean()
    {
        $this->status = 1;
        $this->intermediario = '';
        $this->idDetalle = '';
        $this->idInter = '';
    }
    public function resetAlert()
    {
        $this->alert = false;
    }
}
