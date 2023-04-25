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
                <th>Cliente</th>
                <th>Id inter</th>
                <th>Intermediario</th>
                <th>Creación</th>
                <th>Creó</th>
                <th>Modificación</th>
                <th>Modificó</th>
                <th>Acción</th>
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
          <td>{{$item->Id_cliente}}</td>
          <td>{{$item->Empresa}}</td>
          <td>{{$item->Id_intermediario}}</td>
          <td>{{$item->Nombres}} {{$item->A_paterno}}</td>
          <td>{{$item->created_at}}</td> 
          <td>{{$item->Id_user_c}}</td>
          <td>{{$item->updated_at}}</td> 
          <td>{{$item->Id_user_m}}</td>
          <td>
            <button type="button" class="btn btn-warning"
            wire:click="setData('{{$item->Id_cliente}}','{{$item->Empresa}}','{{$item->RFC}}','{{$item->Id_intermediario}}','{{$item->deleted_at}}')" data-toggle="modal" data-target="#modalCliente">
            <i class="voyager-edit"></i> <span hidden-sm hidden-xs>editar</span> </button>
            <button type="button" wire:click="detalle({{$item->Id_cliente}})" class="btn btn-primary"><i class="voyager-external"></i> <span hidden-sm hidden-xs>ver</span> </button>
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
                    <h5 class="modal-title" id="exampleModalLabel">Crear intermediario</h5>
                @else
                    <h5 class="modal-title" id="exampleModalLabel">Editar intermediario</h5>
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
                            <input type="text" wire:model="idCliente" hidden>
                            <div class="custom-control custom-switch">
                                <input wire:model='status' type="checkbox" class="custom-control-input" id="customSwitch1">
                                <label class="custom-control-label" for="customSwitch1">Status</label>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="cliente">Cliente</label>
                           <input type="text" wire:model='cliente' class="form-control" placeholder="cliente">
                           @error('cliente') <span class="text-danger">{{ $message  }}</span> @enderror
                          </div>
                    </div>
                    {{-- <div class="col-md-12">
                        <div class="form-group"> 
                            <label for="rfc">RFC</label>
                           <input type="text" wire:model='rfc' class="form-control" placeholder="RFC">
                           @error('rfc') <span class="text-danger">{{ $message  }}</span> @enderror
                          </div>
                    </div> --}}
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="">Intermediario</label>
                                <select class="form-control" wire:model='inter' >
                                <option value="0">Sin seleccionar</option>
                                @foreach ($intermediario as $item)
                                    <option value="{{$item->Id_intermediario}}">{{$item->Nombres}} {{$item->A_paterno}}</option>
                                @endforeach
                              </select>
                              @error('inter') <span class="text-danger">{{ $message  }}</span> @enderror
                          </div>
                    </div>
                    @if ($sw == true)               
                <div class="col-md-12">
                <div class="form-group">
                    <input type="text" wire:model="nota" hidden>
                    <label for="">Nota</label>
                    <input type="text" wire:model='nota' class="form-control" placeholder="Nota" required> 
                    @error('nota') <span class="text-danger">{{ $message  }}</span> @enderror
                  </div>
                </div>
                @endif
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


