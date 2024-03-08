<div style="width: 100%; height: 90vh">

    <x-jet-label for="school">Seleziona la scuola:</x-jet-label>
    <x-select class="col-auto mb-4" wire:model="selectedSchoolId" label="Seleziona Scuola"
              id="school">
        <option value="">Tutte</option>
        @foreach($schools as $school)
            <option @selected($selectedSchoolId == $school->id) value="{{$school->id}}">{{$school->name}}</option>
        @endforeach
    </x-select>
    <iframe style="width: 100%; height: 100%"
            src="{!!  $this->lizmapLink !!}"></iframe>
</div>
