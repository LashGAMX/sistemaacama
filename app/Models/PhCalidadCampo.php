<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PhCalidadCampo extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'ph_calidadcampo';
    protected $primaryKey = 'Id_phCalidad';
    public $timestamps = true;

    protected $fillable = [
        'Id_solicitud',
        'Num_toma',
        'Ph_calidad',
        'Lectura1',
        'Lectura2',
        'Lectura3',
        'Estado',        
        'Promedio',
        'Activo',
        'Id_user_c',
        'Id_user_m'
    ];
}
