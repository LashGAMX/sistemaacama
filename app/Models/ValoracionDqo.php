<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ValoracionDqo extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'valoracion_dqo';
    protected $primaryKey = 'Id_valoracion';
    public $timestamps = true;

    protected $fillable = [
        'Id_lote',
        'Id_parametro',
        'Tipo',
        'Blanco',
        'Vol_k2',
        'Concentracion',
        'Factor',
        'Vol_titulado1',
        'Vol_titulado2',
        'Vol_titulado3',
        'Resultado',
    ];
}
