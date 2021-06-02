<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PuntoMuestreoSir extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'puntos_muestreo';
    protected $primaryKey = 'Id_punto';
    public $timestamps = true;

    protected $fillable = [
        'Id_sucursal',
        'Titulo_consecion',
        'Punto',
        'Anexo',
        'Siralab',
        'Pozos',
        'Cuerpo_receptor',
        'Uso_agua',
        'Latitud',
        'Longitud',
        'Hora',
        'Minuto',
        'Segundo',
        'Observacion',
        'F_inicio',
        'F_termino',
        'Id_user_c',
        'Id_user_m',
    ];
}
