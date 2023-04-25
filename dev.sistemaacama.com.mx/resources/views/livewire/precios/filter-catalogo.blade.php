<div>
    <div class="row">
        <form wire:submit.prevent="show">
            <div class="col-md-10">
                <label for="normas">Laboratorio</label>
                <select class="form-control" wire:model='idSucursal'> 
                   
                    @foreach ($model as $item)
                        <option value="{{$item->Id_sucursal}}">{{$item->Sucursal}}</option>
                    @endforeach
                </select> 

                <br>

                <select class="form-control" wire:model='idNorma'> 
                    <option value="0">Sin seleccionar</option>
                    @foreach ($normas as $item)
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
