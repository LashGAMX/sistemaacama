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
    public $cuerpo = 5;
    public $agua = 11;
    public $categoria = 0;
    public $latitud;
    public $gradosLat;
    public $minutosLat;
    public $segundosLat;
    public $longitud;
    public $gradosLong;
    public $minutosLong;
    public $segundosLong;
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
        $categorias = DB::table('categoria001_2021')->get();
        $model = DB::table('ViewPuntoMuestreoSir')->where('Id_sucursal',$this->idSuc)->get();
        return view('livewire.clientes.table-punto-siralab',compact('model','titulos','categorias','cuerpos','uso'));
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
            'Categoria' => $this->categoria,
            'Latitud' => $this->latitud,
            'GradosLatitud' => $this->gradosLat,
            'MinutosLat' => $this->minutosLat,
            'SegundosLat' => $this->segundosLat,
            'Longitud' => $this->longitud,
            'GradosLong' => $this->gradosLong,
            'MinutosLong' => $this->minutosLong,
            'SegundosLong' => $this->segundosLong,
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
        $model->Categoria = $this->categoria;
        $model->Latitud = $this->latitud;
        $model->GradosLat = $this->gradosLat;
        $model->MinutosLat = $this->minutosLat;
        $model->SegundosLat = $this->segundosLat;
        $model->Longitud = $this->longitud;
        $model->GradosLong = $this->gradosLong;
        $model->MinutosLong = $this->minutosLong;
        $model->SegundosLong = $this->segundosLong;
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
    public function setData($idPunto)
    {
        $model = PuntoMuestreoSir::withTrashed()->find($idPunto);
        $this->sw = true;
        // $this->resetValidation();
        $this->alert = false;

        $this->idPunto = $idPunto;
        $this->punto = $model->Punto;
        $this->titulo = $model->Titulo_consecion;
        $this->anexo = $model->Anexo;
        $this->siralab = $model->Siralab;
        $this->pozos = $model->Pozos;
        $this->cuerpo = $model->Cuerpo_receptor;
        $this->agua = $model->Uso_agua;
        $this->categoria = $model->Categoria;
        $this->latitud = $model->Latitud;
        $this->gradosLat = $model->GradosLat;
        $this->minutosLat = $model->MinutosLat;
        $this->segundosLat = $model->SegundosLat; 
        $this->longitud = $model->Longitud;
        $this->gradosLong = $model->GradosLong;
        $this->minutosLong = $model->MinutosLong;
        $this->segundosLong = $model->SegundosLong;
        $this->hora = $model->Hora;
        $this->observacion = $model->Observacion;
        $this->inicio = $model->F_inicio;
        $this->termino = $model->F_termino;
        if($model->delete_at != null)
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
        $this->cuerpo = 5;
        $this->agua = 11;
        $this->categoria = 0;
        $this->latitud = '';
        $this->gradosLat = '';
        $this->minutosLat = '';
        $this->segundosLat = '';
        $this->longitud = '';
        $this->gradosLong = '';
        $this->minutosLong = '';
        $this->segundosLong= '';
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
