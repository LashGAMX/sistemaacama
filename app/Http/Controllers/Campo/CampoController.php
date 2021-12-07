<?php

namespace App\Http\Controllers\Campo;

use App\Http\Controllers\Controller;
use App\Models\CampoCompuesto;
use App\Models\CampoConCalidad;
use App\Models\CampoConTrazable;
use App\Models\CampoGenerales;
use App\Models\CampoPhCalidad;
use App\Models\CampoPhTrazable;
use App\Models\CampoTempCalidad;
use App\Models\ConductividadCalidad;
use App\Models\ConductividadMuestra;
use App\Models\ConductividadTrazable;
use App\Models\ConTratamiento;
use App\Models\Evidencia;
use App\Models\GastoMuestra;
use App\Models\HistorialCampoAsignar;
use App\Models\MetodoAforo;
use App\Models\PHCalidad;
use App\Models\PhCalidadCampo;
use App\Models\PhMuestra;
use App\Models\PHTrazable;
use App\Models\SeguimientoAnalisis;
use Facade\FlareClient\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\SolicitudesGeneradas;
use App\Models\TemperaturaMuestra;
use App\Models\TermFactorCorreccionTemp;
use App\Models\TermometroCampo;
use App\Models\TipoTratamiento;
use App\Models\Usuario;
use Illuminate\Support\Facades\Auth;

class CampoController extends Controller
{
    //     

    public function asignar()
    {
        $model = DB::table('ViewSolicitud')->where('Id_servicio', 1)->orWhere('Id_servicio', 3)->get();
        $intermediarios = DB::table('ViewIntermediarios')->where('deleted_at', NULL)->get();
        $generadas = SolicitudesGeneradas::all();
        $usuarios = Usuario::all();
        return view('campo.asignarMuestreo', compact('model', 'intermediarios', 'generadas', 'usuarios'));
    }
    public function listaMuestreo()
    {
        $equipo = DB::table('ViewCampoGenerales')->get();
        $model = DB::table('ViewSolicitudGenerada')->where('Id_muestreador', Auth::user()->id)->get();
        return view('campo.listaMuestreo', compact('model','equipo'));
    }
    public function captura($id)
    {        
        $phControlCalidad = PHCalidad::where('Ph_calidad', 7)->first();

        $phTrazable = PHTrazable::all();
        $phCalidad = PHCalidad::all();
        $termometros = TermometroCampo::all();
        $conTrazable = ConductividadTrazable::all();
        $conCalidad = ConductividadCalidad::all();
        $aforo = MetodoAforo::all();
        $conTratamiento = ConTratamiento::all();
        $tipo = TipoTratamiento::all();

        $model = DB::table('ViewSolicitudGenerada')->where('Id_solicitud', $id)->first();
        $general = CampoGenerales::where('Id_solicitud', $model->Id_solicitud)->first();
        $frecuencia = DB::table('frecuencia001')->where('Id_frecuencia', $model->Id_muestreo)->first();
        $phCampoTrazable = CampoPhTrazable::where('Id_solicitud', $model->Id_solicitud)->get();
        $phCampoCalidad = CampoPhCalidad::where('Id_solicitud', $model->Id_solicitud)->get();


        //$phCampoCalidadMuestra = CampoPhCalidad::where('Id_solicitud', $model->Id_solicitud)->where('Id_phCalidad', $phControlCalidad->Id_ph)->first();
        // $conCampoTrazable = CampoConTrazable::where('Id_solicitud',$model->Id_solicitud)->first();
        // $conCampoCalidad = CampoConCalidad::where('Id_solicitud',$model->Id_solicitud)->first();
        // $frecuencia = DB::table('frecuencia001')->where('')
        //var_dump($phCampoTrazable);
        $data = array(
            'model' => $model,
            'general' => $general,
            'frecuencia' => $frecuencia,
            'termometros' => $termometros,
            'phTrazable' => $phTrazable,
            'phCalidad' => $phCalidad,
            'conTrazable' => $conTrazable,
            'conCalidad' => $conCalidad,
            'aforo' => $aforo,
            'conTratamiento' => $conTratamiento,
            'tipo' => $tipo,
            'phCampoTrazable' => $phCampoTrazable,
            'phCampoCalidad' => $phCampoCalidad,
            'phControlCalidad' => $phControlCalidad,
            //'phCampoCalidadMuestra' => $phCampoCalidadMuestra
        );
        return view('campo.captura', $data);
    }
    public function setDataGeneral(Request $request)
    {
        $model = CampoGenerales::where('Id_solicitud', $request->idSolicitud)->first();

        $model->Captura = "Sistema";
        $model->Id_equipo = $request->equipo;
        $model->Temperatura_a = $request->temp1;
        $model->Temperatura_b = $request->temp2;
        $model->Latitud = $request->latitud;
        $model->Longitud = $request->longitud;
        $model->Altitud = $request->altitud;
        $model->Pendiente = $request->pendiente;
        $model->Criterio = $request->criterio;
        $model->Supervisor = $request->supervisor;
        $model->save();

        //Ph trazable
        $phTrazableModel = CampoPhTrazable::where('Id_solicitud', $request->idSolicitud)->get();
        if ($phTrazableModel->count()) {
            $phTrazable = CampoPhTrazable::find($phTrazableModel[0]->Id_ph);
            $phTrazable->Id_solicitud = $request->idSolicitud;
            $phTrazable->Id_phTrazable = $request->phTrazable1;
            $phTrazable->Lectura1 = $request->phTl11;
            $phTrazable->Lectura2 = $request->phT21;
            $phTrazable->Lectura3 = $request->phTl31;
            $phTrazable->Estado = $request->phTEstado1;
            $phTrazable->save();

            $phTrazable = CampoPhTrazable::find($phTrazableModel[1]->Id_ph);
            $phTrazable->Id_solicitud = $request->idSolicitud;
            $phTrazable->Id_phTrazable = $request->phTrazable2;
            $phTrazable->Lectura1 = $request->phTl12;
            $phTrazable->Lectura2 = $request->phT22;
            $phTrazable->Lectura3 = $request->phTl32;
            $phTrazable->Estado = $request->phTEstado2;
            $phTrazable->save();
        } else {
            CampoPhTrazable::create([
                'Id_solicitud' => $request->idSolicitud,
                'Id_phTrazable' => $request->phTrazable1,
                'Lectura1' => $request->phTl11,
                'Lectura2' => $request->phT21,
                'Lectura3' => $request->phTl31,
                'Estado' => $request->phTEstado1,
            ]);
            CampoPhTrazable::create([
                'Id_solicitud' => $request->idSolicitud,
                'Id_phTrazable' => $request->phTrazable2,
                'Lectura1' => $request->phTl12,
                'Lectura2' => $request->phT22,
                'Lectura3' => $request->phTl32,
                'Estado' => $request->phTEstado2,
            ]);
        }
        //PhCalidad

        $phCalidadMode = CampoPhCalidad::where('Id_solicitud', $request->idSolicitud)->get();
        if ($phCalidadMode->count()) {
            $phCalidad = CampoPhCalidad::find($phCalidadMode[0]->Id_ph);
            $phCalidad->Id_solicitud = $request->idSolicitud;
            $phCalidad->Id_phCalidad = $request->phTrazable1;
            $phCalidad->Lectura1 = $request->phTl11;
            $phCalidad->Lectura2 = $request->phT21;
            $phCalidad->Lectura3 = $request->phTl31;
            $phCalidad->Estado = $request->phTEstado1;
            $phCalidad->Promedio = $request->phCPromedio1;
            $phCalidad->save();

            $phCalidad = CampoPhCalidad::find($phCalidadMode[1]->Id_ph);
            $phCalidad->Id_solicitud = $request->idSolicitud;
            $phCalidad->Id_phCalidad = $request->phTrazable2;
            $phCalidad->Lectura1 = $request->phTl12;
            $phCalidad->Lectura2 = $request->phT22;
            $phCalidad->Lectura3 = $request->phTl32;
            $phCalidad->Estado = $request->phTEstado2;
            $phCalidad->Promedio = $request->phCPromedio2;
            $phCalidad->save();
        } else {
            CampoPhCalidad::create([
                'Id_solicitud' => $request->idSolicitud,
                'Id_phCalidad' => $request->phTrazable1,
                'Lectura1' => $request->phTl11,
                'Lectura2' => $request->phT21,
                'Lectura3' => $request->phTl31,
                'Estado' => $request->phTEstado1,
                'Promedio' => $request->phCPromedio1,
            ]);
            CampoPhCalidad::create([
                'Id_solicitud' => $request->idSolicitud,
                'Id_phCalidad' => $request->phTrazable2,
                'Lectura1' => $request->phTl12,
                'Lectura2' => $request->phT22,
                'Lectura3' => $request->phTl32,
                'Estado' => $request->phTEstado2,
                'Promedio' => $request->phCPromedio2,
            ]);
        }

        //ConTrazable
        $conTrazableModel = CampoConTrazable::where('Id_solicitud', $request->idSolicitud)->get();
        if ($conTrazableModel->count()) {
            $conTrazable = CampoConTrazable::find($conTrazableModel[0]->Id_conductividad);
            $conTrazable->Id_solicitud = $request->idSolicitud;
            $conTrazable->Id_conTrazable = $request->conTrazable;
            $conTrazable->Lectura1 = $request->conT1;
            $conTrazable->Lectura2 = $request->conT2;
            $conTrazable->Lectura3 = $request->conT3;
            $conTrazable->Estado = $request->conTEstado;
            $conTrazable->save();
        } else {
            CampoConTrazable::create([
                'Id_solicitud' => $request->idSolicitud,
                'Id_conTrazable' => $request->conTrazable,
                'Lectura1' => $request->conT1,
                'Lectura2' => $request->conT2,
                'Lectura3' => $request->conT3,
                'Estado' => $request->conTEstado,
            ]);
        }

        //Conductividad control calidad
            $conCalidadModel = CampoConCalidad::where('Id_solicitud',$request->idSolicitud)->get();
            if($conCalidadModel->count())
            {
                $conCalidad = CampoConCalidad::find($conCalidadModel[0]->Id_conductividad);
                $conCalidad->Id_solicitud = $request->idSolicitud;
                $conCalidad->Id_conCalidad = $request->conCalidad;
                $conCalidad->Lectura1 = $request->conCl1;
                $conCalidad->Lectura2 = $request->conCl2;
                $conCalidad->Lectura3 = $request->conCl3;
                $conCalidad->Estado = $request->conCEstado;
                $conCalidad->Promedio = $request->conCPromedio;
                $conCalidad->save();

            }else{
             CampoConCalidad::create([
                    'Id_solicitud' => $request->idSolicitud,
                    'Id_conCalidad' => $request->conCalidad,
                    'Lectura1' => $request->conCl1,
                    'Lectura2' => $request->conCl2,
                    'Lectura3' => $request->conCl3,
                    'Estado' => $request->conCEstado,
                    'Promedio' => $request->conCPromedio,
                ]);
            }

        $seguimiento = SeguimientoAnalisis::where('Id_servicio',$request->idSolicitud)->first();
        $seguimiento->Muestreo = 1;
        $seguimiento->save();

        $data = array('sw' => true, 'model' => $model);
        return response()->json($data);
    }

    public function setDataMuestreo(Request $request)
    {
        $phModel = PhMuestra::where('Id_solicitud', $request->idSolicitud)->get();

        if ($phModel->count()) {
            for ($i = 0; $i < $request->numTomas; $i++) {
                
                $ph = PhMuestra::find($phModel[$i]->Id_ph);
                $ph->Id_solicitud = $request->idSolicitud;
                $ph->Materia = $request->ph[$i][0];
                $ph->Olor = $request->ph[$i][1];
                $ph->Color = $request->ph[$i][2];
                $ph->Ph1 = $request->ph[$i][3];
                $ph->Ph2 = $request->ph[$i][4];
                $ph->Ph3 = $request->ph[$i][5];
                $ph->Promedio = $request->ph[$i][6];
                $ph->Fecha = $request->ph[$i][7];
                $ph->save();
            }
        } else {
            for ($i = 0; $i < $request->numTomas; $i++) {
             
                PhMuestra::create([
                    'Id_solicitud' => $request->idSolicitud,
                    'Materia' => $request->ph[$i][0],
                    'Olor' => $request->ph[$i][1],
                    'Color' => $request->ph[$i][2],
                    'Ph1' => $request->ph[$i][3],
                    'Ph2' => $request->ph[$i][4],
                    'Ph3' => $request->ph[$i][5],
                    'Promedio' => $request->ph[$i][6],
                    'Fecha' => $request->ph[$i][7],
                ]);
            }
        }


        $tempMuestra = TemperaturaMuestra::where('Id_solicitud', $request->idSolicitud)->get();

        if ($tempMuestra->count()) {
            for ($i = 0; $i < $request->numTomas; $i++) {
             
                $temp = TemperaturaMuestra::find($tempMuestra[$i]->Id_temperatura);
                $temp->Id_solicitud = $request->idSolicitud;
                $temp->Temperatura1 = $request->temperatura[$i][0];
                $temp->Temperatura2 = $request->temperatura[$i][1];
                $temp->Temperatura3 = $request->temperatura[$i][2];
                $temp->Promedio = $request->temperatura[$i][3];
                $temp->save();
            }
        } else {
            for ($i = 0; $i < $request->numTomas; $i++) {
          
                TemperaturaMuestra::create([
                    'Id_solicitud' => $request->idSolicitud,
                    'Temperatura1' => $request->temperatura[$i][0],
                    'Temperatura2' => $request->temperatura[$i][1],
                    'Temperatura3' => $request->temperatura[$i][2],
                    'Promedio' => $request->temperatura[$i][3],
                ]);
            }
        }


        $phCalidadMuestra = PhCalidadCampo::where('Id_solicitud', $request->idSolicitud)->get();

        if ($phCalidadMuestra->count()) {
            for ($i = 0; $i < $request->numTomas; $i++) {            
                $phCalidad = PhCalidadCampo::find($phCalidadMuestra[$i]->Id_phCalidad);
                $phCalidad->Id_solicitud = $request->idSolicitud;
                $phCalidad->Ph_calidad = $request->phCalidad[$i][0];
                $phCalidad->Lectura1 = $request->phCalidad[$i][1];
                $phCalidad->Lectura2 = $request->phCalidad[$i][2];
                $phCalidad->Lectura3 = $request->phCalidad[$i][3];
                $phCalidad->Estado = $request->phCalidad[$i][4];
                $phCalidad->Promedio = $request->phCalidad[$i][5];
                $phCalidad->save();
            }
        } else {
            for ($i = 0; $i < $request->numTomas; $i++) {
               
                PhCalidadCampo::create([
                    'Id_solicitud' => $request->idSolicitud,
                    'Ph_calidad' => $request->phCalidad[$i][0],
                    'Lectura1' => $request->phCalidad[$i][1],
                    'Lectura2' => $request->phCalidad[$i][2],
                    'Lectura3' => $request->phCalidad[$i][3],
                    'Estado' => $request->phCalidad[$i][4],
                    'Promedio' => $request->phCalidad[$i][5]
                ]);
            }
        }


        $conModel = ConductividadMuestra::where('Id_solicitud', $request->idSolicitud)->get();

        if ($conModel->count()) {
            for ($i = 0; $i < $request->numTomas; $i++) {
           
                $conduc = ConductividadMuestra::find($conModel[$i]->Id_conductividad);
                $conduc->Id_solicitud = $request->idSolicitud;
                $conduc->Conductividad1 = $request->conductividad[$i][0];
                $conduc->Conductividad2 = $request->conductividad[$i][1];
                $conduc->Conductividad3 = $request->conductividad[$i][2];
                $conduc->Promedio = $request->conductividad[$i][3];
                $conduc->save();
            }
        } else {
            for ($i = 0; $i < $request->numTomas; $i++) {
               
                ConductividadMuestra::create([
                    'Id_solicitud' => $request->idSolicitud,
                    'Conductividad1' => $request->conductividad[$i][0],
                    'Conductividad2' => $request->conductividad[$i][1],
                    'Conductividad3' => $request->conductividad[$i][2],
                    'Promedio' => $request->conductividad[$i][3],
                ]);
            }
        }

        $gastoModel = GastoMuestra::where('Id_solicitud', $request->idSolicitud)->get();

        if ($gastoModel->count()) {
            for ($i = 0; $i < $request->numTomas; $i++) {
                
                $gasto = GastoMuestra::find($gastoModel[$i]->Id_gasto);
                $gasto->Id_solicitud = $request->idSolicitud;
                $gasto->Gasto1 = $request->gasto[$i][0];
                $gasto->Gasto2 = $request->gasto[$i][1];
                $gasto->Gasto3 = $request->gasto[$i][2];
                $gasto->Promedio = $request->gasto[$i][3];
                $gasto->save();
            }
        } else {
            for ($i = 0; $i < $request->numTomas; $i++) {
                
                GastoMuestra::create([
                    'Id_solicitud' => $request->idSolicitud,
                    'Gasto1' => $request->gasto[$i][0],
                    'Gasto2' => $request->gasto[$i][1],
                    'Gasto3' => $request->gasto[$i][2],
                    'Promedio' => $request->gasto[$i][3],
                ]);
            }
        }

        $data = array('sw' => true);
        return response()->json($data);
    }

    public function setDataCompuesto(Request $request){
        $campoCompModel = CampoCompuesto::where('Id_solicitud', $request->idSolicitud)->get();

        if($campoCompModel->count()){
            $campoComp = CampoCompuesto::find($request->idSolicitud);
            
            $campoComp->Metodo_aforo = $request->aforoCompuesto;
            $campoComp->Con_tratamiento = $request->conTratamientoCompuesto;
            $campoComp->Tipo_tratamiento = $request->tipoTratamientoCompuesto;
            $campoComp->Proce_muestreo = $request->procedimientoCompuesto;
            $campoComp->Observaciones = $request->obsCompuesto;
            $campoComp->Ph_muestraComp = $request->phMuestraCompuesto;
            $campoComp->Temp_muestraComp = $request->valTempCompuesto;
            $campoComp->Volumen_calculado = $request->volCalculadoComp;

            $campoComp->save();
        }else{
            CampoCompuesto::create([
                'Id_solicitud' => $request->idSolicitud,
                'Metodo_aforo' => $request->aforoCompuesto,
                'Con_tratamiento' => $request->conTratamientoCompuesto,
                'Tipo_tratamiento' => $request->tipoTratamientoCompuesto,
                'Proce_muestreo' => $request->procedimientoCompuesto,
                'Observaciones' => $request->obsCompuesto,
                'Ph_muestraComp' => $request->phMuestraCompuesto,
                'Temp_muestraComp' => $request->valTempCompuesto,
                'Volumen_calculado' => $request->volCalculadoComp
            ]);
        }

        $data = array('sw' => true);
        return response()->json($data);
    }

    public $nota;
    public $alert;
    public $idSol;

    public function generar(Request $request) //Generar solicitud 
    {                
        $sol = SolicitudesGeneradas::where('Id_solicitud', $request->idSolicitud)->get();
        
        if ($sol->count()) {                    //ACTUALIZAR
            $model = SolicitudesGeneradas::where('Id_solicitud', $request->idSolicitud)->get();
            $this->idSol = $request->idSolicitud;
            $this->nota = "Registro modificado";
            $this->historial();
            $this->alert = true;                         
        } else {                                //CREAR
            $this->idSol = $request->idSolicitud;
            $this->nota = "Creación de registro";
            $this->historial();
            $this->alert = true;
            
            $solGen = SolicitudesGeneradas::create([
                'Id_solicitud' => $request->idSolicitud,
                'Folio' => $request->folio,
                'Id_user_c' => Auth::user()->id,
                'Captura' => "Sin captura"
            ]);
            CampoGenerales::create([
                'Id_solicitud' => $request->idSolicitud,
            ]);

            $model = SolicitudesGeneradas::where('Id_solicitud', $solGen->Id_solicitud)->get();                        
        }                

        return response()->json(
            compact('model')
        );
    }

    public function historial()
    {
        $idUser = Auth::user()->id;

        $model = DB::table('solicitudes_generadas')->where('Id_solicitud', $this->idSol)->first();
        HistorialCampoAsignar::create([
            'Id_solicitud' => $model->Id_solicitud,
            'Id_muestreador' => $model->Id_muestreador,
            'Nota' => $this->nota,
            'F_creacion' => $model->created_at,
            'Id_user_c' => $model->Id_user_c,
            'Id_user_m' => $idUser,
            'F_modificacion' => $model->updated_at,
            'Id_user_m' => $idUser,
            'Punto_muestreo' => $model->Punto_muestreo,
            'Captura' => $model->Captura
        ]);
    }

    public function getFolio(Request $request)
    {
        $idUser = $request->idUser;
        $inge = Usuario::where('id', $idUser)->first();

        $folio = $request->folioAsignar;
        $nombres = $inge->name;
        $muestreador = $inge->id;

        $update = SolicitudesGeneradas::where('Folio', $folio)
            ->update([
                'Nombres' => $nombres,
                'Id_muestreador' => $muestreador,
            ]);

        return response()->json(
            compact('update'),
        );
    }
    public function getFactorCorreccion(Request $request)
    {
        $model = TermFactorCorreccionTemp::where('Id_termometro', $request->idFactor)->get();
        return response()->json(compact('model'));
    }

    public function getFactorAplicado(Request $request)
    {
        $model = TermFactorCorreccionTemp::where('Id_termometro', $request->idFactor)->get();
        return response()->json(compact('model'));
    } 
 
    public function getPhTrazable(Request $request)
    {
        $model = PHTrazable::where('Id_ph', $request->idPh)->first();
        return response()->json(compact('model'));
    }
    public function getPhCalidad(Request $request)
    {                
        $model = PHCalidad::where('Id_ph', $request->idPh)->first();
        return response()->json(compact('model'));
    }
    public function getConTrazable(Request $request)
    {
        $model = ConductividadTrazable::where('Id_conductividad', $request->idCon)->first();
        return response()->json(compact('model'));
    }
    public function getConCalidad(Request $request)
    {
        $model = ConductividadCalidad::where('Id_conductividad', $request->idCon)->first();
        return response()->json(compact('model'));
    }
    public function hojaCampo($id)
    { 
      
        $model = DB::table('ViewSolicitud')->where('Id_solicitud',$id)->first();
        $punto = DB::table('ViewPuntoGenSol')->where('Id_solicitud',$id)->first();
        $solGen = DB::table('ViewSolicitudGenerada')->where('Id_solicitud',$id)->first();

        $phMuestra = PhMuestra::where('Id_solicitud',$id)->get();
        $gastoMuestra = GastoMuestra::where('Id_solicitud',$id)->get();
        $tempMuestra = TemperaturaMuestra::where('Id_solicitud',$id)->get();
        $conMuestra = ConductividadMuestra::where('Id_solicitud',$id)->get();
        $muestreador = Usuario::where('id',$solGen->Id_muestreador)->first();

        $mpdf = new \Mpdf\Mpdf([
            'format' => 'letter',
            'margin_left' => 20,
            'margin_right' => 20,
            'margin_top' => 30,
            'margin_bottom' => 18
        ]);
        
        $mpdf->SetWatermarkImage(
            asset('storage/HojaMembretada.png'),
            1,
            array(215, 280),
            array(0, 0),
        );
        $mpdf->showWatermarkImage = true;
        $html = view('exports.campo.hojaCampo',compact('model','punto','phMuestra','gastoMuestra','tempMuestra','conMuestra','muestreador'));
        $mpdf->CSSselectMedia = 'mpdf';
        $mpdf->WriteHTML($html);
        $mpdf->Output();
    }  
    public function bitacoraCampo($id)
    {
        $model = DB::table('ViewSolicitud')->where('Id_solicitud',$id)->first();
        $punto = DB::table('ViewPuntoGenSol')->where('Id_solicitud',$id)->first();
        $solGen = DB::table('ViewSolicitudGenerada')->where('Id_solicitud',$id)->first();

        $campoGen = DB::table('ViewCampoGenerales')->where('Id_solicitud',$id)->first();
        $phMuestra = PhMuestra::where('Id_solicitud',$id)->get();
        $gastoMuestra = GastoMuestra::where('Id_solicitud',$id)->get();
        $tempMuestra = TemperaturaMuestra::where('Id_solicitud',$id)->get();
        $conMuestra = ConductividadMuestra::where('Id_solicitud',$id)->get();
        $muestreador = Usuario::where('id',$solGen->Id_muestreador)->first();

        $phTrazable = CampoPhTrazable::where('Id_solicitud',$id)->get();
        $campoConTrazable = CampoConTrazable::wherE('Id_solicitud',$id)->get();

        $mpdf = new \Mpdf\Mpdf([
            'format' => 'letter',
            'margin_left' => 20,
            'margin_right' => 20,
            'margin_top' => 30,
            'margin_bottom' => 18
        ]);
        
        $mpdf->SetWatermarkImage(
            asset('storage/HojaMembretada.png'),
            1,
            array(215, 280),
            array(0, 0), 
        );
        $mpdf->showWatermarkImage = true;
        $html = view('exports.campo.bitacoraCampo',compact('model','punto','phMuestra','gastoMuestra','campoGen','tempMuestra','conMuestra','muestreador','phTrazable','campoConTrazable'));
        $mpdf->CSSselectMedia = 'mpdf';
        $mpdf->WriteHTML($html);
        $mpdf->Output();
    }
}
