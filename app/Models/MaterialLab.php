<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaterialLab extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'materiales_lab';
    protected $primaryKey = 'Id_material';
    public $timestamps = true;

    protected $fillable = [
        'Material'
    ];
}
