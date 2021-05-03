@extends('voyager::master')

@section('content')

  @section('page_header')
<div class="container-fluid"> 

    <div class="row">
        <div class="col-md-4">
            <button class="btn btn-success" onclick="window.location='{{url('')}}/admin/exportarPdf'">Exportar</button>
        </div>
    </div>
</div> 
  @stop

@endsection 
