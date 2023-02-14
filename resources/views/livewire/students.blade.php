<div x-data="{}">
    <div class="m-10">
        <x-select wire:change="schoolChanged" class="col-auto" wire:model="selectedSchoolId" label="Seleziona Scuola"
                  for="school">
            @foreach($schools as $school)
                <option @selected($selectedSchoolId == $school->id) value="{{$school->id}}">{{$school->name}}</option>
            @endforeach
        </x-select>


        @if(!$editSections)

            <x-jet-button type="button" class="mt-9" wire:click.prevent="startCreatingStudent()">Aggiungi Studente
            </x-jet-button>

            <x-jet-button type="button" class="mt-9" wire:click.prevent="$toggle('editSections')">Aggiungi Sezione
            </x-jet-button>

            <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-9">
                {{$errors->first()}}
                <table class="my-table table-auto">
                    <thead class="my-header">
                    <tr>
                        <th class="my-th">Sezione</th>
                        <th class="my-th">Comune Domicilio</th>
                        <th class="my-th">Indirizzo</th>
                        <th class="my-th md:hidden lg:block">Percorso</th>
                        <th class="my-th">Azioni</th>

                    </tr>
                    </thead>
                    <tbody>
                    @if($students)
                        @foreach($students as $index => $student)
                            <tr class="body-tr" wire:key="{{$index}}">
                                <td class="my-th">
                                    @if($editStudentIndex === $index || $editStudentField === $index.'.section_id')
                                        <x-select
                                            @click.away="$wire.editStudentField === '{{$index}}.section_id' ? $wire.saveStudent({{$index}}) : null"
                                            label="" wire:model="students.{{$index}}.section_id"
                                            for="students.{{$index}}.section_id">
                                            @foreach($this->sections as $section)
                                                <option value="{{$section['id']}}">{{$section['name']}}</option>
                                            @endforeach
                                        </x-select>
                                    @else
                                        <div wire:click="setEditStudentField({{$index}},'section_id')"
                                             class="my-th">{{$this->sections[$student['section_id']]['name']}}
                                        </div>
                                    @endif

                                    @if($errors->has('students.'.$index.'.section_id'))
                                        <div
                                            class="mt-2 text-sm text-red-600">{{$errors->first('students.'.$index.'.section_id')}}</div>
                                    @endif
                                </td>
                                <td class="my-th">
                                    @if($editStudentIndex === $index || $editStudentField === $index.'.town_istat')
                                        <x-select
                                            @click.away="$wire.editStudentField === '{{$index}}.town_istat' ? $wire.saveStudent({{$index}}) : null"
                                            label="" wire:model="students.{{$index}}.town_istat"
                                            for="students.{{$index}}.town_istat">
                                            @foreach($this->comuni as $comune)
                                                <option value="{{$comune['istat']}}">{{$comune['comune']}}</option>
                                            @endforeach
                                        </x-select>
                                    @else
                                        <div wire:click="setEditStudentField({{$index}},'town_istat')"
                                             class="my-th">{{$student['town_istat'] ? $this->comuni[$student['town_istat']]['comune'] : ''}}
                                        </div>
                                    @endif

                                    @if($errors->has('students.'.$index.'.town_istat'))
                                        <div
                                            class="mt-2 text-sm text-red-600">{{$errors->first('students.'.$index.'.town_istat')}}</div>
                                    @endif
                                </td>

                                <td class="my-th">
                                    @if($editStudentIndex === $index || $editStudentField === $index.'.address')
                                        <input
                                            @click.away="$wire.editStudentField === '{{$index}}.address' ? $wire.saveStudent({{$index}}) : null"
                                            type="text" wire:model.defer="students.{{$index}}.address"
                                            class="bg-gray-50 text-sm border border-gray-300 text-gray-900 rounded-md focus:ring-blue-500 focus:border-blue-500 block  p-2.5">

                                    @else
                                        <div wire:click="setEditStudentField({{$index}},'address')"
                                             class="my-th">{{$student['address']}}
                                        </div>
                                    @endif

                                    @if($errors->has('students.'.$index.'.address'))
                                        <div
                                            class="mt-2 text-sm text-red-600">{{$errors->first('students.'.$index.'.address')}}</div>
                                    @endif
                                </td>
                                <td class="my-th md:hidden lg:block">
                                    <div wire:click.prevent="openTransportsModal({{$index}})">
                                        {!!  $student['trip_string']!!}
                                    </div>
                                </td>
                                <td class="my-th ">
                                    <div class="flex flex-wrap">
                                        <x-jet-button class="m-1" wire:click.prevent="openTransportsModal({{$index}})">Modifica
                                            Percorso
                                        </x-jet-button>
                                        <x-jet-danger-button class="m-1" wire:click.prevent="deleteStudent({{$index}})">Elimina
                                            Studente
                                        </x-jet-danger-button>
                                    </div>
                                </td>


                                {{--                    <td class="my-th">--}}
                                {{--                        <x-jet-button label="Salva" wire:click.prevent="saveStudent({{$index}})"--}}
                                {{--                                  color="green"></x-jet-button>--}}
                                {{--                    </td>--}}
                            </tr>

                        @endforeach
                    @endif


                    @if($addingNewStudent)
                        <tr class="body-tr">
                            <td class="my-th">
                                <x-select label="" wire:model="newSectionId"
                                          for="newSectionId">
                                    <option selected value="">-------------------------------</option>
                                    @foreach($this->sections as $section)
                                        <option
                                            @selected($loop->first) value="{{$section['id']}}">{{$section['name']}}</option>
                                    @endforeach
                                </x-select>
                                @if($errors->has('newSectionId'))
                                    <div
                                        class="mt-2 text-sm text-red-600">{{$errors->first('newSectionId')}}</div>
                                @endif
                            </td>
                            <td class="my-th">
                                <x-select label="" wire:model="newComuneIstat"
                                          for="newComuneIstat">
                                    <option selected value="">-------------------------------</option>
                                    @foreach($this->comuni as $comune)
                                        <option value="{{$comune['istat']}}">{{$comune['comune']}}</option>
                                    @endforeach
                                </x-select>
                                @if($errors->has('newComuneIstat'))
                                    <div
                                        class="mt-2 text-sm text-red-600 ">{{$errors->first('newComuneIstat')}}</div>
                                @endif
                            </td>
                            <td class="my-th">
                                <label for="newIndirizzo" hidden></label>
                                <input type="text" wire:model.defer="newIndirizzo" id="newIndirizzo"
                                       class="bg-gray-50 text-sm border border-gray-300 text-gray-900 rounded-md focus:ring-blue-500 focus:border-blue-500 block  p-2.5 ">

                                @if($errors->has('newIndirizzo'))
                                    <div
                                        class="mt-2 text-sm text-red-600 ">{{$errors->first('newIndirizzo')}}</div>
                                @endif
                            </td>
                            <td class="my-th">
                                <x-jet-button wire:click="createStudent" color="green">Salva</x-jet-button>
                            </td>
                        </tr>
                    @endif
                    </tbody>
                </table>

                <div
                    x-on:keydown.escape.window="$wire.closeTransportsModal"
                    class="@if (!$showTransportsModal) hidden @endif flex items-center justify-center fixed left-0 bottom-0 w-full h-full bg-gray-800 bg-opacity-90">
                    <div class="bg-white rounded-lg w-3/4">
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
                                <x-jet-button type="button" class="mt-5" wire:click="$toggle('addingNewTrip')">
                                    {{!$addingNewTrip ? 'Aggiungi Viaggio' : 'Annulla Aggiunta'}}
                                </x-jet-button>
                                <table class="mt-8 my-table">
                                    <thead class="my-header">
                                    <tr>
                                        <th class="my-th">1° Mezzo</th>
                                        <th class="my-th">2° Mezzo</th>
                                        <th class="my-th">Comune di arrivo</th>

                                        <th class="my-th"></th>
                                        <th class="my-th"></th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($students[$editStudentIndex]['trips'] as $index=>$trip)
                                        <tr class="body-tr">
                                            @if($editTripIndex === $trip['id'])
                                                <td class="my-th">
                                                    <x-select
                                                        wire:model="students.{{$editStudentIndex}}.trips.{{$index}}.transport_1"
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
                                                        wire:model="students.{{$editStudentIndex}}.trips.{{$index}}.transport_2"
                                                        for="students.{{$editStudentIndex}}.trips.{{$index}}.transport_2">
                                                        <option selected value="">--------------------</option>
                                                        @foreach($this->transports as $transport)
                                                            <option
                                                                value="{{$transport['id']}}">{{$transport['name']}}</option>
                                                        @endforeach
                                                    </x-select>
                                                </td>
                                                <td class="my-th">
                                                    <x-select
                                                        wire:model="students.{{$editStudentIndex}}.trips.{{$index}}.town_istat"
                                                        for="students.{{$editStudentIndex}}.trip3.{{$index}}.town_istat">
                                                        <option selected value="{{null}}">--------------------</option>
                                                        @foreach($this->comuni as $comune)
                                                            <option
                                                                value="{{$comune['istat']}}">{{$comune['comune']}}</option>
                                                        @endforeach
                                                    </x-select>
                                                </td>
                                                <td class="my-th">
                                                    <x-jet-button type="button"
                                                                  wire:click.prevent="saveTrip({{$index}})">Salva
                                                    </x-jet-button>
                                                </td>

                                            @else

                                                <td class="my-th">
                                                    {{$trip['transport_1'] ? $transports[$trip['transport_1']]['name'] : ''}}
                                                </td>
                                                <td class="my-th">
                                                    {{$trip['transport_2'] ? $transports[$trip['transport_2']]['name'] : ''}}
                                                </td>
                                                <td class="my-th">
                                                    {{$trip['town_istat'] ? $this->comuni[$trip['town_istat']]['comune'] : ''}}
                                                </td>
                                                <td class="my-th">
                                                    <x-jet-button type="button"
                                                                  wire:click.prevent="$set('editTripIndex',{{$trip['id']}})">
                                                        Modifica
                                                    </x-jet-button>
                                                </td>
                                                <td class="my-th">
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
                                                wire:model="newTripTownIstat"
                                                for="newTripTownIstat">
                                                <option selected value="">--------------------</option>
                                                @foreach($this->comuni as $comune)
                                                    <option
                                                        value="{{$comune['istat']}}">{{$comune['comune']}}</option>
                                                @endforeach
                                            </x-select>
                                        </td>
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
                                        <td class="my-th">
                                            <x-select
                                                wire:model="newTripTransport2"
                                                for="newTripTransport1">
                                                <option selected value="">--------------------</option>
                                                @foreach($this->transports as $transport)
                                                    <option
                                                        value="{{$transport['id']}}">{{$transport['name']}}</option>
                                                @endforeach
                                            </x-select>
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

            </div>

        @else

            <x-jet-secondary-button class="mt-9" wire:click.prevent="$toggle('editSections')">
                <x-back-icon></x-back-icon>
                Torna agli Studenti
            </x-jet-secondary-button>
            <livewire:sections wire:key="{{ now() }}" :selected-school-id="$selectedSchoolId"></livewire:sections>
        @endif


    </div>


</div>


