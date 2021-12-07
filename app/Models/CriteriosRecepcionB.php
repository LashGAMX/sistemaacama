<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CriteriosRecepcionB extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'criterios_recepcion_b';
    protected $primaryKey = 'Id_criterios_recepcion_a';
    public $timestamps = true;

    protected $fillable = [
        'N_muestras',
        'Motivos',
        'Id_user_c',
        'Id_user_m'
    ];
}
