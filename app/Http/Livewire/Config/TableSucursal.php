<?php

namespace App\Http\Livewire\Config;

use App\Models\Sucursal;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class TableSucursal extends Component
{

    use WithPagination;

    public $idUser;
    public $search = '';
    protected $queryString = ['search' => ['except' => '']];
    public $perPage = 5;
    public $show = false;
    public $name;
    public $idSuc;


    protected $rules = [
        'name' => 'required|min:6',
    ];
    protected $messages = [
        'name.required' => 'El nombre es un dato requerido',
    ];
 
    public function render() 
    { 
        $sucursal = Sucursal::where('Sucursal','LIKE',"%{$this->search}%")
        ->orWhere('Id_sucursal','LIKE',"%{$this->search}%")
        ->paginate($this->perPage);
        return view('livewire.config.table-sucursal',compact('sucursal'));
    } 
    public function setSucursal()
    {
      $this->validate();
      Sucursal::create([
          'Sucursal' => $this->name,
          'Id_user_c' => $this->idUser, 
          'Id_user_m' => $this->idUser, 
      ]);
    }
    public function store()
    {
        $this->validate();
        $sucursal = Sucursal::find($this->idSuc);
        $sucursal->Sucursal = $this->name;
        $sucursal->Id_user_m = $this->idUser;
        $sucursal->save();
    }
    public function setData($id,$name)
    {
        $this->resetValidation();
        $this->idSuc = $id;
        $this->name = $name;
    }

    public function setBtn()
    {
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

}
  