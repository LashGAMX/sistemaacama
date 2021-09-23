@extends('voyager::master')

@section('content')

  @section('page_header')
  <h6 class="page-title"> 
    <i class="voyager-window-list"></i>
    Tipo Análisis
  </h6>
  <!--MENU PRINCIPAL-->
  <div>
    <div class="page-content">
      <div class="alerts">
      </div>
      <div class="clearfix container-fluid row">
          
          <div class="col-md-3">
              <a href="https://dev.sistemaacama.com.mx/admin/laboratorio/captura">
                  <div class="panel widget center bgimage">
                      <div class="dimmer"></div>
                      <div class="panel-content">
                        <i class="fab fa-buffer"></i>
                          <h4>METALES</h4>
                      </div>
                  </div>
              </a>
          </div>
                      
          <div class="col-md-3">
              <a href="{{route('voyager.hist-clientes.index')}}">
                  <div class="panel widget center bgimage">
                      <div class="dimmer"></div>
                      <div class="panel-content">
                        <i class="fas fa-biohazard"></i>
                          <h4>CIANUROS</h4>
                      </div>
                  </div>
              </a>
          </div>      
          <div class="col-md-3">
            <a href="{{route('voyager.hist-clientes.index')}}">
                <div class="panel widget center bgimage">
                    <div class="dimmer"></div>
                    <div class="panel-content">
                      <i class="fas fa-bolt"></i>
                        <h4>CONDUCTIVIDAD</h4>
                    </div>
                </div>
            </a>
        </div>   
        <div class="col-md-3">
          <a href="{{route('voyager.hist-clientes.index')}}">
              <div class="panel widget center bgimage">
                  <div class="dimmer"></div>
                  <div class="panel-content">
                    <i class="voyager-lab"></i>
                      <h4>DBO</h4>
                  </div>
              </div>
          </a>
      </div>   

      </div>
  </div> 
  </div>
    <!--FIN DE MENU PRINCIPAL-->
  

  @stop

  @section('javascript')
  <script src="{{asset('js/laboratorio/TipoAnalisis.js')}}"></script>
  <script src="{{asset('js/libs/componentes.js')}}"></script>
  <script src="{{asset('js/libs/tablas.js')}}"></script>
  @stop

@endsection  


