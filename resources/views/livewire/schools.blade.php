<span class="font-semibold text-3xl m-3 text-gray-800 leading-tight">Scuole</span>
<div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-9  ">
    <table class="my-table text-xl table-auto">
        <thead class="my-header">
        <tr>
            <th class="my-th">Nome</th>
            <th class="my-th">Studenti</th>
            <th class="my-th">Sezioni</th>
            <th class="my-th">Azioni</th>
        </tr>
        </thead>
        <tbody>
        @foreach($schools as $index=>$school)
            <tr class="body-tr">
                <td class="my-th">
                    @if($editSchoolId === $index)
                        <x-jet-input
                            @click.away="$wire.editSchoolId === {{$index}} ? $wire.saveSchool({{$index}}) : null"
                            type="text" wire:model.defer="schools.{{$index}}.name">
                        </x-jet-input>
                    @else
                        <div wire:click="$set('editSchoolId',{{$index}})">{{$school['name']}}</div>
                    @endif

                    @if($errors->has('schools.'.$index.'.name'))
                        <div
                            class="mt-2 text-sm text-red-600">{{$errors->first('schools.'.$index.'.name')}}
                        </div>
                    @endif
                </td>
                <td class="my-th">{{$school['students_count']}}</td>
                <td class="my-th">{{$school['sections_count']}}</td>
                <td class="my-th">
                    @if($editSchoolId == null || $editSchoolId != $index)
                        <x-jet-secondary-button type="button"
                                                wire:click.prevent="$set('editSchoolId',{{$index}})">
                            <i class="fa-solid fa-pen-to-square fa-fw mr-1"></i> Modifica Nome
                        </x-jet-secondary-button>
                        <x-jet-button type="button"
                                      wire:click="$emit('openModal', 'sections', {{json_encode(['selectedSchoolId' => $school['id']])}})"
                        >
                            <i class="fa-solid fa-users-line fa-fw mr-1"></i> Sezioni
                        </x-jet-button>
                        <x-jet-button type="button"
                                      wire:click="$emit('openModal', 'buildings', {{json_encode(['selectedSchoolId' => $school['id']])}})"
                        >
                            <i class="fa-solid fa-school fa-fw mr-1"></i> Sedi
                        </x-jet-button>
                    @else
                        <x-jet-secondary-button type="button" wire:click.prevent="saveSchool({{$index}})">
                            <i class="fa-solid fa-circle-check fa-fw mr-1"></i> Salva
                        </x-jet-secondary-button>
                    @endif
                </td>
            </tr>
        @endforeach

        </tbody>
    </table>
</div>
