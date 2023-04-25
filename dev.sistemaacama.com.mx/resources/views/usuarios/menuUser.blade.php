@extends('voyager::master')

@section('content')


@section('page_header')

<h6 class="page-title">
    {{-- <i class="fa fa-truck-pickup"></i> --}}
    Permiso usuarios : {{ $user->name }}
</h6>
@stop
<input type="text" id="idUser" value="{{ $user->id }}" hidden>
<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <div class="row">
        <div class="col-md-3">
            <button type="button" class="btn btn-info" id="btnAsignar"><i class="fas fa-edit"></i> Asignar menu</button>
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
            @php $temp = ""; @endphp
            @foreach ($padre as $item)
              <tr>
                <td>
                  <div class="custom-control custom-checkbox">
                    @foreach ($model as $mod)
                        @if ($mod->Id_item == $item->id)
                            @php $temp = "checked"; @endphp
                        @endif
                    @endforeach
                    <input type="checkbox" class="custom-control-input" {{ $temp }} name="menus" value="{{ $item->id }}">
                    <label for="">{{ $item->title }}</label>
                  </div>
                </td>
                <td>
                    @php $temp = ""; @endphp
                  @foreach ($hijo as $item2)
                    @if ($item->id == $item2->parent_id)
                        @foreach ($model as $mod)
                            @if ($mod->Id_item == $item2->id)
                                @php $temp = "checked"; @endphp
                            @endif
                        @endforeach
                      <div class="custom-control custom-checkbox">
                        <input type="checkbox" {{ $temp }} class="custom-control-input" name="menus" value="{{ $item2->id }}">
                        <label for="">{{ $item2->title }}</label>
                      </div>
                    @endif
                    @php $temp = ""; @endphp
                  @endforeach
                </td>
              </tr>
              @php $temp = ""; @endphp
            @endforeach
          </tbody>
        </table>
    </div>
  </div>
</div>

@section('javascript')
  <script src="{{asset('/public/js/usuarios/menuUser.js')}}?v=0.0.1"></script>
@stop
@endsection 

