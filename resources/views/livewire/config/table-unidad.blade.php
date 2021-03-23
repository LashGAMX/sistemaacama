<div>
    <div class="row">
      <div class="col-md-8">
        <button class="btn btn-success btn-sm" wire:click="setBtn"><i class="voyager-plus"></i> Crear</button>
      </div>
      <div class="col-md-4">
        <input type="search" wire:model="search" class="form-control" placeholder="Buscar">
      </div>
    </div>
    @if ($show != false)  
    <div class="row">
      <form wire:submit.prevent="create">
      <div class="col-md-2">
        <input type="text" wire:model='idUser' hidden>
        <label for="">Unidad</label>
          <input type="text" wire:model='name' class="form-control" placeholder="Unidad">
          @error('name') <span class="text-danger">{{ $message  }}</span> @enderror
      </div>
      <div class="col-md-4">
        <input type="text" wire:model='idUser' hidden>
        <label for="">Descripcion</label>
          <input type="text" wire:model='description' class="form-control" placeholder="Descripción">
          @error('description') <span class="text-danger">{{ $message  }}</span> @enderror
      </div>
      <div class="col-md-4">
        <button class="btn btn-sm btn-success" type="submit" ><i class="voyager-check"></i> <span hidden-sm hidden-xs>Aceptar</span> </button>
        <button class="btn btn-sm btn-danger" type="button"  wire:click="deleteBtn"><i class="voyager-x"></i> <span hidden-sm hidden-xs>Cancel</span> </button>
      </div>
    </form>
    </div>  
  @endif
    <table class="table table-hover table-striped">
        <thead class="thead-dark">
            <tr>
                <th>Id</th>
                <th>Unidad</th>
                <th>Descripcion</th>
                <th>Creación</th>
                <th>Modificación</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
        @if ($model->count()) 
        @foreach ($model as $item) 
        <tr>  
          {{-- <form wire:submit.prevent="update"> --}}
          <td>{{$item->Id_unidad}}</td>
          <td>{{$item->Unidad}}</td>          
          <td>{{$item->Descripcion}}</td>
          <td>{{$item->created_at}}</td>
          <td>{{$item->updated_at}}</td>
          <td>
            <button type="button" class="btn btn-primary" wire:click="setData('{{$item->Id_unidad}}','{{$item->Unidad}}','{{$item->Descripcion}}')"  data-toggle="modal" data-target="#modalUnidad"><i class="voyager-edit"></i> <span hidden-sm hidden-xs>editar</span> </button>
          </td>
          {{-- </form>  --}}
        </tr>
    @endforeach
        @else
            <h4>No hay resultados para la búsqueda "{{$search}}"</h4>
        @endif
        </tbody>
    </table>
  
  
    
    <div wire:ignore.self class="modal fade" id="modalUnidad" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form wire:submit.prevent="store">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Modificar unidad</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                    <input type="text" wire:model="idUni" hidden>
                    <label for="">Unidad</label>
                    <input type="text" wire:model='name' class="form-control" placeholder="Unidad">
                    @error('name') <span class="text-danger">{{ $message  }}</span> @enderror
                </div>
                <div class="col-md-12">
                    <label for="">Descripción</label>
                    <input type="text" wire:model='description' class="form-control" placeholder="Descripción">
                    @error('description') <span class="text-danger">{{ $message  }}</span> @enderror
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

   {{-- Alerta --}}
   @if ($alert == true)
   <script>
     swal("Registro!", "Registro guardado correctamente!", "success");
     $('#modalUnidad').modal('hide')
   </script>
   @endif
   
    
  </div>
   
