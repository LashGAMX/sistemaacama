@extends('voyager::master')

@section('content')

  @section('page_header')
<div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <h4>Tus Notificaciones</h4>
      </div>
        <div class="col-md-12" >
             <table class="table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Mensaje</th>
            <th>Fecha de Creación</th>
          </tr>
        </thead>
        <tbody>
          @foreach($notificaciones as $notificacion)
          <tr>
            <td>{{ $notificacion->Id_notificacion }}</td>
            <td>{{ $notificacion->Mensaje }}</td>
            <td>{{ $notificacion->created_at }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
        </div>
      </div>
</div>
@stop
</body>
</html>
