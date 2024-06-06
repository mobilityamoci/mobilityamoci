<div class="grid grid-cols-5 my-5 mx-auto gap-4 content-baseline">
    <div class="col-span-1"></div>

    <div class="col-span-1">
        <x-jet-label>Nome</x-jet-label>

        <x-jet-input wire:model.lazy="pedibusStop.name" class="w-full"/>
    </div>
    <div class="col-span-1">
        <x-jet-label>Indirizzo</x-jet-label>

        <x-jet-input wire:model.lazy="pedibusStop.address" class="w-full"/>
    </div>
    <div class="col-span-1">
        <x-jet-label>Ordine</x-jet-label>
        <x-select wire:model.lazy="pedibusStop.order" class="w-full">
            @for($count = 1; $count <= $this->pedibusStopsCount; $count++)
                <option value="{{$count}}">{{$count}}</option>
            @endfor
        </x-select>
    </div>
    <div class="col-span-1">
        <x-jet-button
            wire:click="update"
        >Salva
        </x-jet-button>
    </div>
</div>
