<div x-data="{}">
    <div style="width: 100%; height: 100vh">
        <x-jet-label for="school">Seleziona la scuola:</x-jet-label>
        <x-select wire:change="schoolChanged" class="col-auto" wire:model="selectedSchoolId" label="Seleziona Scuola"
                  id="school">
            @foreach($schools as $school)
                <option @selected($selectedSchoolId == $school->id) value="{{$school->id}}">{{$school->name}}</option>
            @endforeach
        </x-select>

        <x-jet-label class="mt-4" for="section">Seleziona la sezione:</x-jet-label>
        <x-select wire:change="sectionChanged" class="col-auto" wire:model="selectedSectionId" label="Seleziona Sezione"
                  id="section">
            @foreach($this->sections as $section)
                <option
                    @selected($selectedSectionId == $section->id) value="{{$section->id}}">{{$section->name}}</option>
            @endforeach
        </x-select>

        <div class="flex justify-between">
            <div class="flex items-center">
                <x-jet-button type="button" class="mt-9" wire:click.prevent="$toggle('addingNewStudent')">
                    <i class="fa-solid fa-fw fa-user-plus mr-2"></i> Aggiungi Studente
                </x-jet-button>
            </div>

            <div class="flex items-center">
                <x-jet-button type="button" class="mt-9"
                              wire:click="$emit('openModal', 'upload-students-import',{{json_encode(['selectedSectionId' => $selectedSectionId])}})">
                    <i class="fa-solid fa-fw fa-file-excel mr-2"></i> Importa Excel
                </x-jet-button>

            </div>

        </div>


        <div class="relative table-wrp block overflow-y-auto shadow-md sm:rounded-lg mt-9" style="max-height: 70%">
            {{$errors->first()}}
            <table class="my-table table-auto">
                <thead class="my-header">
                <tr>
                    @hasanyrole($this->canSeeNamesRoles)
                    <th class="my-th">Nome</th>
                    <th class="my-th">Cognome</th>
                    @endhasanyrole
                    <th class="my-th">Sezione</th>
                    <th class="my-th">Comune Domicilio</th>
                    <th class="my-th">Indirizzo</th>
                    <th class="my-th hidden xl:block">Percorso</th>
                    <th class="my-th">Azioni</th>

                </tr>
                </thead>
                <tbody>
                @if($this->students)
                    @foreach($this->students as $index => $student)
                        <tr @class([
                                        'bg-red-200' => is_null($student['geom_address']),
                                        'bg-white' => !is_null($student['geom_address']),

                                        'font-bold' => is_null($student['geom_address']),])
                            wire:key="{{$index}}">
                            @hasanyrole($this->canSeeNamesRoles)
                            <td class="my-th">
                                @if($editStudentIndex === $index || $editStudentField === $index.'.name')
                                    <input
                                        @click.away="$wire.editStudentField === '{{$index}}.name' ? $wire.saveStudent({{$index}}) : null"
                                        for="students.{{$index}}.name" wire:model.defer="students.{{$index}}.name"
                                        class="bg-gray-50 text-sm border border-gray-300 text-gray-900 rounded-md focus:ring-blue-500 focus:border-blue-500 block  p-2.5">

                                @else
                                    <div wire:click="setEditStudentField({{$index}},'name')"
                                         class="my-th">{{$student['name']}}
                                    </div>
                                @endif

                                @if($errors->has('students.'.$index.'.name'))
                                    <div class="mt-2 text-sm text-red-600">
                                        {{$errors->first('students.'.$index.'.name')}}
                                    </div>
                                @endif
                            </td>
                            <td class="my-th">
                                @if($editStudentIndex === $index || $editStudentField === $index.'.surname')
                                    <input
                                        @click.away="$wire.editStudentField === '{{$index}}.surname' ? $wire.saveStudent({{$index}}) : null"
                                        for="students.{{$index}}.surname"
                                        wire:model.defer="students.{{$index}}.surname"
                                        class="bg-gray-50 text-sm border border-gray-300 text-gray-900 rounded-md focus:ring-blue-500 focus:border-blue-500 block  p-2.5">

                                @else
                                    <div wire:click="setEditStudentField({{$index}},'surname')"
                                         class="my-th">{{$student['surname']}}
                                    </div>
                                @endif

                                @if($errors->has('students.'.$index.'.surname'))
                                    <div class="mt-2 text-sm text-red-600">
                                        {{$errors->first('students.'.$index.'.surname')}}
                                    </div>
                                @endif
                            </td>
                            @endhasanyrole
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
                                        class="bg-gray-50 text-sm border border-gray-300 text-gray-900 rounded-md focus:ring-blue-500 focus:border-blue-500 block p-2.5">

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
                            <td class="my-th hidden xl:block">
                                <div wire:click.prevent="openTransportsModal({{$index}})">
                                    {!!  $student['trip_string']!!}
                                </div>
                            </td>
                            <td class="my-th ">
                                <div class="flex flex-wrap">
                                    <x-jet-button class="m-1" wire:click.prevent="openTransportsModal({{$index}})">
                                        Modifica
                                        Percorso
                                    </x-jet-button>
                                    <x-jet-danger-button class="m-1" wire:click.prevent="deleteStudent({{$index}})">
                                        Elimina
                                    </x-jet-danger-button>
                                </div>
                            </td>

                        </tr>

                    @endforeach
                @endif


                @if($addingNewStudent)
                    <tr class="body-tr">
                        @hasanyrole($this->canSeeNamesRoles)
                        <td class="my-th">
                            <label for="newName" hidden></label>
                            <input type="text" wire:model.defer="newName" id="newName"
                                   class="bg-gray-50 text-sm border border-gray-300 text-gray-900 rounded-md focus:ring-blue-500 focus:border-blue-500 block  p-2.5 ">

                            @if($errors->has('newName'))
                                <div
                                    class="mt-2 text-sm text-red-600 ">{{$errors->first('newName')}}</div>
                            @endif
                        </td>
                        <td class="my-th">
                            <label for="newSurname" hidden></label>
                            <input type="text" wire:model.defer="newSurname" id="newSurname"
                                   class="bg-gray-50 text-sm border border-gray-300 text-gray-900 rounded-md focus:ring-blue-500 focus:border-blue-500 block  p-2.5 ">

                            @if($errors->has('newSurname'))
                                <div
                                    class="mt-2 text-sm text-red-600 ">{{$errors->first('newSurname')}}</div>
                            @endif
                        </td>


                        @endhasanyrole
                        <td class="my-th">
                            <x-select label="" wire:model="newSectionId"
                                      for="newSectionId">
                                <option value="">-------------------------------</option>
                                @foreach($this->sections as $section)
                                    <option
                                        value="{{$section['id']}}">{{$section['name']}}</option>
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
                        <td></td>
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




