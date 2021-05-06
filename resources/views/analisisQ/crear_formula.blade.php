@extends('voyager::master')

@section('content')

  @section('page_header')
  <h6 class="page-title"> 
    <i class="fa fa-square-root-alt"></i>
    Formulas
  </h6>

  @stop
    <div class="container">
        <div class="row">
            <div class="col-md-2">
                <button type="submit" class="btn btn-success">Guardar</button>
                <button type="button" class="btn btn-secondary" >Probar</button>
            </div>
        </div>
    
    </div>
    
    <div class="container"> 
    <div class="row">  
         
        <div class="col-md-3">
           
           
            <input type="text" wire:model='formula' class="form-control" placeholder="Formula">
        </div>
        <div class="col-md-3">
            
            <input type="text" wire:model='Prom_Dmin' class="form-control" placeholder="Formula sistema">    
        </div>
        <div class="col-md-3">
           
            <button type="submit" class="btn btn-danger">Asignar</button>
        </div>
        
        </div>
    </div>
    

        <div class="col-md-2">
        <label for="">Área      </label>
        <select>
            <option value="0">Selecciona...</option>
            <option value="1">ABS</option>
            <option value="2">POTABLE</option>
            <option value="2">FISICOQUIMICO</option>
          </select>
        </div> 
        <div class="col-md-2">
            <label for="">Técnica</label>
            <select>
                <option value="0">Selecciona...</option>
                <option value="1">ABS</option>
                <option value="2">POTABLE</option>
                <option value="2">FISICOQUIMICO</option>
              </select>
        </div>
    
        

@endsection  
