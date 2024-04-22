<div class="p-7">
    <h2 class="text-3xl">Seleziona con chi condividere questo sondaggio:</h2>

    <div class="flex flex-row-reverse m-3 gap-4">
        <x-jet-button wire:key="all_section_share_button" wire:click="selectWholeSchool">Seleziona tutta la scuola</x-jet-button>

    </div>
    <div class="my-14 flex flex-wrap">
        @foreach($this->selectedSchool->sections as $section)
            <div class="flex items-center m-4">
                <x-jet-checkbox wire:key="checkbox_{{$section->id}}" wire:model="selectedSectionIds" id="checkbox-{{$section->id}}" value="{{$section->id}}" class="w-4 h-4"/>
                <x-jet-label for="checkbox-{{$section->id}}" class="m-2 text-md">{{$section->name}}</x-jet-label>
            </div>
        @endforeach
    </div>

    <div class="flex flex-row-reverse m-3 gap-4">
        <x-jet-button wire:key="share_button" wire:click="shareToSections">Condividi</x-jet-button>
        <x-jet-secondary-button wire:key="close_modal" wire:click="$emit('close-modal')">Chiudi</x-jet-secondary-button>
    </div>
</div>
