<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Analisis extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'analisis';
    protected $primaryKey = 'Id_analisis';
    public $timestamps = true;

    protected $fillable = [
        'Analisis',
        'Id_parametro',
        'Id_envase',
        'Perservacion'
    ];
}
