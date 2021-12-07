<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HistorialCampoCaptMuestreoConductividad extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'hist_campoCaptMuestreoConductividad';
    protected $primaryKey = 'Id_hist';
    public $timestamps = true;

    protected $fillable = [
        'Id_conductividad',
        'Id_solicitud',
        'Conductividad1',
        'Conductividad2',
        'Conductividad3',
        'Promedio',                
        'Nota',
        'F_creacion',
        'Id_user_c',
        'F_modificacion',
        'Id_user_m',
    ];
}
