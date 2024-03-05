<?php

namespace App\Charts;

use App\Models\School;
use App\Models\Student;
use App\Models\Transport;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use ArielMejiaDev\LarapexCharts\PieChart;
use Illuminate\Database\Eloquent\Builder;

class SchoolTypeOfTransportStudentChart
{
    protected $chart;
    protected School $school;
    protected $sections;

    public function __construct(School $school, LarapexChart $chart)
    {
        $this->chart = $chart;
        $this->school = $school;
        $this->sections = $this->school->sections->pluck('id')->toArray();
    }

    public function build(): PieChart
    {

        $test = Student::whereIn('section_id', $this->sections)->whereHas('trip1', function (Builder $query) {
            $query->where('transport_1', Transport::PIEDI);
        })->count();

        $test2 = Student::whereIn('section_id', $this->sections)->whereHas('trip1', function (Builder $query) {
            $query->where('transport_1', Transport::BUS_COMUNALE);
        })->count();

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
                        $query->where('transport_1', Transport::AUTO_3);
                    })->count(),
                    Student::whereIn('section_id', $this->sections)->whereHas('trip1', function (Builder $query) {
                        $query->where('transport_1', Transport::AUTO_2);
                    })->count(),
                    Student::whereIn('section_id', $this->sections)->whereHas('trip1', function (Builder $query) {
                        $query->where('transport_1', Transport::AUTO);
                    })->count()
                ]
            )
            ->setLabels(['A Piedi', 'In Bici', 'Bus Comunale','Auto Condivisa 3+', 'Auto condivisa','Auto' ]);
    }
}
