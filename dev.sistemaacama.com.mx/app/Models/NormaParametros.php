<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NormaParametros extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'norma_parametros';
    protected $primaryKey = 'Id_norma_param';
    // public $timestamps = true;

    protected $fillable = [
        'Id_norma',
        'Id_cuerpo',
        'Id_parametro',
        'Reporte',
        'Id_user_c',
        'Id_user_m'
    ];
}
