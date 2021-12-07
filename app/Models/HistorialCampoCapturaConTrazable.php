<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HistorialCampoCapturaConTrazable extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'hist_campoCaptConTrazable';
    protected $primaryKey = 'Id_hist';
    public $timestamps = true;

    protected $fillable = [
        'Id_conductividad',
        'Id_solicitud',
        'Conductividad',
        'Marca',
        'Lote',
        'Inicio_caducidad',
        'Fin_caducidad',
        'Nota',
        'F_creacion',
        'Id_user_c',
        'F_modificacion',
        'Id_user_m',
    ];
}
