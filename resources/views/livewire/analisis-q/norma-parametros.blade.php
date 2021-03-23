<div>
    @if ($alert === true)
    <div class="alert alert-success" role="alert">
        {{$msg}}
  </div>   
@endif
@if ($alert === false)
    <div class="alert alert-success" role="alert">
    {{$msg}}
  </div>   
@endif
<script type="text/javascript">
        setTimeout(function() {
            $(".alert").fadeOut(1500);
        },6000);
</script>
    <div class="row">
      <div class="col-md-8" style="align-content: right">
        <button class="btn btn-success btn-sm" wire:click='btnCreate'  data-toggle="modal" data-target="#modalNormaParametro" ><i class="voyager-plus"></i> Crear</button>
      </div>
    </div>
    <table class="table table-hover table-striped">
        <thead class="thead-dark">
            <tr>
                <th>Norma</th>
                <th>Matriz</th>
                <th>Parametro</th>
            </tr>
        </thead>
        <tbody>
        @if ($model->count()) 
        @foreach ($model as $item) 
        <tr>  
          <td>{{$item->Clave}}</td>
          <td>{{$item->Parametro}}</td>          
          <td>{{$item->Matriz}}</td>
        </tr>
    @endforeach
        @else
            <h6>No hay resultados para la búsqueda "{{$search}}"</h6>
        @endif
        </tbody>
    </table>


    <div wire:ignore.self class="modal fade" id="modalNormaParametro"  >
        <div class="modal-dialog modal-lg">
          <div class="modal-content ">
            <div class="modal-header">
                <form wire:submit.prevent="update">
                    <h5 class="modal-title" id="exampleModalLabel">Asignar parametros</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <select multiple="multiple" wire:model='parametro'>
                            @foreach ($parametros as $item)
                            <option value="{{$item->Id_parametro}}">{{$item->Parametro}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <h6>Selecionado</h6>
                        {{$parametro}}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
              <button type="submit" class="btn btn-primary" >Guardar cambios</button>
            </div>
        </form>
          </div>
        </div>
      </div> 

 
@if ($sw == true)
    <script>

    $('.duallistbox').bootstrapDualListbox({
    nonSelectedListLabel: 'No seleccionado',
    selectedListLabel: 'Seleccionado',
    preserveSelectionOnMove: 'Mover',
    moveOnSelect: true,
    infoText:'Mostrar todo {0}',
    filterPlaceHolder:'Filtro'
    });
    </script>
@endif
  </div>
   

  