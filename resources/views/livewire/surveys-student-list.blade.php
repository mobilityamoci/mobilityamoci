<div class=" space-y-8">
    <h3 class="text-3xl font-bold uppercase">I miei sondaggi</h3>
    <h3 class="text-xl">Hai <span
            class="font-bold">{{$this->surveys->count()}}</span> {{$this->surveys->count() === 1 ? 'sondaggio' : 'sondaggi'}}
        a cui rispondere!</h3>


    @foreach($this->surveys as $survey)
        <div class="w-full p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
            <div class="flex items-center justify-between">

                <x-jet-label class="text-xl font-bold uppercase">{{$survey->name}}</x-jet-label>
                <x-jet-button wire:click="answerSurvey({{$survey->id}})">Compila</x-jet-button>
            </div>
        </div>
    @endforeach

</div>
