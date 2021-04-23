@extends('voyager::master')

@section('content')

  @section('page_header')
  <h6 class="page-title"> 
    <i class="voyager-lab"></i>
    Arcvhios excel
  </h6>

  @stop

  <div class="container">
    <div class="row">
    <div class="col-md-12">
      <form action="{{url('admin/analisisQ/importar/create')}}" method="POST" enctype="multipart/form-data" >
        @csrf
        {{-- <div class="form-group">
          <label for="file">Archivo</label>
          <input type="file" class="form-control" id="file" name="file">
        </div> --}}
        <div class="form-group">
          <label for="">Sub modulo</label>
          <select name="submodulo" class="form-control">
            <option value="0">Sin seleccionar</option>
            <option value="1">Parametros</option>
            <option value="2">Normas</option>
            <option value="3">Limites</option>
          </select>
        </div>
        <div class="form-group">
          <label for="">Subir archivo</label>
          <input name="file" class="form-control" type="file" required />
        </div>
        <button type="submit" class="btn btn-success"><i class="voyager-upload"> </i> Subir datos</button>
      </form>
    </div>
  </div>
  </div>

@endsection  


@section('javascript')
{{-- <script src="{{asset('js/libs/dropzone-5.7.0/dropzone.js')}}"></script> --}}
<script>
  // Disabling autoDiscover, otherwise Dropzone will try to attach twice.
Dropzone.autoDiscover = false;
// or disable for specific dropzone:
// Dropzone.options.myDropzone = false;

$(function() {
  // Now that the DOM is fully loaded, create the dropzone, and setup the
  // event listeners
  var myDropzone = new Dropzone("#my-dropzone");
  myDropzone.on("addedfile", function(file) {
    /* Maybe display some more file information on your page */
  });
})
</script>
@stop

