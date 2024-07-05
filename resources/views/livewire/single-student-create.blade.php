<div>
    <x-loading-spinner/>
    <h3 class="text-center text-5xl">Non hai ancora creato il tuo percorso.</h3>

    @if($possibleStudents->isEmpty())


        <div class="flex items-end justify-between justify-items-end">

    <h3 class="mt-8 text-2xl font-bold uppercase">I miei dati</h3>

            <x-jet-button wire:click="askPossibleStudents" class="h-8">Controlla tra gli studenti già creati</x-jet-button>
        </div>
    <div class="grid my-6 sm:grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <x-jet-label class="text-xl">Nome</x-jet-label>
            <x-jet-input class="w-full" wire:model.lazy="newStudentName"></x-jet-input>
            @if($errors->has('newStudentName'))
                <div class="mt-2 text-sm text-red-600">
                    {{$errors->first('newStudentName')}}
                </div>
            @endif
        </div>
        <div>
            <x-jet-label class="text-xl">Cognome</x-jet-label>
            <x-jet-input class="w-full" wire:model.lazy="newStudentSurname"></x-jet-input>
            @if($errors->has('newStudentSurname'))
                <div class="mt-2 text-sm text-red-600">
                    {{$errors->first('newStudentSurname')}}
                </div>
            @endif
        </div>
        <div>
            <x-jet-label class="text-xl">{{config('custom.lang.section')}}</x-jet-label>
            <x-select class="w-full" wire:model="newStudentSection">
                <option value="">----------------</option>
                @foreach($this->sections as $section)
                    <option value="{{$section->id}}" class="capitalize">{{$section->name}}</option>
                @endforeach
            </x-select>
            @if($errors->has('newStudentSection'))
                <div class="mt-2 text-sm text-red-600">
                    {{$errors->first('newStudentSection')}}
                </div>
            @endif
        </div>
        <div>
            <x-jet-label class="text-xl">Comune di domicilio</x-jet-label>
            <x-select class="w-full" wire:model="newStudentIstat">
                <option value="">----------------</option>
                @foreach($this->comuni as $istat => $comune)
                    <option value="{{$istat}}">{{$comune}}</option>
                @endforeach
            </x-select>
            @if($errors->has('newStudentIstat'))
                <div class="mt-2 text-sm text-red-600">
                    {{$errors->first('newStudentIstat')}}
                </div>
            @endif
        </div>
        <div class="md:col-span-2">
            <x-jet-label class="text-xl">Indirizzo</x-jet-label>
            <x-jet-input class="w-full" wire:model.lazy="newStudentAddress"></x-jet-input>
            @if($errors->has('newStudentAddress'))
                <div class="mt-2 text-sm text-red-600">
                    {{$errors->first('newStudentAddress')}}
                </div>
            @endif
        </div>
    </div>

        <div class="grid justify-items-end">
            <x-success-button wire:click.prevent="createStudent">Salva Dati</x-success-button>
        </div>
    @endif

</div>

@push('scripts')

    <script>
        $(document).ready(function () {
            console.log('ciao')
            Livewire.emit('chiediAssociazione')
        });
    </script>
@endpush
