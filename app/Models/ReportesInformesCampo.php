<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReportesInformesCampo extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'reportes_informes_campo';
    protected $primaryKey = 'Id_reporte';
    public $timestamps = true;

    protected $fillable = [
        'Encabezado',
        'Nota',
        'Id_analizo',
        'Id_reviso',
        'Fecha_inicio',
        'Fecha_fin',
        'Num_rev',
        'Clave',
    ];
}
