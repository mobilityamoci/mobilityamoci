<?php

namespace App\Http\Livewire;

use Livewire\Component;

class StudentNavigationMenu extends Component
{
    public function render()
    {
        return view('livewire.student-navigation-menu');
    }

    public function getSurveysCountProperty()
    {
        return \Auth::user()->student->surveysToSubmit()->count();
    }
}
