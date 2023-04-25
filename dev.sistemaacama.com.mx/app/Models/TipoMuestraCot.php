<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoMuestraCot extends Model
{
    use HasFactory,SoftDeletes;
    
    protected $table = 'tipo_muestraCot';
    protected $primaryKey = 'Id_muestraCot';
    public $timestamps = true;

    protected $fillable = [
        'Tipo',
    ];
}
