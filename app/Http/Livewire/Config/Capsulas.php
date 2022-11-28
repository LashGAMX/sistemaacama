<?php

namespace App\Http\Livewire\Config;

use App\Models\Capsulas as CapsulasSolidos;
use Livewire\Component;

class Capsulas extends Component
{
      // Variables publicas 
  public $idUser;
  public $show = false;
  public $alert = false;
  public $search = '';

  // Variables form
  public $idCris;
  public $serie;
  public $peso;
  public $min; 
  public $max;
  public $nota;
    public function render()
    {
        $model = CapsulasSolidos::withTrashed()
      ->where('Num_serie','LIKE',"%{$this->search}%")
      ->orWhere('Peso','LIKE',"%{$this->search}%")
      ->get();
        return view('livewire.config.capsulas',compact('model'));
    }
    public function create()
  {
      $model = CapsulasSolidos::create([
          'Num_serie' => $this->serie,
          'Peso' => $this->peso, 
          'Min' => ($this->peso - 0.0001),
          'Max' => ($this->peso + 0.0001),
          'Estado' => 0,
        //   'Id_user_c' => $this->idUser,  
        //   'Id_user_m' => $this->idUser,
      ]);
      $this->idPro = $model->Id_matraz;
    //   $this->nota = "Creación de registro";
    //   $this->historial();
      $this->alert = true;
  }
  public function store()
  {
      //$this->historial();
      $model = CapsulasSolidos::find($this->idCris);
      $model->Num_serie = $this->serie;
      $model->Peso = $this->peso;
      $model->Min = ($this->peso - 0.0001);
      $model->Max = ($this->peso + 0.0001);
      $model->save();
      $this->alert = true;
  }
  public function setData($idCris,$serie,$peso,$min,$max)
  { 
      $this->alert = false;
      $this->idCris = $idCris;
      $this->serie = $serie;
      $this->peso = $peso;
      $this->min = $min;
      $this->max = $max;
  }
//   Public function historial()
//   {
//       $model = DB::table('procedimiento_analisis')->where('Id_procedimiento',$this->idPro)->first();
//       HistorialProcedimientoAnalisis::create([
//           'Id_procedimiento' => $this->idPro,
//           'Procedimiento' => $model->Procedimiento,
//           'Descripcion' => $model->Descripcion,
//           'Nota' => $this->nota,
//           'F_creacion' => $model->created_at,
//           'Id_user_c' => $model->Id_user_c,
//           'F_modificacion' => $model->updated_at,
//           'Id_user_m' => $model->Id_user_m,
//       ]);
//   }
  public function btnCreate()
  {
      $this->clean();
      $this->show = true;
      $this->alert = false;
  }
  public function btnCancel()
  {
      $this->show = false;
  }
  public function clean()
  {
      $this->idCris = '';
      $this->serie = '';
      $this->peso = '';
      $this->min = '';
      $this->max = '';
  }
}
