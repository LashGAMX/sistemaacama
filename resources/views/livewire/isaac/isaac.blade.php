@extends('voyager::master')

@section('content')

  @section('page_header')

  <h6 class="page-title"> 
      <i class="voyager-folder"></i>
      Descomponer formula
  </h6> 

<div>
    <div class="row">
        <form wire:submit.prevent="agregar">
        <div class="col-md-4">
          <input type="text" wire:model='idUser' hidden>
          <label for="">Formula</label>
            <input type="text" wire:model='formula' class="form-control" placeholder="">
            @error('name') <span class="text-danger">{{ $message  }}</span> @enderror
        </div>
        <div class="col-md-4">
          <button class="btn btn-sm btn-success" type="submit" ><i class="voyager-check"></i> <span hidden-sm hidden-xs>Agregar</span> </button>
        </div>
      
      </form>
      </div>  
</div>

@stop

@endsection   
