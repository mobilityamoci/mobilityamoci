<?php

namespace App\Exports;

use App\Models\Survey;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SurveyEntriesExport implements FromView, ShouldAutoSize
{

    private Survey $survey;

    public function __construct(Survey $survey)
    {
        $this->survey = $survey;
    }

    public function view(): View
    {
        return view('components.surveys-entries-table', ['survey' => $this->survey, 'showQuestionsContent' => true]);
    }
}
