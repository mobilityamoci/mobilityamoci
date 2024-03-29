<div>
    <x-jet-label for="school">Seleziona la scuola:</x-jet-label>
    <x-select class="col-auto" wire:model="selectedSchoolId" wire:change="refresh" label="Seleziona Scuola"
              id="school">
        @foreach($schools as $school)
            <option @selected($selectedSchoolId == $school->id) value="{{$school->id}}">{{$school->name}}</option>
        @endforeach
    </x-select>
    <x-jet-label class="mt-4" for="section">Seleziona la sezione:</x-jet-label>
    <x-select class="col-auto" wire:model="selectedSectionId" wire:change="refresh" label="Seleziona Sezione"
              id="section">
        <option @selected($selectedSectionId == 0) value="0">Tutte le sezioni</option>
        @foreach($this->sections as $section)
            <option
                @selected($selectedSectionId == $section->id) value="{{$section->id}}">{{$section->name}}</option>
        @endforeach
    </x-select>



    <div class="my-8">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">

                    <div class="card-body items-center">
                        <div class="flex items-center justify-end">
                            <x-jet-button wire:click="downloadExport"><i class="fa-solid fa-fw fa-file-excel mr-2"></i> Scarica Excel</x-jet-button>
                        </div>
                        {!! $this->chartTransport->container() !!}

                        <div class="flex">

                            <div class="w-1/2">
                                {!! $this->chartPollution->container() !!}
                            </div>
                            <div class="w-1/2">
                                {!! $this->chartCalories->container() !!}
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>

@push('scripts')
    <script src="{{ $this->chartTransport->cdn() }}"></script>
    <script src="{{ $this->chartPollution->cdn() }}"></script>
    <script src="{{ $this->chartCalories->cdn() }}"></script>

    {{ $this->chartTransport->script() }}
    {{ $this->chartPollution->script() }}
    {{ $this->chartCalories->script() }}
@endpush
