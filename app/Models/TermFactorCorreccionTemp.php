<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TermFactorCorreccionTemp extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'term_factCorrTemp';
    protected $primaryKey = 'Id_factor';
    public $timestamps = true;

    protected $fillable = [
        'Id_termometro',
        'De_c',
        'A_c',
        'Factor',
        'Factor_aplciado'
    ];
}
