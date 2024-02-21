<div>
    <x-jet-label for="school">Seleziona la scuola:</x-jet-label>
    <x-select class="col-auto" wire:model="selectedSchoolId" wire:change="refresh" label="Seleziona Scuola"
              id="school">
        @foreach($schools as $school)
            <option @selected($selectedSchoolId == $school->id) value="{{$school->id}}">{{$school->name}}</option>
        @endforeach
    </x-select>

    <div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">

                        <div class="card-body">

                            <h1></h1>
                            {!! $this->chart->container() !!}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@push('scripts')
    <script src="{{ $this->chart->cdn() }}"></script>

    {{ $this->chart->script() }}
@endpush
