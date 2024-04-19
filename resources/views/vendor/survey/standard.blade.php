<div class="card">
    <div class="card-header bg-white p-4">
        <h1 class="text-xl font-bold">{{ $survey->name }}</h1>

        @if(!$eligible)
            We only accept
            <strong>{{ $survey->limitPerParticipant() }} {{ \Str::plural('entry', $survey->limitPerParticipant()) }}</strong>
            per participant.
        @endif

        @if($lastEntry)
            You last submitted your answers <strong>{{ $lastEntry->created_at->diffForHumans() }}</strong>.
        @endif

    </div>
    @if(!$survey->acceptsGuestEntries() && auth()->guest())
        <div class="p-5">
            Please login to join this survey.
        </div>
    @else
        @foreach($survey->sections as $section)
            @include('survey::sections.single')
        @endforeach

        @foreach($survey->questions()->withoutSection()->get() as $question)
            @include('survey::questions.single')
        @endforeach

        @if($eligible)
            <x-jet-button class="mt-5">Invia risposte</x-jet-button>
        @endif
    @endif
</div>
