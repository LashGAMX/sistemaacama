<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SolicitudParametro extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'solicitud_parametros';
    protected $primaryKey = 'Id_parametro';
    public $timestamps = true;

    protected $fillable = [
        'Id_solicitud',
        'Id_subnorma',
        'Extra'
    ];
}
