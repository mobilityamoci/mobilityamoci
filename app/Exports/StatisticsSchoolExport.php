<?php

namespace App\Exports;

use App\Models\School;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class StatisticsSchoolExport implements WithMultipleSheets
{

    protected School $school;

    public function __construct($school)
    {
        $this->school = $school;
    }

    public function sheets(): array
    {
        $sheets = [];
        $sheets[$this->school->name] = new StatisticsSectionExport($this->school->trips, $this->school->name);

        $sections = $this->school->sections;
        foreach ($sections as $section) {
            if ($section->trips)
                $sheets[$section->name] = new StatisticsSectionExport($section->trips, $section->name);
        }

        return $sheets;
    }
}
