<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EstandarVerificacionMet extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'estandar_verificacion_met';
    protected $primaryKey = 'Id_estandar';
    public $timestamps = true;

    protected $fillable = [
        'Id_lote',
        'Conc_mgL',
        'DESV_std',
        'Cumple',
        'ABS1',
        'ABS2',
        'ABS3',
        'ABS4',
        'ABS5',
        'Id_user_c',
        'Id_user_m'
    ];
}
