<?php

namespace App\Http\Livewire\Clientes;

use App\Models\Clientes;
use App\Models\SucursalCliente;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class TableSucursal extends Component
{

    use WithPagination;
    public $idUser;
    public $idCliente;
    public $search = '';
    protected $queryString = ['search' => ['except' => '']]; 
    public $perPage = 500;
    public $sw = 0;
    public $dup = 0;
    public $alert = false;

    public $idSuc;
    public $empresa;
    public $estado;
    public $idEstado;
    public $tipo = '';
    public $status = 1;

    protected $rules = [  
        'empresa' => 'required', 
        'estado' => 'required',
        'tipo' => 'required',
    ]; 
    protected $messages = [
        'empresa.required' => 'El nombre es un dato requerido',
    ];

    public function render()
    {
        $model = SucursalCliente::withTrashed()->where('Id_cliente',$this->idCliente)
        ->where('Empresa','LIKE',"%{$this->search}%")
        ->where('Estado','LIKE',"%{$this->search}%")
        ->orderBy('Id_sucursal','desc')
        ->paginate($this->perPage);
        // ->get();
        // $model = SucursalCliente::where('Id_cliente',$this->idCliente)
        // ->orWhere('Empresa','LIKE',"%{$this->search}%")
        // ->orWhere('Estado','LIKE',"%{$this->search}%")
        // ->orderBy('Id_sucursal','desc')
        // ->paginate($this->perPage);
        $estados = DB::table('estados')->get();
        return view('livewire.clientes.table-sucursal',compact('model','estados'));
    }

    public function create()
    {
        $this->validate();
        SucursalCliente::create([
            'Id_cliente' => $this->idCliente,
            'Empresa' => $this->empresa,
            'Estado' => $this->estado,
            'Id_siralab' => $this->tipo,
            'Id_user_c' => $this->idUser,
            'Id_user_m' => $this->idUser,
        ]);
        $this->alert = true;
    }
    public function duplicateMatriz()
    {
        if($this->dup == 1)
        {
            $matriz = Clientes::find($this->idCliente);
            $this->empresa = $matriz->Nombres;
        }else if($this->dup == 0)
        {
            $this->empresa = '';
        }
    }
    public function store()
    {
        $this->validate();
        SucursalCliente::withTrashed()->where('Id_sucursal',$this->idSuc)->restore();
        $model = SucursalCliente::find($this->idSuc);
        $model->Empresa = $this->empresa;
        $model->Estado = $this->estado;
        $model->Id_siralab = $this->tipo;
        $model->Id_user_m = $this->idUser;
        $model->save();
        if($this->status != 1)
        {
            SucursalCliente::find($this->idSuc)->delete();
        }
        $this->alert = true;
        
    }
    public function setData($id,$empresa,$estado,$tipo,$status)
    {
        $this->alert = false;
        $this->resetValidation();
        $this->sw = 0;
        $this->idSuc = $id;
        $this->empresa = $empresa;
        $this->estado = $estado;
        $this->tipo = $tipo;
        if($status != NULL)
        {
            $this->status = 0;
        }else{
            $this->status = 1;
        }
    }
    public function btnDetails($idSuc)
    {
        return redirect()->to('admin/clientes/cliente_detalle/'.$this->idCliente.'/'.$idSuc);
    }
    public function btnCreate()
    {
        $this->dup = 0;
        $this->alert = false;
        $this->clean();
        $this->resetValidation();
        $this->sw = 1;
    }
    public function resetAlert()
    {
        $this->alert = false;
    }
    public function clean()
    {
        $this->idSuc = '';
        $this->empresa = '';
        $this->estado = '';
        $this->tipo = '';
        $this->status = 1;
    }
}
