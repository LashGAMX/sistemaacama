<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlantillaDirectos extends Model
{
    use HasFactory;
    protected $table = 'plantilla_directos';
    protected $primaryKey = 'Id_plantilla';
    public $timestamps = true;

    protected $fillable = [
        'Id_parametro',
        'Titulo',
        'Texto',
        'Rev'
    ];
}

