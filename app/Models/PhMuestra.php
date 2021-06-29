<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PhMuestra extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'ph_muestra';
    protected $primaryKey = 'Id_ph';
    public $timestamps = true;

    protected $fillable = [
        'Id_solicitud',
        'Materia',
        'Olor',
        'Color',
        'Ph1',
        'Ph2',
        'Ph3',
        'Promedio',
        'Fecha'
    ];
}
