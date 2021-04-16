@extends('voyager::master')

@section('content')

  @section('page_header')
  @stop

  <div class="row">
    <div class="col-md-12">
      <form action="{{url('admin/clientes/importar/create')}}" method="POST">
        @csrf
        <div class="form-group">
          <label for="file">Archivo</label>
          <input type="file" class="form-control" id="file" name="file">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
      </form>
    </div>
  </div>

@endsection  
