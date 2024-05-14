<div> 
    <div class="row">
        <div class="col-md-7">
            <button class="btn btn-success btn-md" wire:click='btnCreate'> <i class="voyager-plus"></i> Crear</button>
        </div>
        <div class="col-md-5">
            <input type="search" class="form-control" wire:model='search' placeholder="Buscar">
        </div>
    </div>

    <div class="row">
        @if ($show != false)
            <form wire:submit.prevent='create'>
                <div class="row">
                    <div class="col-md-4">
                        <input type="text" class="form-control" wire:model='serie' placeholder="N° Serie" required>
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" wire:model='peso' placeholder="Peso" required>
                    </div>
                    <div class="col-md-4">
                        <button class="btn btn-success" type="submit"><i class="voyager-check"></i> Guardar</button>
                        <button class="btn btn-danger" type="button" wire:click='btnCancel'><i class="voyager-x"></i> Cancelar</button>
                    </div>
                </div>
            </form>
        @endif
        <div class="col-md-12">
            <table class="table">
                <thead>
                    <tr>
                        <td>#</td>
                        <td>Serie</td>
                        <td>Peso</td>
                        <td>Min</td>
                        <td>Max</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($model as $item)
                        @if ($item->deleted_at != null)
                            <tr class="bg-danger text-white">
                        @else
                            <tr>
                        @endif
                            <td>{{$item->Id_crisol}}</td>
                            <td>{{$item->Num_serie}}</td>
                            <td>{{$item->Peso}}</td> 
                            <td>{{$item->Min}}</td>
                            <td>{{$item->Max}}</td>
                            <td><button class="btn btn-warning"
                                wire:click="setData('{{$item->Id_crisol}}','{{$item->Num_serie}}','{{$item->Peso}}','{{$item->Min}}','{{$item->Max}}')"
                                data-toggle="modal" data-target="#modalCrisol"> <i class="voyager-edit"></i> Editar</button></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>


       
    <div wire:ignore.self class="modal fade" id="modalCrisol" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <form wire:submit.prevent="store">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Modificar matraz</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        
                        <label for="">Existencia </label>
                        <input type="checkbox" wire:model='delete'  {{ $delete ? 'checked' : '' }} > 
                       
                    </div> 
                    <div class="col-md-12">
                        <label for="">N° Serie</label>
                        <input type="text" wire:model='serie' class="form-control" placeholder="Num Serie" required>
                    </div>
                    <div class="col-md-12">
                        <label for="">Peso</label>
                        <input type="text" wire:model='peso' class="form-control" placeholder="Peso" required>
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
            swal("Registro!","Registros guardado correctamente","success");
            $('#modalCrisol').modal('hide');
        </script>
    @endif
    @if ($alert2 == true)
        <script>
            swal("Registro!","Este Crisol Ha dejado de existir ","success");
            $('#modalCrisol').modal('hide');
        </script>
    @endif
    @if ($alert3 == true)
        <script>
            swal("Registro!","Este Crisol Esta de Nuevo Disponoble","success");
            $('#modalCrisol').modal('hide');
        </script>
    @endif

</div>
