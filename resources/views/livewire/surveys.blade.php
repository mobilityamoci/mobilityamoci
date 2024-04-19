<div style="width: 100%; height: 90vh">
    <x-jet-label for="school">Seleziona la scuola:</x-jet-label>
    <x-select class="col-auto" wire:model="selectedSchoolId" wire:change="schoolChanged" label="Seleziona Scuola"
              id="school">
        @foreach($schools as $school)
            <option @selected($selectedSchoolId == $school->id) value="{{$school->id}}">{{$school->name}}</option>
        @endforeach
    </x-select>
    <x-jet-button type="button" class="mt-9 m-5"
                  wire:click="$emit('openModal', 'modals.survey-create-modal', {{json_encode(['selectedSchoolId' => $selectedSchoolId])}})">
        <i class="fa-solid fa-fw fa-file-excel mr-2"></i> Crea nuovo sondaggio
    </x-jet-button>
</div
