<?php

namespace App\Exports;

use App\Services\QgisService;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class StatisticsSectionExport implements FromView, ShouldAutoSize,WithTitle
{

    public $trips;
    public ?string $filename;

    public function __construct($trips, $filename = null)
    {
        $this->trips = $trips;
        $this->filename = $filename;
    }


    public function view(): View
    {
        $res = QgisService::calculatePollutionAndCaloriesForTrips($this->trips);

        return view('exports.statistic-export', compact('res'));
    }

    public function title(): string
    {
        return $this->filename ?? 'Worksheet';
    }
}
