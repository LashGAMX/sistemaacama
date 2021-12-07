<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaterialesCampo extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'materiales_campo';
    protected $primaryKey = 'Id_material';
    public $timestamps = true;

    protected $fillable = [
        'Material',
        'Unidad',
        'Id_user_c',
        'Id_user_m'
    ];
}
