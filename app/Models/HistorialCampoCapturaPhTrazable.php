<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HistorialCampoCapturaPhTrazable extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'hist_campoCaptPhTrazable';
    protected $primaryKey = 'Id_hist';
    public $timestamps = true;

    protected $fillable = [
        'Id_ph',
        'Id_solicitud',
        'Id_phTrazable',
        'Lectura1',
        'Lectura2',
        'Lectura3',
        'Estado',
        'Nota',
        'F_creacion',
        'Id_user_c',
        'F_modificacion',
        'Id_user_m',
    ];
}
