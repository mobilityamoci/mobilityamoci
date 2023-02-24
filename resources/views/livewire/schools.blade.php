<div class="m-10">
    @if(!$editingSections)
        <div class="place-items-center grid">
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-9  ">
                <table class="my-table text-xl">
                    <thead class="my-header">
                    <tr>
                        <th class="my-th">Nome</th>
                        <th class="my-th">Studenti</th>
                        <th class="my-th">Sezioni</th>
                        <th class="my-th">Azioni</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($schools as $school)
                        <tr class="body-tr">
                            <td class="my-th">
                                @if($editSchoolId == $school->id)
                                    <x-jet-input
                                        type="text" wire:model.defer="editingSchoolName"
                                        value="{{$school->name}}">
                                    </x-jet-input>
                                @else
                                    <div wire:click="$set('editSchoolId',{{$school->id}})">{{$school->name}}</div>
                                @endif

                            </td>
                            <td class="my-th">{{$school->students_count}}</td>
                            <td class="my-th">{{$school->sections_count}}</td>
                            <td class="my-th">
                                @if($editSchoolId == null)
                                    <x-jet-secondary-button type="button"
                                                            wire:click.prevent="$set('editSchoolId',{{$school->id}})">
                                        Modifica Nome
                                    </x-jet-secondary-button>
                                    <x-jet-button type="button"
                                                  wire:click.prevent="handleSections({{$school->id}})"
                                    >
                                        Gestisci Sezioni
                                    </x-jet-button>
                                @else
                                    <x-jet-secondary-button type="button" wire:click.prevent="saveSchool">
                                        Salva
                                    </x-jet-secondary-button>
                                @endif
                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    @else

        <x-jet-secondary-button class="mt-9" wire:click.prevent="$toggle('editingSections')">
            <x-back-icon></x-back-icon>
            Torna agli Studenti
        </x-jet-secondary-button>
        <livewire:sections wire:key="{{ now() }}" :selected-school-id="$editSchoolId"></livewire:sections>

    @endif
</div>
