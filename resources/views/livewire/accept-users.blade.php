<div>
    <div class="m-10">
        <x-jet-label for="school">Seleziona la scuola:</x-jet-label>
        <x-select class="col-auto" wire:model="selectedSchoolId" label="Seleziona Scuola"
                  id="school">
            @foreach($schools as $school)
                <option @selected($selectedSchoolId == $school->id) value="{{$school->id}}">{{$school->name}}</option>
            @endforeach
        </x-select>


        <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-9">
            {{$errors->first()}}
            <table class="my-table table-auto">
                <thead class="my-header">
                <tr>
                    <th class="my-th">Nome</th>
                    <th class="my-th">Cognome</th>
                    <th class="my-th">Email</th>
                    <th class="my-th">Si iscrive come:</th>
                    <th class="my-th">Azioni</th>
                </tr>
                </thead>
                <tbody>
                @foreach($this->usersToAccept as $user)
                    <tr class="body-tr">
                        <th class="my-th">{{$user->name}}</th>
                        <th class="my-th">{{$user->surname}}</th>
                        <th class="my-th">{{$user->email}}</th>
                        <th class="my-th">{{$user->firstRoleString()}}</th>
                        <th class="my-th">
                            <x-success-button wire:click.prevent="acceptUser({{$user->id}})">Accetta Utente</x-success-button>
                        </th>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
