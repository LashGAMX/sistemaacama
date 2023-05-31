<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bitacoras extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'bitacoras';
    protected $primaryKey = 'Id_bitacora';
    public $timestamps = true;

    protected $fillable = [
        'Id_lote',
        'Titulo',
        'Texto',
        'Rev',
    ];
}
