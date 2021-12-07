<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CotizacionParametros extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'cotizacion_parametros';
    protected $primaryKey = 'Id_parametro';
    public $timestamps = true;

    protected $fillable = [
        'Id_cotizacion',
        'Id_subnorma',
        'Extra',
        'Id_user_c',
        'Id_user_m'
    ];
}
 