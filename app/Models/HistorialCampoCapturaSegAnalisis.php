<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HistorialCampoCapturaSegAnalisis extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'hist_campoCaptSegAnalisis';
    protected $primaryKey = 'Id_hist';
    public $timestamps = true;

    protected $fillable = [
        'Id_seguimiento',
        'Id_servicio',
        'Obs_solicitud',
        'Muestreo',
        'Obs_muestreo',
        'Recepcion',
        'Obs_recepcion',
        'Nota',
        'F_creacion',
        'Id_user_c',
        'F_modificacion',
        'Id_user_m',
    ];
}
