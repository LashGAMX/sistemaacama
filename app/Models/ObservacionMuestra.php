<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ObservacionMuestra extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'observacion_muestra';
    protected $primaryKey = 'Id_observacion';
    public $timestamps = true;

    protected $fillable = [
        'Id_analisis',
        'Id_area',
        'Ph',
        'Solido',
        'Olor',
        'Color',
        'Observaciones'
    ];
}
