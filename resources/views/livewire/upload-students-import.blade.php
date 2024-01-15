<div class="p-7">

    <div class="text-2xl">Importazione dati</div>
    <div class="mt-3 text-lg text-gray-400">Scarica il template con il pulsante "Download Template" e,
        dopo averlo compilato, caricalo e premi "Importa"</div>

    <x-success-button wire:click.prevent="downloadTemplate()" target="_blank" class="my-8 w-full h-12">
        <span class="text-2xl"><i class="mr-3 fa-solid fa-file-arrow-down h-8"></i> Scarica Template</span>
    </x-success-button>

    <hr>

    <form wire:submit.prevent="save" class="mt-8">


        <label class="block text-lg mb-2 text-sm font-medium text-gray-900" for="file_input">Carica File compilato</label>
        <input class="mt-5 text-sm text-grey-500
            file:mr-5 file:py-2 file:px-6
            file:rounded-full file:border-0
            file:text-sm file:font-medium
            file:bg-blue-50 file:text-blue-700
            hover:file:cursor-pointer hover:file:bg-green-50
            hover:file:text-green-700" aria-describedby="file_input_help" id="file_input" type="file" wire:model="importFile">
        <p class="mt-1 text-sm text-gray-500" id="file_input_help"></p>

        <x-success-button wire:loading.class="bg-gray" wire:loading.attr="disabled" class="float-right mb-8 mt-10" wire:click.prevent="submitImport()">Importa</x-success-button>

    </form>

</div>
