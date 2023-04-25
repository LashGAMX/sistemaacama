<?php

namespace App\Models\Api\Encuesta;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Encuesta extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'encuesta';
    protected $primaryKey = 'Id_encuesta';
    public $timestamps = true;

    protected $fillable = [
        'Url',
        'Descripcion',
        'Id_user_c',
        'Id_user_m'
    ];
}
