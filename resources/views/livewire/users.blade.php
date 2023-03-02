<div class="p-7">
    <div class="place-items-center grid">
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="my-table ">
                <thead class="my-header">
                <tr>
                    <th class="my-th">Nome</th>
                    <th class="my-th">Cognome</th>
                    <th class="my-th">Email</th>
                    <th class="my-th">Azioni</th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $index => $user)
                    <tr class="body-tr">
                        <td class="my-th">
                            @if($editUserIndex === $index || $editUserField === $index.'.name')
                                <x-jet-input
                                    @click.away="$wire.editUserField === '{{$index}}.name' ? $wire.saveUser({{$index}}) : null"
                                    type="text" wire:model.defer="users.{{$index}}.name"
                                    value="{{$user['name']}}">
                                </x-jet-input>
                            @else
                                <div wire:click="$set('editUserField','{{$index}}.name')">{{$user['name']}}</div>
                            @endif
                        </td>
                        <td class="my-th">
                            @if($editUserIndex === $index || $editUserField === $index.'.surname')
                                <x-jet-input
                                    @click.away="$wire.editUserField === '{{$index}}.surname' ? $wire.saveUser({{$index}}) : null"
                                    type="text" wire:model.defer="users.{{$index}}.surname"
                                    value="{{$user['surname']}}">
                                </x-jet-input>
                            @else
                                <div wire:click="$set('editUserField','{{$index}}.surname')">{{$user['surname']}}</div>
                            @endif
                        </td>
                        <td class="my-th">
                                <div>{{$user['email']}}</div>
                        </td>
                        <td class="my-th">
                            <x-jet-danger-button wire:click.prevent="deleteUser({{$index}})">Elimina
                            </x-jet-danger-button>
                            <x-jet-button
                                wire:click="$emit('openModal', 'permissions-user', {{json_encode(['selectedUserId' => $user['id']])}})"
                            >
                                Gestisci Ruoli
                            </x-jet-button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
