<?php

namespace App\Models;

use App\Http\Livewire\AnalisisQ\Parametros;
use App\Models\Parametro; 

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Users;


class CodigoParametros extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'codigo_parametro';
    protected $primaryKey = 'Id_codigo';
    public $timestamps = true;

    protected $fillable = [
        'Id_solicitud', 
        'Id_muestraAli',
        'Id_parametro', 
        'Codigo',       
        'Num_muestra',
        'Resultado',
        'Resultado2',
        'Resultado_aux',
        'Resultado_aux2',
        'Resultado_aux3',
        'Resultado_aux4',
        'Incertidumbre',
        'Ph_muestra',
        'Id_lote',
        'Asignado',
        'Analizo',
        'Reporte',
        'Mensual',
        'Id_titulo_parametro',
        'Cadena',
        'Cancelado',
        'Liberado',
        'Auditoria',
        'Id_user_c',
        'Id_user_m',
        'Historial',
    ];
    public function parametro()
    {
        return $this->belongsTo(Parametro::class, 'Id_parametro', 'Id_parametro');
    }
     public function user()
    {
      return $this->belongsTo(User::class,"Analizo","id");
    }
    public function solicitud()
    {
        return $this->belongsTo(Solicitud::class, 'Id_solicitud', 'Id_solicitud');
    }

}
