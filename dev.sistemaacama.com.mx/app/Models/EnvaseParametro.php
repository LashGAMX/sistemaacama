<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EnvaseParametro extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'envase_parametro';
    protected $primaryKey = 'Id_env';
    public $timestamps = true;

    protected $fillable = [
        'Id_analisis',
        'Id_parametro',
        'Id_envase',
        'Id_preservador'        
    ];
}
