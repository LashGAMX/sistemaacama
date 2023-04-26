<div>
    <div class="row">
      <div class="col-md-8">
        <button class="btn btn-success btn-sm" wire:click='btnCreate' data-toggle="modal" data-target="#modalInter" ><i class="voyager-plus"></i> Crear</button>
      </div>
      <div class="col-md-4">
        <input type="search" wire:model="search" wire:click='resetAlert' class="form-control" placeholder="Buscar">
      </div>
    </div>
    <table class="table table-dark">
        <thead>
            <tr>
                <th>Id</th>
                <th>Laboratorio</th>
                <th>Nombre</th>
                <th>RFC</th>
                <th>Correo</th>
                <th>Dirección</th>
                <th>Teléfono</th>
                <th>Usuario</th>
                <th>Creación</th>
                <th>Modificación</th>
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
          {{-- <form wire:submit.prevent="update"> --}}
          <td>{{$item->Id_intermediario}}</td>
          <td>{{$item->Sucursal}}</td>      
          <td>{{$item->Nombres}} {{$item->A_paterno}}</td>    
          <td>{{$item->RFC}}</td>
          <td>{{$item->Correo}}</td>
          <td>{{substr($item->Direccion,0,100)}}</td>
          <td>{{$item->Tel_oficina}}</td>
          <td>{{@$item->Id_usuario}}</td>
          <td>{{$item->created_at}}</td>
          <td>{{$item->updated_at}}</td>
          <td>
            <button type="button" class="btn btn-primary" 
            wire:click="setData('{{$item->Id_cliente}}','{{$item->Nombres}}','{{$item->A_paterno}}','{{$item->A_materno}}','{{$item->RFC}}','{{$item->deleted_at}}','{{$item->Id_laboratorio}}','{{$item->Correo}}','{{$item->Direccion}}','{{$item->Tel_oficina}}','{{$item->Extension}}','{{$item->Celular1}}','{{$item->Id_usuario}}')" data-toggle="modal" data-target="#modalInter">
            <i class="voyager-edit"></i> <span hidden-sm hidden-xs>editar</span> </button>
          </td>
          {{-- </form>  --}}
        </tr>
    @endforeach
        @else
            <h4>No hay resultados para la búsqueda "{{$search}}"</h4>
        @endif
        </tbody>
    </table>
  
    
    <div wire:ignore.self class="modal fade" id="modalInter" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
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
                <div class="col-md-3">
                    @if ($sw != true)
                        <div class="custom-control custom-switch">
                            <input wire:model='status' type="checkbox" class="custom-control-input" id="customSwitch1">
                            <label class="custom-control-label" for="customSwitch1">Status</label>
                        </div>
                    @else
                        <input type="text" wire:model="idCliente" hidden>
                        <div class="custom-control custom-switch">
                            <input wire:model='status' type="checkbox" class="custom-control-input" id="customSwitch1">
                            <label class="custom-control-label" for="customSwitch1">Status</label>
                        </div>
                    @endif      
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="">Sucursal</label>
                            <select class="form-control" id="" wire:model='lab' name="sucursal">
                            @foreach ($sucursal as $item)
                                <option value="{{$item->Id_sucursal}}">{{$item->Sucursal}}</option>
                            @endforeach
                          </select>
                      </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                       <input type="text" wire:model='nombre' class="form-control" placeholder="Nombre cliente">
                       @error('nombre') <span class="text-danger">{{ $message  }}</span> @enderror
                      </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="paterno">Apellido paterno</label>
                        <input type="text" wire:model='paterno' class="form-control" id="paterno" name="paterno" placeholder="Apellido paterno">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="materno">Apellido materno</label>
                        <input type="text" wire:model='materno' class="form-control" id="materno" name="materno" placeholder="Apellido materno">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="rfc">RFC</label>
                        <input type="text" class="form-control" wire:model='rfc' id="rfc" name="rfc" placeholder="RFC">
                        @error('rfc') <span class="text-danger">{{ $message  }}</span> @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="correo">Correo</label>
                        <input type="email" class="form-control" wire:model='correo' name="correo" id="correo" placeholder="correo@ejemplo.com">
                        @error('correo') <span class="text-danger">{{ $message  }}</span> @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="dir">Dirección</label>
                        <input type="text" class="form-control" wire:model='dir' name="dir" id="dir" placeholder="Dirección">
                        @error('dir') <span class="text-danger">{{ $message  }}</span> @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="tel">Telefono</label>
                        <input type="number" class="form-control" wire:model='tel' name="tel" id="tel" placeholder="Telefono">
                        @error('tel') <span class="text-danger">{{ $message  }}</span> @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="ext">Extensión</label>
                        <input type="number" class="form-control" wire:model='ext' name="ext" id="ext" placeholder="Extensión">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="cel">Celular</label>
                        <input type="number" class="form-control" wire:model='cel' name="cel" id="cel" placeholder="Celular">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="user">Usuario</label>
                        <input type="number" class="form-control" wire:model='user' name="user" id="user" placeholder="user">
                    </div>
                </div>
                @if ($sw == true)               
                <div class="col-md-12">
                <div class="form-group">
                    <input type="text" wire:model="nota" hidden>
                    <label for="">Nota</label>
                    <input type="text" wire:model='nota' class="form-control" placeholder="Nota"> 
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
  $('#modalInter').modal('hide')
</script>
@endif

  </div>
   

