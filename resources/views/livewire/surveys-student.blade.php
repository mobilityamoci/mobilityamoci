<div class="md:flex justify-center">
    <table class="my-table table-auto">
        <thead class="my-header">
        <tr>
            <th class="my-th">Nome</th>
            <th class="my-th">Data condivisione</th>
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
                    <x-jet-secondary-button wire:click="copyUuid" @click="$clipboard('{{$survey->uuid}}')">Copia codice</x-jet-secondary-button>
                    <x-jet-button wire:click="$emit('openModal', 'modals.survey-share-modal', {{json_encode(['selectedSchoolId' => $selectedSchoolId, 'selectedSurveyId' => $survey->id])}})">Condividi</x-jet-button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
