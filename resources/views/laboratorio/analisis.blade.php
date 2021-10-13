@extends('voyager::master')

@section('content')

  @section('page_header')
  <h6 class="page-title"> 
    <i class="voyager-window-list"></i>
    Análisis (Solo visualización)
  </h6>

  <style>

    *{
      margin:0;
    }
    
    header{
      height:170px;
      color:#FFF;
      font-size:20px;
      font-family:Sans-serif;
      background:#009688;
      padding-top:30px;
      padding-left:50px;
    }
    
    .contenedor{
      width:90px;
      height:240px;
      position:absolute;
      right:0px;
      bottom:0px;
    }
    
    .botonF1{
      padding: 0%;
      width:60px;
      height:60px;
      border-radius:100%;
      background:#F44336;
      right:0;
      bottom:0;
      position:absolute;
      margin-right:16px;
      margin-bottom:16px;
      border:none;
      outline:none;
      color:#FFF;
      font-size:36px;
      box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);
      transition:.3s;  
    }
    
    span{
      transition:.5s;  
    }
    
    .botonF1:hover span{
      transform:rotate(360deg);
    }
    
    .botonF1:active{
      transform:scale(1.1);
    }

    .btn{
      padding: 0%;
      width:40px;
      height:40px;
      border-radius:100%;
      border:none;
      color:#FFF;
      box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);
      font-size:28px;
      outline:none;
      position:absolute;
      right:0;
      bottom:0;
      margin-right:26px;
      transform:scale(0);
    }
    .botonF2{
      background:#2196F3;
      margin-bottom:85px;
      transition:0.5s;
    }
    .botonF3{
      background:#673AB7;
      margin-bottom:130px;
      transition:0.7s;
    }
    .botonF4{
      background:#009688;
      margin-bottom:175px;
      transition:0.9s;
    }
    .botonF5{
      background:#FF5722;
      margin-bottom:220px;
      transition:0.99s;
    }
    .animacionVer{
      transform:scale(1);
    }

  </style>
 
<div class="container-fluid">
    <div class="row">
        <div class="col-md-5">
            <div class="form-group">
              <label for="exampleFormControlSelect1">Filtro</label>
                <select class="form-control">
                    <option value="0">Sin seleccionar</option>
                    <option value="1">Asignado</option>
                    <option value="2">Sin asignar</option>
                  </select>
            </div>
        </div>
        <div class="col-md-12">
            <table class="table" id="tableAnalisis"> 
                <thead>
                  <tr>
                    <th>Id_solicitud</th>
                    <th>Folio</th>
                    <th>Cliente</th>
                    <th>Fecha y Hora Recepción</th>
                    <th>Punto muestreo</th>
                    <th>Norma</th>
                    <th style="width: 30%">Parámetros</th>
                  </tr>
                </thead>
                <tbody>
                  @for ($i = 0; $i < $elements; $i++)
                    <tr>
                      <td id="idSolicitud">{{$model[$i]->Id_solicitud}}</td>
                      <td>{{$model[$i]->Folio}}</td>
                      <td>{{$model[$i]->Cliente}}</td>
                      <td>{{$model[$i]->Hora_entrada}}</td>
                      
                      @php
                        $semaforo = true
                      @endphp

                      @for ($x = 0; $x < $solicitudPuntosLength; $x++)
                        @if ($model[$i]->Id_solicitud == $solicitudPuntos[$x]->Id_solicitud)
                          @for ($j = 0; $j < $puntoMuestreoLength; $j++)
                            @if ($solicitudPuntos[$x]->Id_punto == $puntoMuestreo[$j]->Id_punto)
                              <td>{{$puntoMuestreo[$j]->Descripcion}}</td>
                              @php
                                  $semaforo = true
                              @endphp
                              @break                            
                            @else
                              @php
                                  $semaforo = false
                              @endphp
                            @endif
                          @endfor                          
                          @if ($semaforo == true)
                            @break
                          @endif                        
                        @else
                          @php
                            $semaforo = false
                          @endphp
                        @endif
                      @endfor

                      @if ($semaforo == false)
                        <td>Sin resultados</td>
                      @endif

                      @if ($model[$i]->Id_solicitud == $solicitud[$i]->Id_solicitud)
                        <td>{{$solicitud[$i]->Clave_norma}}</td>                      
                      @endif
                  
                      @for ($j = 0; $j < $parametrosLength; $j++)
                        @if ($solicitud[$i]->Id_norma == $parametros[$j]->Id_norma)
                          <td>
                            @for ($z = $j; $z < $parametrosLength; $z++)
                              {{$parametros[$z]->Parametro, }}
                            @endfor
                          </td>
                          @php
                            $semaforo2 = true
                          @endphp
                          @break                            
                        @else
                          @php
                          $semaforo2 = false
                          @endphp
                        @endif
                      @endfor                          
                      
                      @if ($semaforo2 == false)
                        <td>Sin resultados</td>
                      @endif
                      
                                                          
                                            
                      
                    </tr>
                  @endfor
              </tbody>
            </table>
      </div>
    </div>

    <div class="contenedor">
      <button class="botonF1"><i class="voyager-move"></i></button>
      <button class="btn botonF2"><i class="far fa-eye"></i></button>
      <button class="btn botonF3"><i class="voyager-data"></i></button>
      <button class="btn botonF4"><i class="voyager-list-add"></i></button>
      <button class="btn botonF5"><i class="voyager-file-text"></i></button>
    </div>

</div>
  @stop

  @section('javascript')
  <script src="{{asset('js/laboratorio/analisis.js')}}"></script>
  <script src="{{asset('js/libs/componentes.js')}}"></script>
  <script src="{{asset('js/libs/tablas.js')}}"></script>

  <script>
    $('.botonF1').hover(function(){
      $('.btn').addClass('animacionVer');
    })

    $('.contenedor').mouseleave(function(){
      $('.btn').removeClass('animacionVer');
    })
  </script>
  @stop

@endsection  


