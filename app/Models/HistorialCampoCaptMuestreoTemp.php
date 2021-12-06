<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HistorialCampoCaptMuestreoTemp extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'hist_campoCaptMuestreoTemp';
    protected $primaryKey = 'Id_hist';
    public $timestamps = true;

    protected $fillable = [
        'Id_temperatura',
        'Id_solicitud',
        'Temperatura1',
        'Temperatura2',
        'Temperatura3',
        'Promedio',        
        'Nota',
        'F_creacion',
        'Id_user_c',
        'F_modificacion',
        'Id_user_m',
    ];
}
