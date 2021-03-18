<?php

namespace App\Http\Livewire\AnalisisQ;

use App\Models\MatrizParametro;
use App\Models\MetodoPrueba;
use App\Models\Norma;
use App\Models\Parametro;
use App\Models\ProcedimientoAnalisis;
use App\Models\Rama;
use App\Models\Sucursal;
use App\Models\TipoFormula;
use App\Models\Unidad;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Parametros extends Component
{
    use WithPagination;
    public $idUser;
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
    public $sw = false;
    public $status;
    public $search;

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
        $model = DB::table('ViewParametros')->get();
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
        Parametro::create([
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
    }
    public function store()
    {
        
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
    }
    public function clean()
    {
        $this->idParametro = '';
        $this->laboratorio = '';
        $this->parametro = '';
        $this->unidad = '';
        $this->tipo = '';
        $this->norma = '';
        $this->limite = '';
        $this->matriz = '';
        $this->rama = '';
        $this->metodo = '';
        $this->procedimiento = '';
    }
    public function btnCreate()
    {
        $this->clean();
        if($this->sw == true)
        {
            $this->resetValidation();
            $this->sw = false;
        }
    }

}
 
