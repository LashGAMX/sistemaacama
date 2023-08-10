<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CampoConCalidad extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'campo_concalidad';
    protected $primaryKey = 'Id_conductividad';
    public $timestamps = true;

    protected $fillable = [
        'Id_solicitud',
        'Id_conCalidad',
        'Lectura1',
        'Lectura2',
        'Lectura3',
        'Estado',
        'Promedio',
        'Id_user_c',
        'Id_user_m'
    ];
}
