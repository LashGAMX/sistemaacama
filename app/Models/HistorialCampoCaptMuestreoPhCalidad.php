<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HistorialCampoCaptMuestreoPhCalidad extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'hist_campoCaptMuestreoPhCalidad';
    protected $primaryKey = 'Id_hist';
    public $timestamps = true;

    protected $fillable = [
        'Id_phCalidad',
        'Id_solicitud',
        'Ph_calidad',
        'Lectura1',
        'Lectura2',
        'Lectura3',
        'Estado',
        'Promedio',
        'Activo',
        'Nota',
        'F_creacion',
        'Id_user_c',
        'F_modificacion',
        'Id_user_m',
    ];
}
