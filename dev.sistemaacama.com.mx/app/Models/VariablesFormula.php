<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class VariablesFormula extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'variables_formula';
    protected $primaryKey = 'Id_variable';
    public $timestamps = true;

    protected $fillable = [
        'Id_formula',
        'Id_parametro',
        'Variable',
        'Id_tipo',
        'Valor',
        'Deci',
        'Id_user_c',
        'Id_user_m'
    ];
}
