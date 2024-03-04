<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PruebaPatrullajeMapa extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'streemview_patrullaje_mapa';
    protected $primaryKey = 'Id_mapa';
    public $timestamps = true;

    protected $fillable = [
        'Folio',
        'Latitud',
        'Longitud'              
     
    ];
}
