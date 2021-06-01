<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Localidad extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'localidades';
    protected $primaryKey = 'Id_localidad';
    public $timestamps = true;
    protected $fillable = [
        'Id_estado',
        'Nombre',
    ];
}
  