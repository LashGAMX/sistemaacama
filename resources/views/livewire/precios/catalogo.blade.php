<div>
    <div class="row">
      <div class="col-md-8">
        <button class="btn btn-success btn-sm" wire:click="btnCreate" data-toggle="modal" data-target="#modalDetalleCliente" ><i class="voyager-plus"></i> Crear</button>
      </div>
      <div class="col-md-4">
        <input type="search" wire:model="search" class="form-control" placeholder="Buscar">
      </div>
    </div>
    
    <table class="table table-hover table-striped">
      <thead class="thead-dark">
            <tr>
                <th>Id</th>
                <th>Parametro</th>
                <th>Tipo fórmula</th>
                <th>Matriz</th>
                <th>Rama</th>
                <th>Unidad</th>
                <th>Precio</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
        @if ($model->count())  
        @foreach ($model as $item) 
        <tr>  

          <td>{{$item->Id_precio}}</td>
          <td>{{$item->Parametro}}</td>          
          <td>{{$item->Tipo_formula}}</td>
          <td>{{$item->Matriz}}</td>
          <td>{{$item->Rama}}</td>
          <td>{{$item->Unidad}}</td>
          <td>$ {{$item->Precio}}</td>
          <td>
            <button type="button" class="btn btn-warning"  data-toggle="modal" data-target="#modalDetalleCliente"><i class="voyager-edit"></i> <span hidden-sm hidden-xs>editar</span> </button>
          </td>

        </tr>
    @endforeach
        @else
            <h4>No hay resultados para la búsqueda "{{$search}}"</h4>
        @endif
        </tbody>
    </table>
  
  
    
    <div wire:ignore.self class="modal fade" id="modalDetalleCliente" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        @if ($sw == false)
          <form wire:submit.prevent="create">
        @else 
          <form wire:submit.prevent="store">
        @endif
        <div class="modal-header">
          @if ($sw == false)
            <h5 class="modal-title" id="exampleModalLabel">Crear sucursal cliente</h5>
          @else 
            <h5 class="modal-title" id="exampleModalLabel">Editar sucursal cliente</h5>
          @endif         
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="custom-control custom-switch">
                      <input wire:model='status' type="checkbox" class="custom-control-input" id="customSwitch1">
                      <label class="custom-control-label" for="customSwitch1">Activo</label>
                  </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Parametro</label>
                        <select class="form-control" wire:model='parametro' >
                          <option value="0">Sin seleccionar</option>
                          @foreach ($parametros as $item)
                          <option value="{{$item->Id_parametro}}">{{$item->Parametro}}</option>
                             @endforeach
                        </select>
                    </div>
                  </div>
                <div class="col-md-12">
                    <label for="">Precio</label>
                        <label>Precio</label>
                        <input type="number" wire:model='precio' class="form-control" placeholder="Precio">
                        @error('precio') <span class="text-danger">{{ $message  }}</span> @enderror
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

  </div>
   
