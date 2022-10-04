<div>
    <h4>Parametro: {{$parametro->Parametro}}</h4>
  
    <table class="table table-hover table-striped">
        <thead class="thead-dark">
            <tr>
                <th>Indirecto</th>
                <th>Directo</th>
                <th>Accion</th>
            </tr>
        </thead>
        <tbody>
           <tr>
               <td>{{$model->Serv_indirecto}}</td>
               <td>{{$model->Serv_directo}}</td>
               <td><button type="button" class="btn btn-primary" wire:click="setData('{{$model->Id_limite}}','{{$model->PromM}}','{{$model->PromD}}','{{$model->Instantaneo}}')" data-toggle="modal" data-target="#modalLimiteParametro"><i class="voyager-edit"></i> <span hidden-sm hidden-xs>editar</span> </button></td>
            </tr> 
        </tbody>
    </table>
    
      <div wire:ignore.self class="modal fade" id="modalLimiteParametro" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <form wire:submit.prevent="store">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Modificar limite</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body"> 
                <div class="row">
                    <div class="col-md-6">
                        <input type="text" wire:model="idLimite" hidden>
                        <label for="">Directo.</label>
                        <input type="text" wire:model='promM' class="form-control" placeholder="Prom Indirecto">
                    </div>
                    <div class="col-md-6">
                        <label for="">Indirecto</label>
                        <input type="text" wire:model='promD' class="form-control" placeholder="Prom Directo">
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
        $('#modalLimiteParametro').modal('hide')
      </script>
    @endif
    
</div>
 