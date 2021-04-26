<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class TipoServicios extends Model
{
    use HasFactory;
    protected $table = 'tipo_servicios';
    protected $primaryKey = 'Id_tipo';
    public $timestamps = true;

    protected $fillable = [
        'Servicio',
        'Descripcion',
        'creo',
        'modifico',
    ];

    public function getCreoAddAttribute()
    {
        return $this->creo ?? Auth::user()->id; 
    }
    public function getModificoAddAttribute()
    {
        return $this->modifico ?? Auth::user()->id;
    }
}
