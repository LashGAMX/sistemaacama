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
                    <th>Constante</th>
                    <th>Valor</th>
                    <th>Descripción</th>
                </tr> 
            </thead> 
            <tbody>
            @foreach ($constante as $item)  
            <tr>  
              <td>{{$item->Constante}}</td>
              <td>{{$item->Valor}}</td>          
              <td>{{$item->Descripcion}}</td>
            </tr> 
        @endforeach
            </tbody> 
        </table>
        </div>
        </div>    



</div>

