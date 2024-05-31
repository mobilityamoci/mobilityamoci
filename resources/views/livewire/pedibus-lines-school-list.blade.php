<div>
    <div style="width: 100%; height: 90vh">
        <x-schools-select/>

        <div class="flex justify-between">
            <div class="flex items-center">
                <x-jet-button type="button" class="mt-9"
                              wire:click="$emit('openModal', 'modals.pedibus-line-create',{{json_encode(['selectedSchoolId' => $selectedSchoolId])}})">
                    <i class="fa-solid fa-route mr-2"></i> Aggiungi Linea
                </x-jet-button>
            </div>
        </div>
        <ul>
            @foreach($this->pedibusLines as $pedibusLine)
                <li class="my-10">
                    <livewire:pedibus-line-edit :key="$pedibusLine->id"
                                                :selected-line-id="$pedibusLine->id"></livewire:pedibus-line-edit>
                </li>
            @endforeach
        </ul>

    </div>
</div>
