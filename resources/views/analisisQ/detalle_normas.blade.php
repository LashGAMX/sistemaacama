@extends('voyager::master')

@section('content')

  @section('page_header')

  <h6 class="page-title"> 
      <i class="voyager-person"></i>
      Detalle normas
  </h6>
  <div class="row">
    <div class="col-md-5">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                      <h5 class="card-title"><strong>Norma: </strong>{{$norma->Norma}}</h5>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        @livewire('analisis-q.detalle-norma', ['idUser' => Auth::user(),'name' => $norma->Clave_norma])
                    </div>
                </div>
            </div>
          </div>
    </div>
    <div class="col-md-7">
    
    </div>
  </div>
  @stop

@endsection   