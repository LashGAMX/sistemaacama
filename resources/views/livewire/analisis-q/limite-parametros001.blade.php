<div>
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
          <a class="nav-link active" id="rios-tab" data-toggle="tab" href="#rios" role="tab" aria-controls="rios" aria-selected="true">Rios</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="embalses-tab" data-toggle="tab" href="#embalses" role="tab" aria-controls="embalses" aria-selected="true">Embalses naturales A.</a>
          </li>
          <li class="nav-item" role="presentation">
            <a class="nav-link" id="aguas-tab" data-toggle="tab" href="#aguas" role="tab" aria-controls="aguas" aria-selected="true">Aguas costeras</a>
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
                            <th>Categoria</th>
                            <th>Prom_Mmax</th>
                            <th>Prom_Mmin</th>
                            <th>Prom_Dmax</th>
                            <th>Prom_Dmin</th>
                            <th>Accion</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if ($model->count()) 
                    @for ($i = 0; $i < 3; $i++)
                    <tr>  
                        <td>{{$model[$i]->Categoria}}</td>
                        <td>{{$model[$i]->Prom_Mmax}}</td>          
                        <td>{{$model[$i]->Prom_Mmin}}</td>
                        <td>{{$model[$i]->Prom_Dmax}}</td>
                        <td>{{$model[$i]->Prom_Dmin}}</td>
                        <td><button type="button" class="btn btn-primary" wire:click="setData('{{$model[$i]->Id_limite}}','{{$model[$i]->Prom_Mmax}}','{{$model[$i]->Prom_Mmin}}','{{$model[$i]->Prom_Dmax}}','{{$model[$i]->Prom_Dmin}}')" data-toggle="modal" data-target="#modalLimiteParametro"><i class="voyager-edit"></i> <span hidden-sm hidden-xs>editar</span> </button></td>
                      </tr>
                    @endfor
                    @else
                        <h6>No hay resultados para la búsqueda "{{$search}}"</h6>
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
                            <th>Categoria</th>
                            <th>Prom_Mmax</th>
                            <th>Prom_Mmin</th>
                            <th>Prom_Dmax</th>
                            <th>Prom_Dmin</th>
                            <th>Accion</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if ($model->count()) 
                    @for ($i = 3; $i < 5; $i++)
                    <tr>  
                        <td>{{$model[$i]->Categoria}}</td>
                        <td>{{$model[$i]->Prom_Mmax}}</td>          
                        <td>{{$model[$i]->Prom_Mmin}}</td>
                        <td>{{$model[$i]->Prom_Dmax}}</td>
                        <td>{{$model[$i]->Prom_Dmin}}</td>
                        <td><button type="button" class="btn btn-primary" wire:click="setData('{{$model[$i]->Id_limite}}','{{$model[$i]->Prom_Mmax}}','{{$model[$i]->Prom_Mmin}}','{{$model[$i]->Prom_Dmax}}','{{$model[$i]->Prom_Dmin}}')" data-toggle="modal" data-target="#modalLimiteParametro"><i class="voyager-edit"></i> <span hidden-sm hidden-xs>editar</span> </button></td>
                      </tr>
                    @endfor
                    @else
                        <h6>No hay resultados para la búsqueda "{{$search}}"</h6>
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
                            <th>Categoria</th>
                            <th>Prom_Mmax</th>
                            <th>Prom_Mmin</th>
                            <th>Prom_Dmax</th>
                            <th>Prom_Dmin</th>
                            <th>Accion</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if ($model->count()) 
                    @for ($i = 5; $i < 8; $i++)
                    <tr>  
                        <td>{{$model[$i]->Categoria}}</td>
                        <td>{{$model[$i]->Prom_Mmax}}</td>          
                        <td>{{$model[$i]->Prom_Mmin}}</td>
                        <td>{{$model[$i]->Prom_Dmax}}</td>
                        <td>{{$model[$i]->Prom_Dmin}}</td>
                        <td><button type="button" class="btn btn-primary" wire:click="setData('{{$model[$i]->Id_limite}}','{{$model[$i]->Prom_Mmax}}','{{$model[$i]->Prom_Mmin}}','{{$model[$i]->Prom_Dmax}}','{{$model[$i]->Prom_Dmin}}')" data-toggle="modal" data-target="#modalLimiteParametro"><i class="voyager-edit"></i> <span hidden-sm hidden-xs>editar</span> </button></td>
                      </tr>
                    @endfor
                    @else
                        <h6>No hay resultados para la búsqueda "{{$search}}"</h6>
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
                            <th>Prom_Mmax</th>
                            <th>Prom_Mmin</th>
                            <th>Prom_Dmax</th>
                            <th>Prom_Dmin</th>
                            <th>Accion</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if ($model->count()) 
                    @for ($i = 8; $i < 10; $i++)
                    <tr>  
                        <td>{{$model[$i]->Categoria}}</td>
                        <td>{{$model[$i]->Prom_Mmax}}</td>          
                        <td>{{$model[$i]->Prom_Mmin}}</td>
                        <td>{{$model[$i]->Prom_Dmax}}</td>
                        <td>{{$model[$i]->Prom_Dmin}}</td>
                        <td><button type="button" class="btn btn-primary" wire:click="setData('{{$model[$i]->Id_limite}}','{{$model[$i]->Prom_Mmax}}','{{$model[$i]->Prom_Mmin}}','{{$model[$i]->Prom_Dmax}}','{{$model[$i]->Prom_Dmin}}')" data-toggle="modal" data-target="#modalLimiteParametro"><i class="voyager-edit"></i> <span hidden-sm hidden-xs>editar</span> </button></td>
                      </tr>
                    @endfor
                    @else
                        <h6>No hay resultados para la búsqueda "{{$search}}"</h6>
                    @endif
                    </tbody>
                </table>
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
                    <div class="col-md-6">
                        <input type="text" wire:model="idLimite" hidden>
                        <label for="">Prom Mmax</label>
                        <input type="text" wire:model='Prom_Mmax' class="form-control" placeholder="Prom_Mmax">
                    </div>
                    <div class="col-md-6">
                        <label for="">Prom Mmin</label>
                        <input type="text" wire:model='Prom_Mmin' class="form-control" placeholder="Prom_Mmax">
                    </div>
                    <div class="col-md-6">
                        <label for="">Prom Dmax</label>
                        <input type="text" wire:model='Prom_Dmax' class="form-control" placeholder="Prom_Mmax">
                    </div>
                    <div class="col-md-6">
                        <label for="">Prom Dmax</label>
                        <input type="text" wire:model='Prom_Dmin' class="form-control" placeholder="Prom_Mmax">
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
 