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
            <i class="voyager-params"></i>        <h4>Sucursal</h4>
            
            <a href="https://dev.sistemaacama.com.mx/admin/hist-laboratoriosuc" class="btn btn-primary">Ver Historial</a>
        </div>
        </div>
        </div><div class="col-md-3"><div class="panel widget center bgimage" style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx/admin/voyager-assets?path=images%2Fwidget-backgrounds%2F01.jpg');">
        <div class="dimmer"></div>
        <div class="panel-content">
            <i class="voyager-people"></i>        <h4>Unidad</h4>
            
            <button type="button" wire:click="clientes" data-toggle="modal" data-target="#modalHistorial" class="btn btn-primary" >Ver Historial</button>
        </div>
        </div>
        </div>
        
        
        
        </div>
              @break
          @case(2)
              
              @break
          @default
              
      @endswitch

    

    {{-- modal --}}

    <div wire:ignore.self class="modal fade" id="modalHistorial" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog " style="width: 90%">
          <div class="modal-content">
            <form wire:submit.prevent="render">
            <div class="modal-header">
                @switch($sw)  {{--Condicion para los titulos --}}
                    @case(1)
                    <h5 class="modal-title" id="exampleModalLabel">Historial Configuraciones</h5>
                        @break
                    @case(2)
                    <h5 class="modal-title" id="exampleModalLabel">Historial Clientes</h5>
                        @break
                    @default                       
                @endswitch

              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            {{--Condicional para selectores--}}
            <div class="modal-body">
                @switch($sw)
                    @case(1) {{--Configuraciones--}}
                        <div class="col-md-3">
                <label for="">submodulo</label>
             
              <select class="form-control"  wire:model='subModulo'>
                <option value="0">Selecciona...</option>
                <option value="1">Laboratorio</option>
                <option value="2">Analisis</option>
              </select>
            </div>

            <div class="col-md-3">
                <label for="">Categoria</label> 
              <select class="form-control" wire:model='categoria' wire:click='getConfig'>
                  @switch($subModulo)
                      @case(1)
                      <option value="0">Selecciona...</option>
                      <option value="1">Sucursal</option>
                      <option value="2">Unidad</option>
                          @break
                      @case(2)
                      <option value="1">tipoFormula</option>
                      <option value="2">Matriz</option>
                          @break
                      @default
                  @endswitch
              </select>
            </div>
            @switch($categoria)
                @case(1)
                <div class="col-md-12">  
                    <table class="table table-hover table-striped ">
                     <thead>
                         <tr>
                             <td>#</td>
                             <td>Sucursal</td>
                             <td>Nota</td>
                             <td>Creación</td>
                             <td>Usuario Creación</td>
                             <td>Modificación</td>
                             <td>Usuario Modificación</td>
                         </tr>
                     </thead>
                     <tbody>
                         @if ($categoria == 1)
                         @foreach (@$sucursal as $item)
                             <tr>
                                 <td>{{$item->Id_sucursal}}</td>
                                 <td>{{$item->Sucursal}}</td>
                                 <td>{{$item->Nota}}</td>
                                 <td>{{$item->F_creacion}}</td>
                                 <td>{{$item->Id_user_c}}</td>
                                 <td>{{$item->F_modificacion}}</td>
                                 <td>{{$item->Id_user_m}}</td>
                             <tr>
                         @endforeach
                         @endif
                     </tbody>  
                 </table>
                   
                 </div>
                    @break
                @case(2)
                <div class="col-md-12">  
                    <table class="table table-hover table-striped ">
                     <thead>
                         <tr>
                             <td>#</td>
                             <td>Sucursal</td>
                             <td>Descripción</td>
                             <td>Nota</td>
                             <td>Creación</td>
                             <td>Usuario Creación</td>
                             <td>Modificación</td>
                             <td>Usuario Modificación</td>
                         </tr>
                     </thead>
                     <tbody>
                         
                         @foreach (@$unidad as $item)
                             <tr>
                                 <td>{{$item->Id_unidad}}</td>
                                 <td>{{$item->Unidad}}</td>
                                 <td>{{$item->Descripcion}}</td>
                                 <td>{{$item->Nota}}</td>
                                 <td>{{$item->F_creacion}}</td>
                                 <td>{{$item->Id_user_c}}</td>
                                 <td>{{$item->F_modificacion}}</td>
                                 <td>{{$item->Id_user_m}}</td>
                             <tr>
                         @endforeach
                         
                     </tbody>  
                 </table>
                   
                 </div>
                
                    @break
                @default
                    
            @endswitch

            
                        @break
                    @case(2)
                    <h4>clientes</h4>
                        @break
                    @default
                    
                        
                @endswitch
                
            </div>
            
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
              {{-- <button type="submit" class="btn btn-primary">Guardar cambios</button> --}}
            </div>
          </form>
          </div>
        </div>
      </div>
       
    {{-- <div id="divModal"></div>
    @section('javascript')
    <script src="{{asset('js/libs/componentes.js')}}"></script>
    <script src="{{asset('js/historial/historial.js')}}"></script>
    @endsection --}}
    
</div>
