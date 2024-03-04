<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PruebaPatrullajeGeneral extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'streemview_patrullaje_generales';
    protected $primaryKey = 'Id_general';
    public $timestamps = true;

    protected $fillable = [
        'Folio',
        'Fecha',
        'Calle',                
        'Colonia',
        'NumExt',
        'NumInt',
        'Tipo',
        'Zona',
        'Descripcion'
    ];
}
