<div>

    <div class="row">
      <div class="col-md-8">
        <button class="btn btn-success btn-sm" wire:click='btnCreate' data-toggle="modal" data-target="#modalDetalleInter" ><i class="voyager-plus"></i> Crear</button>
      </div>
      <div class="col-md-4">
        <input type="search" wire:model="search" wire:click='resetAlert' class="form-control" placeholder="Buscar">
      </div>
    </div>
    <table class="table table-hover">
        <thead class="thead-dark">
            <tr>
                <th>Id</th> 
                <th>Intermediario</th>
                <th>Nivel</th>
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
          <td>{{$item->Id_cliente}}</td>
          <td>{{$item->Nombres}} {{$item->A_paterno}}</td> 
          <td>{{$item->Nivel}} - %{{$item->DescNivel}}</td>
          <td>
            <button type="button" class="btn btn-warning" 
            wire:click="setData('{{$item->Id_detalle}}','{{$item->Id_cliente}}','{{$item->Id_nivel}}','{{$item->deleted_at}}')"
         data-toggle="modal" data-target="#modalDetalleInter">
            <i class="voyager-edit"></i> <span hidden-sm hidden-xs>editar</span> </button>
            <button type="button" class="btn btn-primary" wire:click="showDetils('{{$item->Id_cliente}}')"><i class="voyager-external"></i> <span hidden-sm hidden-xs>ver</span> </button>
          </td>
        </tr>
    @endforeach
        @else
            <h6>No hay resultados para la búsqueda "{{$search}}"</h6>
        @endif
        </tbody>
    </table>
  
    <div wire:ignore.self class="modal fade" id="modalDetalleInter" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
                @if ($sw != true)
                    <form wire:submit.prevent="create">
                @else
                    <form wire:submit.prevent="store">
                @endif
            <div class="modal-header">
                @if ($sw != true)
                    <h5 class="modal-title" id="exampleModalLabel">Crear nivel intermediario</h5>
                @else
                    <h5 class="modal-title" id="exampleModalLabel">Editar nivel intermediario</h5>
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
                            <label for="">Intermediarios</label>
                            @if ($sw != true)
                            <select class="form-control" wire:model='intermediario'>
                            @else
                              <select class="form-control" wire:model='intermediario' disabled>
                            @endif
                                <option value="0">Sin seleccionar</option>
                                @foreach ($intermediarios as $item)
                                    <option value="{{$item->Id_cliente}}">{{$item->Nombres}} {{$item->A_paterno}}</option>
                                @endforeach
                              </select>
                              @error('intermediario') <span class="text-danger">{{ $message  }}</span> @enderror
                          </div>
                    </div>
                    <div class="col-md-12">
                      <div class="form-group">
                          <label for="">Nivel</label>
                              <select class="form-control" wire:model='nivel' required>
                              <option value="0">Sin seleccionar</option>
                              @foreach ($niveles as $item)
                                  <option value="{{$item->Id_nivel}}">{{$item->Nivel}} - %{{$item->Descuento}}</option>
                              @endforeach
                            </select>
                            @error('nivel') <span class="text-danger">{{ $message  }}</span> @enderror
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
    @if ($error == true)
        <script>
            swal("Error!", "Este cliente ya se encuentra registrado!", "error");
            $('#modalDetalleInter').modal('hide')
        </script>
    @else
        <script>
            swal("Registro!", "Registro guardado correctamente!", "success");
            $('#modalDetalleInter').modal('hide')
        </script>  
    @endif
@endif
    
  </div>
   

