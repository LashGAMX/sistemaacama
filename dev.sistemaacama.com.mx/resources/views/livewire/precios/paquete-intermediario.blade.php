<div>
    <div class="row">
      <div class="col-md-4">
        <button class="btn btn-success btn-sm" wire:click="btnCreate" data-toggle="modal" data-target="#modalPrecioPaquete" ><i class="voyager-plus"></i> Crear</button>
      </div>
      {{-- <div class="col-md-4">
        <button class="btn btn-dark btn-sm" onclick="confirmar()" ><i class="voyager-list-add"></i> Anual</button>
      </div> --}}
      <div class="col-md-4">
        <input type="search" wire:model="search" wire:click='resetAlert' class="form-control" placeholder="Buscar">
      </div>
    </div> 
    
    <table class="table ">
      <thead class="thead-dark">
            <tr>
                <th>Id</th>
                <th>Parametro</th>
                <th>Precio</th>
                <th>Descuento</th>
                <th>Original</th>
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
          <td>{{$item->Clave}}</td>          
          <td>$ {{$item->Precio}}</td>
          <td>% {{$item->Descuento}}</td>   
          <td>$ {{$item->Original}}</td>   
          <td>
            <button type="button" class="btn btn-warning" 
            wire:click="setData('{{$item->Id_precio}}','{{$item->Id_catalogo}}','{{$item->Descuento}}','{{$item->Precio}}','{{$item->deleted_at}}')"
             data-toggle="modal" data-target="#modalPrecioPaquete"><i class="voyager-edit"></i> <span hidden-sm hidden-xs>editar</span> </button>
          </td>

        </tr>
    @endforeach
        @else
            <h6>No hay resultados para la búsqueda "{{$search}}"</h6>
        @endif
        </tbody>
    </table>
  
  
    {{-- Modal crear precios --}}
    
<div wire:ignore.self class="modal fade" id="modalPrecioPaquete" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                        <label>Paquetes</label>
                        @if ($sw == false)
                            <select class="form-control" wire:model='paquete' wire:click='calcularDes'>
                        @else
                            <select class="form-control" wire:model='paquete' disabled>
                        @endif
                          <option value="0">Sin seleccionar</option>
                          @foreach ($paquetes as $item)
                          <option value="{{$item->Id_subnorma}}">{{$item->Clave}}</option>
                             @endforeach
                        </select>
                    </div> 
                </div> 
                <div class="col-md-12">
                        <label>Descuento %</label>
                        <input type="number" wire:model='desc'  max="{{$descNivel}}" class="form-control" placeholder="Descuento" required>
                    @error('precio') <span class="text-danger">{{ $message  }}</span> @enderror
                </div>
              
                <div class="col-md-6">
                    <label for="">Precio</label>
                        <input type="number" wire:model='precio' class="form-control" placeholder="Precio" disabled>
                        @error('precio') <span class="text-danger">{{ $message  }}</span> @enderror
                </div>
                <div class="col-md-6">
                    <label for="">Calcular</label>
                        <input type="number" wire:model='calcular' class="form-control" placeholder="Calcular"  disabled>
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

  {{-- Modal subir precio --}}
  <div wire:ignore.self class="modal fade" id="modalPrecioPaqueteAnual"  tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
            $('#modalPrecioPaquete').modal('hide')
        </script>
    @else
        <script>
            swal("Registro!", "Registro guardado correctamente!", "success");
            $('#modalPrecioPaquete').modal('hide')
        </script>  
    @endif
@endif


  </div>
   

