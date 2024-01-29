<div>
    <h3 class="text-3xl font-bold uppercase">I miei dati</h3>
    <div class="grid my-6 sm:grid-cols-1 md:grid-cols-3 gap-4">
        <div>

            <x-jet-label class="text-xl">Nome</x-jet-label>
            <x-jet-input class="w-full" wire:model.lazy="student.name"></x-jet-input>
            @if($errors->has('student.name'))
                <div class="mt-2 text-sm text-red-600">
                    {{$errors->first('student.name')}}
                </div>
            @endif
        </div>
        <div>
            <x-jet-label class="text-xl">Cognome</x-jet-label>
            <x-jet-input class="w-full" wire:model.lazy="student.surname"></x-jet-input>
            @if($errors->has('student.surname'))
                <div class="mt-2 text-sm text-red-600">
                    {{$errors->first('student.surname')}}
                </div>
            @endif
        </div>
        <div>
            <x-jet-label class="text-xl">Sezione</x-jet-label>
            <x-select class="w-full" wire:model.lazy="student.section">
                @foreach($this->sections as $section)
                    <option value="{{$section->id}}" class="capitalize">{{$section->name}}</option>
                @endforeach
            </x-select>
            @if($errors->has('student.section'))
                <div class="mt-2 text-sm text-red-600">
                    {{$errors->first('student.section')}}
                </div>
            @endif
        </div>
        <div>
            <x-jet-label class="text-xl">Comune di domicilio</x-jet-label>
            <x-select class="w-full" wire:model="student.town_istat">
                @foreach($this->comuni as $key => $comune)
                    <option value="{{$key}}">{{$comune}}</option>
                @endforeach
            </x-select>
            @if($errors->has('student.town_istat'))
                <div class="mt-2 text-sm text-red-600">
                    {{$errors->first('student.town_istat')}}
                </div>
            @endif
        </div>
        <div class="md:col-span-2">
            <x-jet-label class="text-xl">Indirizzo</x-jet-label>
            <x-jet-input class="w-full" wire:model.lazy="student.address"></x-jet-input>
            @if($errors->has('student.address'))
                <div class="mt-2 text-sm text-red-600">
                    {{$errors->first('student.address')}}
                </div>
            @endif
        </div>
    </div>

    <div class="grid justify-items-end">
        <x-success-button wire:click.prevent="saveStudent">Aggiorna Dati</x-success-button>
    </div>

    <h3 class="text-3xl font-bold uppercase">Il mio viaggio</h3>
    @forelse($this->student->trips as $index => $trip)
        <div wire:key="{{$trip->index}}">
            <x-jet-label class="flex items-center justify-center text-2xl mb-2">
                {{$loop->iteration}}° tappa
            </x-jet-label>
            <div class="grid my-6 sm:grid-cols-1 md:grid-cols-5 gap-4 border-b-2 pb-2 border-b-gray-400">
                <div>
                    <x-jet-label class="text-lg">Mezzo (Prima Opzione)</x-jet-label>
                    <x-select
                        wire:change="saveTrip({{$index}})"
                        wire:model="student.trips.{{$index}}.transport_1"
                        for="student.trips.{{$index}}.transport_1">
                        <option selected value="">--------------------</option>
                        @foreach($this->transports as $transport)
                            <option
                                value="{{$transport['id']}}">{{$transport['name']}}</option>
                        @endforeach
                    </x-select>
                    @if($errors->has('student.trips.'.$index.'.transport_1'))
                        <div class="mt-2 text-sm text-red-600">
                            {{$errors->first('student.trips.'.$index.'.transport_1')}}
                        </div>
                    @endif
                </div>
                {{--            <div>--}}
                {{--                <x-select--}}
                {{--                    wire:change="saveTrip({{$index}})"--}}
                {{--                    wire:model="student.trips.{{$index}}.transport_2"--}}
                {{--                    for="student.trips.{{$index}}.transport_2">--}}
                {{--                    <option selected value="{{NULL}}">--------------------</option>--}}
                {{--                    @foreach($this->transports as $transport)--}}
                {{--                        <option--}}
                {{--                            value="{{$transport['id']}}">{{$transport['name']}}</option>--}}
                {{--                    @endforeach--}}
                {{--                </x-select>--}}
                {{--                @if($errors->has('student.trips.'.$index.'.transport_2'))--}}
                {{--                    <div class="mt-2 text-sm text-red-600">--}}
                {{--                        {{$errors->first('student.trips.'.$index.'.transport_2')}}--}}
                {{--                    </div>--}}
                {{--                @endif--}}
                {{--            </div>--}}
                <div class="md:col-span-2">
                    <x-jet-label class="text-lg">Comune di arrivo o intermedio</x-jet-label>
                    <x-select class="w-full" wire:change="saveTrip({{$index}})"
                              wire:model="student.trips.{{$index}}.town_istat">
                        <option value="">Scuola</option>
                        @foreach($this->comuni as $key => $comune)
                            <option value="{{$key}}">{{$comune}}</option>
                        @endforeach
                    </x-select>
                    @if($errors->has('student.trips.'.$index.'.town_istat'))
                        <div class="mt-2 text-sm text-red-600">
                            {{$errors->first('student.trips.'.$index.'.town_istat')}}
                        </div>
                    @endif
                </div>
                @if($trip->town_istat != 0)
                    <div>
                        <x-jet-label class="text-lg">Indirizzo Intermedio</x-jet-label>
                        <x-jet-input class="w-full" wire:change.defer="saveTrip({{$index}})"
                                     wire:model.lazy="student.trips.{{$index}}.address"></x-jet-input>
                    </div>
                @endif
                <div class="flex items-end justify-items-end">
                    <x-jet-danger-button wire:click="deleteTrip({{$index}})" class="h-1/2">
                        <i class="fa-solid fa-trash mr-4"></i> Elimina Tappa
                    </x-jet-danger-button>
                </div>
            </div>
        </div>
    @empty
    @endforelse
    @if($addingTrip)
        <x-jet-label class="flex items-center justify-center text-2xl mb-2">
            {{sizeof($student->trips)+1}}° tappa
        </x-jet-label>
        <div class="grid my-6 sm:grid-cols-1 md:grid-cols-5 gap-4 border-b-2 pb-2 border-b-gray-400">
            <div>
                <x-jet-label class="text-lg">Mezzo (Prima Opzione)</x-jet-label>
                <x-select
                    wire:model="newTripTrans1">
                    <option selected value="">--------------------</option>
                    @foreach($this->transports as $transport)
                        <option
                            value="{{$transport['id']}}">{{$transport['name']}}</option>
                    @endforeach
                </x-select>
                @if($errors->has('newTripTrans1'))
                    <div class="mt-2 text-sm text-red-600">
                        {{$errors->first('newTripTrans1')}}
                    </div>
                @endif
            </div>
            <div class="md:col-span-2">
                <x-jet-label class="text-lg">Comune di arrivo o intermedio</x-jet-label>
                <x-select class="w-full"
                          wire:model="newTripIstat">
                    <option value="">Scuola</option>
                    @foreach($this->comuni as $key => $comune)
                        <option value="{{$key}}">{{$comune}}</option>
                    @endforeach
                </x-select>
                @if($errors->has('newTripIstat'))
                    <div class="mt-2 text-sm text-red-600">
                        {{$errors->first('newTripIstat')}}
                    </div>
                @endif
            </div>
            @if($newTripIstat != 0)
                <div>
                    <x-jet-label class="text-lg">Indirizzo Intermedio</x-jet-label>
                    <x-jet-input class="w-full"
                                 wire:model.lazy="newTripAddress"></x-jet-input>
                </div>
            @endif
            <div class="flex items-end justify-items-end">
                <x-success-button wire:click.prevent="addNewTrip">Aggiungi Tappa</x-success-button>
            </div>
        </div>
    @endif
    @if(isset($trip) && $trip->town_istat != 0)
        <div class="grid justify-items-end">
            <x-jet-button wire:click.prevent="$toggle('addingTrip')">@if($addingTrip)
                    Annulla
                @else
                    Aggiungi Tappa
                @endif
            </x-jet-button>
        </div>
    @elseif ($this->student->trips->count() > 0)
        <div >
            <x-jet-label class="flex items-center justify-center text-2xl mb-2">
                Viaggio concluso. Grazie!
            </x-jet-label>
        </div>
    @endif

</div>
