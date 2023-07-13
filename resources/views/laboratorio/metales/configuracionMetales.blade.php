@extends('voyager::master')

@section('content')

  @section('page_header')
 
  @stop

  <div class="container-fluid">
    <div class="row">
        <div class="col-md-6">
            <table class="table" id="tabParametros">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tipo formula</th>
                        <th>Parametro</th>                        
                    </tr>
                </thead>
                <tbody>
                    @foreach ($parametros as $item)
                        <tr>
                            <td>{{$item->Id_parametro}}</td>
                            <td>{{$item->Tipo_formula}}</td>
                            <td>{{$item->Parametro}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-12">
                    <table class="table">
                        <tr>
                            <td><input type="text" id="parametro" style="width: 100%;border:none"></td>
                            <td><button id="btnGuardar" class="btn-success"><i class="fas fa-save"></i> Guardar</button></td>
                        </tr>
                        <tr>
                            <td>Equipo</td>
                            <td><input type="text" placeholder="Equipo" id="equipo"></td>
                        </tr>
                        <tr>
                            <td>Corriente de Lampara</td>
                            <td><input type="text" placeholder="corriente" id="corrienteLampara"></td>
                        </tr>
                        <tr>
                            <td>No. Inventario</td> 
                            <td><input type="text" placeholder="# Inventario" id="NoInventario"></td>
                        </tr>
                        <tr>
                            <td>Energia de lampara</td>
                            <td><input type="text" placeholder="Energia lampara" id="energiaLampara"></td>
                        </tr>
                        <tr>
                            <td>Concentracion STD</td>
                            <td><input type="text" placeholder="Concentracion" id="concentracion"></td>
                        </tr>
                        <tr>
                            <td>No. Inventario Lampara</td>
                            <td><input type="text" placeholder="# Lampara" id="inventarioLamapra"></td>
                        </tr>
                        <tr>
                            <td>Longitud Onda</td>
                            <td><input type="text" placeholder="Longitud onda" id="longitudOnda"></td>
                        </tr>
                        <tr>
                            <td>SLIT</td>
                            <td><input type="text" placeholder="SLIT" id="slit"></td>
                        </tr>
                        <tr>
                            <td>Acetileno</td>
                            <td><input type="text" placeholder="Acetileno" id="acetileno"></td>
                        </tr>
                        <tr>
                            <td>Aire</td>
                            <td><input type="text" placeholder="Aire" id="aire"></td>
                        </tr>
                        <tr>
                            <td>Oxido Nitroso</td>
                            <td><input type="text" placeholder="Oxido Nitroso" id="oxidoNitroso"></td>
                        </tr>
                        <tr>
                            <td>Generador Hidruros</td>
                            <td><input type="text" placeholder="Gen. Hidruros" id="hidruros"></td>
                        </tr>
                        <tr>
                            <td>Bitacora Curva calibracion</td>
                            <td><input type="text" placeholder="curva" id="curva"></td>
                        </tr>
                    </table>
                </div>
          
            </div>
        </div>
        <div class="col-md-12">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 20px"></th>
                        <th style="width: 20px"></th>
                        <th style="width: 20px"></th>
                        <th style="width: 20px"></th>
                        <th style="width: 20px"></th>
                        <th style="width: 20px"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td></td>
                        <td>STD1</td>
                        <td>STD2</td>
                        <td>STD3</td>
                        <td>STD4</td>
                        <td>STD5</td>
                    </tr>
                    <tr>
                        <td>Limite Sup.</td>
                        <td><input type="text" id="supStd1"></td>
                        <td><input type="text" id="supStd2"></td>
                        <td><input type="text" id="supStd3"></td>
                        <td><input type="text" id="supStd4"></td>
                        <td><input type="text" id="supStd5"></td>
                    </tr>
                    <tr>
                        <td>Absorvancia.</td>
                        <td><input type="text" id="absStd1"></td>
                        <td><input type="text" id="absStd2"></td>
                        <td><input type="text" id="absStd3"></td>
                        <td><input type="text" id="absStd4"></td>
                        <td><input type="text" id="absStd5"></td>
                    </tr>
                    <tr>
                        <td>Limite Inf</td>
                        <td><input type="text" id="infStd1"></td>
                        <td><input type="text" id="infStd2"></td>
                        <td><input type="text" id="infStd3"></td>
                        <td><input type="text" id="infStd4"></td>
                        <td><input type="text" id="infStd5"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
  </div>

  @section('javascript')
    <script src="{{asset('public/js/laboratorio/metales/configuracionMetales.js')}}?v=0.0.1"></script>
  @stop

@endsection    


