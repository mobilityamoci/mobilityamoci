<div>
    <x-jet-label for="school">Seleziona la scuola:</x-jet-label>
    <x-select class="col-auto" wire:model="selectedSchoolId" wire:change="schoolChanged" label="Seleziona Scuola"
              id="school">
        @foreach($this->schools as $school)
            <option @selected($this->selectedSchoolId == $school->id) value="{{$school->id}}">{{$school->name}}</option>
        @endforeach
    </x-select>
</div>
