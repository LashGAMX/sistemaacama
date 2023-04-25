<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ValoracionCloro extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'valoracion_cloro';
    protected $primaryKey = 'Id_valoracion';
    public $timestamps = true;

    protected $fillable = [
        'Id_lote',
        'Id_parametro',
        'Blanco',
        'Ml_titulado1',
        'Ml_titulado2',
        'Ml_titulado3',
        'Ml_trazable',
        'Normalidad',
        'Resultado',
    ];
}
