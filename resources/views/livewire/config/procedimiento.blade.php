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
                        <input type="text" class="form-control" wire:model='procedimiento' placeholder="Procedimiento" required>
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" wire:model='descripcion' placeholder="Descripción" required>
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
                        <td>Procedimiento</td>
                        <td>Descripción</td>
                        <td>Creación</td>
                        <td>Modificación</td>
                        <td>Acción</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($model as $item)
                        @if ($item->deleted_at != null)
                            <tr class="bg-danger text-white">
                        @else
                            <tr>
                        @endif
                            <td>{{$item->Id_procedimiento}}</td>
                            <td>{{$item->Procedimiento}}</td>
                            <td>{{$item->Descripcion}}</td> 
                            <td>{{$item->created_at}}</td>
                            <td>{{$item->updated_at}}</td>
                            <td><button class="btn btn-warning"
                                wire:click="setData('{{$item->Id_procedimiento}}','{{$item->Procedimiento}}','{{$item->Descripcion}}')"
                                data-toggle="modal" data-target="#modalProcedimiento"> <i class="voyager-edit"></i> Editar</button></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>


       
    <div wire:ignore.self class="modal fade" id="modalProcedimiento" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <form wire:submit.prevent="store">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Modificar procedimiento</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <label for="">Procedimiento</label>
                        <input type="text" wire:model='procedimiento' class="form-control" placeholder="Procedimiento" required>
                    </div>
                    <div class="col-md-12">
                        <label for="">Descripción</label>
                        <input type="text" wire:model='descripcion' class="form-control" placeholder="Descripcion" required>
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
            $('#modalProcedimiento').modal('hide');
        </script>
    @endif

</div>
