<div x-data="{}" style="width: 100%; height: 90vh">
        {!! $component !!}
        @livewire($component, ['surveyId' => $selectedSurveyId], key(random_int(-999,999)) )
</div>
