<?php

namespace App\Charts;

use App\Models\School;
use App\Models\Section;
use App\Services\QgisService;
use ArielMejiaDev\LarapexCharts\BarChart;
use ArielMejiaDev\LarapexCharts\LarapexChart;

class PollutionStudentGraph
{
    protected LarapexChart $chart;
    protected School $school;
    protected Section|null $section;

    public function __construct(School $school, LarapexChart $chart, array $sections = null)
    {
        $this->chart = $chart;
        $this->school = $school;
        if ($sections)
            $this->section = Section::find($sections[0]);
        else
            $this->section = null;
    }

    public function build()
    {
        if ($this->section)
            $arr = QgisService::calculatePollutionAndCaloriesForSection($this->section);
        else
            $arr = QgisService::calculatePollutionAndCaloriesForSchool($this->school);
        return $this->chart->barChart()
            ->setTitle('Inquinanti (g/anno)')
            ->addData('Inquinanti',[$arr['carburante'], $arr['co2'],$arr['co'],$arr['nox'],$arr['pm10']])
            ->setGrid()
            ->setLabels(['Carburante','CO2', 'CO','NOX','PM10']);
    }
}
