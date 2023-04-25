<div>
    {{-- Be like water. --}}
    <div class="row">
        <div class="col-md-12">
            <h5>Este es mi componente</h5>
            <form wire:submit.prevent='create'>
                <input type="text" class="form-control" placeholder="Nombre" wire:model='name'>
                @error('name') <span class="text-danger">{{ $message  }}</span> @enderror
                {{-- <button type="button" class="btn btn-success" wire:click='increment'> + </button>
                {{$count}} --}}
                <button type="submit" class="btn btn-success"> + </button>
            </form>
        </div>
    </div>
</div>
 