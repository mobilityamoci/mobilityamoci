<div>

    <ul class="m-3">
        @foreach($this->selectedSurvey->questions as $question)
            <li class="text-xl">
                <b>Domanda {{$loop->iteration}}:</b> "{{$question->content}}"
            </li>
        @endforeach
    </ul>

    @include('components.surveys-entries-table', ['survey' => $this->selectedSurvey])

    <hr>
</div>
