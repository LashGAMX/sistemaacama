<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Formulas extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'formulas';
    protected $primaryKey = 'Id_formula';
    public $timestamps = true;

    protected $fillable = [
        'Id_area',
        'Id_parametro',
        'Id_tecnica',
        'Formula',
        'Formula_sistema'
    ];
}
