<div>
    <div class="row">
      <div class="col-md-8">
        <button class="btn btn-success btn-sm" wire:click="btnCreate" data-toggle="modal" data-target="#modalDetalleCliente" ><i class="voyager-plus"></i> Crear</button>
      </div>
      <div class="col-md-4">
        {{-- <input type="search" wire:model="search" class="form-control" placeholder="Buscar"> --}}
      </div>
    </div>
    
    <table class="table table-hover table-striped">
      <thead class="thead-dark">
            <tr>
                <th>Id</th>
                <th>Nombre</th>
                <th>Estado</th>
                <th>Tipo cliente</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
        @if ($model->count()) 
        @foreach ($model as $item) 
        <tr>  

          <td>{{$item->Id_sucursal}}</td>
          <td>{{$item->Empresa}}</td>          
          <td>{{$item->Estado}}</td>
          @if ($item->Id_siralab == 1)
            <td>Reporte</td>
          @else
            @if ($item->Id_siralab == 2) 
            <td>Reporte / Siralab</td>
            @else
            <td>Sin seleccionar</td>
            @endif
          @endif
          <td>
            <button type="button" class="btn btn-warning" wire:click="setData('{{$item->Id_sucursal}}','{{$item->Empresa}}','{{$item->Estado}}','{{$item->Id_siralab}}','{{$item->deleted_at}}')"  data-toggle="modal" data-target="#modalDetalleCliente"><i class="voyager-edit"></i> <span hidden-sm hidden-xs>editar</span> </button>
            <button class="btn btn-primary btn-sm" wire:click="btnDetails('{{$item->Id_sucursal}}')" ><i class="voyager-plus"></i> Ver</button>
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
        @if ($sw == 1)
          <form wire:submit.prevent="create">
        @else 
          <form wire:submit.prevent="store">
        @endif
        <div class="modal-header">
          @if ($sw == 1)
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
                <div class="col-md-6">
                    <div class="custom-control custom-switch">
                      <input wire:model='status' type="checkbox" class="custom-control-input" id="customSwitch1">
                      <label class="custom-control-label" for="customSwitch1">Status</label>
                  </div>
                </div>
                <div class="col-md-6">
                    <label for="">Nombre empresa |</label>
                        <input wire:click="duplicateMatriz" type="checkbox" class="custom-control-input">
                        <label class="custom-control-label" for="customSwitch1">Nombre Matriz</label>
                    <input type="text" wire:model='empresa' class="form-control" placeholder="Sucursal">
                    @error('empresa') <span class="text-danger">{{ $message  }}</span> @enderror
                </div>
              <div class="col-md-6">
                <label>Estado</label>
                <select class="form-control" wire:model='estado' >
                  <option value="0">Sin seleccionar</option>
                  @foreach ($estados as $item)
                  <option value="{{$item->Nombre}}">{{$item->Nombre}}</option>
              @endforeach
                </select>
              </div>
              <div class="col-md-6">
                <label>Tipo</label>
                <select class="form-control" wire:model='tipo' >
                  @if ($tipo == 0)
                    <option value="0" selected>Sin seleccionar</option>
                  @else
                    <option value="0">Sin seleccionar</option>  
                  @endif
                  @if ($tipo == 1)
                    <option value="1" selected>Reporte</option>
                  @else
                    <option value="1">Reporte</option>
                  @endif
                  @if ($tipo == 2)
                    <option value="2" selected>Reporte / Siralab</option>
                  @else
                    <option value="2">Reporte / Siralab</option>
                  @endif
                </select>
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
   
