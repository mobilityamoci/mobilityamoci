<?php

namespace App\Charts;

use App\Models\School;
use App\Models\Student;
use App\Models\Transport;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use ArielMejiaDev\LarapexCharts\PieChart;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;

class TypeOfTransportStudentChart
{
    protected LarapexChart $chart;
    protected School|null $school;
    protected array $sections;
    protected Collection|null $data;

    public function __construct(School|null $school, LarapexChart $chart, array $sections = null, $data = null)
    {
        $this->chart = $chart;
        $this->school = $school;
        if ($this->school) {

            if ($sections)
                $this->sections = $sections;
            else
                $this->sections = $this->school->sections->pluck('id')->toArray();
        }
        $this->data = $data;
    }

    public function build(): PieChart
    {
        if ($this->data) {
            $ids = $this->data->pluck('student_id')->toArray();
            $query = Student::withTrashed()->whereIn('id', $ids);
        } else {
            if ($this->school)
                $query = Student::whereIn('section_id', $this->sections);
            else
                $query = Student::query();
        }


        $count = (clone $query)->whereHas('trip1')->count();

        return $this->chart->pieChart()
            ->setTitle('Mezzi usati.')
            ->setSubtitle("Calcoli effettuati su $count studenti.")
            ->addData(
                [
                    (clone $query)->whereHas('trip1', function (Builder $q) {
                        $q->where('transport_1', Transport::PIEDI);
                    })->count(),
                    (clone $query)->whereHas('trip1', function (Builder $q) {
                        $q->where('transport_1', Transport::BICICLETTA);
                    })->count(),
                    (clone $query)->whereHas('trip1', function (Builder $q) {
                        $q->where('transport_1', Transport::BUS_COMUNALE);
                    })->count(),
                    (clone $query)->whereHas('trip1', function (Builder $q) {
                        $q->where('transport_1', Transport::AUTO);
                    })->count(),
                ]
            )
            ->setDataLabels()
            ->setColors(['#13B647', '#A6CEE3', '#CD4EFF', '#DF2238'])
            ->setLabels(['A Piedi', 'In Bicicletta', 'Bus Comunale', 'Auto']);
    }
}
