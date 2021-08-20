<div>
    <div class="row">
        <div class="col-md-8">
          <button class="btn btn-success btn-sm"  data-toggle="modal" data-target="#modalCrear" ><i class="voyager-plus"></i> Crear</button>
        <!--  <button class="btn btn-success btn-sm" wire:click='btnCreate' data-toggle="modal" data-target="#modalCrear" ><i class="voyager-plus"></i> Crear</button> -->
        </div>

        <div class="col-md-12">
          <table class="table table-hover table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>nombre</th>
                    <th>volumen</th>
                    <th>unidad</th>
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
      </div>
</div>


</div>
<!-- Modal -->
<div class="modal fade" id="modalCrear" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
 <div class="modal-dialog">
     <div class="modal-content">
       <form wire:submit.prevent="store">
       <div class="modal-header">
         <h5 class="modal-title" id="exampleModalLabel">Nueva constante</h5>
         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
           <span aria-hidden="true">&times;</span>
         </button>
       </div>
       <div class="modal-body"> 
           <div class="row">
               <div class="col-md-6">
                   
                   <label for="">Constante</label>
                   <input type="text" id='constante' class="form-control" placeholder="constante">
               </div>
               <div class="col-md-6">
                   <label for="">Valor</label>
                   <input type="text" id='valor' class="form-control" placeholder="Valor">
               </div>
               <div class="col-md-6">
                   <label for="">Descripción</label>
                   <input type="text" id='descripcion' class="form-control" placeholder="Descripción">
               </div>
           </div>
       </div>
       <div class="modal-footer">
         <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
         <button type="button" id="guardar" class="btn btn-primary">Guardar</button>
       </div>
     </form>
     </div>
   </div>
</div>
