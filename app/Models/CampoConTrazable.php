<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CampoConTrazable extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'campo_contrazable';
    protected $primaryKey = 'Id_conductividad';
    public $timestamps = true;

    protected $fillable = [
        'Id_solicitud',
        'Id_conCalidad',
        'Lectura1',
        'Lectura2',
        'Lectura3',
        'Estado',
        'Id_user_c',
        'Id_user_m'
    ];
}
