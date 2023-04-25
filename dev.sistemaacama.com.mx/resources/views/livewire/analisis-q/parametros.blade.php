<div>
    <div class="row">
      <div class="col-md-4">
        <button class="btn btn-success btn-sm" wire:click='btnCreate' data-toggle="modal" data-target="#modalParametro" ><i class="voyager-plus"></i> Crear</button>
      </div>
      <div class="col-md-4">
        <select class="form-control" wire:click='setNorma()' wire:model='idNorma'> 
            <label>Normas</label>
            <option value="0">Todas las normas</option>
            @foreach ($normas as $item)
                <option value="{{$item->Id_norma}}">{{$item->Clave_norma}}</option>
            @endforeach
        </select>
      </div>
      <div class="col-md-4">
        <input type="search" wire:model="search" wire:click='resetAlert' class="form-control" placeholder="Buscar">
      </div>
    </div>
    <table class="table table-sm">
        <thead class="">
            <tr>
                <th>Id</th>
                <th>Sucursal</th>
                <th>Rama</th>
                <th>Parámetro</th>
                <th>Unidad</th>
                <th>Método de prueba</th> 
                <th>C. Metodo</th>
                <th>Norma</th>
                <th>Límite C.</th>
                {{-- <th>Creación</th>
                <th>Modificación</th> --}}
                <th>Acción</th> 
            </tr>
        </thead> 
        <tbody>
        @if ($model->count()) 
        @foreach ($model as $item) 
            @if ($item->deleted_at != null)
                <tr class="bg-danger text-white">  
            @else
                <tr>
            @endif
          <td>{{$item->Id_parametro}}</td>
          <td>{{$item->Sucursal}}</td>
          <td>{{$item->Rama}}</td>
          <td>{{$item->Parametro}} <sup>({{$item->Simbologia}})</sup></td>
          <td>{{$item->Unidad}}</td>
          <td>{{$item->Metodo_prueba}}</td>
          <td>{{$item->Clave_metodo}}</td>
          <td>
            @php $model2 = DB::table('ViewParametroNorma')->where('Id_parametro',$item->Id_parametro)->get(); @endphp
            @foreach ($model2 as $item2)
                {{$item2->Clave_norma}},
            @endforeach
          </td>
          <td>{{$item->Limite}}</td>
          <td>
            <button type="button" class="btn btn-warning" 
            wire:click="setData('{{$item->Id_parametro}}','{{$item->Id_laboratorio}}','{{$item->Parametro}}','{{$item->Id_unidad}}','{{$item->Id_tipo_formula}}','{{$item->Id_area}}','{{$item->Limite}}','{{$item->Id_tecnica}}','{{$item->Id_matriz}}','{{$item->Id_simbologia}}',{{$item->Id_simbologia_info}},'{{$item->Id_rama}}','{{$item->Id_metodo}}','{{$item->Id_procedimiento}}','{{$item->deleted_at}}')" data-toggle="modal" data-target="#modalParametro">
            <i class="voyager-edit"></i> <span hidden-sm hidden-xs>editar</span> 
            </button>
          </td>  
        </tr>
    @endforeach
        @else
            <h6>No hay resultados para la búsqueda "{{$search}}"</h6>
        @endif
        </tbody>
    </table>
  
    <div wire:ignore.self class="modal fade" id="modalParametro" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
                @if ($sw != true)
                    <form wire:submit.prevent="create">
                @else
                    <form wire:submit.prevent="store">
                @endif
            <div class="modal-header">
                @if ($sw != true)
                    <h5 class="modal-title" id="exampleModalLabel">Crear parámetro</h5>
                @else
                    <h5 class="modal-title" id="exampleModalLabel">Editar parámetro</h5>
                @endif
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-3">
                        @if ($sw != true)
                            <div class="custom-control custom-switch">
                                <input wire:model='status' type="checkbox" class="custom-control-input" id="customSwitch1">
                                <label class="custom-control-label" for="customSwitch1">Status</label>
                            </div>
                        @else
                            <input type="text" wire:model="idParametro" hidden>
                            <div class="custom-control custom-switch">
                                <input wire:model='status' type="checkbox" class="custom-control-input" id="customSwitch1">
                                <label class="custom-control-label" for="customSwitch1">Status</label>
                            </div>
                        @endif     
                        <div class="custom-control custom-switch">
                            <input wire:model='curva' type="checkbox" class="custom-control-input" id="curva">
                            <label class="custom-control-label" for="customSwitch1">Curva</label>
                        </div>       
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="">Laboratorio</label>
                                <select class="form-control" wire:model='laboratorio' >
                                @foreach ($laboratorios as $item)
                                    <option value="{{$item->Id_sucursal}}">{{$item->Sucursal}}</option>
                                @endforeach
                              </select>
                              @error('laboratorio') <span class="text-danger">{{ $message  }}</span> @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="parametro">Nombre parámetro</label>
                           <input type="text" wire:model='parametro' class="form-control" placeholder="Parámetro">
                           @error('parametro') <span class="text-danger">{{ $message  }}</span> @enderror
                          </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="">Unidad</label>
                            <select class="form-control" wire:model='unidad' >
                            @foreach ($unidades as $item)
                                <option value="{{$item->Id_unidad}}">{{$item->Unidad}}</option>
                            @endforeach
                          </select>
                          @error('unidad') <span class="text-danger">{{ $message  }}</span> @enderror
                          </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="">Tipo fórmula</label>
                            <select class="form-control" wire:model='tipo' >
                            @foreach ($tipos as $item)
                                <option value="{{$item->Id_tipo_formula}}">{{$item->Tipo_formula}}</option>
                            @endforeach
                          </select>
                          @error('tipo') <span class="text-danger">{{ $message  }}</span> @enderror
                          </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="">Area</label>
                            <select class="form-control" wire:model='area' >
                            @foreach ($areas as $item)
                                <option value="{{$item->Id_area_analisis}}">{{$item->Area_analisis}}</option>
                            @endforeach
                          </select>
                          @error('area') <span class="text-danger">{{ $message  }}</span> @enderror
                          </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Norma</label>
                            <select class="form-control" id="norma" wire:model='norma' size="5"  multiple >
                            @foreach ($normas as $item)
                                @if ($sw != true)
                                    <option value="{{$item->Id_norma}}">{{$item->Clave_norma}}</option>
                                @else
                                    @php
                                    @$paraModel = DB::table('parametros_normas')->get();
                                    @endphp 
                                    @if ($paraModel->count())
                                    <option value="{{$item->Id_norma}}"  selected>{{$item->Clave_norma}}</option>
                                    @else
                                    <option value="{{$item->Id_norma}}">{{$item->Clave_norma}}</option>
                                    @endif
                                @endif
                            @endforeach
                          </select>
                          @error('norma') <span class="text-danger">{{ $message  }}</span> @enderror
                          </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="limite">Límite Cuantificación</label>
                           <input type="text" wire:model='limite' class="form-control" placeholder="Limite">
                           @error('limite') <span class="text-danger">{{ $message  }}</span> @enderror
                          </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="">Matriz</label>
                            <select class="form-control" wire:model='matriz' >
                            @foreach ($metrices as $item)
                                <option value="{{$item->Id_matriz_parametro}}">{{$item->Matriz}}</option>
                            @endforeach
                          </select>
                          @error('matriz') <span class="text-danger">{{ $message  }}</span> @enderror
                          </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="">Rama</label>
                            <select class="form-control" wire:model='rama' >
                            @foreach ($ramas as $item)
                                <option value="{{$item->Id_rama}}">{{$item->Rama}}</option>
                            @endforeach
                          </select>
                          @error('rama') <span class="text-danger">{{ $message  }}</span> @enderror
                          </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="">Método</label>
                            <select class="form-control" wire:model='metodo' >
                            @foreach ($metodos as $item)
                                <option value="{{$item->Id_metodo}}">{{$item->Clave_metodo}}</option>
                            @endforeach
                          </select>
                          @error('metodo') <span class="text-danger">{{ $message  }}</span> @enderror
                          </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="">Tecnica</label>
                            <select class="form-control" wire:model='tecnica' >
                            @foreach ($tecnicas as $item)
                                <option value="{{$item->Id_tecnica}}">{{$item->Tecnica}}</option>
                            @endforeach
                          </select>
                          @error('tecnica') <span class="text-danger">{{ $message  }}</span> @enderror
                          </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="">Procedimiento análisis</label>
                            <select class="form-control" wire:model='procedimiento' >
                            @foreach ($procedimientos as $item)
                                <option value="{{$item->Id_procedimiento}}">{{$item->Procedimiento}}</option>
                            @endforeach
                          </select>
                          @error('procedimiento') <span class="text-danger">{{ $message  }}</span> @enderror
                          </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="">Simbología Oficial</label>
                            <select class="form-control" wire:model='simbologia' >
                            @foreach ($simbologias as $item)
                                <option value="{{$item->Id_simbologia}}">{{$item->Simbologia}}</option>
                            @endforeach
                          </select>
                          @error('simbologia') <span class="text-danger">{{ $message  }}</span> @enderror
                          </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="">Simbología Informes</label>
                            <select class="form-control" wire:model='simbologiaInformes'>
                            @foreach ($simbologiaInf as $item)
                                <option value="{{$item->Id_simbologia_info}}">{{$item->Simbologia}}</option>
                            @endforeach
                          </select> 
                          </div>
                    </div>
                    @if ($sw == true)               
                    <div class="col-md-12">
                    <div class="form-group">
                        <input type="text" wire:model="nota" hidden>
                        <label for="">Nota</label>
                        <input type="text" wire:model='nota' class="form-control" placeholder="Nota" required> 
                        @error('nota') <span class="text-danger">{{ $message  }}</span> @enderror
                      </div>
                    </div>
                    @endif
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
              <button type="submit" class="btn btn-primary">Guardar cambios</button>
            </div>
          </form>
          </div>
        </div>
      </div>
    
      @if ($alert == true)
      <script>
        swal("Registro!", "Registro guardado correctamente!", "success");
        $('#modalParametro').modal('hide')
      </script>
      @endif
    
  </div>
   

