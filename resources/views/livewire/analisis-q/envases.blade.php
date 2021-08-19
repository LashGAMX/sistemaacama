<div>
    <div class="row">
        <div class="col-md-8">
          <button class="btn btn-success btn-sm" wire:click='btnCreate' data-toggle="modal" data-target="#modalNorma" ><i class="voyager-plus"></i> Crear</button>
        </div>

        <div class="tab-pane fade" id="rios" role="tabpanel" aria-labelledby="rios-tab"> 
            <div class="accordion" id="accordionExample">
                <table class="table">
                    <thead class="thead-dark">
                        <tr>
                            <th>Id</th>
                            <th>Sub Norma</th>
                            <th>Clave</th>
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
                      {{-- <form wire:submit.prevent="update"> --}}
                      <td>{{$item->Id_subnorma}}</td>
                      <td>{{$item->Norma}}</td>           
                      <td>{{$item->Clave}}</td>
                      <td>
                        <button type="button" class="btn btn-warning btn-sm" wire:click="setData('{{$item->Id_subnorma}}','{{$item->Norma}}','{{$item->Clave}}','{{$item->deleted_at}}')"  data-toggle="modal" data-target="#modalSubNorma"><i class="voyager-edit"></i> <span hidden-sm hidden-xs>editar</span> </button>
                        <button class="btn btn-primary btn-sm" wire:click="showDetils('{{$item->Id_subnorma}}')"><i class="voyager-external"></i> Ver</button>
                      </td>
                      {{-- </form>  --}}
                    </tr>
                @endforeach
                    @else
                        <h6>No hay resultados para la búsqueda "{{$search}}"</h6>
                    @endif
                    </tbody>
                </table>
              </div>
        </div>      
      </div>
      
</div>
