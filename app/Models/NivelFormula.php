<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NivelFormula extends Model
{
    use HasFactory,SoftDeletes;
    
    protected $table = 'niveles_formula';
    protected $primaryKey = 'Id_nivel';
    public $timestamps = true;

    protected $fillable = [
        'Nivel',
        'Descripcion',
        'Id_user_c',
        'Id_user_m'
    ];
    
}
