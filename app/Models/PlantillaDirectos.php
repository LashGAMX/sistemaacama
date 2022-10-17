<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlantillaDirectos extends Model
{
    use HasFactory;
    protected $table = 'plantilla_b';
    protected $primaryKey = 'Id_plantilla';
    public $timestamps = true;

    protected $fillable = [
        'Id_parametro',
        'Texto',
    ];
}

