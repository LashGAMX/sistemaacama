<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SimbologiaInforme extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'Simbologia_informe';
    protected $primaryKey = 'Id_simbologia_info';
    public $timestamps = true;

    protected $fillable = [
        'Id_laboratorio',
        'Simbologia',
        'Descripcion',
        'Id_user_c',
        'Id_user_m'
    ];
}
