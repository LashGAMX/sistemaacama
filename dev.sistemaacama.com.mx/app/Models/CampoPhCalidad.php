<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CampoPhCalidad extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'campo_phCalidad';
    protected $primaryKey = 'Id_ph';
    public $timestamps = true;

    protected $fillable = [
        'Id_solicitud',
        'Id_phCalidad',
        'Ph',
        'Lectura1',
        'Lectura2',
        'Lectura3',
        'Estado',
        'Promedio',
        'Id_user_c',
        'Id_user_m'
    ];
}
