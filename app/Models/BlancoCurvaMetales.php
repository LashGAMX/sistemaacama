<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlancoCurvaMetales extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'blanco_curva_metales';
    protected $primaryKey = 'Id_blanco';
    public $timestamps = true;

    protected $fillable = [
        'Id_lote',
        'Verif_blanco',
        'ABS_teor_blanco',
        'ABS1',
        'ABS2',
        'ABS3',
        'ABS4',
        'ABS5',
        'ABS_prom',
        'Concl_blanco',
        'Id_user_c',
        'Id_user_m'
    ];
}
