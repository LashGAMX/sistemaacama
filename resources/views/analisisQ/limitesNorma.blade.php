@extends('voyager::master')

@section('content')

@section('page_header')
<h6 class="page-title"> 
  <i class="fa fa-layer-group"></i>
  Limites por Norma
</h6>

<div class="container-fluid">
    
    <div class="row">
        <div class="col-md-12">            
                  <Select id="normas">
                      <option value="0">Selecionar Norma</option>
                      <option value="1">NOM-001-SEMARNAT-1996</option>
                  </Select>

        </div>
            <div class="col-md-12"> 
                <button type="button" class="btn btn-primary" id="btnBuscar">Buscar</button>
            </div>     
    </div>
 


</div> 
 

  @stop

@endsection  


@section('javascript')
<script src="{{asset('/public/js/analisisQ/limitesNorma.js')}}"></script> 
<!-- <script src="{{asset('js/libs/componentes.js')}}"></script>
<script src="{{asset('js/libs/tablas.js')}}"></script> -->
@stop