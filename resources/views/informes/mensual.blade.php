@extends('voyager::master')

@section('content')

  @section('page_header')
  <h6 class="page-title"> 
    Informes
    <i class="fas fa-chart-area"></i>
  </h6>
  @stop
  <style>
      .clearfix:after {
    content: '';
    display: table;
    clear: both;
}
#main{
   
   float: right;
   height:200px;
    width: 75%;
}
#sidebar{
   
   width:25%;
   float: left;
   height:200px;
   overflow-y: hidden;
}

#dragbar{
   background-color:black;
   height:100%;
   float: right;
   width: 3px;
   cursor: col-resize;
}
#ghostbar{
    width:3px;
    background-color:#000;
    opacity:0.5;
    position:absolute;
    cursor: col-resize;
    z-index:999}
  </style>


  <div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <input type="month" class="form-control form-control-sm">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group"> 
                        <button class="btn btn-sm btn-success"><i class="fas fa-search"></i> Filtrar</button>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group"> 
                        <button class="btn btn-sm btn-info" id="btnSC"><i class="fas fa-print"></i> SC</button>
                    </div>
                    <div class="form-group"> 
                        <button class="btn btn-sm btn-info" id="btnCc"><i class="fas fa-print"></i> CC</button>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="row">
                        <!-- Primera sección de tablas -->
                        <div class="col-md-4">
                            <button class="btn btn-primary" id="buscaPreInforme">Buscar</button>
                            <div style="width: 100%; overflow:scroll">
                                <table id="tableServicios" class="table" style="width: 100%; font-size: 10px">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Folio</th>
                                            <th>Cliente</th>
                                            <th>Norma</th> 
                                            <th>Muestreo</th>
                                            <th>Punto</th>
                                        </tr>
                                    </thead> 
                                    <tbody>
                                        @foreach ($model as $item)
                                            <tr>
                                                <td>{{$item->Id_solicitud}}</td>
                                                <td>{{$item->Folio_servicio}}</td>
                                                <td>{{$item->Empresa_suc}}</td>
                                                <td>{{$item->Clave_norma}}</td>
                                                <td>{{$item->Fecha_muestreo}}</td>
                                                <td></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- Segunda seccion de tablas -->
                        <div class="col-md-8">

                            <div style="width: 100%; overflow:scroll">
                               <div id="divReporte">
                                <table id="tableReporte" class="table" style="width: 100%; font-size: 10px">
                                    <thead>
                                        <tr>
                                            <th>Fórmula</th>
                                            <th>Unidad</th>
                                            <th>Método prueba</th>
                                            <th>Prom diario</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                    </tbody>
                                </table>
                               </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
 
    </div>
  </div>
@endsection  

@section('javascript')
<script>
    var i = 0;
var dragging = false;
   $('#dragbar').mousedown(function(e){
       e.preventDefault();
       
       dragging = true;
       var main = $('#main');
       var ghostbar = $('<div>',
                        {id:'ghostbar',
                         css: {
                                height: main.outerHeight(),
                                top: main.offset().top,
                                left: main.offset().left
                               }
                        }).appendTo('body');
       
        $(document).mousemove(function(e){
          ghostbar.css("left",e.pageX+2);
       });
       
    });

   $(document).mouseup(function(e){
       if (dragging) 
       {
           var percentage = (e.pageX / window.innerWidth) * 100;
           var mainPercentage = 100-percentage;
           
           $('#console').text("side:" + percentage + " main:" + mainPercentage);
           
           $('#sidebar').css("width",percentage + "%");
           $('#main').css("width",mainPercentage + "%");
           $('#ghostbar').remove();
           $(document).unbind('mousemove');
           dragging = false;
       }
    });

</script>
    <script src="{{asset('/public/js/informes/mensual.js')}}?v=0.0.1"></script>
@stop 