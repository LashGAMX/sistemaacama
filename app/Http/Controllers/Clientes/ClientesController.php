<?php

namespace App\Http\Controllers\Clientes;

use App\Http\Controllers\Controller;
use App\Models\Clientes;
use App\Models\SucursalCliente;
use App\Models\ClienteGeneral;
use App\Models\RfcSucursal;
use App\Models\DireccionReporte;
use App\Models\PuntoMuestreoGen;
use App\Models\ClienteSiralab;
use App\Models\TituloConsecionSir;
use App\Models\PuntoMuestreoSir;
use App\Models\SucursalContactos;
use Illuminate\Http\Request;
use App\Http\Controllers\Clientes\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use ReturnTypeWillChange;

class ClientesController extends Controller
{
    public $perPage = 500;
    public $idCliente;
    public $Search = '';


    public function getClientesGen()
    {
        $clienteGen = DB::table('Viewgenerales')->get();
        $data = array(
            'clienteGen' => $clienteGen
        );
        return response()->json($data);
    }

    public function setClientesGen(Request $res)
    {
        $clientesCreado = Clientes::create([
            'Nombres' => $res->nombres,
            'Id_tipo_cliente' => 2,
            'Id_user_c' => Auth::user()->id,
            'Id_user_m' => Auth::user()->id
        ]);

        $clienteGeneralCreado = ClienteGeneral::create([
            'Id_cliente' => $clientesCreado->Id_cliente,
            // 'Nombre' => $res->nombres,
            'Empresa' => $res->nombres,
            'Id_intermediario' => $res->idIntermediario,
            'Id_user_c' => Auth::user()->id,
            'Id_user_m' => Auth::user()->id
        ]);

        if ($res->activoCheck == "false") {
            $clientesCreado->delete();
            $clienteGeneralCreado->delete();
        }

        $data = array(
            'clientesCreado' => $clientesCreado,
            'clienteGeneralCreado' => $clienteGeneralCreado
        );

        return response()->json($data);
    }

    public function upClientesGen(Request $res)
    {
        $clientesModificar = Clientes::withTrashed()->find($res->idCliente);
        $clienteGeneralModificar = ClienteGeneral::withTrashed()->where('Id_cliente', $clientesModificar->Id_cliente)->first();

        $clientesModificar->Nombres = $res->nombres;
        $clientesModificar->Id_user_m = Auth::user()->id;
        $clienteGeneralModificar->Empresa = $res->nombres;
        $clienteGeneralModificar->Id_intermediario = $res->idIntermediario;
        $clienteGeneralModificar->Id_user_m = Auth::user()->id;
        $clientesModificar->save();
        $clienteGeneralModificar->save();

        if ($res->activoCheckEditar == "false") {
            $clientesModificar->delete();
            $clienteGeneralModificar->delete();
        } elseif ($clientesModificar->deleted_at != null) { //&& $clienteGeneralModificar->deleted_at != null){
            $clientesModificar->restore();
            $clienteGeneralModificar->restore();
        }

        $data = array(
            'clientesModificar' => $clientesModificar,
            'clienteGeneralModificar' => $clienteGeneralModificar
        );

        return response()->json($data);
    }

    public function clientesGenDetalle($id)
    {
        $clienteGen = DB::table('Viewgenerales')->where('Id_cliente', $id)->first();
        return view('clientes.clientesGenDetalle', compact('clienteGen'));
    }

    public function clientesGen()
    {
        return view('clientes.clientesgen');
    }

    public function TablaSucursal($idCliente)
    {
        $model = SucursalCliente::withTrashed()
            ->where('Id_cliente', $idCliente)
            ->orderBy('Id_sucursal', 'desc')
            ->paginate($this->perPage);
        return response()->json(['datos' => $model->items()]);
    }
    public function Consuc($idSucursal)
    {
        $sucursal = SucursalCliente::withTrashed()
            ->where('Id_sucursal', $idSucursal)
            ->first();

        if ($sucursal) {
            return response()->json([
                'datos' => [
                    'Empresa' => $sucursal->Empresa,
                    'Estado' => $sucursal->Estado,
                    'Id_siralab' => $sucursal->Id_siralab,
                    'deleted_at' => $sucursal->deleted_at,
                ]
            ]);
        } else {
            return response()->json(['error' => 'Sucursal no encontrada'], 404);
        }
    }

    public function Nombrematrix($idCliente)
    {
        $matriz = Clientes::where('Id_cliente', $idCliente)->select('Nombres')->first();

        if ($matriz) {
            return response()->json($matriz);
        } else {
            return response()->json(['error' => 'Cliente no encontrado'], 404);
        }
    }

    public function EditarSuc(Request $request)
    {
        $idSucursal = $request->input('idSucursal');

        $validatedData = $request->validate([
            'idSucursal' => 'required|integer',
            'empresa' => 'required|string|max:255',
            'estado' => 'required|string|max:255',
            'tipo' => 'required|integer',
            'deleted_at' => 'nullable|date',
        ]);


        $sucursal = SucursalCliente::withTrashed()->find($idSucursal);

        if ($sucursal) {
            if ($sucursal->trashed() && $validatedData['deleted_at'] !== null) {
                $sucursal->restore();
            }

            $sucursal->Empresa = $validatedData['empresa'];
            $sucursal->Estado = $validatedData['estado'];
            $sucursal->Id_siralab = $validatedData['tipo'];
            $sucursal->Id_user_m = $request->user()->id;
            $sucursal->deleted_at = $validatedData['deleted_at'] ? \Carbon\Carbon::parse($validatedData['deleted_at']) : null;
            $sucursal->save();

            if ($validatedData['deleted_at'] !== null) {
                $sucursal->delete();
            }

            return response()->json(['success' => true, 'message' => 'Datos modificados']);
        } else {
            return response()->json(['success' => false, 'message' => 'Registro no encontrado'], 404);
        }
    }

    public function CrearSuc(Request $res)
    {
        $validated = $res->validate([
            'idCliente' => 'required|integer|exists:clientes,id',
            'empresa' => 'required|string|max:255',
            'estado' => 'required|string',
            'tipo' => 'required|integer',
            'deleted_at' => 'nullable|date',
            'idUser' => 'integer|exists:users,id',
        ]);
        return response()->json(['success' => false, 'message' => 'Error al Crear Sucursal']);

        $sucursal = SucursalCliente::create([
            'Id_cliente' => $res->idCliente,
            'Empresa' => $res->empresa,
            'Estado' => $res->estado,
            'Id_siralab' => $res->tipo,
            'Id_user_c' => $res->idUser,
            'deleted_at' => $res->deleted_at,
        ]);

        return response()->json(['success' => true, 'message' => 'Sucursal Creada']);
    }
    public function TablaRFC(Request $res)
    {
        $model = RfcSucursal::withTrashed()
            ->where('Id_sucursal', $res->idSucursal)
            ->orderBy('Id_sucursal', 'desc')
            ->paginate($this->perPage);
        return response()->json(['datos' => $model->items()]);
    }

    public function TablaDirReport(Request $res)
    {
        $model = DireccionReporte::withTrashed()
            ->where('Id_sucursal', $res->idSucursal)
            ->orderBy('Id_sucursal', 'desc')
            ->paginate($this->perPage);
        return response()->json(['datos' => $model->items()]);
    }

    public function TablaPM(Request $res)
    {
        $model = PuntoMuestreoGen::withTrashed()
            ->where('Id_sucursal', $res->idSucursal)
            ->orderBy('Id_sucursal', 'desc')
            ->paginate($this->perPage);
        return response()->json(['datos' => $model->items()]);
    }

    public function TablaConcesión(Request $res)
    {
        $model = TituloConsecionSir::withTrashed()
            ->where('Id_sucursal', $res->idSucursal)
            ->orderBy('Id_sucursal', 'desc')
            ->paginate($this->perPage);
        return response()->json(['datos' => $model->items()]);
    }

    public function TablaDireccionSiralab(Request $res)
    {
        $model = ClienteSiralab::withTrashed()
            ->where('Id_sucursal', $res->idSucursal)
            ->orderBy('Id_sucursal', 'desc')
            ->paginate($this->perPage);
        return response()->json(['datos' => $model->items()]);
    }

    public function TablePuntoSiralab(Request $res)
    {
        $model = PuntoMuestreoSir::withTrashed()
            ->where('Id_sucursal', $res->idSucursal)
            ->orderBy('Id_sucursal', 'desc')
            ->paginate($this->perPage);
        return response()->json(['datos' => $model->items()]);
    }
    public function GetRFCDetails(Request $request, $id)
    {
        $rfc = RfcSucursal::withTrashed()->find($id);

        if ($rfc) {
            return response()->json(['data' => [
                'RFC' => $rfc->RFC,
                'status' => $rfc->deleted_at === null
            ]]);
        } else {
            return response()->json(['error' => 'RFC no encontrado'], 404);
        }
    }
    public function GetDirDetails(Request $res, $id)
    {
        $direc = DireccionReporte::withTrashed()->find($id);

        if ($direc) {
            return response()->json(['datos' => [
                'Direccion' => $direc->Direccion,
                'status' => $direc->deleted_at === null
            ]]);
            return response()->json(['success' => 'Consulta correcta de datos']);
        } else {
            return response()->json(['error' => 'Direccion no encontrada'], 400);
        }
    }

    public function GetPunDetails(Request $res, $id)
    {
        $punto = PuntoMuestreoGen::withTrashed()->find($id);

        if ($punto) {
            return response()->json(['datos' => [
                'punto' => $punto->Punto_muestreo,
                'status' => $punto->deleted_at === null
            ]]);
        } else {
            return response()->json(['error' => 'Punto no encontrado'], 400);
        }
    }
    public function GetTituloDetails(Request $res, $id)
    {
        $titulo = TituloConsecionSir::withTrashed()->find($id);

        if ($titulo) {
            return response()->json(['datos' => [
                'titulo' => $titulo->Titulo,
                'status' => $titulo->deleted_at === null
            ]]);
        } else {
            return response()->json(['error' => 'Punto no encontrado'], 400);
        }
    }
    public function GetDirSiralbDetails(Request $res, $id)
    {
        // Busca el registro basado en el campo 'Titulo_concesion'
        $DirRepSir = ClienteSiralab::withTrashed()->where('Titulo_concesion', $id)->first();

        if ($DirRepSir) {
            return response()->json([
                'datos' => [
                    'Titulo' => $DirRepSir->Titulo_concesion, // Cambia 'notitulo' por 'Titulo' si eso es lo que esperas
                    'Calle' => $DirRepSir->Calle,
                    'Noext' => $DirRepSir->Num_exterior,
                    'Noint' => $DirRepSir->Num_interior,
                    'Estado' => $DirRepSir->Estado,
                    'Colonia' => $DirRepSir->Colonia,
                    'Cp' => $DirRepSir->CP,
                    'Ciudad' => $DirRepSir->Ciudad,
                    'Localidad' => $DirRepSir->Localidad,
                    'Status' => $DirRepSir->deleted_at === null
                ]
            ]);
        } else {
            return response()->json(['Error' => 'Dirección Siralab no encontrada']);
        }
    }
    public function GetDatosGen($id)
{
    $contacto = SucursalContactos::withTrashed()->where('Id_contacto', $id)->first();
    if ($contacto) {
        return response()->json([
            'dato' => [
                'numero' => $contacto->Id_contacto,
                'nombre' => $contacto->Nombre,
                'departamento' => $contacto->Departamento,
                'puesto' => $contacto->Puesto,
                'email' => $contacto->Email,
                'celular' => $contacto->Celular,
                'telefono' => $contacto->Telefono,
            ]
        ]);
    } else {
        return response()->json(['error' => 'Datos generales no encontrados.']);
    }
}




    public function UpdateRFC(Request $request)
    {
        // Obténgo  los datos JSON directamente
        $validated = $request->validate([
            'id_rfc' => 'required|exists:rfc_sucursal,id_rfc',
            'rfc' => 'required|string|max:255',
            'status' => 'nullable|boolean'
        ]);

        $idRfc = $validated['id_rfc'];
        $newRfc = $validated['rfc'];
        $status = $validated['status'];

        $rfc = RfcSucursal::withTrashed()->find($idRfc);

        if ($rfc) {
            $rfc->RFC = $newRfc;
            $rfc->deleted_at = ($status === null) ? $rfc->deleted_at : ($status ? null : now());

            // Guarda los cambios
            $rfc->save();

            return response()->json(['success' => true]);
        } else {
            return response()->json(['error' => 'RFC no encontrado'], 404);
        }
    }

    public function UpdateDIRrep(Request $request)
    {


        // Validar los datos de la solicitud
        $validated = $request->validate([
            'direccion' => 'required|exists:direccion_reporte,Id_direccion',
            'newdir' => 'required|string|max:255',
            'status' => 'nullable|boolean'
        ]);

        $id = $validated['direccion'];
        $newdir = $validated['newdir'];
        $status = $validated['status'];

        // Buscar el registro
        $dir = DireccionReporte::withTrashed()->find($id);

        if ($dir) {
            // Actualizar los datos
            $dir->Direccion = $newdir;
            $dir->deleted_at = ($status === null) ? $dir->deleted_at : ($status ? null : now());
            $dir->save();
            return response()->json(['success' => true]);
        } else {
            return response()->json(['error' => 'Dirección no encontrada'], 404);
        }
    }

    public function UpdatePunto(Request $request)
    {


        // Validar los datos de la solicitud
        $validated = $request->validate([
            'punto1' => 'required|exists:puntos_muestreogen,Id_punto',
            'newpunto' => 'required|string|max:255',
            'status' => 'nullable|boolean'
        ]);

        $id = $validated['punto1'];
        $newpunto = $validated['newpunto'];
        $status = $validated['status'];

        // Buscar el registro
        $Pun = PuntoMuestreoGen::withTrashed()->find($id);
        if ($Pun) {
            // Actualizar los datos
            $Pun->deleted_at = ($status === null) ? $Pun->deleted_at : ($status ? null : now());
            $Pun->Punto_muestreo = $newpunto;
            $Pun->save();
            return response()->json(['success' => true]);
        } else {
            return response()->json(['error' => 'Punto no encontrada'], 404);
        }
    }

    public function UpdateTitulo(Request $request)
    {
        // Validar los datos de la solicitud
        $validated = $request->validate([
            'titulo' => 'required|exists:titulo_concesion_sir,Id_titulo',
            'newtitulo' => 'required|string|max:255',
            'status' => 'nullable|boolean'
        ]);

        $id = $validated['titulo'];
        $newtitulo = $validated['newtitulo'];
        $status = $validated['status'];

        // Buscar el registro
        $titulo = TituloConsecionSir::withTrashed()->find($id);
        if ($titulo) {
            // Actualizar los datos
            $titulo->deleted_at = ($status === null) ? $titulo->deleted_at : ($status ? null : now());
            $titulo->Titulo = $newtitulo;
            $titulo->save();
            return response()->json(['success' => true]);
        } else {
            return response()->json(['error' => 'Punto no encontrada'], 404);
        }
    }
    public function UpdateTituloRepSir(Request $res)
    {

        //$datos = $res->all();
        $update = ClienteSiralab::withTrashed()->where('Titulo_concesion', $res->Titulo)->first();
        if ($update) {
            $update->Titulo_concesion = $res->Titulo;
            $update->Calle = $res->Calle;
            $update->Num_exterior = $res->NumeroExterior;
            $update->Num_interior = $res->NumeroInterior;
            $update->Estado = $res->Estado;
            $update->Colonia = $res->Colonia;
            $update->CP =  $res->CP;
            $update->Localidad = $res->Localidad;
            $status = filter_var($res->Status, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            $update->deleted_at = $status ? null : now();

            $update->save();
            return response()->json(['success' => true]);
        } else {
            return response()->json(['error' => 'Punto no encontrada'], 404);
        }
    }
    public function CrearDirRepSir(Request $res)
    {
        $validar =  $res->validate([
            'Titulo' => 'required|integer',
            'Calle' => 'requiered|string',
            'NumeroExterior' => 'required|integer',
            'NumeroInterior' => 'required|integer',
            'Calle' => 'required|string',
            'CP' => 'required|string',
            'Ciudad' => 'required|string',
            'Estado' => 'nullable|string',

            'Localidad' => 'required|string',
            'Status' => 'required|in:0,1',
            'Id_sucursal' => 'required|integer',
        ]);

        $DirRepSir = new ClienteSiralab();
        $DirRepSir->Titulo_concesion = $validar['Titulo'];
        $DirRepSir->Calle = $validar['Calle'];
        $DirRepSir->Num_exterior = $validar['NumeroExterior'];
        $DirRepSir->Num_interior = $validar['NumeroInterior'];
        $DirRepSir->Estado = $validar['Estado'];
        $DirRepSir->CP = $validar['CP'];
        $DirRepSir->Ciudad = $validar['Ciudad'];
        $DirRepSir->Localidad = $validar['Localidad'];
        $DirRepSir->Id_sucursal = $validar['Id_sucursal'];
        $DirRepSir->deleted_at = $validar['Status'] == 1 ? null : now();
        if ($DirRepSir->save()) {
            return response()->json(['success' => true, 'message' => 'Direccion creada con éxito.']);
        } else {
            return response()->json(['success' => false, 'message' => 'Error al crear la dirección.']);
        }
    }

    public function NuevoRFC(Request $request)
    {
        // Validar los datos recibidos
        $validatedData = $request->validate([
            'rfc' => 'required|string|max:13',
            'status' => 'required|boolean',
            'idSucursal' => 'required|integer'
        ]);

        $rfcSucursal = new RfcSucursal();
        $rfcSucursal->RFC = $validatedData['rfc'];
        $rfcSucursal->Id_sucursal = $validatedData['idSucursal'];
        $rfcSucursal->Id_user_c = auth()->id();
        $rfcSucursal->Id_user_m = null;
        $rfcSucursal->deleted_at = $validatedData['status'] ? null : now();

        // Guardar el modelo en la base de datos
        if ($rfcSucursal->save()) {
            return response()->json(['success' => true, 'message' => 'RFC creado con éxito.']);
        } else {
            return response()->json(['success' => false, 'message' => 'Error al crear el RFC.']);
        }
    }
    public function NuevaDireccion(Request $request)
    {
        // Validar los datos recibidos
        $validatedData = $request->validate([
            'direccion' => 'required|string',
            'status' => 'required|boolean',
            'idSucursal' => 'required|integer'
        ]);

        $direc = new DireccionReporte();
        $direc->Direccion = $validatedData['direccion'];
        $direc->Id_sucursal = $validatedData['idSucursal'];
        $direc->Id_user_c = auth()->id();
        $direc->Id_user_m = null;
        $direc->deleted_at = $validatedData['status'] ? null : now();

        // Guardar el modelo en la base de datos
        if ($direc->save()) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }
    public function NuevoPunto(Request $request)
    {
        // Validar los datos recibidos
        $validatedData = $request->validate([
            'punto' => 'required|string',
            'status' => 'required|boolean',
            'idSucursal' => 'required|integer'
        ]);

        $punto = new PuntoMuestreoGen();
        $punto->Punto_muestreo = $validatedData['punto'];
        $punto->Id_user_c = auth()->id();
        $punto->Id_user_m = null;
        $punto->deleted_at = $validatedData['status'] ? null : now();
        $punto->Id_sucursal = $validatedData['idSucursal'];

        // Guardar el modelo en la base de datos
        if ($punto->save()) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }
    public function NuevoTConcesion(Request $request)
    {
        // Validar los datos recibidos
        $validatedData = $request->validate([
            'titulo' => 'required|string',
            'status' => 'required|boolean',
            'idSucursal' => 'required|integer'
        ]);

        $titulo = new TituloConsecionSir();
        $titulo->Titulo = $validatedData['titulo'];
        $titulo->Id_user_c = auth()->id();
        $titulo->Id_user_m = null;
        $titulo->deleted_at = $validatedData['status'] ? null : now();
        $titulo->Id_sucursal = $validatedData['idSucursal'];

        // Guardar el modelo en la base de datos
        if ($titulo->save()) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }
    public function TablaDatosGen($idSucursal)
    {
        $model = SucursalContactos::where('Id_sucursal', $idSucursal)
            ->orderBy('Id_sucursal', 'desc')
            ->get();
        return response()->json(['datos' => $model]);
    }
    public function EdiDatos(Request $res) {

    
        // Validar los datos recibidos
        $validatedData = $res->validate([
            'id' => 'required|integer|exists:sucursal_contactos,Id_contacto',
            'nombre' => 'required|string|max:255',
            'departamento' => 'required|string|max:255',
            'puesto' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'celular' => 'required|string|max:15', // Ajusta según el formato de tu celular
            'telefono' => 'required|string|max:15', // Ajusta según el formato de tu teléfono
        ]);
    
        // Recuperar el contacto
        $contacto = SucursalContactos::withTrashed()->where('Id_contacto', $validatedData['id'])->first();
    
        // Verificar si se encontró el contacto
        if (!$contacto) {
            return response()->json(['success' => false, 'message' => 'Contacto no encontrado.'], 404);
        }
    
        // Actualizar los campos
        $contacto->nombre = $validatedData['nombre'];
        $contacto->departamento = $validatedData['departamento'];
        $contacto->puesto = $validatedData['puesto'];
        $contacto->email = $validatedData['email'];
        $contacto->celular = $validatedData['celular'];
        $contacto->telefono = $validatedData['telefono'];
    
        // Guardar los cambios
        if ($contacto->save()) {
            return response()->json(['success' => true, 'message' => 'Datos actualizados correctamente.']);
        } else {
            return response()->json(['success' => false, 'message' => 'Error al actualizar los datos.'], 500);
        }
    }
    
    
}
