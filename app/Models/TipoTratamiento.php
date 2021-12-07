<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoTratamiento extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'tipo_tratamiento';
    protected $primaryKey = 'Id_tratamiento';
    public $timestamps = true;

    protected $fillable = [
        'Tratamiento',
        'Id_user_c',
        'Id_user_m'
    ];
}
