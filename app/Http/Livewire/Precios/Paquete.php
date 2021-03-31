<?php

namespace App\Http\Livewire\Precios;

use App\Models\PrecioPaquete;
use App\Models\SubNorma;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Paquete extends Component
{
    use WithPagination;

    public $search = '';
    protected $queryString = ['search' => ['except' => '']];
    public $sw = false;
    public $perPage = 30;
    public $alert = false;
    public $error = false;

    public $status;
    public $paquete;
    public $precio;
    public $idPaquete;

    protected $rules = [
        'paquete' => 'required',
        'precio' => 'required',
    ];
    public function render() 
    {
        $paquetes = SubNorma::all();
        $model = DB::table('ViewPrecioPaq')
        ->orWhere('Clave','LIKE',"%{$this->search}%")
        ->orWhere('Precio','LIKE',"%{$this->search}%")
        ->get();
        return view('livewire.precios.paquete',compact('model','paquetes'));
    }
    public function create()
    {
        $this->validate();
        $paquetes = PrecioPaquete::where('Id_paquete',$this->paquete)->get();
        if($paquetes->count())
        { 
            $this->error = true;   
        }else{
            $model = PrecioPaquete::create([
                'Id_paquete' => $this->paquete,
                'Precio' => $this->precio,
            ]);
            if ($this->status != 1) {
                PrecioPaquete::find($model->Id_precio)->delete();
            }
            $this->error = false;
        }
        $this->alert = true; 
    } 
    public function store()
    {
        PrecioPaquete::withTrashed()->find($this->idPaquete)->restore();
        $model = PrecioPaquete::find($this->idPaquete);
        $model->Precio = $this->precio;
        $model->save();

        if ($this->status != 1) {
            PrecioPaquete::find($this->idPaquete)->delete();
        }
        $this->error = false;
        $this->alert = true;
    }
    public function setData($idPaquete,$paquete,$precio,$status)
    {
        $this->sw = true;
        $this->error = false;
        $this->resetValidation();
        
        $this->status = $status;
        $this->paquete = $paquete;
        $this->precio = $precio;
        $this->idPaquete = $idPaquete;

        if($status != null)
        {
            $this->status = 0;
        }else{
            $this->status = 1;
        }
        $this->resetAlert();
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
        $this->paquete = '';
        $this->idPaquete = '';
        $this->precio = '';
    }
    public function resetAlert()
    {
        $this->alert = false;
    }
}
