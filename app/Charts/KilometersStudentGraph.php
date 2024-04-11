<?php

namespace App\Charts;

use App\Models\School;
use App\Models\Section;
use App\Services\QgisService;
use ArielMejiaDev\LarapexCharts\LarapexChart;

class KilometersStudentGraph
{
    protected LarapexChart $chart;
    protected School $school;
    protected Section|null $section;
    public function __construct(School $school,LarapexChart $chart, array $sections = null)
    {
        $this->chart = $chart;
        $this->school = $school;
        if ($sections)
            $this->section = Section::find($sections[0]);
        else
            $this->section = null;
    }

    public function build(): \ArielMejiaDev\LarapexCharts\LineChart
    {
        if ($this->section)
            list($auto, $piedi, $bici, $bus) = QgisService::calculateDistanceForTransport($this->section->trips);
        else
            list($auto, $piedi, $bici, $bus) = QgisService::calculateDistanceForTransport($this->school->trips);


        return $this->chart->lineChart()
            ->setTitle('Sales during 2021.')
            ->setSubtitle('Physical sales vs Digital sales.')
            ->addData('Physical sales', [40, 93, 35, 42, 18, 82])
            ->addData('Digital sales', [70, 29, 77, 28, 55, 45])
            ->setXAxis(['January', 'February', 'March', 'April', 'May', 'June']);
    }
}
