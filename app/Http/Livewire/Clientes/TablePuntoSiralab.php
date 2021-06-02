<?php

namespace App\Http\Livewire\Clientes;

use App\Models\PuntoMuestreoSir;
use App\Models\TituloConsecionSir;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class TablePuntoSiralab extends Component
{
    use WithPagination; 
    public $idSuc;
    public $idUser;
    public $search = '';
    protected $queryString = ['search' => ['except' => '']]; 
    public $perPage = 5;
    public $sw = false;
    public $alert = false;

    public $status = 1;
    public $idPunto;
    public $punto;
    public $titulo;
    public $anexo;
    public $siralab = 1;
    public $pozos = 1;
    public $cuerpo = 0;
    public $agua = 0;
    public $latitud;
    public $longitud;
    public $hora;
    public $inicio;
    public $termino;
    public $observacion;

    // protected $rules = [ 
    //     'punto' => 'required',
    // ];
    // protected $messages = [
    //     'punto.required' => 'El punto es un dato requerido',
    // ];

    public function render()
    {
        $cuerpos = DB::table('tipo_cuerpo')->get();
        $uso = DB::table('detalle_tipoCuerpos')->where('Id_tipo',$this->cuerpo)->get();
        $titulos = TituloConsecionSir::where('Id_sucursal',$this->idSuc)->get();
        $model = DB::table('ViewPuntoMuestreoSir')->where('Id_sucursal',$this->idSuc)->get();
        return view('livewire.clientes.table-punto-siralab',compact('model','titulos','cuerpos','uso'));
    }

    public function create()
    {
        // $this->validate();
        $model = PuntoMuestreoSir::create([
            'Id_sucursal' => $this->idSuc,
            'Titulo_consecion' => $this->titulo,
            'Punto' => $this->punto,
            'Anexo' => $this->anexo,
            'Siralab' => $this->siralab,
            'Pozos' => $this->pozos,
            'Cuerpo_receptor' => $this->cuerpo,
            'Uso_agua' => $this->agua,
            'Latitud' => $this->latitud,
            'Longitud' => $this->longitud,
            'Hora' => $this->hora,
            'F_inicio' => $this->inicio,
            'F_termino' => $this->termino,
            'Id_user_c' => $this->idUser,
            'Id_user_m' => $this->idUser,
            'Observacion' => $this->observacion,
        ]);
        if($this->status != 1)
        {
            PuntoMuestreoSir::find($model->Id_punto)->delete();
        }
        $this->alert = true;
    }
    public function store()
    {
        // $this->validate();
        PuntoMuestreoSir::withTrashed()->find($this->idPunto)->restore();
        $model = PuntoMuestreoSir::find($this->idPunto);
        $model->Titulo_consecion = $this->titulo;
        $model->Punto = $this->punto;
        $model->Anexo = $this->anexo;
        $model->Siralab = $this->siralab;
        $model->Pozos = $this->pozos;
        $model->Cuerpo_receptor = $this->cuerpo;
        $model->Uso_agua = $this->agua;
        $model->Latitud = $this->latitud;
        $model->Longitud = $this->longitud;
        $model->Hora = $this->hora;
        $model->F_inicio = $this->inicio;
        $model->F_termino = $this->termino;
        $model->Id_user_m = $this->idUser;
        $model->Observacion = $this->observacion;
        $model->save();
        if($this->status != 1)
        {
            PuntoMuestreoSir::find($model->Id_punto)->delete();
        }
        $this->alert = true;
    }
    public function setData($idPunto,$punto,$titulo,$anexo,$siralab,$pozos,$cuerpo,$agua,$latitud,$longitud,$hora,$observacion,$inicio,$termino,$status)
    {
        $this->sw = true;
        // $this->resetValidation();
        $this->alert = false;

        $this->idPunto = $idPunto;
        $this->punto = $punto;
        $this->titulo = $titulo;
        $this->anexo = $anexo;
        $this->siralab = $siralab;
        $this->pozos = $pozos;
        $this->cuerpo = $cuerpo;
        $this->agua = $agua;
        $this->latitud = $latitud;
        $this->longitud = $longitud;
        $this->hora = $hora;
        $this->observacion = $observacion;
        $this->inicio = $inicio;
        $this->termino = $termino;
        if($status != null)
        {
            $this->status = 0;
        }else{
            $this->status = 1;
        }
    }
    
    public function setBtn()
    {
        $this->alert = false;
        $this->clean();
        $this->sw = false;
            $this->resetValidation();
    }

    public function clean()
    {
        $this->idPunto = '';
        $this->punto = '';
        $this->titulo = '';
        $this->anexo = '';
        $this->siralab = 1;
        $this->pozos = 1;
        $this->cuerpo = 0;
        $this->agua = 0;
        $this->latitud = '';
        $this->longitud = '';
        $this->hora = '';
        $this->inicio = '';
        $this->termino = '';
        $this->status = 1;
        $this->observacion = '';
    }
    public function resetAlert()
    {
        $this->alert = false;
    }
}
