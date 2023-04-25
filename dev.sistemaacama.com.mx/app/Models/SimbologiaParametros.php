<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SimbologiaParametros extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'simbologia_parametros';
    protected $primaryKey = 'Id_simbologia';
    public $timestamps = true;

    protected $fillable = [
        'Simbologia',
        'Descripcion',
        'Id_user_c',
        'Id_user_m',
    ];
}
