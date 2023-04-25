<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReporteCadena extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'reportes_cadena';
    protected $primaryKey = 'Id_reporte';
    public $timestamps = true;

    protected $fillable = [
        'Encabezado',
        'Titulo1',
        'Titulo2',
        'Titulo3',
        'Seccion1',
        'Seccion2',
        'Responsable',
        'Num_rev',
    ];
}
