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
            <a href="{{url('')}}/admin/laboratorio/fq/capturaEspectro">
                  <div class="panel widget center bgimage">
                      <div class="dimmer"></div>
                      <div class="panel-content">
                        <i class="fab fa-buffer"></i>
                          <h4>ESPECTROFOTOMETRICOS</h4>
                      </div>
                  </div>
              </a>
          </div>
                      
          {{-- <div class="col-md-3">
            <a href="{{url('')}}/admin/laboratorio/fq/capturaGravi">
                  <div class="panel widget center bgimage">
                      <div class="dimmer"></div>
                      <div class="panel-content">
                        <i class="fas fa-biohazard"></i>
                          <h4>GRAVIMETRIA</h4>
                      </div> 
                  </div>
              </a>
          </div>       --}}
          <div class="col-md-3">
            <a href="{{url('')}}/admin/laboratorio/fq/capturaVolumetria">
                <div class="panel widget center bgimage">
                    <div class="dimmer"></div>
                    <div class="panel-content">
                      <i class="fas fa-bolt"></i>
                        <h4>VOLUMETRIA</h4>
                    </div>
                </div>
            </a>
        </div>    
        <div class="col-md-3">
          <a href="{{url('')}}/admin/laboratorio/fq/capturaGA">
              <div class="panel widget center bgimage">
                  <div class="dimmer"></div>
                  <div class="panel-content">
                    <i class="fas fa-bolt"></i>
                      <h4>G & A</h4>
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
  <script src="{{asset('/public/js/laboratorio/metales/TipoAnalisis.js')}}"></script>
  <script src="{{asset('/public/js/libs/componentes.js')}}"></script>
  <script src="{{asset('/public/js/libs/tablas.js')}}"></script>
  @stop

@endsection  


