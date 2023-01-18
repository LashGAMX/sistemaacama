<div>

    <div class="row">
      <div class="col-md-8">
        <button class="btn btn-success btn-sm" wire:click='btnCreate' data-toggle="modal" data-target="#modalEnvase" ><i class="voyager-plus"></i> Crear</button>
      </div>
      <div class="col-md-4">
        <input type="search" wire:model="search" wire:click='resetAlert' class="form-control" placeholder="Buscar">
      </div>
    </div>
    <table class="table table-sm">
        <thead class="">
            <tr>
                <th>Id</th>
                <th>Area</th>
                <th>Parametro</th>
                <th>Envase</th>
                <th>Preservador</th>
                <th>Acción</th> 
            </tr>
        </thead>
        <tbody>
        @if ($model->count()) 
        @foreach ($model as $item) 
            @if ($item->deleted_at != null)
                <tr class="bg-danger text-white">  
            @else
                <tr>
            @endif
          <td>{{$item->Id_env}}</td>
          <td>{{$item->Area}}</td>
          <td>{{$item->Parametro}}</td>
          <td>{{$item->Nombre}}</td>
          <td>{{$item->Preservacion}}</td>
          <td>
              <button type="button" class="btn btn-warning" wire:click="setData('{{$item->Id_env}}','{{$item->Id_analisis}}','{{$item->Id_parametro}}','{{$item->Id_envase}}','{{$item->Id_preservador}}','{{$item->deleted_at}}')" data-toggle="modal" data-target="#modalEnvase"><i class="voyager-edit"></i> <span hidden-sm hidden-xs>editar</span> </button>
          </td>  
        </tr>
    @endforeach
        @else
            <h6>No hay resultados para la búsqueda "{{$search}}"</h6>
        @endif
        </tbody>
    </table>
  
  
    
    <div wire:ignore.self class="modal fade" id="modalEnvase" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
                @if ($sw != true)
                    <form wire:submit.prevent="create">
                @else
                    <form wire:submit.prevent="store">
                @endif
            <div class="modal-header">
                @if ($sw != true)
                    <h5 class="modal-title" id="exampleModalLabel">Crear envase</h5>
                @else
                    <h5 class="modal-title" id="exampleModalLabel">Editar envase</h5>
                @endif
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        @if ($sw != true)
                            <div class="custom-control custom-switch">
                                <input wire:model='status' type="checkbox">
                                <label class="custom-control-label" for=""> Activo</label>
                            </div>
                        @else
                            <div class="custom-control custom-switch">
                                <input wire:model='status' type="checkbox" class="custom-control-input" id="customSwitch1">
                                <label class="custom-control-label" for="customSwitch1">Activo</label>
                            </div>
                        @endif               
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="parametro">Parametros</label>
                            @if ($sw == false)
                                <select class="form-control" wire:model='parametro' >
                            @else
                                <select class="form-control" wire:model='parametro' disabled>
                            @endif
                                <option value="0">Sin seleccionar</option>
                                @foreach ($parametros as $item)
                                    @if ($item->Envase != 0)
                                        <option value="{{$item->Id_parametro}}" style="color:crimson"> {{$item->Id_parametro}} {{$item->Parametro}} ({{$item->Tipo_formula}})</option>
                                    @else
                                        <option value="{{$item->Id_parametro}}">{{$item->Id_parametro}} {{$item->Parametro}} ({{$item->Tipo_formula}})</option>
                                    @endif
                                @endforeach
                           </select>
                          </div>
                    </div>   
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="area">Area</label>
                           <select class="form-control" wire:model='area'>
                            <option>Sin seleccionar</option>
                               @foreach ($areaLab as $item)
                                   <option value="{{$item->Id_area}}">{{$item->Area}}</option>
                               @endforeach
                           </select>
                          </div>
                    </div>     
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="envase">Envases</label>
                           <select class="form-control" wire:model='envase'>
                            <option>Sin seleccionar</option>
                               @foreach ($envases as $item)
                                   <option value="{{$item->Id_envase}}">{{$item->Nombre}} DE {{$item->Volumen}} {{$item->Unidad}}</option>
                               @endforeach
                           </select>
                          </div>
                    </div>   
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="preservador">Preservador</label>
                           <select class="form-control" wire:model='preservador'>
                               <option>Sin seleccionar</option>
                               
                               @foreach ($preservadores as $item)
                                   <option value="{{$item->Id_preservacion}}">{{$item->Preservacion}}</option>
                               @endforeach
                           </select>
                          </div>
                    </div>  
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
              <button type="submit" class="btn btn-primary">Guardar cambios</button>
            </div>
          </form> 
          </div>
        </div>
      </div>
  
      @if ($alert == true)
      <script>
        swal("Registro!", "Registro guardado correctamente!", "success");
        $('#modalEnvase').modal('hide')
      </script>
      
      @endif
  
  </div>
   
  
  