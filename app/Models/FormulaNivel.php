<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class FormulaNivel extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'formula_nivel';
    protected $primaryKey = 'Id_formulaNivel';
    public $timestamps = true;

    protected $fillable = [
        'Nombre',
        'Nivel',
        'Descripcion',
        'Resultado',
        'Id_user_c',
        'Id_user_m'
    ];
}
