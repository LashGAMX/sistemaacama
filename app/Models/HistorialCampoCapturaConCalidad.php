<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HistorialCampoCapturaConCalidad extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'hist_campoCaptConCalidad';
    protected $primaryKey = 'Id_hist';
    public $timestamps = true;

    protected $fillable = [
        'Id_conductividad',
        'Id_solicitud',
        'Id_conCalidad',
        'Lectura1',
        'Lectura2',
        'Lectura3',
        'Estado',
        'Promedio',
        'Nota',
        'F_creacion',
        'Id_user_c',
        'F_modificacion',
        'Id_user_m',
    ];
}
