<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoFormula extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'tipo_formulas';
    protected $primaryKey = 'Id_tipo_formula';
    public $timestamps = true;

    protected $fillable = [
        'Tipo_formula',
        // 'Id_user_c',
        // 'Id_user_m',
    ];
}
