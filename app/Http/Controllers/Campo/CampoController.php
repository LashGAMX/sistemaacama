<?php

namespace App\Http\Controllers\Campo;

use App\Http\Controllers\Controller;
use App\Models\AreaAnalisis;
use App\Models\AreaLab;
use App\Models\CampoCompuesto;
use App\Models\CampoConCalidad;
use App\Models\CampoConTrazable;
use App\Models\CampoGenerales;
use App\Models\CampoPhCalidad;
use App\Models\CampoPhTrazable;
use App\Models\CampoTempCalidad;
use App\Models\Color;
use App\Models\ComplementoCampo;
use App\Models\ConductividadCalidad;
use App\Models\ConductividadMuestra;
use App\Models\ConductividadTrazable;
use App\Models\ConTratamiento;
use App\Models\DireccionReporte;
use App\Models\Envase;
use App\Models\Frecuencia001;
use App\Models\Evidencia;
use App\Models\GastoMuestra;
use App\Models\HistorialCampoAsignar;
use App\Models\HistorialCampoCaptCompuesto;
use App\Models\HistorialCampoCaptMuestreoConductividad;
use App\Models\HistorialCampoCaptMuestreoGasto;
use App\Models\HistorialCampoCaptMuestreoPh;
use App\Models\HistorialCampoCaptMuestreoPhCalidad;
use App\Models\HistorialCampoCaptMuestreoTemp;
use App\Models\HistorialCampoCapturaConCalidad;
use App\Models\HistorialCampoCapturaConTrazable;
use App\Models\HistorialCampoCapturaGeneral;
use App\Models\HistorialCampoCapturaPhCalidad;
use App\Models\HistorialCampoCapturaPhTrazable;
use App\Models\HistorialCampoCapturaSegAnalisis;
use App\Models\MetodoAforo;
use App\Models\Parametro;
use App\Models\PHCalidad;
use App\Models\PhCalidadCampo;
use App\Models\PhMuestra;
use App\Models\PHTrazable;
use App\Models\PlanComplemento;
use App\Models\PlanPaquete;
use App\Models\ProcedimientoAnalisis;
use App\Models\ProcesoAnalisis;
use App\Models\PuntoMuestreoGen;
use App\Models\PuntoMuestreoSir;
use App\Models\SeguimientoAnalisis;
use App\Models\Solicitud;
use App\Models\Cotizacion;
use Facade\FlareClient\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\SolicitudesGeneradas;
use App\Models\SolicitudParametro;
use App\Models\SolicitudPuntos;
use App\Models\SubNorma;
use App\Models\SucursalCliente;
use App\Models\TemperaturaAmbiente;
use App\Models\TemperaturaMuestra;
use App\Models\TermFactorCorreccionTemp;
use App\Models\TermometroCampo;
use App\Models\TipoReporte;
use App\Models\TipoTratamiento;
use App\Models\User;
use App\Models\Users;
use App\Models\Usuario;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class CampoController extends Controller
{
    //     

    public function asignar()
    {

        if (Auth::user()->role->id == 13) {
            $model = DB::table('ViewSolicitud2')->where('Padre', 1)->where('Id_servicio', 1)->where('Id_user_c', Auth::user()->id)->where('Id_servicio', '!=', 3)->OrderBy('Id_solicitud', 'DESC')->get();
        } else {
            $model = DB::table('ViewSolicitud2')->where('Padre', 1)->where('Id_servicio', 1)->where('Id_servicio', '!=', 3)->OrderBy('Id_solicitud', 'DESC')->get();
        }

        $intermediarios = DB::table('ViewIntermediarios')->where('deleted_at', NULL)->get();
        $generadas = SolicitudesGeneradas::all();
        $usuarios = Usuario::all();
        return view('campo.asignarMuestreo', compact('model', 'intermediarios', 'generadas', 'usuarios'));
    }
    public function listaMuestreo()
    {
        $equipo = DB::table('ViewCampoGenerales')->get();
        // var_dump(Auth::user()); 
        switch (Auth::user()->role_id) {
            case 1:
            case 15:
                $model = DB::table('ViewSolicitudGenerada')->orderBy('Id_solicitud', 'DESC')->get();
                break;
            default:
                $model = DB::table('ViewSolicitudGenerada')->where('Id_muestreador', Auth::user()->id)->orderBy('Id_solicitud', 'DESC')->get();
                break;
        }
        return view('campo.listaMuestreo', compact('model', 'equipo'));
    }
    public function setObservacion(Request $res)
    {
        $model = Solicitud::where('Id_solicitud', $res->idSol)->first();
        $model->Observacion_plan = $res->obs;
        $model->save();
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function captura($id)
    {
        $phControlCalidad = PHCalidad::where('Ph_calidad', 7)->first();

        $phTrazable = PHTrazable::all();
        $phCalidad = PHCalidad::all();
        $termometros = TermometroCampo::where('Tipo', 1)->get();
        $termometros2 = TermometroCampo::where('Tipo', 2)->get();
        $conTrazable = ConductividadTrazable::all();
        $conCalidad = ConductividadCalidad::all();
        $aforo = MetodoAforo::all();
        $conTratamiento = ConTratamiento::all();
        $tipo = TipoTratamiento::all();
        $color = Color::all();

        $model = DB::table('ViewSolicitudGenerada')->where('Id_solicitud', $id)->first();
        $general = CampoGenerales::where('Id_solicitud', $model->Id_solicitud)->first();
        $frecuencia = DB::table('frecuencia001')->where('Id_frecuencia', $model->Id_muestreo)->first();
        $phCampoTrazable = CampoPhTrazable::where('Id_solicitud', $model->Id_solicitud)->get();
        $phCampoCalidad = CampoPhCalidad::where('Id_solicitud', $model->Id_solicitud)->get();
        $phCampoCalTemo = DB::table('ph_calidad')->get();
        $conCampoTrazable = CampoConTrazable::where('Id_solicitud', $model->Id_solicitud)->first();
        $conCampoCalidad = CampoConCalidad::where('Id_solicitud', $model->Id_solicitud)->first();
        $puntos = SolicitudPuntos::where('Id_solicitud', $id)->first();
        $evidencia = Evidencia::where('Id_punto', $puntos->Id_muestreo)->orderby('created_at', 'desc')->get();
        $compuesto = CampoCompuesto::where('Id_solicitud', $id)->first();
        $materia = SolicitudParametro::where('Id_subnorma', 2)->where('Id_solicitud', $model->Id_solicitud)->get();


        //Datos muestreo
        $phMuestra = PhMuestra::where('Id_solicitud', $id)->get();
        $tempMuestra = TemperaturaMuestra::where('Id_solicitud', $id)->get();
        $tempAmbiente = TemperaturaAmbiente::where('Id_solicitud', $id)->get();
        $phCalidadCampo = PhCalidadCampo::where('Id_solicitud', $id)->get();
        $conductividadMuestra = ConductividadMuestra::where('Id_solicitud', $id)->get();
        $gastoMuestra = GastoMuestra::where('Id_solicitud', $id)->get();

        $materiales =   DB::table('ViewEnvaseParametroSol')->where('Id_solicitud', $id)->get();

        if ($model->Num_tomas > 1) {
            $hidden = "";
        } else {
            $hidden = "hidden";
        }


        $data = array(
            'phCampoCalTemo' => $phCampoCalTemo,
            'hidden' => $hidden,
            'model' => $model,
            'color' => $color,
            'materia' => $materia,
            'general' => $general,
            'compuesto' => $compuesto,
            'evidencia' => $evidencia,
            'frecuencia' => $frecuencia,
            'termometros' => $termometros,
            'termometros2' => $termometros2,
            'phTrazable' => $phTrazable,
            'phCalidad' => $phCalidad,
            'conTrazable' => $conTrazable,
            'conCalidad' => $conCalidad,
            'aforo' => $aforo,
            'conTratamiento' => $conTratamiento,
            'tipo' => $tipo,
            'phCampoTrazable' => $phCampoTrazable,
            'phCampoCalidad' => $phCampoCalidad,
            'conCampoTrazable' => $conCampoTrazable,
            'conCampoCalidad' => $conCampoCalidad,
            'phControlCalidad' => $phControlCalidad,
            'phMuestra' => $phMuestra,
            'tempMuestra' => $tempMuestra,
            'tempAmbiente' => $tempAmbiente,
            'phCalidadCampo' => $phCalidadCampo,
            'conductividadMuestra' => $conductividadMuestra,
            'gastoMuestra' => $gastoMuestra,
            'puntos' => $puntos,
            'materiales' => $materiales,
            //'phCampoCalidadMuestra' => $phCampoCalidadMuestra
        );
        return view('campo.captura', $data);
    }
    public function setDataGeneral(Request $request)
    {


        $model = CampoGenerales::where('Id_solicitud', $request->idSolicitud)->first();
        $model->Captura = "Sistema";
        $model->Id_equipo = $request->equipo;
        $model->Id_equipo2 = $request->equipo2;
        $model->Temperatura_a = $request->temp1;
        $model->Temperatura_b = $request->temp2;
        $model->Latitud = $request->latitud;
        $model->Longitud = $request->longitud;
        $model->Altitud = $request->altitud;
        $model->Pendiente = $request->pendiente;
        $model->Criterio = $request->criterio;
        $model->Supervisor = $request->supervisor;
        // $model->Firma_revisor = $imagenComoBase64;
        $model->Id_user_m = Auth::user()->id;
        $model->save();
        $nota = "Registro modificado";

        //Solicitudes generadas (para actualizar el nombre del punto de muestreo en Campo Captura)
        $punto = SolicitudesGeneradas::where('Id_solicitud', $request->idSolicitud)->first();
        $punto->Punto_muestreo = $request->puntoMuestreo;
        $punto->save();
        $puntoSol = SolicitudPuntos::where('Id_solicitud', $request->idSolicitud)->first();
        $puntoSol->Punto = $request->puntoMuestreo;
        $puntoSol->save();

        //Ph trazable
        $phTrazableModel = CampoPhTrazable::where('Id_solicitud', $request->idSolicitud)->get();
        $phTrazable = CampoPhTrazable::find($phTrazableModel[0]->Id_ph);
        $phTrazable->Id_solicitud = $request->idSolicitud;
        $phTrazable->Id_phTrazable = $request->phTrazable1;
        $phTrazable->Lectura1 = $request->phTl11;
        $phTrazable->Lectura2 = $request->phT21;
        $phTrazable->Lectura3 = $request->phTl31;
        $phTrazable->Estado = $request->phTEstado1;
        $phTrazable->Id_user_m = Auth::user()->id;

        $nota = "Registro modificado";
        // $this->historialPhTrazable($request->idSolicitud, $nota, $phTrazable->Id_ph);

        $phTrazable->save();

        $phTrazable = CampoPhTrazable::find($phTrazableModel[1]->Id_ph);
        $phTrazable->Id_solicitud = $request->idSolicitud;
        $phTrazable->Id_phTrazable = $request->phTrazable2;
        $phTrazable->Lectura1 = $request->phTl12;
        $phTrazable->Lectura2 = $request->phT22;
        $phTrazable->Lectura3 = $request->phTl32;
        $phTrazable->Estado = $request->phTEstado2;
        $phTrazable->Id_user_m = Auth::user()->id;

        $nota = "Registro modificado";
        // $this->historialPhTrazable($request->idSolicitud, $nota, $phTrazable->Id_ph);
        $phTrazable->save();

        //PhCalidad

        $phCalidadMode = CampoPhCalidad::where('Id_solicitud', $request->idSolicitud)->get();
        $phCalidad = CampoPhCalidad::find($phCalidadMode[0]->Id_ph);
        $phCalidad->Id_solicitud = $request->idSolicitud;
        $phCalidad->Id_phCalidad = $request->phTrazable1;
        $phCalidad->Lectura1 = $request->phC11;
        $phCalidad->Lectura2 = $request->phC21;
        $phCalidad->Lectura3 = $request->phC31;
        $phCalidad->Estado = $request->phTEstado1;
        $phCalidad->Promedio = $request->phCPromedio1;
        $phCalidad->Id_user_m = Auth::user()->id;

        $nota = "Registro modificado";
        // $this->historialPhCalidadGen($request->idSolicitud, $nota, $phCalidad->Id_ph);

        $phCalidad->save();

        $phCalidad = CampoPhCalidad::find($phCalidadMode[1]->Id_ph);
        $phCalidad->Id_solicitud = $request->idSolicitud;
        $phCalidad->Id_phCalidad = $request->phTrazable2;
        $phCalidad->Lectura1 = $request->phC12;
        $phCalidad->Lectura2 = $request->phC22;
        $phCalidad->Lectura3 = $request->phC23;
        $phCalidad->Estado = $request->phTEstado2;
        $phCalidad->Promedio = $request->phCPromedio2;
        $phCalidad->Id_user_m = Auth::user()->id;

        $nota = "Registro modificado";
        // $this->historialPhCalidadGen($request->idSolicitud, $nota, $phCalidad->Id_ph);

        $phCalidad->save();

        //ConTrazable
        $conTrazableModel = CampoConTrazable::where('Id_solicitud', $request->idSolicitud)->get();
        $conTrazable = CampoConTrazable::find($conTrazableModel[0]->Id_conductividad);
        $conTrazable->Id_solicitud = $request->idSolicitud;
        $conTrazable->Id_conTrazable = $request->conTrazable;
        $conTrazable->Lectura1 = $request->conT1;
        $conTrazable->Lectura2 = $request->conT2;
        $conTrazable->Lectura3 = $request->conT3;
        $conTrazable->Estado = $request->conTEstado;
        $conTrazable->Id_user_m = Auth::user()->id;

        $nota = "Registro modificado";
        // $this->historialCondTrazable($request->idSolicitud, $nota, $conTrazable->Id_conductividad);
        $conTrazable->save();

        //Conductividad control calidad
        $conCalidadModel = CampoConCalidad::where('Id_solicitud', $request->idSolicitud)->get();
        $conCalidad = CampoConCalidad::find($conCalidadModel[0]->Id_conductividad);
        $conCalidad->Id_solicitud = $request->idSolicitud;
        $conCalidad->Id_conCalidad = $request->conCalidad;
        $conCalidad->Lectura1 = $request->conCl1;
        $conCalidad->Lectura2 = $request->conCl2;
        $conCalidad->Lectura3 = $request->conCl3;
        $conCalidad->Estado = $request->conCEstado;
        $conCalidad->Promedio = $request->conCPromedio;
        $conCalidad->Id_user_m = Auth::user()->id;

        $nota = "Registro modificado";
        // $this->historialCondCalidad($request->idSolicitud, $nota, $conCalidad->Id_conductividad);

        $conCalidad->save();


        $data = array(
            'sw' => true,
        );
        return response()->json($data);
    }
    public function generarVmsi(Request $res)
    {
        $model = GastoMuestra::where('Id_solicitud', $res->idSolicitud)->get();
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }


    public function CancelarMuestra(Request $request)
    {
        $std = false;
        $ph = PhMuestra::where('Id_solicitud', $request->idSolicitud)->where('Num_toma', $request->muestra)->first();
        $tempMuestra = TemperaturaMuestra::where('Id_solicitud', $request->idSolicitud)->where('Num_toma', $request->muestra)->first();
        $tempAmbiente = TemperaturaAmbiente::where('Id_solicitud', $request->idSolicitud)->where('Num_toma', $request->muestra)->first();
        $phControlCalidad = PhCalidadCampo::where('Id_solicitud', $request->idSolicitud)->where('Num_toma', $request->muestra)->first();
        $cond = ConductividadMuestra::where('Id_solicitud', $request->idSolicitud)->where('Num_toma', $request->muestra)->first();
        $gasto = GastoMuestra::where('Id_solicitud', $request->idSolicitud)->where('Num_toma', $request->muestra)->first();
        if ($ph->Activo == 1) {
            $ph->Activo = 0;
            $tempMuestra->Activo = 0;
            $tempAmbiente->Activo = 0;
            $cond->Activo = 0;
            $phControlCalidad->Activo = 0;
            $gasto->Activo = 0;
            $std = true;
        } else {
            $ph->Activo = 1;
            $tempMuestra->Activo = 1;
            $tempAmbiente->Activo = 1;
            $cond->Activo = 1;
            $phControlCalidad->Activo = 1;
            $gasto->Activo = 1;
            $std =  false;
        }

        $ph->save();
        $tempMuestra->save();
        $tempAmbiente->save();
        $cond->save();
        $phControlCalidad->save();
        $gasto->save();

        $data = array(
            'std' => $std,
        );

        return response()->json($data);
    }
    //-----------------------------Inicio de guardado independiente en Captura campo-----------------------------------
    public function GuardarPhMuestra(Request $res)
    {
        $tomas = PhMuestra::where('Id_solicitud', $res->id)->get();
        $cont = 0;
        foreach ($tomas as $item) {
            $model = PhMuestra::find($item->Id_ph);
            $model->Num_toma = $cont + 1;
            $model->Materia = $res->materia[$cont];
            $model->Olor = $res->olor[$cont];
            $model->Color = $res->color[$cont];
            $model->Ph1 = $res->ph1[$cont];
            $model->Ph2 = $res->ph2[$cont];
            $model->Ph3 = $res->ph3[$cont];
            $model->Promedio = $res->promedio[$cont];
            $model->Fecha = $res->fecha[$cont];
            $model->Activo = $res->activo[$cont];
            $model->save();
            $cont++;
        }
    }
    public function GuardarTempAgua(Request $request)
    {
        $model = TemperaturaMuestra::where('Id_solicitud', $request->idSolicitud)->get();
        for ($i = 0; $i < sizeof($model); $i++) {
            $model[$i]->Temperatura1 = $request->arrayB1[$i];
            $model[$i]->Temperatura2 = $request->arrayB2[$i];
            $model[$i]->Temperatura3 = $request->arrayB3[$i];
            $model[$i]->TemperaturaSin1 = $request->array1[$i];
            $model[$i]->TemperaturaSin2 = $request->array2[$i];
            $model[$i]->TemperaturaSin3 = $request->array3[$i];
            $model[$i]->Promedio = $request->promedio[$i];
            $model[$i]->Activo = $request->estado[$i];
            $model[$i]->save();
        }
        $data = array(
            'model' => $model,
            'array' => $request->array1,
        );
        return response()->json($data);
    }
    public function GuardarTempAmb(Request $request)
    {
        $model = TemperaturaAmbiente::where('Id_solicitud', $request->idSolicitud)->get();
        for ($i = 0; $i < sizeof($model); $i++) {
            $model[$i]->Temperatura1 = $request->array2[$i];
            $model[$i]->TemperaturaSin1 = $request->array1[$i];
            $model[$i]->Fact_apl = $request->factor[$i];
            $model[$i]->Activo = $request->activo[$i];
            $model[$i]->save();
        }
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function GuardarPhControlCalidad(Request $request)
    {
        $model = PhCalidadCampo::where('Id_solicitud', $request->idSolicitud)->get();
        for ($i = 0; $i < sizeof($model); $i++) {
            $model[$i]->Ph_calidad = $request->array1[$i];
            $model[$i]->Lectura1 = $request->array2[$i];
            $model[$i]->Lectura2 = $request->array3[$i];
            $model[$i]->Lectura3 = $request->array4[$i];
            $model[$i]->Promedio = $request->promedio[$i];
            $model[$i]->Estado = $request->estado[$i];
            $model[$i]->Activo = $request->activo[$i];
            $model[$i]->save();
        }
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function GuardarConductividad(Request $request)
    {
        $model = ConductividadMuestra::where('Id_solicitud', $request->idSolicitud)->get();
        for ($i = 0; $i < sizeof($model); $i++) {
            $model[$i]->Conductividad1 = $request->array1[$i];
            $model[$i]->Conductividad2 = $request->array2[$i];
            $model[$i]->Conductividad3 = $request->array3[$i];
            $model[$i]->Promedio = $request->promedio[$i];
            $model[$i]->Activo = $request->activo[$i];
            $model[$i]->save();
        }
        $data = array(
            'model' => $model,

        );
        return response()->json($data);
    }
    public function GuardarGasto(Request $request)
    {
        $model = GastoMuestra::where('Id_solicitud', $request->idSolicitud)->get();
        for ($i = 0; $i < sizeof($model); $i++) {
            $model[$i]->Gasto1 = $request->array1[$i];
            $model[$i]->Gasto2 = $request->array2[$i];
            $model[$i]->Gasto3 = $request->array3[$i];
            $model[$i]->Promedio = $request->promedio[$i];
            $model[$i]->Activo = $request->estado[$i];
            $model[$i]->save();
        }
        $data = array(
            'model' => $model,

        );
        return response()->json($data);
    }
    public function setDatosCompuestos(Request $request)
    {
        $model = CampoCompuesto::where('Id_solicitud', $request->idSolicitud)->first();
        $model->Metodo_aforo = $request->metodoAforo;
        $model->Con_tratamiento = $request->ConTratamiento;
        $model->Tipo_tratamiento = $request->TipoTratamiento;
        $model->Proce_muestreo = $request->ProcedimientoMuestreo;
        $model->Volumen_calculado = $request->volumenCalculado;
        $model->Observaciones = $request->observacion;
        $model->Ph_muestraComp = $request->phMuestraCompuesta;
        $model->Temp_muestraComp = $request->tempMuestraCompuesta;
        $model->Cloruros = $request->cloruros;
        $model->save();

        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    //---------------------Fin de metodos de guardado------------------------

    public function historialCampoGeneral($idSol, $nota, $campoGeneral)
    {
        $idUser = Auth::user()->id;

        $model = DB::table('campo_generales')->where('Id_general', $campoGeneral)->where('Id_solicitud', $idSol)->first();

        HistorialCampoCapturaGeneral::create([
            'Id_general' => $model->Id_general,
            'Id_solicitud' => $model->Id_solicitud,
            'Captura' => $model->Captura,
            'Id_equipo' => $model->Id_equipo,
            'Temperatura_a' => $model->Temperatura_a,
            'Temperatura_b' => $model->Temperatura_b,
            'Latitud' => $model->Latitud,
            'Longitud' => $model->Longitud,
            'Altitud' => $model->Altitud,
            'Pendiente' => $model->Pendiente,
            'Criterio' => $model->Criterio,
            'Supervisor' => $model->Supervisor,
            'Nota' => $nota,
            'F_creacion' => $model->created_at,
            'Id_user_c' => $model->Id_user_c,
            'F_modificacion' => $model->updated_at,
            'Id_user_m' => $idUser,
        ]);
    }

    public function historialPhTrazable($idSol, $nota, $campoPhTrazable)
    {
        $idUser = Auth::user()->id;

        $model = DB::table('campo_phTrazable')->where('Id_ph', $campoPhTrazable)->where('Id_solicitud', $idSol)->first();

        HistorialCampoCapturaPhTrazable::create([
            'Id_ph' => $model->Id_ph,
            'Id_solicitud' => $model->Id_solicitud,
            'Id_phTrazable' => $model->Id_phTrazable,
            'Lectura1' => $model->Lectura1,
            'Lectura2' => $model->Lectura2,
            'Lectura3' => $model->Lectura3,
            'Estado' => $model->Estado,
            'Nota' => $nota,
            'F_creacion' => $model->created_at,
            'Id_user_c' => $model->Id_user_c,
            'F_modificacion' => $model->updated_at,
            'Id_user_m' => $idUser,
        ]);
    }

    public function historialPhCalidadGen($idSol, $nota, $campoPhCalidad)
    {
        $idUser = Auth::user()->id;

        $model = DB::table('campo_phCalidad')->where('Id_ph', $campoPhCalidad)->where('Id_solicitud', $idSol)->first();

        HistorialCampoCapturaPhCalidad::create([
            'Id_ph' => $model->Id_ph,
            'Id_solicitud' => $model->Id_solicitud,
            'Id_phCalidad' => $model->Id_phCalidad,
            'Lectura1' => $model->Lectura1,
            'Lectura2' => $model->Lectura2,
            'Lectura3' => $model->Lectura3,
            'Estado' => $model->Estado,
            'Promedio' => $model->Promedio,
            'Nota' => $nota,
            'F_creacion' => $model->created_at,
            'Id_user_c' => $model->Id_user_c,
            'F_modificacion' => $model->updated_at,
            'Id_user_m' => $idUser,
        ]);
    }

    public function historialCondTrazable($idSol, $nota, $campoCondTrazable)
    {
        $idUser = Auth::user()->id;

        $model = DB::table('campo_conTrazable')->where('Id_conductividad', $campoCondTrazable)->where('Id_solicitud', $idSol)->first();

        HistorialCampoCapturaConTrazable::create([
            'Id_conductividad' => $model->Id_conductividad,
            'Id_solicitud' => $model->Id_solicitud,
            'Id_conTrazable' => $model->Id_conTrazable,
            'Lectura1' => $model->Lectura1,
            'Lectura2' => $model->Lectura2,
            'Lectura3' => $model->Lectura3,
            'Estado' => $model->Estado,
            'Nota' => $nota,
            'F_creacion' => $model->created_at,
            'Id_user_c' => $model->Id_user_c,
            'F_modificacion' => $model->updated_at,
            'Id_user_m' => $idUser,
        ]);
    }

    public function historialCondCalidad($idSol, $nota, $campoCondCalidad)
    {
        $idUser = Auth::user()->id;

        $model = DB::table('campo_conCalidad')->where('Id_conductividad', $campoCondCalidad)->where('Id_solicitud', $idSol)->first();

        HistorialCampoCapturaConCalidad::create([
            'Id_conductividad' => $model->Id_conductividad,
            'Id_solicitud' => $model->Id_solicitud,
            'Id_conCalidad' => $model->Id_conCalidad,
            'Lectura1' => $model->Lectura1,
            'Lectura2' => $model->Lectura2,
            'Lectura3' => $model->Lectura3,
            'Estado' => $model->Estado,
            'Promedio' => $model->Promedio,
            'Nota' => $nota,
            'F_creacion' => $model->created_at,
            'Id_user_c' => $model->Id_user_c,
            'F_modificacion' => $model->updated_at,
            'Id_user_m' => $idUser,
        ]);
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
                $ph->Fecha = $request->ph[$i][6];
                // $ph->Activo = $request->ph[$i][8];
                $ph->Activo = 1;
                $ph->Num_toma = $request->ph[$i][9];
                $ph->Id_user_m = Auth::user()->id;

                $nota = "Registro modificado";
                // $this->historialPhMuestra($request->idSolicitud, $nota, $ph->Id_ph);

                $ph->save();
            }
        } else {
            for ($i = 0; $i < $request->numTomas; $i++) {

                $phMuestra = PhMuestra::create([
                    'Id_solicitud' => $request->idSolicitud,
                    'Materia' => $request->ph[$i][0],
                    'Olor' => $request->ph[$i][1],
                    'Color' => $request->ph[$i][2],
                    'Ph1' => $request->ph[$i][3],
                    'Ph2' => $request->ph[$i][4],
                    'Ph3' => $request->ph[$i][5],
                    'Promedio' => $request->ph[$i][6],
                    'Fecha' => $request->ph[$i][7],
                    'Activo' => $request->ph[$i][8],
                    'Num_toma' => $request->ph[$i][9],
                    'Id_user_c' => Auth::user()->id,
                    'Id_user_m' => Auth::user()->id
                ]);

                $nota = "Creación de registro";
                // $this->historialPhMuestra($request->idSolicitud, $nota, $phMuestra->Id_ph);
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
                $temp->Activo = $request->temperatura[$i][4];
                $temp->Id_user_m = Auth::user()->id;

                $nota = "Registro modificado";
                // $this->historialTempMuestra($request->idSolicitud, $nota, $temp->Id_temperatura);

                $temp->save();
            }
        } else {
            for ($i = 0; $i < $request->numTomas; $i++) {

                $tempMuestra = TemperaturaMuestra::create([
                    'Id_solicitud' => $request->idSolicitud,
                    'Temperatura1' => $request->temperatura[$i][0],
                    'Temperatura2' => $request->temperatura[$i][1],
                    'Temperatura3' => $request->temperatura[$i][2],
                    'Promedio' => $request->temperatura[$i][3],
                    'Activo' => $request->temperatura[$i][4],
                    'Id_user_c' => Auth::user()->id,
                    'Id_user_m' => Auth::user()->id
                ]);

                $nota = "Creación de registro";
                //$this->historialTempMuestra($request->idSolicitud, $nota, $tempMuestra->Id_temperatura);
            }
        }

        $tempAmbiente = TemperaturaAmbiente::where('Id_solicitud', $request->idSolicitud)->get();

        if ($tempAmbiente->count()) {
            for ($i = 0; $i < $request->numTomas; $i++) {

                $temp = TemperaturaAmbiente::find($tempAmbiente[$i]->Id_temperatura);
                $temp->Id_solicitud = $request->idSolicitud;
                $temp->Temperatura1 = $request->temperatura2[$i][0];
                $temp->Temperatura2 = $request->temperatura2[$i][1];
                $temp->Temperatura3 = $request->temperatura2[$i][2];
                $temp->Promedio = $request->temperatura2[$i][3];
                $temp->Activo = $request->temperatura2[$i][4];
                $temp->Id_user_m = Auth::user()->id;

                $nota = "Registro modificado";
                // $this->historialTempMuestra($request->idSolicitud, $nota, $temp->Id_temperatura);

                $temp->save();
            }
        } else {
            for ($i = 0; $i < $request->numTomas; $i++) {

                $tempAmbiente = TemperaturaAmbiente::create([
                    'Id_solicitud' => $request->idSolicitud,
                    'Temperatura1' => $request->temperatura2[$i][0],
                    'Temperatura2' => $request->temperatura2[$i][1],
                    'Temperatura3' => $request->temperatura2[$i][2],
                    'Promedio' => $request->temperatura2[$i][3],
                    'Activo' => $request->temperatura2[$i][4],
                    'Id_user_c' => Auth::user()->id,
                    'Id_user_m' => Auth::user()->id
                ]);

                $nota = "Creación de registro";
                // $this->historialTempMuestra($request->idSolicitud, $nota, $tempMuestra->Id_temperatura);
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
                $phCalidad->Activo = $request->phCalidad[$i][6];
                $phCalidad->Id_user_m = Auth::user()->id;

                $nota = "Registro modificado";
                // $this->historialPhControl($request->idSolicitud, $nota, $phCalidad->Id_phCalidad);

                $phCalidad->save();
            }
        } else {
            for ($i = 0; $i < $request->numTomas; $i++) {

                $campoPhControlMuestra = PhCalidadCampo::create([
                    'Id_solicitud' => $request->idSolicitud,
                    'Ph_calidad' => $request->phCalidad[$i][0],
                    'Lectura1' => $request->phCalidad[$i][1],
                    'Lectura2' => $request->phCalidad[$i][2],
                    'Lectura3' => $request->phCalidad[$i][3],
                    'Estado' => $request->phCalidad[$i][4],
                    'Promedio' => $request->phCalidad[$i][5],
                    'Activo' => $request->phCalidad[$i][6],
                    'Id_user_c' => Auth::user()->id,
                    'Id_user_m' => Auth::user()->id
                ]);

                $nota = "Creación de registro";
                // $this->historialPhControl($request->idSolicitud, $nota, $campoPhControlMuestra->Id_phCalidad);
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
                $conduc->Activo = $request->conductividad[$i][4];
                $conduc->Id_user_m = Auth::user()->id;

                $nota = "Registro modificado";
                // $this->historialConductividad($request->idSolicitud, $nota, $conduc->Id_conductividad);

                $conduc->save();
            }
        } else {
            for ($i = 0; $i < $request->numTomas; $i++) {

                $campoCondMuestra = ConductividadMuestra::create([
                    'Id_solicitud' => $request->idSolicitud,
                    'Conductividad1' => $request->conductividad[$i][0],
                    'Conductividad2' => $request->conductividad[$i][1],
                    'Conductividad3' => $request->conductividad[$i][2],
                    'Promedio' => $request->conductividad[$i][3],
                    'Activo' => $request->conductividad[$i][4],
                    'Id_user_c' => Auth::user()->id,
                    'Id_user_m' => Auth::user()->id
                ]);

                $nota = "Creación de registro";
                // $this->historialConductividad($request->idSolicitud, $nota, $campoCondMuestra->Id_conductividad);
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
                $gasto->Activo = $request->gasto[$i][4];
                $gasto->Id_user_m = Auth::user()->id;

                $nota = "Registro modificado";
                // $this->historialGasto($request->idSolicitud, $nota, $gasto->Id_gasto);

                $gasto->save();
            }
        } else {
            for ($i = 0; $i < $request->numTomas; $i++) {

                $campoGastoMuestra = GastoMuestra::create([
                    'Id_solicitud' => $request->idSolicitud,
                    'Gasto1' => $request->gasto[$i][0],
                    'Gasto2' => $request->gasto[$i][1],
                    'Gasto3' => $request->gasto[$i][2],
                    'Promedio' => $request->gasto[$i][3],
                    'Activo' => $request->gasto[$i][4],
                    'Id_user_c' => Auth::user()->id,
                    'Id_user_m' => Auth::user()->id
                ]);

                $nota = "Creación de registro";
                //  $this->historialGasto($request->idSolicitud, $nota, $campoGastoMuestra->Id_gasto);
            }
        }

        $data = array('sw' => true);
        return response()->json($data);
    }

    public function historialPhMuestra($idSol, $nota, $campoPhMuestra)
    {
        $idUser = Auth::user()->id;

        $model = DB::table('ph_muestra')->where('Id_ph', $campoPhMuestra)->where('Id_solicitud', $idSol)->first();
        /* HistorialCampoCaptMuestreoPh::create([
            'Id_ph' => $model->Id_ph,
            'Id_solicitud' => $model->Id_solicitud,
            'Num_toma' => $model->Num_toma,
            'Materia' => $model->Materia,
            'Olor' => $model->Olor,
            'Color' => $model->Color,
            'Ph1' => $model->Ph1,
            'Ph2' => $model->Ph2,
            'Ph3' => $model->Ph3,
            'Promedio' => $model->Promedio,
            'Fecha' => $model->Fecha,
            'Activo' => $model->Activo,
            'Nota' => $nota,
            'F_creacion' => $model->created_at,
            'Id_user_c' => $model->Id_user_c,            
            'F_modificacion' => $model->updated_at,
            'Id_user_m' => $idUser,
        ]); */
    }

    public function historialTempMuestra($idSol, $nota, $campoTempMuestra)
    {
        $idUser = Auth::user()->id;

        $model = DB::table('temperatura_muestra')->where('Id_temperatura', $campoTempMuestra)->where('Id_solicitud', $idSol)->first();
        /* HistorialCampoCaptMuestreoTemp::create([
            'Id_temperatura' => $model->Id_temperatura,
            'Id_solicitud' => $model->Id_solicitud,
            'Temperatura1' => $model->Temperatura1,
            'Temperatura2' => $model->Temperatura2,
            'Temperatura3' => $model->Temperatura3,
            'Promedio' => $model->Promedio,
            'Activo' => $model->Activo,
            'Nota' => $nota,
            'F_creacion' => $model->created_at,
            'Id_user_c' => $model->Id_user_c,
            'F_modificacion' => $model->updated_at,
            'Id_user_m' => $idUser,
        ]); */
    }

    public function historialPhControl($idSol, $nota, $campoPhControlMuestra)
    {
        $idUser = Auth::user()->id;

        $model = DB::table('ph_calidadCampo')->where('Id_phCalidad', $campoPhControlMuestra)->where('Id_solicitud', $idSol)->first();
        /* HistorialCampoCaptMuestreoPhCalidad::create([            
            'Id_phCalidad' => $model->Id_phCalidad,
            'Id_solicitud' => $model->Id_solicitud,
            'Ph_calidad' => $model->Ph_calidad,
            'Lectura1' => $model->Lectura1,
            'Lectura2' => $model->Lectura2,
            'Lectura3' => $model->Lectura3,
            'Estado' => $model->Estado,
            'Promedio' => $model->Promedio,
            'Activo' => $model->Activo,
            'Nota' => $nota,
            'F_creacion' => $model->created_at,
            'Id_user_c' => $model->Id_user_c,
            'F_modificacion' => $model->updated_at,
            'Id_user_m' => $idUser,
        ]); */
    }

    public function historialConductividad($idSol, $nota, $campoCondMuestra)
    {
        $idUser = Auth::user()->id;

        $model = DB::table('conductividad_muestra')->where('Id_conductividad', $campoCondMuestra)->where('Id_solicitud', $idSol)->first();
        /* HistorialCampoCaptMuestreoConductividad::create([            
            'Id_conductividad' => $model->Id_conductividad,
            'Id_solicitud' => $model->Id_solicitud,
            'Conductividad1' => $model->Conductividad1,
            'Conductividad2' => $model->Conductividad2,
            'Conductividad3' => $model->Conductividad3,
            'Promedio' => $model->Promedio,
            'Activo' => $model->Activo,
            'Nota' => $nota,
            'F_creacion' => $model->created_at,
            'Id_user_c' => $model->Id_user_c,
            'F_modificacion' => $model->updated_at,
            'Id_user_m' => $idUser,
        ]); */
    }

    public function historialGasto($idSol, $nota, $campoGastoMuestra)
    {
        $idUser = Auth::user()->id;

        $model = DB::table('gasto_muestra')->where('Id_gasto', $campoGastoMuestra)->where('Id_solicitud', $idSol)->first();
        /* HistorialCampoCaptMuestreoGasto::create([            
            'Id_gasto' => $model->Id_gasto,
            'Id_solicitud' => $model->Id_solicitud,
            'Gasto1' => $model->Gasto1,
            'Gasto2' => $model->Gasto2,
            'Gasto3' => $model->Gasto3,
            'Promedio' => $model->Promedio,
            'Activo' => $model->Activo,
            'Nota' => $nota,
            'F_creacion' => $model->created_at,
            'Id_user_c' => $model->Id_user_c,
            'F_modificacion' => $model->updated_at,
            'Id_user_m' => $idUser,
        ]); */
    }

    public function setDataCompuesto(Request $request)
    {
        $campoCompModel = CampoCompuesto::where('Id_solicitud', $request->idSolicitud)->get();

        if ($campoCompModel->count()) {
            $campoComp = CampoCompuesto::where('Id_solicitud', $request->idSolicitud)->first();

            $campoComp->Metodo_aforo = $request->aforoCompuesto;
            $campoComp->Con_tratamiento = $request->conTratamientoCompuesto;
            $campoComp->Tipo_tratamiento = $request->tipoTratamientoCompuesto;
            $campoComp->Proce_muestreo = $request->procedimientoCompuesto;
            $campoComp->Observaciones = $request->obsCompuesto;
            $campoComp->Ph_muestraComp = $request->phMuestraCompuesto;
            $campoComp->Temp_muestraComp = $request->valTempCompuesto;
            $campoComp->Volumen_calculado = $request->volCalculadoComp;
            $campoComp->Cloruros = $request->cloruros;
            $campoComp->Id_user_m = Auth::user()->id;

            $nota = "Registro modificado";
            // $this->historialDatosCompuestos($request->idSolicitud, $nota, $campoComp->Id_campo);

            $campoComp->save();
        } else {
            $campoComp = CampoCompuesto::create([
                'Id_solicitud' => $request->idSolicitud,
                'Metodo_aforo' => $request->aforoCompuesto,
                'Con_tratamiento' => $request->conTratamientoCompuesto,
                'Tipo_tratamiento' => $request->tipoTratamientoCompuesto,
                'Proce_muestreo' => $request->procedimientoCompuesto,
                'Observaciones' => $request->obsCompuesto,
                //'Obser_solicitud' => $request->obs_sol,
                'Ph_muestraComp' => $request->phMuestraCompuesto,
                'Temp_muestraComp' => $request->valTempCompuesto,
                'Volumen_calculado' => $request->volCalculadoComp,
                'Id_user_c' => Auth::user()->id,
                'Id_user_m' => Auth::user()->id
            ]);

            $nota = "Creación de registro";
            //$this->historialDatosCompuestos($request->idSolicitud, $nota, $campoComp->Id_campo);
        }

        $data = array('sw' => true, 'campoComp' => $campoComp);
        return response()->json($data);
    }

    public function setEvidencia(Request $request)
    {

        $contenidoBinario = file_get_contents($request->file);
        $imagenComoBase64 = base64_encode($contenidoBinario);


        $model = Evidencia::create([
            'Id_solicitud' => $request->idSolEv,
            'Id_punto' => $request->idPuntEv,
            'Base64' => $imagenComoBase64
        ]);

        return redirect()->to('admin/campo/captura/' . $request->idSolEv);
    }
    public function setEvidenciaFirma(Request $res)
    {

        $contenidoBinario = file_get_contents($res->file);
        $imagenComoBase64 = base64_encode($contenidoBinario);

        $model = CampoGenerales::where('Id_solicitud', $res->idSolEvFir)->first();
        $model->firma_revisor = $imagenComoBase64;
        $model->save();

        return redirect()->to('admin/campo/captura/' . $res->idSolEvFir);
    }
    public function historialDatosCompuestos($idSol, $nota, $campoCompuesto)
    {
        $idUser = Auth::user()->id;

        $model = DB::table('campo_compuesto')->where('Id_campo', $campoCompuesto)->where('Id_solicitud', $idSol)->first();
        HistorialCampoCaptCompuesto::create([
            'Id_campo' => $model->Id_campo,
            'Id_solicitud' => $model->Id_solicitud,
            'Metodo_aforo' => $model->Metodo_aforo,
            'Con_tratamiento' => $model->Con_tratamiento,
            'Tipo_tratamiento' => $model->Tipo_tratamiento,
            'Proce_muestreo' => $model->Proce_muestreo,
            'Observaciones' => $model->Observaciones,
            //'Obser_solicitud' => $model->Obser_solicitud,
            'Ph_muestraComp' => $model->Ph_muestraComp,
            'Temp_muestraComp' => $model->Temp_muestraComp,
            'Volumen_calculado' => $model->Volumen_calculado,
            'Nota' => $nota,
            'F_creacion' => $model->created_at,
            'Id_user_c' => $model->Id_user_c,
            'F_modificacion' => $model->updated_at,
            'Id_user_m' => $idUser,
        ]);
    }

    public $nota;
    public $alert;
    public $idSol;

    public function asignarMultiple(Request $res)
    {
        $model = SolicitudPuntos::where('Id_solPadre', $res->id)->get();
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function setMuestreadorMultiple(Request $res)
    {
        $user = User::where('id', $res->idUser)->first();
        $model = SolicitudesGeneradas::where('Id_solicitud', $res->id)->first();
        $model->Id_muestreador = $res->idUser;
        $model->Nombres = $user->name;
        $model->save();

        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function generar(Request $request) //Generar solicitud 
    {
        $sol = SolicitudesGeneradas::where('Id_solPadre', $request->idSolicitud)->get();
        
        if ($sol->count() > 0) {                    //ACTUALIZAR
            $model = SolicitudesGeneradas::where('Id_solPadre', $request->idSolicitud)->get();
            $msg = "Entro a if";
        } else {
            $msg = "Entro a else";
            //CREAR
            $this->idSol = $request->idSolicitud;
            $this->nota = "Creación de registro";
            $solModel = Solicitud::where('Hijo', $request->idSolicitud)->get();
            //
            foreach ($solModel as $item) {
                // $idPunto = SolicitudPuntos::where('Id_solicitud',$item->Id_solicitud)->first();
                $punto = SolicitudPuntos::where('Id_solicitud', $item->Id_solicitud)->first();

                SolicitudesGeneradas::create([
                    'Id_solicitud' => $item->Id_solicitud,
                    'Id_solPadre' => $request->idSolicitud,
                    'Folio' => $item->Folio_servicio,
                    'Punto_muestreo' => $punto->Punto,
                    'Id_user_c' => Auth::user()->id,
                    'Captura' => "Sin captura"
                ]);
                CampoGenerales::create([
                    'Id_solicitud' => $item->Id_solicitud,
                ]);
                //Datos generales
                //-----------doble
                CampoPhTrazable::create([
                    'Id_solicitud' => $item->Id_solicitud,
                ]);
                CampoPhCalidad::create([
                    'Id_solicitud' => $item->Id_solicitud,
                ]);
                CampoPhTrazable::create([
                    'Id_solicitud' => $item->Id_solicitud,
                ]);
                CampoPhCalidad::create([
                    'Id_solicitud' => $item->Id_solicitud,
                ]);
                //----------fin doble
                CampoConTrazable::create([
                    'Id_solicitud' => $item->Id_solicitud,
                ]);
                CampoConCalidad::create([
                    'Id_solicitud' => $item->Id_solicitud,
                ]);
                CampoCompuesto::create([
                    'Id_solicitud' => $item->Id_solicitud,
                ]);

                for ($i = 0; $i < $item->Num_tomas; $i++) {
                    //Datos muestreo
                    PhMuestra::create([
                        'Id_solicitud' => $item->Id_solicitud,
                        'Num_toma' => $i + 1,
                        'Activo' => 1,
                    ]);
                    TemperaturaMuestra::create([
                        'Id_solicitud' => $item->Id_solicitud,
                        'Num_toma' => $i + 1,
                        'Activo' => 1,
                    ]);
                    TemperaturaAmbiente::create([
                        'Id_solicitud' => $item->Id_solicitud,
                        'Num_toma' => $i + 1,
                        'Activo' => 1,
                    ]);
                    PhCalidadCampo::create([
                        'Id_solicitud' => $item->Id_solicitud,
                        'Num_toma' => $i + 1,
                        'Activo' => 1,
                    ]);
                    ConductividadMuestra::create([
                        'Id_solicitud' => $item->Id_solicitud,
                        'Num_toma' => $i + 1,
                        'Activo' => 1,
                    ]);
                    GastoMuestra::create([
                        'Id_solicitud' => $item->Id_solicitud,
                        'Num_toma' => $i + 1,
                        'Activo' => 1,
                    ]);
                }
            }


            //$this->historial();
            $this->alert = true;
          
        }
        $solPunto = array();
        $model = SolicitudesGeneradas::where('Id_solPadre', $request->idSolicitud)->get();
        foreach ($model as $item) {
            $temp = SolicitudPuntos::where('Id_solicitud',$item->Id_solicitud)->first();
            array_push($solPunto,$temp->Punto);
        }

        $data = array(
            'solPunto' => $solPunto,
            'solPadre' => $sol->count(),
            'model' => $model,
            'diUser' => $request->idUser,
            'msg' => $msg,
        );

        return response()->json($data);
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
        $user = Usuario::where('id', $idUser)->first();

        // $folio = $request->folioAsignar;
        // $nombres = $inge->name;
        // $muestreador = $inge->id;

        $update = SolicitudesGeneradas::where('Id_solPadre', $request->idSol)
            ->update([
                'Nombres' => $user->name,
                'Id_muestreador' => $user->id,
            ]);

        return response()->json(
            compact($update),
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
        $model = PHTrazable::where('Id_ph', $request->id)->first();
        $model2 = PHCalidad::where('Ph_calidad', $model->Ph)->first();
        return response()->json(compact('model', 'model2'));
    }
    public function getPhCalidad(Request $request)
    {
        $model = PHCalidad::where('Ph_calidad', $request->id)->first();
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

        $model = DB::table('ViewSolicitud2')->where('Id_solicitud', $id)->first();
        $direccion = "";
        $firmaRecepcion = "";

        if ($model->Siralab == 1) { //Es cliente Siralab
            $direccion = DB::table('ViewDireccionSir')->where('Id_cliente_siralab', $model->Id_direccion)->first();
            // $direccion = DireccionReporte::where('Id_direccion', $model->Id_direccion)->first();
        } else {
            $direccion = DireccionReporte::where('Id_direccion', $model->Id_direccion)->first();
        }
        $puntoMuestreo = SolicitudPuntos::where('Id_solicitud',$id)->first();

        $modelCompuesto = CampoCompuesto::where('Id_solicitud', $id)->first();

        $folio = explode("-", $model->Folio_servicio);
        $parte1 = strval($folio[0]);
        $parte2 = strval($folio[1]);

        $numOrden = Solicitud::where('Folio_servicio', $parte1 . "-" . $parte2)->first();

        $punto = DB::table('ViewPuntoGenSol')->where('Id_solicitud', $id)->first();
        $solGen = DB::table('ViewSolicitudGenerada')->where('Id_solicitud', $id)->first();

        $campoGeneral = CampoGenerales::where('Id_solicitud', $id)->first();
        $phMuestra = PhMuestra::where('Id_solicitud', $id)->get();
        $gastoMuestra = GastoMuestra::where('Id_solicitud', $id)->get();
        $tempMuestra = TemperaturaMuestra::where('Id_solicitud', $id)->get();
        $tempAmbiente = TemperaturaAmbiente::where('Id_solicitud', $id)->get();
        $conMuestra = ConductividadMuestra::where('Id_solicitud', $id)->get();
        $muestreador = Usuario::where('id', $solGen->Id_muestreador)->first();
        $swMateria = SolicitudParametro::where('Id_solicitud', $id)->where('Id_subnorma', 2)->get();
        $recepcion = SeguimientoAnalisis::where('Id_servicio', $id)->first();

        $firmaRes = DB::table('users')->where('id', $solGen->Id_muestreador)->first();

        //Recupera los parámetros de la solicitud
        $paramSolicitud = DB::table('ViewEnvaseParametroSol')->where('Id_solicitud', $id)->get();
        $paramSolicitudLength = $paramSolicitud->count();

        $areaModel = AreaLab::all();
        $procesoAnalisis = ProcesoAnalisis::where('Id_solicitud', $id)->get();
        if ($procesoAnalisis->count()) {
            $firmaRecepcion =  DB::table('users')->where('id', 31)->first();
        } else {
            $firmaRecepcion = "";
        }

        $mpdf = new \Mpdf\Mpdf([
            'format' => 'letter',
            'margin_left' => 2,
            'margin_right' => 2,
            'margin_top' => 18,
            'margin_bottom' => 13
        ]);

        $mpdf->SetWatermarkImage(
            asset('/public/storage/MembreteVertical.png'),
            1,
            array(215, 280),
            array(0, 0),
        );
        $data = array(
            'campoGeneral' => $campoGeneral,
            'solGen' => $solGen,
            'procesoAnalisis' => $procesoAnalisis,
            'firmaRecepcion' => $firmaRecepcion,
            'swMateria' => $swMateria,
            'model' => $model,
            'tempAmbiente' => $tempAmbiente,
            'modelCompuesto' => $modelCompuesto,
            'areaModel' => $areaModel,
            'numOrden' => $numOrden,
            'punto' => $punto,
            'puntoMuestreo' => $puntoMuestreo,
            'phMuestra' => $phMuestra,
            'gastoMuestra' => $gastoMuestra,
            'tempMuestra' => $tempMuestra,
            'conMuestra' => $conMuestra,
            'muestreador' => $muestreador,
            'paramSolicitudLength' => $paramSolicitudLength,
            'recepcion' => $recepcion,
            'firmaRes' => $firmaRes,
            'direccion' => $direccion,
        );
        $mpdf->showWatermarkImage = true;
        $html = view('exports.campo.hojaCampo', $data);
        $mpdf->CSSselectMedia = 'mpdf';
        $mpdf->WriteHTML($html);
        $htmlFooter = view('exports.campo.hojaCampoFooter');
        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
        $mpdf->Output();
    }

    public function hojaCampoCli($id)
    {

        $model = DB::table('ViewSolicitud')->where('Id_solicitud', $id)->first();

        $direccion = SucursalCliente::where('Id_sucursal', $model->Id_sucursal)->first();

        if ($model->Siralab == 1) { //Es cliente Siralab
            $puntoMuestreo = PuntoMuestreoSir::where('Id_sucursal', $model->Id_sucursal)->get();
            // $puntoMuestreo = SolicitudPuntos::where('Id_solicitud',$idSolicitud)->get();
            $puntos = $puntoMuestreo->count();
        } else {
            $puntoMuestreo = PuntoMuestreoGen::where('Id_sucursal', $model->Id_sucursal)->get();
            // $puntoMuestreo = SolicitudPuntos::where('Id_solicitud',$idSolicitud)->get();
            $puntos = $puntoMuestreo->count();
        }

        $modelCompuesto = CampoCompuesto::where('Id_solicitud', $id)->first();

        $folio = explode("-", $model->Folio_servicio);
        $parte1 = strval($folio[0]);
        $parte2 = strval($folio[1]);

        $numOrden = Solicitud::where('Folio_servicio', $parte1 . "-" . $parte2)->first();

        $punto = DB::table('ViewPuntoGenSol')->where('Id_solicitud', $id)->first();
        $solGen = DB::table('ViewSolicitudGenerada')->where('Id_solicitud', $id)->first();

        $phMuestra = PhMuestra::where('Id_solicitud', $id)->get();
        $gastoMuestra = GastoMuestra::where('Id_solicitud', $id)->get();
        $tempMuestra = TemperaturaMuestra::where('Id_solicitud', $id)->get();
        $conMuestra = ConductividadMuestra::where('Id_solicitud', $id)->get();
        $muestreador = Usuario::where('id', $solGen->Id_muestreador)->first();

        $recepcion = SeguimientoAnalisis::where('Id_servicio', $id)->first();

        $firmaRes = DB::table('users')->where('id', $solGen->Id_muestreador)->first();

        //Recupera los parámetros de la solicitud
        $paramSolicitud = DB::table('ViewSolicitudParametros')->where('Id_solicitud', $id)->get();
        $paramSolicitudLength = $paramSolicitud->count();
        $envasesArray = array();

        foreach ($paramSolicitud as $item) {
            $modelEnvase = DB::table('ViewEnvaseParametro')->where('Id_parametro', $item->Id_parametro)->first();

            array_push($envasesArray, $modelEnvase);
        }

        $mpdf = new \Mpdf\Mpdf([
            'format' => 'letter',
            'margin_left' => 2,
            'margin_right' => 2,
            'margin_top' => 30,
            'margin_bottom' => 18
        ]);

        $mpdf->SetWatermarkImage(
            asset('/public/storage/MembreteVertical.png'),
            1,
            array(215, 280),
            array(0, 0),
        );
        $mpdf->showWatermarkImage = true;
        $html = view('exports.campo.hojaCampo', compact('model', 'modelCompuesto', 'numOrden', 'punto', 'puntos', 'puntoMuestreo', 'phMuestra', 'gastoMuestra', 'tempMuestra', 'conMuestra', 'muestreador', 'envasesArray', 'paramSolicitudLength', 'recepcion', 'firmaRes', 'direccion'));
        $mpdf->CSSselectMedia = 'mpdf';
        $mpdf->WriteHTML($html);
        $htmlFooter = view('exports.campo.hojaCampoFooter');
        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
        $mpdf->Output('Hoja Campo.pdf', 'I');
    }

    public function bitacoraCampo($id)
    {
        $tipoReporte = "";
        $termometros = array();
        $model = DB::table('ViewSolicitud2')->where('Id_solicitud', $id)->first();

        $modelCot = Cotizacion::where('Id_cotizacion', $model->Id_cotizacion)->first();
        $idNorma = $modelCot->Id_norma;

        if ($idNorma == 1) {
            $tipoReporte = DB::table('categorias001')->where('Id_categoria', $modelCot->Tipo_reporte)->first();
        }

        $frecuenciaMuestreo = Frecuencia001::where('Id_frecuencia', $modelCot->Frecuencia_muestreo)->first();
        $solPuntos = SolicitudPuntos::where('Id_solicitud', $id)->where('Id_solPadre', '!=', '')->first();

        if ($model->Siralab == 1) { //Es cliente Siralab
            $puntoMuestreo = PuntoMuestreoSir::where('Id_punto', $solPuntos->Id_muestreo)->get();
            // $direccion = DB::table('ViewDireccionSir')->where('Id_sucursal', $model->Id_sucursal)->first();
            $puntos = $puntoMuestreo->count();
        } else {
            $puntoMuestreo = PuntoMuestreoGen::where('Id_punto', $solPuntos->Id_muestreo)->get();
            // $direccion = DireccionReporte::where('Id_sucursal', $model->Id_sucursal)->first();
            $puntos = $puntoMuestreo->count();
        }

        $punto = DB::table('ViewPuntoGenSol')->where('Id_solicitud', $id)->first();
        $solGen = DB::table('ViewSolicitudGenerada')->where('Id_solicitud', $id)->first();

        $campoGen = DB::table('ViewCampoGenerales')->where('Id_solicitud', $id)->first();
        $termometro1 = TermometroCampo::where('Id_termometro', $campoGen->Id_equipo)->first();
        $termometro2 = TermometroCampo::where('Id_termometro', $campoGen->Id_equipo2)->first();

        $tempMuestra = TemperaturaMuestra::where('Id_solicitud', $id)->get();
        $tempAmbiente = TemperaturaAmbiente::where('Id_solicitud', $id)->get();

        $factorCorreccion = TermFactorCorreccionTemp::where('Id_termometro', $campoGen->Id_equipo)->get();
        $factorCorreccion2 = TermFactorCorreccionTemp::where('Id_termometro', $campoGen->Id_equipo2)->get();

        $factCorrec = array();
        $factApl = array();
        $factCorrec2 = array();
        $factApl2 = array();
        $aux = 0;
        foreach ($tempMuestra as $item) {
            $temp = array();
            $temp = TermFactorCorreccionTemp::where('De_c', '<=', $item->Promedio)->where('A_c', '>', $item->Promedio)->where('Id_termometro', $campoGen->Id_equipo)->first();
            array_push($factCorrec, $temp->Factor);
            array_push($factApl, $temp->Factor_aplicado);
            $temp = array();
            $temp = TermFactorCorreccionTemp::where('De_c', '<=', $tempAmbiente[$aux]->Temperatura1)->where('A_c', '>', $tempAmbiente[$aux]->Temperatura1)->where('Id_termometro', $campoGen->Id_equipo2)->first();
            array_push($factCorrec2, $temp->Factor);
            array_push($factApl2, $temp->Factor_aplicado);
            $aux++;
        }



        $phMuestra = PhMuestra::where('Id_solicitud', $id)->get();

        $gastoMuestra = GastoMuestra::where('Id_solicitud', $id)->get();
        $gastoTotal = 0;

        foreach ($gastoMuestra as $item) {
            if ($item->Promedio === NULL) {
                $gastoTotal += 0;
            } else {
                $gastoTotal += $item->Promedio;
            }
        }



        $conMuestra = ConductividadMuestra::where('Id_solicitud', $id)->get();
        $muestreador = Usuario::where('id', $solGen->Id_muestreador)->first();

        // $phTrazable = CampoPhTrazable::where('Id_solicitud',$id)->get();
        $phTrazable = DB::table('ViewCampoPhTrazable')->where('Id_solicitud', $id)->get();
        $phCalidad = DB::table('ViewCampoPhCalidad')->where('Id_solicitud', $id)->get();

        $campoConTrazable = DB::table('ViewCampoConTrazable')->where('Id_solicitud', $id)->get();
        $campoConCalidad = DB::table('ViewCampoConCalidad')->where('Id_solicitud', $id)->get();

        $campoCompuesto = CampoCompuesto::where('Id_solicitud', $id)->first();
        $metodoAforo = MetodoAforo::where('Id_aforo', $campoCompuesto->Metodo_aforo)->first();
        $proceMuestreo = ProcedimientoAnalisis::where('Id_procedimiento', $campoCompuesto->Proce_muestreo)->first();
        $conTratamiento = ConTratamiento::where('Id_tratamiento', $campoCompuesto->Con_tratamiento)->first();
        $tipoTratamiento = TipoTratamiento::where('Id_tratamiento', $campoCompuesto->Tipo_tratamiento)->first();
        $campoGeneral = CampoGenerales::where('Id_solicitud', $id)->first();
        $materia = SolicitudParametro::where('Id_subnorma', 2)->where('Id_solicitud', $model->Id_solicitud)->get();

        $mpdf = new \Mpdf\Mpdf([
            'format' => 'letter',
            'margin_left' => 5,
            'margin_right' => 5,
            'margin_top' => 42,
            'margin_bottom' => 45
        ]);

        $mpdf->SetWatermarkImage(
            asset('/public/storage/MembreteVertical.png'),
            1,
            array(215, 280),
            array(0, 0),
        );

        $mpdf->showWatermarkImage = true;
        $html = view('exports.campo.bitacoraCampo', compact(
            'materia',
            'factApl',
            'factApl2',
            'factCorrec',
            'factCorrec2',
            'factorCorreccion2',
            'model',
            'tempAmbiente',
            'termometro1',
            'termometro2',
            'tipoReporte',
            'idNorma',
            'phCalidad',
            'campoConCalidad',
            'punto',
            'phMuestra',
            'gastoMuestra',
            'tipoReporte',
            'gastoTotal',
            'campoGen',
            'tempMuestra',
            'conMuestra',
            'muestreador',
            'phTrazable',
            'campoConTrazable',
            'metodoAforo',
            'proceMuestreo',
            'solGen',
            'conTratamiento',
            'tipoTratamiento',
            'campoCompuesto',
            'factorCorreccion',
            'puntoMuestreo',
            'puntos',
            'frecuenciaMuestreo'
        ));
        $mpdf->CSSselectMedia = 'mpdf';

        $htmlHeader = view('exports.campo.bitacoraCampoHeader', compact('model'));
        $mpdf->setHeader("<br><br>" . $htmlHeader);

        $htmlFooter = view('exports.campo.bitacoraCampoFooter', compact('muestreador', 'campoGeneral', 'model'));
        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');

        $mpdf->WriteHTML($html);
        $mpdf->Output();
    }

    public function planMuestreo($idSolicitud)
    {
        $model = DB::table('ViewSolicitud2')->where('Id_solicitud', $idSolicitud)->first();

        $puntoMuestreo = SolicitudPuntos::where('Id_solicitud', $idSolicitud)->get();
        $puntos = $puntoMuestreo->count();
        $mpdf = new \Mpdf\Mpdf([
            'format' => 'letter',
            'margin_left' => 20,
            'margin_right' => 20,
            'margin_top' => 35.5,
            'margin_bottom' => 18
        ]);

        $mpdf->SetWatermarkImage(
            asset('/public/storage/MembreteVertical.png'),
            1,
            array(215, 280),
            array(0, 0),
        );

        $mpdf->showWatermarkImage = true;

        //Recupera los parámetros de la solicitud
        $parametros = SolicitudParametro::where('Id_solicitud', $idSolicitud)->get();


        $complementoCampoTipo1 = DB::table('ViewPlanComplemento')->where('Id_paquete', $model->Id_subnorma)->where('Tipo', 1)->get();
        $complementoCampoTipo2 = DB::table('ViewPlanComplemento')->where('Id_paquete', $model->Id_subnorma)->where('Tipo', 2)->get();
        $complementoCampoTipo3 = DB::table('ViewPlanComplemento')->where('Id_paquete', $model->Id_subnorma)->where('Tipo', 3)->get();
        $complementoCampoTipo1Length = $complementoCampoTipo1->count();
        $complementoCampoTipo2Length = $complementoCampoTipo2->count();
        $complementoCampoTipo3Length = $complementoCampoTipo3->count();

        $paquete = DB::table('ViewPlanPaquete')->where('Id_paquete', $model->Id_subnorma)->get();
        $paqueteLength = $paquete->count();
        $areaParam = DB::table('ViewEnvaseParametroSol')->where('Id_solicitud', $idSolicitud)->get();
        $puntoMuestreo = SolicitudPuntos::where('Id_solPadre',$idSolicitud)->get();

        $area = array();
        $tempArea = array();
        $totalArea = array();
        $volumentEnva = array();
        $sw = false;
        $aux = 0;
        foreach ($areaParam as $item) {
            $sw = false;
            $aux = 0;
            for ($i = 0; $i < sizeof($tempArea); $i++) {
                if ($item->Id_area == $tempArea[$i]) {
                    $sw = true;
                }
            }
            if ($sw != true) {

                switch ($item->Id_areaAnalisis) {
                    case 3:
                    case 6:
                    case 13: 
                    case 12:
                        switch ($item->Id_parametro) {
                            case 16:
                            case 81:
                                $aux = $puntoMuestreo->count(); 
                                break;
                            
                            default:
                                $aux = $model->Num_tomas * $puntoMuestreo->count();
                                break;
                        }
                        break;
                    
                    default: 
                    $aux = $puntoMuestreo->count(); 
                        break;
                }

                array_push($volumentEnva, $item->Nombre ." ". $item->Volumen ." ".$item->UniEnv);
                array_push($totalArea, $aux);
                array_push($tempArea, $item->Id_area);
                array_push($area, $item->Area);
            }
        }



        //Obtiene los parámetros de esta solicitud
        $parametrosSolicitud = DB::table('ViewSolicitudParametros')->where('Id_solicitud', $idSolicitud)->get();
        $parametrosSolicitudLength = $parametrosSolicitud->count();

        //Obtiene las preservaciones de los parámetros
        $preservacionesArray = array();

        foreach ($parametrosSolicitud as $item) {
            $preservacionParametro = DB::table('ViewEnvaseParametro')->where('Id_parametro', $item->Id_parametro)->first();

            if (!is_null($preservacionParametro)) {
                if (!in_array($preservacionParametro->Preservacion, $preservacionesArray)) {
                    array_push(
                        $preservacionesArray,
                        $preservacionParametro->Preservacion
                    );
                }
            }
        }


        $preservacionesArrayLength = sizeof($preservacionesArray);

        $html = view('exports.campo.planMuestreo.bodyPlanMuestreo', compact(
            'parametros',
            'area',
            'tempArea',
            'puntoMuestreo',
            'totalArea',
            'volumentEnva',
            'complementoCampoTipo1',
            'complementoCampoTipo2',
            'complementoCampoTipo3',
            'complementoCampoTipo1Length',
            'complementoCampoTipo2Length',
            'complementoCampoTipo3Length',
            'paquete',
            'paqueteLength',
            'model',
            'preservacionesArray',
            'preservacionesArrayLength',
            'puntos'
        ));
        $htmlHeader = view('exports.campo.planMuestreo.headerPlanMuestreo', compact('model', 'puntos','puntoMuestreo'));
        $htmlFooter = view('exports.campo.planMuestreo.footerPlanMuestreo');

        $mpdf->CSSselectMedia = 'mpdf';

        $mpdf->setHeader("<br><br>" . $htmlHeader);
        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
        $mpdf->WriteHTML($html);

        $mpdf->Output();
    }
    //todo ******************************************************
    //todo Inicio de configuracio plan de muestreo
    //todo ******************************************************
    public function configPlan()
    {
        return view('campo.configuracionPlan');
    }
    public function getPaquetes(Request $res)
    {
        $model = SubNorma::all();
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function getEnvase(Request $res)
    {
        $model = DB::table('ViewPlanPaquete')->where('Id_paquete', $res->idPaquete)->get();
        $parametro = Parametro::all();
        $data = array(
            'parametro' => $parametro,
            'model' => $model,
        );
        return response()->json($data);
    }
    public function getPlanMuestreo(Request $res)
    {
        $model = AreaLab::all();
        //$envase = Envase::all();
        $parametro = Parametro::all();
        $envase = DB::table('ViewEnvases')->get();
        $datoModel = DB::table('ViewPlanPaquete')->where('Id_paquete', $res->idSub)->get();

        $data = array(
            'parametro' => $parametro,
            'model' => $model,
            'envase' => $envase,
            'datoModel' => $datoModel,
        );
        return response()->json($data);
    }
    public function setPlanMuestreo(Request $res)
    {
        $model = DB::table('plan_paquete')->where('Id_paquete', $res->idSub)->delete();
        for ($i = 0; $i < sizeof($res->areas); $i++) {
            PlanPaquete::create([
                'Id_paquete' => $res->idSub,
                'Id_area' => $res->areas[$i],
                'Id_recipiente' => $res->envase[$i],
                'Cantidad' => $res->cantidad[$i],
            ]);
        }
        $model = PlanPaquete::where('Id_paquete', $res->idSub)->get();
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function getMaterial(Request $res)
    {
        $model = DB::table('ViewPlanComplemento')->where('Id_paquete', $res->idSub)->where('Tipo', 1)->get();

        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function getEquipo(Request $res)
    {
        $model = DB::table('ViewPlanComplemento')->where('Id_paquete', $res->idSub)->where('Tipo', 2)->get();

        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function getComplementoCamp(Request $res)
    {
        $model = DB::table('ViewPlanComplemento')->where('Id_paquete', $res->idSub)->where('Tipo', 3)->get();

        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function getComplemento(Request $res)
    {
        $model = ComplementoCampo::where('Tipo', $res->tipo)->get();
        $comModel = PlanComplemento::where('Id_paquete', $res->idSub)->get();

        $data = array(
            'model' => $model,
            'comModel' => $comModel,
        );
        return response()->json($data);
    }
    public function setComplemento(Request $res)
    {
        $model = DB::table('plan_complemento')->where('Id_paquete', $res->idSub)->where('Tipo', $res->tipo)->delete();
        for ($i = 0; $i < sizeof($res->complemento); $i++) {
            PlanComplemento::create([
                'Id_paquete' => $res->idSub,
                'Id_complemento' => $res->complemento[$i],
                'Tipo' => $res->tipo,
            ]);
        }
        $model = PlanComplemento::where('Id_paquete', $res->idSub)->get();
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }


    //todo ******************************************************
    //todo Fin de configuracio plan de muestreo
    //todo ******************************************************
}
