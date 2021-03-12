<div>
  <div class="row">
    <div class="col-md-8">
      <button class="btn btn-success btn-sm"><i class="voyager-plus"></i> Crear</button>
    </div>
    <div class="col-md-4">
      <input type="search" class="form-control" placeholder="Buscar">
    </div>
  </div>
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
</div>
 