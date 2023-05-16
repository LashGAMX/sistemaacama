<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CodigoParametros extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'codigo_parametro';
    protected $primaryKey = 'Id_codigo';
    public $timestamps = true;

    protected $fillable = [
        'Id_solicitud', 
        'Id_parametro',
        'Codigo',
        'Num_muestra',
        'Resultado',
        'Resultado2',
        'Asignado',
        'Analizo',
        'Reporte',
        'Cadena',
        'Cancelado',
        'Id_user_c',
        'Id_user_m'
    ];
}
