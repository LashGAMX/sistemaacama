<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ValoracionDqo extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'alcalinidad_valoracion';
    protected $primaryKey = 'Id_valoracion';
    public $timestamps = true;

    protected $fillable = [
        'Id_lote',
        'Id_parametro',
        'Titulado1',
        'Titulado2',
        'Titulado3',
        'Vol1',
        'Vol2',
        'Vol3',
        'Blanco',
        'Equivalentes',
        'Factor',
        'Resultado',
    ];
}
