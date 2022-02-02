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
              <th>Envase</th>
              <th>Volumen</th>
              <th>Unidad</th>
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
        <td>{{$item->Id_envase}}</td>
        <td>{{$item->Nombre}}</td>
        <td>{{$item->Volumen}}</td>
        <td>{{$item->Id_unidad}}</td>
        <td>
            <button type="button" class="btn btn-warning" wire:click="setData('{{$item->Id_envase}}','{{$item->Nombre}}','{{$item->Volumen}}','{{$item->Id_unidad}}','{{$item->deleted_at}}')" data-toggle="modal" data-target="#modalEnvase"><i class="voyager-edit"></i> <span hidden-sm hidden-xs>editar</span> </button>
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
                          <input type="text" wire:model="idNorma" hidden>
                          <div class="custom-control custom-switch">
                              <input wire:model='status' type="checkbox" class="custom-control-input" id="customSwitch1">
                              <label class="custom-control-label" for="customSwitch1">Activo</label>
                          </div>
                      @endif               
                  </div>
                  <div class="col-md-12">
                      <div class="form-group">
                          <label for="nombre">Nombre</label>
                         <input type="text" wire:model='nombre' class="form-control" placeholder="Nombre envase">
                        </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="volumen">Volumen</label>
                         <input type="text" wire:model='vol' class="form-control" placeholder="Volumen">
                        </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="unidad">Unidad</label>
                         <select class="form-control" wire:model='unidad'>
                             @foreach ($unidades as $item)
                                 <option value="{{$item->Id_unidad}}">{{$item->Unidad}}</option>
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
 

