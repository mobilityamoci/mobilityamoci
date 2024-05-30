<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SchoolsSelect extends Component
{

    public $schoolChangedTrigger;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($schoolChangedTrigger = false)
    {
        $this->schoolChangedTrigger = $schoolChangedTrigger;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.schools-select', ['schoolChangeTrigger' => $this->schoolChangedTrigger]);
    }
}
