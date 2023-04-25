<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EvaluacionParametros extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $table = 'evaluacion_parametros';
    protected $primaryKey = 'Evaluacion_parametros_id';
    public $timestamps = true;

    protected $fillable = [
        'Id_cotizacion',
        'Id_parametro',
        'Es_extra',
        'Id_user_c',
        'Id_user_m'
    ];

}
