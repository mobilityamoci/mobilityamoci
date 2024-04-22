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

    <div class="relative table-wrap block overflow-y-auto shadow-md sm:rounded-lg mt-9"
         style="max-height: 70%; max-width: 100%;">
        <table class="my-table table-auto">
            <thead class="my-header">
                <tr>
                    <th class="my-th">Nome</th>
                    <th class="my-th">Azioni</th>
                </tr>
            </thead>
            <tbody>
            @foreach($this->surveys as $survey)
                <tr wire:key="{{$survey->id}}">
                    <td class="my-th">
                        {{$survey->name}}
                    </td>
                    <td class="my-th">
                        <x-jet-secondary-button wire:click="$emit('openModal', 'modals.survey-show-modal', {{json_encode(['selectedSurveyId' => $survey->id])}})">Visualizza</x-jet-secondary-button>
                        <x-jet-button wire:click="$emit('openModal', 'modals.survey-share-modal', {{json_encode(['selectedSchoolId' => $selectedSchoolId, 'selectedSurveyId' => $survey->id])}})">Condividi</x-jet-button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

</div>
