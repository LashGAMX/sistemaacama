<?php

namespace App\Http\Livewire\Precios;

use App\Models\PrecioCatalogo;
use App\Models\PrecioIntermedio;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CatalogoIntermediario extends Component
{
    public $search = '';
    protected $queryString = ['search' => ['except' => '']];
    public $sw = false;
    public $perPage = 30;
    public $alert = false;
    public $error = false;
    public $idCliente;
    public $idLab;
    public $descNivel; 
    public $calcular;


    public $parametro;
    public $desc;
    public $idCatalogo;
    public $precio;
    public $status = 1;

    protected $rules = [
        'desc' => 'required',
    ];

    public function render()
    {
        
            $this->calcularDes();
        
        $parametros = DB::table('ViewPrecioCat')->where('Id_laboratorio',$this->idLab)->get();
        $model = DB::table('ViewPrecioCatInter')->where('Id_intermediario',$this->idCliente)
        ->get();
        return view('livewire.precios.catalogo-intermediario',compact('model','parametros'));
    }
    public function calcularDes()
    {
        if($this->parametro != 0)
        {
            $param = DB::table('ViewPrecioCat')->where('Id_parametro',$this->parametro)->first();
            $this->precio = $param->Precio;
            $desc = ($param->Precio * $this->desc) / 100;
            $this->calcular = $this->precio - $desc;
        }
    }
    public function create() 
    {
        $this->validate();
        $param = PrecioIntermedio::where('Tipo_precio',1)->where('Id_catalogo',$this->parametro)->get();
        if($param->count())
        {
            $this->error = true;   
        }else{
            $model = PrecioIntermedio::create([
                'Id_intermediario' => $this->idCliente,
                'Tipo_precio' => 1,
                'Id_catalogo' => $this->parametro,
                'Precio' => $this->calcular,
                'Original' => $this->precio,
                'Descuento' => $this->desc,
            ]);
            if ($this->status != 1) {
                PrecioIntermedio::find($model->Id_precio)->delete();
            }
            $this->error = false;
        }
        $this->alert = true;
        
    }
    public function store()
    {
        PrecioIntermedio::withTrashed()->find($this->idCatalogo)->restore();
        $model = PrecioIntermedio::find($this->idCatalogo);
        $model->Precio = $this->calcular;
        $model->Original = $this->precio;
        $model->Descuento = $this->desc;
        $model->save();

        if ($this->status != 1) {
            PrecioIntermedio::find($this->idCatalogo)->delete();
        }
        $this->error = false;
        $this->alert = true;
    }
    public function setData($idCatalogo,$parametro,$desc,$precio,$status)
    {
    
        $this->sw = true;
        $this->error = false;
        $this->calcular = '';
        $this->resetValidation();
        $this->parametro =$parametro;
        $this->desc =$desc;
        $this->idCatalogo =$idCatalogo;
        $this->precio =$precio;
        $this->status = $status;
        if($status != null) 
        {
            $this->status = 0;
        }else{
            $this->status = 1;
        }
        $this->resetAlert();
    }
    public function setCal()
    {
        
    }
    public function btnCreate()
    {
        $this->sw = false;
        $this->clean();
        $this->resetValidation();
        $this->resetAlert();
        $this->desc = $this->descNivel;
        $this->calcular = '';
        $this->alert = false;
        $this->error = false; 
    }
    public function clean()
    {
        $this->status = 1;
        $this->precio = '';
        $this->parametro = '';
        $this->idParametro = '';
        $this->desc = '';
    }
    public function resetAlert()
    {
        $this->alert = false;
    }
}
