
  <div>
    <h4>Parametro: {{$parametro->Parametro}}</h4>
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
          <a class="nav-link active" id="rios-tab" data-toggle="tab" href="#rios" role="tab" aria-controls="rios" aria-selected="true" >Ríos, arroyos, <br> canales,
            drenes</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="embalses-tab" data-toggle="tab" href="#embalses" role="tab" aria-controls="embalses" aria-selected="true">Embalses, lagos y
              lagunas</a>
          </li>
          <li class="nav-item" role="presentation">
            <a class="nav-link" id="aguas-tab" data-toggle="tab" href="#aguas" role="tab" aria-controls="aguas" aria-selected="true">Zonas marinas mexicanas</a>
          </li>
          <li class="nav-item" role="presentation">
            <a class="nav-link" id="suelo-tab" data-toggle="tab" href="#suelo" role="tab" aria-controls="suelo" aria-selected="true">Suelo</a>
          </li>
      </ul>
      <div class="tab-content" id="myTabContent">

        <div class="tab-pane fade" id="rios" role="tabpanel" aria-labelledby="rios-tab"> 
            <div class="accordion" id="accordionExample">
                <table class="table table-hover table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th>P.M</th>
                            <th>P.D</th>
                            <th>V.I</th>
                            <th>Accion</th>
                        </tr>
                    </thead>
                    <tbody>
                      @if ($model->count()) 
                        <tr>  
                            <td><input type="text" value="{{$model[0]->Pm}}" disabled></td>          
                            <td><input type="text" value="{{$model[0]->Pd}}" disabled></td>
                            <td><input type="text" value="{{$model[0]->Vi}}" disabled></td>
                            <td><button type="submit" class="btn btn-primary" wire:click='setData({{$model[0]->Id_limite}})' data-toggle="modal" data-target="#modalLimiteParametro"><i class="voyager-edit"></i> <span hidden-sm hidden-xs>editar</span> </button></td>
                          </tr>
                      @else
                          <h6>No hay resultados para la búsqueda</h6>
                      @endif
                    </tbody>
                </table>
              </div>
        </div>

        <div class="tab-pane fade" id="embalses" role="tabpanel" aria-labelledby="embalses-tab">
            <div class="accordion" id="accordionExample">
              <table class="table table-hover table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>P.M</th>
                        <th>P.D</th>
                        <th>V.I</th>
                        <th>Accion</th>
                    </tr>
                </thead>
                <tbody>
                  @if ($model->count()) 
                    <tr>  
                        <td><input type="text" value="{{$model[1]->Pm}}" disabled></td>          
                        <td><input type="text" value="{{$model[1]->Pd}}" disabled></td>
                        <td><input type="text" value="{{$model[1]->Vi}}" disabled></td>
                        <td><button type="submit" class="btn btn-primary" wire:click='setData({{$model[1]->Id_limite}})' data-toggle="modal" data-target="#modalLimiteParametro"><i class="voyager-edit"></i> <span hidden-sm hidden-xs>editar</span> </button></td>
                      </tr>
                  @else
                      <h6>No hay resultados para la búsqueda</h6>
                  @endif
                </tbody>
            </table>
              </div>
        </div>

        
        <div class="tab-pane fade" id="aguas" role="tabpanel" aria-labelledby="aguas-tab">
            <div class="accordion" id="accordionExample">
              <table class="table table-hover table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>P.M</th>
                        <th>P.D</th>
                        <th>V.I</th>
                        <th>Accion</th>
                    </tr>
                </thead>
                <tbody>
                  @if ($model->count()) 
                    <tr>  
                        <td><input type="text" value="{{$model[2]->Pm}}" disabled></td>          
                        <td><input type="text" value="{{$model[2]->Pd}}" disabled></td>
                        <td><input type="text" value="{{$model[2]->Vi}}" disabled></td>
                        <td><button type="submit" class="btn btn-primary" wire:click='setData({{$model[2]->Id_limite}})' data-toggle="modal" data-target="#modalLimiteParametro"><i class="voyager-edit"></i> <span hidden-sm hidden-xs>editar</span> </button></td>
                      </tr>
                  @else
                      <h6>No hay resultados para la búsqueda</h6>
                  @endif
                </tbody>
            </table>
              </div>
        </div>

        
        <div class="tab-pane fade" id="suelo" role="tabpanel" aria-labelledby="suelo-tab">
            <div class="accordion" id="accordionExample">
              <table class="table table-hover table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>Categoria</th>
                        <th>P.M</th>
                        <th>P.D</th>
                        <th>V.I</th> 
                        <th>Accion</th>
                    </tr>
                </thead>
                <tbody>
                  @if ($model->count()) 
                    @for ($i = 3; $i < 6; $i++)
                      <tr>  
                        <td><input type="text" value="{{@$model[$i]->Categoria}}" disabled></td>        
                        <td><input type="text" value="{{@$model[$i]->Pm}}" disabled></td>          
                        <td><input type="text" value="{{@$model[$i]->Pd}}" disabled></td>
                        <td><input type="text" value="{{@$model[$i]->Vi}}" disabled></td>
                        <td><button type="submit" class="btn btn-primary" wire:click='setData({{@$model[$i]->Id_limite}})' data-toggle="modal" data-target="#modalLimiteParametro"><i class="voyager-edit"></i> <span hidden-sm hidden-xs>editar</span> </button></td>
                      </tr>      
                    @endfor
                  @else
                      <h6>No hay resultados para la búsqueda</h6>
                  @endif
                </tbody>
              </div>
        </div>

      </div>

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
                    <div class="col-md-4">
                        <input type="text" wire:model="idLimite" hidden>
                        <label for="">P.M</label>
                        <input type="text" wire:model='pm' class="form-control" placeholder="Promedio Mensual">
                    </div>
                    <div class="col-md-4">
                        <label for="">P.D</label>
                        <input type="text" wire:model='pd' class="form-control" placeholder="Promedio Diario">
                    </div>
                    <div class="col-md-4">
                        <label for="">V.I</label>
                        <input type="text" wire:model='vi' class="form-control" placeholder="Valor instantaneo">
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

 