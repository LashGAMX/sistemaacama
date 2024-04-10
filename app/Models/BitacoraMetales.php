<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BitacoraMetales extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'bitacora_metales';
    protected $primaryKey = 'Id_bitacora';
    public $timestamps = true;

    //created_at timestamp
    //updated_at
    //delted_at
    protected $fillable = [
        'Id_lote', 
        'Titulo',
        'Texto',
        'Rev',
    ];
}
