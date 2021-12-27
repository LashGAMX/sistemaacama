<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HistorialCampoCaptMuestreoPh extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'hist_campoCaptMuestreoPh';
    protected $primaryKey = 'Id_hist';
    public $timestamps = true;

    protected $fillable = [
        'Id_ph',
        'Id_solicitud',
        'Num_toma',
        'Materia',
        'Olor',
        'Color',
        'Ph1',
        'Ph2',
        'Ph3',
        'Promedio',
        'Fecha',
        'Activo',
        'Nota',
        'F_creacion',
        'Id_user_c',
        'F_modificacion',
        'Id_user_m',
    ];
}
