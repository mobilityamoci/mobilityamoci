<div class="p-7">
    <h2 class="text-3xl">Incolla il codice copiato da un sondaggio:</h2>

    <div class="mt-16 mb-12">
        <x-jet-input wire:model="uuid" id="uuid" placeholder="Inserisci il codice" class="rounded-lg w-full"></x-jet-input>
    </div>
    <hr class="h-0.5 bg-gray-200 border-0 dark:bg-gray-700">

    <div class="flex flex-row-reverse my-3 gap-4">
        <x-jet-button wire:key="import_survey" wire:click="importSurvey">Importa Sondaggio</x-jet-button>
        <x-jet-secondary-button wire:key="close_modal" wire:click="$emit('close-modal')">Chiudi</x-jet-secondary-button>
    </div>
</div>
