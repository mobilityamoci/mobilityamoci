<div class="p-7">
    {{--    @foreach($userRoles as $role)--}}
    {{--        <p>{{$role->name}}</p>--}}
    {{--    @endforeach--}}
    <h3 class="text-lg">Ruoli</h3>
    <x-success-button class="mt-3" wire:click.prevent="$toggle('addingNewRole')">Aggiungi Ruolo</x-success-button>

    <table class="my-table table-auto mt-4">
        <thead class="my-header">
        <tr>
            <th class="my-th">Ruolo</th>
            <th class="my-th">Azioni</th>
        </tr>
        </thead>
        <tbody>

        @foreach($this->userRoles as $role)
            <tr class="body-tr">
                <td class="my-th">
                    {{$role->name}}
                </td>
                <td>
                    <x-jet-danger-button wire:click.prevent="removeRole('{{$role->name}}')">Rimuovi
                    </x-jet-danger-button>
                </td>
            </tr>
        @endforeach
        @if($addingNewRole)
            <tr class="body-tr">
                <td class="my-th">
                    <x-select wire:model="newRole">
                        @foreach($allRoles as $role)
                            <option value="{{$role->name}}">{{$role->name}}</option>
                        @endforeach
                    </x-select>
                </td>
                <td>
                    <x-success-button wire:click.prevent="saveNewRole()">Aggiungi</x-success-button>
                </td>
            </tr>
        @endif
        </tbody>
    </table>


    @if($this->userIsInsegnante || $this->userIsMMScolastico)
        <h3 class="text-lg mt-8">{{config('custom.lang.school')}}</h3>
        @if($this->allSchools->isNotEmpty())
            <x-success-button class="mt-3" wire:click.prevent="$toggle('addingNewSchool')">Associa Nuova {{config('custom.lang.school')}}
            </x-success-button>
        @endif
        <table class="my-table table-auto mt-4">
            <thead class="my-header">
            <tr>
                <th class="my-th">{{config('custom.lang.school')}}</th>
                <th class="my-th">Azioni</th>
            </tr>
            </thead>
            <tbody>
            @foreach($this->userSchools as $school)
                <tr class="body-tr">
                    <td class="my-th">
                        {{$school->name}}
                    </td>
                    <td>
                        <x-jet-danger-button wire:click.prevent="removeSchool('{{$school->id}}')">Rimuovi
                        </x-jet-danger-button>
                    </td>
                </tr>
            @endforeach
            @if($addingNewSchool)
                <tr class="body-tr">
                    <td class="my-th">
                        <x-select wire:model="newSchoolId">
                            <option selected value="">--------------</option>
                            @foreach($this->allSchools as $school)
                                <option value="{{$school->id}}">{{$school->name}}</option>
                            @endforeach
                        </x-select>
                    </td>
                    <td>
                        <x-success-button wire:click.prevent="saveNewSchool()">Associa
                        </x-success-button>
                    </td>
                </tr>
            @endif
            </tbody>
        </table>

    @endif
    @if($this->userIsInsegnante && !$this->userIsMMScolastico)
        <h3 class="text-lg mt-8">{{config('custom.lang.section')}}</h3>
    @if($this->allSections->isNotEmpty())
        <x-success-button class="mt-3" wire:click.prevent="$toggle('addingNewSection')">Associa Nuova {{config('custom.lang.section')}}</x-success-button>
    @endif
        <table class="my-table table-auto mt-4">
            <thead class="my-header">
            <tr>
                <th class="my-th">{{config('custom.lang.section')}}</th>
                <th class="my-th">Azioni</th>
            </tr>
            </thead>
            <tbody>
            @foreach($this->userSections as $section)
                <tr class="body-tr">
                    <td class="my-th">
                        {{$section->fullName()}}
                    </td>
                    <td class="my-th">
                        <x-jet-danger-button wire:click.prevent="removeSection('{{$section->id}}')">Rimuovi
                        </x-jet-danger-button>
                    </td>
                </tr>
            @endforeach
            @if($addingNewSection)
                <tr class="body-tr">
                    <td class="my-th">
                        <x-select wire:model="newSectionId">
                            <option selected value="">--------------</option>
                            @foreach($this->allSections as $section)
                                <option value="{{$section->id}}">{{$section->fullName()}}</option>
                            @endforeach
                        </x-select>
                    </td>
                    <td>
                        <x-success-button wire:click.prevent="saveNewSection()">Associa
                        </x-success-button>
                    </td>
                </tr>
            @endif
            </tbody>
        </table>

    @endif

</div>
