<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VolumenParametros extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'Volumen_parametros';
    protected $primaryKey = 'Id_vol';
    public $timestamps = true;

    protected $fillable = [
        'Id_parametro',
        'Volumen',
        
    ];
}
