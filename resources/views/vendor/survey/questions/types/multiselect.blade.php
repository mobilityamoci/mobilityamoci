@component('survey::questions.base', compact('question'))
    @foreach ($question->options as $option)
        <div class="custom-control custom-checkbox">
            <input type="checkbox"
                   name="{{ $question->key }}[]"
                   id="{{ $question->key . '-' . Str::slug($option) }}"
                   value="{{ $option }}"
                   class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                    {{ ($value ?? old($question->key)) == $option ? 'checked' : '' }}
                    {{ ($disabled ?? false) ? 'disabled' : '' }}
            >
            <label class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300"
                   for="{{ $question->key . '-' . Str::slug($option) }}">{{ $option }}
            </label>
        </div>
    @endforeach
@endcomponent
