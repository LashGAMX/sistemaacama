<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Evidencia extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'evidencias';
    protected $primaryKey = 'Id_evidencia';
    public $timestamps = true;

    protected $fillable = [
        'Id_solicitud',
        'Imagen_1',
        'Imagen_2',
        'Id_user_c',
        'Id_user_m'
    ];
}
