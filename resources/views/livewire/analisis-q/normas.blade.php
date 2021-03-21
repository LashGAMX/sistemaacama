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
        <button class="btn btn-success btn-sm" wire:click='btnCreate' data-toggle="modal" data-target="#modalNorma" ><i class="voyager-plus"></i> Crear</button>
      </div>
      <div class="col-md-4">
        <input type="search" wire:model="search" class="form-control" placeholder="Buscar">
      </div>
    </div>
    <table class="table table-sm">
        <thead class="">
            <tr>
                <th>Id</th>
                <th>Norma</th>
                <th>Clave_norma</th>
                <th>Ini Validez</th>
                <th>Fin Validez</th>
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
          <td>{{$item->Id_norma}}</td>
          <td>{{$item->Norma}}</td>
          <td>{{$item->Clave_norma}}</td>
          <td>{{$item->Inicio_validez}}</td>
          <td>{{$item->Fin_validez}}</td>
          <td>

                <button type="button" class="btn btn-warning" wire:click="setData('{{$item->Id_norma}}','{{$item->Norma}}','{{$item->Clave_norma}}','{{$item->Inicio_validez}}','{{$item->Fin_validez}}','{{$item->deleted_at}}')" data-toggle="modal" data-target="#modalNorma"><i class="voyager-edit"></i> <span hidden-sm hidden-xs>editar</span> </button>
                <button class="btn btn-primary btn-sm" wire:click="showDetils('{{$item->Id_norma}}')""><i class="voyager-external"></i> Ver</button>

          </td>  
        </tr>
    @endforeach
        @else
            <h6>No hay resultados para la búsqueda "{{$search}}"</h6>
        @endif
        </tbody>
    </table>


    
    <div wire:ignore.self class="modal fade" id="modalNorma" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
                @if ($sw != true)
                    <form wire:submit.prevent="create">
                @else
                    <form wire:submit.prevent="store">
                @endif
            <div class="modal-header">
                @if ($sw != true)
                    <h5 class="modal-title" id="exampleModalLabel">Crear norma</h5>
                @else
                    <h5 class="modal-title" id="exampleModalLabel">Editar norma</h5>
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
                            <label for="norma">Norma</label>
                           <input type="text" wire:model='norma' class="form-control" placeholder="Nombre norma">
                           @error('norma') <span class="text-danger">{{ $message  }}</span> @enderror
                          </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="clave">Clave de norma</label>
                           <input type="text" wire:model='clave' class="form-control" placeholder="Clave de norma">
                           @error('clave') <span class="text-danger">{{ $message  }}</span> @enderror
                          </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="inicio">Iniio validez</label>
                           <input type="date" wire:model='inicio' class="form-control" placeholder="Inicio validez">
                           @error('inicio') <span class="text-danger">{{ $message  }}</span> @enderror
                          </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="fin">Fin validez</label>
                           <input type="date" wire:model='fin' class="form-control" placeholder="Fin valdiez">
                           @error('fin') <span class="text-danger">{{ $message  }}</span> @enderror
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
   

