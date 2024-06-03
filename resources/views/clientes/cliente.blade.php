@extends('voyager::master')

@section('content')

@section('page_header')
{{--Aqui empeiza el boton para crear un nuevo cliente--}}
<button class="btn btn-success btn-sm" data-toggle="modal" data-target="#modalCliente" id="botonModal"><i class="voyager-plus"></i> Crear</button>
        <div class="modal fade" id="modalCliente" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Crear intermediario</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="activoCheck" checked>
                                <label class="form-check-label" for="defaultCheck1">
                                    Activo
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="nombreClienteCrear">Cliente</label>
                            <input type="text" class="form-control" id="nombreClienteCrear" placeholder="cliente">
                        </div>
                        <div class="form-group">
                            <label for="intermediarioSelectCrear">Intermediario</label>
                            <select class="form-control" name="intermediarioSelectCrear" id="intermediarioSelectCrear" style="width: 100%">
                                <option value="0">Sin seleccionar</option> 
                                <option value="56">AGUSTIN QUINTOS AQUAQUINTOS</option>
                                <option value="76">ALEJANDRA ALBORES ALFARO</option>
                                <option value="34">ALEJANDRO  DZUL ENFOQUE ANALITICO</option>
                                <option value="38">ALTA INGENIERIA DE ESTANDARES AMBIENTALES, S.A. DE C.V. MARCO ANTONIO CARRERO </option>
                                <option value="35">ALTA INGENIERIA DE ESTANDARES AMBIENTALES, SA. DE C.V. MARCO ANTONIO CARRO INGENIERO</option>
                                <option value="36">ALTA INGENIERIA DE ESTANDARES AMBIENTALES, SA. DE C.V. MARCO ANTONIO CARRO INGENIERO</option>
                                <option value="37">ALTA INGENIERIA DE ESTANDARES AMBIENTALES, SA. DE C.V. MARCO ANTONIO CARRO INGENIERO</option>
                                <option value="7">AMADO OVANDO SANCHEZ </option>
                                <option value="80">ANDRES  HERNANDEZ</option>
                                <option value="1">ANER GARCIA SANCHEZ </option>
                                <option value="24">ARMANDO LARA</option>
                                <option value="31">ARMANDO  LARA</option>
                                <option value="19">AZUL Y VERDE AMBIENTAL </option>
                                <option value="50">BIOTASK SYBELLE</option>
                                <option value="77">CARLOS  MACOTO</option>
                                <option value="41">CENTRO COMERCIAL ANGELOPOLIS</option>
                                <option value="22">CLAUDIA ACEVEDO</option>
                                <option value="71">COMELESA *</option>
                                <option value="58">CONCRETO SUPERIOR, S.A. DE C.V.</option>
                                <option value="66">CONSORCIO TITANIC, S.A. DE C.V.</option>
                                <option value="60">CONSTRUCTORA ALARIFF, S.A. DE C.V. PABLO DIAZ</option>
                                <option value="11">CONSULTORIA AMBIENTAL Y LABORAL S.A. DE C.V. </option>
                                <option value="64">CORPORATIVO BTO</option>
                                <option value="9">CORPORATIVO AQUAQUINTOS S.A. DE C.V. </option>
                                <option value="26">DANIEL GONZALEZ SEBASTIA</option>
                                <option value="44">DAVID HERRADA</option>
                                <option value="8">DAVID DELFINO  HERRADA</option>
                                <option value="32">E &amp; R SOLUCIONES HIDRAULICAS</option>
                                <option value="46">EICO INGENIERIA</option>
                                <option value="30">ELENA  ZENTENO</option>
                                <option value="3">ENFOQUE ANALITICO INTEGRAL S.A. DE C.V. </option>
                                <option value="68">FABIAN PINTO</option>
                                <option value="55">FERNANDO CHAMORRO </option>
                                <option value="78">FLORENTINO ARENAS</option>
                                <option value="45">FRANCISCO ALEJANDRO PECH MOO</option>
                                <option value="61">GENARO MAQUEDA ALPIZAR SERVICIO DE INGENIERIA</option>
                                <option value="40">GERARDO PIÑA</option>
                                <option value="72">GISEL HERRADA</option>
                                <option value="79">GRUPO GEMMA</option>
                                <option value="6">GRUPO EMPRESARIAL GONZALEZ EBENHOCH S.A. DE C.V. </option>
                                <option value="5">HILDA CABA</option>
                                <option value="82">HUGO SANCHEZ</option>
                                <option value="25">HYDROCONTROL HUGO CONSTANTINO</option>
                                <option value="33">ING GODO</option>
                                <option value="54">ING.ISAIAS MORALES OTERO</option>
                                <option value="17">INTEMA, S.A. DE C.V. </option>
                                <option value="53">IRENE HERNANDEZ SALVADOR SAMBRANO</option>
                                <option value="12">JAIME RANGEL ESPEJO </option>
                                <option value="69">JOSE GUADALUPE VALDERRABANO</option>
                                <option value="65">JOSE RAMON TEPOX (ANTES AMADO)</option>
                                <option value="47">JUVENCIO MONROY</option>
                                <option value="21">LABACAMA  *</option>
                                <option value="62">LABCIISA / ICA  /  TREN MAYA</option>
                                <option value="70">LABORATORIO D'AMCO</option>
                                <option value="20">LUIS GERARDO PIÑA</option>
                                <option value="81">LUIS JAVIER LOPEZ</option>
                                <option value="59">MANUEL GALINDO</option>
                                <option value="13">MARCO ANTONIO CASTAÑON  </option>
                                <option value="67">MARIA HERRADA</option>
                                <option value="43">MARY MEDRANO</option>
                                <option value="73">MINKES *</option>
                                <option value="18">PARTNER </option>
                                <option value="14">PATRICIA HERNANDEZ  </option>
                                <option value="2">PETRONA CONTROL QUIMICO </option>
                                <option value="28">PROINSA *</option>
                                <option value="10">RAFAEL QUINTOS RODRIGUEZ </option>
                                <option value="48">RAUL REYES PINEDA</option>
                                <option value="63">RAUL GORDILLO</option>
                                <option value="16">RAUL LORENZO GORDILLO </option>
                                <option value="39">RODOLFO RAMIREZ</option>
                                <option value="4">RODRIGO LOPEZ</option>
                                <option value="29">ROSALINDA CUELLAR</option>
                                <option value="23">SANIDAD VEGETAL</option>
                                <option value="57">SERVICIOS DE INGENIERIA Y CONSTRUCCION AMBIENTAL, SA. DE CV. ING. JAIME VIDAL</option>
                                <option value="15">SICA (JAIME VIDAL) </option>
                                <option value="74">SIGA VERDE</option>
                                <option value="49">SISTEMAS DE INGENIERIA AMBIENTAL S.A. DE C.V.</option>
                                <option value="75">SR. IBARRA.  *</option>
                                <option value="52">TERESITA JIMENEZ SALGADO BUAP</option>
                                <option value="42">VERONICA  CONTRERAS</option>
                                <option value="27">VICTOR LEON</option>
                                <option value="51">VICTOR CAPORAL</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal" id="crearClienteGen">Guardar cambios</button>
                    </div>
                </div>
            </div>
        </div>

{{--Aqui empieza la tabla de Clientes--}}
    <div class="col-md-12">
     <div id="divclientesGen">
        <table id="tablaClientesGen" class="display compact cell-border" style="width:100%">
        </table>
     </div>
    </div>
    {{--Aqui Empieza el Modal de Edición del Cliente --}}
    <div class="modal fade" id="modalEditar" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="flase">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Editar intermediario</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="activoCheckEditar" checked>
                                <label class="form-check-label" for="defaultCheck1">
                                    Activo
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="nombreClienteCrear">Cliente</label>
                            <input type="text" class="form-control" id="nombreClienteEditar" placeholder="cliente">
                        </div>
                        <div class="form-group">
                            <label for="intermediarioSelectCrear">Intermediario</label>
                            <select class="form-control" name="intermediarioSelectEditar" id="intermediarioSelectEditar" style="width: 100%">
                                <option value="0">Sin seleccionar</option> 
                                <option value="56">AGUSTIN QUINTOS AQUAQUINTOS</option>
                                <option value="76">ALEJANDRA ALBORES ALFARO</option>
                                <option value="34">ALEJANDRO  DZUL ENFOQUE ANALITICO</option>
                                <option value="38">ALTA INGENIERIA DE ESTANDARES AMBIENTALES, S.A. DE C.V. MARCO ANTONIO CARRERO </option>
                                <option value="35">ALTA INGENIERIA DE ESTANDARES AMBIENTALES, SA. DE C.V. MARCO ANTONIO CARRO INGENIERO</option>
                                <option value="36">ALTA INGENIERIA DE ESTANDARES AMBIENTALES, SA. DE C.V. MARCO ANTONIO CARRO INGENIERO</option>
                                <option value="37">ALTA INGENIERIA DE ESTANDARES AMBIENTALES, SA. DE C.V. MARCO ANTONIO CARRO INGENIERO</option>
                                <option value="7">AMADO OVANDO SANCHEZ </option>
                                <option value="80">ANDRES  HERNANDEZ</option>
                                <option value="1">ANER GARCIA SANCHEZ </option>
                                <option value="24">ARMANDO LARA</option>
                                <option value="31">ARMANDO  LARA</option>
                                <option value="19">AZUL Y VERDE AMBIENTAL </option>
                                <option value="50">BIOTASK SYBELLE</option>
                                <option value="77">CARLOS  MACOTO</option>
                                <option value="41">CENTRO COMERCIAL ANGELOPOLIS</option>
                                <option value="22">CLAUDIA ACEVEDO</option>
                                <option value="71">COMELESA *</option>
                                <option value="58">CONCRETO SUPERIOR, S.A. DE C.V.</option>
                                <option value="66">CONSORCIO TITANIC, S.A. DE C.V.</option>
                                <option value="60">CONSTRUCTORA ALARIFF, S.A. DE C.V. PABLO DIAZ</option>
                                <option value="11">CONSULTORIA AMBIENTAL Y LABORAL S.A. DE C.V. </option>
                                <option value="64">CORPORATIVO BTO</option>
                                <option value="9">CORPORATIVO AQUAQUINTOS S.A. DE C.V. </option>
                                <option value="26">DANIEL GONZALEZ SEBASTIA</option>
                                <option value="44">DAVID HERRADA</option>
                                <option value="8">DAVID DELFINO  HERRADA</option>
                                <option value="32">E &amp; R SOLUCIONES HIDRAULICAS</option>
                                <option value="46">EICO INGENIERIA</option>
                                <option value="30">ELENA  ZENTENO</option>
                                <option value="3">ENFOQUE ANALITICO INTEGRAL S.A. DE C.V. </option>
                                <option value="68">FABIAN PINTO</option>
                                <option value="55">FERNANDO CHAMORRO </option>
                                <option value="78">FLORENTINO ARENAS</option>
                                <option value="45">FRANCISCO ALEJANDRO PECH MOO</option>
                                <option value="61">GENARO MAQUEDA ALPIZAR SERVICIO DE INGENIERIA</option>
                                <option value="40">GERARDO PIÑA</option>
                                <option value="72">GISEL HERRADA</option>
                                <option value="79">GRUPO GEMMA</option>
                                <option value="6">GRUPO EMPRESARIAL GONZALEZ EBENHOCH S.A. DE C.V. </option>
                                <option value="5">HILDA CABA</option>
                                <option value="82">HUGO SANCHEZ</option>
                                <option value="25">HYDROCONTROL HUGO CONSTANTINO</option>
                                <option value="33">ING GODO</option>
                                <option value="54">ING.ISAIAS MORALES OTERO</option>
                                <option value="17">INTEMA, S.A. DE C.V. </option>
                                <option value="53">IRENE HERNANDEZ SALVADOR SAMBRANO</option>
                                <option value="12">JAIME RANGEL ESPEJO </option>
                                <option value="69">JOSE GUADALUPE VALDERRABANO</option>
                                <option value="65">JOSE RAMON TEPOX (ANTES AMADO)</option>
                                <option value="47">JUVENCIO MONROY</option>
                                <option value="21">LABACAMA  *</option>
                                <option value="62">LABCIISA / ICA  /  TREN MAYA</option>
                                <option value="70">LABORATORIO D'AMCO</option>
                                <option value="20">LUIS GERARDO PIÑA</option>
                                <option value="81">LUIS JAVIER LOPEZ</option>
                                <option value="59">MANUEL GALINDO</option>
                                <option value="13">MARCO ANTONIO CASTAÑON  </option>
                                <option value="67">MARIA HERRADA</option>
                                <option value="43">MARY MEDRANO</option>
                                <option value="73">MINKES *</option>
                                <option value="18">PARTNER </option>
                                <option value="14">PATRICIA HERNANDEZ  </option>
                                <option value="2">PETRONA CONTROL QUIMICO </option>
                                <option value="28">PROINSA *</option>
                                <option value="10">RAFAEL QUINTOS RODRIGUEZ </option>
                                <option value="48">RAUL REYES PINEDA</option>
                                <option value="63">RAUL GORDILLO</option>
                                <option value="16">RAUL LORENZO GORDILLO </option>
                                <option value="39">RODOLFO RAMIREZ</option>
                                <option value="4">RODRIGO LOPEZ</option>
                                <option value="29">ROSALINDA CUELLAR</option>
                                <option value="23">SANIDAD VEGETAL</option>
                                <option value="57">SERVICIOS DE INGENIERIA Y CONSTRUCCION AMBIENTAL, SA. DE CV. ING. JAIME VIDAL</option>
                                <option value="15">SICA (JAIME VIDAL) </option>
                                <option value="74">SIGA VERDE</option>
                                <option value="49">SISTEMAS DE INGENIERIA AMBIENTAL S.A. DE C.V.</option>
                                <option value="75">SR. IBARRA.  *</option>
                                <option value="52">TERESITA JIMENEZ SALGADO BUAP</option>
                                <option value="42">VERONICA  CONTRERAS</option>
                                <option value="27">VICTOR LEON</option>
                                <option value="51">VICTOR CAPORAL</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" id="editarClienteGen">Guardar cambios</button>
                    </div>
                </div>
            </div>
        </div>
  @stop
@section('javascript')
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
<script src="{{ asset('/public/js/cliente/clientesGen.js') }}"></script>
@endsection  
