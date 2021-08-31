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
                    <th>Analisis</th>
                    <th>Parametro</th>
                    <th>Envase</th>
                    <th>Preservacion</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($model as $item) 
            <tr>  
              <td>{{$item->Analisis}}</td>
              <td>{{$item->Id_parametro}}</td>          
              <td>{{$item->Id_envase}}</td>
              <td>{{$item->Id_preservacion}}</td>
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
                    
                    <label for="">Analisis</label>
                    <input type="text" id='analisis' wire:model='analisis'  class="form-control" placeholder="Analisis">
                </div>
                <div class="col-md-6">
                    <label for="">Parametro</label>
                    <select class="form-control" wire:model='parametro' >
                        <option value="0">Sin seleccionar</option>
                        @foreach ($parametros as $item)
                        <option value="{{$item->Id_parametro}}">{{$item->Parametro}}</option>
                    @endforeach
                      </select>
                </div>
                <div class="col-md-6">
                    <label for="">Envase</label>
                    <select class="form-control" wire:model='envase' >
                      <option value="0">Sin seleccionar</option>
                      @foreach ($envases as $item)
                      <option value="{{$item->Id_envase}}">{{$item->Nombre}}</option>
                  @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    
                    <label for="">Preservacion</label>
                    <select class="form-control" wire:model='preservacion' >
                        <option value="0">Sin seleccionar</option>
                        @foreach ($preservaciones as $item)
                        <option value="{{$item->Id_preservacion}}">{{$item->Preservacion}}</option>
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
