<div>

    <div class="row">
      <div class="col-md-8">
        <button class="btn btn-success btn-sm" wire:click='btnCreate' data-toggle="modal" data-target="#modalTermometro" ><i class="voyager-plus"></i> Crear</button>
      </div>
      <div class="col-md-4">
        {{-- <input type="search" wire:model="search" wire:click='resetAlert' class="form-control" placeholder="Buscar"> --}}
      </div>
    </div>
   <div class="col-md-12">
    <table class="table" id="tableEquipo">
        <thead class="thead-dark">
            <tr>
                <th>Id</th>
                <th>Equipo</th>
                <th>Marca</th>
                <th>Modelo</th>
                <th>Serie</th>
                <th>Tipo</th>
                <th>Muestreador</th>
                <th>Opc</th>
            </tr>
        </thead>
        <tbody>
        @if ($model->count())
        @foreach ($model as  $item)
            @if ($item->deleted_at != NULL)
                <tr class="bg-danger text-white">
            @else
                <tr>
            @endif
          <td>{{$item->Id_termometro}}</td>
          <td>{{$item->Equipo}}</td>
          <td>{{$item->Marca}}</td>
          <td>{{$item->Modelo}}</td>
          <td>{{$item->Serie}}</td>
          @if (@$item->Tipo == 1)
              <td>Termometro</td>
          @else
              <td>Potenciometro</td>
          @endif
          <td>
            <button type="button" class="btn btn-warning" data-toggle="modal"
            wire:click="setData('{{$item->Id_termometro}}')" data-target="#modalTermometro">
            <i class="voyager-edit"></i> <span hidden-sm hidden-xs>editar</span> </button>
            <button type="button" class="btn btn-info" data-toggle="modal"
            wire:click="setData2('{{$item->Id_termometro}}')" data-target="#modalCalibracion">
            <i class="voyager-list"></i> <span hidden-sm hidden-xs> Calibración</span> </button>
          </td>
        </tr>
    @endforeach
        @else
            {{-- <h4>No hay resultados para la búsqueda "{{$search}}"</h4> --}}
        @endif
        </tbody>
    </table>
   </div>
    <div wire:ignore.self class="modal fade" id="modalTermometro" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
                @if ($sw != true)
                    <form wire:submit.prevent="create">
                @else
                    <form wire:submit.prevent="store">
                @endif
            <div class="modal-header">
                @if ($sw != true)
                    <h5 class="modal-title" id="exampleModalLabel">Crear equipo</h5>
                @else
                    <h5 class="modal-title" id="exampleModalLabel">Editar equipo</h5>
                @endif
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input wire:model='status' type="checkbox" class="custom-control-input" id="customSwitch1">
                                <label class="custom-control-label" for="customSwitch1">Activo</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="">Equipo</label>
                            <input type="text" class="form-control" wire:model="equipo" placeholder="Equipo" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="">Marca</label>
                            <input type="text" class="form-control" wire:model="marca" placeholder="Marca" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="">Modelo</label>
                            <input type="text" class="form-control" wire:model="modelo" placeholder="Modelo" required>
                        </div>
                    </div>
                    <div class="col-md-4"> 
                        <div class="form-group">
                            <label for="">Serie</label>
                            <input type="text" class="form-control" wire:model="serie" placeholder="Serie" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Tipo</label>
                            <select class="form-control" wire:model="tipo" placeholder="Tipo" required>
                                <option value="0">Sin seleccionar</option>
                                <option value="1">Termometro</option>
                                <option value="2">Potenciometro</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Muestreador</label>
                            <select class="form-control" wire:model='muestreador' >
                                <option value="0">Sin seleccionar</option>
                                @foreach ($muestreadores as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                              </select>
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

      <div wire:ignore.self class="modal fade" id="modalCalibracion" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
                
            <div class="modal-header">
                @if ($sw != true)
                    <h5 class="modal-title" id="exampleModalLabel">Crear equipo</h5>
                @else
                    <h5 class="modal-title" id="exampleModalLabel">Editar equipo</h5>
                @endif
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <form wire:submit.prevent="storeFactor">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th># {{$idTermo}}</th>
                                    <th>De C°</th>
                                    <th>A C°</th>
                                    <th>Factor de corrección</th>
                                    <th>Factor de corección aplicado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $contF = 0;
                                @endphp
                                @foreach ($factores as $item)
                                    <tr>
                                        <td>{{$contF = $contF + 1}}</td>
                                        <td>{{$item->De_c}}</td>
                                        <td>{{$item->A_c}}</td>
                                        <td> 
                                            <div class="form-group">
                                                <input type="text" class="form-control" placeholder="Factor de correccion" wire:model="fa{{$contF}}" value="{{$item->Factor}}">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input type="text" class="form-control" placeholder="Factor de correccion aplicado" wire:model="apl{{$contF}}" value="{{$item->Factor_aplicado}}">
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            
                        </table>
                        <div class="col-md-12">
                        </div>
                    </div>
                </div>
            
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
              <button type="submit" class="btn btn-primary">Guardar cambios</button>
            </form>
            </div>
          </div>
        </div>
      </div>

@if ($alert == true)
<script>
  swal("Registro!", "Registro guardado correctamente!", "success");
  $('#modalTermometro').modal('hide');
  $('#modalCalibracion').modal('hide');
</script>
@endif

</div>

