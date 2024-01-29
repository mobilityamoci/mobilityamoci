<div class="p-7">
    <x-success-button class="my-2" wire:click.prevent="$toggle('creatingBuilding')">Crea Sede
    </x-success-button>
    <div class="place-items-center grid">
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="my-table">
                <thead class="my-header">
                <tr>
                    <th class="my-th">Nome</th>
                    <th class="my-th">Comune</th>
                    <th class="my-th">Indirizzo</th>
                    <th class="my-th">Azioni</th>
                </tr>
                </thead>
                <tbody>
                @foreach($buildings as $index => $building)
                    <tr class="body-tr">
                        <td class="my-th">
                            @if($editBuildingIndex === $index || $editBuildingField === $index.'.name')
                                <x-jet-input
                                    @click.away="$wire.editBuildingField === '{{$index}}.name' ? $wire.saveBuilding({{$index}}) : null"
                                    type="text" wire:model.defer="buildings.{{$index}}.name"
                                    value="{{$building['name']}}">
                                </x-jet-input>
                            @else
                                <div wire:click="setEditBuildingField({{$index}},'name')">{{$building['name']}}</div>
                            @endif
                        </td>
                        <td class="my-th">
                            @if($editBuildingIndex === $index || $editBuildingField === $index.'.town_istat')
                                <x-select
                                    @click.away="$wire.editBuildingField === '{{$index}}.town_istat' ? $wire.saveBuilding({{$index}}) : null"
                                    wire:model="buildings.{{$index}}.town_istat" for="buildings.{{$index}}.town_istat">
                                    @foreach($this->comuni as $key => $comune)
                                        <option value="{{$key}}">{{$comune}}</option>
                                    @endforeach
                                </x-select>
                            @else
                                <div wire:click="setEditBuildingField({{$index}},'town_istat')"
                                     class="my-th">{{$building['town_istat'] ? $this->comuni[$building['town_istat']] : ''}}
                                </div>
                            @endif

                            @if($errors->has('buildings.'.$index.'.town_istat'))
                                <div
                                    class="mt-2 text-sm text-red-600">{{$errors->first('buildings.'.$index.'.town_istat')}}</div>
                            @endif
                        </td>
                        <td class="my-th">
                            @if($editBuildingIndex === $index || $editBuildingField === $index.'.address')
                                <x-jet-input
                                    @click.away="$wire.editBuildingField === '{{$index}}.address' ? $wire.saveBuilding({{$index}}) : null"
                                    type="text" wire:model.defer="buildings.{{$index}}.address"
                                    value="{{$building['address']}}">
                                </x-jet-input>
                            @else
                                <div
                                    wire:click="setEditBuildingField({{$index}},'address')">{{$building['address']}}</div>
                            @endif
                        </td>
                        <td class="my-th">
                            <x-jet-danger-button wire:click.prevent="deleteBuilding({{$index}})" class="w-32">Elimina
                            </x-jet-danger-button>
                        </td>
                    </tr>
                @endforeach

                @if($creatingBuilding)
                    <tr class="body-tr">
                        <td class="my-th">
                            <x-jet-input
                                type="text" wire:model.defer="newBuildingName">
                            </x-jet-input>
                        </td>
                        <td class="my-th">
                            <x-select label="" wire:model="newBuildingTownIstat"
                                      for="newComuneIstat">
                                <option selected value="">-------------------------------</option>
                                @foreach($this->comuni as $key => $comune)
                                    <option value="{{$key}}">{{$comune}}</option>
                                @endforeach
                            </x-select>
                        </td>
                        <td class="my-th">
                            <x-jet-input
                                type="text" wire:model.defer="newBuildingAddress">
                            </x-jet-input>
                        </td>
                        <td class="my-th">
                            <x-jet-button class="w-32" wire:click.prevent="createBuilding">Crea
                            </x-jet-button>
                        </td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
