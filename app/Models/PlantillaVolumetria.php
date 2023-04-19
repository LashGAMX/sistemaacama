<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlantillaVolumetria extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'plantilla_volumetria';
    protected $primaryKey = 'Id_plantilla';
    public $timestamps = true;

    protected $fillable = [
        'Id_parametro',
        'Titulo',
        'Texto',
        'Rev'
    ];
}
