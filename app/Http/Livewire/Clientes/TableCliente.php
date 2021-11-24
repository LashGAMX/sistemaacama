<?php

namespace App\Http\Livewire\Clientes;

use App\Models\ClienteGeneral;
use App\Models\Clientes;
use App\Models\HistorialClientes;
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
    public $nota;

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
        $intermediario = DB::table('ViewIntermediarios')
        ->orderBy('Nombres','asc')
        ->get();
        $model = DB::table('ViewGenerales')
        ->where('Empresa','LIKE',"%{$this->search}%")
        ->orWhere('RFC','LIKE',"%{$this->search}%")
        ->orderBy('Id_cliente','desc') 
        ->get();

        return view('livewire.clientes.table-cliente',compact('model','intermediario'));
    }

    public function create()
    {
       // $this->validate();

        $model = Clientes::create([
            'Nombres' => $this->cliente,
            // 'RFC' => $this->rfc,
            'Id_tipo_liente' => 2,
            'Id_user_c' => $this->idUser,
            'Id_user_m' => $this->idUser
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
        $this->idCliente = $model->Id_cliente;
        $this->nota = "Creación de registro";
        $this->historial();
        $this->alert = true;
    }
    public function store()
    {
        $this->validate([
            'cliente' => 'required',
            'rfc' => 'required|max:13|min:12',
        ]);
        Clientes::withTrashed()->where('Id_cliente',$this->idCliente)->restore();
                $model = Clientes::find($this->idCliente);
                $model->Nombres = $this->cliente;
                // $model->RFC = $this->rfc;
                $model->Id_user_m = $this->idCliente;
                $this->historial();
                $model->save();
        if($this->status != 1) 
        {
            Clientes::find($this->idCliente)->delete();
        }
        
        DB::table('clientes_general')
        ->where('Id_cliente',$this->idCliente)
        ->update([
            'Empresa' => $this->cliente,
            'Id_intermediario' => $this->inter,
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
        $this->inter = $idInter;
        if($status != null)
        {
            $this->status = 0;
        }else{
            $this->status = 1;
        }
    }

    Public function historial()
    {
        $model = DB::table('ViewGenerales')->where('Id_cliente',$this->idCliente)->first();
        HistorialClientes::create([
            'Id_cliente' => $this->idCliente,
            'Nombres' => $model->Nombres,
            'A_paterno' => $model->A_paterno,
            'A_materno' => $model->A_materno,
            'RFC' => $model->RFC,
            'Nota' => $this->nota,
            'F_creacion' => $model->created_at,
            'Id_user_c' => $model->Id_user_c,
            'F_modificacion' => $model->updated_at,
            'Id_user_m' => $this->idUser,
        ]);
    }
    
    public function btnCreate()
    {
        $this->alert = false;
        $this->clean();
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
        $this->status = 1;
        $this->sw = false;
        $this->nota = '';
    }
}
