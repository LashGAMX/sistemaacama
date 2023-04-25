<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HistorialCampoCapturaGeneral extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'hist_campoCaptGenerales';
    protected $primaryKey = 'Id_hist';
    public $timestamps = true;

    protected $fillable = [
        'Id_general',
        'Id_solicitud',
        'Captura',
        'Id_equipo',
        'Temperatura_a',
        'Temperatura_b',
        'Latitud',
        'Longitud',
        'Altitud',
        'Pendiente',
        'Criterio',
        'Supervisor',        
        'Nota',        
        'F_creacion',
        'Id_user_c',
        'F_modificacion',
        'Id_user_m',
    ];
}
