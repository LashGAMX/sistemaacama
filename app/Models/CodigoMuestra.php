<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class CodigoMuestra extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'estado_analisis';
    protected $primaryKey = 'Id_codigo';
    public $timestamps = true;

    protected $fillable = [
        'Id_solicitud',
        'Codigo',
        'Estado_codigo'
    ];

}
