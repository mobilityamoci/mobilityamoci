<div class="p-7">
    <div class="justify-between">

    <x-success-button class="my-2" wire:click.prevent="startCreatingSection()">Crea {{config('custom.lang.section')}}
    </x-success-button>

        <x-jet-button type="button" class="mt-9"
                      wire:click="$emit('openModal', 'modals.sections-school-import-modal', {{json_encode(['selectedSchoolId' => $selectedSchoolId])}})"
        >
            <i class="fa-solid fa-fw fa-file-excel mr-2"></i> Carica Excel {{config('custom.lang.section')}}
        </x-jet-button>

    </div>
    <div class="place-items-center grid mt-4">
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="my-table ">
                <thead class="my-header">
                <tr>
                    <th class="my-th">Nome</th>
                    <th class="my-th">Azioni</th>
                </tr>
                </thead>
                <tbody>
                @foreach($sections as $index => $section)
                    <tr class="body-tr">
                        <td class="my-th">
                            @if($editSectionIndex === $index || $editSectionField === $index.'.name')
                                <x-jet-input
                                    @click.away="$wire.editSectionField === '{{$index}}.name' ? $wire.saveSection({{$index}}) : null"
                                    type="text" wire:model.defer="sections.{{$index}}.name"
                                    value="{{$section['name']}}">
                                </x-jet-input>
                            @else
                                <div wire:click="$set('editSectionField','{{$index}}.name')">{{$section['name']}}</div>
                            @endif
                        </td>
                        <td class="my-th">
                            @if($editSectionIndex === $index || $editSectionField === $index.'.building_id')
                                <x-select
                                    @click.away="$wire.editSectionField === '{{$index}}.building_id' ? $wire.saveSection({{$index}}) : null"
                                    wire:model="sections.{{$index}}.building_id" for="sections.{{$index}}.building_id">
                                    @foreach($this->buildings as $building_index=>$building)
                                        <option value="{{$building_index}}">{{$building['name']}}</option>
                                    @endforeach
                                </x-select>
                            @else
                                <div wire:click="$set('editSectionField','{{$index}}.building_id')">{{$this->buildings[$section['building_id']]['name']}}</div>
                            @endif
                        </td>
                        <td class="my-th">
                            <x-jet-danger-button wire:click.prevent="deleteSection({{$index}})" class="w-32">Elimina
                            </x-jet-danger-button>
                        </td>
                    </tr>
                @endforeach

                @if($creatingSection)
                    <tr class="body-tr">
                        <td class="my-th">
                            <x-jet-input
                                type="text" wire:model.defer="newSectionName">
                            </x-jet-input>
                            @if($errors->has('newSectionName'))
                                <div class="mt-2 text-sm text-red-600">
                                    {{$errors->first('newSectionName')}}
                                </div>
                            @endif
                        </td>
                        <td class="my-th">
                            <x-select
                                type="text" wire:model.defer="newBuildingId" for="newBuildingId">
                                <option value="">-------</option>
                                @foreach($this->buildings as $building_index=>$building)
                                    <option value="{{$building_index}}">{{$building['name']}}</option>
                                @endforeach
                            </x-select>
                            @if($errors->has('newBuildingId'))
                                <div class="mt-2 text-sm text-red-600">
                                    {{$errors->first('newBuildingId')}}
                                </div>
                            @endif
                        </td>
                        <td class="my-th">
                            <div></div>
                            <x-jet-button class="w-32" wire:click.prevent="createSection">Crea
                            </x-jet-button>
                        </td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
