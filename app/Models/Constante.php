<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Constante extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'constantes';
    protected $primaryKey = 'Id_constante';
    public $timestamps = true;

    protected $fillable = [
        'Constante',
        'Valor',
        'Descripcion',
    ];

}
