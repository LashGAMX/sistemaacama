<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GeneradorHidrurosMet extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'generador_hidruros_met';
    protected $primaryKey = 'Id_gen';
    public $timestamps = true;

    protected $fillable = [
        'Id_lote',
        'Generador_hidruros',
        'Id_user_c',
        'Id_user_m'
    ];
}
