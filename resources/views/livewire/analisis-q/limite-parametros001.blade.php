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

        <div class="tab-pane fade active" id="rios" role="tabpanel" aria-labelledby="rios-tab">
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
                        <td></td>
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
                        <td></td>
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
                        <td></td>
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
                        <td></td>
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
</div>
 