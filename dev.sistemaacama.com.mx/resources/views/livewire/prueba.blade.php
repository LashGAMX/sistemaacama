
  
<div style="text-align: center">
    <br> 
    <button wire:click="increment">+</button>
    <h1>{{ $count }}</h1>
    <button wire:click="decrement">-</button>
    <input type="numeber" wire:model='a';>
    <button wire:click="multi">Mult</button>

    <h1>{{ $a }}</h1>
    <div>
        <input type="text" placeholder="nombre" wire:model='nombre';>
        <input type="text" placeholder="apellido" wire:model='apellido';>
        <input type="text" placeholder="Segundo Apellido" wire:model='SegApellido';>
        <button wire:click="borrar">Borrar</button>
    </div>

    
    <textarea>
        {{ $nombre}} {{$apellido}} {{$SegApellido}}
    </textarea>
    
</div>