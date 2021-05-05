<div>
    <div class="row">
        <form wire:submit.prevent="descomponer">
        <div class="col-md-4">
          <input type="text" wire:model='idUser' hidden>
          <label for="">formula</label>
            <input type="text" wire:model='formula' class="form-control" placeholder="formula">
            @error('name') <span class="text-danger">{{ $message  }}</span> @enderror
        </div>
        <div class="col-md-4">
          <button class="btn btn-sm btn-success" type="submit" ><i class="voyager-check"></i> <span hidden-sm hidden-xs>Aceptar</span> </button>
        </div>
      </form>
      </div>   {{-- Nothing in the world is as soft and yielding as water. --}}
</div>
