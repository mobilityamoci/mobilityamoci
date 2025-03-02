<div
    x-cloak
    @click.away="$wire.closeTransportsModal"
    x-show="$wire.showTransportsModal"
    x-transition:enter="transition ease-in duration-200"
    x-transition:enter-start="opacity-0 transform "
    x-transition:enter-end="opacity-100 transform "
    x-transition:leave="transition ease-out duration-200"
    x-transition:leave-start="opacity-100 transform"
    x-transition:leave-end="opacity-0 transform"
    style="display: none; z-index:1"
    x-on:keydown.escape.window="$wire.closeTransportsModal"
    class="my-modal flex  items-center justify-center fixed left-0 bottom-0 w-full h-full bg-gray-800 bg-opacity-90">
    <div class="bg-white rounded-lg w-1/2">
        <div class="flex flex-col items-start p-4">
            <div class="flex items-center w-full border-b pb-4">
                <div class="text-gray-900 font-medium text-lg">Modifica Percorso Studente</div>
                <svg wire:click="closeTransportsModal"
                     class="ml-auto fill-current text-gray-700 w-6 h-6 cursor-pointer"
                     xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 18">
                    <path
                        d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z"/>
                </svg>
            </div>
            @if($showTransportsModal && !is_null($editStudentIndex))
                <div class="text-gray-900 font-medium text-md">Percorso Attuale Studente</div>

                <br>
                <p>{!! $student['trip_string'] !!}</p>
                <br>

                <x-jet-button type="button" class="mt-5" wire:click="$toggle('addingNewTrip')">
                    {{!$addingNewTrip ? 'Aggiungi Viaggio' : 'Annulla Aggiunta'}}
                </x-jet-button>
                <table class="mt-8 table-auto my-table">
                    <thead class="my-header">
                    <tr>
                        <th class="my-th">Mezzo</th>
                        <th class="my-th">Comune di arrivo</th>
                        <th class="my-th">Indirizzo</th>

                        {{--                        <th class="my-th"></th>--}}
                        <th class="my-th"></th>

                    </tr>
                    </thead>
                    <tbody>
                    @foreach($students[$editStudentIndex]['trips'] as $index=>$trip)
                        <tr class="body-tr">
                            @if($editTripIndex === $trip['id'])
                                <td class="my-th">
                                    <x-select
                                        wire:model.defer="students.{{$editStudentIndex}}.trips.{{$index}}.transport_1"
                                        for="students.{{$editStudentIndex}}.trips.{{$index}}.transport_1">
                                        <option selected value="">--------------------</option>
                                        @foreach($this->transports as $transport)
                                            <option
                                                value="{{$transport['id']}}">{{$transport['name']}}</option>
                                        @endforeach
                                    </x-select>
                                </td>
                                <td class="my-th">
                                    <x-select
                                        wire:model.defer="students.{{$editStudentIndex}}.trips.{{$index}}.town_istat"
                                        for="students.{{$editStudentIndex}}.trip3.{{$index}}.town_istat">
                                        <option selected value="{{null}}">{{config('custom.lang.school')}}</option>
                                        @foreach($this->comuni as $key => $comune)
                                            <option
                                                value="{{$key}}">{{$comune}}</option>
                                        @endforeach
                                    </x-select>
                                </td>
                                <td class="my-th">
                                    <x-jet-input
                                        wire:model.defer="students.{{$editStudentIndex}}.trips.{{$index}}.address"></x-jet-input>
                                </td>

                                {{--                                <td class="my-th">--}}
                                {{--                                    <x-select--}}
                                {{--                                        wire:model="students.{{$editStudentIndex}}.trips.{{$index}}.transport_2"--}}
                                {{--                                        for="students.{{$editStudentIndex}}.trips.{{$index}}.transport_2">--}}
                                {{--                                        <option selected value="">--------------------</option>--}}
                                {{--                                        @foreach($this->transports as $transport)--}}
                                {{--                                            <option--}}
                                {{--                                                value="{{$transport['id']}}">{{$transport['name']}}</option>--}}
                                {{--                                        @endforeach--}}
                                {{--                                    </x-select>--}}
                                {{--                                </td>--}}

                                <td class="my-th">
                                    <x-jet-button type="button"
                                                  wire:click.prevent="saveTrip({{$index}})">Salva
                                    </x-jet-button>
                                </td>

                            @else

                                <td class="my-th">
                                    {{$trip['transport_1'] ? $transports[$trip['transport_1']]['name'] : ''}}
                                </td>
                                {{--                                <td class="my-th">--}}
                                {{--                                    {{$trip['transport_2'] ? $transports[$trip['transport_2']]['name'] : ''}}--}}
                                {{--                                </td>--}}
                                <td class="my-th">
                                    {{$trip['town_istat'] ? $this->comuni[$trip['town_istat']] : ''}}
                                </td>
                                <td class="my-th">
                                    {{$trip['address'] ?? ''}}
                                </td>
                                <td class="my-th">
                                    <x-jet-button type="button"
                                                  wire:click.prevent="$set('editTripIndex',{{$trip['id']}})">
                                        Modifica
                                    </x-jet-button>
                                    {{--                                </td>--}}
                                    {{--                                <td class="my-th">--}}
                                    <x-jet-danger-button type="button"
                                                         wire:click.prevent="deleteTrip({{$index}})">
                                        Elimina
                                    </x-jet-danger-button>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                    </tbody>
                    @if($addingNewTrip)

                        <td class="my-th">
                            <x-select
                                wire:model="newTripTransport1"
                                for="newTripTransport1">
                                <option selected value="">--------------------</option>
                                @foreach($this->transports as $transport)
                                    <option
                                        value="{{$transport['id']}}">{{$transport['name']}}</option>
                                @endforeach
                            </x-select>
                        </td>
                        {{--                        <td class="my-th">--}}
                        {{--                            <x-select--}}
                        {{--                                wire:model="newTripTransport2"--}}
                        {{--                                for="newTripTransport1">--}}
                        {{--                                <option selected value="">--------------------</option>--}}
                        {{--                                @foreach($this->transports as $transport)--}}
                        {{--                                    <option--}}
                        {{--                                        value="{{$transport['id']}}">{{$transport['name']}}</option>--}}
                        {{--                                @endforeach--}}
                        {{--                            </x-select>--}}
                        {{--                        </td>--}}
                        <td class="my-th">
                            <x-select
                                wire:model="newTripTownIstat"
                                for="newTripTownIstat">
                                <option value="0">{{config('custom.lang.school')}}</option>
                                @foreach($this->comuni as $key => $comune)
                                    <option
                                        value="{{$key}}">{{$comune}}</option>
                                @endforeach
                            </x-select>
                        </td>
                        <td class="my-th">
                            <x-jet-input :disabled="$newTripTownIstat == 0" wire:model="newTripAddress"></x-jet-input>
                        </td>
                        <td class="my-th">
                            <x-jet-secondary-button wire:click="createTrip">Salva
                            </x-jet-secondary-button>
                        </td>
                    @endif
                </table>

            @endif
            <div class="mt-8 ml-auto">
                {{--                                    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"--}}
                {{--                                            type="submit">{{ $productId ? 'Save Changes' : 'Save' }}--}}
                {{--                                    </button>--}}
                <x-jet-button class="bg-gray-500 text-white font-bold py-2 px-4 rounded"
                              wire:click="closeTransportsModal"
                              type="button"
                              data-dismiss="modal">Chiudi
                </x-jet-button>
            </div>
        </div>
    </div>
</div>
