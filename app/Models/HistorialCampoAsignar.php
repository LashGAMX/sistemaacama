<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HistorialCampoAsignar extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'hist_campoAsignar';
    protected $primaryKey = 'Id_hist';
    public $timestamps = true;

    protected $fillable = [
        'Id_solicitud',
        'Nota',
        'Id_muestreador',
        'F_creacion',
        'Id_user_c',
        'F_modificacion',
        'Id_user_m', 
        'Punto_muestreo',
        'Captura'
    ];
}
