<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HistorialCampoCaptMuestreoGasto extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'hist_campoCaptMuestreoGasto';
    protected $primaryKey = 'Id_hist';
    public $timestamps = true;

    protected $fillable = [
        'Id_gasto',
        'Id_solicitud',
        'Gasto1',
        'Gasto2',
        'Gasto3',
        'Promedio',
        'Activo',
        'Nota',
        'F_creacion',
        'Id_user_c',
        'F_modificacion',
        'Id_user_m',
    ];
}
