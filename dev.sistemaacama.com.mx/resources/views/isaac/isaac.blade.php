@extends('voyager::master')

@section('content')

  @section('page_header')

  <h6 class="page-title"> 
      <i class="voyager-folder"></i>
      Descomponer formula
  </h6> 

   @livewire('isaac.isaac')

  {{-- <form name="index" method="post" action="{{url('admin/isaac/formula')}}">
    
    @csrf
    Formula: <input type="text" name="formula" >
    
    <input type="submit" value="agregar" />
    
    </form> --}}

  @stop

@endsection   
