<div class="p-4 border-b-2">
    @include(view()->exists("survey::questions.types.{$question->type}")
        ? "survey::questions.types.{$question->type}"
        : "survey::questions.types.text",[
            'disabled' => !($eligible ?? true),
            'value' => $lastEntry ? $lastEntry->answerFor($question) : null,
            'includeResults' => false
        ]
    )
</div>
