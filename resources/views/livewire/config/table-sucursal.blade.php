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
    <form wire:submit.prevent="setSucursal">
    <div class="col-md-4">
      <input type="text" wire:model='idUser' hidden>
        <input type="text" wire:model='name' class="form-control" placeholder="Sucursal">
        @error('name') <span class="text-danger">{{ $message  }}</span> @enderror
    </div>
    <div class="col-md-4">
      <button class="btn btn-sm btn-success" ><i class="voyager-check"></i> <span hidden-sm hidden-xs>Aceptar</span> </button>
      <button class="btn btn-sm btn-danger"  wire:click="deleteBtn"><i class="voyager-x"></i> <span hidden-sm hidden-xs>Cancel</span> </button>
    </div>
  </form>
  </div>  
@endif
  <table id="dataTable" class="table table-hover">
      <thead class="thead-dark">
          <tr>
              <th>Id</th>
              <th>Sucursal</th>
              <th>Creación</th>
              <th>Modificación</th>
              <th>Acción</th>
          </tr>
      </thead>
      <tbody>
      @if ($sucursal->count()) 
      @foreach ($sucursal as $item) 
      <tr>
        <td>{{$item->Id_sucursal}}</td>
        @if ($showEdit[$cont++] == true)
          <td><input type="text" wire:model='name' value="{{$item->Sucursal}}" class="form-control" placeholder="Sucursal"></td>
        @else
          <td>{{$item->Sucursal}}</td>          
        @endif
        <td>{{$item->created_at}}</td>
        <td>{{$item->updated_at}}</td>
        <td>
            <button class="btn btn-sm btn-info" wire:click="setBtnEdit($cont)"><i class="voyager-edit"></i> <span hidden-sm hidden-xs>editar</span> </button>
        </td>
      </tr>
  @endforeach
      @else
          <h4>No hay resultados para la búsqueda "{{$search}}"</h4>
      @endif
      </tbody>
  </table>
</div>
 