<div>
    {{-- Be like water. --}}
    <select class="form-control"  wire:model='subModulo'>
        <option value="0">Selecciona...</option>
        <option value="1">Laboratorio</option>
        <option value="2">Analisis</option>
      </select>
      @switch($subModulo)
          @case(1)
          <div class="page-content">
            <div class="alerts">
        </div>
            <div class="clearfix container-fluid row">
            <div class="col-md-3"><div class="panel widget center bgimage" style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx/admin/voyager-assets?path=images%2Fwidget-backgrounds%2F02.jpg');">
        <div class="dimmer"></div>
        <div class="panel-content">
            <i class="fa fa-archway"></i>        <h4>Sucursal</h4>
            
            <a href="{{route("voyager.hist-laboratoriosuc.index")}}" class="btn btn-primary">Ver Historial</a>
        </div>
        </div>
        </div><div class="col-md-3"><div class="panel widget center bgimage" style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx/admin/voyager-assets?path=images%2Fwidget-backgrounds%2F01.jpg');">
        <div class="dimmer"></div>
        <div class="panel-content">
            <i class="fa fa-underline"></i>        <h4>Unidad</h4>
            
            <a href="{{route("voyager.hist-laboratoriouni.index")}}" class="btn btn-primary">Ver Historial</a>
        </div>
        </div>
        </div>
        </div>
          </div>
              @break
          @case(2)
          <div class="page-content">
            <div class="alerts">
        </div>
            <div class="clearfix container-fluid row">
            <div class="col-md-3"><div class="panel widget center bgimage" style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx/admin/voyager-assets?path=images%2Fwidget-backgrounds%2F02.jpg');">
        <div class="dimmer"></div>
        <div class="panel-content">
            <i class="fa fa-subscript"></i>        <h4>Tipo Formula</h4>
            
            <a href="{{route("voyager.hist-analisistipfor.index")}}" class="btn btn-primary">Ver Historial</a>
        </div>
        </div>
        </div>
        <div class="col-md-3"><div class="panel widget center bgimage" style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx/admin/voyager-assets?path=images%2Fwidget-backgrounds%2F01.jpg');">
        <div class="dimmer"></div>
        <div class="panel-content">
            <i class="fab fa-medium-m"></i>        <h4>Matriz</h4>
            
            <a href="{{route("voyager.hist-analisismatriz.index")}}" class="btn btn-primary">Ver Historial</a>
        </div>
        </div>
        </div>
        <div class="col-md-3"><div class="panel widget center bgimage" style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx/admin/voyager-assets?path=images%2Fwidget-backgrounds%2F01.jpg');">
            <div class="dimmer"></div>
            <div class="panel-content">
                <i class="fa fa-project-diagram"></i>        <h4>Rama</h4>
                
                <a href="{{route("voyager.hist-analisisrama.index")}}" class="btn btn-primary">Ver Historial</a>
            </div>
            </div>
            </div>
            <div class="col-md-3"><div class="panel widget center bgimage" style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx/admin/voyager-assets?path=images%2Fwidget-backgrounds%2F01.jpg');">
                <div class="dimmer"></div>
                <div class="panel-content">
                    <i class="fa fa-wrench"></i>        <h4>Técnica</h4>
                    
                    <a href="{{route("voyager.hist-analisistecnica.index")}}" class="btn btn-primary">Ver Historial</a>
                </div>
                </div>
                </div>
                <div class="col-md-3"><div class="panel widget center bgimage" style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx/admin/voyager-assets?path=images%2Fwidget-backgrounds%2F01.jpg');">
                    <div class="dimmer"></div>
                    <div class="panel-content">
                        <i class="fab fa-atlassian"></i>        <h4>Área de análisis</h4>
                        
                        <a href="{{route("voyager.hist-analisisareaana.index")}}" class="btn btn-primary">Ver Historial</a>
                    </div>
                    </div>
                    </div>
                    <div class="col-md-3"><div class="panel widget center bgimage" style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx/admin/voyager-assets?path=images%2Fwidget-backgrounds%2F01.jpg');">
                        <div class="dimmer"></div>
                        <div class="panel-content">
                            <i class="fa fa-vials"></i>        <h4>Método Prueba</h4>
                            
                            <a href="{{route("voyager.hist-analisismetprueba.index")}}" class="btn btn-primary">Ver Historial</a>
                        </div>
                        </div>
                        </div>
                        <div class="col-md-3"><div class="panel widget center bgimage" style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx/admin/voyager-assets?path=images%2Fwidget-backgrounds%2F01.jpg');">
                            <div class="dimmer"></div>
                            <div class="panel-content">
                                <i class="fab fa-stripe-s"></i>        <h4>Simbología</h4>
                                
                                <a href="{{route("voyager.hist-analisissim.index")}}" class="btn btn-primary">Ver Historial</a>
                            </div>
                            </div>
                            </div>
                            <div class="col-md-3"><div class="panel widget center bgimage" style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx/admin/voyager-assets?path=images%2Fwidget-backgrounds%2F01.jpg');">
                                <div class="dimmer"></div>
                                <div class="panel-content">
                                    <i class="fa fa-bong"></i>        <h4>Procedimiento</h4>
                                    
                                    <a href="https://dev.sistemaacama.com.mx/admin/hist-laboratoriosuc" class="btn btn-primary">Ver Historial</a>
                                </div>
                                </div>
                                </div>
        </div>

        </div>
              @break
          @default
              
      @endswitch

    

    
    
</div>
