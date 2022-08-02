<?php

namespace App\Http\Livewire\AnalisisQ;

use App\Models\AreaAnalisis;
use App\Models\DetallesTipoCuerpo;
use App\Models\HistorialParametros;
use App\Models\Limite001;
use App\Models\MatrizParametro;
use App\Models\MetodoPrueba;
use App\Models\Norma;
use App\Models\Parametro;
use App\Models\ParametroNorma;
use App\Models\PrecioCatalogo;
use App\Models\ProcedimientoAnalisis;
use App\Models\Rama;
use App\Models\SimbologiaParametros;
use App\Models\SimbologiaInforme;
use App\Models\Sucursal;
use App\Models\Tecnica;
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
    public $idNorma = 0;

    public $parametro;
    public $idParametro;
    public $laboratorio;
    public $tipo;
    public $area;
    public $unidad;
    public $norma;
    public $tecnica;
    public $limite;
    public $matriz;
    public $rama;
    public $metodo;
    public $procedimiento;
    public $simbologia;
    public $simbologiaInforme;
    public $status;
    public $nota;


    protected $rules = [
        'parametro' => 'required',
        'laboratorio' => 'required',
        'tipo' => 'required',
        'area' => 'required',
        'unidad' => 'required',
        'limite' => 'required',
        'matriz' => 'required',
        'rama' => 'required',
        'metodo' => 'required',
        'procedimiento' => 'required',
        'simbologia' => 'required',
    ];
    protected $messages = [
        'parametro.required' => 'El parametro es un dato requerido', 
    ];

    public function render()
    {
        $model = DB::table('ViewParametros')
            ->where('Parametro', 'LIKE', "%{$this->search}%")
            ->get();
        
        $laboratorios = Sucursal::all();
        $unidades = Unidad::all();
        $tipos = TipoFormula::all();
        $areas = AreaAnalisis::all();
        $normas = Norma::all();
        $tecnicas = Tecnica::all();
        $metrices = MatrizParametro::all();
        $ramas = Rama::all();
        $metodos = MetodoPrueba::all();
        $procedimientos = ProcedimientoAnalisis::all();
        $simbologias = SimbologiaParametros::all();
        $simbologiaInf= SimbologiaInforme::all();
        $parametroNorma = ParametroNorma::all();

        return view(
            'livewire.analisis-q.parametros',
            compact('model', 'laboratorios', 'unidades', 'tipos','areas','normas', 'metrices', 'ramas', 'metodos','tecnicas', 'procedimientos', 'simbologias','simbologiaInf','parametroNorma')
        );
    }
    public function setNorma()
    {
        
    }
    public function create()
    {
        $this->validate();
        $parametro = Parametro::create([
            'Id_laboratorio' => $this->laboratorio,
            'Id_tipo_formula' => $this->tipo,
            'Id_area' => $this->area,
            'Id_rama' => $this->rama,
            'Parametro' => $this->parametro,
            'Id_unidad' => $this->unidad,
            'Id_metodo' => $this->metodo,
            'Id_tecnica' => $this->tecnica,
            'Limite' => $this->limite,
            'Id_procedimiento' => $this->procedimiento,
            'Id_matriz' => $this->matriz,
            'Id_simbologia' => $this->simbologia,
            'Id_simbologia_info' => $this->simbologiaInforme,
            'Id_user_c' => $this->idUser,
            'Id_user_m' => $this->idUser,
        ]);

        for ($i=0; $i < sizeof($this->norma); $i++) { 
            $model = ParametroNorma::create([
                'Id_norma' => $this->norma[$i],
                'Id_parametro' => $this->idParametro,
            ]);
        }
        
        $precioCatalogo = PrecioCatalogo::create([
            'Id_parametro' => $parametro->Id_parametro,
            'Id_laboratorio' => $this->laboratorio,
            'Precio' => 0,
            'Id_user_c' => $this->idUser,
            'Id_user_m' => $this->idUser,
        ]);

        if ($this->status != 1) {
            Parametro::find($parametro->Id_parametro)->delete();
        }
        $this->idParametro = $parametro->Id_parametro;
        $this->nota = "Creación de registro";
        $this->historial();
        $this->alert = true;
    }
    public function store()
    {
        $this->validate();
        Parametro::withTrashed()->find($this->idParametro)->restore();
        $model = Parametro::find($this->idParametro);
        $model->Id_laboratorio = $this->laboratorio;
        $model->Id_tipo_formula = $this->tipo;
        $model->Id_area = $this->area;
        $model->Id_rama = $this->rama;
        $model->Parametro = $this->parametro;
        $model->Id_unidad = $this->unidad;
        $model->Id_metodo = $this->metodo;
        $model->Id_tecnica = $this->tecnica;
        $model->Limite = $this->limite;
        $model->Id_procedimiento = $this->procedimiento;
        $model->Id_matriz = $this->matriz;
        $model->Id_simbologia = $this->simbologia;
        $model->Id_simbologia_info = $this->simbologiaInforme;
        $model->Id_user_m = $this->idUser;
        $this->historial();
        $model->save();

        $model = DB::table('parametros_normas')->where('Id_parametro',$this->idParametro)->delete();

        for ($i=0; $i < sizeof($this->norma); $i++) { 
            $model = ParametroNorma::create([
                'Id_norma' => $this->norma[$i],
                'Id_parametro' => $this->idParametro,
            ]);
        }

        if ($this->status != 1) {
            Parametro::find($this->idParametro)->delete();
        }
        $this->alert = true;
    }
    public function setData($id, $laboratorio, $parametro, $unidad, $tipo,$area, $limite,$tecnica, $matriz, $simbologia, $simbologiaInforme, $rama, $metodo, $procedimiento, $status)
    {
        $this->sw = true;
        $this->resetValidation();
        $this->idParametro = $id;
        $this->laboratorio = $laboratorio;
        $this->parametro = $parametro;
        $this->unidad = $unidad;
        $this->tipo = $tipo;
        $this->area = $area;
        $this->tecnica = $tecnica;
        $this->limite = $limite;
        $this->matriz = $matriz;
        $this->simbologia = $simbologia;
        $this->simbologiaInforme = $simbologiaInforme;
        $this->rama = $rama;
        $this->metodo = $metodo;
        $this->procedimiento = $procedimiento;
        if ($status != NULL) {
            $this->status = 0;
        } else {
            $this->status = 1;
        }
        $this->alert = false;
    }

    public function historial()
    {
        $model = DB::table('ViewParametros')->where('Id_parametro', $this->idParametro)->first();
        HistorialParametros::create([
            'Id_parametro' => $model->Id_parametro,
            'Laboratorio' => $model->Sucursal,
            'Tipo_formula' => $model->Tipo_formula,
            'Rama' => $model->Rama,
            'Parametro' => $model->Parametro,
            'Unidad' => $model->Unidad,
            'Metodo' => $model->Id_metodo,
            'Limite' => $model->Limite,
            'Procedimiento' => $model->Procedimiento,
            'Matriz' => $model->Matriz,
            'Simbologia' => $model->Simbologia,
            'Nota' => $this->nota,
            'F_creacion' => $model->created_at,
            'Id_user_c' => $model->Id_user_c,
            'F_modificacion' => $model->updated_at,
            'Id_user_m' => $this->idUser 
        ]);
    }
    public function clean()
    {
        $this->idParametro = '';
        $this->laboratorio = 1;
        $this->parametro = '';
        $this->unidad = 1;
        $this->tipo = 1;
        $this->area = 1;
        $this->limite = '';
        $this->matriz = 1;
        $this->simbologia = 1;
        $this->rama = 1;
        $this->tecnica =1;
        $this->metodo = 1;
        $this->procedimiento = 1;
        $this->status = 1;
        $this->nota = '';
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
