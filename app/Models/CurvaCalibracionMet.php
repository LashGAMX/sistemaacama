<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CurvaCalibracionMet extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'curva_calibracion_met';
    protected $primaryKey = 'Id_curCal';
    public $timestamps = true;

    protected $fillable = [
        'Id_lote',
        'Bitacora_curCal',
        'Folio_curCal'          
    ];
}
