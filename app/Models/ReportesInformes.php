<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class ReportesInformes extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'reportes_informes';
    protected $primaryKey = 'Id_reporte';
    public $timestamps = true;

    protected $fillable = [
        'Encabezado',
        'Nota',
        'Id_analizo',
        'Id_reviso',
        'Num_rev',
    ];
}
