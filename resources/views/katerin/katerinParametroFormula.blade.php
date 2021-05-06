@extends('voyager::master')
 
@section('content')

  @section('page_header')

  <div class="row">
      <div class="col-md-12">
                                              <div>

                                                  <div class="row">
                                                    <div class="col-md-8">
                                                      <button class="btn btn-success btn-sm" ><i class="voyager-plus"></i> Crear</button>
                                                    </div>
                                                    <div class="col-md-3">
                                                      <input type="search" class="form-control" placeholder="Buscar">
                                                    </div>
                                                  </div>
                                                  <div class="row">
                                                      <div class="col-md-3">


                                                      <input type="text" class="form-control" placeholder="Area de análisis">
                                                      <select name="" id="" class="form-control">
                                                        <option>Absorción Atómica</option>
                                                        <option>Grasas y Aceites</option>
                                                      </select>
                                                      <br/>
                                                      <br/>
                                                      <br/>
                                                      <input type="text" class="form-control" placeholder="Parametro">
                                                      <select name="" id="" class="form-control">
                                                        <option>Boro</option>
                                                        <option>Cianuro</option>
                                                      </select>
                                                      <br/>
                                                      <br/>
                                                      <br/>
                                                      <input type="text" class="form-control" placeholder="Tecnica">
                                                      <select name="" id="" class="form-control">
                                                        <option>Espectrofotométrica</option>
                                                        <option>Gravimétrica</option>
                                                        <option> Microbiología</option>
                                                      </select>

                                                      </div>
                                                      <div class="col-md-4">
                                                      <button  class="btn btn-info btn-sm"> Probar Formula</button>
                                                        <br/>
                                                        <br/>
                                                        <br/>
                                                      <div class="col-md-12">
                                                      <label for="">Formula</label>
                                                      <input type="text" placeholder="((((7814-*1301))))">
                                                      </div>
                                                      <br/>
                                                      <br/>
                                                      <br/>

                                                      <div class="col-md-12"> 
                                                      <label for="">Formula</label>
                                                      <input type="text" placeholder="((((7814-*1301))))">
                                                      </div>
                                                      <br/>
                                                      <br/>
                                                   

                                                        <div class="col-md-12"  >
                                                          <table  class="table table-bordered" >
                                                            <thead>
                                                            <tr>
                                                            <td>Formula </td>
                                                            <td>Tipo</td>
                                                            <td>Valor </td>
                                                            <td>Decimal</td>
                                                            </tr>
                                                            </thead>
                                                          <tbody>
                                                          <tr>
                                                            <td>ABS </td>
                                                            <td>const</td>
                                                            <td>0,023 </td>
                                                            <td><input type="text"></td>
                                                            </tr>
                                                            <tr>
                                                            <td>ABS </td>
                                                            <td>const</td>
                                                            <td> <select name="" id="" class="form-control">
                                                        <option>Primer nivel 1</option>
                                                        <option>segundo nivel  2</option>
                                                        <option>Tercer nivel 3</option>
                                                      </select> </td>
                                                            <td><input type="text"></td>
                                                            </tr>
                                                          </tbody>
                                                          </table>
                                                        </div>
                                                      </div>
                                                      <div class="col-md-4">
                                                        <div class="col-md-12">
                                                        <textarea class="form-control"></textarea>
                                                        </div>
                                                      </div>
                                                  </div>
                                                </div>
                                          </div>
  </div>
  @stop

@endsection

