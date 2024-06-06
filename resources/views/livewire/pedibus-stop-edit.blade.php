<div class="flex flex-row my-5 mx-auto gap-4 content-baseline">
    <div  class="flex-1">
{{--        <x-jet-label>Nome</x-jet-label>--}}

        <x-jet-input wire:model.lazy="pedibusStop.name" class="w-full"/>
    </div>
    <div class="flex-1">
{{--        <x-jet-label>Indirizzo</x-jet-label>--}}

        <x-jet-input wire:model.lazy="pedibusStop.address" class="w-full"/>
    </div>
    <div class="flex-1">
{{--        <x-jet-label>Ordine</x-jet-label>--}}
        <x-select wire:model.lazy="pedibusStop.order" class="w-full">
            @for($count = 1; $count <= $this->pedibusStopsCount; $count++)
                <option value="{{$count}}">{{$count}}</option>
            @endfor
        </x-select>
    </div>
    <div >
        <x-jet-button
            class="mt-auto"
            wire:click="update"
        >Salva
        </x-jet-button>
    </div>
    <div>
        <x-jet-button
            wire:click="$emit('openModal', 'modals.pedibus-stop-map-draw-modal',{{json_encode(['pedibusLineId' => $this->pedibusLine->id, 'pedibusStopId' => $pedibusStop->id])}})"

        >
            <i class="fa-solid mr-2 fa-location-dot"></i>
            Disegna
        </x-jet-button>
    </div>
</div>
