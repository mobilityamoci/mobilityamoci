@props(['onlyView' => false])
<div class="card">
    <div class="card-header bg-white p-4">
        <h1 class="text-xl font-bold">{{ $survey->name }}</h1>

        @if(!$eligible)
            Accettiamo solo
            <strong>{{ $survey->limitPerParticipant() }} {{ \Str::plural('entry', $survey->limitPerParticipant()) }}</strong>
            risposte per utente.
        @endif

        @if($lastEntry)
            Hai risposto <strong>{{ $lastEntry->created_at->diffForHumans() }}</strong>.
        @endif

    </div>
    <form method="POST" action="{{ route('sondaggio.submit', $survey) }}">
        @csrf
        @if(!$survey->acceptsGuestEntries() && auth()->guest())
            <div class="p-5">
                Fai login per visualizzare e rispondere a questo sondaggio.
            </div>
        @else
            @foreach($survey->sections as $section)
                @include('survey::sections.single')
            @endforeach

            @foreach($survey->questions()->withoutSection()->get() as $question)
                @include('survey::questions.single')
            @endforeach

            @if($eligible && !$onlyView)
                <div class="flex flex-row-reverse my-3 gap-4">
                    <x-jet-button class="mt-5">Invia risposte</x-jet-button>
                </div>
            @endif
        @endif
    </form>
</div>
