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
        <button class="btn btn-success btn-sm"  data-toggle="modal" data-target="#modalNormaParametro" ><i class="voyager-plus"></i> Crear</button>
      </div>
      {{-- <div class="col-md-4">
        <input type="search" wire:model="search" class="form-control" placeholder="Buscar">
      </div> --}}
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


    <div wire:ignore.self class="modal fade" id="modalNormaParametro" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content ">
              
            <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Asignar parametros</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <h6>Lista de parametros</h6>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <select class="form-control select2">
                                <option value="">option 1</option>
                                <option value="">option 2</option>
                                <option value="">option 3</option>
                                <option value="">option 4</option>
                                <option value="">option 5</option>
                                <option value="">option 6</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <select class="form-control" multiple>
                                <option value="">option 1</option>
                                <option value="">option 2</option>
                                <option value="">option 3</option>
                                <option value="">option 4</option>
                                <option value="">option 5</option>
                                <option value="">option 6</option>
                            </select>
                        </div>
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
    
  </div>
   

  