    <div class="row">
        {{-- <input type="text" value="{{$idUser}}"> --}}
        <!-- Parte de Encabezado-->
      <div class="col-md-2">
        <button class="btn btn-success btn-sm"  data-toggle="modal" data-target="#modalCotizacionPrincipal">
            <i class="voyager-plus"></i> Crear</button>
      </div>
        {{-- {{$idUser}} --}}
       <div class="col-md-4 mt-2">
           <input type="date"  placeholder="Fecha inicio" class="form-control" value=""  wire:model="fechaRangoIncial">
            {{ "La Fecha a Buscar es:".$fechaRangoIncial}}
        </div>
       <div class="col-md-4 mt-2">
           <input type="date"  placeholder="Fecha inicio" class="form-control" value="" wire:model="fechaRangoFinal">
           {{"La Ultima Fecha a Buscar es: ".$fechaRangoFinal}}
       </div>

      <div class="col-md-2 mt-2">
        <input type="search" class="form-control" placeholder="Buscar" wire:model="search">
      </div>
       <!-- Fin Parte de Encabezado-->

        <!--Tabla -->
        <table class="table table-sm">
            <thead class="">
                <tr>
                    <th>Cliente</th>
                    <th>Folio Servicio</th>
                    <th>Cotización Folio</th>
                    <th>Empresa</th>
                    <th>Servicio</th>
                    <th>Fecha Cotización</th>
                    <th>Supervición</th>
                    <th>Acciónes</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($model as $item)
              <td>{{$item->Cliente}}</td>
              <td>{{$item->Folio_servicio}}</td>
              <td>{{$item->Cotizacion_folio}}</td>
              <td>{{$item->Empresa}}</td>
              <td>{{$item->Servicio}}</td>
              <td>{{$item->Fecha_cotizacion}}</td>
              <td>{{$item->Supervicion}}</td>
              <td>
                <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#modalCotizacionPrincipal">
                <i class="voyager-edit"></i> <span hidden-sm hidden-xs>Editar</span> </button>
                <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modalCotizacionHistorico" wire:click="details('{{$item->Id_cotizacion}}')">
                <i class="voyager-list" aria-hidden="true"></i>
                <span hidden-sm hidden-xs>Historico</span> </button>
                    <button type="button" class="btn btn-sm btn-dark" data-toggle="modal" data-target="#modalCotizacionHistorico" wire:click="details('{{$item->Id_cotizacion}}')">
                        <i class="voyager-documentation" aria-hidden="true"></i>
                        <span hidden-sm hidden-xs>Duplicar</span> </button>
              </td>
            </tr>
            @endforeach
            </tbody>
        </table>




 <!-- Modal Principal -->
 <div wire:ignore.self class="modal fade" id="modalCotizacionPrincipal" tabindex="-1" aria-labelledby="modalCotizacionPrincipal" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="width:98%">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body"> <!-- Body-->
                    <button wire:click="controlTab(1)" class="btn btn-info">Información Basica</button>
                    <button wire:click="controlTab(2)" class="btn btn-success">Parametros</button>
                    <button wire:click="controlTab(3)" class="btn btn-warning">Información Cotización</button>
                    {{$tabNombre}}
                    @if($tab == '1')
                    {{-- <input type="text" wire:model="testOne">
                    {{$testOne}} --}}


                     <!-- Boton Guardar -->
                     <div class="col-md-12 mt-1">
                        <button class="btn  btn-success" wire:click="create">Guardar</button>
                     </div>

                    </div>
                    @endif()

                    @if($tab == '2')
                    <input type="text" wire:model="testTwo">
                    {{$testTwo}}
                    @endif()

                    @if($tab == '3')
                    <input type="text" wire:model="testThree">
                    {{$testThree}}
                    @endif()


        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-primary" wire:click="create">Guardar cambios</button>
        </div>

      </div>
    </div>
  </div>
<!-- Fin de Modal Principal -->

    </div>

 <!-- Alpine.js -->
 <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
