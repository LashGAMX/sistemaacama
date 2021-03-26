<?php

namespace App\Http\Livewire\Clientes;

use App\Models\PuntoMuestreoSir;
use App\Models\TituloConsecionSir;
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

    public $idPunto;
    public $punto;
    public $titulo;
    public $anexo;
    public $siralab = 1;
    public $pozos = 1;
    public $cuerpo;
    public $latitud;
    public $longitud;
    public $hora;
    public $inicio;
    public $termino;

    // protected $rules = [ 
    //     'punto' => 'required',
    // ];
    // protected $messages = [
    //     'punto.required' => 'El punto es un dato requerido',
    // ];

    public function render()
    {
        $titulos = TituloConsecionSir::where('Id_sucursal',$this->idSuc)->get();
        $model = PuntoMuestreoSir::where('Id_sucursal',$this->idSuc)->get();
        return view('livewire.clientes.table-punto-siralab',compact('model','titulos'));
    }

    public function create()
    {
        // $this->validate();
        PuntoMuestreoSir::create([
            'Id_sucursal' => $this->idSuc,
            'Titulo_consecion' => $this->titulo,
            'Punto' => $this->punto,
            'Anexo' => $this->anexo,
            'Siralab' => $this->siralab,
            'Pozos' => $this->pozos,
            'Cuerpo_receptor' => $this->cuerpo,
            'Latitud' => $this->latitud,
            'Longitud' => $this->longitud,
            'Hora' => $this->hora,
            'F_inicio' => $this->inicio,
            'F_termino' => $this->termino,
        ]);
        $this->alert = true;
    }
    public function store()
    {
        // $this->validate();
        $model = PuntoMuestreoSir::find($this->idPunto);
    
        $model->Titulo_consecion = $this->titulo;
        $model->Punto = $this->punto;
        $model->Anexo = $this->anexo;
        $model->Siralab = $this->siralab;
        $model->Pozos = $this->pozos;
        $model->Cuerpo_receptor = $this->cuerpo;
        $model->Latitud = $this->latitud;
        $model->Longitud = $this->longitud;
        $model->Hora = $this->hora;
        $model->F_inicio = $this->inicio;
        $model->F_termino = $this->termino;
        $model->save();
        $this->alert = true;
    }
    public function setData($idPunto,$punto,$titulo,$anexo,$siralab,$pozos,$cuerpo,$latitud,$longitud,$hora,$inicio,$termino)
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
        $this->latitud = $latitud;
        $this->longitud = $longitud;
        $this->hora = $hora;
        $this->inicio = $inicio;
        $this->termino = $termino;
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
        $this->cuerpo = '';
        $this->latitud = '';
        $this->longitud = '';
        $this->hora = '';
        $this->inicio = '';
        $this->termino = '';
    }
    public function resetAlert()
    {
        $this->alert = false;
    }
}
