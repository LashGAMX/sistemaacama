<div>

    <div class="row">
      <div class="col-md-8">
        <button class="btn btn-success btn-sm" wire:click='btnCreate' data-toggle="modal" data-target="#modalCliente" ><i class="voyager-plus"></i> Crear</button>
      </div>
      <div class="col-md-4">
        <input type="search" wire:model="search" wire:click='resetAlert' class="form-control" placeholder="Buscar">
      </div>
    </div>
    <table class="table table-hover table-striped">
        <thead class="thead-dark">
            <tr>
                <th>Id</th>
                <th>Estado</th>
                <th>Localidad</th>   
                <th>Opc</th>
            </tr>
        </thead>
        <tbody>
        @if ($model->count())
        @foreach ($model as $item)
            @if ($item->deleted_at != NULL)
                <tr class="bg-danger text-white">
            @else
                <tr>
            @endif
          <td>{{$item->Id_localidad}}</td>
          <td>{{$item->estado}}</td>
          <td>{{$item->Nombre}}</td>
          <td>
            <button type="button" class="btn btn-warning"
            wire:click="setData('{{$item->Id_localidad}}','{{$item->Id_estado}}','{{$item->Nombre}}','{{$item->deleted_at}}')" data-toggle="modal" data-target="#modalCliente">
            <i class="voyager-edit"></i> <span hidden-sm hidden-xs>editar</span> </button>
          </td>
        </tr>
    @endforeach
        @else
            <h4>No hay resultados para la búsqueda "{{$search}}"</h4>
        @endif
        </tbody>
    </table>

    <div wire:ignore.self class="modal fade" id="modalCliente" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
                @if ($sw != true)
                    <form wire:submit.prevent="create">
                @else
                    <form wire:submit.prevent="store">
                @endif
            <div class="modal-header">
                @if ($sw != true)
                    <h5 class="modal-title" id="exampleModalLabel">Crear localidad</h5>
                @else
                    <h5 class="modal-title" id="exampleModalLabel">Editar localidad</h5>
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
                                <input wire:model='status' type="checkbox" class="custom-control-input" id="customSwitch1">
                                <label class="custom-control-label" for="customSwitch1">Activo</label>
                            </div>
                        @else
                            <div class="custom-control custom-switch">
                                <input wire:model='status' type="checkbox" class="custom-control-input" id="customSwitch1">
                                <label class="custom-control-label" for="customSwitch1">Status</label>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="">Estado</label>
                                <select class="form-control" wire:model='estado' >
                                <option value="0">Sin seleccionar</option>
                                @foreach ($estados as $item)
                                    <option value="{{$item->Id_estado}}">{{$item->Nombre}}</option>
                                @endforeach
                              </select>
                              @error('estado') <span class="text-danger">{{ $message  }}</span> @enderror
                          </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="localidad">Localidad</label>
                           <input type="text" wire:model='localidad' class="form-control" placeholder="localidad">
                           @error('localidad') <span class="text-danger">{{ $message  }}</span> @enderror
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
  $('#modalCliente').modal('hide')
</script>
@endif


  </div>


