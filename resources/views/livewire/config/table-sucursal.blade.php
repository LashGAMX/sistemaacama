<div class="table-responsive">
  <table id="dataTable" class="table table-hover">
      <thead class="thead-dark">
          <tr>
              <th>Id</th>
              <th>Sucursal</th>
              <th>Creación</th>
              <th>Modificación</th>
              <th>Acción</th>
          </tr>
      </thead>
      <tbody>
        @foreach ($sucursal as $item)
            <tr>
              <td>{{$item->Id_sucursal}}</td>
              <td>{{$item->Sucursal}}</td>
              <td>{{$item->created_at}}</td>
              <td>{{$item->updated_at}}</td>
              <td>
                  <button class="btn btn-sm btn-info"><i class="voyager-edit"></i> <span hidden-sm hidden-xs>editar</span> </button>
              </td>
            </tr>
        @endforeach
      </tbody>
  </table>
  <script>
    $(document).ready(function() {
    $('#dataTable').DataTable();
    });
  </script>
</div>
 