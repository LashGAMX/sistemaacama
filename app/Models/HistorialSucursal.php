<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HistorialSucursal extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'hist_laboratorioSuc';
    protected $primaryKey = 'Id_hist';
    public $timestamps = true;

    protected $fillable = [
        'Id_sucursal',
        'Sucursal',
        'Nota',
        'F_creacion',
        'Id_user_c',
        'F_modificacion',
        'Id_user_m',
    ];
}
