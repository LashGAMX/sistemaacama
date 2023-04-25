<?php

namespace App\Http\Livewire\Config;

use App\Models\AreaAnalisis;
use App\Models\HistorialAreaAnalisis;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class TableAreaAnalisis extends Component
{
    use WithPagination;
    public $idUser;
    public $search = '';
    protected $queryString = ['search' => ['except' => '']]; 
    public $perPage = 50;
    public $show = false;
    public $alert = false;

    public $area;
    public $idArea;
    public $nota;

    protected $rules = [ 
        'area' => 'required',
    ];
    protected $messages = [
        'area.required' => 'El nombre es un dato requerido',
    ];

    public function render()
    {
        $model = AreaAnalisis::where('Area_analisis','LIKE',"%{$this->search}%")
        ->paginate($this->perPage);
        return view('livewire.config.table-area-analisis',compact('model'));
    }

    public function create()
    {
        $this->validate();
        $model = AreaAnalisis::create([
            'Area_analisis' => $this->area,
            'Id_user_c' => $this->idUser, 
            'Id_user_m' => $this->idUser,
        ]);
        $this->idArea = $model->Id_area_analisis;
        $this->nota = "Creación de registro";
        $this->historial();
        $this->alert = true;
    }
    public function store()
    {
        $this->validate();
        $model = AreaAnalisis::find($this->idArea);
        $model->Area_analisis = $this->area;
        $this->historial();
        $model->save();
        
        $this->alert = true;
    }
    public function setData($id,$area)
    {
        $this->alert = false;
        $this->resetValidation();
        $this->idArea = $id;
        $this->area = $area;
    }
    
    Public function historial()
    {
        $model = DB::table('area_analisis')->where('Id_area_analisis',$this->idArea)->first();
        HistorialAreaAnalisis::create([
            'Id_area_analisis' => $this->idArea,
            'Area_analisis' => $model->Area_analisis,
            'Nota' => $this->nota,
            'F_creacion' => $model->created_at,
            'Id_user_c' => $model->Id_user_c,
            'F_modificacion' => $model->updated_at,
            'Id_user_m' => $model->Id_user_m,
        ]);
    }
    public function setBtn()
    {
        $this->clean();
        $this->alert = false;
        if($this->show == false)
        {
            $this->resetValidation();
            $this->show = true;
        }
    }
    public function deleteBtn()
    {
        if($this->show == true)
        {
            $this->show = false;
        }
    }
    public function resetAlert()
    {
        $this->alert = false;
    }
    public function clean()
    {
        $this->idArea = '';
        $this->area = '';
        $this->nota = '';
    }
}
