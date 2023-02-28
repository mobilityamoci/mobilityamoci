<div x-data="{}">
    <div class="m-10">
        <x-select wire:change="schoolChanged" class="col-auto" wire:model="selectedSchoolId" label="Seleziona Scuola"
                  for="school">
            @foreach($schools as $school)
                <option @selected($selectedSchoolId == $school->id) value="{{$school->id}}">{{$school->name}}</option>
            @endforeach
        </x-select>

        <x-select wire:change="sectionChanged" class="col-auto" wire:model="selectedSectionId" label="Seleziona Sezione"
                  for="section">
            @foreach($this->sections as $section)
                <option @selected($selectedSectionId == $section->id) value="{{$section->id}}">{{$section->name}}</option>
            @endforeach
        </x-select>

            <x-jet-button type="button" class="mt-9" wire:click.prevent="fetchResults()">Aggiungi Studente
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
                                                <option value="{{$section->id}}">{{$section->name}}</option>
                                            @endforeach
                                        </x-select>
                                    @else
                                        <div wire:click="setEditStudentField({{$index}},'section_id')"
                                             class="my-th">{{$this->sections[$student['section_id']]['name']}}
                                        </div>
                                    @endif

                                    @if($errors->has('students.'.$index.'.section_id'))
                                        <div class="mt-2 text-sm text-red-600">
                                            {{$errors->first('students.'.$index.'.section_id')}}
                                        </div>
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
                @include('components.trips-modal')

            </div>


    </div>


</div>


