<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConfiguracionLab extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'configuracion_lab';
    protected $primaryKey = 'Id_config';
    public $timestamps = true;

    protected $fillable = [
        'Configuracion',
        'Descripcion',
        'Id_user_c',
        'Id_user_m'
    ];
}
