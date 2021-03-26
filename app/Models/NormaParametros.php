<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NormaParametros extends Model
{
    use HasFactory;
    protected $table = 'norma_parametros';
    protected $primaryKey = 'Id_norma_param';
    // public $timestamps = true;

    protected $fillable = [
        'Id_norma',
        'Id_parametro'
    ];
}
