<div class="p-7">

    <div class="text-2xl">{{$title ?? 'Importazione Dati'}}</div>
    <div class="mt-3 text-lg text-gray-400">Scarica il template con il pulsante "Download Template" e,
        dopo averlo compilato, caricalo e premi "Importa"</div>

    <x-success-button wire:click.prevent="downloadTemplate()" target="_blank" class="my-8 w-full h-12">
        <span class="text-2xl"><i class="mr-3 fa-solid fa-file-arrow-down h-8"></i> Scarica Template</span>
    </x-success-button>

    <hr>

    <form wire:submit.prevent="save" class="mt-8 container">


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
    <br>
    <br>

    <div class="row p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
        <svg class="flex-shrink-0 inline w-4 h-4 me-3 mt-[2px]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
        </svg>
        <span class="sr-only">Danger</span>
        <div>
            <span class="font-medium">Ensure that these requirements are met:</span>
            <ul class="mt-1.5 list-disc list-inside">
                <li>At least 10 characters (and up to 100 characters)</li>
                <li>At least one lowercase character</li>
                <li>Inclusion of at least one special character, e.g., ! @ # ?</li>
            </ul>
        </div>
    </div>

</div>
