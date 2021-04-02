@extends('voyager::master')

@section('content')

  @section('page_header')
<div class="container-fluid"> 
 
    <div class="row">

        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2"><strong>Id cliente: </strong>{{$model->Id_cliente}}</div>
                        <div class="col-md-3"><strong>Nombre: </strong>{{$model->Nombres}} {{$model->A_paterno}}</div>
                        <div class="col-md-2"><strong>RFC: </strong>{{$model->RFC}}</div>
                        <div class="col-md-2"><strong>Sucursal: </strong>{{$model->Sucursal}}</div>
                        <div class="col-md-3"><strong>% Auth: </strong>{{$model->Nivel}}/ %{{$model->DescNivel}}</div>
                    </div>
                </div>
              </div>
        </div>
        
        <div class="col-md-12"> 
          <div class="card">
            <div class="card-body">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                      <a class="nav-link" id="parametros-tab" data-toggle="tab" href="#parametros" role="tab" aria-controls="parametros" aria-selected="true">Catalogo</a>
                    </li>
                    <li class="nav-item" role="presentation">
                      <a class="nav-link" id="paquetes-tab" data-toggle="tab" href="#paquetes" role="tab" aria-controls="paquetes" aria-selected="false">Paquetes</a>
                    </li>
                  </ul>
                  <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade" id="parametros" role="tabpanel" aria-labelledby="parametros-tab">
                        @livewire('precios.catalogo-intermediario', ['idCliente' => $model->Id_cliente,'descNivel' => $model->DescNivel,'idLab' => $model->Id_laboratorio])
                    </div>
                    <div class="tab-pane fade" id="paquetes" role="tabpanel" aria-labelledby="paquetes-tab">
                      @livewire('precios.paquete-intermediario', ['idCliente' => $model->Id_cliente,'descNivel' => $model->DescNivel,'idLab' => $model->Id_laboratorio])
                    </div>
                  </div>
            </div>
          </div>
        </div>
 
      </div> 

</div> 
  @stop 

@endsection 
 
