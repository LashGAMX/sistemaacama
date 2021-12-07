<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Promedio extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'promedios';
    protected $primaryKey = 'Id_promedio';
    public $timestamps = true;

    protected $fillable = [
        'Descripcion',                
        'Id_user_c',
        'Id_user_m'
    ];
}
