<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConductividadTrazable extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'conductividad_trazable';
    protected $primaryKey = 'Id_conductividad';
    public $timestamps = true;

    protected $fillable = [
        'Conductividad',
        'Marca',
        'Lote',
        'Inicio_caducidad',
        'Fin_caducidad',
        'Id_user_c',
        'Id_user_m'
    ];
}
