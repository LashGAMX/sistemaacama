<div>
    <div class="row">
        <form wire:submit.prevent="show">
            <div class="col-md-10">
                <label for="normas"> Seleciona una norma</label>
                <select class="form-control" wire:model='norma'>
                    <option value="0">Sin seleccionar</option>
                    @foreach ($model as $item)
                        @if ($idNorma == $item->Id_norma)
                            <option value="{{$item->Id_norma}}" selected>{{$item->Clave_norma}}</option>
                        @else
                            <option value="{{$item->Id_norma}}">{{$item->Clave_norma}}</option>
                        @endif
                    @endforeach
                </select>
            </div> 
            <div class="col-md-2">
                <button type="submit" class="btn btn-sm btn-primary"><i class="voyager-search"></i> Buscar</button>
            </div>
        </form>
    </div>
</div>
