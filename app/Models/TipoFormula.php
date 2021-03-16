<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoFormula extends Model
{
    use HasFactory;
    protected $table = 'tipo_formulas';
    protected $primaryKey = 'Id_tipo_formula';
    public $timestamps = true;

    protected $fillable = [
        'Tipo_formula',
        // 'Id_user_c',
        // 'Id_user_m',
    ];
}
