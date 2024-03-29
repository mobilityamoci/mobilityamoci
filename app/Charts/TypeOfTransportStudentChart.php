<?php

namespace App\Charts;

use App\Models\School;
use App\Models\Student;
use App\Models\Transport;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use ArielMejiaDev\LarapexCharts\PieChart;
use Illuminate\Database\Eloquent\Builder;

class TypeOfTransportStudentChart
{
    protected LarapexChart $chart;
    protected School $school;
    protected array $sections;

    public function __construct(School $school, LarapexChart $chart, array $sections = null)
    {
        $this->chart = $chart;
        $this->school = $school;
        if ($sections)
            $this->sections = $sections;
        else
            $this->sections = $this->school->sections->pluck('id')->toArray();
    }

    public function build(): PieChart
    {
        return $this->chart->pieChart()
            ->setTitle('Mezzi usati dai ragazzi.')
            ->addData(
                [
                    Student::whereIn('section_id', $this->sections)->whereHas('trip1', function (Builder $query) {
                        $query->where('transport_1', Transport::PIEDI);
                    })->count(),
                    Student::whereIn('section_id', $this->sections)->whereHas('trip1', function (Builder $query) {
                        $query->where('transport_1', Transport::BICICLETTA);
                    })->count(),
                    Student::whereIn('section_id', $this->sections)->whereHas('trip1', function (Builder $query) {
                        $query->where('transport_1', Transport::BUS_COMUNALE);
                    })->count(),
                    Student::whereIn('section_id', $this->sections)->whereHas('trip1', function (Builder $query) {
                        $query->where('transport_1', Transport::AUTO);
                    })->count(),
                ]
            )
            ->setColors(['#13B647','#A6CEE3','#CD4EFF','#DF2238'])
            ->setLabels(['A Piedi', 'In Bici', 'Bus Comunale', 'Auto']);
    }
}
