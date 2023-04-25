<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoFormulaArea extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'tipo_formula_areas';
    protected $primaryKey = 'Id_tipo';
    public $timestamps = true;

    protected $fillable = [
        'Id_formula',
        'Id_area',        
        'Id_user_c',
        'Id_user_m'
    ];
}
