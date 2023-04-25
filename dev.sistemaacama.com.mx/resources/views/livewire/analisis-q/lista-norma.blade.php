<div>
    <div class="row">
        <form wire:submit.prevent="show">
            <div class="col-md-10">
                <label for="normas"> Selecciona una norma</label>
                <select class="form-control" wire:model='idNorma'>
                    <option value="0">Sin seleccionar</option>
                    @foreach ($model as $item)
                        <option value="{{$item->Id_norma}}">{{$item->Clave_norma}}</option>
                    @endforeach
                </select>
            </div> 
            <div class="col-md-2">
                <button type="submit" class="btn btn-sm btn-primary"><i class="voyager-search"></i> Buscar</button>
            </div>
        </form>
    </div>
</div>
