<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Limite001 extends Model
{
    use HasFactory;
    protected $table = 'limitepnorma_001';
    protected $primaryKey = 'Id_limite';
    public $timestamps = true;

    protected $fillable = [
        'Id_tipo',
        'Id_parametro',
        'Prom_Mmax',
        'Prom_Mmin',
        'Prom_Dmax',
        'Prom_Dmin',
    ];
}
