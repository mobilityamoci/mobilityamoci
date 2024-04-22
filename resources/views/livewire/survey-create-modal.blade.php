<div class="p-7">
    <h3 class="text-2xl">Crea Sondaggio</h3>

    <div class="my-5 mt-10 mx-auto">
        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nome Sondaggio</label>
        <x-jet-input wire:model.lazy="surveyName" type="name" id="name" class="w-full"/>
    </div>

    <h3 class="text-2xl">Domande</h3>
    @foreach($questions as $key=>$question)
        <div class="grid grid-cols-2 my-5 mx-auto gap-4">
            <div>
                <label for="name"
                       class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Domanda {{$key}}</label>
                <x-jet-input wire:model.lazy="questions.{{$key}}.content" class="w-full"/>
            </div>
            <div>
                <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tipo di
                    risposta</label>
                <x-select wire:model="questions.{{$key}}.type">
                    @foreach($this->questionTypes as $name => $questionType)
                        <option value="{{$questionType}}">{{$name}}</option>
                    @endforeach
                </x-select>
            </div>
        </div>
        @if($question['type'] == 'radio' || $question['type'] == 'multiselect')
            @foreach($question['options'] as $optionKey => $option)

                <div class="grid grid-cols-5 my-5 mx-auto gap-4 content-baseline">
                    <div class="col-span-1"></div>
                    <div class="col-span-3">
                        <label for="name"
                               class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Risposta</label>
                        <x-jet-input id="name" wire:key="option_{{$key}}_{{$optionKey}}" wire:model.lazy="questions.{{$key}}.options.{{$optionKey}}" class="w-full"/>

                    </div>
                    <div class="col-span-1">
                        <x-jet-danger-button wire:key="remove_option_{{$key}}_{{$optionKey}}" wire:click="removeOption({{$key}}, {{$optionKey}})" color="red">Rimuovi
                        </x-jet-danger-button>
                    </div>
                </div>
            @endforeach
            <div class="flex flex-row-reverse my-5">
                <x-jet-button wire:key="add_option_{{$key}}" wire:click="addOption({{$key}})">Aggiungi Risposta
                </x-jet-button>
            </div>
        @endif
        <hr>
    @endforeach
    <div class="my-5">
        <x-success-button wire:key="add_question" wire:click="addQuestion({{$key}})">Aggiungi Domanda</x-success-button>
    </div>

    <hr class="h-2 my-8 bg-gray-200 border-0 dark:bg-gray-700">
    <div class="flex flex-row-reverse my-3 gap-4">
        <x-jet-button wire:key="create_survey" wire:click="createSurvey">Crea Sondaggio</x-jet-button>
        <x-jet-secondary-button wire:key="close_modal" wire:click="$emit('close-modal')">Chiudi</x-jet-secondary-button>

    </div>

</div>
