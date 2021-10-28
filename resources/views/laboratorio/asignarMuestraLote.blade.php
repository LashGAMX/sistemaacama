@extends('voyager::master')

@section('content')

  @section('page_header')
  <h6 class="page-title"> 
    <i class="voyager-data"></i>
    Asignar muestra
  </h6>
 
<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <div class="row">   

      </div>
    </div>
    
    <div class="col-md-12">
    
      <div class="" id="divTable">
        
      <table class="table" id="tableObservacion"> 
        <thead>
          <tr>
            
            <th>Id lote</th>
            <th>Analisis</th>
    
          </tr>
        </thead>
        <tbody>
            @foreach ($lote as $item)
                <tr>
                    <td>{{$item->Id_lote}}</td>
                    <td>{{$item->Id_analisis}}</td>
                </tr>
            @endforeach   
        </tbody>
      </table>
      </div>
    </div>
  </div>
</div>


  @stop

  @section('javascript')
  <script src="{{asset('js/libs/componentes.js')}}"></script>
  <script src="{{asset('js/libs/tablas.js')}}"></script>
  <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
  @stop

@endsection