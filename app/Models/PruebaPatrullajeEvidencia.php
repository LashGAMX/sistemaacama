<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PruebaPatrullajeEvidencia extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'streemview_patrullaje_evidencia';
    protected $primaryKey = 'Id_evidencia';
    public $timestamps = true;

    protected $fillable = [
        'Folio',
        'Imagen1',
        'Imagen2',
        'Imagen3',
        'Captura'              
     
    ];
}
