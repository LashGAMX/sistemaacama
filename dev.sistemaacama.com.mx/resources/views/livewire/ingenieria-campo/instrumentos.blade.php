<div>

    <div class="row">
      <div class="col-md-8">
        <button class="btn btn-success btn-sm"  data-toggle="modal" data-target="#modalCliente" ><i class="voyager-plus"></i> Crear</button>
      </div>
      <div class="col-md-4">
        <input type="search"  class="form-control" placeholder="Buscar">
      </div>
    </div>
    <table class="table table-hover table-striped">
        <thead class="thead-dark">
            <tr>
                <th>Id</th>
                <th>Codigo</th>
                <th>Instrumento</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
        @if ($instrumentos->count())
        @foreach ($instrumentos as $instrumento)
            {{-- @if ($instrumento->deleted_at != 'null')
                <tr class="bg-danger text-white">
            @else
                <tr>
            @endif--}}

          <td>{{$instrumento->Id_instrumentos_laboratorios}}</td>
          <td>{{$instrumento->Codigo_instrumento}}</td>
          <td>{{$instrumento->Descripcion}}</td>
           <td>
             <button type="button" class="btn btn-warning"
             data-toggle="modal" data-target="#modalCliente">
            <i class="voyager-edit"></i> <span hidden-sm hidden-xs>editar</span> </button>
            <button type="button"  class="btn btn-primary"><i class="voyager-external"></i> <span hidden-sm hidden-xs>ver</span> </button>
            <button type="button"  class="btn btn-primary"><i class="voyager-external"></i> <span hidden-sm hidden-xs>Configuración</span> </button>
          </td>
        </tr>
    @endforeach
        @else
            {{-- <h4>No hay resultados para la búsqueda "{{$search}}"</h4> --}}
        @endif
        </tbody>
    </table>


{{-- @if ($alert == true) --}}
{{-- <script>
  swal("Registro!", "Registro guardado correctamente!", "success");
  $('#modalCliente').modal('hide')
</script> --}}
{{-- @endif --}}


  </div>



