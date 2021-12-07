<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VerificacionMetales extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'verificacion_metales';
    protected $primaryKey = 'Id_verificacion';
    public $timestamps = true;

    protected $fillable = [
        'Id_lote',
        'STD_cal',
        'ABS_teorica',
        'Conc_mgL',
        'ABS1',
        'ABS2',
        'ABS3',
        'ABS4',
        'ABS5',
        'ABS_prom',
        'Masa_caract',
        'Conclusion',
        'Conc_obtenida',
        'Porc_rec',
        'Cumple',
        'Id_user_c',
        'Id_user_m'
    ];
}
