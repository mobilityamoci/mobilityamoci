@component('survey::questions.base', compact('question'))
    <input type="text" name="{{ $question->key }}" id="{{ $question->key }}"
           class="bg-gray-50 text-sm border border-gray-300 text-gray-900 rounded-md focus:ring-blue-500 focus:border-blue-500 block  p-2
           value="{{ $value ?? old($question->key) }}" {{ ($disabled ?? false) ? 'disabled' : '' }}>
@endcomponent
