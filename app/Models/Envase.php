<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Envase extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'envase';
    protected $primaryKey = 'Id_envase';
    public $timestamps = true;

    protected $fillable = [
        'Nombre',
        'Volumen',
        'Unidad',
        'Id_user_c',
        'Id_user_m'
    ];
}
