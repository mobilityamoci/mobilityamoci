<div class="p-7">
    <h3 class="text-2xl">Crea Fermata Pedibus per <b>"{{$this->pedibusLine->name}}"</b></h3>

    <div class="my-5">
        <label for="name"
               class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nome</label>
        <x-jet-input wire:model.defer="newName" class="w-full"/>
    </div>
    <label for="address"
           class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Indirizzo</label>
    <x-jet-input wire:model.defer="newAddress" class="w-full"/>
    <div class="my-5">
        <label for="order"
               class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Ordine</label>
        <x-select wire:model.defer="newOrder" class="w-full">
            @for($count = 1; $count <= $this->pedibusStopsCount+1;$count++)
                <option value="{{$count}}">{{$count}}</option>
            @endfor
        </x-select>
    </div>
    <div class="my-5">
        <label for="time"
               class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Orario di arrivo</label>
        <input type="time" id="time" name="time"
               class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
               min="09:00" max="18:00" value="00:00" required/>
    </div>
    <hr class=" h-1 my-8 bg-gray-200 border-0 dark:bg-gray-700">

    <div class="flex flex-row-reverse my-3 gap-4">
        <x-jet-button wire:click="createStop">Crea Fermata</x-jet-button>
        <x-jet-secondary-button wire:click="$emit('close-modal')">Chiudi</x-jet-secondary-button>
    </div>
</div>
