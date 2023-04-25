@extends('voyager::master')

@section('content')


@section('page_header')

<h6 class="page-title">
    {{-- <i class="fa fa-truck-pickup"></i> --}}
    Permisos perfiles
</h6>
@stop

<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <div class="row">
        <div class="col-md-3">
          <label for="perfil">
            Perfiles
            <select class="form-control" id="perfil">
              <option value="0">Sin seleccionar</option>
              @foreach ($roles as $item)
                <option value="{{$item->id}}">{{ $item->display_name }}</option>
              @endforeach
            </select>
          </label>
        </div>
        <div class="col-md-2">
          <button type="button" id="btnBuscar" class="btn btn-info"><i class="fas fa-search"></i> Buscar</button>
        </div>
        <div class="col-md-3">
            <button type="button" class="btn btn-success" id="btnGuardar"><i class="fas fa-save"></i> Guardar</button>
        </div>
      </div>
    </div>
    <div class="col-md-12" id="divTable">
        <table class="table">
          <thead>
            <tr>
              <th>Menu</th>
              <th>Sub-Menu</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($padre as $item)
              <tr>
                <td>
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" name="menus" value="{{ $item->id }}">
                    <label for="">{{ $item->title }}</label>
                  </div>
                </td>
                <td>
                  @foreach ($model as $item2)
                    @if ($item->id == $item2->parent_id)
                      <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" name="menus" value="{{ $item2->id }}">
                        <label for="">{{ $item2->title }}</label>
                      </div>
                    @endif
                  @endforeach
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
    </div>
  </div>
</div>

@section('javascript')
  <script src="{{asset('/public/js/usuarios/menuPerfil.js')}}?v=0.0.1"></script>
@stop
@endsection 

