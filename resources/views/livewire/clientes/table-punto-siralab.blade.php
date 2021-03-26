<div>
    <div class="row">
      <div class="col-md-8">
        <button class="btn btn-success btn-sm" wire:click="setBtn"  data-toggle="modal" data-target="#modalPuntoSiralab"><i class="voyager-plus"></i> Crear</button>
      </div>
      <div class="col-md-4">
        <input type="search" wire:model="search" wire:click='resetAlert' class="form-control" placeholder="Buscar">
      </div>
    </div>
    <table class="table table-hover table-striped">
        <thead class="thead-dark">
            <tr> 
                <th>Titulo</th>
                <th>Punto</th>
                <th>Anexo</th>
                <th>Siralab</th>
                <th>Pozos</th>
                <th>Cuerpo</th>
                <th>Latitud</th>
                <th>Longitud</th>
                <th>Hora</th>
                <th>Fecha Ini</th>
                <th>Fecha Ter</th> 
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
        @if ($model->count())  
        @foreach ($model as $item) 
        <tr>  
          {{-- <form wire:submit.prevent="update"> --}}
          <td>{{$item->Titulo_consecion}}</td>
          <td>{{$item->Punto}}</td>          
          <td>{{$item->Anexo}}</td>
          <td>{{$item->Siralab}}</td>
          <td>{{$item->Pozos}}</td>
          <td>{{$item->Cuerpo_receptor}}</td>
          <td>{{$item->Latitud}}</td>
          <td>{{$item->Longitud}}</td>
          <td>{{$item->Hora}}</td>
          <td>{{$item->F_inicio}}</td>
          <td>{{$item->F_termino}}</td>
          <td>
            <button type="button" class="btn btn-primary" wire:click="setData('{{$item->Id_punto}}','{{$item->Punto}}','{{$item->Titulo_consecion}}','{{$item->Anexo}}','{{$item->Siralab}}','{{$item->Pozos}}','{{$item->Cuerpo_receptor}}','{{$item->Latitud}}','{{$item->Longitud}}','{{$item->Hora}}','{{$item->F_inicio}}','{{$item->F_termino}}')" data-toggle="modal" data-target="#modalPuntoSiralab"><i class="voyager-edit"></i> <span hidden-sm hidden-xs>editar</span> </button>
          </td>
          {{-- </form>  --}}
        </tr>
    @endforeach 
        @else
            <h6>No hay resultados para la búsqueda "{{$search}}"</h6>
        @endif
        </tbody>
    </table>
  
  
    
    <div wire:ignore.self class="modal fade" id="modalPuntoSiralab" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
            @if ($sw != true)
            <form wire:submit.prevent="create">
            @else
                <form wire:submit.prevent="store">
            @endif
        <div class="modal-header">
            @if ($sw != true)
                <h5 class="modal-title" id="exampleModalLabel">Crear Punto de muestreo siralab</h5>
            @else
                <h5 class="modal-title" id="exampleModalLabel">Editar Punto de muestreo siralab</h5>
            @endif
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>
        <div class="modal-body">
            <input type="text" wire:model="idPunto" hidden>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Titulo</label>
                            <select class="form-control" wire:model='titulo' >
                            <option value="0">Sin seleccionar</option>
                            @foreach ($titulos as $item)
                                <option value="{{$item->Id_titulo}}">{{$item->Titulo}}</option>
                            @endforeach
                          </select>
                          @error('titulo') <span class="text-danger">{{ $message  }}</span> @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                    <label for="">Punto muestreo</label>
                    <input type="text" wire:model='punto' class="form-control" placeholder="Punto muestreo" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                    <label for="">Anexo</label>
                    <input type="text" wire:model='anexo' class="form-control" placeholder="Anexo" >
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Cuerpo</label>
                            <select class="form-control" wire:model='cuerpo' >
                            <option value="0">Sin seleccionar</option>
                            <option value="1">Rios</option>
                            <option value="2">Embalses naturales artificiales</option>
                            <option value="3">Aguas costeras</option>
                            <option value="4">Suelo</option>
                          </select>
                      </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                    <label for="">Latitud</label>
                    <input type="text" wire:model='latitud' class="form-control" placeholder="Latitud" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                    <label for="">Longitud</label>
                    <input type="text" wire:model='longitud' class="form-control" placeholder="Longitud" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                    <label for="">Hora</label>
                    <input type="time" wire:model='hora' step="1" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                    <label for="">Fecha inicio</label>
                    <input type="date" wire:model='inicio' class="form-control" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                    <label for="">Fecha termino</label>
                    <input type="date" wire:model='termino' class="form-control" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                    <label for="">Siralab </label>
                    <input type="checkbox" wire:model='siralab' class="" >
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                    <label for="">Pozos </label>
                    <input type="checkbox" wire:model='pozos' class="">
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
    $('#modalPuntoSiralab').modal('hide')
  </script>
  
  @endif
    
  </div>
   
