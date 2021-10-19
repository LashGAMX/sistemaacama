<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class estandares extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'estandares';
    protected $primaryKey = 'Id_std';
    public $timestamps = true;

    protected $fillable = [
        'Id_lote',
        'Id_foprmula',
        'Id_tecnica',
        'STD',
        'Concentracion',
        'ABS1',
        'ABS2',
        'ABS3',
        'Promedio'
    ];
}
