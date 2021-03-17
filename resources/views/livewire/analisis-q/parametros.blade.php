<div>
    <div class="row">
      <div class="col-md-8">
        <button class="btn btn-success btn-sm" wire:click='btnCreate' data-toggle="modal" data-target="#modalParametro" ><i class="voyager-plus"></i> Crear</button>
      </div>
      <div class="col-md-4">
        <input type="search" wire:model="search" class="form-control" placeholder="Buscar">
      </div>
    </div>
    <table class="table ">
        <thead class="thead-dark">
            <tr>
                <th>Id</th>
                <th>Laboratorio</th>
                <th>Tipo formula</th>
                <th>Matriz</th>
                <th>Rama</th>
                <th>Parametro</th>
                <th>Unidad</th>
                <th>Metodo de prueba</th>
                <th>Norma</th>
                <th>Limite C.</th>
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
          <td>{{$item->Id_parametro}}</td>
          <td>{{$item->Laboratorio}}</td>
          <td>{{$item->Tipo_formula}}</td>
          <td>{{$item->Matriz}}</td>
          <td>{{$item->Rama}}</td>
          <td>{{$item->Parametro}}</td>
          <td>{{$item->Unidad}}</td>
          <td>{{$item->Metodo_prueba}}</td>
          <td>{{$item->Norma}}</td>
          <td>{{$item->Limite}}</td>
          <td>{{$item->created_at}}</td>
          <td>{{$item->updated_at}}</td>
          <td>
            <button type="button" class="btn btn-warning" 
            wire:click="setData()" data-toggle="modal" data-target="#modalCliente">
            <i class="voyager-edit"></i> <span hidden-sm hidden-xs>editar</span> </button>

          </td>
        </tr>
    @endforeach
        @else
            <h6>No hay resultados para la búsqueda "{{$search}}"</h6>
        @endif
        </tbody>
    </table>
  
    {{-- <div wire:ignore.self class="modal fade" id="modalCliente" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="cliente">Cliente</label>
                           <input type="text" wire:model='cliente' class="form-control" placeholder="cliente">
                           @error('cliente') <span class="text-danger">{{ $message  }}</span> @enderror
                          </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="rfc">RFC</label>
                           <input type="text" wire:model='rfc' class="form-control" placeholder="RFC">
                           @error('rfc') <span class="text-danger">{{ $message  }}</span> @enderror
                          </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="">Intermediario</label>
                            @if ($sw != true)
                                <select class="form-control" wire:model='inter' >
                                <option value="0">Sin seleccionar</option>
                                @foreach ($intermediario as $item)
                                    <option value="{{$item->Id_intermediario}}">{{$item->Nombres}} {{$item->A_paterno}}</option>
                                @endforeach
                              </select>
                            @else
                                <select class="form-control" wire:model='idInter' >
                                    <option value="0">Sin seleccionar</option>
                                    @foreach ($intermediario as $item)
                                        @if ($idInter == $item->Id_intermediario)
                                        <option value="{{$item->Id_intermediario}}" selected>{{$item->Nombres}} {{$item->A_paterno}}</option>
                                        @else
                                        <option value="{{$item->Id_intermediario}}">{{$item->Nombres}} {{$item->A_paterno}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            @endif
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
      </div> --}}
    
  </div>
   

