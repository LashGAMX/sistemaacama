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
                    <div class="container-fluid">
                        <div class="row justify-content-center">
                            <div class="col-11 col-sm-9 col-md-7 col-lg-6 col-xl-5 text-center p-0 mt-3 mb-2">
                                <div class="card px-0 pt-4 pb-0 mt-3 mb-3">
                                    <h2 id="heading">Sign Up Your User Account</h2>
                                    <p>Fill all form field to go to next step</p>
                                    <form id="msform">
                                        <!-- progressbar -->
                                        <ul id="progressbar">
                                            <li class="active" id="account"><strong>Account</strong></li>
                                            <li id="personal"><strong>Personal</strong></li>
                                            <li id="payment"><strong>Image</strong></li>
                                            <li id="confirm"><strong>Finish</strong></li>
                                        </ul>
                                        <div class="progress">
                                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div> <br> <!-- fieldsets -->
                                        <fieldset>
                                            <div class="form-card">
                                                <div class="row">
                                                    <div class="col-7">
                                                        <h2 class="fs-title">Account Information:</h2>
                                                    </div>
                                                    <div class="col-5">
                                                        <h2 class="steps">Step 1 - 4</h2>
                                                    </div>
                                                </div> <label class="fieldlabels">Email: *</label> <input type="email" name="email" placeholder="Email Id" /> <label class="fieldlabels">Username: *</label> <input type="text" name="uname" placeholder="UserName" /> <label class="fieldlabels">Password: *</label> <input type="password" name="pwd" placeholder="Password" /> <label class="fieldlabels">Confirm Password: *</label> <input type="password" name="cpwd" placeholder="Confirm Password" />
                                            </div> <input type="button" name="next" class="next action-button" value="Next" />
                                        </fieldset>
                                        <fieldset>
                                            <div class="form-card">
                                                <div class="row">
                                                    <div class="col-7">
                                                        <h2 class="fs-title">Personal Information:</h2>
                                                    </div>
                                                    <div class="col-5">
                                                        <h2 class="steps">Step 2 - 4</h2>
                                                    </div>
                                                </div> <label class="fieldlabels">First Name: *</label> <input type="text" name="fname" placeholder="First Name" /> <label class="fieldlabels">Last Name: *</label> <input type="text" name="lname" placeholder="Last Name" /> <label class="fieldlabels">Contact No.: *</label> <input type="text" name="phno" placeholder="Contact No." /> <label class="fieldlabels">Alternate Contact No.: *</label> <input type="text" name="phno_2" placeholder="Alternate Contact No." />
                                            </div> <input type="button" name="next" class="next action-button" value="Next" /> <input type="button" name="previous" class="previous action-button-previous" value="Previous" />
                                        </fieldset>
                                        <fieldset>
                                            <div class="form-card">
                                                <div class="row">
                                                    <div class="col-7">
                                                        <h2 class="fs-title">Image Upload:</h2>
                                                    </div>
                                                    <div class="col-5">
                                                        <h2 class="steps">Step 3 - 4</h2>
                                                    </div>
                                                </div> <label class="fieldlabels">Upload Your Photo:</label> <input type="file" name="pic" accept="image/*"> <label class="fieldlabels">Upload Signature Photo:</label> <input type="file" name="pic" accept="image/*">
                                            </div> <input type="button" name="next" class="next action-button" value="Submit" /> <input type="button" name="previous" class="previous action-button-previous" value="Previous" />
                                        </fieldset>
                                        <fieldset>
                                            <div class="form-card">
                                                <div class="row">
                                                    <div class="col-7">
                                                        <h2 class="fs-title">Finish:</h2>
                                                    </div>
                                                    <div class="col-5">
                                                        <h2 class="steps">Step 4 - 4</h2>
                                                    </div>
                                                </div> <br><br>
                                                <h2 class="purple-text text-center"><strong>SUCCESS !</strong></h2> <br>
                                                <div class="row justify-content-center">
                                                    <div class="col-3"> <img src="https://i.imgur.com/GwStPmg.png" class="fit-image"> </div>
                                                </div> <br><br>
                                                <div class="row justify-content-center">
                                                    <div class="col-7 text-center">
                                                        <h5 class="purple-text text-center">You Have Successfully Signed Up</h5>
                                                    </div>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>


        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-primary" wire:click="create">Guardar cambios</button>
        </div>

      </div>
    </div>
  </div>
<!-- Fin de Modal Principal -->

    </div>
