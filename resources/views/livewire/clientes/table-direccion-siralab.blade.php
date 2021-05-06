<div>
    <div class="row">
      <div class="col-md-8">
        <button class="btn btn-success btn-sm" wire:click="setBtn" data-toggle="modal" data-target="#modalDireccionSiralab"><i class="voyager-plus"></i> Crear</button>
      </div>
      <div class="col-md-4">
        <input type="search" wire:model="search" wire:click='resetAlert' class="form-control" placeholder="Buscar">
      </div>
    </div>
    <table class="table">
        <thead class="thead-dark">
            <tr>
                <th>Titulo</th>
                <th>Calle</th>
                <th>Num Ext</th>
                <th>Num Int</th>
                <th>Estado</th>
                <th>Municipio</th>
                <th>Colonia</th>
                <th>C.P</th>
                <th>Ciudad</th>
                <th>Localidad</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
        @if ($model->count())
        @foreach ($model as $item)
        <tr>
          {{-- <form wire:submit.prevent="update"> --}}
          <td>{{$item->Titulo_concesion}}</td>
          <td>{{$item->Calle}}</td>
          <td>{{$item->Num_exterior}}</td>
          <td>{{$item->Num_interior}}</td>
          <td>{{$item->Estado}}</td>
          <td>{{$item->Municipio}}</td>
          <td>{{$item->Colonia}}</td>
          <td>{{$item->CP}}</td>
          <td>{{$item->Ciudad}}</td>
          <td>{{$item->Localidad}}</td>

          <td>
            <button type="button" class="btn btn-primary"
            wire:click="setData('{{$item->Id_cliente_siralab}}','{{$item->Titulo_concesion}}','{{$item->Calle}}','{{$item->Num_exterior}}','{{$item->Num_interior}}','{{$item->Estado}}','{{$item->Municipio}}','{{$item->Colonia}}','{{$item->CP}}','{{$item->Localidad}}','{{$item->Ciudad}}')"
            data-toggle="modal" data-target="#modalDireccionSiralab"><i class="voyager-edit"></i> <span hidden-sm hidden-xs>editar</span> </button>
          </td>
          {{-- </form>  --}}
        </tr>
    @endforeach
        @else
            <h6>No hay resultados para la búsqueda "{{$search}}"</h6>
        @endif
        </tbody>
    </table>



    <div wire:ignore.self class="modal fade" id="modalDireccionSiralab" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
                @if ($sw != true)
                    <form wire:submit.prevent="create">
                @else
                    <form wire:submit.prevent="store">
                @endif
            <div class="modal-header">
                @if ($sw != true)
                    <h5 class="modal-title" id="exampleModalLabel">Crear dirección siralab</h5>
                @else
                    <h5 class="modal-title" id="exampleModalLabel">Editar dirección siralab</h5>
                @endif
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                          <input type="text" wire:model='idDireccion' hidden>
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
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="calle">Calle</label>
                           <input type="text" wire:model='calle' class="form-control" placeholder="Calle">
                           @error('calle') <span class="text-danger">{{ $message  }}</span> @enderror
                          </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="ext">Num exterior</label>
                           <input type="text" wire:model='ext' class="form-control" placeholder="Num exterior">
                           @error('ext') <span class="text-danger">{{ $message  }}</span> @enderror
                          </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="int">Num interior</label>
                           <input type="text" wire:model='int' class="form-control" placeholder="Num interior">
                           @error('int') <span class="text-danger">{{ $message  }}</span> @enderror
                          </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                          <label for="">Estado</label>
                              <select class="form-control" wire:model='estado' >
                              <option value="0">Sin seleccionar</option>
                              @foreach ($estados as $item)
                                  <option value="{{$item->Id_estado}}">{{$item->Nombre}}</option>
                              @endforeach
                            </select>
                            {{-- @error('inter') <span class="text-danger">{{ $message  }}</span> @enderror --}}
                        </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Municipio</label>
                            <select class="form-control"   wire:model='municipio' >
                            <option value="0">Sin seleccionar</option>
                            @foreach ($municipios as $item)
                                <option value="{{$item->Id_localidad}}">{{$item->Nombre}}</option>
                            @endforeach
                          </select>
                          {{-- @error('inter') <span class="text-danger">{{ $message  }}</span> @enderror --}}
                      </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                      <label for="colonia">Colonia</label>
                     <input type="text" wire:model='colonia' class="form-control" placeholder="Colonia">
                     @error('colonia') <span class="text-danger">{{ $message  }}</span> @enderror
                    </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                    <label for="cp">C.P</label>
                   <input type="text" wire:model='cp' class="form-control" placeholder="Codigo Postal">
                   @error('cp') <span class="text-danger">{{ $message  }}</span> @enderror
                  </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                  <label for="localidad">Localidad</label>
                 <input type="text" wire:model='localidad' class="form-control" placeholder="Localidad">
                 @error('localidad') <span class="text-danger">{{ $message  }}</span> @enderror
                </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
                <label for="ciudad">Ciudad</label>
               <input type="text" wire:model='ciudad' class="form-control" placeholder="Ciudad">
               @error('ciudad') <span class="text-danger">{{ $message  }}</span> @enderror
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
    $('#modalDireccionSiralab').modal('hide')
  </script>

  @endif

  </div>

