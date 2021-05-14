<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SolicitudPuntos extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'solicitud_puntos';
    protected $primaryKey = 'Id_punto';
    public $timestamps = true;

    protected $fillable = [
        'Id_solicitud',
        'Id_muestra',
    ];
}
