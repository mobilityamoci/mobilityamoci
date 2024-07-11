<?php

namespace App\Charts;

use App\Models\School;
use App\Models\Section;
use App\Models\Student;
use App\Models\Trip;
use App\Services\QgisService;
use ArielMejiaDev\LarapexCharts\BarChart;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use Illuminate\Support\Collection;

class CaloriesStudentGraph
{
    protected LarapexChart $chart;
    protected School|null $school;
    protected Section|null $section;
    protected Collection|null $data;


    public function __construct(School|null $school, LarapexChart $chart, array $sections = null, $data = null)
    {
        $this->chart = $chart;
        $this->school = $school;
        $this->data = $data;
        if ($this->school)
            if ($sections)
                $this->section = Section::find($sections[0]);
            else
                $this->section = null;


    }

    public function build(): BarChart
    {
        if ($this->data) {
            $trip_ids = $this->data->pluck('trip_id');
            $trips = Trip::withTrashed()->whereIn('id', $trip_ids)->get();
            $arr = QgisService::calculatePollutionAndCaloriesForTripsSum($trips);
        } else {
            if ($this->school)
                if ($this->section)
                    $arr = QgisService::calculatePollutionAndCaloriesForSection($this->section);
                else
                    $arr = QgisService::calculatePollutionAndCaloriesForSchool($this->school);
            else
                $arr = QgisService::calculatePollutionAndCaloriesForAllSchools();
        }

        return $this->chart->barChart()
            ->setTitle('Calorie (KCal/anno)')
            ->addData('', [$arr['kcal_piedi'], $arr['kcal_bici']])
            ->setColors(['#00E396'])
            ->setGrid()
            ->setXAxis(['KCal a piedi', 'KCal in bici']);
    }
}
