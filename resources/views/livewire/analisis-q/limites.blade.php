<div>
    
    <div class="row"> 
        <div class="col-md-8">
          {{-- <button class="btn btn-success btn-sm" wire:click="setBtn" hidden><i class="voyager-plus"></i> Crear</button> --}}
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
              <th>Acción</th>
          </tr>
      </thead>
      <tbody>
      @if ($model->count()) 
      @foreach ($model as $item) 
      <tr>  
        <td>{{$item->Id_parametro}}</td>
        <td>{{$item->Parametro}}</td>          
        <td>
            <button class="btn btn-primary btn-sm" wire:click="details('{{$item->Id_parametro}}')"><i class="voyager-external"></i> Ver</button>
        </td>
      </tr>
  @endforeach
      @else
          <h6>No hay resultados para la búsqueda "{{$search}}"</h6>
      @endif
      </tbody>
  </table>
    
</div>
