<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class PHTrazable extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'ph_trazable';
    protected $primaryKey = 'Id_ph';
    public $timestamps = true;

    protected $fillable = [
        'Ph',
        'Marca',
        'Lote',
        'Inicio_caducidad',
        'Fin_caducidad'
    ];
}
