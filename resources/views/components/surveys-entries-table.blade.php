@props(['showQuestionsContent'=>false])

<table class="my-table table-auto mt-10">
    <thead class="my-header">
    <tr>
        <th class="my-th">Studente</th>
        @foreach($survey->questions as $question)
            @if($showQuestionsContent)
                <th class="my-th">{{$question->content}}</th>
            @else
                <th class="my-th">{{$loop->iteration}}</th>
            @endif
        @endforeach
    </tr>
    </thead>
    <tbody>
    @foreach($survey->entries as $entry)
        <tr>
            <td class="my-th">
                @hasanyrole(getCanSeeNameRoles())
                    {{$entry->student->fullName()}}
                @else
                    {{$entry->student->id}}
                @endhasanyrole
            </td>
            @foreach($entry->answers as $answer)
                <td class="my-th">{{$answer->value}}</td>
            @endforeach
        </tr>
    @endforeach
    </tbody>
</table>
