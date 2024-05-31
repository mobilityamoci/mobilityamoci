<div class="block p-6 bg-white border border-gray-200 rounded-lg shadow">
    <div class="grid grid-cols-5 my-5 mx-auto gap-4 content-baseline">
        <div class="col-span-2">
            <x-jet-input wire:model.lazy="line.name" class="w-full"/>
        </div>
        <div class="col-span-1"></div>
        <div class="col-span-1">
            @if($line->line)
                <x-jet-button
                        wire:click="$emit('openModal', 'modals.pedibus-line-map-show-modal',{{json_encode(['pedibusLineId' => $this->line->id])}})">
                    Visualizza Linea
                </x-jet-button>
            @endif
        </div>
        <div class="col-span-1">
            <x-jet-button
                wire:click="$emit('openModal', 'modals.pedibus-line-map-draw-modal',{{json_encode(['pedibusLineId' => $this->line->id])}})"
            >Disegna Linea</x-jet-button>
        </div>
    </div>
</div>
