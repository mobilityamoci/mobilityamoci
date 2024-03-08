<div class="p-7">
    <x-jet-button type="button" class="mt-9"
                  wire:click="$emit('openModal', 'modals.upload-m-m-import-modal')"
    >
        <i class="fa-solid fa-fw fa-file-excel mr-2"></i> Carica Excel Utenti
    </x-jet-button>
    <div class="place-items-center">
        <div class="mt-8">
            <livewire:users-table></livewire:users-table>
        </div>
    </div>
</div>
