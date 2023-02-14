<div class="">
    <x-jet-button class="my-9" wire:click.prevent="startCreatingSection()"
                  color="green">Crea Sezione
    </x-jet-button>
    <div class="place-items-center grid">
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-9 w-1/4 ">
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
                            <div class="grid grid-cols-3 gap-4">
                                @if($editSectionIndex === $index)
                                    <x-jet-input
                                        @click.away="$wire.editSectionIndex === {{$index}} ? $wire.saveSection({{$index}}) : null"
                                        type="text" wire:model.defer="sections.{{$index}}.name"
                                        value="{{$section['name']}}">
                                    </x-jet-input>
                                @else
                                    <div wire:click="$set('editSectionIndex',{{$index}})">{{$section['name']}}</div>
                            @endif
                        </td>
                        <td class="my-th">
                            <x-jet-danger-button wire:click.prevent="deleteSection({{$index}})" class="w-32">Elimina
                            </x-jet-danger-button>
                        </td>
                    </tr>
                @endforeach

                @if($creatingSection)
                    <li class="my-4">
                        <div class="grid grid-cols-3 gap-4">
                            <x-jet-input
                                type="text" wire:model.defer="newSectionName">
                            </x-jet-input>
                            <div></div>
                            <x-jet-button class="w-32" wire:click.prevent="createSection">Crea
                            </x-jet-button>
                        </div>
                    </li>
                @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
