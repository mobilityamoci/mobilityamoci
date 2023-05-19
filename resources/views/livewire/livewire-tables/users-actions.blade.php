<div class="inline-flex rounded-md shadow-sm gap-4">
{{--    @include('datatables::delete', ['value' => $name, 'id' => $id])--}}
    <x-jet-button
        wire:click="$emit('openModal', 'permissions-user', {{json_encode(['selectedUserId' => $id])}})"
    >
        Gestisci Ruoli
    </x-jet-button>
    <x-jet-danger-button wire:click="deleteUser({{$id}})">
        Elimina
    </x-jet-danger-button>
</div>

