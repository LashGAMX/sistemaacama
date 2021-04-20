<div> 
    <div class="row">
        <div class="col-md-7">
            <button class="btn btn-success btn-md" wire:click='btnCreate'> <i class="voyager-plus"></i> Crear</button>
        </div>
        <div class="col-md-5">
            <input type="search" class="form-control" placeholder="Buscar">
        </div>
    </div>

    <div class="row">
        @if ($show != false)
            <form wire:submit.prevent='create'>
                <div class="row">
                    <div class="col-md-4">
                        <input type="text" class="form-control" wire:model='procedimiento' placeholder="Procedimiento" required>
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" wire:model='descripcion' placeholder="Descripción" required>
                    </div>
                    <div class="col-md-4">
                        <button class="btn btn-success" type="submit"><i class="voyager-check"></i> Guardar</button>
                        <button class="btn btn-danger" type="button" wire:click='btnCancel'><i class="voyager-x"></i> Cancelar</button>
                    </div>
                </div>
            </form>
        @endif
        <div class="col-md-12">
            <table class="table">
                <thead>
                    <tr>
                        <td>#</td>
                        <td>Procedimiento</td>
                        <td>Descripción</td>
                        <td>Creación</td>
                        <td>Modificación</td>
                        <td>Acción</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($model as $item)
                        @if ($item->deleted_at != null)
                            <tr class="bg-danger text-white">
                        @else
                            <tr>
                        @endif
                            <td>{{$item->Id_procedimiento}}</td>
                            <td>{{$item->Procedimiento}}</td>
                            <td>{{$item->Descripcion}}</td>
                            <td>{{$item->created_at}}</td>
                            <td>{{$item->updated_at}}</td>
                            <td></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
