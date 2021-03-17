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
    public $perPage = 5;
    public $sw = 0;
    public $dup = 0;

    public $idSuc;
    public $empresa;
    public $estado;
    public $idEstado;
    public $tipo;
    public $status;

    protected $rules = [ 
        'empresa' => 'required',
    ];
    protected $messages = [
        'empresa.required' => 'El nombre es un dato requerido',
    ];

    public function render()
    {
        // $model = AreaAnalisis::where('Area_analisis','LIKE',"%{$this->search}%")
        // ->paginate($this->perPage);
        $model = SucursalCliente::where('Id_cliente',$this->idCliente)
        ->get();
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
        ]);
    }
    public function duplicateMatriz()
    {
        if($this->dup == 0)
        {
            $matriz = Clientes::find($this->idCliente);
            $this->empresa = $matriz->Nombres;
            $this->dup = 1;
        }else if($this->dup == 1)
        {
            $this->empresa = '';
            $this->dup = 0;
        }
    }
    public function store()
    {
        $this->validate();
        if($this->status != 1)
        {
            SucursalCliente::withTrashed()->where('Id_sucursal',$this->idSuc)->restore();
            $model = SucursalCliente::find($this->idSuc);
            $model->Empresa = $this->empresa;
            $model->Estado = $this->estado;
            $model->Id_siralab = $this->tipo;
            $model->save();
            SucursalCliente::find($this->idSuc)->delete();
        }else{
            SucursalCliente::withTrashed()->where('Id_sucursal',$this->idSuc)->restore();
            $model = SucursalCliente::find($this->idSuc);
            $model->Empresa = $this->empresa;
            $model->Estado = $this->estado;
            $model->Id_siralab = $this->tipo;
            $model->save();
        }
        
    }
    public function setData($id,$empresa,$estado,$tipo,$status)
    {
        $this->resetValidation();
        $this->sw = 0;
        $this->idSuc = $id;
        $this->empresa = $empresa;
        $this->estado = $estado;
        $this->tipo = $tipo;
        if($status != 'null')
        {
            $this->status = 1;
        }else{
            $this->status = 0;
        }
    }
    public function btnDetails($idSuc)
    {
        return redirect()->to('admin/clientes/cliente_detalle/'.$this->idCliente.'/'.$idSuc);
    }
    public function btnCreate()
    {
        $this->reiniciar();
        if($this->sw == 0)
        {
            $this->resetValidation();
            $this->sw = 1;
        }
    }
    public function reiniciar()
    {
        $this->suc = '';
        $this->empresa = '';
        $this->estado = '';
        $this->tipo = '';
        $this->status = '';
    }
}
