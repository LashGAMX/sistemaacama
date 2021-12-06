<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CampoPhTrazable extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'campo_phTrazable';
    protected $primaryKey = 'Id_ph';
    public $timestamps = true;

    protected $fillable = [
        'Id_solicitud',
        'Id_phTrazable',
        'Lectura1',
        'Lectura2',
        'Lectura3',
        'Estado',
        'Id_user_c',
        'Id_user_m'
    ];
}
