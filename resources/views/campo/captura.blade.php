@extends('voyager::master')

@section('content')

  @section('page_header')
  {{-- <h6 class="page-title"> 
    <i class="fa fa-edit"></i>
    Captura
  </h6> --}}
  @stop

  <div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                  <a class="nav-link" id="datosGenerales-tab" data-toggle="tab" href="#datosGenerales" role="tab" aria-controls="datosGenerales" aria-selected="true">1. Datos Generales</a>
                </li>
                <li class="nav-item" role="presentation">
                  <a class="nav-link" id="datosMuestreo-tab" data-toggle="tab" href="#datosMuestreo" role="tab" aria-controls="datosMuestreo" aria-selected="false">2. Datos muestreo</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="datosCompuestos-tab" data-toggle="tab" href="#datosCompuestos" role="tab" aria-controls="datosCompuestos" aria-selected="false">3. Datos Compuestos</a>
                  </li>
                  <li class="nav-item" role="presentation">
                    <a class="nav-link" id="evidencia-tab" data-toggle="tab" href="#evidencia" role="tab" aria-controls="evidencia" aria-selected="false">4. Evidencia</a>
                  </li>
              </ul>
        </div>
        <div class="col-md-12">
          <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade" id="datosGenerales" role="tabpanel" aria-labelledby="datosGenerales-tab">  
              <form>
              <div class="row">
                <div class="col-md-12">
                  <h6>Datos generales</h6>
                  <hr>
                </div>
                <div class="col-md-2">
                  <p>Id Solucitud: 57235</p>
                </div>
                <div class="col-md-2">
                  <p>Id Ing campo: 57235</p>
                </div>
                <div class="col-md-2">
                  <p>Folio servicio: 14-25/22</p>
                </div>
                <div class="col-md-2">
                  <p>Captura: sistema</p>
                </div>
                <div class="col-md-2">
                  <p>Siralab: No</p>
                </div>
              </div>

              <div class="col-md-12">
                <p>Punto de muestreo: DESCARGA FINAL</p>
              </div>

              <div class="col-md-5">
                <div class="form-group">
                  <label for="">Equipo serie</label>
                  <select name="termometro" id="termometro" class="form-control">
                    <option>Sin seleccionar</option>
                    @foreach ($termometros as $item)
                        <option value="{{$item->Id_termometro}}">{{$item->Equipo}} / {{$item->Marca}} / {{$item->Modelo}} / {{$item->Serie}}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="">Temperatura ambiente</label>
                  <input type="number" class="form-control" placeholder="Temperatura">
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label for="">Temperatura buffer</label>
                  <input type="number" class="form-control" placeholder="Temperatura">
                </div>
              </div>

              <div class="col-md-12">
                <h6>Empresa</h6>
                <hr>
              </div>
              <div class="col-md-12">
                <p>Cliente: LABORATORIO ACAMA PRUEBA</p>
              </div>
              <div class="col-md-12">
                <h6>Croquis punto de muestreo</h6>
                <hr>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                  <label for="">Punto de muestreo</label>
                  <input type="text" class="form-control" placeholder="Punto de muestreo" value="{{$model->Direccion}}" disabled>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="">Latitud</label>
                  <input type="text" class="form-control" placeholder="Latitud">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="">Longitud</label>
                  <input type="text" class="form-control" placeholder="Longitud">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="">Altitud</label>
                  <input type="text" class="form-control" placeholder="Altitud">
                </div>
              </div>

              <div class="col-md-4">
                <p>Muestreo: 12hrs</p>
              </div>
              <div class="col-md-4">
                <p>Numero de muestras: 4</p>
              </div>
              <div class="col-md-4">
                <p>Tipo de descarga: Residual</p>
              </div>
              <div class="col-md-12"> 
                <p>Norma / Material usado muestreo</p>
                <table class="table" id="materialUsado">
                  <thead>
                    <tr>
                      <th>Norma</th>
                      <th>Formúla</th>
                      <th>Análisis</th>
                      <th>Preservador</th>
                      <th>Recipiente</th>
                      <th>Volumen</th>
                      <th>Unidad</th>
                    </tr>
                  </thead>
                </table>
              </div>
              <div class="col-md-12">
                <p>Factor de conversión de temperatura</p>
                <div class="" id="factorDeConversion">
                <table class="table" id="">
                  <thead>
                    <tr>
                      <th>De °C</th>
                      <th>a °C</th>
                      <th>Factor corección</th>
                      <th>Factor de corección aplicada</th>
                    </tr>
                  </thead>
                </table>
                </div>
              </div>
              <div class="col-md-12">
                <p>PH trazable</p>
                <table class="table" id="phTrazable">
                  <thead>
                    <tr>
                      <th>PH Trazable</th>
                      <th>PH Trazable</th>
                      <th>Marca</th>
                      <th>No Lote</th>
                      <th>Lectura 1</th>
                      <th>Lectura 2</th>
                      <th>Lectura 3</th>
                      <th>Estado</th>
                    </tr> 
                  </thead>
                  <tbody>
                    <tr>
                      <td>
                        <select id="phTrazable1">
                          <option value="0">Sin seleccionar</option>
                          @foreach ($phTrazable as $item)
                            <option value="{{$item->Id_ph}}">{{$item->Ph}}</option>    
                          @endforeach
                        </select>  
                      </td>
                      <td><p id="phTNombre1"></p></td>
                      <td><p id="phTMarca1"></p></td>
                      <td><p id="phTLote1"></p></td>
                      <td>
                        <input type="text" class="" value="L1">
                      </td>
                      <td>
                        <input type="text" class="" value="L2">
                      </td>
                      <td>
                        <input type="text" class="" value="L3">
                      </td>
                      <td><input type="text" id="phTEstado1"></td>
                    </tr>
                    <tr>
                      <td>
                        <select id="phTrazable2">
                          <option value="0">Sin seleccionar</option>
                          @foreach ($phTrazable as $item)
                            <option value="{{$item->Id_ph}}">{{$item->Ph}}</option>    
                          @endforeach
                        </select>  
                      </td>
                      <td><p id="phTNombre2"></p></td>
                      <td><p id="phTMarca2"></p></td>
                      <td><p id="phTLote2"></p></td>
                      <td>
                        <input type="text" class="" value="L1">
                      </td>
                      <td>
                        <input type="text" class="" value="L2">
                      </td>
                      <td>
                        <input type="text" class="" value="L3">
                      </td>
                      <td><input type="text" id="phTEstado2"></td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div class="col-md-12">
                <p>PH control calidad</p>
                <table class="table" id="phTrazable">
                  <thead>
                    <tr>
                      <th>PH calidad</th>
                      <th>PH calidad</th>
                      <th>Marca</th>
                      <th>No Lote</th>
                      <th>Lectura 1</th>
                      <th>Lectura 2</th>
                      <th>Lectura 3</th>
                      <th>Estado</th>
                      <th>Promedio</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>
                        <select id="phCalidad1">
                          <option value="0">Sin seleccionar</option>
                          @foreach ($phCalidad as $item)
                            <option value="{{$item->Id_ph}}">{{$item->Ph_calidad}}</option>    
                          @endforeach
                        </select>  
                      </td>
                      <td><p id="phCNombre1"></p></td>
                      <td><p id="phCMarca1"></p></td>
                      <td><p id="phCLote1"></p></td>
                      <td>
                        <input type="text" class="" value="L1">
                      </td>
                      <td>
                        <input type="text" class="" value="L2">
                      </td>
                      <td>
                        <input type="text" class="" value="L3">
                      </td>
                      <td><input type="text" id="phCEstado1"></td>
                      <td><input type="text" id="phCPromedio1"></td>
                    </tr>
                    <tr>
                      <td>
                        <select id="phCalidad2">
                          <option value="0">Sin seleccionar</option>
                          @foreach ($phCalidad as $item)
                            <option value="{{$item->Id_ph}}">{{$item->Ph_calidad}}</option>    
                          @endforeach
                        </select>  
                      </td>
                      <td><p id="phCNombre2"></p></td>
                      <td><p id="phCMarca2"></p></td>
                      <td><p id="phCLote2"></p></td>
                      <td>
                        <input type="text" class="" value="L1">
                      </td>
                      <td>
                        <input type="text" class="" value="L2">
                      </td>
                      <td>
                        <input type="text" class="" value="L3">
                      </td>
                      <td><input type="text" id="phCEstado2"></td>
                      <td><input type="text" id="phCPromedio2"></td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div class="col-md-12">
                <p>Conductividad trazable</p>
                <table class="table" id="phTrazable">
                  <thead>
                    <tr>
                      <th>Conductividad Trazable</th>
                      <th>Conductividad Trazable</th>
                      <th>Marca</th>
                      <th>No Lote</th>
                      <th>Lectura 1</th>
                      <th>Lectura 2</th>
                      <th>Lectura 3</th>
                      <th>Estado</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>
                        <select id="conTrazable">
                          <option value="0">Sin seleccionar</option>
                          @foreach ($conTrazable as $item)
                            <option value="{{$item->Id_ph}}">{{$item->Conductividad}}</option>    
                          @endforeach
                        </select>  
                      </td>
                      <td><p id="conNombre"></p></td>
                      <td><p id="conMarca"></p></td>
                      <td><p id="conLote"></p></td>
                      <td>
                        <input type="text" class="" value="L1">
                      </td>
                      <td>
                        <input type="text" class="" value="L2">
                      </td>
                      <td>
                        <input type="text" class="" value="L3">
                      </td>
                      <td><input type="text" id="conEstado"></td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div class="col-md-12">
                <p>Conductividad control calidad</p>
                <table class="table" id="phTrazable">
                  <thead>
                    <tr>
                      <th>Conductividad calidad</th>
                      <th>Conductividad calidad</th>
                      <th>Marca</th>
                      <th>No Lote</th>
                      <th>Lectura 1</th>
                      <th>Lectura 2</th>
                      <th>Lectura 3</th>
                      <th>Estado</th>
                      <th>Promedio</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>
                        <select id="conCalidad">
                          <option value="0">Sin seleccionar</option>
                          @foreach ($phCalidad as $item)
                            <option value="{{$item->Id_ph}}">{{$item->Conductividad}}</option>    
                          @endforeach
                        </select>  
                      </td>
                      <td><p id="conCNombre"></p></td>
                      <td><p id="conCMarca"></p></td>
                      <td><p id="conCLote"></p></td>
                      <td>
                        <input type="text" class="" value="L1">
                      </td>
                      <td>
                        <input type="text" class="" value="L2">
                      </td>
                      <td>
                        <input type="text" class="" value="L3">
                      </td>
                      <td><input type="text" id="conCEstado"></td>
                      <td><input type="text" id="conCPromedio"></td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Guardar</button>
              </form>
            </div> 
            <div class="tab-pane fade" id="datosMuestreo" role="tabpanel" aria-labelledby="datosMuestreo-tab">  
              <div class="row">
                  <form>
                  <div class="col-md-12">
                    <p>PH</p>
                    <table class="table" id="phTrazable">
                      <thead>
                        <tr>
                          <th>Núm Muestra</th>
                          <th>Materia flotante</th>
                          <th>Olor</th>
                          <th>Color</th>
                          <th>PH 1</th>
                          <th>PH 2</th> 
                          <th>PH 3</th>
                          <th>PH Promedio</th>
                          <th>Fecha muestreo</th>
                        </tr>
                      </thead>
                      <tbody>
                        @for ($i = 0; $i < $model->Num_tomas; $i++)
                            <tr>
                              <td>{{$i + 1}}</td>
                              <td>Materia</td>
                              <td>Olor</td>
                              <td>Color</td>
                              <td></td>
                              <td></td>
                              <td></td> 
                              <td></td>
                              <td>Fecha</td>
                            </tr>
                        @endfor
                      </tbody>
                    </table>
                  </div>
                  <div class="col-md-12">
                    <p>Temperatura del agua</p>
                    <table class="table" id="phTrazable">
                      <thead>
                        <tr>
                          <th>Núm Muestra</th>
                          <th>Temperatura °C 1</th>
                          <th>Temperatura °C 2</th>
                          <th>Temperatura °C 3</th>
                          <th>Promedio</th>
                        </tr>
                      </thead>
                      <tbody>
                        @for ($i = 0; $i < $model->Num_tomas; $i++)
                        <tr>
                          <td>{{$i + 1}}</td>
                          <td></td>
                          <td></td>
                          <td></td> 
                          <td>Promedio</td>
                        </tr>
                        @endfor
                      </tbody>
                    </table>
                  </div>
                  <div class="col-md-12">
                    <p>Conductividad</p>
                    <table class="table" id="phTrazable">
                      <thead>
                        <tr>
                          <th>Núm Muestra</th>
                          <th>Conductividad 1</th>
                          <th>Conductividad 2</th>
                          <th>Conductividad 3</th>
                          <th>Conductividad Promedio</th>
                        </tr>
                      </thead>
                      <tbody>
                        @for ($i = 0; $i < $model->Num_tomas; $i++)
                        <tr>
                          <td>{{$i + 1}}</td>
                          <td></td>
                          <td></td>
                          <td></td> 
                          <td>Promedio</td>
                        </tr>
                        @endfor
                      </tbody>
                    </table>
                  </div>
                  <div class="col-md-12">
                    <p>Gasto</p>
                    <table class="table" id="phTrazable">
                      <thead>
                        <tr>
                          <th>Núm Muestra</th>
                          <th>Gasto (ls/s) 1</th>
                          <th>Gasto (ls/s) 2</th>
                          <th>Gasto (ls/s) 3</th>
                          <th>Gasto Promedio</th>
                        </tr>
                      </thead>
                      <tbody>
                        @for ($i = 0; $i < $model->Num_tomas; $i++)
                        <tr>
                          <td>{{$i + 1}}</td>
                          <td></td>
                          <td></td>
                          <td></td> 
                          <td>Promedio</td>
                        </tr>
                        @endfor
                      </tbody>
                    </table>
                  </div>
                </div>
                <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Guardar</button>
              </form>
            </div> 
            <div class="tab-pane fade" id="datosCompuestos" role="tabpanel" aria-labelledby="datosCompuestos-tab">  
              <form>
                <div class="row">
                  <div class="col-md-12">
                    <p>Muestra compuesta</p>
                    <table class="table" id="phTrazable">
                      <thead>
                        <tr>
                          <th>Núm Muestra</th>
                          <th>Litros a tomar</th>
                        </tr>
                      </thead>
                    </table>
                  </div>
                  <div class="col-md-12">
                    <p>Tipo descarga: RESIDUAL</p>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="">Metodo aforo</label>
                      <select name="" id="" class="form-control">
                        <option>Metodo 1</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="">Con tratamiento</label>
                      <select name="" id="" class="form-control">
                        <option>Tratamiento 1</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="">Tipo tratamiento</label>
                      <select name="" id="" class="form-control">
                        <option>Tipo 1</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="">Procedimiento de Muestreo PE-10-02-</label>
                      <input type="number" class="form-control" placeholder="Procedimiento">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <textarea class="form-group" style="width: 100%;"></textarea>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <p>Observacion solicitud: ES UN PUNTO DE MUESTREO // SUPERVICION  DEL SERVICIO: ____________ </p>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <h6></h6>
                    <hr>
                  </div>
                  <div class="col-md-12">
                    <p>Calculo de muestreo</p>
                    <table class="table" id="phTrazable">
                      <thead>
                        <tr>
                          <th>Norma</th>
                          <th>Volumen calculado</th>
                        </tr>
                      </thead>
                    </table>
                  </div>
                  <div class="col-md-12">
                    <table class="table" id="phTrazable">
                      <thead>
                        <tr>
                          <th>Núm muestra</th>
                          <th>Qi</th>
                          <th>Qt</th>
                          <th>Qi / Qt</th>
                          <th>Vmc</th>
                          <th>Vmsi</th>
                        </tr>
                      </thead>
                    </table>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="">PH Muestra compuesta</label>
                      <input type="number" class="form-control" placeholder="PH muestra">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="">Temperatura muestra</label>
                      <input type="number" class="form-control" placeholder="Temperatura muestra">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <p>Fecha recepción: 04/06/2021</p>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <p>Empleado: ABUNDIS RODRIGUEZ FRANCISCO JAVIER</p>
                  </div>
                  <div class="col-md-12">
                    <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Guardar</button>
                  </div>
                </div>
              </form>
            </div> 
            <div class="tab-pane fade" id="evidencia" role="tabpanel" aria-labelledby="evidencia-tab">  
              Datos Evidencia
              <hr> 
            
              <div class="col-md-4">
                  <input type="file" name="foto" id="imgEvidencia1" accept="image/png, image/jpeg"/>
              </div>
              <div class="col-md-4">
                <input type="file" name="foto" id="imgEvidencia2" accept="image/png, image/jpeg"/>
            </div>
              <div  class="col-md-4">
                <button type="submit" class="btn btn-success">Subir Imagen</button>
              </div>
            </div> 
          </div>
        </div>
      
      </div>
    </div>

@endsection  


@section('javascript')
    <script src="{{asset('js/campo/captura.js')}}"></script>
    <script src="{{asset('js/libs/componentes.js')}}"></script>
    <script src="{{asset('js/libs/tablas.js')}}"></script>
@stop

