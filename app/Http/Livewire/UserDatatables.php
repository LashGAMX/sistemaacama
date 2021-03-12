<?php
namespace App\Http\Livewire;
    
use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Str;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\NumberColumn;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
   
class UserDatatables extends LivewireDatatable
{
    public $model = User::class;
   
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function columns()
    {

    }
}