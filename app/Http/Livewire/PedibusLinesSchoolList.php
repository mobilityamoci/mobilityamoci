<?php

namespace App\Http\Livewire;

use App\Models\School;
use App\Traits\HasSelectedSchool;
use Livewire\Component;

class PedibusLinesSchoolList extends Component
{

    use HasSelectedSchool;

    protected $queryString = [
        'selectedSchoolId' => ['except' => 1, 'as' => 'scuola'],
    ];

    public function render()
    {
        return view('livewire.pedibus-lines-school-list');
    }

    public function getCenterPointProperty()
    {
        return $this->selectedSchool->centerPoints()[0];
    }

    public function getMarkersProperty()
    {
        $arr = [];
        foreach ($this->selectedSchool->centerPoints() as $centerPoint) {
            $centerPoint[] = array_merge($centerPoint);
        }

        return $arr;
    }

    public function getSchoolsProperty()
    {
        return School::where('has_pedibus', true)->get();
    }

    public function getPedibusLinesProperty()
    {
        if ($this->selectedSchool)
            return $this->selectedSchool->pedibusLines;
        else
            return [];
    }

    public function schoolChanged()
    {
        $this->dispatchBrowserEvent('rerender-map');
    }

}
