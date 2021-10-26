<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SeguimientoAnalisis extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'seguimiento_analisis';
    protected $primaryKey = 'Id_seguimiento';
    public $timestamps = true;

    protected $fillable = [ 
        'Id_servicio',
        'Obs_solicitud',
        'Muestreo',
        'Obs_muestreo',
        'Recepcion', 
        'Obs_recepcion'
    ];
}
