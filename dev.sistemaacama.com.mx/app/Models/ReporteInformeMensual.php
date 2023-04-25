<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReporteInformeMensual extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'reportes_informes_mensual';
    protected $primaryKey = 'Id_reporte';
    public $timestamps = true;

    protected $fillable = [
        'Encabezado',
        'Nota',
        'Id_reviso',
        'Id_autorizo',
        'Responsable',
        'Num_rev',
    ];
}
