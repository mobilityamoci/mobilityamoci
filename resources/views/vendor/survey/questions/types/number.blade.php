@component('survey::questions.base', compact('question'))
    <input type="number" name="{{ $question->key }}" id="{{ $question->key }}" class="bg-gray-50 text-sm border border-gray-300 text-gray-900 rounded-md focus:ring-blue-500 focus:border-blue-500 block  p-2.5 "
           value="{{ $value ?? old($question->key) }}" {{ ($disabled ?? false) ? 'disabled' : '' }}>

    @slot('report')
        @if($includeResults ?? false)
            {{ number_format((new \MattDaneshvar\Survey\Utilities\Summary($question))->average()) }} (Average)
        @endif
    @endslot
@endcomponent
