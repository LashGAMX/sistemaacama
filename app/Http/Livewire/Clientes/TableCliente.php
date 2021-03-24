<?php

namespace App\Http\Livewire\Clientes;

use App\Models\ClienteGeneral;
use App\Models\Clientes;
use App\Models\Intermediario;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class TableCliente extends Component
{
    use WithPagination; 
    public $idUser;
    public $search = '';
    protected $queryString = ['search' => ['except' => '']]; 
    public $perPage = 50;
    public $sw = false;
    public $alert = false;

    public $idCliente;
    public $cliente;
    public $rfc;
    public $idInter = 0;
    public $inter;
    public $status = 1;

    protected $rules = [ 
        'cliente' => 'required',
        'rfc' => 'required|max:13|min:12|unique:clientes',
        'inter' => 'required',
    ];
    protected $messages = [
        'cliente.required' => 'El nombre es un dato requerido',
        'rfc.required' => 'El RFC es un dato requerido',
        'rfc.min' => 'El RFC Tiene que ser entre 12 y 13 caracteres',
        'rfc.max' => 'El RFC Tiene que ser entre 12 y 13 caracteres',
        'rfc.unique' => 'El rfc ya se encuentra registrado',
    ];

    public function render()
    {
        // $model = MetodoPrueba::where('Metodo_prueba','LIKE',"%{$this->search}%")
        // ->orWhere('Clave_metodo','LIKE',"%{$this->search}%")
        // ->paginate($this->perPage);
        $intermediario = DB::table('ViewIntermediarios')->get();
        $model = DB::table('ViewGenerales')
        ->where('Empresa','LIKE',"%{$this->search}%")
        ->orWhere('RFC','LIKE',"%{$this->search}%")
        ->orderBy('Id_cliente','desc') 
        ->get();

        return view('livewire.clientes.table-cliente',compact('model','intermediario'));
    }

    public function create()
    {
        $this->validate();

        $model = Clientes::create([
            'Nombres' => $this->cliente,
            'RFC' => $this->rfc,
            'Id_tipo_liente' => 2,
        ]);
        ClienteGeneral::create([
            'Id_cliente' => $model->Id_cliente,
            'Empresa' => $this->cliente,
            'Id_intermediario' => $this->inter
        ]);
        if($this->status != 1)
        {
            Clientes::find($model->Id_cliente)->delete();
        }
        $this->alert = true;
    }
    public function store()
    {
        $this->validate([
            'cliente' => 'required',
            'rfc' => 'required|max:13|min:12',
        ]);
        if($this->status != 1) 
        {
            Clientes::withTrashed()->where('Id_cliente',$this->idCliente)->restore();
                $model = Clientes::find($this->idCliente);
                $model->Nombres = $this->cliente;
                $model->RFC = $this->rfc;
                $model->save();
            Clientes::find($this->idCliente)->delete();
        }else{
            Clientes::withTrashed()->where('Id_cliente',$this->idCliente)->restore();
            $model = Clientes::find($this->idCliente);
                $model->Nombres = $this->cliente;
                $model->RFC = $this->rfc;
                $model->save();
        }
        DB::table('clientes_general')
        ->where('Id_cliente',$this->idCliente)
        ->update([
            'Empresa' => $this->cliente,
            'Id_intermediario' => $this->idInter,
        ]);
        $this->alert = true;
    }
    public function setData($id,$cliente,$rfc,$idInter,$status)
    {
        $this->sw = true;
        $this->alert = false;
        $this->resetValidation();
        $this->idCliente = $id;
        $this->cliente = $cliente;
        $this->rfc = $rfc;
        $this->idInter = $idInter;
        if($status != null)
        {
            $this->status = 0;
        }else{
            $this->status = 1;
        }
    }
    
    public function btnCreate()
    {
        $this->alert = false;
        if($this->sw == true)
        {
            $this->clean();
        }
    }
    public function detalle($id)
    {
        return redirect()->to('admin/clientes/cliente_detalle/'.$id);
    }
    public function resetAlert()
    {
        $this->alert = false;
    }
    public function clean()
    {
        $this->resetValidation();
        $this->idCliente = '';
        $this->cliente = '';
        $this->rfc = '';
        $this->idInter = '';
        $this->inter = '';
        $this->status = '';
        $this->sw = false;
    }
}
