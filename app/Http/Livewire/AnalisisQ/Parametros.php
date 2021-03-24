<?php

namespace App\Http\Livewire\AnalisisQ;

use App\Models\DetallesTipoCuerpo;
use App\Models\Limite001;
use App\Models\MatrizParametro;
use App\Models\MetodoPrueba;
use App\Models\Norma;
use App\Models\Parametro;
use App\Models\ProcedimientoAnalisis;
use App\Models\Rama;
use App\Models\Sucursal;
use App\Models\TipoFormula;
use App\Models\Unidad;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Parametros extends Component
{
    use WithPagination;
    public $idUser;
    public $alert = false;
    protected $queryString = ['search' => ['except' => '']]; 
    public $search;
    public $sw = false;

    public $parametro;
    public $idParametro;
    public $laboratorio;
    public $tipo;
    public $unidad;
    public $norma;
    public $limite;
    public $matriz;
    public $rama;
    public $metodo;
    public $procedimiento;
    public $status;
 

    protected $rules = [ 
        'parametro' => 'required',
        'laboratorio' => 'required',
        'tipo' => 'required',
        'unidad' => 'required',
        'norma' => 'required',
        'limite' => 'required',
        'matriz' => 'required',
        'rama' => 'required',
        'metodo' => 'required',
        'procedimiento' => 'required',
    ];
    protected $messages = [
        'parametro.required' => 'El parametro es un dato requerido',
    ];

    public function render()
    {
        $model = DB::table('ViewParametros')
        ->where('Parametro','LIKE',"%{$this->search}%")
        ->get();
        $laboratorios = Sucursal::all();
        $unidades = Unidad::all();
        $tipos = TipoFormula::all();
        $normas = Norma::all();   
        $metrices = MatrizParametro::all();
        $ramas = Rama::all();
        $metodos = MetodoPrueba::all();
        $procedimientos = ProcedimientoAnalisis::all();

        return view('livewire.analisis-q.parametros', 
        compact('model','laboratorios','unidades','tipos','normas','metrices','ramas','metodos','procedimientos'));
    }
    public function create()
    {
        $this->validate();
        $parametro = Parametro::create([
            'Id_laboratorio' => $this->laboratorio,
            'Id_tipo_formula' => $this->tipo,
            'Id_rama' => $this->rama,
            'Parametro' => $this->parametro,
            'Id_unidad' => $this->unidad,
            'Id_metodo' => $this->metodo,
            'Id_norma' => $this->norma,
            'Limite' => $this->limite,
            'Id_procedimiento' => $this->procedimiento,
            'Id_matriz' => $this->matriz
        ]);

        switch($this->norma)
        {
            case 1:
                $detalle = DetallesTipoCuerpo::all();
                foreach($detalle as $item)
                {
                    Limite001::create([
                        'Id_tipo' => $item->Id_detalle,
                        'Id_parametro' => $parametro->Id_parametro,
                    ]);
                }
                break;
            default:
            break;
        }
        $this->alert = true;
    }
    public function store()
    {
        $this->validate();
        $model = Parametro::find($this->idParametro);
        $model->Id_laboratorio = $this->laboratorio;
        $model->Id_tipo_formula = $this->tipo;
        $model->Id_rama = $this->rama;
        $model->Parametro = $this->parametro;
        $model->Id_unidad= $this->unidad;
        $model->Id_metodo = $this->metodo;
        $model->Id_norma = $this->norma;
        $model->Limite = $this->limite;
        $model->Id_procedimiento = $this->procedimiento;
        $model->Id_matriz = $this->matriz;
        $model->save();
        $this->alert = true;
    }
    public function setData($id,$laboratorio,$parametro,$unidad,$tipo,$norma,$limite,$matriz,$rama,$metodo,$procedimiento,$status)
    {
        $this->sw = true;
        $this->resetValidation();
        $this->idParametro = $id;
        $this->laboratorio = $laboratorio;
        $this->parametro = $parametro;
        $this->unidad = $unidad;
        $this->tipo = $tipo;
        $this->norma = $norma;
        $this->limite = $limite;
        $this->matriz = $matriz;
        $this->rama = $rama;
        $this->metodo = $metodo;
        $this->procedimiento = $procedimiento;
        if($status != null)
        {
            $this->status = 0;
        }else{
            $this->status = 1;
        }
        $this->alert = false;
    }
    public function clean()
    {
        $this->idParametro = '';
        $this->laboratorio = 1;
        $this->parametro = '';
        $this->unidad = 1;
        $this->tipo = 1;
        $this->norma = 1;
        $this->limite = '';
        $this->matriz = 1;
        $this->rama = 1;
        $this->metodo = 1;
        $this->procedimiento = 1;
        $this->status = 1;
    }
    public function btnCreate()
    {
        $this->alert = false;
            $this->clean();
            $this->resetValidation();
            $this->sw = false;
    }
    public function resetAlert()
    {
        $this->alert = false;
    }
}
 
