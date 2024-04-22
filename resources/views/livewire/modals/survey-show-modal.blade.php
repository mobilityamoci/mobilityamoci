<div  class="p-7">
    <h2 class="text-2xl">Visualizzazione Sondaggio lato studente</h2>
    <div class="p-10">
        @include('survey::standard', ['survey' => $this->survey, 'onlyView' => true])
    </div>

    <div class="flex flex-row-reverse m-3 gap-4">
        <x-jet-secondary-button wire:key="close_modal" wire:click="$emit('close-modal')">Chiudi</x-jet-secondary-button>
    </div>
</div>
