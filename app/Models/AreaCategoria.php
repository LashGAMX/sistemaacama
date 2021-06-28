<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AreaCategoria extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'areas_categoria';
    protected $primaryKey = 'Id_categoria';
    public $timestamps = true;

    protected $fillable = [
        'Categoria'
    ];
}
