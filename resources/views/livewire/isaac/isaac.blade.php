<div>
    Formula: {{$string}}
    <br>
<<<<<<< HEAD
    <div class="container">
        
        {{-- <div class="col-md-1"> --}}
            <button wire:click='abrir-parentesis'>(</button>
         {{-- </div> --}}
        {{-- <div class="col-md-1"> --}}
            <button wire:click='cerrar-parentesis'>)</button>
        {{-- </div> --}}
        {{-- <div class="col-md-1"> --}}
            <button wire:click='agregar'>+</button>
         {{-- </div> --}}
         {{-- <div class="col-md-1"> --}}
            <button wire:click='agregar'>-</button>
         {{-- </div> --}}
            <button wire:click='agregar'>*</button>

            <button wire:click='agregar'>/</button>
        </div>
=======
>>>>>>> 63563302587b40a422efaa51e6034a28e39898ef
    <input wire:model='formula' type="text"/>
    <button wire:click='agregar'>Agregar</button>
    {{ print_r($array) }}
   
</div>
