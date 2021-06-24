<div>
    <div class="">row</div>
    <div class="col-md-12">
        <h1>Tabla de prueba</h1>
    </div>

    <div class="col-md-12" style="display: flex">
        <div class="col-md-12">
            <button class="btn btn-success" wire:click='btnCrear'>Crear</button>
        </div>
    
        <div class="col-md-6">
            <input type="text" class="form-control" placeholder="Buscar" wire:model='buscar'>
        </div>        
    </div>

    <div class="col-md-12">
        @if ($sw == true)
            <form wire.submit.prevent='create'>
                <input type="text" class="form-control" placeholder="Grupo" wire:model='grupo'>
                <input type="text" class="form-control" placeholder="Comentario" wire:model='comentario'>
                <button type="submit" class="btn btn-success">Aceptar</button>
                <button type="button" class="btn btn-danger">Cancelar</button>
            </form>
        @endif
    </div>

    <div class="col-md-12">
        <table class="table">
            <thead>
                <tr>
                    <td>Id</td>
                    <td>Grupo</td>
                    <td>Comentario</td>
                </tr>
            </thead>

            <tbody>
                @foreach ($model as $item)
                    <tr>
                        <td>{{$item ->Id_grupo}}</td>
                        <td>{{$item ->Grupo}}</td>
                        <td>{{$item ->Comentarios}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
