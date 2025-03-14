<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecepcionAlimentos extends Model
{
    use HasFactory;

    protected $table = 'recepcion_laboratorioali';
    protected $primaryKey = 'Id_rep';
    public $timestamps = true;

    protected $fillable = [
        'Folio',
        'Fecha',
        'Nombre',
        'Id_user',
        'Hora_recepcion',
        'Recibio',
        'Fecha_resguardo',
        'Resguardo',
        'Fecha_desecho',
        'Analista_desecho',
        'Lugar_desecho',
    ];
}
