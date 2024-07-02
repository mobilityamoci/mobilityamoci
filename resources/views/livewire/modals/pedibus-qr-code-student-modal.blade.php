<div class="p-7">
    <h3 class="text-2xl">Invia QRCode ai genitori di <b>"{{$this->student->fullName()}}"</b></h3>


    <object type="application/pdf" style="min-height: 80vh; min-width: 100%"
            id="pdfViewer" data="data:application/pdf;base64,{{$this->qrCode}}">
        <embed id="preview" type="application/pdf">
    </object>

    <div class="my-5">
        <label for="emails"
               class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Inserire email dei genitori (divise
            da virgola, se più di una)</label>
        <x-jet-input id="emails" wire:model.defer="emails" class="w-full"/>
        @error('emails') <span class="text-red-700">{{ $message }}</span> @enderror

    </div>

    <hr class="h-1 my-8 bg-gray-200 border-0 dark:bg-gray-700">

    <div class="flex flex-row-reverse my-3 gap-4">
        <x-jet-button wire:click="sendMail">Invia QRCode per Email</x-jet-button>
        <x-jet-secondary-button wire:click="$emit('close-modal')">Chiudi</x-jet-secondary-button>
    </div>
</div>
