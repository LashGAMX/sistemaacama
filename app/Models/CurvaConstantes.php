<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class CurvaConstantes extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'curva_constantes';
    protected $primaryKey = 'Id_curvaConst';
    public $timestamps = true;

    protected $fillable = [
        'Id_lote',
        'B',
        'M',
        'R',
<<<<<<< HEAD
        'Fecha_inicio',
        'Fecha_final'
=======
        'Id_user_c',
        'Id_user_m'
>>>>>>> 7b431173da463061d5c734d827fbd51a2bcf4583
    ];
}