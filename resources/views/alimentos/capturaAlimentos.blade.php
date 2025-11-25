@extends('voyager::master')
@section('content')
<link rel="stylesheet" href="{{asset('/public/assets/summer/summernote.min.css')}}">
<link rel="stylesheet" href="{{asset('/public/css/laboratorio/analisis/captura.css')}}">

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 border border-dark">
            <div class="row">
                <div class="col-md-6">
                    <center><span>Lote</span></center>
                    <table class="table" id="tblLote">
                        <thead class="">
                            <tr>
                                <th>Id</th>
                                <th>Fecha</th>
                                <th>Matriz</th>
                                <th>Asignados</th>
                                <th>Liberados</th>
                                <th>Opc</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>null</td>
                                <td>null</td>
                                <td>null</td>
                                <td>0</td>
                                <td>0</td>
                                <td>n/a</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-4">
                        <center><span>Herramientass</span></center>
                            <label for="matriz">Matriz</label>
                            <select name="" id="matriz" class="select2">
                                @foreach($matriz as $item)
                                <option value="{{$item->Id_matriz_parametro}}">{{$item->Matriz}}</option>
                                @endforeach
                            </select>
                            <input type="date"  style="width:100%">
                            <br>
                            <input type="text" placeholder="203A-100/25-1" style="width:100%">
                        </div>
                        <div class="col-md-4">
                            <button class="fas fa-search btn-info">Buscar</button>
                            <button class="fas fa-plus btn-success">Crear lote</button>
                            <button class="fas fa-archive btn-primary">Buscar</button>
                        </div>
                    </div>
    
                </div>
            </div>
        </div>
    </div>
</div>
 <!-- Spinner -->
<div id="spinner"></div>

@endsection
@section('javascript')
<script src="{{asset('/public/js/alimentos/capturaAlimentos.jsx')}}?v=1.0.0"></script>

<script src="{{asset('/assets/summer/summernote.js')}}"></script>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
@stopa