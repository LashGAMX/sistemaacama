<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RfcSucursal extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'rfc_sucursal';
    protected $primaryKey = 'Id_rfc';
    public $timestamps = true;

    protected $fillable = [
        'Id_sucursal',
        'RFC',
        'Id_user_c',
        'Id_user_m'
    ];
}
