<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BitacoraMb extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'bitacora_mb';
    protected $primaryKey = 'Id_bitacora';
    public $timestamps = true;

    protected $fillable = [
        'Id_lote',
        'Texto',
    ];
}
