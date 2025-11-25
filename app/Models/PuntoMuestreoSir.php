<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PuntoMuestreoSir extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'puntos_muestreo';
    protected $primaryKey = 'Id_punto';
    public $timestamps = true;

    protected $fillable = [
        'Id_sucursal',
        'Titulo_consecion',
        'Punto',
        'Anexo',
        'Siralab',
        'Pozos',
        'Cuerpo_receptor',
        'Uso_agua',
        'Categoria',
        'Latitud',
        'GradosLat',
        'MinutosLat',
        'SegundosLat',
        'Longitud',
        'GradosLong',
        'MinutosLong',
        'SegundosLong',
        'Hora',
        'Minuto',
        'Segundo',
        'Observacion',
        'F_inicio',
        'F_termino',
        'Id_user_c',
        'Id_user_m',
    ];
   public function cuerpoReceptor()
{
    return $this->belongsTo(TipoCuerpo::class, 'Cuerpo_receptor', 'Id_tipo');
}

public function usoAgua()
{
    return $this->belongsTo(DetallesTipoCuerpo::class, 'Uso_agua', 'Id_detalle');
}
public  function titulo(){
        return $this->belongsTo(TituloConsecionSir::class, 'Titulo_consecion', 'Id_titulo');
}



}
