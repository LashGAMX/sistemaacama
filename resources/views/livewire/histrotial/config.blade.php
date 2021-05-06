<div>
    {{-- Modal --}}
    {{-- <div wire:ignore.self class="modal fade" id="modalHistConfig" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <form wire:submit.prevent="store">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Modificar sucursal</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <input type="text" wire:model="idSuc" hidden>
              <label for="">Sucursal</label>
              <input type="text" wire:model='name' class="form-control" placeholder="Sucursal">
              @error('name') <span class="text-danger">{{ $message  }}</span> @enderror
            </div>
            <div class="modal-body">
              <input type="text" wire:model="nota" hidden>
              <label for="">Nota</label>
              <input type="text" wire:model='nota' class="form-control" placeholder="Nota"> 
              @error('nota') <span class="text-danger">{{ $message  }}</span> @enderror
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
              <button type="submit" class="btn btn-primary">Guardar cambios</button>
            </div>
          </form>
          </div>
        </div>
      </div> --}}
      <h1>Esté es mi componente</h1>
</div>
