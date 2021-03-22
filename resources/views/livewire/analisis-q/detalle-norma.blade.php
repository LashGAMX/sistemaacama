<div>
    @if ($alert === true)
    <div class="alert alert-success" role="alert">
        {{$msg}}
  </div>   
@endif
@if ($alert === false)
    <div class="alert alert-success" role="alert">
    {{$msg}}
  </div>   
@endif
<script type="text/javascript">
        setTimeout(function() {
            $(".alert").fadeOut(1500);
        },6000);
</script>
    <div class="row">
      <div class="col-md-8">
        <button class="btn btn-success btn-sm"  data-toggle="modal" data-target="#modalSubNorma" wire:click="btnCreate"><i class="voyager-plus"></i> Crear</button>
      </div>
      {{-- <div class="col-md-4">
        <input type="search" wire:model="search" class="form-control" placeholder="Buscar">
      </div> --}}
    </div>
    <table class="table table-hover table-striped">
        <thead class="thead-dark">
            <tr>
                <th>Id</th>
                <th>Sub Norma</th>
                <th>Clave</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
        @if ($model->count()) 
        @foreach ($model as $item) 
        <tr>  
          {{-- <form wire:submit.prevent="update"> --}}
          <td>{{$item->Id_subnorma}}</td>
          <td>{{$item->Norma}}</td>          
          <td>{{$item->Clave}}</td>
          <td>
            <button type="button" class="btn btn-warning btn-sm" wire:click="setData('{{$item->Id_subnorma}}','{{$item->Norma}}','{{$item->Clave}}','{{$item->deleted_at}}')"  data-toggle="modal" data-target="#modalSubNorma"><i class="voyager-edit"></i> <span hidden-sm hidden-xs>editar</span> </button>
            <button class="btn btn-primary btn-sm" wire:click="showDetils('{{$item->Id_subnorma}}')"><i class="voyager-external"></i> Ver</button>
          </td>
          {{-- </form>  --}}
        </tr>
    @endforeach
        @else
            <h6>No hay resultados para la búsqueda "{{$search}}"</h6>
        @endif
        </tbody>
    </table>
    <div wire:ignore.self class="modal fade" id="modalSubNorma" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
                @if ($sw != true)
                    <form wire:submit.prevent="create">
                @else
                    <form wire:submit.prevent="store">
                @endif
            <div class="modal-header">
                @if ($sw != true)
                    <h5 class="modal-title" id="exampleModalLabel">Crear sub norma</h5>
                @else
                    <h5 class="modal-title" id="exampleModalLabel">Editar sub norma</h5>
                @endif
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                            <input type="text" wire:model="idNorma" hidden>
                            <div class="custom-control custom-switch">
                                <input wire:model='status' type="checkbox" class="custom-control-input" id="customSwitch1">
                                <label class="custom-control-label" for="customSwitch1">Activo</label>
                            </div> 
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="sub">Sub norma</label><label  for="">| Usar nombre padre</label><input wire:model='stdNorma' wire:click='setName' type="checkbox">
                           <input type="text" wire:model='sub' class="form-control" placeholder="Nombre sub norma">
                           @error('sub') <span class="text-danger">{{ $message  }}</span> @enderror
                          </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="clave">Clave de sub norma</label>
                           <input type="text" wire:model='clave' class="form-control" placeholder="Clave sub norma">
                           @error('clave') <span class="text-danger">{{ $message  }}</span> @enderror
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
    
  </div>
   
