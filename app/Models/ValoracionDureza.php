<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ValoracionDureza extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'valoracion_dureza';
    protected $primaryKey = 'Id_valoracion';
    public $timestamps = true;

    protected $fillable = [
        'Id_lote',
        'Id_parametro',
        'Blanco',
        'Blanco1',
        'Blanco2',
        'Blanco3',
        'SolucionSec1',
        'Disolucion1Sec1',
        'Disolucion2Sec1',
        'Disolucion3Sec1',
        'ResultadoSec1',
        'SolucionSec2',
        'Disolucion1Sec2',
        'Disolucion2Sec2',
        'Disolucion3Sec2',
        'ResultadoSec2',
        'SolucionSec3',
        'Disolucion1Sec3',
        'Disolucion2Sec3',
        'Disolucion3Sec3',
        'ResultadoSec3',
        'Resultado',
    ];
}
