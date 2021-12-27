<div>

    <div class="row">
        <div class="col-md-8">
          <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#modalCrear" ><i class="voyager-plus"></i> Crear</button>
        <!--  <button class="btn btn-success btn-sm" wire:click='btnCreate' data-toggle="modal" data-target="#modalCrear" ><i class="voyager-plus"></i> Crear</button> -->
        </div>

        <div class="col-md-12">
          <table class="table table-hover table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>Nombre</th>
                    <th>Volumen</th>
                    <th>Unidad</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($model as $item) 
            <tr>  
              <td>{{$item->Nombre}}</td>
              <td>{{$item->Volumen}}</td>          
              <td>{{$item->Unidad}}</td>
            </tr>
        @endforeach
            </tbody>
        </table>
        </div>
        </div>    
        
        <!-- Modal -->
<div wire:ignore.self class="modal fade" id="modalCrear" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
      <div class="modal-content">
       <form wire:submit.prevent="create">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Nueva constante</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body"> 
            <div class="row">
                <div class="col-md-6">
                    
                    <label for="">Nombre</label>
                    <input type="text" id='nombre' wire:model='nombre'  class="form-control" placeholder="Nombre">
                </div>
                <div class="col-md-6">
                    <label for="">Volumen</label>
                    <input type="text" id='volumen' wire:model='volumen' class="form-control" placeholder="Volumen">
                </div>
                <div class="col-md-6">
                    <label for="">Unidad</label>
                    <select class="form-control" wire:model='unidad' >
                      <option value="0">Sin seleccionar</option>
                      @foreach ($unidades as $item)
                          <option value="{{$item->Id_unidad}}">{{$item->Unidad}}</option>
                      @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
      </form>
      </div>
    </div>
    
   </div>

   @if ($alert == true)
   <script>
     swal("Registro!", "Registro guardado correctamente!", "success");
     $('#modalNorma').modal('hide')
   </script>
   
   @endif

</div>






