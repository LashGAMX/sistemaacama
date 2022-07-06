<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ParametroNorma extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'parametros_normas';
    protected $primaryKey = 'Id_detalle';
    public $timestamps = true;

    protected $fillable = [
        'Id_detalle',
        'Id_norma',
        'Id_parametro'
    ];
}
