<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DqoDetalle extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'dqo_detalle';
    protected $primaryKey = 'Id_detalle';
    public $timestamps = true;

    protected $fillable = [
        'Id_lote',
        'Cant_dilucion',
        'De',
        'A',
        'Pag',
        'N',
        'Dilucion'
    ];
}
