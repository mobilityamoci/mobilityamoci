<div class="p-7">
    <h3 class="text-2xl">Crea Linea Pedibus per <b>"{{$this->selectedSchool->name}}"</b></h3>

    <div class="my-5">
        <label for="name"
               class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nome</label>
        <x-jet-input wire:model.defer="newName" class="w-full"/>
    </div>
    <hr class="h-1 my-8 bg-gray-200 border-0 dark:bg-gray-700">

    <div class="flex flex-row-reverse my-3 gap-4">
        <x-jet-button wire:click="createLine">Crea Linea</x-jet-button>
        <x-jet-secondary-button wire:click="$emit('close-modal')">Chiudi</x-jet-secondary-button>

    </div>
</div>
