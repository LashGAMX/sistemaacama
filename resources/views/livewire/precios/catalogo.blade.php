<div>
<<<<<<< HEAD
=======
  {{-- {{$idUser}} --}}
>>>>>>> 0f3ec6e1dd1d8bdc6c567878cd6b968b8cdaaedb
    <div class="row">
      {{-- <div class="col-md-4">
        <button class="btn btn-success btn-sm" wire:click="btnCreate" data-toggle="modal" data-target="#modalPrecioCatalgo" ><i class="voyager-plus"></i> Crear</button>
      </div> --}}
      <div class="col-md-4">
        <button class="btn btn-dark btn-sm" onclick="confirmar()" ><i class="voyager-list-add"></i> Anual</button>
      </div>
      <div class="col-md-4">
        <input type="text" wire:model='idUser' hidden>
        <input type="search" wire:model="search" wire:click='resetAlert' class="form-control" placeholder="Buscar">
      </div>
    </div>
   
    <table class="table ">
      <thead class="thead-dark">
            <tr>
                <th>Id</th>
                <th>Parametro</th>
                <th>Tipo fórmula</th>
                <th>Matriz</th>
                <th>Rama</th>
                <th>Unidad</th>
                <th>Precio</th>
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
          <td>{{$item->Id_precio}}</td>
          <td>{{$item->Parametro}}</td>
          <td>{{$item->Tipo_formula}}</td>
          <td>{{$item->Matriz}}</td>
          <td>{{$item->Rama}}</td>
          <td>{{$item->Unidad}}</td>
          <td>$ {{$item->Precio}}</td>
          <td>
            <button type="button" class="btn btn-warning"
            wire:click="setData('{{$item->Id_precio}}','{{$item->Id_parametro}}','{{$item->Precio}}','{{$item->deleted_at}}')" data-toggle="modal" data-target="#modalPrecioCatalgo"><i class="voyager-edit"></i> <span hidden-sm hidden-xs>editar</span> </button>
          </td>

        </tr>
    @endforeach
        @else
            <h6>No hay resultados para la búsqueda "{{$search}}"</h6>
        @endif
        </tbody>
    </table>


    {{-- Modal crear precios --}}

<div wire:ignore.self class="modal fade" id="modalPrecioCatalgo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        @if ($sw == false)
          <form wire:submit.prevent="create">
        @else
          <form wire:submit.prevent="store">
        @endif
        <div class="modal-header">
          @if ($sw == false)
            <h5 class="modal-title" id="exampleModalLabel">Crear precio de parametro</h5>
          @else
            <h5 class="modal-title" id="exampleModalLabel">Editar precio de parametro</h5>
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
                        <label>Parametro</label>
                        @if ($sw == false)
                            <select class="form-control" wire:model='parametro' >
                        @else
                            <select class="form-control" wire:model='parametro' disabled>
                        @endif
                          <option value="0">Sin seleccionar</option>
                          @foreach ($parametros as $item)
                          <option value="{{$item->Id_parametro}}">{{$item->Parametro}}</option>
                             @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-12">
                    <label for="">Precio</label>
                        <label>Precio</label>
                        <input type="number" wire:model='precio' class="form-control" placeholder="Precio" required>
                        @error('precio') <span class="text-danger">{{ $message  }}</span> @enderror
                </div>
                @if ($sw == true)
                <div class="col-md-12">
                  <input type="text" wire:model="nota" hidden>
                  <label for="">Nota</label>
                  <input type="text" wire:model='nota' class="form-control" placeholder="Nota" required> 
                  @error('nota') <span class="text-danger">{{ $message  }}</span> @enderror
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

  {{-- Modal subir precio --}}
  <div wire:ignore.self class="modal fade" id="modalPrecioAnual"  tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
          <form wire:submit.prevent="createPrecio">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Incrementar precio anual</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                        <label>Precio anual %</label>
                        <input type="number" wire:model='porcentaje' class="form-control" placeholder="Precio %" required>
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

  <script>
      function confirmar() {
                swal({
                title: "¿Estas seguro de realizar el incremento anual?",
                text: "Se ajustaran automaticamente todos los precios al laboratorio asignado",
                icon: "warning",
                buttons: true,
                dangerMode: false,
                })
                .then((willDelete) => {
                if (willDelete) {
                    $('#modalPrecioAnual').modal('show')
                } else {

                }
                });
        }
  </script>

@if ($alert == true)
    @if ($error == true)
        <script>
            swal("Error!", "Este parametro ya se encuentra registrado!", "error");
            $('#modalPrecioCatalgo').modal('hide')
        </script>
    @else
        <script>
            swal("Registro!", "Registro guardado correctamente!", "success");
            $('#modalPrecioCatalgo').modal('hide')
        </script>
    @endif
@endif


  </div>

