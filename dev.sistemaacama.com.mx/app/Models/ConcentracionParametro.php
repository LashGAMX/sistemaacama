<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConcentracionParametro extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'concentracion_parametro';
    protected $primaryKey = 'Id_concentracion';
    public $timestamps = true;

    protected $fillable = [
        'Id_parametro',
        'Concentracion',
        'Id_user_c',
        'Id_user_m'
    ];
}
