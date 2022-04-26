<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Limite001 extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'limitepnorma_001';
    protected $primaryKey = 'Id_limite';
    public $timestamps = true;

    protected $fillable = [
        'Id_tipo',
        'Id_categoria',
        'Id_parametro',
        'Prom_Mmax',
        'Prom_Mmin',
        'Prom_Dmax',
        'Prom_Dmin',
        'Id_user_c',
        'Id_user_m'
    ];
}
